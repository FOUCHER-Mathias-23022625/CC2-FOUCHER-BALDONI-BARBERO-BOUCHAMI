package fr.univamu.iut.apicommandes;


import java.sql.Date;

public class Commande {

    protected int id;
    protected int prix;
    protected String localisationRetrait;
    protected Date dateRetrait;

    public Commande(int id, int prix, String localisationRetrait, Date dateRetrait) {
        this.id = id;
        this.prix = prix;
        this.localisationRetrait = localisationRetrait;
        this.dateRetrait = dateRetrait;
    }

    public Commande() {
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public int getPrix() {
        return prix;
    }

    public void setPrix(int prix) {
        this.prix = prix;
    }

    public String getLocalisationRetrait() {
        return localisationRetrait;
    }

    public void setLocalisationRetrait(String localisationRetrait) {
        this.localisationRetrait = localisationRetrait;
    }

    public Date getDateRetrait() {
        return dateRetrait;
    }

    public void setDateRetrait(Date dateRetrait) {
        this.dateRetrait = dateRetrait;
    }

    @Override
    public String toString() {
        return "Commande{" +
                "id=" + id +
                ", prix=" + prix +
                ", localisationRetrait='" + localisationRetrait + '\'' +
                ", dateRetrait=" + dateRetrait +
                '}';
    }

}
