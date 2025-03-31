<?php
// filepath: c:\Users\malio\Desktop\dossier important\coursIUTaix\archi logi\CC2\CC2-FOUCHER-BALDONI-BARBERO-BOUCHAMI\gui\ViewLogin.php

namespace gui;

include_once "View.php";

class ViewLogin extends View
{
    public function __construct($layout)
    {
        parent::__construct($layout);

        $this->title = 'Coopérative Agricole - Connexion';

        $this->content = '
            <div class="login-container">
                <form method="post" action="/index.php/login" class="login-form">
                    <h2>Connexion</h2>
                    <div class="form-group">
                        <label for="login">Identifiant :</label>
                        <input type="text" name="login" id="login" placeholder="Votre identifiant" required />
                    </div>
                    <div class="form-group">
                        <label for="password">Mot de passe :</label>
                        <input type="password" name="password" id="password" placeholder="Votre mot de passe" required />
                    </div>
                    <div class="form-actions">
                        <input type="submit" value="Se connecter" class="submit-button">
                    </div>
                    <p class="create-account-link">
                        Pas encore de compte ? <a href="/index.php/create">Créer un compte</a>
                    </p>
                </form>
            </div>
        ';
    }
}