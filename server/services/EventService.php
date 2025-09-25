<?php

require_once __DIR__ . '/../dataAccess/EventDataAccess.php';
require_once __DIR__ . '/../models/Event.php';

class EventService {
    private $eventDataAccess;
    
    public function __construct() {
        $this->eventDataAccess = new EventDataAccess();
    }
    
    public function getAllEvents() {
        try {
            return $this->eventDataAccess->readAll();
        } catch (Exception $e) {
            throw new Exception('Failed to retrieve events: ' . $e->getMessage());
        }
    }
    
    public function getEventById($id) {
        if (!is_numeric($id) || $id <= 0) {
            throw new InvalidArgumentException('Invalid event ID');
        }
        
        $event = $this->eventDataAccess->read($id);
        
        if (!$event) {
            throw new Exception('Event not found');
        }
        
        return $event;
    }
    
    public function createEvent($eventData) {
        
        $event = new Event($eventData);
        
        $eventId = $this->eventDataAccess->create($event);
        
        if (!$eventId) {
            throw new Exception('Failed to create event');
        }
        
        return $eventId;
    }
    
    public function updateEvent($id, $eventData) {
    
        $existingEvent = $this->getEventById($id);
    
    $existingEvent->name = $eventData['name'] ?? $existingEvent->name;
    $existingEvent->description = $eventData['description'] ?? $existingEvent->description;
    $existingEvent->startDate = $eventData['startDate'] ?? $existingEvent->startDate;
    $existingEvent->endDate = $eventData['endDate'] ?? $existingEvent->endDate;
    $existingEvent->ownerId = $eventData['ownerId'] ?? $existingEvent->ownerId;
    
    $result = $this->eventDataAccess->update($existingEvent);
    
    if (!$result) {
        throw new Exception('Failed to update event');
    }
    
    return true;
}
    
    public function deleteEvent($id) {
        $result = $this->eventDataAccess->delete($id);
        
        if (!$result) {
            throw new Exception('Failed to delete event');
        }
        
        return true;
    } 
}