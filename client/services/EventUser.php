<?php

class EventUser {
    private $apiBaseUrl;

    public function __construct($apiBaseUrl = 'http://eventx_api:8000/api') {
        $this->apiBaseUrl = rtrim($apiBaseUrl, '/');
    }

    public function authenticateUser($email, $password) {
        $url = $this->apiBaseUrl . '/auth/login';
        $data = ['email' => $email, 'password' => $password];
        
        try {
            $response = $this->makeApiCall($url, 'POST', $data);
            return [
                'success' => $response['success'] ?? false,
                'data' => $response['data'] ?? [],
                'message' => $response['message'] ?? 'Erreur inconnue'
            ];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Erreur de connexion'];
        }
    }

    public function registerUser($email, $password) {
        $url = $this->apiBaseUrl . '/auth/register';
        $data = ['email' => $email, 'password' => $password];
        
        try {
            $response = $this->makeApiCall($url, 'POST', $data);
            return [
                'success' => $response['success'] ?? false,
                'data' => $response['data'] ?? [],
                'message' => $response['message'] ?? 'Erreur inconnue'
            ];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Erreur de connexion'];
        }
    }

    private function makeApiCall($url, $method = 'GET', $data = null) {
        $context = [
            'http' => [
                'method' => $method,
                'header' => 'Content-Type: application/json',
                'timeout' => 10
            ]
        ];

        if ($data && $method === 'POST') {
            $context['http']['content'] = json_encode($data);
        }

        $response = file_get_contents($url, false, stream_context_create($context));
        
        if ($response === false) {
            throw new Exception('API call failed');
        }

        $decoded = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON response');
        }

        return $decoded;
    }
}
