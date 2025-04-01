package fr.univamu.iut.panier;

public class ProduitPanier {
    protected String id;
    protected String panierId;
    protected String produitId;
    protected String nom;
    protected double prix;
    protected double quantite;
    protected String unite;

    public ProduitPanier() {
    }

    public ProduitPanier(String id, String panierId, String produitId, String nom, double prix, double quantite, String unite) {
        this.id = id;
        this.panierId = panierId;
        this.produitId = produitId;
        this.nom = nom;
        this.prix = prix;
        this.quantite = quantite;
        this.unite = unite;
    }

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public String getPanierId() {
        return panierId;
    }

    public void setPanierId(String panierId) {
        this.panierId = panierId;
    }

    public String getProduitId() {
        return produitId;
    }

    public void setProduitId(String produitId) {
        this.produitId = produitId;
    }

    public String getNom() {
        return nom;
    }

    public void setNom(String nom) {
        this.nom = nom;
    }

    public double getPrix() {
        return prix;
    }

    public void setPrix(double prix) {
        this.prix = prix;
    }

    public double getQuantite() {
        return quantite;
    }

    public void setQuantite(double quantite) {
        this.quantite = quantite;
    }

    public String getUnite() {
        return unite;
    }

    public void setUnite(String unite) {
        this.unite = unite;
    }

    @Override
    public String toString() {
        return "ProduitPanier [id=" + id + ", panierId=" + panierId + ", produitId=" + produitId +
                ", nom=" + nom + ", prix=" + prix + ", quantite=" + quantite +
                ", unite=" + unite + "]";
    }
}