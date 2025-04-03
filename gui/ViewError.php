<?php

namespace gui;

include_once "View.php";

class ViewError extends View
{
    /**
     * @param $layout
     * @param $error
     * @param $redirect
     */
    public function __construct($layout, $error, $redirect)
    {
        parent::__construct($layout);

        header( "refresh:5;url=$redirect" );
        echo $error;
    }
}