package fr.univamu.iut.apicommandes;

public class CommandeContient {
    protected int idCommande;
    protected int idPanier;
    protected int quantite;

    public CommandeContient(int idCommande, int idPanier, int quantite) {
        this.idCommande = idCommande;
        this.idPanier = idPanier;
        this.quantite = quantite;
    }

    public CommandeContient() {
    }

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