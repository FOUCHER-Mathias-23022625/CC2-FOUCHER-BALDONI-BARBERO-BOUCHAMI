<?php
// filepath: c:\Users\malio\Desktop\dossier important\coursIUTaix\archi logi\CC2\CC2-FOUCHER-BALDONI-BARBERO-BOUCHAMI\gui\ViewPaniers.php

namespace gui;

include_once "ViewLogged.php";

class ViewPaniers extends ViewLogged
{
    public function __construct($layout, $login, $presenter)
    {
        parent::__construct($layout, $login);

        $this->title = 'Coopérative Agricole - Paniers disponibles';

        // Utiliser le presenter pour générer le contenu HTML
        $this->content = $presenter->getAllPaniersHTML();
    }
}