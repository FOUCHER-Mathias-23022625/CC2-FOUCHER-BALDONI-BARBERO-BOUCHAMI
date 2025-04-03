<?php

namespace service;

class UserChecking
{
    /**
     * @param $login
     * @param $password
     * @param $data
     * @return bool
     */
    public function authenticate($login, $password, $data)
    {
        return ($data->getUser($login, $password) != null);
    }

}