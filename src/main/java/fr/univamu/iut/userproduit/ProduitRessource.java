package fr.univamu.iut.userproduit;

import jakarta.inject.Inject;
import jakarta.ws.rs.*;
import jakarta.ws.rs.core.MediaType;
import jakarta.ws.rs.core.Response;

/**
 * Ressource REST pour les opérations CRUD sur les produits
 */
@Path("/produit")
public class ProduitRessource {

    private ProduitService service;

    /**
     * Constructeur par défaut
     */
    public ProduitRessource() {}

    /**
     * Constructeur avec injection de dépendance
     * @param produitRepo le repository pour accéder aux données
     */
    @Inject
    public ProduitRessource(ProduitRepositoryInterface produitRepo) {
        this.service = new ProduitService(produitRepo);
    }

    /**
     * Constructeur avec service défini
     * @param service le service de gestion des produits
     */
    public ProduitRessource(ProduitService service) {
        this.service = service;
    }

    /**
     * Endpoint pour récupérer tous les produits
     * @return la liste des produits au format JSON
     */
    @GET
    @Produces(MediaType.APPLICATION_JSON)
    public String getAllProduits() {
        return service.getAllProduitsJSON();
    }

    /**
     * Endpoint pour récupérer un produit par son ID
     * @param id l'identifiant du produit recherché
     * @return les informations du produit au format JSON
     */
    @GET
    @Path("{id}")
    @Produces(MediaType.APPLICATION_JSON)
    public String getProduit(@PathParam("id") String id) {
        String result = service.getProduitJSON(id);

        if (result == null)
            throw new NotFoundException("Produit non trouvé avec l'ID: " + id);

        return result;
    }

    /**
     * Endpoint pour mettre à jour un produit existant
     * @param id l'identifiant du produit à mettre à jour
     * @param produit les nouvelles informations du produit
     * @return une réponse HTTP indiquant le succès ou l'échec
     */
    @PUT
    @Path("{id}")
    @Consumes(MediaType.APPLICATION_JSON)
    public Response updateProduit(@PathParam("id") String id, Produit produit) {
        if (!service.updateProduit(id, produit))
            throw new NotFoundException("Produit non trouvé avec l'ID: " + id);

        return Response.ok("Produit mis à jour").build();
    }

    /**
     * Endpoint pour ajouter un nouveau produit
     * @param produit le produit à ajouter
     * @return une réponse HTTP indiquant le succès ou l'échec
     */
    @POST
    @Consumes(MediaType.APPLICATION_JSON)
    public Response addProduit(Produit produit) {
        if (service.addProduit(produit))
            return Response.status(Response.Status.CREATED).entity("Produit créé").build();
        else
            return Response.status(Response.Status.BAD_REQUEST).entity("Échec de la création du produit").build();
    }

    /**
     * Endpoint pour supprimer un produit
     * @param id l'identifiant du produit à supprimer
     * @return une réponse HTTP indiquant le succès ou l'échec
     */
    @DELETE
    @Path("{id}")
    public Response deleteProduit(@PathParam("id") String id) {
        if (service.deleteProduit(id))
            return Response.ok("Produit supprimé").build();
        else
            throw new NotFoundException("Produit non trouvé avec l'ID: " + id);
    }
}