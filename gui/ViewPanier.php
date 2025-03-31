<?php
namespace gui;

include_once "ViewLogged.php";

class ViewPanier extends ViewLogged
{
    public function __construct($layout, $login, $presenter)
    {
        parent::__construct($layout, $login);

        $this->title = 'Coopérative Agricole - Détail du panier';

        // Simulation de contenu statique pour le moment
        $this->content = '
        <div class="panier-detail">
            <h1>Panier Fruits & Légumes</h1>
            <p class="panier-description">Panier de saison comprenant des fruits et légumes frais</p>
            <p class="update-date">Dernière mise à jour: 28/03/2025</p>
            
            <h2>Contenu du panier</h2>
            <table class="product-table">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Quantité</th>
                        <th>Unité</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Tomates</td>
                        <td>500</td>
                        <td>grammes</td>
                    </tr>
                    <tr>
                        <td>Pommes</td>
                        <td>4</td>
                        <td>unités</td>
                    </tr>
                    <tr>
                        <td>Carottes</td>
                        <td>1</td>
                        <td>botte</td>
                    </tr>
                    <tr>
                        <td>Salade</td>
                        <td>1</td>
                        <td>pièce</td>
                    </tr>
                    <tr>
                        <td>Courgettes</td>
                        <td>3</td>
                        <td>unités</td>
                    </tr>
                </tbody>
            </table>
            
            <div class="panier-price">
                <p>Prix total: <strong>18.50 €</strong></p>
            </div>
            
            <div class="action-buttons">
                <button class="add-to-cart">Ajouter au panier</button>
                <a href="/index.php/paniers" class="back-button">Retour aux paniers</a>
            </div>
        </div>
        ';
    }
}