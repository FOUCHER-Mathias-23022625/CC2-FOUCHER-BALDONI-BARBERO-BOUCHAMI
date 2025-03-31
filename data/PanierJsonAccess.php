<?php
// filepath: c:\Users\malio\Desktop\dossier important\coursIUTaix\archi logi\CC2\CC2-FOUCHER-BALDONI-BARBERO-BOUCHAMI\data\PanierJsonAccess.php

namespace data;

class PanierJsonAccess
{
    private $filePath;
    private $paniers = [];

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
        $this->loadPaniers();
    }

    private function loadPaniers()
    {
        if (file_exists($this->filePath)) {
            $jsonData = file_get_contents($this->filePath);
            $this->paniers = json_decode($jsonData, true) ?: [];
        }
    }

    public function getAllPaniers()
    {
        return $this->paniers;
    }

    public function getPanier($id)
    {
        foreach ($this->paniers as $panier) {
            if ($panier['id'] == $id) {
                return $panier;
            }
        }
        return null;
    }

    public function updateStock($id, $quantity)
    {
        foreach ($this->paniers as &$panier) {
            if ($panier['id'] == $id) {
                $panier['stock'] -= $quantity;
                if ($panier['stock'] < 0) $panier['stock'] = 0;
                
                $jsonData = json_encode($this->paniers, JSON_PRETTY_PRINT);
                file_put_contents($this->filePath, $jsonData);
                return true;
            }
        }
        return false;
    }
}