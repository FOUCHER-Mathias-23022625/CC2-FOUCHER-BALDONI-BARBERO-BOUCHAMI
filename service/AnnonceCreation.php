<?php

namespace service;

class AnnonceCreation
{

    public function createAnnonce($title,$body,$data){
        return ($data->createAnnonce($title,$body) != false);
    }

}