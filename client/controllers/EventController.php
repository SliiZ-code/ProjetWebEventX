<?php

require_once __DIR__ . '/../services/EventService.php';

class EventController
{
    private $eventService;
    private $twig;

    public function __construct($twig) {
        $this->twig = $twig;
        $this->eventService = new EventService();
    }

    public function showAllEvents() {
        $result = $this->eventService->getAllEvents();
        
        return $this->twig->render('events.twig', [
            'success' => $result['success'],
            'message' => $result['message'],
            'events' => $result['data'] ?? [],
            'events_count' => count($result['data'] ?? [])
        ]);
    }
}
