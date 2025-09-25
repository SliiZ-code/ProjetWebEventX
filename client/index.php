<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/controllers/HomeController.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

// Configuration Twig
$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader, [
    'cache' => false, // Désactiver le cache pour le développement
    'debug' => true
]);

// Initialiser le contrôleur d'accueil
$homeController = new HomeController($twig);

// Gérer les requêtes
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Affichage de la page d'accueil
        echo $homeController->showHome();
        break;
        
    default:
        // Pour toute autre méthode, rediriger vers login
        $homeController->redirectToLogin();
}

?>