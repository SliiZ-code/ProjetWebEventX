<?php

require_once __DIR__ . '/../core/Router.php';
require_once __DIR__ . '/../controllers/EventController.php';
require_once __DIR__ . '/../controllers/UserController.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') exit(0);

$router = new Router();

$router->get('/events', function($params) {
    $controller = new EventController();
    echo $controller->getAllEvents();
});

$router->get('/events/{id}', function($params) {
    $controller = new EventController();
    echo $controller->getEvent($params['id']);
});

$router->post('/events', function($params) {
    $controller = new EventController();
    echo $controller->createEvent();
});

$router->put('/events/{id}', function($params) {
    $controller = new EventController();
    echo $controller->updateEvent($params['id']);
});

$router->delete('/events/{id}', function($params) {
    $controller = new EventController();
    echo $controller->deleteEvent($params['id']);
});

$router->get('/users', function($params) {
    $controller = new UserController();
    echo $controller->getAllUsers();
});

$router->get('/users/{id}', function($params) {
    $controller = new UserController();
    echo $controller->getUser($params['id']);
});

$router->post('/users', function($params) {
    $controller = new UserController();
    echo $controller->createUser();
});

$router->post('/auth/login', function($params) {
    $controller = new UserController();
    echo $controller->login();
});

$router->post('/auth/register', function($params) {
    $controller = new UserController();
    echo $controller->register();
});

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['PATH_INFO'] ?? '/');
?>