package fr.univamu.iut.apicommandes;

import jakarta.enterprise.context.ApplicationScoped;
import jakarta.inject.Inject;
import jakarta.json.bind.Jsonb;
import jakarta.json.bind.JsonbBuilder;

import java.util.ArrayList;

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
    public ArrayList<Commande> getAllCommandes() {
        return commandeRepo.getAllCommandes();
    }

    public Commande getCommandeById(int id) {
        return commandeRepo.getCommandeById(id);
    }

    public boolean updateCommande(int id, Commande commande) {
        return commandeRepo.updateCommande(id, commande.getPrix(), commande.getLocalisationRetrait(), commande.getDateRetrait());
    }

    public boolean removeCommande(int id) {
        return commandeRepo.removeCommande(id);
    }

    public boolean addCommande(Commande commande) {
        return commandeRepo.addCommande(commande);
    }

    public ArrayList<CommandeContient> getCommandeContient(int idCommande) {
        return commandeRepo.getCommandeContient(idCommande);
    }

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

    public boolean addCommandeContient(CommandeContient commandeContient) {
        return commandeRepo.addCommandeContient(commandeContient);
    }

    public boolean removeCommandeContient(int idCommande, int idPanier) {
        return commandeRepo.removeCommandeContient(idCommande, idPanier);
    }

    public boolean updateCommandeContient(int idCommande, int idPanier, int quantite) {
        return commandeRepo.updateCommandeContient(idCommande, idPanier, quantite);
    }

    public Object getPanierDetails(int panierId) {
        if (panierClient != null) {
            return panierClient.getPanierById(String.valueOf(panierId));
        }
        return null;
    }

    public double getCommandeTotal(int idCommande) {
        return commandeRepo.getCommandeTotal(idCommande);
    }

}
