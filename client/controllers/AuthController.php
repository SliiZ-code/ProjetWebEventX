<?php

require_once __DIR__ . '/../services/EventUser.php';

class AuthController
{
    private $twig;

    public function __construct($twig) {
        $this->twig = $twig;
    }

    public function showLoginForm($error = null, $success = null, $email = '') {
        return $this->twig->render('login.twig', [
            'error' => $error,
            'success' => $success, 
            'email' => $email
        ]);
    }

    public function showRegisterForm($error = null, $success = null, $email = '') {
        return $this->twig->render('register.twig', [
            'error' => $error,
            'success' => $success, 
            'email' => $email
        ]);
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->showLoginForm();
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            return $this->showLoginForm('Tous les champs sont requis.', null, $email);
        }

        // Redirection directe vers les événements
        session_start();
        $_SESSION['email'] = $email;
        header("Location: events.php");
        exit;
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->showRegisterForm();
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            return $this->showRegisterForm('Tous les champs sont requis.', null, $email);
        }

        if (strlen($password) < 6) {
            return $this->showRegisterForm('Le mot de passe doit contenir au moins 6 caracteres.', null, $email);
        }

        // Redirection directe vers les événements
        session_start();
        $_SESSION['email'] = $email;
        header("Location: events.php");
        exit;
    }
}
