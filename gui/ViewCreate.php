<?php
// filepath: c:\Users\malio\Desktop\dossier important\coursIUTaix\archi logi\CC2\CC2-FOUCHER-BALDONI-BARBERO-BOUCHAMI\gui\ViewCreate.php

namespace gui;

include_once "View.php";

class ViewCreate extends View
{
    /**
     * @param $layout
     */
    public function __construct($layout)
    {
        parent::__construct($layout);

        $this->title = 'Coopérative Agricole - Création de compte';

        $this->content = '
            <div class="login-container">
                <form method="post" action="/index.php/create" class="login-form">
                    <h2>Créer un compte</h2>
                    <div class="form-group">
                        <label for="login">Identifiant :</label>
                        <input type="text" name="login" id="login" placeholder="Choisissez un identifiant" maxlength="20" required />
                    </div>
                    <div class="form-group">
                        <label for="password">Mot de passe :</label>
                        <input type="password" name="password" id="password" placeholder="Choisissez un mot de passe" minlength="8" required />
                    </div>
                    <div class="form-group">
                        <label for="name">Nom :</label>
                        <input type="text" name="name" id="name" placeholder="Votre nom" maxlength="20" required />
                    </div>
                    <div class="form-group">
                        <label for="firstName">Prénom :</label>
                        <input type="text" name="firstName" id="firstName" placeholder="Votre prénom" maxlength="20" required />
                    </div>
                    <div class="form-actions">
                        <input type="submit" value="Créer mon compte" class="submit-button">
                    </div>
                    <p class="create-account-link">
                        Déjà inscrit ? <a href="/index.php">Se connecter</a>
                    </p>
                </form>
            </div>
        ';
    }
}