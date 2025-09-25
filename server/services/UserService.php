<?php

require_once __DIR__ . '/../dataAccess/UserDataAccess.php';
require_once __DIR__ . '/../models/User.php';

class UserService {
    private $userDataAccess;
    
    public function __construct() {
        $this->userDataAccess = new UserDataAccess();
    }

    public function getAllUsers() {
        try {
            return $this->userDataAccess->readAll();
        } catch (Exception $e) {
            throw new Exception('Failed to retrieve users: ' . $e->getMessage());
        }
    }

    public function getUserById($id) {
        if (!is_numeric($id) || $id <= 0) {
            throw new InvalidArgumentException('Invalid user ID');
        }
        
        $user = $this->userDataAccess->read($id);
        
        if (!$user) {
            throw new Exception('User not found');
        }
        
        return $user;
    }

    public function createUser($userData) {
        
        $user = new User($userData);
        
        $userId = $this->userDataAccess->create($user);
        
        if (!$userId) {
            throw new Exception('Failed to create user');
        }
        
        return $userId;
    }

    public function login($mail, $password) {
        if (empty($mail) || empty($password)) {
            throw new InvalidArgumentException('Email and password are required');
        }
        
        $user = $this->userDataAccess->findByEmail($mail);

        if (!$user || $password !== $user->password) {
            throw new Exception('Invalid email or password');
        }

        return $user->id;
    }

    public function register($mail,$password) {
        if (empty($mail) || empty($password)) {
            throw new InvalidArgumentException('Email and password are required');
        }

        if ($this->userDataAccess->findByEmail($mail)) {
            throw new Exception('Email already in use');
        }

        $user = new User(['mail' => $mail, 'password' => $password]);
        
        $userId = $this->userDataAccess->create($user);
        
        if (!$userId) {
            throw new Exception('Failed to register user');
        }
        
        return $userId;
    }

}