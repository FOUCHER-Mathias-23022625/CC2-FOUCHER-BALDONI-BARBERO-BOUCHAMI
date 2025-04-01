package fr.univamu.iut.panier;

/**
 * Classe représentant un produit récupéré depuis l'API Produits
 */
public class Produit {
    private String id;
    private String nom;
    private double prix;
    private String unite;
    private double quantiteDisponible;

    // Constructeurs, getters et setters
    public Produit() {}

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

    public String getUnite() {
        return unite;
    }

    public void setUnite(String unite) {
        this.unite = unite;
    }

    public double getQuantiteDisponible() {
        return quantiteDisponible;
    }

    public void setQuantiteDisponible(double quantiteDisponible) {
        this.quantiteDisponible = quantiteDisponible;
    }
}