<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/controllers/EventController.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

// Configuration Twig
$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader, [
    'cache' => false, // Désactiver le cache pour le développement
    'debug' => true
]);

// Démarrer la session
session_start();

// Récupérer l'URL demandée et la méthode HTTP
$requestUri = $_SERVER['REQUEST_URI'];
$path = parse_url($requestUri, PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Nettoyer le chemin
$path = rtrim($path, '/');
if ($path === '') {
    $path = '/';
}

// Initialiser le contrôleur d'événements
$eventController = new EventController($twig);

switch ($path) {
    case '/':
    case '/events.php':
    case '/events':
        // Affichage de la liste des événements
        if ($method === 'GET') {
            echo $eventController->showAllEvents();
        } else {
            http_response_code(405);
            echo "Méthode non autorisée";
        }
        break;
    
    default:
        // Par défaut, afficher la liste des événements
        echo $eventController->showAllEvents();
        break;
}