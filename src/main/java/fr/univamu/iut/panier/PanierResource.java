package fr.univamu.iut.panier;

import jakarta.inject.Inject;
import jakarta.ws.rs.*;
import jakarta.ws.rs.core.MediaType;
import jakarta.ws.rs.core.Response;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;
import java.util.UUID;

/**
 * API REST pour la gestion des paniers
 */
@Path("/paniers")
@Produces(MediaType.APPLICATION_JSON)
public class PanierResource {

    @Inject
    private PanierRepositoryInterface panierRepository;

    @Inject
    private ProduitsUtilisateursClient produitsClient;


    /**
     * Constructeur par défaut
     */
    public PanierResource() {}

    /**
     * Constructeur qui initialise le repository par injection CDI
     */
    public PanierResource(PanierRepositoryInterface panierRepository) {
        this.panierRepository = panierRepository;
    }

    /**
     * Récupère tous les paniers
     * @return Liste de tous les paniers
     */
    @GET
    public Response getAllPaniers() {
        try {
            ArrayList<Panier> paniers = panierRepository.getAllPaniers();
            return Response.ok(paniers).build();
        } catch (Exception e) {
            return Response.status(Response.Status.INTERNAL_SERVER_ERROR)
                    .entity("Erreur lors de la récupération des paniers: " + e.getMessage())
                    .build();
        }
    }

    /**
     * Récupère tous les produits disponibles
     * @return Liste de tous les produits
     */
    @GET
    @Path("/produits")
    public Response getAllProduits() {
        try {
            List<Produit> produits = produitsClient.getAllProduits();
            if (produits == null) {
                return Response.status(Response.Status.SERVICE_UNAVAILABLE)
                        .entity("Service de produits indisponible")
                        .build();
            }
            return Response.ok(produits).build();
        } catch (Exception e) {
            return Response.status(Response.Status.INTERNAL_SERVER_ERROR)
                    .entity("Erreur lors de la récupération des produits: " + e.getMessage())
                    .build();
        }
    }

    /**
     * Récupère les paniers validés
     * @return Liste des paniers validés
     */
    @GET
    @Path("/valides")
    public Response getValidPaniers() {
        try {
            ArrayList<Panier> paniers = panierRepository.getPaniersValides();
            return Response.ok(paniers).build();
        } catch (Exception e) {
            return Response.status(Response.Status.INTERNAL_SERVER_ERROR)
                    .entity("Erreur lors de la récupération des paniers validés: " + e.getMessage())
                    .build();
        }
    }

    /**
     * Récupère les paniers non validés
     * @return Liste des paniers non validés
     */
    @GET
    @Path("/non-valides")
    public Response getNonValidPaniers() {
        try {
            ArrayList<Panier> paniers = panierRepository.getPaniersNonValides();
            return Response.ok(paniers).build();
        } catch (Exception e) {
            return Response.status(Response.Status.INTERNAL_SERVER_ERROR)
                    .entity("Erreur lors de la récupération des paniers non validés: " + e.getMessage())
                    .build();
        }
    }

    /**
     * Récupère un panier par son identifiant
     * @param id Identifiant du panier
     * @return Le panier correspondant
     */
    @GET
    @Path("/{id}")
    public Response getPanierById(@PathParam("id") String id) {
        try {
            Panier panier = panierRepository.getPanier(id);
            if (panier == null) {
                return Response.status(Response.Status.NOT_FOUND)
                        .entity("Panier non trouvé avec l'identifiant: " + id)
                        .build();
            }
            return Response.ok(panier).build();
        } catch (Exception e) {
            return Response.status(Response.Status.INTERNAL_SERVER_ERROR)
                    .entity("Erreur lors de la récupération du panier: " + e.getMessage())
                    .build();
        }
    }

    /**
     * Crée un nouveau panier
     * @param panier Le panier à créer
     * @return Le panier créé avec son identifiant
     */
    @POST
    @Consumes(MediaType.APPLICATION_JSON)
    public Response createPanier(Panier panier) {
        try {
            // Générer un identifiant unique si non fourni
            if (panier.getId() == null || panier.getId().isEmpty()) {
                panier.setId(UUID.randomUUID().toString());
            }

            // S'assurer que les dates sont définies
            if (panier.getDateMiseAJour() == null) {
                panier.setDateMiseAJour(new Date());
            }

            boolean success = panierRepository.addPanier(
                    panier.getId(),
                    panier.getPrixTotal(),
                    panier.getDateRetrait(),
                    panier.getRelais(),
                    panier.getNom(),
                    panier.getDescription(),
                    panier.isValide(),
                    panier.getDateMiseAJour()
            );

            if (!success) {
                return Response.status(Response.Status.BAD_REQUEST)
                        .entity("Impossible de créer le panier")
                        .build();
            }

            return Response.status(Response.Status.CREATED)
                    .entity(panierRepository.getPanier(panier.getId()))
                    .build();
        } catch (Exception e) {
            return Response.status(Response.Status.INTERNAL_SERVER_ERROR)
                    .entity("Erreur lors de la création du panier: " + e.getMessage())
                    .build();
        }
    }

