package fr.univamu.iut.panier;

import jakarta.json.bind.Jsonb;
import jakarta.json.bind.JsonbBuilder;
import java.util.ArrayList;
import java.util.List;
import java.util.UUID;
import java.util.Date;

/**
 * Classe utilisée pour récupérer les informations nécessaires à la ressource
 * (permet de dissocier ressource et mode d'accès aux données)
 */
public class PanierService {

    /**
     * Objet permettant d'accéder au dépôt où sont stockées les informations sur les paniers
     */
    protected PanierRepositoryInterface panierRepo;

    /**
     * Constructeur permettant d'injecter l'accès aux données
     * @param panierRepo objet implémentant l'interface d'accès aux données
     */
    public PanierService(PanierRepositoryInterface panierRepo) {
        this.panierRepo = panierRepo;
    }

    /**
     * Méthode retournant les informations sur tous les paniers au format JSON
     * @return une chaîne de caractères contenant les informations au format JSON
     */
    public String getAllPaniersJSON() {
        List<Panier> allPaniers = panierRepo.getAllPaniers();
        String result = null;
        try (Jsonb jsonb = JsonbBuilder.create()) {
            result = jsonb.toJson(allPaniers);
        } catch (Exception e) {
            System.err.println(e.getMessage());
        }
        return result;
    }

    /**
     * Méthode retournant les informations sur les paniers valides au format JSON
     * @return une chaîne de caractères contenant les informations au format JSON
     */
    public String getPaniersValidesJSON() {
        ArrayList<Panier> paniers = panierRepo.getPaniersValides();
        String result = null;
        try (Jsonb jsonb = JsonbBuilder.create()) {
            result = jsonb.toJson(paniers);
        } catch (Exception e) {
            System.err.println(e.getMessage());
        }
        return result;
    }

    /**
     * Méthode retournant les informations sur les paniers non valides au format JSON
     * @return une chaîne de caractères contenant les informations au format JSON
     */
    public String getPaniersNonValidesJSON() {
        ArrayList<Panier> paniers = panierRepo.getPaniersNonValides();
        String result = null;
        try (Jsonb jsonb = JsonbBuilder.create()) {
            result = jsonb.toJson(paniers);
        } catch (Exception e) {
            System.err.println(e.getMessage());
        }
        return result;
    }

    /**
     * Méthode retournant au format JSON les informations sur un panier recherché
     * @param id l'identifiant du panier recherché
     * @return une chaîne de caractères contenant les informations au format JSON
     */
    public String getPanierJSON(String id) {
        String result = null;
        Panier myPanier = panierRepo.getPanier(id);

        if (myPanier != null) {
            try (Jsonb jsonb = JsonbBuilder.create()) {
                result = jsonb.toJson(myPanier);
            } catch (Exception e) {
                System.err.println(e.getMessage());
            }
        }
        return result;
    }

    /**
     * Méthode permettant de créer un nouveau panier
     * @return l'identifiant du panier créé ou null si la création a échoué
     */
    public String creerPanier() {
        String id = UUID.randomUUID().toString();
        Date currentDate = new Date();

        boolean success = panierRepo.addPanier(
                id,
                0.0, // Prix total initial à 0
                null, // Pas de date de retrait initialement
                null, // Pas de relais initialement
                "Nouveau panier", // Nom par défaut
                "", // Pas de description initialement
                false, // Non validé par défaut
                currentDate // Date de mise à jour actuelle
        );

        return success ? id : null;
    }

    /**
     * Méthode permettant de mettre à jour les informations d'un panier
     * @param id Identifiant du panier à mettre à jour
     * @param panier Les nouvelles informations du panier
     * @return true si le panier a pu être mis à jour, false sinon
     */
    public boolean updatePanier(String id, Panier panier) {
        // Mettre à jour la date de mise à jour
        panier.setDateMiseAJour(new Date());

        return panierRepo.updatePanier(
                id,
                panier.getPrixTotal(),
                panier.getDateRetrait(),
                panier.getRelais(),
                panier.getNom(),
                panier.getDescription(),
                panier.isValide(),
                panier.getDateMiseAJour()
        );
    }

    /**
     * Méthode permettant de supprimer un panier
     * @param id Identifiant du panier à supprimer
     * @return true si le panier a été supprimé avec succès, false sinon
     */
    public boolean supprimerPanier(String id) {
        return panierRepo.deletePanier(id);
    }

    /**
     * Méthode permettant d'ajouter un produit à un panier
     * @param panierId Identifiant du panier
     * @param produit Le produit à ajouter
     * @return true si le produit a été ajouté avec succès, false sinon
     */
    public boolean ajouterProduitPanier(String panierId, ProduitPanier produit) {
        if (produit.getId() == null || produit.getId().isEmpty()) {
            produit.setId(UUID.randomUUID().toString());
        }
        produit.setPanierId(panierId);

        return panierRepo.addProduitPanier(
                produit.getId(),
                panierId,
                produit.getProduitId(),
                produit.getNom(),
                produit.getPrix(),
                produit.getQuantite(),
                produit.getUnite()
        );
    }

    /**
     * Méthode permettant de mettre à jour la quantité d'un produit dans un panier
     * @param produitId Identifiant du produit
     * @param quantite Nouvelle quantité
     * @return true si la mise à jour a réussi, false sinon
     */
    public boolean updateQuantiteProduit(String produitId, double quantite) {
        return panierRepo.updateProduitPanier(produitId, quantite);
    }

    /**
     * Méthode permettant de supprimer un produit d'un panier
     * @param produitId Identifiant du produit à supprimer
     * @return true si le produit a été supprimé avec succès, false sinon
     */
    public boolean supprimerProduitPanier(String produitId) {
        return panierRepo.deleteProduitPanier(produitId);
    }

    /**
     * Méthode permettant de récupérer tous les produits d'un panier
     * @param panierId Identifiant du panier
     * @return Liste des produits dans le panier au format JSON
     */
    public String getProduitsPanierJSON(String panierId) {
        ArrayList<ProduitPanier> produits = panierRepo.getProduitsPanier(panierId);
        String result = null;
        try (Jsonb jsonb = JsonbBuilder.create()) {
            result = jsonb.toJson(produits);
        } catch (Exception e) {
            System.err.println(e.getMessage());
        }
        return result;
    }

    /**
     * Méthode permettant de valider un panier
     * @param panierId Identifiant du panier à valider
     * @return true si le panier a été validé avec succès, false sinon
     */
    public boolean validerPanier(String panierId) {
        Panier panier = panierRepo.getPanier(panierId);
        if (panier != null && !panier.isValide()) {
            panier.setValide(true);
            panier.setDateMiseAJour(new Date());
            return panierRepo.updatePanier(
                    panierId,
                    panier.getPrixTotal(),
                    panier.getDateRetrait(),
                    panier.getRelais(),
                    panier.getNom(),
                    panier.getDescription(),
                    true,
                    panier.getDateMiseAJour()
            );
        }
        return false;
    }

    /**
     * Ferme la connexion au repository
     */
    public void close() {
        if (panierRepo != null) {
            panierRepo.close();
        }
    }
}