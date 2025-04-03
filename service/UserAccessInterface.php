<?php

namespace service;

interface UserAccessInterface
{
    /**
     * @param $login
     * @param $password
     * @return mixed
     */
    public function getUser($login, $password);
}