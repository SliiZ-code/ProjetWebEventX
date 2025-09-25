<?php

class EventService
{
    private $apiBaseUrl;

    public function __construct($apiBaseUrl = 'http://eventx_api:8000/api')
    {
        $this->apiBaseUrl = rtrim($apiBaseUrl, '/');
    }

    /**
     * Récupère tous les événements depuis l'API
     * @return array
     */
    public function getAllEvents()
    {
        $url = $this->apiBaseUrl . '/events';
        
        try {
            $response = $this->makeApiCall($url);
            
            if (isset($response['success']) && $response['success'] === true) {
                return [
                    'success' => true,
                    'data' => $response['data'] ?? [],
                    'message' => $response['message'] ?? 'Événements récupérés avec succès'
                ];
            } else {
                return [
                    'success' => false,
                    'data' => [],
                    'message' => $response['message'] ?? 'Erreur lors de la récupération des événements'
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
     * Récupère un événement spécifique par son ID
     * @param int $id
     * @return array
     */
    public function getEventById($id)
    {
        $url = $this->apiBaseUrl . '/events/' . intval($id);
        
        try {
            $response = $this->makeApiCall($url);
            
            if (isset($response['success']) && $response['success'] === true) {
                return [
                    'success' => true,
                    'data' => $response['data'] ?? null,
                    'message' => $response['message'] ?? 'Événement récupéré avec succès'
                ];
            } else {
                return [
                    'success' => false,
                    'data' => null,
                    'message' => $response['message'] ?? 'Événement non trouvé'
                ];
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'data' => null,
                'message' => 'Erreur de connexion à l\'API: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Effectue un appel à l'API
     * @param string $url
     * @param string $method
     * @param array $data
     * @return array
     */
    private function makeApiCall($url, $method = 'GET', $data = null)
    {
        $context = stream_context_create([
            'http' => [
                'method' => $method,
                'header' => [
                    'Content-Type: application/json',
                    'Accept: application/json'
                ],
                'timeout' => 10
            ]
        ]);

        if ($data && in_array($method, ['POST', 'PUT', 'PATCH'])) {
            $context['http']['content'] = json_encode($data);
        }

        $response = file_get_contents($url, false, $context);
        
        if ($response === false) {
            throw new Exception('Impossible de contacter l\'API');
        }

        $decodedResponse = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Réponse API invalide');
        }

        return $decodedResponse;
    }
}