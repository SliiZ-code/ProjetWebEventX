<?php

require_once __DIR__ . '/DataAccess.php';
require_once __DIR__ . '/../models/Registration.php';

class RegistrationDataAccess extends DataAccess {
    
    public function __construct() {
        parent::__construct();
    }

    public function create($entity) {
        if (!$entity instanceof Registration) {
            throw new InvalidArgumentException('Entity must be an Registration instance');
        }

        $query = "INSERT INTO Registration (userId, eventId) 
                  VALUES (?, ?)";

        $stmt = $this->connection->prepare($query);
        $result = $stmt->execute([
            $entity->userId,
            $entity->eventId
        ]);

        return $result ? $this->connection->lastInsertId() : false;
    }


    public function findByUseridAndEventid($userId, $eventId) {
        $query = "SELECT * FROM Registration WHERE userId = ? AND eventId = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->execute([$userId, $eventId]);

        $registration = $stmt->fetchObject('Registration');
        return $registration ?: null;
    }

    public function delete($userId, $eventId) {
        $query = "DELETE FROM Registration WHERE userId = ? AND eventId = ?";
        $stmt = $this->connection->prepare($query);
        return $stmt->execute([$userId, $eventId]);
    }
}