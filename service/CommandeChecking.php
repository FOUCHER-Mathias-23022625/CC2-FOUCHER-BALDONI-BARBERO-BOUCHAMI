<?php
// filepath: c:\Users\malio\Desktop\dossier important\coursIUTaix\archi logi\CC2\CC2-FOUCHER-BALDONI-BARBERO-BOUCHAMI\service\CommandeChecking.php

namespace service;

class CommandeChecking
{
    protected $commandesTxt = [];

    /**
     * @return array|mixed
     */
    public function getCommandesTxt()
    {
        return $this->commandesTxt;
    }

    // Récupérer toutes les commandes

    /**
     * @param $data
     * @return void
     */
    public function getAllCommandes($data)
    {
        $commandes = $data->getAllCommandes();
        $this->commandesTxt = $commandes;
    }

    // Récupérer les commandes d'un utilisateur

    /**
     * @param $login
     * @param $data
     * @return void
     */
    public function getCommandesByUser($login, $data)
    {
        $commandes = $data->getCommandesByUser($login);
        $this->commandesTxt = $commandes;
    }

    // Récupérer une commande spécifique

    /**
     * @param $id
     * @param $data
     * @return void
     */
    public function getCommande($id, $data)
    {
        $commande = $data->getCommande($id);
        if ($commande) {
            $this->commandesTxt = [$commande];
        }
    }

    // Créer une commande

    /**
     * @param $login
     * @param $commandeData
     * @param $data
     * @return mixed
     */
    public function createCommande($login, $commandeData, $data)
    {
        $relai = $commandeData['relai'] ?? '';
        $dateRetrait = $commandeData['date'] ?? '';
        $heureRetrait = $commandeData['heure'] ?? '';
        $produits = $commandeData['produits'] ?? [];
        $paniers = $commandeData['paniers'] ?? [];
        $total = $commandeData['total'] ?? 0;

        return $data->createCommande(
            $login,
            $relai,
            $dateRetrait,
            $heureRetrait,
            $produits,
            $paniers,
            $total
        );
    }

    // Mettre à jour le statut d'une commande

    /**
     * @param $id
     * @param $status
     * @param $data
     * @return mixed
     */
    public function updateCommandeStatus($id, $status, $data)
    {
        return $data->updateCommandeStatus($id, $status);
    }
}