<?php

class User{
    public $id;
    public $mail;
    public $password;
    public $idRole;
    public $idProfile;
    public $creationDate;
    public $updateDate;
    public $isActive;

    public function __construct($data = null) {
        if ($data) {
            $this->mail = $data['mail'] ?? null;
            $this->password = $data['password'] ?? null;
            $this->idRole = $data['idRole'] ?? null;
            $this->idProfile = $data['idProfile'] ?? null;
            $this->isActive = $data['isActive'] ?? true;
        }
    }
}