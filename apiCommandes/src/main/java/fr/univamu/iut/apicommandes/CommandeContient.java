package fr.univamu.iut.apicommandes;

/**
 * Représente le contenu d'une commande.
 */
public class CommandeContient {
    protected int idCommande;
    protected int idPanier;
    protected int quantite;

    /**
     * Constructeur avec paramètres.
     *
     * @param idCommande l'identifiant de la commande
     * @param idPanier l'identifiant du panier
     * @param quantite la quantité d'articles dans le panier
     */
    public CommandeContient(int idCommande, int idPanier, int quantite) {
        this.idCommande = idCommande;
        this.idPanier = idPanier;
        this.quantite = quantite;
    }

    /**
     * Constructeur par défaut.
     */
    public CommandeContient() {
    }

    // Getters et setters avec Javadoc

    public int getIdCommande() {
        return idCommande;
    }

    public void setIdCommande(int idCommande) {
        this.idCommande = idCommande;
    }

    public int getIdPanier() {
        return idPanier;
    }

    public void setIdPanier(int idPanier) {
        this.idPanier = idPanier;
    }

    public int getQuantite() {
        return quantite;
    }

    public void setQuantite(int quantite) {
        this.quantite = quantite;
    }

    @Override
    public String toString() {
        return "CommandeContient{" +
                "idCommande=" + idCommande +
                ", idPanier=" + idPanier +
                ", quantite=" + quantite +
                '}';
    }
}