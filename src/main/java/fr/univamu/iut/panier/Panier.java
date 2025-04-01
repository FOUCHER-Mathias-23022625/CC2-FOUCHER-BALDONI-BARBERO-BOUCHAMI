package fr.univamu.iut.panier;

import java.util.ArrayList;
import java.util.Date;
import java.util.List;

public class Panier {
    protected String id;
    protected double prixTotal;
    protected Date dateRetrait;
    protected String relais;
    protected String nom;
    protected String description;
    protected boolean valide;
    protected Date dateMiseAJour;
    protected List<ProduitPanier> produits;

    public Panier() {
        this.produits = new ArrayList<>();
        this.dateMiseAJour = new Date();
        this.valide = false;
    }

    public Panier(String id, double prixTotal, Date dateRetrait, String relais,
                  String nom, String description, boolean valide, Date dateMiseAJour) {
        this.id = id;
        this.prixTotal = prixTotal;
        this.dateRetrait = dateRetrait;
        this.relais = relais;
        this.nom = nom;
        this.description = description;
        this.valide = valide;
        this.dateMiseAJour = dateMiseAJour;
        this.produits = new ArrayList<>();
    }

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public double getPrixTotal() {
        return prixTotal;
    }

    public void setPrixTotal(double prixTotal) {
        this.prixTotal = prixTotal;
    }

    public Date getDateRetrait() {
        return dateRetrait;
    }

    public void setDateRetrait(Date dateRetrait) {
        this.dateRetrait = dateRetrait;
    }

    public String getRelais() {
        return relais;
    }

    public void setRelais(String relais) {
        this.relais = relais;
    }

    public String getNom() {
        return nom;
    }

    public void setNom(String nom) {
        this.nom = nom;
    }

    public String getDescription() {
        return description;
    }

    public void setDescription(String description) {
        this.description = description;
    }

    public boolean isValide() {
        return valide;
    }

    public void setValide(boolean valide) {
        this.valide = valide;
    }

    public Date getDateMiseAJour() {
        return dateMiseAJour;
    }

    public void setDateMiseAJour(Date dateMiseAJour) {
        this.dateMiseAJour = dateMiseAJour;
    }

    public List<ProduitPanier> getProduits() {
        return produits;
    }

    public void setProduits(List<ProduitPanier> produits) {
        this.produits = produits;
    }

    /**
     * Calcule le prix total du panier basé sur les prix et quantités des produits
     */
    public void calculerPrixTotal() {
        double total = 0;
        for (ProduitPanier produitPanier : produits) {
            total += produitPanier.getPrix() * produitPanier.getQuantite();
        }
        this.prixTotal = total;
    }

    /**
     * Met à jour la date de dernière modification du panier
     */
    public void miseAJourDate() {
        this.dateMiseAJour = new Date();
    }

    @Override
    public String toString() {
        return "Panier [id=" + id + ", prixTotal=" + prixTotal +
                ", dateRetrait=" + dateRetrait + ", relais=" + relais +
                ", nom=" + nom + ", description=" + description +
                ", valide=" + valide + ", dateMiseAJour=" + dateMiseAJour +
                ", produits=" + produits + "]";
    }
}