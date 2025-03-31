package fr.univamu.iut.userproduit;
import jakarta.inject.Inject;
import jakarta.ws.rs.*;
import jakarta.ws.rs.core.Response;

@Path("/user")
public class UserRessource {

    private UserService service;

    public UserRessource(){}

    public @Inject UserRessource(UserRepositoryInterface UserRepo){
        this.service = new UserService(UserRepo) ;
    }

    public UserRessource(UserService service ){
        this.service = service;
    }

    @GET
    @Produces("application/json")
    public String getAllUser() {
        return service.getAllUsersJSON();
    }

    @GET
    @Path("{id}")
    @Produces("application/json")
    public String getUser( @PathParam("id") String id){

        String result = service.getUserJSON(id);


        if( result == null )
            throw new NotFoundException();

        return result;
    }

    @PUT
    @Path("{id}")
    @Consumes("application/json")
    public Response updateUser(@PathParam("id") String id, User user ){

        // si le livre n'a pas été trouvé
        if( ! service.updateUser(id, user) )
            throw new NotFoundException();
        else
            return Response.ok("updated").build();
    }

}
