<?php

require_once __DIR__ . '/DataAccess.php';
require_once __DIR__ . '/../models/Event.php';

class EventDataAccess extends DataAccess {
    
    public function __construct() {
        parent::__construct();
    }

    public function create($entity) {
        if (!$entity instanceof Event) {
            throw new InvalidArgumentException('Entity must be an Event instance');
        }
        
        $query = "INSERT INTO Event (name, description, startDate, endDate, ownerId) 
                  VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $this->connection->prepare($query);
        $result = $stmt->execute([
            $entity->name,
            $entity->description,
            $entity->startDate,
            $entity->endDate,
            $entity->ownerId
        ]);
        
        return $result ? $this->connection->lastInsertId() : false;
    }

    public function read($id) {
        $query = "SELECT * FROM Event WHERE id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->execute([$id]);
        
        $event = $stmt->fetchObject('Event');
        return $event ?: null;
    }

    public function readAll() {
        $query = "SELECT * FROM Event ORDER BY id ASC";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Event');
    }

    public function update($entity) {
        if (!$entity instanceof Event) {
            throw new InvalidArgumentException('Entity must be an Event instance');
        }
        
        $query = "UPDATE Event 
                  SET name = ?, description = ?, startDate = ?, endDate = ? 
                  WHERE id = ?";
        
        $stmt = $this->connection->prepare($query);
        return $stmt->execute([
            $entity->name,
            $entity->description,
            $entity->startDate,
            $entity->endDate,
            $entity->id
        ]);
    }

    public function delete($id) {
        $query = "DELETE FROM Event WHERE id = ?";
        $stmt = $this->connection->prepare($query);
        return $stmt->execute([$id]);
    }
}