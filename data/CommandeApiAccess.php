<?php

namespace data;

class CommandeApiAccess
{
    private $apiUrl;
    private $commandes = [];

    /**
     * @param $apiUrl
     */
    public function __construct($apiUrl)
    {
        $this->apiUrl = $apiUrl;
    }
    
    /**
     * Récupère toutes les commandes depuis l'API
     */
    /**
     * @return array|mixed
     */
    public function getAllCommandes()
    {
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
            $this->commandes = json_decode($response, true) ?: [];
            return $this->commandes;
        } else {
            error_log("Erreur API Commandes: " . $err);
            return [];
        }
    }
    
    /**
     * Récupère les commandes d'un utilisateur spécifique
     * Note: L'API ne semble pas fournir ce point d'entrée, donc on filtre côté client
     */
    /**
     * @param $login
     * @return array
     */
    public function getCommandesByUser($login)
    {
        $allCommandes = $this->getAllCommandes();
        $userCommandes = [];
        
        foreach ($allCommandes as $commande) {
            // On suppose que la commande contient une propriété avec l'utilisateur
            // Adaptez cette logique en fonction de votre API
            if (isset($commande['login']) && $commande['login'] === $login) {
                $userCommandes[] = $commande;
            }
        }
        
        return $userCommandes;
    }
    
    /**
     * Récupère une commande par son ID
     */
    /**
     * @param $id
     * @return mixed|null
     */
    public function getCommande($id)
    {
        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->apiUrl . '/' . $id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_TIMEOUT => 30
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if (!$err) {
            $commande = json_decode($response, true);
            
            // Récupérer également le contenu de la commande
            if ($commande) {
                $contenu = $this->getCommandeContenu($id);
                if ($contenu) {
                    $commande['contenu'] = $contenu;
                }
                return $commande;
            }
        } else {
            error_log("Erreur API Commande $id: " . $err);
        }
        
        return null;
    }
    
    /**
     * Récupère le contenu d'une commande
     */
    /**
     * @param $id
     * @return mixed|null
     */
    private function getCommandeContenu($id)
    {
        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->apiUrl . '/' . $id . '/contenu',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_TIMEOUT => 30
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if (!$err) {
            return json_decode($response, true);
        } else {
            error_log("Erreur API Contenu Commande $id: " . $err);
            return null;
        }
    }
    
    /**
     * Crée une nouvelle commande
     */
    /**
     * @param $login
     * @param $relai
     * @param $dateRetrait
     * @param $heureRetrait
     * @param $produits
     * @param $paniers
     * @param $total
     * @return mixed|null
     */
    public function createCommande($login, $relai, $dateRetrait, $heureRetrait, $produits, $paniers, $total)
    {
        // Adapter le format pour la nouvelle API
        $commandeData = [
            "id" => uniqid(), // Générer un ID unique ou laisser l'API le faire
            "prix" => $total,
            "localisationRetrait" => $relai,
            "dateRetrait" => $dateRetrait,
            "login" => $login, // Supposant que l'API accepte un champ login
            "heure" => $heureRetrait // Supposant que l'API accepte un champ heure
        ];
        
        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($commandeData),
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_TIMEOUT => 30
        ]);
        
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if (!$err && ($httpCode == 200 || $httpCode == 201)) {
            // Récupérer l'ID de la commande créée
            $createdCommande = json_decode($response, true);
            $commandeId = $createdCommande['id'] ?? null;
            
            if ($commandeId) {
                // Ajouter les paniers à la commande
                foreach ($paniers as $panier) {
                    $this->addPanierToCommande($commandeId, $panier['id'], $panier['quantite']);
                }
                
                // Retourner l'ID de la commande
                return $commandeId;
            }
        }
        
        error_log("Échec création commande: Erreur=" . $err . ", Code=" . $httpCode);
        return null;
    }
    
    /**
     * Ajoute un panier à une commande
     */
    /**
     * @param $commandeId
     * @param $panierId
     * @param $quantite
     * @return bool
     */
    private function addPanierToCommande($commandeId, $panierId, $quantite)
    {
        $panierData = [
            "idPanier" => $panierId,
            "quantite" => $quantite
        ];
        
        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->apiUrl . '/' . $commandeId . '/contenu',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($panierData),
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_TIMEOUT => 30
        ]);
        
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err || ($httpCode != 200 && $httpCode != 201)) {
            error_log("Échec ajout panier à commande: Erreur=" . $err . ", Code=" . $httpCode);
            return false;
        }
        
        return true;
    }
    
    /**
     * Met à jour le statut d'une commande
     */
    /**
     * @param $id
     * @param $status
     * @return false
     */
    public function updateCommandeStatus($id, $status)
    {
        // La nouvelle API ne semble pas avoir de champ statut explicite
        // On pourrait utiliser PUT pour mettre à jour d'autres champs si nécessaire
        return false;
    }
}