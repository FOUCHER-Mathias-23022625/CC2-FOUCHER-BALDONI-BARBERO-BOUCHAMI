<?php
// filepath: c:\Users\malio\Desktop\dossier important\coursIUTaix\archi logi\CC2\CC2-FOUCHER-BALDONI-BARBERO-BOUCHAMI\data\UserJsonAccess.php

namespace data;

use service\UserAccessInterface;
include_once "service/UserAccessInterface.php";

use domain\User;
include_once "domain/User.php";

class UserJsonAccess implements UserAccessInterface
{
    private $filePath;
    private $users = [];

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
        $this->loadUsers();
    }

    private function loadUsers()
    {
        if (file_exists($this->filePath)) {
            $jsonData = file_get_contents($this->filePath);
            $this->users = json_decode($jsonData, true) ?: [];
        }
    }

    private function saveUsers()
    {
        $jsonData = json_encode($this->users, JSON_PRETTY_PRINT);
        file_put_contents($this->filePath, $jsonData);
    }

    public function getUser($login, $password)
    {
        foreach ($this->users as $userData) {
            if ($userData['login'] === $login && $userData['password'] === $password) {
                return new User(
                    $userData['login'],
                    $userData['password'],
                    $userData['name'],
                    $userData['firstName'],
                    $userData['date']
                );
            }
        }
        return null;
    }

    public function createUser($login, $password, $name, $firstName)
    {
        // Vérifier si l'utilisateur existe déjà
        foreach ($this->users as $userData) {
            if ($userData['login'] === $login) {
                return false;
            }
        }

        // Ajouter le nouvel utilisateur
        $this->users[] = [
            'login' => $login,
            'password' => $password,
            'name' => $name,
            'firstName' => $firstName,
            'date' => date('Y-m-d H:i:s')
        ];

        $this->saveUsers();
        return true;
    }
}