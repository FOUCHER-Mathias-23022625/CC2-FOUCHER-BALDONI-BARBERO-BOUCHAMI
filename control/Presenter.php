<?php
namespace control;
class Presenter
{
    protected $annoncesCheck;
    protected $product;

    public function __construct($annoncesCheck)
    {
        $this->annoncesCheck = $annoncesCheck;
    }

    public function getAllAnnoncesHTML()
    {
        $content = null;
        if ($this->annoncesCheck->getAnnoncesTxt() != null) {
            $content = '<h1>List of Posts</h1>  <ul>';
            foreach ($this->annoncesCheck->getAnnoncesTxt() as $post) {
                $content .= ' <li>';
                $content .= '<a href="/index.php/post?id=' . $post['id'] . '">' . $post['title'] . '</a>';
                $content .= ' </li>';
            }
            $content .= '</ul>';
        }
        return $content;
    }

    public function getAllAlternanceHTML()
    {
        $content = null;
        if ($this->annoncesCheck->getAnnoncesTxt() != null) {
            $content = '<h1>List of Company</h1>  <ul>';
            foreach ($this->annoncesCheck->getAnnoncesTxt() as $post) {
                $content .= ' <li>';
                $content .= '<a href="/index.php/companyAlternance?id=' . $post['id'] . '">' . $post['title'] . '</a>';
                $content .= ' </li>';
            }
            $content .= '</ul>';
        }
        return $content;
    }

    public function getCurrentPostHTML()
    {
        $content = null;
        if ($this->annoncesCheck->getAnnoncesTxt() != null) {
            $post = $this->annoncesCheck->getAnnoncesTxt()[0];

            $content = '<h1>' . $post['title'] . '</h1>';
            $content .= '<div class="date">' . $post['date'] . '</div>';
            $content .= '<div class="body">' . $post['body'] . '</div>';
        }
        return $content;
    }

    public function getAllEmploiHTML()
    {
        $content = null;
        if ($this->annoncesCheck->getAnnoncesTxt() != null) {
            $content = '<h1>List of Jobs</h1>  <ul>';
            foreach ($this->annoncesCheck->getAnnoncesTxt() as $post) {
                $content .= ' <li>';
                $content .= '<a href="/index.php/offreEmploi?id=' . $post['id'] . '">' . $post['title'] . '</a>';
                $content .= ' </li>';
            }
            $content .= '</ul>';
        }
        return $content;
    }

    public function getAllProduct()
    {
        $content = null;
        if ($this->product->getProductTxt() != null) {
            $content = '<h1>Liste de tout les produits</h1>';
            $content = '<div class="products-container">';
            foreach ($this->product->getProductTxt() as $product) {
                $content .= '<div class="product-card">';
            $content .='<div class="product-name">'. $product['name'] .'</div>';
            $content .='<div class="product-price">'. $product['price'] .'</div>';
            $content .='<div class="quantity-control">
                <button onclick="updateQuantity(this, -1)">-</button>
                <input type="number" value="1" min="1" max="10">
                <button onclick="updateQuantity(this, 1)">+</button>
            </div>
            
            <button class="add-to-cart">Ajouter au panier</button>
        </div>';
            }
        }
    }
}