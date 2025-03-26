<?php

include_once 'data/AnnonceSqlAccess.php';
include_once 'data/UserSqlAccess.php';
include_once 'data/ApiAlternance.php';
include_once 'data/ApiEmploi.php';

include_once 'control/Controllers.php';
include_once 'control/Presenter.php';

include_once 'service/AnnoncesChecking.php';
include_once 'service/UserChecking.php';
include_once 'service/UserCreation.php';
include_once 'service/AnnonceCreation.php';

include_once 'gui/Layout.php';
include_once 'gui/ViewLogin.php';
include_once 'gui/ViewAnnonces.php';
include_once 'gui/ViewPost.php';
include_once 'gui/ViewError.php';
include_once 'gui/ViewLogged.php';
include_once 'gui/ViewAnnoncesEmploi.php';
include_once 'gui/ViewOffreEmploi.php';
include_once 'gui/ViewCreate.php';
include_once 'gui/ViewAnnoncesAlternance.php';
include_once 'gui/ViewCompanyAlternance.php';
include_once 'gui/ViewCreateAnnonce.php';



use gui\{ViewAnnoncesAlternance,
    ViewAnnoncesEmploi,
    ViewCompanyAlternance,
    ViewCreateAnnonce,
    ViewLogin,
    ViewAnnonces,
    ViewOffreEmploi,
    ViewPost,
    ViewError,
    ViewCreate,
    ViewLogged,
    Layout};
use control\{Controllers, Presenter};
use data\{AnnonceSqlAccess, ApiAlternance, ApiEmploi, UserSqlAccess};
use service\{AnnonceCreation, AnnoncesChecking, UserChecking, UserCreation};


$data = null;
try {
    $bd = new PDO('mysql:host=mysql-archilogitd1.alwaysdata.net;dbname=archilogitd1_annonces_db', '395426_annonce', 'Matlou29');
    // construction du modèle
    $dataAnnonces = new AnnonceSqlAccess($bd);
    $dataUsers = new UserSqlAccess($bd);

} catch (PDOException $e) {
    print "Erreur de connexion !: " . $e->getMessage() . "<br/>";
    die();
}
$apiEmploi = new ApiEmploi();
$token = $apiEmploi->getToken() ;
// initialisation du controller
$controller = new Controllers();

// intialisation du cas d'utilisation service\AnnoncesChecking
$annoncesCheck = new AnnoncesChecking() ;

// intialisation du cas d'utilisation service\UserChecking
$userCheck = new UserChecking() ;

// intialisation du cas d'utilisation service\UserCreation
$userCreation = new UserCreation() ;

// intialisation du presenter avec accès aux données de AnnoncesCheking
$presenter = new Presenter($annoncesCheck);

$annonceCreation = new AnnonceCreation() ;

// chemin de l'URL demandée au navigateur
// (p.ex. /index.php)
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$apiAlternance = new ApiAlternance();
// définition d'une session d'une heure
ini_set('session.gc_maxlifetime', 3600);
session_set_cookie_params(3600);
session_start();

// Authentification et création du compte (sauf pour le formulaire de connexion et de création de compte)
if ( '/' != $uri and '/index.php' != $uri and '/index.php/logout' != $uri  and '/index.php/create' != $uri){

    $error = $controller->authenticateAction($userCreation, $userCheck, $dataUsers);

    if( $error != null )
    {
        $uri='/index.php/error' ;
        if( $error == 'bad login or pwd' or $error == 'not connected')
            $redirect = '/index.php';

        if( $error == 'creation impossible')
            $redirect = '/index.php/create';
    }
}

// route la requête en interne
// i.e. lance le bon contrôleur en fonction de la requête effectuée
if ( '/' == $uri || '/index.php' == $uri || '/index.php/logout' == $uri) {
    // affichage de la page de connexion

    session_destroy();
    $layout = new Layout("gui/layout.html" );
    $vueLogin = new ViewLogin( $layout );

    $vueLogin->display();
}
elseif ( '/index.php/create' == $uri ) {
    // Affichage du fromulaire de création de compte

    $layout = new Layout("gui/layout.html" );
    $vueCreate = new ViewCreate( $layout );

    $vueCreate->display();
}
elseif ( '/index.php/annonces' == $uri ){
    // affichage de toutes les annonces

    if(isset($_POST['contractType'])){
        $controller->annonceCreationAction($_SESSION['login'], $_POST,$dataAnnonces,$annonceCreation);
    }
    $controller->annoncesAction($dataAnnonces, $annoncesCheck);

    $layout = new Layout("gui/layoutLogged.html" );
    $vueAnnonces= new ViewAnnonces( $layout,  $_SESSION['login'], $presenter);

    $vueAnnonces->display();
}
elseif ( '/index.php/post' == $uri
    && isset($_GET['id'])) {
    // Affichage d'une annonce

    $controller->postAction($_GET['id'], $dataAnnonces, $annoncesCheck);

    $layout = new Layout("gui/layout.html" );
    $vuePost= new ViewPost( $layout,  $_SESSION['login'], $presenter );

    $vuePost->display();
}
elseif ( '/index.php/error' == $uri ){
    // Affichage d'un message d'erreur

    $layout = new Layout("gui/layout.html" );
    $vueError = new ViewError( $layout, $error, $redirect );

    $vueError->display();
}
elseif ( '/index.php/annoncesAlternance' == $uri ){
    // Affichage de toutes les entreprises offrant de l'alternance

    $controller->annoncesAction($apiAlternance, $annoncesCheck);

    $layout = new Layout("gui/layoutLogged.html" );
    $vueAnnoncesAlternance= new ViewAnnoncesAlternance( $layout,  $_SESSION['login'], $presenter);

    $vueAnnoncesAlternance->display();
}
elseif ( '/index.php/annoncesEmploi' == $uri ){
    // Affichage de toutes les entreprises offrant de l'alternance

    $controller->annoncesAction($apiEmploi, $annoncesCheck);

    $layout = new Layout("gui/layoutLogged.html" );
    $vueAnnoncesEmploi= new ViewAnnoncesEmploi( $layout,  $_SESSION['login'], $presenter);

    $vueAnnoncesEmploi->display();
}
elseif ( '/index.php/offreEmploi' == $uri ){
    // Affichage de toutes les entreprises offrant de l'alternance
    $controller->postAction($_GET['id'], $apiEmploi, $annoncesCheck);
    $layout = new Layout("gui/layoutLogged.html" );
    $vuePostEmploi = new ViewOffreEmploi($layout,  $_SESSION['login'], $presenter);

    $vuePostEmploi->display();
}
elseif ( '/index.php/companyAlternance' == $uri
    && isset($_GET['id'])) {
    // Affichage d'une entreprise offrant de l'alternance

    $controller->postAction($_GET['id'], $apiAlternance, $annoncesCheck);

    $layout = new Layout("gui/layoutLogged.html" );
    $vuePostAlternance = new ViewCompanyAlternance( $layout,  $_SESSION['login'], $presenter );

    $vuePostAlternance->display();
}
elseif ('/index.php/createAnnonce' == $uri ){
    $layout = new Layout("gui/layoutLogged.html" );
    $vueCreateAnnonce = new ViewCreateAnnonce( $layout);
    $vueCreateAnnonce->display();
}

else {
    header('Status: 404 Not Found');
    echo '<html lang="fr"><body><h1>My Page NotFound</h1></body></html>';
}

