<?php

class HomeController
{
    private $twig;

    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    /**
     * Affiche la page d'accueil avec redirection vers la page de connexion
     * @return string
     */
    public function showHome()
    {
        try {
            // Préparation des données pour la vue
            $viewData = [
                'page_title' => 'Bienvenue sur EventX',
            ];

            // Rendu de la vue Twig
            return $this->twig->render('home.twig', $viewData);

        } catch (Exception $e) {
            // En cas d'erreur, on redirige directement
            header('Location: login.php');
            exit;
        }
    }

    /**
     * Redirige directement vers la page de connexion
     */
    public function redirectToLogin()
    {
        header('Location: login.php');
        exit;
    }
}