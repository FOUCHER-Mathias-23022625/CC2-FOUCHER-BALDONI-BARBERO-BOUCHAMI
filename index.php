<?php

// Import des données et services existants à conserver
include_once 'data/UserSqlAccess.php';

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
include_once 'gui/ViewPaniers.php';  // À créer
include_once 'gui/ViewPanier.php';   // À créer
include_once 'gui/ViewProduits.php'; // À créer
include_once 'gui/ViewCommande.php'; // À créer
include_once 'gui/ViewPanier.php';   // À créer

use gui\{
    ViewLogin,
    ViewError,
    ViewCreate,
    ViewLogged,
    ViewPaniers,
    ViewPanier, 
    ViewProduits,
    ViewCommande,
    Layout
};
use control\{Controllers, Presenter};
use data\{UserSqlAccess};
use service\{UserChecking, UserCreation};

$data = null;
try {
    // Garder la connexion à la base de données existante pour l'authentification
    $bd = new PDO('mysql:host=mysql-archilogicc2.alwaysdata.net;dbname=archilogicc2_db', '406081', 'ArchiLogicielCC2@');
    $dataUsers = new UserSqlAccess($bd);
} catch (PDOException $e) {
    print "Erreur de connexion !: " . $e->getMessage() . "<br/>";
    die();
}

// Initialisation des services
$controller = new Controllers();
$userCheck = new UserChecking();
$userCreation = new UserCreation();

// Initialisation du presenter (à adapter pour les produits/paniers)
$presenter = new Presenter(null); // À modifier pour gérer les produits et paniers

// Récupération de l'URL
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Configuration de la session
ini_set('session.gc_maxlifetime', 3600);
session_set_cookie_params(3600);
session_start();

// Authentification et création du compte (sauf pour le formulaire de connexion et de création de compte)
if ('/' != $uri and '/index.php' != $uri and '/index.php/logout' != $uri and '/index.php/create' != $uri){
    $error = $controller->authenticateAction($userCreation, $userCheck, $dataUsers);

    if($error != null){
        $uri='/index.php/error';
        if($error == 'bad login or pwd' or $error == 'not connected')
            $redirect = '/index.php';

        if($error == 'creation impossible')
            $redirect = '/index.php/create';
    }
}

// Routage des requêtes
if ('/' == $uri || '/index.php' == $uri || '/index.php/logout' == $uri) {
    // Page de connexion
    session_destroy();
    $layout = new Layout("gui/layout.html");
    $vueLogin = new ViewLogin($layout);
    $vueLogin->display();
}
elseif ('/index.php/create' == $uri) {
    // Formulaire de création de compte
    $layout = new Layout("gui/layout.html");
    $vueCreate = new ViewCreate($layout);
    $vueCreate->display();
}
elseif ('/index.php/paniers' == $uri) {
    // Affichage de tous les paniers disponibles
    // À implémenter : récupération des paniers
    // $controller->paniersAction($dataPaniers, $paniersCheck);

    $layout = new Layout("gui/layoutLogged.html");
    $vuePaniers = new ViewPaniers($layout, $_SESSION['login'], $presenter);
    $vuePaniers->display();
}
elseif ('/index.php/panier' == $uri && isset($_GET['id'])) {
    // Affichage du détail d'un panier
    // À implémenter : récupération du panier spécifique
    // $controller->panierAction($_GET['id'], $dataPaniers, $panierCheck);

    $layout = new Layout("gui/layoutLogged.html");
    $vuePanier = new ViewPanier($layout, $_SESSION['login'], $presenter);
    $vuePanier->display();
}
elseif ('/index.php/produits' == $uri) {
    // Affichage de tous les produits
    // À implémenter : récupération des produits
    // $controller->produitsAction($dataProduits, $produitsCheck);

    $layout = new Layout("gui/layoutLogged.html");
    $vueProduits = new ViewProduits($layout, $_SESSION['login'], $presenter);
    $vueProduits->display();
}
elseif ('/index.php/commande' == $uri) {
    // Validation d'une commande
    // À implémenter : traitement de la commande
    // $controller->commandeAction($_SESSION['login'], $_POST, $dataCommandes);

    $layout = new Layout("gui/layoutLogged.html");
    $vueCommande = new ViewCommande($layout, $_SESSION['login'], $presenter);
    $vueCommande->display();
}
elseif ('/index.php/error' == $uri) {
    // Affichage d'un message d'erreur
    $layout = new Layout("gui/layout.html");
    $vueError = new ViewError($layout, $error, $redirect);
    $vueError->display();
}
else {
    // Page non trouvée
    header('Status: 404 Not Found');
    echo '<html lang="fr"><body><h1>Page non trouvée</h1></body></html>';
}