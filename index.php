<?php
// filepath: c:\Users\malio\Desktop\dossier important\coursIUTaix\archi logi\CC2\CC2-FOUCHER-BALDONI-BARBERO-BOUCHAMI\index.php

include_once 'data/UserApiAccess.php';

include_once 'control/Controllers.php';
include_once 'control/Presenter.php';

include_once 'service/UserChecking.php';
include_once 'service/UserCreation.php';

// Import des vues à conserver
include_once 'gui/Layout.php';
include_once 'gui/ViewLogin.php';
include_once 'gui/ViewError.php';
include_once 'gui/ViewLogged.php';
include_once 'gui/ViewCreate.php';

// Import des nouvelles vues e-commerce
include_once 'gui/ViewPaniers.php';
include_once 'gui/ViewPanier.php';
include_once 'gui/ViewProduits.php';
include_once 'gui/ViewCommande.php';
include_once 'gui/ViewAccueil.php';

include_once 'data/ProduitJsonAccess.php';
include_once 'data/PanierJsonAccess.php';
include_once 'service/ProduitChecking.php';
include_once 'service/PanierChecking.php';

include_once 'data/CommandeJsonAccess.php';
include_once 'service/CommandeChecking.php';
include_once 'gui/ViewConfirmation.php';
include_once 'gui/ViewMesCommandes.php';

include_once 'data/PanierApiAccess.php';

use gui\{
    ViewLogin,
    ViewError,
    ViewCreate,
    ViewLogged,
    ViewPaniers,
    ViewPanier, 
    ViewProduits,
    ViewCommande,
    ViewAccueil,
    ViewConfirmation, 
    ViewMesCommandes,
    Layout
};
use control\{Controllers, Presenter};
use data\{UserApiAccess, ProduitJsonAccess, PanierApiAccess, CommandeJsonAccess};
use service\{UserChecking, UserCreation, ProduitChecking, PanierChecking, CommandeChecking};



// URLs des APIs
$userApiUrl = 'http://localhost:9080/UserProduit-1.0-SNAPSHOT/api/user'; // URL de l'API utilisateurs
$panierApiUrl = 'http://localhost:8080/paniers-1.0-SNAPSHOT/api/paniers'; // URL de l'API paniers
$produitApiUrl = 'http://localhost:9080/UserProduit-1.0-SNAPSHOT/api/produit'; // URL de l'API produits
$commandeJsonPath = 'dataSimulate/commandes.json'; // Fichier JSON pour les commandes

// Initialisation des accès aux données
$dataUsers = new UserApiAccess($userApiUrl);
$dataPaniersProduits = new PanierApiAccess($panierApiUrl, $produitApiUrl);
$dataPaniers = $dataPaniersProduits;
$dataProduits = $dataPaniersProduits; // Même si on ne vend pas de produits individuellement, on garde cette référence pour compatibilité
$dataCommandes = new CommandeJsonAccess($commandeJsonPath);

// Initialisation des services
$controller = new Controllers();
$userCheck = new UserChecking();
$userCreation = new UserCreation();
$produitCheck = new ProduitChecking();
$panierCheck = new PanierChecking();
$commandeCheck = new CommandeChecking();



// Initialisation du presenter (à adapter pour les produits/paniers)
$presenter = new Presenter(null, $produitCheck, $panierCheck, $commandeCheck);
// Récupération de l'URL
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Configuration de la session
ini_set('session.gc_maxlifetime', 3600);
session_set_cookie_params(3600);
session_start();

// Liste des URLs publiques (accessibles sans connexion)
$publicUrls = ['/', '/index.php', '/index.php/login', '/index.php/create', '/index.php/error'];

// Vérifier si l'URL actuelle nécessite une authentification
if (!in_array($uri, $publicUrls)) {
    // Si l'utilisateur n'est pas connecté, rediriger vers la page de connexion
    if (!isset($_SESSION['login'])) {
        header('Location: /index.php');
        exit;
    }
}

