<?php

require_once __DIR__ . '/../services/EventService.php';

class EventDetailController
{
    private $eventService;
    private $twig;

    public function __construct($twig)
    {
        $this->twig = $twig;
        $this->eventService = new EventService();
    }

    /**
     * Affiche les détails d'un événement spécifique
     * @param int $eventId
     * @return string
     */
    public function showEventDetail($eventId)
    {
        try {
            // Récupération de l'événement via le service
            $result = $this->eventService->getEventById($eventId);
            
            // Préparation des données pour la vue
            $viewData = [
                'page_title' => 'Détail de l\'événement - EventX',
                'success' => $result['success'],
                'message' => $result['message'],
                'event' => null
            ];

            // Si on a l'événement, on le formate pour l'affichage
            if ($result['success'] && !empty($result['data'])) {
                $viewData['event'] = $this->formatEventForView($result['data']);
                $viewData['page_title'] = $viewData['event']['name'] . ' - EventX';
            }

            // Rendu de la vue Twig
            return $this->twig->render('event-detail.twig', $viewData);

        } catch (Exception $e) {
            // En cas d'erreur, on affiche une page d'erreur
            $viewData = [
                'page_title' => 'Erreur - EventX',
                'success' => false,
                'message' => 'Une erreur inattendue s\'est produite: ' . $e->getMessage(),
                'event' => null
            ];

            return $this->twig->render('event-detail.twig', $viewData);
        }
    }

    /**
     * Formate un événement pour l'affichage dans la vue
     * @param array $event
     * @return array
     */
    private function formatEventForView($event)
    {
        if (empty($event)) {
            return null;
        }

        return [
            'id' => $event['id'] ?? null,
            'name' => $event['name'] ?? 'Sans titre',
            'description' => $event['description'] ?? 'Aucune description disponible',
            'startDate' => $event['startDate'] ?? null,
            'endDate' => $event['endDate'] ?? null,
            'ownerId' => $event['ownerId'] ?? null,
            'creationDate' => $event['creationDate'] ?? null,
            'updateDate' => $event['updateDate'] ?? null,
            'startDateFormatted' => $this->formatDate($event['startDate'] ?? null),
            'endDateFormatted' => $this->formatDate($event['endDate'] ?? null),
            'creationDateFormatted' => $this->formatDateTime($event['creationDate'] ?? null),
            'lastUpdateDateFormatted' => $this->formatDateTime($event['updateDate'] ?? null),
            'duration' => $this->calculateDuration($event['startDate'] ?? null, $event['endDate'] ?? null)
        ];
    }

    /**
     * Formate une date pour l'affichage
     * @param string|null $dateString
     * @return string
     */
    private function formatDate($dateString)
    {
        if (empty($dateString)) {
            return 'Non spécifiée';
        }

        try {
            $date = new DateTime($dateString);
            return $date->format('d/m/Y à H:i');
        } catch (Exception $e) {
            return $dateString;
        }
    }

    /**
     * Formate une date/heure complète pour l'affichage
     * @param string|null $dateString
     * @return string
     */
    private function formatDateTime($dateString)
    {
        if (empty($dateString)) {
            return 'Non spécifiée';
        }

        try {
            $date = new DateTime($dateString);
            return $date->format('d/m/Y à H:i:s');
        } catch (Exception $e) {
            return $dateString;
        }
    }

    /**
     * Calcule la durée entre deux dates
     * @param string|null $startDate
     * @param string|null $endDate
     * @return string|null
     */
    private function calculateDuration($startDate, $endDate)
    {
        if (empty($startDate) || empty($endDate)) {
            return null;
        }

        try {
            $start = new DateTime($startDate);
            $end = new DateTime($endDate);
            $interval = $start->diff($end);
            
            $parts = [];
            
            if ($interval->d > 0) {
                $parts[] = $interval->d . ' jour' . ($interval->d > 1 ? 's' : '');
            }
            
            if ($interval->h > 0) {
                $parts[] = $interval->h . ' heure' . ($interval->h > 1 ? 's' : '');
            }
            
            if ($interval->i > 0) {
                $parts[] = $interval->i . ' minute' . ($interval->i > 1 ? 's' : '');
            }
            
            return !empty($parts) ? implode(', ', $parts) : 'Moins d\'une minute';
            
        } catch (Exception $e) {
            return null;
        }
    }
}