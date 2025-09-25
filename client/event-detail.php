<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/controllers/EventDetailController.php';

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

// Récupérer l'ID de l'événement depuis les paramètres GET
$eventId = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$eventId || $eventId <= 0) {
    // Rediriger vers la liste si pas d'ID valide
    header('Location: events.php');
    exit;
}

try {
    // Initialiser le contrôleur et afficher la page
    $controller = new EventDetailController($twig);
    echo $controller->showEventDetail($eventId);
} catch (Exception $e) {
    // En cas d'erreur, rediriger vers la liste avec un message d'erreur
    header('Location: events.php?error=' . urlencode('Erreur lors de l\'affichage de l\'événement'));
    exit;
}

?>