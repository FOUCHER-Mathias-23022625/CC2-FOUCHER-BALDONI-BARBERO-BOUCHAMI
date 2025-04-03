<?php
// filepath: c:\Users\malio\Desktop\dossier important\coursIUTaix\archi logi\CC2\CC2-FOUCHER-BALDONI-BARBERO-BOUCHAMI\data\UserApiAccess.php

namespace data;

use service\UserAccessInterface;
include_once "service/UserAccessInterface.php";

use domain\User;
include_once "domain/User.php";

class UserApiAccess implements UserAccessInterface
{
    private $apiUrl;
    private $users = [];

    /**
     * @param $apiUrl
     */
    public function __construct($apiUrl)
    {
        $this->apiUrl = $apiUrl;
        $this->loadUsers();
    }

    /**
     * @return void
     */
    private function loadUsers()
    {
        // Utiliser cURL pour récupérer les données de l'API
        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_TIMEOUT => 30
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if (!$err) {
            $this->users = json_decode($response, true) ?: [];
            // Log pour débogage
            error_log("API User: " . count($this->users) . " utilisateurs chargés");
        } else {
            // En cas d'erreur, vous pourriez ajouter un log ou une gestion d'erreur
            error_log("Erreur API User: " . $err);
        }
    }

    /**
     * @param $login
     * @param $password
     * @return User|null
     */
    public function getUser($login, $password)
    {
        // Ajout d'un log de débogage
        error_log("Tentative de connexion: login=$login, password=$password");
        
        foreach ($this->users as $userData) {
            // Dans votre API, 'id' est utilisé comme identifiant de l'utilisateur
            // et 'pwd' comme mot de passe (qui semble être vide dans vos exemples)
            if (isset($userData['id']) && isset($userData['pwd']) && 
                $userData['id'] === $login && $userData['pwd'] === $password) {
                
                // Le nom est disponible, mais il n'y a pas de 'firstName'
                // On peut utiliser une valeur par défaut ou laisser vide
                return new User(
                    $userData['id'],         // login = id dans votre API
                    $userData['pwd'],        // password = pwd dans votre API
                    $userData['name'],       // name est présent dans votre API
                    $userData['mail'] ?? '', // utilisation du mail comme prénom ou champ vide
                    date('Y-m-d H:i:s')      // date actuelle car non fournie par l'API
                );
            }
        }
        
        // Vérifier si l'utilisateur existe mais le mot de passe est vide
        // Ceci est une solution temporaire si votre API ne stocke pas de mots de passe
        foreach ($this->users as $userData) {
            if (isset($userData['id']) && $userData['id'] === $login) {
                // Si le mot de passe dans l'API est vide, on accepte n'importe quel mot de passe
                if (empty($userData['pwd'])) {
                    return new User(
                        $userData['id'],
                        $password, // On utilise le mot de passe fourni
                        $userData['name'],
                        $userData['mail'] ?? '',
                        date('Y-m-d H:i:s')
                    );
                }
            }
        }
        
        // Si aucun utilisateur trouvé, ajouter un log
        error_log("Aucun utilisateur trouvé pour login=$login");
        return null;
    }

    /**
     * @param $login
     * @param $password
     * @param $name
     * @param $firstName
     * @return bool
     */
    public function createUser($login, $password, $name, $firstName)
    {
        // Vérifier si l'utilisateur existe déjà
        foreach ($this->users as $userData) {
            if (isset($userData['id']) && $userData['id'] === $login) {
                return false;
            }
        }
        
        // Adapter le format pour correspondre à ce qu'attend votre API
        $newUser = [
            'id' => $login,
            'pwd' => $password,
            'name' => $name,
            'mail' => $firstName, // Utiliser firstName comme mail si nécessaire
            'gestionnaire' => false // Par défaut, les nouveaux utilisateurs ne sont pas gestionnaires
        ];
        
        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($newUser),
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_TIMEOUT => 30
        ]);
        
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if (!$err && ($httpCode == 200 || $httpCode == 201)) {
            // Recharger les utilisateurs depuis l'API
            $this->loadUsers();
            return true;
        }
        
        // Log de l'échec pour le débogage
        error_log("Échec création utilisateur: Erreur=" . $err . ", Code=" . $httpCode);
        return false;
    }
    
    // Méthode utile pour le débogage
    public function dumpUsers() {
        echo "<pre>";
        print_r($this->users);
        echo "</pre>";
    }
}