    /**
     * Met à jour un panier existant
     * @param id Identifiant du panier à mettre à jour
     * @param panier Les nouvelles informations du panier
     * @return Le panier mis à jour
     */
    @PUT
    @Path("/{id}")
    @Consumes(MediaType.APPLICATION_JSON)
    public Response updatePanier(@PathParam("id") String id, Panier panier) {
        try {
            // Vérifier que le panier existe
            Panier existingPanier = panierRepository.getPanier(id);
            if (existingPanier == null) {
                return Response.status(Response.Status.NOT_FOUND)
                        .entity("Panier non trouvé avec l'identifiant: " + id)
                        .build();
            }

            // Mettre à jour la date de dernière modification
            Date dateMaj = new Date();
            panier.setDateMiseAJour(dateMaj);

            boolean success = panierRepository.updatePanier(
                    id,
                    panier.getPrixTotal(),
                    panier.getDateRetrait(),
                    panier.getRelais(),
                    panier.getNom(),
                    panier.getDescription(),
                    panier.isValide(),
                    dateMaj
            );

            if (!success) {
                return Response.status(Response.Status.BAD_REQUEST)
                        .entity("Impossible de mettre à jour le panier")
                        .build();
            }

            return Response.ok(panierRepository.getPanier(id)).build();
        } catch (Exception e) {
            return Response.status(Response.Status.INTERNAL_SERVER_ERROR)
                    .entity("Erreur lors de la mise à jour du panier: " + e.getMessage())
                    .build();
        }
    }

    /**
     * Supprime un panier
     * @param id Identifiant du panier à supprimer
     * @return Confirmation de suppression
     */
    @DELETE
    @Path("/{id}")
    public Response deletePanier(@PathParam("id") String id) {
        try {
            // Vérifier que le panier existe
            Panier existingPanier = panierRepository.getPanier(id);
            if (existingPanier == null) {
                return Response.status(Response.Status.NOT_FOUND)
                        .entity("Panier non trouvé avec l'identifiant: " + id)
                        .build();
            }

            boolean success = panierRepository.deletePanier(id);

            if (!success) {
                return Response.status(Response.Status.BAD_REQUEST)
                        .entity("Impossible de supprimer le panier")
                        .build();
            }

            return Response.status(Response.Status.NO_CONTENT).build();
        } catch (Exception e) {
            return Response.status(Response.Status.INTERNAL_SERVER_ERROR)
                    .entity("Erreur lors de la suppression du panier: " + e.getMessage())
                    .build();
        }
    }

    /**
     * Récupère les produits d'un panier
     * @param id Identifiant du panier
     * @return Liste des produits du panier
     */
    @GET
    @Path("/{id}/produits")
    public Response getProduitsPanier(@PathParam("id") String id) {
        try {
            // Vérifier que le panier existe
            Panier existingPanier = panierRepository.getPanier(id);
            if (existingPanier == null) {
                return Response.status(Response.Status.NOT_FOUND)
                        .entity("Panier non trouvé avec l'identifiant: " + id)
                        .build();
            }

            ArrayList<ProduitPanier> produits = panierRepository.getProduitsPanier(id);
            return Response.ok(produits).build();
        } catch (Exception e) {
            return Response.status(Response.Status.INTERNAL_SERVER_ERROR)
                    .entity("Erreur lors de la récupération des produits du panier: " + e.getMessage())
                    .build();
        }
    }

    /**
     * Ajoute un produit à un panier
     * @param id Identifiant du panier
     * @param produit Le produit à ajouter
     * @return Confirmation d'ajout
     */
    @POST
    @Path("/{id}/produits")
    @Consumes(MediaType.APPLICATION_JSON)
    public Response addProduitToPanier(@PathParam("id") String id, ProduitPanier produit) {
        try {

            // Vérifier que le panier existe
            Panier existingPanier = panierRepository.getPanier(id);
            if (existingPanier == null) {
                return Response.status(Response.Status.NOT_FOUND)
                        .entity("Panier non trouvé avec l'identifiant: " + id)
                        .build();
            }

            // Générer un id pour le produit s'il n'en a pas
            if (produit.getId() == null || produit.getId().isEmpty()) {
                produit.setId(UUID.randomUUID().toString());
            }

            // Assignation du panier au produit
            produit.setPanierId(id);

            boolean success = panierRepository.addProduitPanier(
                    produit.getId(),
                    id,
                    produit.getProduitId(),
                    produit.getNom(),
                    produit.getPrix(),
                    produit.getQuantite(),
                    produit.getUnite()
            );

            if (!success) {
                return Response.status(Response.Status.BAD_REQUEST)
                        .entity("Impossible d'ajouter le produit au panier")
                        .build();
            }

            // Retourner le panier mis à jour
            return Response.status(Response.Status.CREATED)
                    .entity(panierRepository.getPanier(id))
                    .build();
        } catch (Exception e) {
            return Response.status(Response.Status.INTERNAL_SERVER_ERROR)
                    .entity("Erreur lors de l'ajout du produit au panier: " + e.getMessage())
                    .build();
        }
    }

