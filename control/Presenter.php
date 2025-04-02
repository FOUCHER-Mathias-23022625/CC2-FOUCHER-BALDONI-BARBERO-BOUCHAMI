<?php
// filepath: c:\Users\malio\Desktop\dossier important\coursIUTaix\archi logi\CC2\CC2-FOUCHER-BALDONI-BARBERO-BOUCHAMI\control\Presenter.php

namespace control;

class Presenter
{
    protected $annoncesCheck;
    protected $produitCheck;
    protected $panierCheck;
    protected $commandeCheck;


    public function __construct($annoncesCheck = null, $produitCheck = null, $panierCheck = null, $commandeCheck = null)
{
    $this->annoncesCheck = $annoncesCheck;
    $this->produitCheck = $produitCheck;
    $this->panierCheck = $panierCheck;
    $this->commandeCheck = $commandeCheck;
}

    // Méthodes existantes...

    // Nouvelle méthode pour les produits
    public function getAllProduitsHTML()
    {
        $content = '<h1>Nos produits</h1>';
        $content .= '<div class="products-container">';
        
        if ($this->produitCheck && $this->produitCheck->getProduitsTxt()) {
            foreach ($this->produitCheck->getProduitsTxt() as $produit) {
                $content .= '
                <div class="product-card">
                    <img src="' . htmlspecialchars($produit['image']) . '" alt="' . htmlspecialchars($produit['nom']) . '" class="product-image">
                    <div class="product-name">' . htmlspecialchars($produit['nom']) . '</div>
                    <div class="product-description">' . htmlspecialchars(substr($produit['description'], 0, 100)) . '...</div>
                    <div class="product-price">' . number_format($produit['prix'], 2, ',', ' ') . ' € / ' . htmlspecialchars($produit['unite']) . '</div>
                </div>';
            }
        } else {
            $content .= '<p>Aucun produit disponible pour le moment.</p>';
        }
        
        $content .= '</div>';
        return $content;
    }

    // Nouvelle méthode pour les paniers
    public function getAllPaniersHTML()
    {
        $content = '<h1>Paniers disponibles</h1>';
        $content .= '<div class="paniers-container">';
        
        if ($this->panierCheck && $this->panierCheck->getPaniersTxt()) {
            foreach ($this->panierCheck->getPaniersTxt() as $panier) {
                $content .= '
                <div class="panier-card">
                    <img src="' . htmlspecialchars($panier['image']) . '" alt="' . htmlspecialchars($panier['nom']) . '" class="panier-image">
                    <h2>' . htmlspecialchars($panier['nom']) . '</h2>
                    <div class="panier-description">
                        <p>' . htmlspecialchars($panier['description']) . '</p>
                        <p>Dernière mise à jour: ' . htmlspecialchars($panier['dateMAJ']) . '</p>
                    </div>
                    <div class="panier-price">' . number_format($panier['prix'], 2, ',', ' ') . ' €</div>
                    <a href="/index.php/panier?id=' . htmlspecialchars($panier['id']) . '" class="view-details">Voir le détail</a>
                    <button class="add-to-cart" data-id="' . htmlspecialchars($panier['id']) . '" data-type="panier">Ajouter au panier</button>
                </div>';
            }
        } else {
            $content .= '<p>Aucun panier disponible pour le moment.</p>';
        }
        
        $content .= '</div>';
        return $content;
    }

    // Nouvelle méthode pour le détail d'un panier
    public function getPanierDetailHTML()
    {
        if (!$this->panierCheck || !$this->panierCheck->getPaniersTxt()) {
            return '<p>Panier non trouvé.</p>';
        }
        
        $panier = $this->panierCheck->getPaniersTxt()[0];
        
        $content = '
        <div class="panier-detail">
            <h1>' . htmlspecialchars($panier['nom']) . '</h1>
            <p class="panier-description">' . htmlspecialchars($panier['description']) . '</p>
            <p class="update-date">Dernière mise à jour: ' . htmlspecialchars($panier['dateMAJ']) . '</p>
            
            <h2>Contenu du panier</h2>
            <table class="product-table">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Quantité</th>
                        <th>Unité</th>
                    </tr>
                </thead>
                <tbody>';
        
        if (isset($panier['contenu']) && is_array($panier['contenu'])) {
            foreach ($panier['contenu'] as $item) {
                $content .= '
                <tr>
                    <td>' . htmlspecialchars($item['nom'] ?? $item['produitNom'] ?? 'Produit inconnu') . '</td>
                    <td>' . htmlspecialchars($item['quantite']) . '</td>
                    <td>' . htmlspecialchars($item['unite']) . '</td>
                </tr>';
            }
        }
        
        $content .= '
                </tbody>
            </table>
            
            <div class="panier-price">
                <p>Prix total: <strong>' . number_format($panier['prix'], 2, ',', ' ') . ' €</strong></p>
            </div>
            
            <div class="action-buttons">
                <button class="add-to-cart" data-id="' . htmlspecialchars($panier['id']) . '" data-type="panier">Ajouter au panier</button>
                <a href="/index.php/paniers" class="back-button">Retour aux paniers</a>
            </div>
        </div>';
        
        return $content;
    }
    public function getMesCommandesHTML()
{
    $content = '<h1>Mes commandes</h1>';
    
    if ($this->commandeCheck && !empty($this->commandeCheck->getCommandesTxt())) {
        $content .= '
        <div class="commandes-container">
            <table class="commandes-table">
                <thead>
                    <tr>
                        <th>N° Commande</th>
                        <th>Date</th>
                        <th>Point de retrait</th>
                        <th>Date & Heure de retrait</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>';
        
        foreach ($this->commandeCheck->getCommandesTxt() as $commande) {
            $statut_class = '';
            switch ($commande['status']) {
                case 'Validée':
                    $statut_class = 'status-validated';
                    break;
                case 'En préparation':
                    $statut_class = 'status-preparing';
                    break;
                case 'En attente':
                    $statut_class = 'status-pending';
                    break;
            }
            
            $content .= '
                <tr>
                    <td>#' . htmlspecialchars($commande['id']) . '</td>
                    <td>' . htmlspecialchars($commande['date']) . '</td>
                    <td>' . htmlspecialchars($commande['relai']) . '</td>
                    <td>' . htmlspecialchars($commande['dateRetrait']) . ' à ' . htmlspecialchars($commande['heureRetrait']) . '</td>
                    <td>' . number_format($commande['total'], 2, ',', ' ') . ' €</td>
                    <td><span class="status ' . $statut_class . '">' . htmlspecialchars($commande['status']) . '</span></td>
                    <td><a href="/index.php/detail-commande?id=' . htmlspecialchars($commande['id']) . '" class="view-button">Détails</a></td>
                </tr>';
        }
        
        $content .= '
                </tbody>
            </table>
        </div>';
    } else {
        $content .= '<p class="no-commandes">Vous n\'avez pas encore passé de commande.</p>';
        $content .= '<div class="action-buttons"><a href="/index.php/paniers" class="back-button">Voir les paniers</a></div>';
    }
    
    return $content;
}
}