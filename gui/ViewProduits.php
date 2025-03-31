<?php
// filepath: c:\Users\malio\Desktop\dossier important\coursIUTaix\archi logi\CC2\CC2-FOUCHER-BALDONI-BARBERO-BOUCHAMI\gui\ViewProduits.php

namespace gui;

include_once "ViewLogged.php";

class ViewProduits extends ViewLogged
{
    public function __construct($layout, $login, $presenter)
    {
        parent::__construct($layout, $login);

        $this->title = 'Coopérative Agricole - Nos produits';

        // Utiliser le presenter pour générer le contenu HTML
        $this->content = $presenter->getAllProduitsHTML();
        
        // Ajouter le script JavaScript pour les contrôles de quantité
        $this->content .= '<script src="/gui/js/product.js"></script>';
    }
}