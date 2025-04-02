package fr.univamu.iut.apicommandes;

import java.sql.Date;
import java.util.*;

public interface CommandeRepositoryInterface {

    public void close();

    public Commande getCommandeById(int id);

    public ArrayList<Commande> getAllCommandes();

    public boolean addCommande(Commande commande);

    public boolean removeCommande(int id);

    public boolean updateCommande(int id, int prix, String localisationRetrait, Date dateRetrait);

    public ArrayList<CommandeContient> getCommandeContient(int idCommande);

    public boolean addCommandeContient(CommandeContient commandeContient);

    public boolean removeCommandeContient(int idCommande, int idPanier);

    public boolean updateCommandeContient(int idCommande, int idPanier, int quantite);

    public double getCommandeTotal(int idCommande);
}