<?php

require_once __DIR__ . '/../services/EventUser.php';

class AuthController
{
    private $twig;
    private $userService;

    public function __construct($twig)
    {
        $this->twig = $twig;
        $this->userService = new EventUser();
    }

    /**
     * Affiche le formulaire de connexion
     * @return string
     */
    public function showLoginForm($error = null, $success = null, $email = null)
    {
        try {
            // Préparation des données pour la vue
            $viewData = [
                'page_title' => 'Connexion - EventX',
                'error' => $error,
                'success' => $success,
                'email' => $email
            ];

            // Rendu de la vue Twig
            return $this->twig->render('login.twig', $viewData);

        } catch (Exception $e) {
            // En cas d'erreur, on affiche une page d'erreur simple
            return $this->twig->render('login.twig', [
                'page_title' => 'Erreur - EventX',
                'error' => 'Une erreur inattendue s\'est produite: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Traite la soumission du formulaire de connexion
     * @return string
     */
    public function processLogin()
    {
        $email = $_POST['email'] ?? $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        // Validation basique
        if (empty($email) || empty($password)) {
            return $this->showLoginForm('Veuillez saisir votre email et mot de passe.', null, $email);
        }

        // Validation format email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->showLoginForm('Format d\'email invalide.', null, $email);
        }

        try {
            // Vérifier si l'utilisateur existe dans la base de données via l'API
            $authResult = $this->userService->authenticateUser($email, $password);

            if ($authResult['success']) {
                // Utilisateur trouvé et authentifié
                session_start();
                $_SESSION['user'] = [
                    'id' => $authResult['data']['id'] ?? null,
                    'email' => $email,
                    'name' => $authResult['data']['name'] ?? $email,
                    'logged_in' => true
                ];

                // Redirection vers les événements (HTTP 302)
                header('Location: events.php', true, 302);
                exit;
            } else {
                // Vérifier si l'utilisateur existe mais mot de passe incorrect
                $userCheck = $this->checkUserExists($email);
                
                if ($userCheck['exists']) {
                    return $this->showLoginForm('Mot de passe incorrect.', null, $email);
                } else {
                    return $this->showLoginForm('Aucun compte trouvé avec cet email. Veuillez vous inscrire d\'abord.', null, $email);
                }
            }

        } catch (Exception $e) {
            // Erreur de connexion API
            return $this->showLoginForm('Erreur de connexion au serveur. Veuillez réessayer plus tard.', null, $email);
        }
    }

    /**
     * Vérifie si un utilisateur existe dans la base de données
     * @param string $email
     * @return array
     */
    private function checkUserExists($email)
    {
        try {
            // Récupérer tous les utilisateurs et chercher l'email
            $usersResult = $this->userService->getAllUsers();
            
            if ($usersResult['success']) {
                $users = $usersResult['data'];
                
                foreach ($users as $user) {
                    if (isset($user['email']) && $user['email'] === $email) {
                        return [
                            'exists' => true,
                            'user' => $user
                        ];
                    }
                }
            }
            
            return ['exists' => false, 'user' => null];
            
        } catch (Exception $e) {
            return ['exists' => false, 'user' => null];
        }
    }

    /**
     * Déconnecte l'utilisateur
     */
    public function logout()
    {
        session_start();
        session_destroy();
        header('Location: login.php');
        exit;
    }

    /**
     * Vérifie si un utilisateur est connecté
     * @return bool
     */
    public static function isLoggedIn()
    {
        session_start();
        return isset($_SESSION['user']) && $_SESSION['user']['logged_in'] === true;
    }

    /**
     * Récupère les informations de l'utilisateur connecté
     * @return array|null
     */
    public static function getCurrentUser()
    {
        session_start();
        return $_SESSION['user'] ?? null;
    }
}