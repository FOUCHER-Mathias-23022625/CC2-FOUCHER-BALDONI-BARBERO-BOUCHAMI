<?php
// filepath: c:\Users\malio\Desktop\dossier important\coursIUTaix\archi logi\CC2\CC2-FOUCHER-BALDONI-BARBERO-BOUCHAMI\service\ProduitChecking.php

namespace service;

class ProduitChecking
{
    protected $produitsTxt = [];

    public function getProduitsTxt()
    {
        return $this->produitsTxt;
    }

    public function getAllProduits($data)
    {
        $produits = $data->getAllProduits();

        $this->produitsTxt = [];
        foreach ($produits as $produit) {
            $this->produitsTxt[] = [
                'id' => $produit['id'],
                'nom' => $produit['nom'],
                'description' => $produit['description'],
                'prix' => $produit['prix'],
                'unite' => $produit['unite'],
                'stock' => $produit['stock'],
                'image' => $produit['image'] ?? '',
                'categorie' => $produit['categorie'] ?? 'Non catégorisé'
            ];
        }
    }

    public function getProduit($id, $data)
    {
        $produit = $data->getProduit($id);

        if ($produit) {
            $this->produitsTxt = [[
                'id' => $produit['id'],
                'nom' => $produit['nom'],
                'description' => $produit['description'],
                'prix' => $produit['prix'],
                'unite' => $produit['unite'],
                'stock' => $produit['stock'],
                'image' => $produit['image'] ?? '',
                'categorie' => $produit['categorie'] ?? 'Non catégorisé'
            ]];
        }
    }
}