<?php
// filepath: c:\Users\malio\Desktop\dossier important\coursIUTaix\archi logi\CC2\CC2-FOUCHER-BALDONI-BARBERO-BOUCHAMI\gui\ViewMesCommandes.php

namespace gui;

include_once "ViewLogged.php";

class ViewMesCommandes extends ViewLogged
{
    /**
     * @param $layout
     * @param $login
     * @param $presenter
     */
    public function __construct($layout, $login, $presenter)
    {
        parent::__construct($layout, $login);

        $this->title = 'Coopérative Agricole - Mes commandes';

        // Utiliser le presenter pour générer le contenu HTML
        $this->content = $presenter->getMesCommandesHTML();
    }
}