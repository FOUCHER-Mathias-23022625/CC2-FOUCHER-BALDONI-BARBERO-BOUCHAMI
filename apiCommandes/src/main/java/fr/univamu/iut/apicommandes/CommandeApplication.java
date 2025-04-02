package fr.univamu.iut.apicommandes;

import jakarta.enterprise.context.ApplicationScoped;
import jakarta.enterprise.inject.Disposes;
import jakarta.enterprise.inject.Produces;
import jakarta.ws.rs.ApplicationPath;
import jakarta.ws.rs.core.Application;

@ApplicationPath("/api")
@ApplicationScoped
public class CommandeApplication extends Application {

    private static final String PANIER_API_URL = "http://localhost:7080/paniers-1.0-SNAPSHOT/api/paniers";

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

    @Produces
    @ApplicationScoped
    public String producePanierApiUrl() {
        return System.getProperty("panier.api.url", PANIER_API_URL);
    }

    @Produces
    @ApplicationScoped
    public PanierClientInterface createPanierClient() {
        return new PanierClient(producePanierApiUrl());
    }

    public void closeDbConnection(@Disposes CommandeRepositoryInterface commandeRepo) {
        if (commandeRepo != null) {
            commandeRepo.close();
        }
    }

    public void closePanierClient(@Disposes PanierClientInterface panierClient) {
        if (panierClient != null) {
            panierClient.close();
        }
    }
}