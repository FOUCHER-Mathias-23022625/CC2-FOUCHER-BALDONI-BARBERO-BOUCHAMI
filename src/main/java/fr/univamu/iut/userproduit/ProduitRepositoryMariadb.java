package fr.univamu.iut.userproduit;

import java.io.Closeable;
import java.sql.*;
import java.util.ArrayList;

public class ProduitRepositoryMariadb implements ProduitRepositoryInterface, Closeable {

    protected Connection dbConnection;

    public ProduitRepositoryMariadb(String infoConnection, String user, String pwd) throws java.sql.SQLException, java.lang.ClassNotFoundException {
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
    public Produit getProduit(String id) {
        Produit selectedProduit = null;
        String query = "SELECT * FROM Produit WHERE id=?";

        try (PreparedStatement ps = dbConnection.prepareStatement(query)) {
            ps.setString(1, id);
            ResultSet result = ps.executeQuery();

            if (result.next()) {
                String nom = result.getString("nom");
                double prix = result.getDouble("prix");
                double quantite = result.getDouble("quantite");
                String unite = result.getString("unite");

                selectedProduit = new Produit(id, nom, prix, quantite, unite);
            }
        } catch (SQLException e) {
            throw new RuntimeException(e);
        }
        return selectedProduit;
    }

    @Override
    public Produit getProduitByNom(String nom) {
        Produit selectedProduit = null;
        String query = "SELECT * FROM Produit WHERE nom=?";

        try (PreparedStatement ps = dbConnection.prepareStatement(query)) {
            ps.setString(1, nom);
            ResultSet result = ps.executeQuery();

            if (result.next()) {
                String id = result.getString("id");
                double prix = result.getDouble("prix");
                double quantite = result.getDouble("quantite");
                String unite = result.getString("unite");

                selectedProduit = new Produit(id, nom, prix, quantite, unite);
            }
        } catch (SQLException e) {
            throw new RuntimeException(e);
        }
        return selectedProduit;
    }

    @Override
    public ArrayList<Produit> getAllProduits() {
        ArrayList<Produit> listProduits;
        String query = "SELECT * FROM Produit";

        try (PreparedStatement ps = dbConnection.prepareStatement(query)) {
            ResultSet result = ps.executeQuery();
            listProduits = new ArrayList<>();

            while (result.next()) {
                String id = result.getString("id");
                String nom = result.getString("nom");
                double prix = result.getDouble("prix");
                double quantite = result.getDouble("quantite");
                String unite = result.getString("unite");

                Produit currentProduit = new Produit(id, nom, prix, quantite, unite);
                listProduits.add(currentProduit);
            }
        } catch (SQLException e) {
            throw new RuntimeException(e);
        }
        return listProduits;
    }

    @Override
    public boolean updateProduit(String id, String nom, double prix, double quantite, String unite) {
        String query = "UPDATE Produit SET nom=?, prix=?, quantite=?, unite=? WHERE id=?";
        int nbRowModified = 0;

        try (PreparedStatement ps = dbConnection.prepareStatement(query)) {
            ps.setString(1, nom);
            ps.setDouble(2, prix);
            ps.setDouble(3, quantite);
            ps.setString(4, unite);
            ps.setString(5, id);

            nbRowModified = ps.executeUpdate();
        } catch (SQLException e) {
            throw new RuntimeException(e);
        }

        return (nbRowModified != 0);
    }

    @Override
    public boolean addProduit(String id, String nom, double prix, double quantite, String unite) {
        String query = "INSERT INTO Produit (id, nom, prix, quantite, unite) VALUES (?, ?, ?, ?, ?)";
        int nbRowModified = 0;

        try (PreparedStatement ps = dbConnection.prepareStatement(query)) {
            ps.setString(1, id);
            ps.setString(2, nom);
            ps.setDouble(3, prix);
            ps.setDouble(4, quantite);
            ps.setString(5, unite);

            nbRowModified = ps.executeUpdate();
        } catch (SQLException e) {
            throw new RuntimeException(e);
        }

        return (nbRowModified != 0);
    }

    @Override
    public boolean deleteProduit(String id) {
        String query = "DELETE FROM Produit WHERE id=?";
        int nbRowModified = 0;

        try (PreparedStatement ps = dbConnection.prepareStatement(query)) {
            ps.setString(1, id);
            nbRowModified = ps.executeUpdate();
        } catch (SQLException e) {
            throw new RuntimeException(e);
        }

        return (nbRowModified != 0);
    }
}