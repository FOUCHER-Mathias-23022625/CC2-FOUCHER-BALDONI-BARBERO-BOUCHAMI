<?php
namespace gui;

include_once "ViewLogged.php";

class ViewAnnonces extends ViewLogged
{
    public function __construct($layout, $login, $presenter)
    {
        parent::__construct($layout, $login);

        $this->title= 'Exemple Annonces Basic PHP: Annonces';

        $this->content = $presenter->getAllAnnoncesHTML();
        $this->content .= '<a href="/index.php/createAnnonce"> Creation dun nouvelle annonce</a>';
    }
}