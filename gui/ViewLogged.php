<?php

namespace gui;

include_once "View.php";

abstract class ViewLogged extends View
{
    protected $connexion = '';

    /**
     * @param $layout
     * @param $login
     */
    public function __construct($layout, $login)
    {
        parent::__construct($layout);

        $this->connexion = "<p> Bonjour $login </p>".'<a href="/index.php/logout">Déconnexion</a>';
    }

    public function display()
    {
        $this->layout->display( $this->title, $this->connexion, $this->content );
    }

}