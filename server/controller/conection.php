<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Inclure les dépendances
require_once '../config/database.php';
require_once '../model/User.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['message' => 'Méthode non autorisée']);
    exit;
}

try {
    // Récupérer les données JSON
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    if (!$data) {
        http_response_code(400);
        echo json_encode(['message' => 'Données invalides']);
        exit;
    }
    
    $email = $data['email'] ?? '';
    $password = $data['password'] ?? '';
    
    // Validation
    if (empty($email) || empty($password)) {
        http_response_code(400);
        echo json_encode(['message' => 'Email et mot de passe requis']);
        exit;
    }
    
    // Authentification via le modèle User
    $user = new User();
    $result = $user->authenticate($email, $password);
    
    if ($result) {
        // Connexion réussie
        session_start();
        $_SESSION['user_id'] = $result['id'];
        $_SESSION['email'] = $result['email'];
        
        http_response_code(200);
        echo json_encode([
            'message' => 'Connexion réussie',
            'user' => [
                'id' => $result['id'],
                'email' => $result['email'],
                'role' => $result['role']
            ],
            'redirect' => '/client/profile.html'
        ]);
    } else {
        http_response_code(401);
        echo json_encode(['message' => 'Email ou mot de passe incorrect']);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Erreur serveur : ' . $e->getMessage()]);
}
?>