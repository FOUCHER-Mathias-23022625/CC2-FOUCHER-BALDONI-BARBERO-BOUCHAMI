<?php
namespace gui;

include_once "ViewLogged.php";

class ViewProduits extends ViewLogged
{
    public function __construct($layout, $login, $presenter)
    {
        parent::__construct($layout, $login);

        $this->title = 'Coopérative Agricole - Nos produits';

        // Le contenu HTML devrait idéalement venir du presenter
        $this->content = '
        <h1>Nos produits</h1>
        <div class="products-container">
            <div class="product-card">
                <div class="product-name">Tomates</div>
                <div class="product-price">3.50 € / kg</div>
                <div class="quantity-control">
                    <button onclick="updateQuantity(this, -1)">-</button>
                    <input type="number" value="1" min="1" max="10">
                    <button onclick="updateQuantity(this, 1)">+</button>
                </div>
                <button class="add-to-cart">Ajouter au panier</button>
            </div>
            
            <div class="product-card">
                <div class="product-name">Œufs fermiers</div>
                <div class="product-price">4.20 € / douzaine</div>
                <div class="quantity-control">
                    <button onclick="updateQuantity(this, -1)">-</button>
                    <input type="number" value="1" min="1" max="10">
                    <button onclick="updateQuantity(this, 1)">+</button>
                </div>
                <button class="add-to-cart">Ajouter au panier</button>
            </div>
            
            <div class="product-card">
                <div class="product-name">Fromage de chèvre</div>
                <div class="product-price">5.80 € / pièce</div>
                <div class="quantity-control">
                    <button onclick="updateQuantity(this, -1)">-</button>
                    <input type="number" value="1" min="1" max="10">
                    <button onclick="updateQuantity(this, 1)">+</button>
                </div>
                <button class="add-to-cart">Ajouter au panier</button>
            </div>
            
            <div class="product-card">
                <div class="product-name">Pommes de terre</div>
                <div class="product-price">2.20 € / kg</div>
                <div class="quantity-control">
                    <button onclick="updateQuantity(this, -1)">-</button>
                    <input type="number" value="1" min="1" max="10">
                    <button onclick="updateQuantity(this, 1)">+</button>
                </div>
                <button class="add-to-cart">Ajouter au panier</button>
            </div>
        </div>
        <script src="/gui/js/product.js"></script>
        ';
    }
}