<?php

require_once __DIR__ . '/../dataAccess/RegistrationDataAccess.php';
require_once __DIR__ . '/../models/Registration.php';

class RegistrationService {
    private $registrationDataAccess;

    public function __construct() {
        $this->registrationDataAccess = new RegistrationDataAccess();
    }

    public function registerUserToEvent($userId,$eventId) {
        if (!is_numeric($userId) || $userId <= 0) {
            throw new InvalidArgumentException('Invalid user ID');
        }
        if (!is_numeric($eventId) || $eventId <= 0) {
            throw new InvalidArgumentException('Invalid event ID');
        }

        $registration = new Registration(['userId' => $userId, 'eventId' => $eventId]);

        try {
            return $this->registrationDataAccess->create($registration);
        } catch (Exception $e) {
            throw new Exception('Failed to register user to event: ' . $e->getMessage());
        }
    }

    public function unregisterUserFromEvent($userId,$eventId) {
        if (!is_numeric($userId) || $userId <= 0) {
            throw new InvalidArgumentException('Invalid user ID');
        }
        if (!is_numeric($eventId) || $eventId <= 0) {
            throw new InvalidArgumentException('Invalid event ID');
        }

        $registration = $this->registrationDataAccess->findByUseridAndEventid($userId, $eventId);
        if (!$registration) {
            throw new Exception('Registration not found');
        }

        try {
            return $this->registrationDataAccess->delete($registration->userId, $registration->eventId);
        } catch (Exception $e) {
            throw new Exception('Failed to unregister user from event: ' . $e->getMessage());
        }
    }

}