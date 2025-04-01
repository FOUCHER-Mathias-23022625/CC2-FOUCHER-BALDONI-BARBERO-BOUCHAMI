package fr.univamu.iut.panier;

import java.util.ArrayList;
import java.util.Date;

public interface PanierRepositoryInterface {
    public void close();

    public Panier getPanier(String id);

    public ArrayList<Panier> getAllPaniers();

    public ArrayList<Panier> getPaniersValides();

    public ArrayList<Panier> getPaniersNonValides();

    public boolean updatePanier(String id, double prixTotal, Date dateRetrait, String relais,
                                String nom, String description, boolean valide, Date dateMiseAJour);

    public boolean addPanier(String id, double prixTotal, Date dateRetrait, String relais,
                             String nom, String description, boolean valide, Date dateMiseAJour);

    public boolean deletePanier(String id);

    public ArrayList<ProduitPanier> getProduitsPanier(String panierId);

    public boolean addProduitPanier(String id, String panierId, String produitId, String nom, double prix, double quantite, String unite);

    public boolean updateProduitPanier(String id, double quantite);

    public boolean deleteProduitPanier(String id);

    public boolean deleteAllProduitsPanier(String panierId);
}