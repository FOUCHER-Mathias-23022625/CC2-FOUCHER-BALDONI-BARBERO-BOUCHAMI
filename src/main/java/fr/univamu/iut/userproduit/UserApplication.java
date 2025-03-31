package fr.univamu.iut.userproduit;

import jakarta.enterprise.context.ApplicationScoped;
import jakarta.enterprise.inject.Disposes;
import jakarta.enterprise.inject.Produces;
import jakarta.ws.rs.ApplicationPath;
import jakarta.ws.rs.core.Application;

@ApplicationPath("/api")
@ApplicationScoped
public class UserApplication extends Application {

    @Produces
    private UserRepositoryInterface openUserDbConnection(){
        UserRepositoryMariadb db = null;

        try{
            db = new UserRepositoryMariadb("jdbc:mariadb://mysql-td3-architecture.alwaysdata.net/td3-architecture_db", "404406", "Td3020905.");
        }
        catch (Exception e){
            System.err.println(e.getMessage());
        }
        return db;
    }

    /**
     * Méthode permettant de fermer la connexion à la base de données utilisateur lorsque l'application est arrêtée
     * @param userRepo la connexion à la base de données instanciée dans la méthode @openUserDbConnection
     */
    private void closeUserDbConnection(@Disposes UserRepositoryInterface userRepo) {
        userRepo.close();
    }

    @Produces
    private ProduitRepositoryInterface openProduitDbConnection(){
        ProduitRepositoryMariadb db = null;

        try{
            db = new ProduitRepositoryMariadb("jdbc:mariadb://mysql-td3-architecture.alwaysdata.net/td3-architecture_db", "404406", "Td3020905.");
        }
        catch (Exception e){
            System.err.println(e.getMessage());
        }
        return db;
    }

    /**
     * Méthode permettant de fermer la connexion à la base de données produit lorsque l'application est arrêtée
     * @param produitRepo la connexion à la base de données instanciée dans la méthode @openProduitDbConnection
     */
    private void closeProduitDbConnection(@Disposes ProduitRepositoryInterface produitRepo) {
        produitRepo.close();
    }
}