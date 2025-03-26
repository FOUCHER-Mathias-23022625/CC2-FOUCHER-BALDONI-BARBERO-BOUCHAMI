<?php

namespace gui;

include_once "View.php";

class ViewAnnoncesEmploi extends View
{

    public function __construct($layout, $login, $presenter){
        parent::__construct($layout, $login);

        $this->title = "Exemple ANNonces Basic PHP : OFFRE DEMPLOI";

        $this->content = $presenter->getAllEmploiHTML();
    }

}