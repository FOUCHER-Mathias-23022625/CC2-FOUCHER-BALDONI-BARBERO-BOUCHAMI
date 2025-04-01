<?php
// filepath: c:\Users\malio\Desktop\dossier important\coursIUTaix\archi logi\CC2\CC2-FOUCHER-BALDONI-BARBERO-BOUCHAMI\gui\ViewCommande.php

namespace gui;

include_once "ViewLogged.php";

class ViewCommande extends ViewLogged
{
    public function __construct($layout, $login, $presenter)
    {
        parent::__construct($layout, $login);

        $this->title = 'Coopérative Agricole - Validation de commande';

        // Si le panier est vide, afficher un message
        if (empty($_SESSION['panier'])) {
            $this->content = '
            <div class="commande-container">
                <h1>Votre panier est vide</h1>
                <p>Vous n\'avez aucun produit ou panier dans votre commande.</p>
                <div class="action-buttons">
                    <a href="/index.php/produits" class="back-button">Aller aux produits</a>
                    <a href="/index.php/paniers" class="back-button">Aller aux paniers</a>
                </div>
            </div>';
            return;
        }

        // Calculer le total de la commande
        $total = 0;
        foreach ($_SESSION['panier']['produits'] ?? [] as $produit) {
            $total += $produit['prix'] * $produit['quantite'];
        }
        foreach ($_SESSION['panier']['paniers'] ?? [] as $panier) {
            $total += $panier['prix'] * $panier['quantite'];
        }

        // Afficher le formulaire de validation de commande
        $this->content = '
        <div class="commande-container">
            <h1>Validation de votre commande</h1>
            
            <div class="panier-summary">
                <h2>Récapitulatif de votre panier</h2>
                <table class="commande-table">
                    <thead>
                        <tr>
                            <th>Article</th>
                            <th>Prix unitaire</th>
                            <th>Quantité</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>';

        // Afficher les produits
        foreach ($_SESSION['panier']['produits'] ?? [] as $produit) {
            $this->content .= '
                        <tr>
                            <td>' . htmlspecialchars($produit['nom']) . '</td>
                            <td>' . number_format($produit['prix'], 2, ',', ' ') . ' €</td>
                            <td>' . htmlspecialchars($produit['quantite']) . '</td>
                            <td>' . number_format($produit['prix'] * $produit['quantite'], 2, ',', ' ') . ' €</td>
                        </tr>';
        }

        // Afficher les paniers
        foreach ($_SESSION['panier']['paniers'] ?? [] as $panier) {
            $this->content .= '
                        <tr>
                            <td>' . htmlspecialchars($panier['nom']) . '</td>
                            <td>' . number_format($panier['prix'], 2, ',', ' ') . ' €</td>
                            <td>' . htmlspecialchars($panier['quantite']) . '</td>
                            <td>' . number_format($panier['prix'] * $panier['quantite'], 2, ',', ' ') . ' €</td>
                        </tr>';
        }

        // Afficher le total et le formulaire de retrait
        $this->content .= '
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3"><strong>Total de la commande</strong></td>
                            <td><strong>' . number_format($total, 2, ',', ' ') . ' €</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <div class="retrait-info">
                <h2>Informations de retrait</h2>
                <form action="/index.php/confirmation" method="post">
                    <input type="hidden" name="total" value="' . $total . '">
                    <div class="form-group">
                        <label for="relai">Point de retrait :</label>
                        <select id="relai" name="relai" required>
                            <option value="">Choisir un point de retrait</option>
                            <option value="Coopérative principale - Aix-en-Provence">Coopérative principale - Aix-en-Provence</option>
                            <option value="Marché de Pertuis">Marché de Pertuis</option>
                            <option value="Épicerie Bio - Marseille">Épicerie Bio - Marseille</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="date">Date de retrait :</label>
                        <input type="date" id="date" name="date" required min="' . date('Y-m-d', strtotime('+1 day')) . '">
                    </div>
                    
                    <div class="form-group">
                        <label for="heure">Heure de retrait :</label>
                        <select id="heure" name="heure" required>
                            <option value="">Choisir une heure</option>
                            <option value="10:00 - 11:00">10:00 - 11:00</option>
                            <option value="11:00 - 12:00">11:00 - 12:00</option>
                            <option value="14:00 - 15:00">14:00 - 15:00</option>
                            <option value="15:00 - 16:00">15:00 - 16:00</option>
                            <option value="16:00 - 17:00">16:00 - 17:00</option>
                        </select>
                    </div>
                    
                    <div class="action-buttons">
                        <button type="submit" class="confirm-button">Confirmer la commande</button>
                        <a href="/index.php/paniers" class="back-button">Retour aux paniers</a>
                    </div>
                </form>
            </div>
        </div>
        ';
    }
}