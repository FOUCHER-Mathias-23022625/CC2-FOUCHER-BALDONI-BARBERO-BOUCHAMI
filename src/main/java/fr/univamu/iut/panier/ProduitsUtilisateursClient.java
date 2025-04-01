package fr.univamu.iut.panier;

import jakarta.annotation.PostConstruct;
import jakarta.enterprise.context.ApplicationScoped;
import jakarta.ws.rs.client.Client;
import jakarta.ws.rs.client.ClientBuilder;
import jakarta.ws.rs.client.WebTarget;
import jakarta.ws.rs.core.GenericType;
import jakarta.ws.rs.core.MediaType;
import jakarta.ws.rs.core.Response;
import java.util.List;

/**
 * Client pour l'API Produits et Utilisateurs
 */
@ApplicationScoped
public class ProduitsUtilisateursClient {

    private String baseUrl;
    private Client client;

    // Constructeur par défaut nécessaire pour CDI
    public ProduitsUtilisateursClient() {
        // Ne pas initialiser client ici
    }

    @PostConstruct
    public void init() {
        // Initialisation après l'injection
        this.baseUrl = System.getProperty("produits.api.url", "http://localhost:9080/UserProduit-1.0-SNAPSHOT/api");
        this.client = ClientBuilder.newClient();
    }

    // Garder le constructeur avec paramètre pour les tests ou usages spécifiques
    public ProduitsUtilisateursClient(String baseUrl) {
        this.baseUrl = baseUrl;
        this.client = ClientBuilder.newClient();
    }
    /**
     * Récupère tous les produits disponibles
     * @return la liste des produits ou null en cas d'erreur
     */
    public List<Produit> getAllProduits() {
        WebTarget target = client.target(baseUrl).path("produit");

        try {
            Response response = target.request(MediaType.APPLICATION_JSON).get();

            if (response.getStatus() == 200) {
                return response.readEntity(new GenericType<List<Produit>>(){});
            } else {
                System.err.println("Erreur lors de la récupération des produits: " + response.getStatus());
                return null;
            }
        } catch (Exception e) {
            System.err.println("Erreur de connexion à l'API Produits: " + e.getMessage());
            return null;
        }
    }

    /**
     * Récupère les informations d'un produit par son ID
     * @param produitId l'identifiant du produit
     * @return l'objet représentant le produit ou null si non trouvé
     */
    public Produit getProduitById(String produitId) {
        // Ceci construira l'URL: http://localhost:9080/UserProduit-1.0-SNAPSHOT/api/produit/{produitId}
        WebTarget target = client.target(baseUrl).path("produit").path(produitId);

        try {
            Response response = target.request(MediaType.APPLICATION_JSON).get();

            if (response.getStatus() == 200) {
                return response.readEntity(Produit.class);
            } else {
                System.err.println("Erreur lors de la récupération du produit: " + response.getStatus());
                return null;
            }
        } catch (Exception e) {
            System.err.println("Erreur de connexion à l'API Produits: " + e.getMessage());
            return null;
        }
    }

    /**
     * Vérifie si un utilisateur existe par son ID
     * @param userId l'identifiant de l'utilisateur
     * @return true si l'utilisateur existe, false sinon
     */
    public boolean utilisateurExists(String userId) {
        WebTarget target = client.target(baseUrl).path("utilisateurs").path(userId);

        try {
            Response response = target.request(MediaType.APPLICATION_JSON).get();
            return response.getStatus() == 200;
        } catch (Exception e) {
            System.err.println("Erreur de connexion à l'API Utilisateurs: " + e.getMessage());
            return false;
        }
    }

    // Fermeture du client
    public void close() {
        if (client != null) {
            client.close();
        }
    }
}