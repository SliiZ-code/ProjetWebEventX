<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/controllers/EventController.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

// Configuration Twig
$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader, ['cache' => false]);

// Contrôleur
$eventController = new EventController($twig);

// Afficher les événements
echo $eventController->showAllEvents();

?>