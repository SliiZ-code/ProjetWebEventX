<?php

class EventUser {
    private $apiBaseUrl;

    public function __construct($apiBaseUrl = 'http://eventx_api:8000/api')
    {
        $this->apiBaseUrl = rtrim($apiBaseUrl, '/');
    }

    /**
     * Authentifier un utilisateur avec email et mot de passe
     */
    public function authenticateUser($email, $password)
    {
        $url = $this->apiBaseUrl . '/auth/login';
        
        $postData = [
            'email' => $email,
            'password' => $password
        ];
        
        try {
            $response = $this->makeApiCall($url, 'POST', $postData);
            
            if (isset($response['success']) && $response['success'] === true) {
                return [
                    'success' => true,
                    'data' => $response['data'] ?? [],
                    'message' => $response['message'] ?? 'Authentification réussie'
                ];
            } else {
                return [
                    'success' => false,
                    'data' => [],
                    'message' => $response['message'] ?? 'Erreur d\'authentification'
                ];
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'data' => [],
                'message' => 'Erreur de connexion à l\'API: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Récupérer tous les utilisateurs
     */
    public function getAllUsers()
    {
        $url = $this->apiBaseUrl . '/users';
        
        try {
            $response = $this->makeApiCall($url);
            
            if (isset($response['success']) && $response['success'] === true) {
                return [
                    'success' => true,
                    'data' => $response['data'] ?? [],
                    'message' => $response['message'] ?? 'Utilisateurs récupérés avec succès'
                ];
            } else {
                return [
                    'success' => false,
                    'data' => [],
                    'message' => $response['message'] ?? 'Erreur lors de la récupération des utilisateurs'
                ];
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'data' => [],
                'message' => 'Erreur de connexion à l\'API: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Récupérer un utilisateur par son ID
     */
    public function getUserById($userId)
    {
        $url = $this->apiBaseUrl . '/users/' . $userId;
        
        try {
            $response = $this->makeApiCall($url);
            
            if (isset($response['success']) && $response['success'] === true) {
                return [
                    'success' => true,
                    'data' => $response['data'] ?? [],
                    'message' => $response['message'] ?? 'Utilisateur récupéré avec succès'
                ];
            } else {
                return [
                    'success' => false,
                    'data' => [],
                    'message' => $response['message'] ?? 'Utilisateur non trouvé'
                ];
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'data' => [],
                'message' => 'Erreur de connexion à l\'API: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Effectue un appel API
     */
    private function makeApiCall($url, $method = 'GET', $data = null)
    {
        $ch = curl_init();
        
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json'
            ]
        ]);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            if ($data) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }
        } elseif ($method === 'PUT') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            if ($data) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }
        } elseif ($method === 'DELETE') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new Exception("Erreur cURL: " . $error);
        }

        if ($httpCode >= 400) {
            throw new Exception("Erreur HTTP: " . $httpCode);
        }

        $decodedResponse = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Réponse JSON invalide: " . json_last_error_msg());
        }

        return $decodedResponse;
    }
}