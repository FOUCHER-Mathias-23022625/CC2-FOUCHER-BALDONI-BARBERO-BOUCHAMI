package fr.univamu.iut.apicommandes;

import jakarta.enterprise.context.ApplicationScoped;
import jakarta.inject.Inject;
import jakarta.json.bind.Jsonb;
import jakarta.json.bind.JsonbBuilder;

import java.util.ArrayList;

/**
 * Classe de service pour gérer la logique métier liée aux commandes.
 */
@ApplicationScoped
public class CommandeService {

    protected CommandeRepositoryInterface commandeRepo;
    protected PanierClientInterface panierClient;

    public CommandeService() {
    }

    @Inject
    public CommandeService(CommandeRepositoryInterface commandeRepo, PanierClientInterface panierClient) {
        this.commandeRepo = commandeRepo;
        this.panierClient = panierClient;

        if (commandeRepo instanceof CommandeRepositoryMariadb) {
            ((CommandeRepositoryMariadb) commandeRepo).setPanierClient(panierClient);
        }
    }

    public CommandeService(CommandeRepositoryInterface commandeRepo) {
        this.commandeRepo = commandeRepo;
    }

    /**
     * Récupère toutes les commandes.
     *
     * @return une liste de toutes les commandes
     */
    public ArrayList<Commande> getAllCommandes() {
        return commandeRepo.getAllCommandes();
    }

    /**
     * Récupère une commande par son identifiant.
     *
     * @param id l'identifiant de la commande
     * @return la commande
     */
    public Commande getCommandeById(int id) {
        return commandeRepo.getCommandeById(id);
    }

    /**
     * Met à jour une commande existante.
     *
     * @param id l'identifiant de la commande à mettre à jour
     * @param commande la commande mise à jour
     * @return true si la mise à jour a réussi, false sinon
     */
    public boolean updateCommande(int id, Commande commande) {
        return commandeRepo.updateCommande(id, 0, commande.getLocalisationRetrait(), commande.getDateRetrait());
    }

    /**
     * Supprime une commande par son identifiant.
     *
     * @param id l'identifiant de la commande à supprimer
     * @return true si la suppression a réussi, false sinon
     */
    public boolean removeCommande(int id) {
        return commandeRepo.removeCommande(id);
    }

    /**
     * Ajoute une nouvelle commande.
     *
     * @param commande la commande à ajouter
     * @return true si l'ajout a réussi, false sinon
     */
    public boolean addCommande(Commande commande) {
        return commandeRepo.addCommande(commande);
    }

    /**
     * Récupère le contenu d'une commande par son identifiant.
     *
     * @param idCommande l'identifiant de la commande
     * @return une liste du contenu de la commande
     */
    public ArrayList<CommandeContient> getCommandeContient(int idCommande) {
        return commandeRepo.getCommandeContient(idCommande);
    }

    /**
     * Récupère le contenu d'une commande par son identifiant au format JSON.
     *
     * @param idCommande l'identifiant de la commande
     * @return le contenu de la commande au format JSON
     */
    public String getCommandeContientJson(int idCommande) {
        ArrayList<CommandeContient> commandeContients = getCommandeContient(idCommande);

        String result = null;
        try(Jsonb jsonb = JsonbBuilder.create()) {
            result = jsonb.toJson(commandeContients);
        } catch (Exception e) {
            System.err.println(e.getMessage());
        }

        return result;
    }

    /**
     * Ajoute du contenu à une commande.
     *
     * @param commandeContient le contenu à ajouter
     * @return true si l'ajout a réussi, false sinon
     */
    public boolean addCommandeContient(CommandeContient commandeContient) {
        return commandeRepo.addCommandeContient(commandeContient);
    }

    /**
     * Supprime le contenu d'une commande.
     *
     * @param idCommande l'identifiant de la commande
     * @param idPanier l'identifiant du panier
     * @return true si la suppression a réussi, false sinon
     */
    public boolean removeCommandeContient(int idCommande, int idPanier) {
        return commandeRepo.removeCommandeContient(idCommande, idPanier);
    }

    /**
     * Met à jour le contenu d'une commande.
     *
     * @param idCommande l'identifiant de la commande
     * @param idPanier l'identifiant du panier
     * @param quantite la nouvelle quantité
     * @return true si la mise à jour a réussi, false sinon
     */
    public boolean updateCommandeContient(int idCommande, int idPanier, int quantite) {
        return commandeRepo.updateCommandeContient(idCommande, idPanier, quantite);
    }

    /**
     * Récupère les détails d'un panier par son identifiant.
     *
     * @param panierId l'identifiant du panier
     * @return les détails du panier
     */
    public Object getPanierDetails(int panierId) {
        if (panierClient != null) {
            return panierClient.getPanierById(String.valueOf(panierId));
        }
        return null;
    }

    /**
     * Récupère le total d'une commande par son identifiant.
     *
     * @param idCommande l'identifiant de la commande
     * @return le total de la commande
     */
    public double getCommandeTotal(int idCommande) {
        return commandeRepo.getCommandeTotal(idCommande);
    }
}