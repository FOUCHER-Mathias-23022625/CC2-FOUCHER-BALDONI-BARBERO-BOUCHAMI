package fr.univamu.iut.apicommandes;

import jakarta.enterprise.context.ApplicationScoped;
import jakarta.inject.Inject;
import jakarta.ws.rs.*;
import jakarta.ws.rs.core.Response;
import jakarta.ws.rs.core.MediaType;

import java.util.List;

/**
 * Classe ressource pour gérer les requêtes HTTP liées aux commandes.
 */
@Path("/commandes")
@ApplicationScoped
public class CommandeResource {

    private CommandeService service;

    public CommandeResource() {
    }

    @Inject
    public CommandeResource(CommandeRepositoryInterface repo) {
        this.service = new CommandeService(repo);
    }

    public CommandeResource(CommandeService service) {
        this.service = service;
    }

    /**
     * Récupère toutes les commandes.
     *
     * @return une réponse contenant la liste de toutes les commandes
     */
    @GET
    @Produces(MediaType.APPLICATION_JSON)
    public Response getAllCommandes() {
        List<Commande> commandes = service.getAllCommandes();
        return Response.ok(commandes).build();
    }

    /**
     * Récupère une commande par son identifiant.
     *
     * @param reference l'identifiant de la commande
     * @return une réponse contenant la commande
     */
    @GET
    @Path("{reference}")
    @Produces(MediaType.APPLICATION_JSON)
    public Response getCommandeById(@PathParam("reference") int reference) {
        Commande commande = service.getCommandeById(reference);
        if (commande == null) {
            return Response.status(Response.Status.NOT_FOUND).entity("Commande non trouvée").build();
        }
        return Response.ok(commande).build();
    }

    /**
     * Ajoute une nouvelle commande.
     *
     * @param commande la commande à ajouter
     * @return une réponse indiquant le résultat de l'opération
     */
    @POST
    @Consumes(MediaType.APPLICATION_JSON)
    public Response addCommande(Commande commande) {
        if (service.addCommande(commande)) {
            return Response.status(Response.Status.CREATED).entity("Commande ajoutée").build();
        }
        return Response.status(Response.Status.BAD_REQUEST).entity("Erreur lors de l'ajout de la commande").build();
    }

    /**
     * Met à jour une commande existante.
     *
     * @param reference l'identifiant de la commande à mettre à jour
     * @param commande la commande mise à jour
     * @return une réponse indiquant le résultat de l'opération
     */
    @PUT
    @Path("{reference}")
    @Consumes(MediaType.APPLICATION_JSON)
    public Response updateCommande(@PathParam("reference") int reference, Commande commande) {
        if (!service.updateCommande(reference, commande)) {
            return Response.status(Response.Status.NOT_FOUND).entity("Commande non trouvée").build();
        }
        return Response.ok("Commande mise à jour").build();
    }

    /**
     * Supprime une commande par son identifiant.
     *
     * @param reference l'identifiant de la commande à supprimer
     * @return une réponse indiquant le résultat de l'opération
     */
    @DELETE
    @Path("{reference}")
    public Response deleteCommande(@PathParam("reference") int reference) {
        if (!service.removeCommande(reference)) {
            return Response.status(Response.Status.NOT_FOUND).entity("Commande non trouvée").build();
        }
        return Response.ok("Commande supprimée").build();
    }

    /**
     * Récupère le contenu d'une commande par son identifiant.
     *
     * @param reference l'identifiant de la commande
     * @return une réponse contenant le contenu de la commande
     */
    @GET
    @Path("{reference}/contenu")
    @Produces(MediaType.APPLICATION_JSON)
    public Response getCommandeContient(@PathParam("reference") int reference) {
        List<CommandeContient> contenu = service.getCommandeContient(reference);
        if (contenu == null || contenu.isEmpty()) {
            return Response.status(Response.Status.NOT_FOUND).entity("Aucun contenu trouvé pour cette commande").build();
        }
        return Response.ok(contenu).build();
    }

    /**
     * Ajoute du contenu à une commande.
     *
     * @param reference l'identifiant de la commande
     * @param commandeContient le contenu à ajouter
     * @return une réponse indiquant le résultat de l'opération
     */
    @POST
    @Path("{reference}/contenu")
    @Consumes(MediaType.APPLICATION_JSON)
    public Response addCommandeContient(@PathParam("reference") int reference, CommandeContient commandeContient) {
        commandeContient.setIdCommande(reference);
        if (!service.addCommandeContient(commandeContient)) {
            return Response.status(Response.Status.BAD_REQUEST).entity("Erreur lors de l'ajout du contenu").build();
        }
        return Response.status(Response.Status.CREATED).entity("Contenu ajouté").build();
    }

    /**
     * Met à jour le contenu d'une commande.
     *
     * @param reference l'identifiant de la commande
     * @param idPanier l'identifiant du panier
     * @param commandeContient le contenu mis à jour
     * @return une réponse indiquant le résultat de l'opération
     */
    @PUT
    @Path("{reference}/contenu/{idPanier}")
    @Consumes(MediaType.APPLICATION_JSON)
    public Response updateCommandeContient(@PathParam("reference") int reference,
                                           @PathParam("idPanier") int idPanier,
                                           CommandeContient commandeContient) {
        if (!service.updateCommandeContient(reference, idPanier, commandeContient.getQuantite())) {
            return Response.status(Response.Status.NOT_FOUND).entity("Contenu non trouvé").build();
        }
        return Response.ok("Contenu mis à jour").build();
    }

    /**
     * Supprime le contenu d'une commande.
     *
     * @param reference l'identifiant de la commande
     * @param idPanier l'identifiant du panier
     * @return une réponse indiquant le résultat de l'opération
     */
    @DELETE
    @Path("{reference}/contenu/{idPanier}")
    public Response deleteCommandeContient(@PathParam("reference") int reference,
                                           @PathParam("idPanier") int idPanier) {
        if (!service.removeCommandeContient(reference, idPanier)) {
            return Response.status(Response.Status.NOT_FOUND).entity("Contenu non trouvé").build();
        }
        return Response.ok("Contenu supprimé").build();
    }

    /**
     * Récupère le total d'une commande par son identifiant.
     *
     * @param reference l'identifiant de la commande
     * @return une réponse contenant le total de la commande
     */
    @GET
    @Path("{reference}/total")
    @Produces(MediaType.APPLICATION_JSON)
    public Response getCommandeTotal(@PathParam("reference") int reference) {
        double total = service.getCommandeTotal(reference);
        return Response.ok(total).build();
    }
}