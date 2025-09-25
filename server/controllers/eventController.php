<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../services/EventService.php';

class EventController extends Controller {
    private $eventService;
    
    public function __construct() {
        $this->eventService = new EventService();
    }
    
    public function getAllEvents() {
        try {
            $events = $this->eventService->getAllEvents();
            return $this->successResponse($events, 'Events retrieved successfully');
        } catch (Exception $e) {
            return $this->errorResponse('Failed to retrieve events', 500);
        }
    }
    
    public function getEvent($id) {
        try {
            $event = $this->eventService->getEventById($id);
            return $this->successResponse($event, 'Event retrieved successfully');
        } catch (InvalidArgumentException $e) {
            return $this->errorResponse($e->getMessage(), 400);
        } catch (Exception $e) {
            return $this->errorResponse('Event not found', 404);
        }
    }
    
    public function createEvent() {
        try {
            $data = $this->getRequestData();
            
            $eventId = $this->eventService->createEvent($data);
            
            return $this->successResponse(
                ['id' => $eventId], 
                'Event created successfully'
            );
            
        } catch (InvalidArgumentException $e) {
            return $this->errorResponse($e->getMessage(), 400);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to create event', 500);
        }
    }
    
    public function updateEvent($id) {
        try {
            $data = $this->getRequestData();
            
            $this->eventService->updateEvent($id, $data);
            
            return $this->successResponse(null, 'Event updated successfully');
            
        } catch (InvalidArgumentException $e) {
            return $this->errorResponse($e->getMessage(), 400);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to update event', 500);
        }
    }
    
    public function deleteEvent($id) {
        try {
            $this->eventService->deleteEvent($id);
            return $this->successResponse(null, 'Event deleted successfully');
        } catch (InvalidArgumentException $e) {
            return $this->errorResponse($e->getMessage(), 400);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to delete event', 500);
        }
    }

    public function getEventsByUser($userId) {
        try {
            $events = $this->eventService->getEventsByUserId($userId);
            return $this->successResponse($events, 'Events retrieved successfully');
        } catch (InvalidArgumentException $e) {
            return $this->errorResponse($e->getMessage(), 400);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to retrieve events for user', 500);
        }
    }

}