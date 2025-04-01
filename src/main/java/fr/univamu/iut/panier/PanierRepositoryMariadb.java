package fr.univamu.iut.panier;

import java.io.Closeable;
import java.sql.*;
import java.util.ArrayList;
import java.util.Date;

public class PanierRepositoryMariadb implements PanierRepositoryInterface, Closeable {

    protected Connection dbConnection;

    public PanierRepositoryMariadb(String infoConnection, String user, String pwd) throws java.sql.SQLException, java.lang.ClassNotFoundException {
        Class.forName("org.mariadb.jdbc.Driver");
        dbConnection = DriverManager.getConnection(infoConnection, user, pwd);
    }

    @Override
    public void close() {
        try {
            dbConnection.close();
        } catch (SQLException e) {
            System.err.println(e.getMessage());
        }
    }

    @Override
    public Panier getPanier(String id) {
        Panier selectedPanier = null;
        String query = "SELECT * FROM Panier WHERE id=?";

        try (PreparedStatement ps = dbConnection.prepareStatement(query)) {
            ps.setString(1, id);
            ResultSet result = ps.executeQuery();

            if (result.next()) {
                double prixTotal = result.getDouble("prixTotal");
                Date dateRetrait = result.getDate("dateRetrait");
                String relais = result.getString("relais");
                String nom = result.getString("nom");
                String description = result.getString("description");
                boolean valide = result.getBoolean("valide");
                Date dateMiseAJour = result.getDate("dateMiseAJour");

                selectedPanier = new Panier(id, prixTotal, dateRetrait, relais, nom, description, valide, dateMiseAJour);

                // Récupération des produits du panier
                selectedPanier.setProduits(getProduitsPanier(id));
            }
        } catch (SQLException e) {
            throw new RuntimeException(e);
        }
        return selectedPanier;
    }

    @Override
    public ArrayList<Panier> getAllPaniers() {
        ArrayList<Panier> listPaniers;
        String query = "SELECT * FROM Panier";

        try (PreparedStatement ps = dbConnection.prepareStatement(query)) {
            ResultSet result = ps.executeQuery();
            listPaniers = new ArrayList<>();

            while (result.next()) {
                String id = result.getString("id");
                double prixTotal = result.getDouble("prixTotal");
                Date dateRetrait = result.getDate("dateRetrait");
                String relais = result.getString("relais");
                String nom = result.getString("nom");
                String description = result.getString("description");
                boolean valide = result.getBoolean("valide");
                Date dateMiseAJour = result.getDate("dateMiseAJour");

                Panier currentPanier = new Panier(id, prixTotal, dateRetrait, relais, nom, description, valide, dateMiseAJour);
                listPaniers.add(currentPanier);
            }
        } catch (SQLException e) {
            throw new RuntimeException(e);
        }
        return listPaniers;
    }

    @Override
    public ArrayList<Panier> getPaniersValides() {
        ArrayList<Panier> listPaniers;
        String query = "SELECT * FROM Panier WHERE valide=true";

        try (PreparedStatement ps = dbConnection.prepareStatement(query)) {
            ResultSet result = ps.executeQuery();
            listPaniers = new ArrayList<>();

            while (result.next()) {
                String id = result.getString("id");
                double prixTotal = result.getDouble("prixTotal");
                Date dateRetrait = result.getDate("dateRetrait");
                String relais = result.getString("relais");
                String nom = result.getString("nom");
                String description = result.getString("description");
                boolean valide = result.getBoolean("valide");
                Date dateMiseAJour = result.getDate("dateMiseAJour");

                Panier currentPanier = new Panier(id, prixTotal, dateRetrait, relais, nom, description, valide, dateMiseAJour);
                listPaniers.add(currentPanier);
            }
        } catch (SQLException e) {
            throw new RuntimeException(e);
        }
        return listPaniers;
    }

    @Override
    public ArrayList<Panier> getPaniersNonValides() {
        ArrayList<Panier> listPaniers;
        String query = "SELECT * FROM Panier WHERE valide=false";

        try (PreparedStatement ps = dbConnection.prepareStatement(query)) {
            ResultSet result = ps.executeQuery();
            listPaniers = new ArrayList<>();

            while (result.next()) {
                String id = result.getString("id");
                double prixTotal = result.getDouble("prixTotal");
                Date dateRetrait = result.getDate("dateRetrait");
                String relais = result.getString("relais");
                String nom = result.getString("nom");
                String description = result.getString("description");
                boolean valide = result.getBoolean("valide");
                Date dateMiseAJour = result.getDate("dateMiseAJour");

                Panier currentPanier = new Panier(id, prixTotal, dateRetrait, relais, nom, description, valide, dateMiseAJour);
                listPaniers.add(currentPanier);
            }
        } catch (SQLException e) {
            throw new RuntimeException(e);
        }
        return listPaniers;
    }

