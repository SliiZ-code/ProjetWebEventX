<?php

class Event{
    public $id;
    public $name;
    public $description;
    public $startDate;
    public $endDate;
    public $ownerId;
    public $creationDate;
    public $updateDate;

    public function __construct($data = null) {
        if ($data) {
            $this->name = $data['name'] ?? null;
            $this->description = $data['description'] ?? null;
            $this->startDate = $data['startDate'] ?? null;
            $this->endDate = $data['endDate'] ?? null;
            $this->ownerId = $data['ownerId'] ?? null;
        }
    }
}