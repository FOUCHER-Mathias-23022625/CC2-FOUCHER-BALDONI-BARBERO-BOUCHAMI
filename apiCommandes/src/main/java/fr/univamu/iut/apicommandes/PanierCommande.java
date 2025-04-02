package fr.univamu.iut.apicommandes;

import jakarta.ws.rs.client.Client;
import jakarta.ws.rs.client.ClientBuilder;
import jakarta.ws.rs.client.WebTarget;
import jakarta.ws.rs.core.MediaType;
import jakarta.ws.rs.core.Response;

public class PanierCommande {
    private final String baseUrl;
    private final Client client;

    public PanierCommande() {
        this.baseUrl = "http://localhost:9080/panier-1.0-SNAPSHOT/api/";
        this.client = ClientBuilder.newClient();
    }

    public double getPanierTotal(int panierId) {
        WebTarget target = client.target(baseUrl).path(String.valueOf(panierId)).path("total");
        try {
            Response response = target.request(MediaType.APPLICATION_JSON).get();
            if (response.getStatus() == 200) {
                return response.readEntity(Double.class);
            }
        } catch (Exception e) {
            System.err.println("probleme connexion api panier " + e.getMessage());
        }
        return 0.0;
    }

    public void close() {
        if (client != null) {
            client.close();
        }
    }
}