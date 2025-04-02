package fr.univamu.iut.apicommandes;

import java.sql.*;
import java.util.ArrayList;

/**
 * Implémentation de CommandeRepositoryInterface pour MariaDB.
 */
public class CommandeRepositoryMariadb implements CommandeRepositoryInterface {

    protected Connection connection;
    protected PanierClientInterface panierClient;

    /**
     * Constructeur avec paramètres pour la connexion à la base de données.
     *
     * @param infoConnexion les informations de connexion
     * @param user le nom d'utilisateur
     * @param password le mot de passe
     * @throws SQLException si une erreur SQL se produit
     * @throws ClassNotFoundException si le driver JDBC n'est pas trouvé
     */
    public CommandeRepositoryMariadb(String infoConnexion, String user, String password) throws SQLException, ClassNotFoundException {
        Class.forName("org.mariadb.jdbc.Driver");
        connection = DriverManager.getConnection(infoConnexion, user, password);
        // Le panierClient est injecté plus tard
    }

    /**
     * Définit le client panier.
     *
     * @param panierClient le client panier à définir
     */
    public void setPanierClient(PanierClientInterface panierClient) {
        this.panierClient = panierClient;
    }

    @Override
    public void close() {
        try {
            connection.close();
        } catch (SQLException e) {
            System.err.println("Erreur fermeture connexion: " + e.getMessage());
        }
    }

    @Override
    public Commande getCommandeById(int id) {
        Commande commandeSelected = null;
        String query = "SELECT * FROM commandes WHERE id_commande = ?";
        try {
            PreparedStatement ps = connection.prepareStatement(query);
            ps.setInt(1, id);
            ResultSet resultSet = ps.executeQuery();

            if (resultSet.next()) {
                commandeSelected = new Commande(
                        resultSet.getInt("id_commande"),
                        resultSet.getInt("prix"),
                        resultSet.getString("localisation_retrait"),
                        resultSet.getDate("date_retrait")
                );
            }
        } catch (SQLException e) {
            System.err.println("Erreur SQL: " + e.getMessage());
        }
        return commandeSelected;
    }

    @Override
    public ArrayList<Commande> getAllCommandes() {
        ArrayList<Commande> commandes = new ArrayList<>();
        String query = "SELECT * FROM commandes";
        try {
            Statement stmt = connection.createStatement();
            ResultSet resultSet = stmt.executeQuery(query);

            while (resultSet.next()) {
                Commande commande = new Commande(
                        resultSet.getInt("id_commande"),
                        resultSet.getInt("prix"),
                        resultSet.getString("localisation_retrait"),
                        resultSet.getDate("date_retrait")
                );
                commandes.add(commande);
            }
        } catch (SQLException e) {
            System.err.println("Erreur SQL: " + e.getMessage());
            e.printStackTrace();
        }
        return commandes;
    }

    @Override
    public boolean addCommande(Commande commande) {
        String query = "INSERT INTO commandes (id_commande, prix, localisation_retrait, date_retrait) VALUES (?, ?, ?, ?)";
        try {
            PreparedStatement ps = connection.prepareStatement(query);
            ps.setInt(1, commande.getId());
            ps.setInt(2, commande.getPrix());
            ps.setString(3, commande.getLocalisationRetrait());
            ps.setDate(4, commande.getDateRetrait());
            ps.executeUpdate();
            return true;
        } catch (SQLException e) {
            System.err.println("Erreur SQL: " + e.getMessage());
            return false;
        }
    }

    @Override
    public boolean removeCommande(int id) {
        String query = "DELETE FROM commandes WHERE id_commande = ?";
        try {
            PreparedStatement ps = connection.prepareStatement(query);
            ps.setInt(1, id);
            ps.executeUpdate();
            return true;
        } catch (SQLException e) {
            System.err.println("Erreur SQL: " + e.getMessage());
            return false;
        }
    }

    @Override
    public boolean updateCommande(int id, int prix, String localisationRetrait, Date dateRetrait) {
        String query = "UPDATE commandes SET prix = ?, localisation_retrait = ?, date_retrait = ? WHERE id_commande = ?";
        try {
            PreparedStatement ps = connection.prepareStatement(query);
            ps.setInt(1, prix);
            ps.setString(2, localisationRetrait);
            ps.setDate(3, dateRetrait);
            ps.setInt(4, id);
            ps.executeUpdate();
            return true;
        } catch (SQLException e) {
            System.err.println("Erreur SQL: " + e.getMessage());
            return false;
        }
    }

    @Override
    public ArrayList<CommandeContient> getCommandeContient(int idCommande) {
        ArrayList<CommandeContient> commandeContients = new ArrayList<>();
        String query = "SELECT * FROM commande_contient WHERE id_commande = ?";
        try {
            PreparedStatement ps = connection.prepareStatement(query);
            ps.setInt(1, idCommande);
            ResultSet resultSet = ps.executeQuery();

            while (resultSet.next()) {
                CommandeContient commandeContient = new CommandeContient(
                        resultSet.getInt("id_commande"),
                        resultSet.getInt("id_panier"),
                        resultSet.getInt("quantite")
                );
                commandeContients.add(commandeContient);
            }
        } catch (SQLException e) {
            System.err.println("Erreur SQL: " + e.getMessage());
        }
        return commandeContients;
    }

    @Override
    public boolean addCommandeContient(CommandeContient commandeContient) {
        String query = "INSERT INTO commande_contient (id_commande, id_panier, quantite) VALUES (?, ?, ?)";
        try {
            PreparedStatement ps = connection.prepareStatement(query);
            ps.setInt(1, commandeContient.getIdCommande());
            ps.setInt(2, commandeContient.getIdPanier());
            ps.setInt(3, commandeContient.getQuantite());
            ps.executeUpdate();
            return true;
        } catch (SQLException e) {
            System.err.println("Erreur SQL: " + e.getMessage());
            return false;
        }
    }

    @Override
    public boolean removeCommandeContient(int idCommande, int idPanier) {
        String query = "DELETE FROM commande_contient WHERE id_commande = ? AND id_panier = ?";
        try {
            PreparedStatement ps = connection.prepareStatement(query);
            ps.setInt(1, idCommande);
            ps.setInt(2, idPanier);
            ps.executeUpdate();
            return true;
        } catch (SQLException e) {
            System.err.println("Erreur SQL: " + e.getMessage());
            return false;
        }
    }

    @Override
    public boolean updateCommandeContient(int idCommande, int idPanier, int quantite) {
        String query = "UPDATE commande_contient SET quantite = ? WHERE id_commande = ? AND id_panier = ?";
        try {
            PreparedStatement ps = connection.prepareStatement(query);
            ps.setInt(1, quantite);
            ps.setInt(2, idCommande);
            ps.setInt(3, idPanier);
            ps.executeUpdate();
            return true;
        } catch (SQLException e) {
            System.err.println("Erreur SQL: " + e.getMessage());
            return false;
        }
    }

    @Override
    public double getCommandeTotal(int idCommande) {
        double total = 0.0;

        try {
            ArrayList<CommandeContient> contenuCommande = getCommandeContient(idCommande);

            if (panierClient != null) {
                for (CommandeContient item : contenuCommande) {
                    int idPanier = item.getIdPanier();
                    int quantite = item.getQuantite();

                    double panierTotal = panierClient.getPanierTotal(String.valueOf(idPanier));
                    total += panierTotal * quantite;
                }
            } else {
                System.err.println("PanierClient non initialisé");
            }
        } catch (Exception e) {
            System.err.println("Erreur calcul prix total: " + e.getMessage());
        }

        return total;
    }
}