package fr.univamu.iut.userproduit;

public class UserAuthenticationService {

    /**
     * Objet permettant d'accéder au dépôt où sont stockées les informations sur les utilisateurs
     */
    protected UserRepositoryInterface userRepo ;

    /**
     * Constructeur permettant d'injecter l'accès aux données
     * @param userRepo objet implémentant l'interface d'accès aux données
     */
    public UserAuthenticationService(UserRepositoryInterface userRepo) {
        this.userRepo = userRepo;
    }

    /**
     * Méthode d'authentifier un utilisateur
     * @param name nom de l'utilisateur
     * @param pwd mot de passe de l'utilisateur
     * @return true si l'utilisateur a été authentifié, false sinon
     */
    public boolean isValidUser(String name, String pwd) {
        User currentUser = userRepo.getUserByName(name);


        if (currentUser == null)
            return false;


        if (!currentUser.pwd.equals(pwd))
            return false;

        return true;
    }
}