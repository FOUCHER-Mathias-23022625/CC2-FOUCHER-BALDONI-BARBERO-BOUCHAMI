<?php
// filepath: c:\Users\malio\Desktop\dossier important\coursIUTaix\archi logi\CC2\CC2-FOUCHER-BALDONI-BARBERO-BOUCHAMI\gui\ViewConfirmation.php

namespace gui;

include_once "ViewLogged.php";

class ViewConfirmation extends ViewLogged
{
    public function __construct($layout, $login, $commandeId)
    {
        parent::__construct($layout, $login);

        $this->title = 'Coopérative Agricole - Confirmation de commande';

        $this->content = '
        <div class="confirmation-container">
            <div class="confirmation-message">
                <h1>Commande confirmée !</h1>
                <p>Votre commande a été enregistrée avec succès.</p>
                <p>Numéro de commande : <strong>#' . $commandeId . '</strong></p>
                <p>Vous recevrez un récapitulatif par email. Merci pour votre confiance !</p>
            </div>
            
            <div class="confirmation-info">
                <h2>Informations importantes</h2>
                <p>Veuillez vous présenter à votre point de retrait à la date et l\'heure choisies avec une pièce d\'identité.</p>
                <p>Paiement à effectuer sur place (espèces, carte bancaire ou chèque acceptés).</p>
            </div>
            
            <div class="confirmation-actions">
                <a href="/index.php/mes-commandes" class="button">Voir mes commandes</a>
                <a href="/index.php/accueil" class="button secondary">Retour à l\'accueil</a>
            </div>
        </div>
        ';
    }
}