package fr.univamu.iut.apicommandes;

import jakarta.enterprise.context.ApplicationScoped;
import jakarta.inject.Inject;
import jakarta.ws.rs.*;
import jakarta.ws.rs.core.Response;
import jakarta.ws.rs.core.MediaType;

import java.util.List;

@Path("/commandes")
@ApplicationScoped
public class CommandeResource {

    private CommandeService service;

    public CommandeResource() {
    }

    @Inject
    public CommandeResource(CommandeRepositoryInterface repo) {
        this.service = new CommandeService(repo);
    }

    public CommandeResource(CommandeService service) {
        this.service = service;
    }

    @GET
    @Produces(MediaType.APPLICATION_JSON)
    public Response getAllCommandes() {
        List<Commande> commandes = service.getAllCommandes();
        return Response.ok(commandes).build();
    }

    @GET
    @Path("{reference}")
    @Produces(MediaType.APPLICATION_JSON)
    public Response getCommandeById(@PathParam("reference") int reference) {
        Commande commande = service.getCommandeById(reference);
        if (commande == null) {
            return Response.status(Response.Status.NOT_FOUND).entity("Commande no ntrouvée").build();
        }
        return Response.ok(commande).build();
    }

    @POST
    @Consumes(MediaType.APPLICATION_JSON)
    public Response addCommande(Commande commande) {
        if (service.addCommande(commande)) {
            return Response.status(Response.Status.CREATED).entity("Commande ajoutée").build();
        }
        return Response.status(Response.Status.BAD_REQUEST).entity("Erreur lors de l'ajout de la commande").build();
    }

    @PUT
    @Path("{reference}")
    @Consumes(MediaType.APPLICATION_JSON)
    public Response updateCommande(@PathParam("reference") int reference, Commande commande) {
        if (!service.updateCommande(reference, commande)) {
            return Response.status(Response.Status.NOT_FOUND).entity("Commande non trouvée").build();
        }
        return Response.ok("Commande mise à jour").build();
    }

    @DELETE
    @Path("{reference}")
    public Response deleteCommande(@PathParam("reference") int reference) {
        if (!service.removeCommande(reference)) {
            return Response.status(Response.Status.NOT_FOUND).entity("commande non trouvée").build();
        }
        return Response.ok("Commande supprimée").build();
    }

    @GET
    @Path("{reference}/contenu")
    @Produces(MediaType.APPLICATION_JSON)
    public Response getCommandeContient(@PathParam("reference") int reference) {
        List<CommandeContient> contenu = service.getCommandeContient(reference);
        if (contenu == null || contenu.isEmpty()) {
            return Response.status(Response.Status.NOT_FOUND).entity("Aucun contenu trouvé pou rcette commande").build();
        }
        return Response.ok(contenu).build();
    }

    @POST
    @Path("{reference}/contenu")
    @Consumes(MediaType.APPLICATION_JSON)
    public Response addCommandeContient(@PathParam("reference") int reference, CommandeContient commandeContient) {
        commandeContient.setIdCommande(reference);
        if (!service.addCommandeContient(commandeContient)) {
            return Response.status(Response.Status.BAD_REQUEST).entity("Erreur lors de l' ajout du contenu").build();
        }
        return Response.status(Response.Status.CREATED).entity("Contenu ajoute").build();
    }

    @PUT
    @Path("{reference}/contenu/{idPanier}")
    @Consumes(MediaType.APPLICATION_JSON)
    public Response updateCommandeContient(@PathParam("reference") int reference,
                                           @PathParam("idPanier") int idPanier,
                                           CommandeContient commandeContient) {
        if (!service.updateCommandeContient(reference, idPanier, commandeContient.getQuantite())) {
            return Response.status(Response.Status.NOT_FOUND).entity("Contenu non trouvé").build();
        }
        return Response.ok("Contenu mis a jour").build();
    }

    @DELETE
    @Path("{reference}/contenu/{idPanier}")
    public Response deleteCommandeContient(@PathParam("reference") int reference,
                                           @PathParam("idPanier") int idPanier) {
        if (!service.removeCommandeContient(reference, idPanier)) {
            return Response.status(Response.Status.NOT_FOUND).entity("contenu non trouvé").build();
        }
        return Response.ok("cotenu supprimé").build();
    }

    @GET
    @Path("{reference}/total")
    @Produces(MediaType.APPLICATION_JSON)
    public Response getCommandeTotal(@PathParam("reference") int reference) {
        double total = service.getCommandeTotal(reference);
        return Response.ok(total).build();
    }
}