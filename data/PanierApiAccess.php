<?php
namespace data;

class PanierApiAccess
{
    private $apiUrlPaniers;
    private $apiUrlProduits;
    private $paniers = [];
    private $produits = [];

    /**
     * @param $apiUrlPaniers
     * @param $apiUrlProduits
     */
    public function __construct($apiUrlPaniers, $apiUrlProduits)
    {
        $this->apiUrlPaniers = $apiUrlPaniers;
        $this->apiUrlProduits = $apiUrlProduits;
        $this->loadPaniers();
        $this->loadProduits();
    }

    /**
     * @return void
     */
    private function loadPaniers()
    {
        // Utiliser cURL pour récupérer les données de l'API
        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->apiUrlPaniers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_TIMEOUT => 30
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if (!$err) {
            $this->paniers = json_decode($response, true) ?: [];
            // Log pour débogage
            error_log("API Paniers: " . count($this->paniers) . " paniers chargés");
        } else {
            // En cas d'erreur, ajouter un log
            error_log("Erreur API Paniers: " . $err);
        }
    }

    /**
     * @return void
     */
    private function loadProduits()
    {
        // Utiliser cURL pour récupérer les produits de l'API
        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->apiUrlProduits,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_TIMEOUT => 30
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if (!$err) {
            $this->produits = json_decode($response, true) ?: [];
            // Log pour débogage
            error_log("API Produits: " . count($this->produits) . " produits chargés");
        } else {
            // En cas d'erreur, ajouter un log
            error_log("Erreur API Produits: " . $err);
        }
    }

    /**
     * @return array
     */
    public function getAllPaniers()
    {
        // Formater les paniers pour être compatibles avec l'application existante
        $formattedPaniers = [];
        
        foreach ($this->paniers as $panier) {
            $formattedPaniers[] = [
                'id' => $panier['id'],
                'nom' => $panier['nom'],
                'description' => $panier['description'],
                'prix' => $panier['prixTotal'], // Utiliser prixTotal comme prix
                'dateRetrait' => $panier['dateRetrait'],
                'relais' => $panier['relais'],
                'valide' => $panier['valide'],
                'dateMAJ' => date('Y-m-d'), // Date actuelle car non fournie par l'API
                'stock' => 10, // Valeur par défaut pour le stock
                // Pas d'image comme demandé
                'produits' => $panier['produits'] ?? []
            ];
        }
        
        return $formattedPaniers;
    }

    /**
     * @param $id
     * @return array|null
     */
    public function getPanier($id)
    {
        // D'abord, essayer de trouver le panier localement
        foreach ($this->paniers as $panier) {
            if (isset($panier['id']) && $panier['id'] == $id) {
                return [
                    'id' => $panier['id'],
                    'nom' => $panier['nom'],
                    'description' => $panier['description'],
                    'prix' => $panier['prixTotal'], // Utiliser prixTotal comme prix
                    'dateRetrait' => $panier['dateRetrait'],
                    'relais' => $panier['relais'],
                    'valide' => $panier['valide'],
                    'dateMAJ' => date('Y-m-d'), // Date actuelle car non fournie par l'API
                    'stock' => 10, // Valeur par défaut pour le stock
                    // Pas d'image comme demandé
                    'produits' => $panier['produits'] ?? []
                ];
            }
        }
        
        // Si non trouvé localement, faire une requête spécifique à l'API
        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->apiUrlPaniers . '/' . $id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_TIMEOUT => 30
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if (!$err) {
            $panier = json_decode($response, true);
            if ($panier) {
                return [
                    'id' => $panier['id'],
                    'nom' => $panier['nom'],
                    'description' => $panier['description'],
                    'prix' => $panier['prixTotal'], // Utiliser prixTotal comme prix
                    'dateRetrait' => $panier['dateRetrait'],
                    'relais' => $panier['relais'],
                    'valide' => $panier['valide'],
                    'dateMAJ' => date('Y-m-d'), // Date actuelle car non fournie par l'API
                    'stock' => 10, // Valeur par défaut pour le stock
                    // Pas d'image comme demandé
                    'produits' => $panier['produits'] ?? [],
                    'contenu' => $this->formatContenuPanier($panier['produits'] ?? [])
                ];
            }
        } else {
            error_log("Erreur API lors de la récupération du panier $id: " . $err);
        }
        
        return null;
    }
    
    /**
     * Formate les produits d'un panier en format compatible avec l'application
     */
    /**
     * @param $produits
     * @return array
     */
    private function formatContenuPanier($produits)
    {
        $contenu = [];
        foreach ($produits as $produit) {
            $contenu[] = [
                'produitId' => $produit['id'] ?? '',
                'nom' => $produit['nom'] ?? '',
                'quantite' => $produit['quantite'] ?? 1,
                'unite' => $produit['unite'] ?? 'pièce'
            ];
        }
        return $contenu;
    }

    /**
     * @return array
     */
    public function getAllProduits()
    {
        // Adapter le format des produits pour correspondre à ce qu'attend l'application
        $formattedProduits = [];
        
        foreach ($this->produits as $produit) {
            $formattedProduits[] = [
                'id' => $produit['id'],
                'nom' => $produit['nom'],
                'description' => $produit['description'] ?? 'Produit local et frais',
                'prix' => $produit['prix'],
                'unite' => $produit['unite'],
                'stock' => $produit['quantite'] ?? 10, // Adaptée à la nouvelle structure
                'image' => $produit['image'] ?? 'https://images.unsplash.com/photo-1607305387299-a3d9611cd469?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80',
                'categorie' => $produit['categorie'] ?? 'Non catégorisé'
            ];
        }
        
        return $formattedProduits;
    }

    /**
     * @param $id
     * @return array|null
     */
    public function getProduit($id)
    {
        // D'abord, essayer de trouver le produit localement
        foreach ($this->produits as $produit) {
            if (isset($produit['id']) && $produit['id'] == $id) {
                return [
                    'id' => $produit['id'],
                    'nom' => $produit['nom'],
                    'description' => $produit['description'] ?? 'Produit local et frais',
                    'prix' => $produit['prix'],
                    'unite' => $produit['unite'],
                    'stock' => $produit['quantite'] ?? 10, // Adaptée à la nouvelle structure
                    'image' => $produit['image'] ?? 'https://images.unsplash.com/photo-1607305387299-a3d9611cd469?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80',
                    'categorie' => $produit['categorie'] ?? 'Non catégorisé'
                ];
            }
        }
        
        // Si non trouvé localement, faire une requête spécifique à l'API
        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->apiUrlProduits . '/' . $id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_TIMEOUT => 30
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if (!$err) {
            $produit = json_decode($response, true);
            if ($produit) {
                return [
                    'id' => $produit['id'],
                    'nom' => $produit['nom'],
                    'description' => $produit['description'] ?? 'Produit local et frais',
                    'prix' => $produit['prix'],
                    'unite' => $produit['unite'],
                    'stock' => $produit['quantite'] ?? 10, // Adaptée à la nouvelle structure
                    'image' => $produit['image'] ?? 'https://images.unsplash.com/photo-1607305387299-a3d9611cd469?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80',
                    'categorie' => $produit['categorie'] ?? 'Non catégorisé'
                ];
            }
        } else {
            error_log("Erreur API lors de la récupération du produit $id: " . $err);
        }
        
        return null;
    }

    /**
     * @param $id
     * @param $quantity
     * @return true
     */
    public function updateStock($id, $quantity)
    {
        // Cette méthode pourrait être implémentée si l'API offre une fonctionnalité de mise à jour de stock
        // Pour l'instant, elle retourne simplement true pour simuler un succès
        return true;
    }
}