    @Override
    public boolean updatePanier(String id, double prixTotal, Date dateRetrait, String relais,
                                String nom, String description, boolean valide, Date dateMiseAJour) {
        String query = "UPDATE Panier SET prixTotal=?, dateRetrait=?, relais=?, nom=?, description=?, valide=?, dateMiseAJour=? WHERE id=?";
        int nbRowModified = 0;

        try (PreparedStatement ps = dbConnection.prepareStatement(query)) {
            ps.setDouble(1, prixTotal);
            if (dateRetrait != null) {
                ps.setDate(2, new java.sql.Date(dateRetrait.getTime()));
            } else {
                ps.setNull(2, java.sql.Types.DATE);
            }
            ps.setString(3, relais);
            ps.setString(4, nom);
            ps.setString(5, description);
            ps.setBoolean(6, valide);
            ps.setDate(7, new java.sql.Date(dateMiseAJour.getTime()));
            ps.setString(8, id);

            nbRowModified = ps.executeUpdate();
        } catch (SQLException e) {
            throw new RuntimeException(e);
        }

        return (nbRowModified != 0);
    }

    @Override
    public boolean addPanier(String id, double prixTotal, Date dateRetrait, String relais,
                             String nom, String description, boolean valide, Date dateMiseAJour) {
        String query = "INSERT INTO Panier (id, prixTotal, dateRetrait, relais, nom, description, valide, dateMiseAJour) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        int nbRowModified = 0;

        try (PreparedStatement ps = dbConnection.prepareStatement(query)) {
            ps.setString(1, id);
            ps.setDouble(2, prixTotal);
            if (dateRetrait != null) {
                ps.setDate(3, new java.sql.Date(dateRetrait.getTime()));
            } else {
                ps.setNull(3, java.sql.Types.DATE);
            }
            ps.setString(4, relais);
            ps.setString(5, nom);
            ps.setString(6, description);
            ps.setBoolean(7, valide);
            ps.setDate(8, new java.sql.Date(dateMiseAJour.getTime()));

            nbRowModified = ps.executeUpdate();
        } catch (SQLException e) {
            throw new RuntimeException(e);
        }

        return (nbRowModified != 0);
    }

    @Override
    public boolean deletePanier(String id) {
        // D'abord supprimer tous les produits associés à ce panier
        deleteAllProduitsPanier(id);

        // Ensuite supprimer le panier
        String query = "DELETE FROM Panier WHERE id=?";
        int nbRowModified = 0;

        try (PreparedStatement ps = dbConnection.prepareStatement(query)) {
            ps.setString(1, id);
            nbRowModified = ps.executeUpdate();
        } catch (SQLException e) {
            throw new RuntimeException(e);
        }

        return (nbRowModified != 0);
    }

    @Override
    public ArrayList<ProduitPanier> getProduitsPanier(String panierId) {
        ArrayList<ProduitPanier> listProduits;
        String query = "SELECT * FROM ProduitPanier WHERE panierId=?";

        try (PreparedStatement ps = dbConnection.prepareStatement(query)) {
            ps.setString(1, panierId);
            ResultSet result = ps.executeQuery();
            listProduits = new ArrayList<>();

            while (result.next()) {
                String id = result.getString("id");
                String produitId = result.getString("produitId");
                String nom = result.getString("nom");
                double prix = result.getDouble("prix");
                double quantite = result.getDouble("quantite");
                String unite = result.getString("unite");

                ProduitPanier currentProduit = new ProduitPanier(id, panierId, produitId, nom, prix, quantite, unite);
                listProduits.add(currentProduit);
            }
        } catch (SQLException e) {
            throw new RuntimeException(e);
        }
        return listProduits;
    }

