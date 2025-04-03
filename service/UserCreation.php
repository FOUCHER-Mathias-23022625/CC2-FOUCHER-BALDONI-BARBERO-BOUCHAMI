<?php

namespace service;

class UserCreation
{
    /**
     * @param $login
     * @param $password
     * @param $name
     * @param $firstName
     * @param $data
     * @return bool
     */
    public function createUser($login, $password, $name, $firstName, $data){
        return ($data->createUser($login, $password, $name, $firstName) != false );
    }

}