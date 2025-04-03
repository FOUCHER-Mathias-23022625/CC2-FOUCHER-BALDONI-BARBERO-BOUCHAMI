package fr.univamu.iut.apicommandes;

import jakarta.enterprise.context.ApplicationScoped;
import jakarta.enterprise.inject.Disposes;
import jakarta.enterprise.inject.Produces;
import jakarta.ws.rs.ApplicationPath;
import jakarta.ws.rs.core.Application;

import java.util.ArrayList;

/**
 * Classe principale de l'application de gestion des commandes.
 */
@ApplicationPath("/api")
@ApplicationScoped
public class CommandeApplication extends Application {

    private static final String PANIER_API_URL = "http://localhost:7080/paniers-1.0-SNAPSHOT/api/paniers";

    /**
     * Crée une instance de CommandeRepositoryInterface.
     *
     * @return une instance de CommandeRepositoryInterface
     */
    @Produces
    @ApplicationScoped
    public CommandeRepositoryInterface createCommandeRepository() {
        CommandeRepositoryMariadb db = null;

        try {
            db = new CommandeRepositoryMariadb("jdbc:mariadb://mysql-baldoni.alwaysdata.net/baldoni_commandes",
                    "baldoni_commande", "cc2MdpCommandes");
            if (db == null) {
                throw new RuntimeException("Échec de création de l'instance CommandeRepositoryMariadb");
            }
            db.getAllCommandes();
        } catch (Exception e) {
            System.err.println("Erreur de connexion à la base de données: " + e.getMessage());
            e.printStackTrace();
            throw new RuntimeException("Échec d'initialisation de la connexion à la base de données", e);
        }
        return db;
    }

    /**
     * Produit l'URL de l'API panier.
     *
     * @return l'URL de l'API panier
     */
    @Produces
    @ApplicationScoped
    public String producePanierApiUrl() {
        return System.getProperty("panier.api.url", PANIER_API_URL);
    }

    /**
     * Crée une instance de PanierClientInterface.
     *
     * @return une instance de PanierClientInterface
     */
    @Produces
    @ApplicationScoped
    public PanierClientInterface createPanierClient() {
        return new PanierClient(producePanierApiUrl());
    }

    /**
     * Ferme la connexion à la base de données.
     *
     * @param commandeRepo l'instance de CommandeRepositoryInterface à fermer
     */
    public void closeDbConnection(@Disposes CommandeRepositoryInterface commandeRepo) {
        if (commandeRepo != null) {
            commandeRepo.close();
        }
    }

    /**
     * Ferme le client panier.
     *
     * @param panierClient l'instance de PanierClientInterface à fermer
     */
    public void closePanierClient(@Disposes PanierClientInterface panierClient) {
        if (panierClient != null) {
            panierClient.close();
        }
    }
}

