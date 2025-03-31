<?php
namespace gui;

include_once "ViewLogged.php";

class ViewPaniers extends ViewLogged
{
    public function __construct($layout, $login, $presenter)
    {
        parent::__construct($layout, $login);

        $this->title = 'Coopérative Agricole - Paniers disponibles';

        // Le contenu HTML devrait idéalement venir du presenter
        // Pour le moment, utilisons un contenu statique pour démonstration
        $this->content = '
        <h1>Paniers disponibles</h1>
        <div class="paniers-container">
            <div class="panier-card">
                <h2>Panier Fruits & Légumes</h2>
                <div class="panier-description">
                    <p>Panier de saison comprenant des fruits et légumes frais</p>
                    <p>Dernière mise à jour: 28/03/2025</p>
                </div>
                <div class="panier-price">18.50 €</div>
                <a href="/index.php/panier?id=1" class="view-details">Voir le détail</a>
                <button class="add-to-cart">Ajouter au panier</button>
            </div>
            
            <div class="panier-card">
                <h2>Panier Fromages</h2>
                <div class="panier-description">
                    <p>Assortiment de fromages locaux</p>
                    <p>Dernière mise à jour: 29/03/2025</p>
                </div>
                <div class="panier-price">22.00 €</div>
                <a href="/index.php/panier?id=2" class="view-details">Voir le détail</a>
                <button class="add-to-cart">Ajouter au panier</button>
            </div>
            
            <div class="panier-card">
                <h2>Panier Complet</h2>
                <div class="panier-description">
                    <p>Fruits, légumes, œufs et produits laitiers</p>
                    <p>Dernière mise à jour: 30/03/2025</p>
                </div>
                <div class="panier-price">32.00 €</div>
                <a href="/index.php/panier?id=3" class="view-details">Voir le détail</a>
                <button class="add-to-cart">Ajouter au panier</button>
            </div>
        </div>
        ';
    }
}