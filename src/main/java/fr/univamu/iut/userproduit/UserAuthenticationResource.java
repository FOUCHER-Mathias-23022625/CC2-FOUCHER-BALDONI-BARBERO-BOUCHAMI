package fr.univamu.iut.userproduit;

import jakarta.enterprise.context.ApplicationScoped;
import jakarta.inject.Inject;
import jakarta.ws.rs.*;
import jakarta.ws.rs.container.ContainerRequestContext;
import jakarta.ws.rs.core.Context;
import jakarta.ws.rs.core.Response;

import java.io.UnsupportedEncodingException;
import java.util.Base64;

/**
 * Ressource associée à l'authentification des utilisateurs
 * (point d'accès de l'API REST)
 */
@Path("/authenticate")
@ApplicationScoped
public class UserAuthenticationResource {

    /**
     * Service utilisé pour accéder aux données des utilisateurs
     */
    private UserAuthenticationService auth;

    /**
     * Constructeur par défaut
     */
    public UserAuthenticationResource(){}

    /**
     * Constructeur permettant d'initialiser le service avec une interface d'accès aux données
     * @param userRepo objet implémentant l'interface d'accès aux données
     */
    public @Inject UserAuthenticationResource( UserRepositoryInterface userRepo ){
        this.auth = new UserAuthenticationService( userRepo ) ;
    }

    /**
     * Enpoint permettant de publier de tous les utilisateurs enregistrés
     * @return la liste des utilisateurs (avec leurs informations) au format JSON
     */
    @GET
    @Produces("text/plain")
    public Response authenticate(@Context ContainerRequestContext requestContext) throws UnsupportedEncodingException {
        boolean res = false;

        // Récupération du header de la requête HTTP
        String authHeader = requestContext.getHeaderString("Authorization");
        if (authHeader == null || !authHeader.startsWith("Basic")) {
            return Response.status(Response.Status.UNAUTHORIZED).header("WWW-Authenticate", "Basic").build();
        }

        // Récupération et transformation du nom et du mot de passe encodé en base 64
        String[] tokens = (new String(Base64.getDecoder().decode(authHeader.split(" ")[1]), "UTF-8")).split(":");
        final String name = tokens[0]; // Ici, on considère que le premier token est le nom
        final String pwd = tokens[1];

        // Ajouter un log pour déboguer si nécessaire
        System.out.println("Tentative d'authentification pour l'utilisateur : " + name);

        // authentification avec le nom
        res = auth.isValidUser(name, pwd);

        return Response.ok(String.valueOf(res)).build();
    }
}
