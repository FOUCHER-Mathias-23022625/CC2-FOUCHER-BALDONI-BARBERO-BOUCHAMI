<?php
// filepath: c:\Users\malio\Desktop\dossier important\coursIUTaix\archi logi\CC2\CC2-FOUCHER-BALDONI-BARBERO-BOUCHAMI\index.php

// Import des données et services pour l'authentification via JSON
include_once 'data/UserJsonAccess.php';

// Import des nouveaux composants pour le e-commerce
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
    Layout
};
use control\{Controllers, Presenter};
use data\{UserJsonAccess, ProduitJsonAccess, PanierJsonAccess};
use service\{UserChecking, UserCreation, ProduitChecking, PanierChecking};

// Chemin vers le fichier JSON des utilisateurs
$userJsonPath = 'dataSimulate/user.json';
$produitJsonPath = 'dataSimulate/produits.json';
$panierJsonPath = 'dataSimulate/paniers.json';

$dataUsers = new UserJsonAccess($userJsonPath);
$dataProduits = new ProduitJsonAccess($produitJsonPath);
$dataPaniers = new PanierJsonAccess($panierJsonPath);

// Initialisation des services
$controller = new Controllers();
$userCheck = new UserChecking();
$userCreation = new UserCreation();
$produitCheck = new ProduitChecking();
$panierCheck = new PanierChecking();


// Initialisation du presenter (à adapter pour les produits/paniers)
$presenter = new Presenter(null, $produitCheck, $panierCheck);
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
elseif ('/index.php/commande' == $uri) {
    // Validation d'une commande
    // $controller->commandeAction($_SESSION['login'], $_POST, $dataCommandes);
    
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