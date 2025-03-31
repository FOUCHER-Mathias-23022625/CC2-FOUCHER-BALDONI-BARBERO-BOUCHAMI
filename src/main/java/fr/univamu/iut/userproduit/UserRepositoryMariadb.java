package fr.univamu.iut.userproduit;

import java.io.Closeable;
import java.sql.*;
import java.util.ArrayList;


public class UserRepositoryMariadb  implements UserRepositoryInterface, Closeable{

    protected Connection dbConnection ;

    public UserRepositoryMariadb(String infoConnection, String user, String pwd) throws java.sql.SQLException, java.lang.ClassNotFoundException {
        Class.forName("org.mariadb.jdbc.Driver");
        dbConnection = DriverManager.getConnection( infoConnection, user, pwd ) ;
    }

    @Override
    public void close() {
        try{
            dbConnection.close();
        }
        catch(SQLException e){
            System.err.println(e.getMessage());
        }
    }

    @Override
    public User getUser(String id) {

        User selectedUser = null;

        String query = "SELECT * FROM User WHERE id=?";

        // construction et exécution d'une requête préparée
        try ( PreparedStatement ps = dbConnection.prepareStatement(query) ){
            ps.setString(1, id);

            // exécution de la requête
            ResultSet result = ps.executeQuery();

            // récupération du premier (et seul) tuple résultat
            // (si la référence du user est valide)
            if( result.next() )
            {
                String name = result.getString("name");
                String pwd = result.getString("pwd");
                String mail = result.getString("mail");
                Boolean gestionnaire = result.getBoolean("gestionnaire");

                // création et initialisation de l'objet Book
                selectedUser = new User(id, name, pwd, mail, gestionnaire);
            }
        } catch (SQLException e) {
            throw new RuntimeException(e);
        }
        return selectedUser;
    }

    @Override
    public ArrayList<User> getAllUser() {
        ArrayList<User> listUser; ;

        String query = "SELECT * FROM User";

        // construction et exécution d'une requête préparée
        try ( PreparedStatement ps = dbConnection.prepareStatement(query) ){
            // exécution de la requête
            ResultSet result = ps.executeQuery();

            listUser = new ArrayList<>();

            // récupération du premier (et seul) tuple résultat
            while ( result.next() )
            {
                String id = result.getString("id");
                String name = result.getString("name");
                String pwd = result.getString("pwd");
                String mail = result.getString("mail");
                boolean gestionnaire = result.getBoolean("gestionnaire");

                // création du livre courant
                User currentUser = new User(id, name, pwd, mail, gestionnaire);


                listUser.add(currentUser);
            }
        } catch (SQLException e) {
            throw new RuntimeException(e);
        }
        return listUser;
    }

    @Override
    public boolean updateUser(String id, String name, String pwd, String mail) {
        String query = "UPDATE User SET name=?, pwd=?, mail=?  where id=?";
        int nbRowModified = 0;

        // construction et exécution d'une requête préparée
        try ( PreparedStatement ps = dbConnection.prepareStatement(query) ){
            ps.setString(1, name);
            ps.setString(2, pwd);
            ps.setString(3, mail );
            ps.setString(4, id);

            // exécution de la requête
            nbRowModified = ps.executeUpdate();
        } catch (SQLException e) {
            throw new RuntimeException(e);
        }

        return ( nbRowModified != 0 );
    }

    @Override
    public User getUserByName(String name) {
        User selectedUser = null;
        String query = "SELECT * FROM User WHERE name=?";

        try (PreparedStatement ps = dbConnection.prepareStatement(query)) {
            ps.setString(1, name);
            ResultSet result = ps.executeQuery();

            if (result.next()) {
                String id = result.getString("id");
                String pwd = result.getString("pwd");
                String mail = result.getString("mail");
                boolean gestionnaire = result.getBoolean("gestionnaire");

                selectedUser = new User(id, name, pwd, mail, gestionnaire);
            }
        } catch (SQLException e) {
            throw new RuntimeException(e);
        }
        return selectedUser;
    }
}
