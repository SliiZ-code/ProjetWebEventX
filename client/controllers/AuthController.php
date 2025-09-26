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

        $eventUser = new EventUser();
        $result = $eventUser->authenticateUser($email, $password);

        if ($result['success']) {
            session_start();
            $_SESSION['user'] = $result['data'];
            $_SESSION['email'] = $email;
            header("Location: events.php");
            exit;
        } else {
            return $this->showLoginForm('Email ou mot de passe incorrect.', null, $email);
        }
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

        $eventUser = new EventUser();
        $result = $eventUser->registerUser($email, $password);

        if ($result['success']) {
            return $this->showRegisterForm(null, 'Inscription reussie ! Vous pouvez vous connecter.');
        } else {
            return $this->showRegisterForm('Erreur lors de inscription.', null, $email);
        }
    }
}
