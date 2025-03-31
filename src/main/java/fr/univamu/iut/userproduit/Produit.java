package fr.univamu.iut.userproduit;

public class Produit {
    protected String id;
    protected String nom;
    protected double prix;
    protected double quantite;
    protected String unite; // "kg", "unite", "douzaine"

    public Produit() {
    }

    public Produit(String id, String nom, double prix, double quantite, String unite) {
        this.id = id;
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
        return "Produit [id=" + id + ", nom=" + nom + ", prix=" + prix + ", quantite=" + quantite + ", unite=" + unite + "]";
    }
}