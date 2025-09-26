<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/controllers/AuthController.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

// Configuration Twig
$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader, ['cache' => false]);

// Contrôleur
$authController = new AuthController($twig);

// Gestion simple GET/POST
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo $authController->showRegisterForm();
} else {
    echo $authController->register();
}

?>