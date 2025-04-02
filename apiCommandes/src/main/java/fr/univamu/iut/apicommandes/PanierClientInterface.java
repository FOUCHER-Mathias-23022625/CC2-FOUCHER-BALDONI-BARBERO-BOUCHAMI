package fr.univamu.iut.apicommandes;

import java.util.List;

public interface PanierClientInterface {
    double getPanierTotal(String panierId);
    Object getPanierById(String panierId);
    List<Object> getPanierProduits(String panierId);
    void close();
}