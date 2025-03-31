package fr.univamu.iut.userproduit;
import java.util.*;


public interface UserRepositoryInterface
{
    public void close();

    public User getUser( String id );

    public User getUserByName(String name);

    public ArrayList<User> getAllUser() ;

    public boolean updateUser( String id, String name, String pwd, String mail);
}
