package fr.univamu.iut.userproduit;
import java.util.*;

public interface ProduitRepositoryInterface {
    public void close();

    public Produit getProduit(String id);

    public Produit getProduitByNom(String nom);

    public ArrayList<Produit> getAllProduits();

    public boolean updateProduit(String id, String nom, double prix, double quantite, String unite);

    public boolean addProduit(String id, String nom, double prix, double quantite, String unite);

    public boolean deleteProduit(String id);
}