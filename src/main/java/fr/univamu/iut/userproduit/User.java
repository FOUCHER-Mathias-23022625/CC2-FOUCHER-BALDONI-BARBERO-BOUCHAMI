package fr.univamu.iut.userproduit;

public class User {
    protected String id;
    protected String name;
    protected String pwd;
    protected String mail;
    protected Boolean gestionnaire;

    public User(){

    }

    public User(String id, String name, String pwd, String mail, Boolean gestionnaire) {
        this.id = id;
        this.name = name;
        this.pwd = pwd;
        this.mail = mail;
        this.gestionnaire = gestionnaire;
    }



    public String getId() {
        return id;
    }
    public void setId(String id) {
        this.id = id;
    }

    public String getName() {
        return name;
    }
    public void setName(String name) {
        this.name = name;
    }

    public String getPwd() {
        return pwd;
    }
    public void setPwd(String pwd) {
        this.pwd = pwd;
    }

    public String getMail() {
        return mail;
    }
    public void setMail(String mail) {
        this.mail = mail;
    }

    public Boolean getGestionnaire() {
        return gestionnaire;
    }

    public void setGestionnaire(Boolean gestionnaire) {
        this.gestionnaire = gestionnaire;
    }

    public String toString() {
        return "User [id=" + id + ", name=" + name + ", pwd=" + pwd + ", mail=" + mail + ", gestionnaire=" + gestionnaire + "]";
    }
}
