<?php
// filepath: c:\Users\malio\Desktop\dossier important\coursIUTaix\archi logi\CC2\CC2-FOUCHER-BALDONI-BARBERO-BOUCHAMI\data\ProduitJsonAccess.php

namespace data;

class ProduitJsonAccess
{
    private $filePath;
    private $produits = [];

    /**
     * @param $filePath
     */
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
        $this->loadProduits();
    }

    /**
     * @return void
     */
    private function loadProduits()
    {
        if (file_exists($this->filePath)) {
            $jsonData = file_get_contents($this->filePath);
            $this->produits = json_decode($jsonData, true) ?: [];
        }
    }

    /**
     * @return array
     */
    public function getAllProduits()
    {
        return $this->produits;
    }

    /**
     * @param $id
     * @return mixed|null
     */
    public function getProduit($id)
    {
        foreach ($this->produits as $produit) {
            if ($produit['id'] == $id) {
                return $produit;
            }
        }
        return null;
    }

    /**
     * @param $id
     * @param $quantity
     * @return bool
     */
    public function updateStock($id, $quantity)
    {
        foreach ($this->produits as &$produit) {
            if ($produit['id'] == $id) {
                $produit['stock'] -= $quantity;
                if ($produit['stock'] < 0) $produit['stock'] = 0;
                
                $jsonData = json_encode($this->produits, JSON_PRETTY_PRINT);
                file_put_contents($this->filePath, $jsonData);
                return true;
            }
        }
        return false;
    }
}