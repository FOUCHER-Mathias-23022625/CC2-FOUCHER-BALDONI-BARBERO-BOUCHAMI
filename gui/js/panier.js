document.addEventListener('DOMContentLoaded', function() {
    // Récupérer tous les boutons d'ajout au panier
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    
    // Ajouter un gestionnaire d'événement à chaque bouton
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault(); // Empêcher l'action par défaut

            const id = this.getAttribute('data-id');
            const type = this.getAttribute('data-type') || 'produit'; // 'produit' par défaut si non spécifié
            
            // Récupérer la quantité depuis l'input, s'il existe
            let quantite = 1;
            const quantityInput = this.closest('.product-card, .panier-card')?.querySelector('input[type="number"]');
            if (quantityInput) {
                quantite = parseInt(quantityInput.value, 10);
            }
            
            // Création d'une requête AJAX
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '/index.php/add-to-cart-ajax', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            
            // Définir ce qui se passe lors de la réception de la réponse
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Afficher une notification de succès
                    showNotification('Produit ajouté à votre panier !');
                    
                    // Mettre à jour le compteur de panier si un tel élément existe
                    const cartCount = document.querySelector('.cart-count');
                    if (cartCount) {
                        const response = JSON.parse(xhr.responseText);
                        if (response.count) {
                            cartCount.textContent = response.count;
                            cartCount.style.display = 'inline-block';
                        }
                    }
                }
            };
            
            // Envoyer la requête avec les données
            xhr.send(`type=${type}&id=${id}&quantite=${quantite}`);
        });
    });
    
    // Fonction pour afficher une notification temporaire
    function showNotification(message) {
        // Vérifier si une notification existe déjà
        let notification = document.querySelector('.cart-notification');
        
        // Si non, en créer une nouvelle
        if (!notification) {
            notification = document.createElement('div');
            notification.className = 'cart-notification';
            document.body.appendChild(notification);
        }
        
        // Mettre à jour le message et afficher la notification
        notification.textContent = message;
        notification.classList.add('show');
        
        // Cacher la notification après 3 secondes
        setTimeout(() => {
            notification.classList.remove('show');
        }, 3000);
    }
});