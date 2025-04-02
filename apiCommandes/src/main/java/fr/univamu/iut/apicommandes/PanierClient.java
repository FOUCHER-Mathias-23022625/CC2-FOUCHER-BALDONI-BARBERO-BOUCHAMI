package fr.univamu.iut.apicommandes;

import jakarta.enterprise.context.ApplicationScoped;
import jakarta.inject.Inject;
import jakarta.ws.rs.client.Client;
import jakarta.ws.rs.client.ClientBuilder;
import jakarta.ws.rs.client.WebTarget;
import jakarta.ws.rs.core.GenericType;
import jakarta.ws.rs.core.MediaType;
import jakarta.ws.rs.core.Response;

import java.util.List;

/**
 * Classe client pour interagir avec l'API Panier.
 */
@ApplicationScoped
public class PanierClient implements PanierClientInterface {
    private final String baseUrl;
    private final Client client;

    public PanierClient() {
        this(System.getProperty("panier.api.url", "http://localhost:7080/paniers-1.0-SNAPSHOT/api/paniers"));
    }

    @Inject
    public PanierClient(String baseUrl) {
        this.baseUrl = baseUrl;
        this.client = ClientBuilder.newClient();
    }

    @Override
    public double getPanierTotal(String panierId) {
        WebTarget target = client.target(baseUrl).path(panierId);
        try {
            Response response = target.request(MediaType.APPLICATION_JSON).get();
            if (response.getStatus() == 200) {
                Object panier = response.readEntity(Object.class);
                return extractPrixTotal(panier);
            }
        } catch (Exception e) {
            System.err.println("Problème de connexion à l'API Panier: " + e.getMessage());
        }
        return 0.0;
    }

    private double extractPrixTotal(Object panier) {
        return 0.0;
    }

    @Override
    public Object getPanierById(String panierId) {
        WebTarget target = client.target(baseUrl).path(panierId);
        try {
            Response response = target.request(MediaType.APPLICATION_JSON).get();
            if (response.getStatus() == 200) {
                return response.readEntity(Object.class);
            }
        } catch (Exception e) {
            System.err.println("Problème de connexion à l'API Panier: " + e.getMessage());
        }
        return null;
    }

    @Override
    public List<Object> getPanierProduits(String panierId) {
        WebTarget target = client.target(baseUrl).path(panierId).path("produits");
        try {
            Response response = target.request(MediaType.APPLICATION_JSON).get();
            if (response.getStatus() == 200) {
                return response.readEntity(new GenericType<List<Object>>(){});
            }
        } catch (Exception e) {
            System.err.println("Problème de connexion à l'API Panier: " + e.getMessage());
        }
        return null;
    }

    @Override
    public void close() {
        if (client != null) {
            client.close();
        }
    }
}