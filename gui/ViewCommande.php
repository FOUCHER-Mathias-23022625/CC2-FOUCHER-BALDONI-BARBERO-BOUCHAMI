<?php
namespace gui;

include_once "ViewLogged.php";

class ViewCommande extends ViewLogged
{
    public function __construct($layout, $login, $presenter)
    {
        parent::__construct($layout, $login);

        $this->title = 'Coopérative Agricole - Validation de commande';

        $this->content = '
        <div class="commande-container">
            <h1>Validation de votre commande</h1>
            
            <div class="panier-summary">
                <h2>Récapitulatif de votre panier</h2>
                <table class="commande-table">
                    <thead>
                        <tr>
                            <th>Panier</th>
                            <th>Prix unitaire</th>
                            <th>Quantité</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Panier Fruits & Légumes</td>
                            <td>18.50 €</td>
                            <td>1</td>
                            <td>18.50 €</td>
                        </tr>
                        <tr>
                            <td>Panier Fromages</td>
                            <td>22.00 €</td>
                            <td>1</td>
                            <td>22.00 €</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3"><strong>Total de la commande</strong></td>
                            <td><strong>40.50 €</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <div class="retrait-info">
                <h2>Informations de retrait</h2>
                <form action="/index.php/confirmation" method="post">
                    <div class="form-group">
                        <label for="relai">Point de retrait :</label>
                        <select id="relai" name="relai" required>
                            <option value="">Choisir un point de retrait</option>
                            <option value="1">Coopérative principale - Aix-en-Provence</option>
                            <option value="2">Marché de Pertuis</option>
                            <option value="3">Épicerie Bio - Marseille</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="date">Date de retrait :</label>
                        <input type="date" id="date" name="date" required min="2025-03-31">
                    </div>
                    
                    <div class="form-group">
                        <label for="heure">Heure de retrait :</label>
                        <select id="heure" name="heure" required>
                            <option value="">Choisir une heure</option>
                            <option value="10:00">10:00 - 11:00</option>
                            <option value="11:00">11:00 - 12:00</option>
                            <option value="14:00">14:00 - 15:00</option>
                            <option value="15:00">15:00 - 16:00</option>
                            <option value="16:00">16:00 - 17:00</option>
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