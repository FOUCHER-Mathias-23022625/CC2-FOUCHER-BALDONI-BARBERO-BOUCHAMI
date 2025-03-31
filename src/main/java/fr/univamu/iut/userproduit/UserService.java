package fr.univamu.iut.userproduit;
import jakarta.json.bind.Jsonb;
import jakarta.json.bind.JsonbBuilder;
import java.util.ArrayList;



public class UserService
{
    protected UserRepositoryInterface UserRepo;

    public  UserService( UserRepositoryInterface UserRepo) {
        this.UserRepo = UserRepo;
    }

    public String getAllUsersJSON(){

        ArrayList<User> allUsers = UserRepo.getAllUser();


        for( User currentUser : allUsers ){
            currentUser.setMail("");
            currentUser.setPwd("");
        }


        String result = null;
        try( Jsonb jsonb = JsonbBuilder.create()){
            result = jsonb.toJson(allUsers);
        }
        catch (Exception e){
            System.err.println( e.getMessage() );
        }

        return result;
    }

    /**
     * Méthode retournant au format JSON les informations sur un utilisateur recherché
     * @param id l'identifiant de l'utilisateur recherché
     * @return une chaîne de caractère contenant les informations au format JSON
     */
    public String getUserJSON( String id ){
        String result = null;
        User myUser = UserRepo.getUser(id);


        if( myUser != null ) {


            try (Jsonb jsonb = JsonbBuilder.create()) {
                result = jsonb.toJson(myUser);
            } catch (Exception e) {
                System.err.println(e.getMessage());
            }
        }
        return result;
    }

    public boolean updateUser(String id, User user) {
        return UserRepo.updateUser(id, user.name, user.pwd, user.mail);
    }
}
