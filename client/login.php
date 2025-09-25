<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/controllers/AuthController.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

// Configuration Twig
$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader, [
    'cache' => false, // Désactiver le cache pour le développement
    'debug' => true
]);

// Initialiser le contrôleur d'authentification
$authController = new AuthController($twig);

// Gérer les requêtes
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Affichage du formulaire de connexion
        echo $authController->showLoginForm();
        break;
        
    case 'POST':
        // Traitement du formulaire de connexion
        echo $authController->processLogin();
        break;
        
    default:
        http_response_code(405);
        echo $authController->showLoginForm('Méthode non autorisée');
}

?>