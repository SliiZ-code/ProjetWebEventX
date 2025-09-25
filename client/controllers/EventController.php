<?php

require_once __DIR__ . '/../services/EventService.php';

class EventController
{
    private $eventService;
    private $twig;

    public function __construct($twig)
    {
        $this->twig = $twig;
        $this->eventService = new EventService();
    }

    /**
     * Affiche la liste de tous les événements
     * @return string
     */
    public function showAllEvents()
    {
        try {
            // Récupération des événements via le service
            $result = $this->eventService->getAllEvents();
            
            // Préparation des données pour la vue
            $viewData = [
                'page_title' => 'Liste des Événements - EventX',
                'success' => $result['success'],
                'message' => $result['message'],
                'events' => $result['data'],
                'events_count' => count($result['data'])
            ];

            // Si on a des événements, on les formate pour l'affichage
            if ($result['success'] && !empty($result['data'])) {
                $viewData['events'] = $this->formatEventsForView($result['data']);
            }

            // Rendu de la vue Twig
            return $this->twig->render('events.twig', $viewData);

        } catch (Exception $e) {
            // En cas d'erreur, on affiche une page d'erreur
            $viewData = [
                'page_title' => 'Erreur - EventX',
                'success' => false,
                'message' => 'Une erreur inattendue s\'est produite: ' . $e->getMessage(),
                'events' => [],
                'events_count' => 0
            ];

            return $this->twig->render('events.twig', $viewData);
        }
    }

    /**
     * Affiche le détail d'un événement
     * @param int $id
     * @return string
     */
    public function showEventDetail($id)
    {
        try {
            $result = $this->eventService->getEventById($id);
            
            $viewData = [
                'page_title' => 'Détail de l\'Événement - EventX',
                'success' => $result['success'],
                'message' => $result['message'],
                'event' => $result['success'] ? $this->formatEventForView($result['data']) : null
            ];

            return $this->twig->render('event-detail.twig', $viewData);

        } catch (Exception $e) {
            $viewData = [
                'page_title' => 'Erreur - EventX',
                'success' => false,
                'message' => 'Erreur lors du chargement de l\'événement: ' . $e->getMessage(),
                'event' => null
            ];

            return $this->twig->render('event-detail.twig', $viewData);
        }
    }

    /**
     * Formate les événements pour l'affichage dans la vue
     * @param array $events
     * @return array
     */
    private function formatEventsForView($events)
    {
        $formattedEvents = [];

        foreach ($events as $event) {
            $formattedEvents[] = $this->formatEventForView($event);
        }

        return $formattedEvents;
    }

    /**
     * Formate un événement pour l'affichage dans la vue
     * @param array $event
     * @return array
     */
    private function formatEventForView($event)
    {
        return [
            'id' => $event['id'] ?? 0,
            'name' => $event['name'] ?? 'Sans titre',
            'description' => $event['description'] ?? 'Aucune description disponible',
            'startDate' => $this->formatDate($event['startDate'] ?? null),
            'endDate' => $this->formatDate($event['endDate'] ?? null),
            'startDateTime' => $this->formatDateTime($event['startDate'] ?? null),
            'endDateTime' => $this->formatDateTime($event['endDate'] ?? null),
            'ownerId' => $event['ownerId'] ?? 0,
            'creationDate' => $this->formatDateTime($event['creationDate'] ?? null),
            'updateDate' => $this->formatDateTime($event['updateDate'] ?? null),
            'duration' => $this->calculateEventDuration($event['startDate'] ?? null, $event['endDate'] ?? null)
        ];
    }

    /**
     * Formate une date au format français
     * @param string $dateString
     * @return string
     */
    private function formatDate($dateString)
    {
        if (!$dateString) {
            return 'Non spécifiée';
        }

        try {
            $date = new DateTime($dateString);
            return $date->format('d/m/Y');
        } catch (Exception $e) {
            return $dateString;
        }
    }

    /**
     * Formate une date et heure au format français
     * @param string $dateString
     * @return string
     */
    private function formatDateTime($dateString)
    {
        if (!$dateString) {
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
     * Calcule la durée d'un événement
     * @param string $startDate
     * @param string $endDate
     * @return string
     */
    private function calculateEventDuration($startDate, $endDate)
    {
        if (!$startDate || !$endDate) {
            return 'Durée non calculable';
        }

        try {
            $start = new DateTime($startDate);
            $end = new DateTime($endDate);
            $interval = $start->diff($end);

            if ($interval->days > 0) {
                return $interval->days . ' jour(s), ' . $interval->h . ' heure(s)';
            } else {
                return $interval->h . ' heure(s), ' . $interval->i . ' minute(s)';
            }
        } catch (Exception $e) {
            return 'Durée non calculable';
        }
    }
}