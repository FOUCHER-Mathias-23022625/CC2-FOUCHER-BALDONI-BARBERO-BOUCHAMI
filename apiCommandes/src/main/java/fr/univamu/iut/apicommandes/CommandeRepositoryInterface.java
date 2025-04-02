package fr.univamu.iut.apicommandes;

import java.sql.Date;
import java.util.ArrayList;

/**
 * Interface pour le dépôt de commandes.
 */
public interface CommandeRepositoryInterface {

    /**
     * Ferme la connexion au dépôt.
     */
    public void close();

    /**
     * Récupère une commande par son identifiant.
     *
     * @param id l'identifiant de la commande
     * @return la commande correspondante
     */
    public Commande getCommandeById(int id);

    /**
     * Récupère toutes les commandes.
     *
     * @return une liste de toutes les commandes
     */
    public ArrayList<Commande> getAllCommandes();

    /**
     * Ajoute une nouvelle commande.
     *
     * @param commande la commande à ajouter
     * @return true si l'ajout a réussi, false sinon
     */
    public boolean addCommande(Commande commande);

    /**
     * Supprime une commande par son identifiant.
     *
     * @param id l'identifiant de la commande
     * @return true si la suppression a réussi, false sinon
     */
    public boolean removeCommande(int id);

    /**
     * Met à jour une commande.
     *
     * @param id l'identifiant de la commande
     * @param prix le nouveau prix de la commande
     * @param localisationRetrait la nouvelle localisation de retrait
     * @param dateRetrait la nouvelle date de retrait
     * @return true si la mise à jour a réussi, false sinon
     */
    public boolean updateCommande(int id, int prix, String localisationRetrait, Date dateRetrait);

    /**
     * Récupère le contenu d'une commande par son identifiant.
     *
     * @param idCommande l'identifiant de la commande
     * @return une liste du contenu de la commande
     */
    public ArrayList<CommandeContient> getCommandeContient(int idCommande);

    /**
     * Ajoute un contenu à une commande.
     *
     * @param commandeContient le contenu à ajouter
     * @return true si l'ajout a réussi, false sinon
     */
    public boolean addCommandeContient(CommandeContient commandeContient);

    /**
     * Supprime un contenu d'une commande.
     *
     * @param idCommande l'identifiant de la commande
     * @param idPanier l'identifiant du panier
     * @return true si la suppression a réussi, false sinon
     */
    public boolean removeCommandeContient(int idCommande, int idPanier);

    /**
     * Met à jour le contenu d'une commande.
     *
     * @param idCommande l'identifiant de la commande
     * @param idPanier l'identifiant du panier
     * @param quantite la nouvelle quantité
     * @return true si la mise à jour a réussi, false sinon
     */
    public boolean updateCommandeContient(int idCommande, int idPanier, int quantite);

    /**
     * Calcule le total d'une commande.
     *
     * @param idCommande l'identifiant de la commande
     * @return le total de la commande
     */
    public double getCommandeTotal(int idCommande);
}