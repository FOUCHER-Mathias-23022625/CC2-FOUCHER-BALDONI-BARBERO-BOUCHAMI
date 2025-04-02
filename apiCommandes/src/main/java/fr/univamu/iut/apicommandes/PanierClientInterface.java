package fr.univamu.iut.apicommandes;

import java.util.List;

/**
 * Interface pour interagir avec l'API Panier.
 */
public interface PanierClientInterface {
    /**
     * Récupère le prix total d'un panier par son identifiant.
     *
     * @param panierId l'identifiant du panier
     * @return le prix total du panier
     */
    double getPanierTotal(String panierId);

    /**
     * Récupère un panier par son identifiant.
     *
     * @param panierId l'identifiant du panier
     * @return le panier
     */
    Object getPanierById(String panierId);

    /**
     * Récupère les produits d'un panier par son identifiant.
     *
     * @param panierId l'identifiant du panier
     * @return une liste de produits dans le panier
     */
    List<Object> getPanierProduits(String panierId);

    /**
     * Ferme la connexion du client.
     */
    void close();
}