    /**
     * Supprime un produit d'un panier
     * @param panierId Identifiant du panier
     * @param produitId Identifiant du produit dans le panier
     * @return Confirmation de suppression
     */
    @DELETE
    @Path("/{panierId}/produits/{produitId}")
    public Response removeProduitFromPanier(
            @PathParam("panierId") String panierId,
            @PathParam("produitId") String produitId) {
        try {
            // Vérifier que le panier existe
            Panier existingPanier = panierRepository.getPanier(panierId);
            if (existingPanier == null) {
                return Response.status(Response.Status.NOT_FOUND)
                        .entity("Panier non trouvé avec l'identifiant: " + panierId)
                        .build();
            }

            // Vérifier que le produit existe dans le panier
            boolean produitExiste = false;
            for (ProduitPanier p : existingPanier.getProduits()) {
                if (p.getId().equals(produitId)) {
                    produitExiste = true;
                    break;
                }
            }

            if (!produitExiste) {
                return Response.status(Response.Status.NOT_FOUND)
                        .entity("Produit non trouvé dans le panier avec l'identifiant: " + produitId)
                        .build();
            }

            boolean success = panierRepository.deleteProduitPanier(produitId);

            if (!success) {
                return Response.status(Response.Status.BAD_REQUEST)
                        .entity("Impossible de supprimer le produit du panier")
                        .build();
            }

            return Response.status(Response.Status.NO_CONTENT).build();
        } catch (Exception e) {
            return Response.status(Response.Status.INTERNAL_SERVER_ERROR)
                    .entity("Erreur lors de la suppression du produit du panier: " + e.getMessage())
                    .build();
        }
    }

    /**
     * Met à jour la quantité d'un produit dans un panier
     * @param panierId Identifiant du panier
     * @param produitId Identifiant du produit dans le panier
     * @param quantite Objet contenant la nouvelle quantité
     * @return Confirmation de mise à jour
     */
    @PUT
    @Path("/{panierId}/produits/{produitId}")
    @Consumes(MediaType.APPLICATION_JSON)
    public Response updateProduitQuantite(
            @PathParam("panierId") String panierId,
            @PathParam("produitId") String produitId,
            ProduitQuantite quantite) {
        try {
            // Vérifier que le panier existe
            Panier existingPanier = panierRepository.getPanier(panierId);
            if (existingPanier == null) {
                return Response.status(Response.Status.NOT_FOUND)
                        .entity("Panier non trouvé avec l'identifiant: " + panierId)
                        .build();
            }

            // Vérifier que le produit existe dans le panier
            boolean produitExiste = false;
            for (ProduitPanier p : existingPanier.getProduits()) {
                if (p.getId().equals(produitId)) {
                    produitExiste = true;
                    break;
                }
            }

            if (!produitExiste) {
                return Response.status(Response.Status.NOT_FOUND)
                        .entity("Produit non trouvé dans le panier avec l'identifiant: " + produitId)
                        .build();
            }

            boolean success = panierRepository.updateProduitPanier(produitId, quantite.getQuantite());

            if (!success) {
                return Response.status(Response.Status.BAD_REQUEST)
                        .entity("Impossible de mettre à jour la quantité du produit")
                        .build();
            }

            return Response.ok(panierRepository.getPanier(panierId)).build();
        } catch (Exception e) {
            return Response.status(Response.Status.INTERNAL_SERVER_ERROR)
                    .entity("Erreur lors de la mise à jour de la quantité du produit: " + e.getMessage())
                    .build();
        }
    }

    /**
     * Valide un panier
     * @param id Identifiant du panier
     * @return Confirmation de validation
     */
    @PUT
    @Path("/{id}/valider")
    public Response validerPanier(@PathParam("id") String id) {
        try {
            // Vérifier que le panier existe
            Panier existingPanier = panierRepository.getPanier(id);
            if (existingPanier == null) {
                return Response.status(Response.Status.NOT_FOUND)
                        .entity("Panier non trouvé avec l'identifiant: " + id)
                        .build();
            }

            // Mettre à jour le statut et la date de dernière modification
            existingPanier.setValide(true);
            existingPanier.setDateMiseAJour(new Date());

            boolean success = panierRepository.updatePanier(
                    id,
                    existingPanier.getPrixTotal(),
                    existingPanier.getDateRetrait(),
                    existingPanier.getRelais(),
                    existingPanier.getNom(),
                    existingPanier.getDescription(),
                    true,
                    existingPanier.getDateMiseAJour()
            );

            if (!success) {
                return Response.status(Response.Status.BAD_REQUEST)
                        .entity("Impossible de valider le panier")
                        .build();
            }

            return Response.ok(panierRepository.getPanier(id)).build();
        } catch (Exception e) {
            return Response.status(Response.Status.INTERNAL_SERVER_ERROR)
                    .entity("Erreur lors de la validation du panier: " + e.getMessage())
                    .build();
        }
    }

    /**
     * Classe utilitaire pour recevoir la mise à jour de quantité
     */
    public static class ProduitQuantite {
        private double quantite;

        public ProduitQuantite() {}

        public double getQuantite() {
            return quantite;
        }

        public void setQuantite(double quantite) {
            this.quantite = quantite;
        }

    }


}