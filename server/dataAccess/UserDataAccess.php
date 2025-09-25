<?php

require_once __DIR__ . '/DataAccess.php';
require_once __DIR__ . '/../models/User.php';

class UserDataAccess extends DataAccess {
    
    public function __construct() {
        parent::__construct();
    }

    public function create($entity) {
        if (!$entity instanceof User) {
            throw new InvalidArgumentException('Entity must be an User instance');
        }

        $query = "INSERT INTO User (mail, password, idRole, idProfile, isActive) 
                  VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->connection->prepare($query);
        $result = $stmt->execute([
            $entity->mail,
            $entity->password,
            $entity->idRole,
            $entity->idProfile,
            $entity->isActive
        ]);

        return $result ? $this->connection->lastInsertId() : false;
    }


    public function read($id) {
        $query = "SELECT * FROM User WHERE id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->execute([$id]);

        $user = $stmt->fetchObject('User');
        return $user ?: null;
    }

    public function readAll() {
        $query = "SELECT * FROM User ORDER BY id ASC";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, 'User');
    }

    public function update($entity) {
        if (!$entity instanceof User) {
            throw new InvalidArgumentException('Entity must be an User instance');
        }

        $query = "UPDATE User
                  SET mail = ?, password = ?, idRole = ?, idProfile = ?, isActive = ?
                  WHERE id = ?";
        
        $stmt = $this->connection->prepare($query);
        return $stmt->execute([
            $entity->mail,
            $entity->password,
            $entity->idRole,
            $entity->idProfile,
            $entity->isActive,
            $entity->id
        ]);
    }

    public function delete($id) {
        $query = "DELETE FROM User WHERE id = ?";
        $stmt = $this->connection->prepare($query);
        return $stmt->execute([$id]);
    }

    public function findByEmail($email) {
        try {
            $query = "SELECT * FROM User WHERE mail = ?";
            $stmt = $this->connection->prepare($query);
            $stmt->execute([$email]);

            $user = $stmt->fetchObject('User');
            return $user ?: null;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
}