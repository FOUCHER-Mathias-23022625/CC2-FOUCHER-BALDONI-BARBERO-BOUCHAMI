<?php
namespace gui;

include_once "View.php";

class ViewAccueil extends View
{
    public function __construct($layout, $login, $presenter)
    {
        parent::__construct($layout, $login);

        $this->title = 'Coopérative Agricole - Produits frais et locaux';

        $this->content = '
        <div class="home-container">
            <!-- Section bannière -->
            <div class="hero-banner">
                <div class="hero-content">
                    <h1>Bienvenue à la Coopérative Agricole</h1>
                    <p>Des produits frais, locaux et de saison directement des producteurs à votre table</p>
                    <div class="cta-buttons">
                        <a href="#about" class="cta-button secondary">Découvrir</a>
                        <a href="#login-form" class="cta-button primary">Se connecter</a>
                    </div>
                </div>
            </div>
            
            <!-- Section À propos -->
            <section id="about" class="about-section">
                <div class="section-header">
                    <h2>Notre histoire</h2>
                    <div class="separator"></div>
                </div>
                <div class="about-content">
                    <div class="about-image">
                        <img src="/images/farm-workers.jpg" alt="Travailleurs agricoles" />
                    </div>
                    <div class="about-text">
                        <p>Fondée en 2015 par un groupe de producteurs locaux passionnés, notre coopérative agricole s\'engage à offrir des produits frais et de qualité tout en respectant l\'environnement et les pratiques agricoles durables.</p>
                        <p>Nous croyons en une agriculture de proximité qui favorise les circuits courts et permet de soutenir l\'économie locale tout en réduisant notre empreinte carbone.</p>
                        <p>Nos membres producteurs pratiquent une agriculture raisonnée, minimisant l\'utilisation de pesticides et favorisant la biodiversité dans leurs exploitations.</p>
                    </div>
                </div>
            </section>
            
            <!-- Section Comment ça marche -->
            <section class="how-it-works">
                <div class="section-header">
                    <h2>Comment ça marche</h2>
                    <div class="separator"></div>
                </div>
                <div class="steps-container">
                    <div class="step-card">
                        <div class="step-icon">
                            <img src="/images/icon-register.svg" alt="Création de compte" />
                        </div>
                        <h3>1. Créez un compte</h3>
                        <p>Inscrivez-vous pour accéder à notre sélection de produits frais et locaux.</p>
                    </div>
                    <div class="step-card">
                        <div class="step-icon">
                            <img src="/images/icon-basket.svg" alt="Choix du panier" />
                        </div>
                        <h3>2. Choisissez votre panier</h3>
                        <p>Sélectionnez un panier précomposé ou des produits individuels.</p>
                    </div>
                    <div class="step-card">
                        <div class="step-icon">
                            <img src="/images/icon-calendar.svg" alt="Date de retrait" />
                        </div>
                        <h3>3. Choisissez la date de retrait</h3>
                        <p>Sélectionnez un point relais et une date de retrait qui vous conviennent.</p>
                    </div>
                    <div class="step-card">
                        <div class="step-icon">
                            <img src="/images/icon-pickup.svg" alt="Retrait du panier" />
                        </div>
                        <h3>4. Récupérez vos produits</h3>
                        <p>Venez chercher vos produits frais au point de retrait et payez sur place.</p>
                    </div>
                </div>
            </section>
            
            <!-- Section Nos produits -->
            <section class="products-preview">
                <div class="section-header">
                    <h2>Nos produits de saison</h2>
                    <div class="separator"></div>
                </div>
                <div class="products-slider">
                    <div class="product-slide">
                        <img src="/images/vegetables.jpg" alt="Légumes frais" />
                        <h3>Légumes frais</h3>
                        <p>Des légumes de saison cueillis à maturité pour une saveur optimale.</p>
                    </div>
                    <div class="product-slide">
                        <img src="/images/fruits.jpg" alt="Fruits de saison" />
                        <h3>Fruits locaux</h3>
                        <p>Des fruits cultivés avec soin par nos producteurs locaux.</p>
                    </div>
                    <div class="product-slide">
                        <img src="/images/dairy.jpg" alt="Produits laitiers" />
                        <h3>Produits laitiers</h3>
                        <p>Fromages, yaourts et autres délices produits artisanalement.</p>
                    </div>
                    <div class="product-slide">
                        <img src="/images/eggs.jpg" alt="Œufs frais" />
                        <h3>Œufs frais</h3>
                        <p>Des œufs de poules élevées en plein air et nourries naturellement.</p>
                    </div>
                </div>
            </section>
            
            <!-- Section témoignages -->
            <section class="testimonials">
                <div class="section-header">
                    <h2>Ce que disent nos clients</h2>
                    <div class="separator"></div>
                </div>
                <div class="testimonial-container">
                    <div class="testimonial-card">
                        <div class="quote">"</div>
                        <p>Les produits de la coopérative ont vraiment changé ma façon de consommer. La fraîcheur et le goût sont incomparables !</p>
                        <div class="testimonial-author">
                            <p><strong>Marie D.</strong> - Cliente depuis 2020</p>
                        </div>
                    </div>
                    <div class="testimonial-card">
                        <div class="quote">"</div>
                        <p>Je suis ravi de pouvoir soutenir des agriculteurs locaux tout en ayant accès à des produits d\'excellente qualité.</p>
                        <div class="testimonial-author">
                            <p><strong>Thomas L.</strong> - Client depuis 2019</p>
                        </div>
                    </div>
                    <div class="testimonial-card">
                        <div class="quote">"</div>
                        <p>Le système de paniers est pratique et me permet de découvrir de nouveaux produits de saison chaque semaine !</p>
                        <div class="testimonial-author">
                            <p><strong>Sophie M.</strong> - Cliente depuis 2021</p>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Formulaire de connexion -->
            <section id="login-form" class="login-section">
                <div class="section-header">
                    <h2>Connectez-vous</h2>
                    <div class="separator"></div>
                </div>
                <div class="form-container">
                    <form method="post" action="/index.php/produits" class="login-form">
                        <div class="form-group">
                            <label for="login">Votre identifiant :</label>
                            <input type="text" name="login" id="login" placeholder="Entrez votre identifiant" maxlength="12" required />
                        </div>
                        <div class="form-group">
                            <label for="password">Votre mot de passe :</label>
                            <input type="password" name="password" id="password" placeholder="Entrez votre mot de passe" minlength="12" required />
                        </div>
                        <div class="form-actions">
                            <input type="submit" value="Se connecter" class="submit-button">
                            <a href="/index.php/create" class="create-account">Créer un compte</a>
                        </div>
                    </form>
                </div>
            </section>
            
            <!-- Section contact -->
            <section class="contact-section">
                <div class="section-header">
                    <h2>Contactez-nous</h2>
                    <div class="separator"></div>
                </div>
                <div class="contact-container">
                    <div class="contact-info">
                        <div class="contact-item">
                            <i class="icon-location"></i>
                            <p>123 Chemin des Agriculteurs<br>13100 Aix-en-Provence</p>
                        </div>
                        <div class="contact-item">
                            <i class="icon-phone"></i>
                            <p>04 42 XX XX XX</p>
                        </div>
                        <div class="contact-item">
                            <i class="icon-email"></i>
                            <p>contact@cooperative-agricole.fr</p>
                        </div>
                        <div class="contact-item">
                            <i class="icon-hours"></i>
                            <p>Lun-Ven: 9h-18h<br>Sam: 9h-13h</p>
                        </div>
                    </div>
                    <div class="map-container">
                        <img src="/images/map.jpg" alt="Carte de localisation" />
                    </div>
                </div>
            </section>
        </div>
        ';
    }
}