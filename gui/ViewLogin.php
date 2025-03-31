<?php
namespace gui;

include_once "View.php";

class ViewLogin extends View
{
    public function __construct($layout)
    {
        parent::__construct($layout);

        $this->title = 'Exemple produits Basic PHP: Connexion';

        $this->content = '
            <form method="post" action="/index.php">
                <label for="name"> Votre nom : </label>
                <input type="text" name="name" id="name" placeholder="defaut" maxlength="12" required />
                <br />
                <label for="pwd"> Votre mot de passe : </label> 
                <input type="password" name="pwd" id="pwd" minlength="12" required />
        
                <input type="submit" value="Envoyer">
            </form>
            
            <a href="/index.php/create">Création d\'un nouveau compte</a>
            ';
    }
}