<?php

class Registration{
    public $userId;
    public $eventId;
    public $creationDate;
    public $updateDate;

    public function __construct($data = null) {
        if ($data) {
            $this->userId = $data['userId'] ?? null;
            $this->eventId = $data['eventId'] ?? null;
        }
    }
}