// Routage des requêtes
if ('/' == $uri || '/index.php' == $uri) {
    // Page de connexion - formulaire initial quand on arrive sur le site
    // Si déjà connecté, rediriger vers la page d'accueil
    if (isset($_SESSION['login'])) {
        header('Location: /index.php/accueil');
        exit;
    }
    
    $layout = new Layout("gui/layout.html");
    $vueLogin = new ViewLogin($layout);
    $vueLogin->display();
}
elseif ('/index.php/login' == $uri) {
    // Traitement du formulaire de connexion
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $login = $_POST['login'] ?? '';
        $password = $_POST['password'] ?? '';
        
        // Vérifier l'authentification avec le fichier JSON
        if ($userCheck->authenticate($login, $password, $dataUsers)) {
            // Connexion réussie
            $_SESSION['login'] = $login;
            header('Location: /index.php/accueil');
            exit;
        } else {
            // Échec de connexion
            $error = 'Identifiant ou mot de passe incorrect';
            $redirect = '/index.php';
            
            $layout = new Layout("gui/layout.html");
            $vueError = new ViewError($layout, $error, $redirect);
            $vueError->display();
        }
    } else {
        // Si ce n'est pas une requête POST, rediriger vers le formulaire
        header('Location: /index.php');
        exit;
    }
}
elseif ('/index.php/logout' == $uri) {
    // Déconnexion - détruire la session et rediriger vers la page de connexion
    session_destroy();
    header('Location: /index.php');
    exit;
}
elseif ('/index.php/create' == $uri) {
    // Formulaire de création de compte ou traitement de la création
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Traitement du formulaire de création
        $login = $_POST['login'] ?? '';
        $password = $_POST['password'] ?? '';
        $name = $_POST['name'] ?? '';
        $firstName = $_POST['firstName'] ?? '';
        
        if ($userCreation->createUser($login, $password, $name, $firstName, $dataUsers)) {
            // Création réussie, connexion automatique
            $_SESSION['login'] = $login;
            header('Location: /index.php/accueil');
            exit;
        } else {
            // Échec de la création
            $error = 'Impossible de créer le compte. Cet identifiant existe peut-être déjà.';
            $redirect = '/index.php/create';
            
            $layout = new Layout("gui/layout.html");
            $vueError = new ViewError($layout, $error, $redirect);
            $vueError->display();
        }
    } else {
        // Affichage du formulaire de création
        $layout = new Layout("gui/layout.html");
        $vueCreate = new ViewCreate($layout);
        $vueCreate->display();
    }
}
elseif ('/index.php/accueil' == $uri) {
    // Page d'accueil après connexion
    $layout = new Layout("gui/layoutLogged.html");
    $vueAccueil = new ViewAccueil($layout, $_SESSION['login'], $presenter);
    $vueAccueil->display();
}
elseif ('/index.php/paniers' == $uri) {
    // Affichage de tous les paniers disponibles
    $panierCheck->getAllPaniers($dataPaniers);
    
    $layout = new Layout("gui/layoutLogged.html");
    $vuePaniers = new ViewPaniers($layout, $_SESSION['login'], $presenter);
    $vuePaniers->display();
}
elseif ('/index.php/panier' == $uri && isset($_GET['id'])) {
    // Affichage du détail d'un panier
    $panierCheck->getPanier($_GET['id'], $dataPaniers);
    
    $layout = new Layout("gui/layoutLogged.html");
    $vuePanier = new ViewPanier($layout, $_SESSION['login'], $presenter);
    $vuePanier->display();
}
elseif ('/index.php/produits' == $uri) {
    // Affichage de tous les produits
    $produitCheck->getAllProduits($dataProduits);
    
    $layout = new Layout("gui/layoutLogged.html");
    $vueProduits = new ViewProduits($layout, $_SESSION['login'], $presenter);
    $vueProduits->display();
}

