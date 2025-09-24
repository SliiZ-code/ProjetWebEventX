<?php

require_once 'model.php';

class Event extends Model {

    public function getOne($id) {
        $stmt = $this->connection->prepare("SELECT * FROM Event WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll() {
        $stmt = $this->connection->query("SELECT * FROM Event ORDER BY startDate ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->connection->prepare("INSERT INTO Event (name, description, startDate, endDate, ownerId, creationDate, updateDate) VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
        
        return $stmt->execute([
            $data['name'],
            $data['description'] ?? '',
            $data['startDate'],
            $data['endDate'] ?? $data['startDate'],
            $data['ownerId']
        ]) ? $this->connection->lastInsertId() : false;
    }

    public function update($id, $data) {
        $stmt = $this->connection->prepare("UPDATE Event SET name=?, description=?, startDate=?, endDate=?, updateDate=NOW() WHERE id=?");
        
        return $stmt->execute([
            $data['name'],
            $data['description'] ?? '',
            $data['startDate'],
            $data['endDate'] ?? $data['startDate'],
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->connection->prepare("DELETE FROM Event WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

?>