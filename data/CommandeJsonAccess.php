<?php
// filepath: c:\Users\malio\Desktop\dossier important\coursIUTaix\archi logi\CC2\CC2-FOUCHER-BALDONI-BARBERO-BOUCHAMI\data\CommandeJsonAccess.php

namespace data;

class CommandeJsonAccess
{
    private $filePath;
    private $commandes = [];

    /**
     * @param $filePath
     */
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
        $this->loadCommandes();
    }

    /**
     * @return void
     */
    private function loadCommandes()
    {
        if (file_exists($this->filePath)) {
            $jsonData = file_get_contents($this->filePath);
            $this->commandes = json_decode($jsonData, true) ?: [];
        }
    }

    /**
     * @return void
     */
    private function saveCommandes()
    {
        // S'assurer que le dossier existe
        $dir = dirname($this->filePath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        
        $jsonData = json_encode($this->commandes, JSON_PRETTY_PRINT);
        file_put_contents($this->filePath, $jsonData);
    }

    // Récupérer toutes les commandes

    /**
     * @return array
     */
    public function getAllCommandes()
    {
        return $this->commandes;
    }

    // Récupérer les commandes d'un utilisateur spécifique

    /**
     * @param $login
     * @return array
     */
    public function getCommandesByUser($login)
    {
        $userCommandes = [];
        foreach ($this->commandes as $commande) {
            if ($commande['login'] === $login) {
                $userCommandes[] = $commande;
            }
        }
        return $userCommandes;
    }

    // Récupérer une commande par son ID

    /**
     * @param $id
     * @return mixed|null
     */
    public function getCommande($id)
    {
        foreach ($this->commandes as $commande) {
            if ($commande['id'] == $id) {
                return $commande;
            }
        }
        return null;
    }

    // Créer une nouvelle commande

    /**
     * @param $login
     * @param $relai
     * @param $dateRetrait
     * @param $heureRetrait
     * @param $produits
     * @param $paniers
     * @param $total
     * @return int|mixed
     */
    public function createCommande($login, $relai, $dateRetrait, $heureRetrait, $produits, $paniers, $total)
    {
        // Générer un nouvel ID (le plus grand ID + 1)
        $id = 1;
        if (!empty($this->commandes)) {
            $maxId = max(array_column($this->commandes, 'id'));
            $id = $maxId + 1;
        }

        // Créer la nouvelle commande
        $nouvelleCommande = [
            'id' => $id,
            'login' => $login,
            'date' => date('Y-m-d H:i:s'),
            'status' => 'En attente',
            'relai' => $relai,
            'dateRetrait' => $dateRetrait,
            'heureRetrait' => $heureRetrait,
            'produits' => $produits,
            'paniers' => $paniers,
            'total' => $total
        ];

        // Ajouter la commande à la liste
        $this->commandes[] = $nouvelleCommande;
        
        // Sauvegarder les changements
        $this->saveCommandes();
        
        return $id;
    }

    // Mettre à jour le statut d'une commande

    /**
     * @param $id
     * @param $status
     * @return bool
     */
    public function updateCommandeStatus($id, $status)
    {
        foreach ($this->commandes as &$commande) {
            if ($commande['id'] == $id) {
                $commande['status'] = $status;
                $this->saveCommandes();
                return true;
            }
        }
        return false;
    }
}