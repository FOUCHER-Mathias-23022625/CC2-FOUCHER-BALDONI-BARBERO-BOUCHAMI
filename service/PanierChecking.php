<?php
// filepath: c:\Users\malio\Desktop\dossier important\coursIUTaix\archi logi\CC2\CC2-FOUCHER-BALDONI-BARBERO-BOUCHAMI\service\PanierChecking.php

namespace service;

class PanierChecking
{
    protected $paniersTxt = [];

    /**
     * @return array|mixed
     */
    public function getPaniersTxt()
    {
        return $this->paniersTxt;
    }

    /**
     * @param $data
     * @return void
     */
    public function getAllPaniers($data)
    {
        $paniers = $data->getAllPaniers();

        $this->paniersTxt = [];
        foreach ($paniers as $panier) {
            $this->paniersTxt[] = [
                'id' => $panier['id'],
                'nom' => $panier['nom'],
                'description' => $panier['description'],
                'prix' => $panier['prix'],
                'dateMAJ' => $panier['dateMAJ'],
                'stock' => $panier['stock'],
                'image' => $panier['image'] ?? ''
            ];
        }
    }

    /**
     * @param $id
     * @param $data
     * @return void
     */
    public function getPanier($id, $data)
    {
        $panier = $data->getPanier($id);

        if ($panier) {
            $this->paniersTxt = [[
                'id' => $panier['id'],
                'nom' => $panier['nom'],
                'description' => $panier['description'],
                'prix' => $panier['prix'],
                'dateMAJ' => $panier['dateMAJ'],
                'stock' => $panier['stock'],
                'image' => $panier['image'] ?? '',
                'contenu' => $panier['contenu'] ?? []
            ]];
        }
    }
}