    @Override
    public boolean addProduitPanier(String id, String panierId, String produitId, String nom, double prix, double quantite, String unite) {
        String query = "INSERT INTO ProduitPanier (id, panierId, produitId, nom, prix, quantite, unite) VALUES (?, ?, ?, ?, ?, ?, ?)";
        int nbRowModified = 0;

        try (PreparedStatement ps = dbConnection.prepareStatement(query)) {
            ps.setString(1, id);
            ps.setString(2, panierId);
            ps.setString(3, produitId);
            ps.setString(4, nom);
            ps.setDouble(5, prix);
            ps.setDouble(6, quantite);
            ps.setString(7, unite);

            nbRowModified = ps.executeUpdate();

            // Mettre à jour la date de dernière modification et le prix total du panier
            if (nbRowModified > 0) {
                Panier panier = getPanier(panierId);
                if (panier != null) {
                    panier.calculerPrixTotal();
                    panier.miseAJourDate();
                    updatePanier(panierId, panier.getPrixTotal(), panier.getDateRetrait(),
                            panier.getRelais(), panier.getNom(), panier.getDescription(),
                            panier.isValide(), panier.getDateMiseAJour());
                }
            }
        } catch (SQLException e) {
            throw new RuntimeException(e);
        }

        return (nbRowModified != 0);
    }

    @Override
    public boolean updateProduitPanier(String id, double quantite) {
        String query = "UPDATE ProduitPanier SET quantite=? WHERE id=?";
        int nbRowModified = 0;
        String panierId = null;

        try {
            // D'abord récupérer le panierId associé à ce produit
            String querySelect = "SELECT panierId FROM ProduitPanier WHERE id=?";
            try (PreparedStatement psSelect = dbConnection.prepareStatement(querySelect)) {
                psSelect.setString(1, id);
                ResultSet result = psSelect.executeQuery();
                if (result.next()) {
                    panierId = result.getString("panierId");
                }
            }

            // Ensuite mettre à jour la quantité
            try (PreparedStatement ps = dbConnection.prepareStatement(query)) {
                ps.setDouble(1, quantite);
                ps.setString(2, id);
                nbRowModified = ps.executeUpdate();

                // Mettre à jour la date de dernière modification et le prix total du panier
                if (nbRowModified > 0 && panierId != null) {
                    Panier panier = getPanier(panierId);
                    if (panier != null) {
                        panier.calculerPrixTotal();
                        panier.miseAJourDate();
                        updatePanier(panierId, panier.getPrixTotal(), panier.getDateRetrait(),
                                panier.getRelais(), panier.getNom(), panier.getDescription(),
                                panier.isValide(), panier.getDateMiseAJour());
                    }
                }
            }
        } catch (SQLException e) {
            throw new RuntimeException(e);
        }

        return (nbRowModified != 0);
    }

    @Override
    public boolean deleteProduitPanier(String id) {
        String panierId = null;

        try {
            // D'abord récupérer le panierId associé à ce produit
            String querySelect = "SELECT panierId FROM ProduitPanier WHERE id=?";
            try (PreparedStatement psSelect = dbConnection.prepareStatement(querySelect)) {
                psSelect.setString(1, id);
                ResultSet result = psSelect.executeQuery();
                if (result.next()) {
                    panierId = result.getString("panierId");
                }
            }
        } catch (SQLException e) {
            throw new RuntimeException(e);
        }

        // Ensuite supprimer le produit
        String query = "DELETE FROM ProduitPanier WHERE id=?";
        int nbRowModified = 0;

        try (PreparedStatement ps = dbConnection.prepareStatement(query)) {
            ps.setString(1, id);
            nbRowModified = ps.executeUpdate();

            // Mettre à jour la date de dernière modification et le prix total du panier
            if (nbRowModified > 0 && panierId != null) {
                Panier panier = getPanier(panierId);
                if (panier != null) {
                    panier.calculerPrixTotal();
                    panier.miseAJourDate();
                    updatePanier(panierId, panier.getPrixTotal(), panier.getDateRetrait(),
                            panier.getRelais(), panier.getNom(), panier.getDescription(),
                            panier.isValide(), panier.getDateMiseAJour());
                }
            }
        } catch (SQLException e) {
            throw new RuntimeException(e);
        }

        return (nbRowModified != 0);
    }

    @Override
    public boolean deleteAllProduitsPanier(String panierId) {
        String query = "DELETE FROM ProduitPanier WHERE panierId=?";
        int nbRowModified = 0;

        try (PreparedStatement ps = dbConnection.prepareStatement(query)) {
            ps.setString(1, panierId);
            nbRowModified = ps.executeUpdate();
        } catch (SQLException e) {
            throw new RuntimeException(e);
        }

        return (nbRowModified != 0);
    }
}