elseif (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [
        'produits' => [],
        'paniers' => []
    ];
}

// Ajouter les routes pour la gestion du panier et des commandes
elseif ('/index.php/add-to-cart' == $uri && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ajouter un produit au panier
    if (isset($_POST['type']) && isset($_POST['id']) && isset($_POST['quantite'])) {
        $type = $_POST['type'];
        $id = intval($_POST['id']);
        $quantite = intval($_POST['quantite']);
        
        if ($type === 'produit') {
            $produit = $dataProduits->getProduit($id);
            if ($produit) {
                // Vérifier si le produit est déjà dans le panier
                $found = false;
                foreach ($_SESSION['panier']['produits'] as &$item) {
                    if ($item['id'] == $id) {
                        $item['quantite'] += $quantite;
                        $found = true;
                        break;
                    }
                }
                unset($item); // Détacher la référence
                
                // Ajouter le produit si non trouvé
                if (!$found) {
                    $_SESSION['panier']['produits'][] = [
                        'id' => $id,
                        'nom' => $produit['nom'],
                        'prix' => $produit['prix'],
                        'quantite' => $quantite
                    ];
                }
            }
        } elseif ($type === 'panier') {
            $panier = $dataPaniers->getPanier($id);
            if ($panier) {
                // Vérifier si le panier est déjà dans la commande
                $found = false;
                foreach ($_SESSION['panier']['paniers'] as &$item) {
                    if ($item['id'] == $id) {
                        $item['quantite'] += $quantite;
                        $found = true;
                        break;
                    }
                }
                unset($item); // Détacher la référence
                
                // Ajouter le panier si non trouvé
                if (!$found) {
                    $_SESSION['panier']['paniers'][] = [
                        'id' => $id,
                        'nom' => $panier['nom'],
                        'prix' => $panier['prix'],
                        'quantite' => $quantite
                    ];
                }
            }
        }
        
        // Rediriger vers la page de commande
        header('Location: /index.php/commande');
        exit;
    }
}

