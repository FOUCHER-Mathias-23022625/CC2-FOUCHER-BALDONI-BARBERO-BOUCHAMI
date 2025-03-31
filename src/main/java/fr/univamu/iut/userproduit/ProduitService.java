package fr.univamu.iut.userproduit;

import jakarta.json.bind.Jsonb;
import jakarta.json.bind.JsonbBuilder;
import java.util.ArrayList;

public class ProduitService {
    protected ProduitRepositoryInterface produitRepo;

    public ProduitService(ProduitRepositoryInterface produitRepo) {
        this.produitRepo = produitRepo;
    }

    /**
     * Méthode retournant au format JSON les informations sur tous les produits
     * @return une chaîne de caractères contenant les informations au format JSON
     */
    public String getAllProduitsJSON() {
        ArrayList<Produit> allProduits = produitRepo.getAllProduits();

        String result = null;
        try (Jsonb jsonb = JsonbBuilder.create()) {
            result = jsonb.toJson(allProduits);
        } catch (Exception e) {
            System.err.println(e.getMessage());
        }

        return result;
    }

    /**
     * Méthode retournant au format JSON les informations sur un produit recherché
     * @param id l'identifiant du produit recherché
     * @return une chaîne de caractères contenant les informations au format JSON
     */
    public String getProduitJSON(String id) {
        String result = null;
        Produit produit = produitRepo.getProduit(id);

        if (produit != null) {
            try (Jsonb jsonb = JsonbBuilder.create()) {
                result = jsonb.toJson(produit);
            } catch (Exception e) {
                System.err.println(e.getMessage());
            }
        }

        return result;
    }

    /**
     * Méthode permettant de mettre à jour un produit
     * @param id l'identifiant du produit à mettre à jour
     * @param produit le produit avec les nouvelles informations
     * @return true si la mise à jour a été effectuée, false sinon
     */
    public boolean updateProduit(String id, Produit produit) {
        return produitRepo.updateProduit(id, produit.getNom(), produit.getPrix(), produit.getQuantite(), produit.getUnite());
    }

    /**
     * Méthode permettant d'ajouter un nouveau produit
     * @param produit le produit à ajouter
     * @return true si l'ajout a été effectué, false sinon
     */
    public boolean addProduit(Produit produit) {
        return produitRepo.addProduit(produit.getId(), produit.getNom(), produit.getPrix(), produit.getQuantite(), produit.getUnite());
    }

    /**
     * Méthode permettant de supprimer un produit
     * @param id l'identifiant du produit à supprimer
     * @return true si la suppression a été effectuée, false sinon
     */
    public boolean deleteProduit(String id) {
        return produitRepo.deleteProduit(id);
    }
}