elseif ('/index.php/add-to-cart-ajax' == $uri && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Initialiser le panier s'il n'existe pas
    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = [
            'produits' => [],
            'paniers' => []
        ];
    }
    
    // Récupérer les données
    $type = $_POST['type'] ?? 'produit';
    $id = intval($_POST['id'] ?? 0);
    $quantite = intval($_POST['quantite'] ?? 1);
    
    if ($id > 0 && $quantite > 0) {
        if ($type === 'produit') {
            $produit = $dataProduits->getProduit($id);
            if ($produit) {
                // Vérifier si le produit est déjà dans le panier
                $found = false;
                foreach ($_SESSION['panier']['produits'] as &$item) {
                    if ($item['id'] == $id) {
                        $item['quantite'] += $quantite;
                        $found = true;
                        break;
                    }
                }
                unset($item); // Détacher la référence
                
                // Ajouter le produit si non trouvé
                if (!$found) {
                    $_SESSION['panier']['produits'][] = [
                        'id' => $id,
                        'nom' => $produit['nom'],
                        'prix' => $produit['prix'],
                        'quantite' => $quantite
                    ];
                }
            }
        } elseif ($type === 'panier') {
            $panier = $dataPaniers->getPanier($id);
            if ($panier) {
                // Vérifier si le panier est déjà dans la commande
                $found = false;
                foreach ($_SESSION['panier']['paniers'] as &$item) {
                    if ($item['id'] == $id) {
                        $item['quantite'] += $quantite;
                        $found = true;
                        break;
                    }
                }
                unset($item); // Détacher la référence
                
                // Ajouter le panier si non trouvé
                if (!$found) {
                    $_SESSION['panier']['paniers'][] = [
                        'id' => $id,
                        'nom' => $panier['nom'],
                        'prix' => $panier['prix'],
                        'quantite' => $quantite
                    ];
                }
            }
        }
    }
    
    // Calculer le nombre total d'articles dans le panier
    $cartCount = 0;
    foreach ($_SESSION['panier']['produits'] as $produit) {
        $cartCount += $produit['quantite'];
    }
    foreach ($_SESSION['panier']['paniers'] as $panier) {
        $cartCount += $panier['quantite'];
    }
    
    // Retourner une réponse JSON
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'count' => $cartCount]);
    exit;
}
elseif ('/index.php/confirmation' == $uri && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Traitement de la validation de commande
    if (isset($_POST['relai']) && isset($_POST['date']) && isset($_POST['heure']) && isset($_POST['total'])) {
        // Préparation des données de commande
        $commandeData = [
            'relai' => $_POST['relai'],
            'date' => $_POST['date'],
            'heure' => $_POST['heure'],
            'produits' => $_SESSION['panier']['produits'],
            'paniers' => $_SESSION['panier']['paniers'],
            'total' => floatval($_POST['total'])
        ];
        
        // Création de la commande
        $commandeId = $commandeCheck->createCommande($_SESSION['login'], $commandeData, $dataCommandes);
        
        // Mise à jour des stocks
        foreach ($_SESSION['panier']['produits'] as $produit) {
            $dataProduits->updateStock($produit['id'], $produit['quantite']);
        }
        
        foreach ($_SESSION['panier']['paniers'] as $panier) {
            $dataPaniers->updateStock($panier['id'], $panier['quantite']);
        }
        
        // Vider le panier
        $_SESSION['panier'] = [
            'produits' => [],
            'paniers' => []
        ];
        
        // Afficher la page de confirmation
        $layout = new Layout("gui/layoutLogged.html");
        $vueConfirmation = new ViewConfirmation($layout, $_SESSION['login'], $commandeId);
        $vueConfirmation->display();
        exit;
    } else {
        // Rediriger vers la page de commande en cas d'erreur
        header('Location: /index.php/commande');
        exit;
    }
}
elseif ('/index.php/mes-commandes' == $uri) {
    // Afficher les commandes de l'utilisateur connecté
    $commandeCheck->getCommandesByUser($_SESSION['login'], $dataCommandes);
    
    $layout = new Layout("gui/layoutLogged.html");
    $vueMesCommandes = new ViewMesCommandes($layout, $_SESSION['login'], $presenter);
    $vueMesCommandes->display();
}
elseif ('/index.php/detail-commande' == $uri && isset($_GET['id'])) {
    // Afficher le détail d'une commande
    $commandeCheck->getCommande($_GET['id'], $dataCommandes);
    
    // Si l'utilisateur essaie d'accéder à une commande qui n'est pas la sienne
    if (empty($commandeCheck->getCommandesTxt()) || $commandeCheck->getCommandesTxt()[0]['login'] !== $_SESSION['login']) {
        header('Location: /index.php/mes-commandes');
        exit;
    }
    
    $layout = new Layout("gui/layoutLogged.html");
    $vueDetailCommande = new ViewDetailCommande($layout, $_SESSION['login'], $presenter);
    $vueDetailCommande->display();
}
elseif ('/index.php/commande' == $uri) {
    // Validation d'une commande
    $layout = new Layout("gui/layoutLogged.html");
    $vueCommande = new ViewCommande($layout, $_SESSION['login'], $presenter);
    $vueCommande->display();
}
elseif ('/index.php/error' == $uri) {
    // Affichage d'un message d'erreur
    $layout = new Layout("gui/layout.html");
    $vueError = new ViewError($layout, $error ?? 'Une erreur est survenue', $redirect ?? '/index.php');
    $vueError->display();
}
else {
    // Page non trouvée
    header('Status: 404 Not Found');
    echo '<html lang="fr"><body><h1>Page non trouvée</h1><p><a href="/index.php">Retour à l\'accueil</a></p></body></html>';
}