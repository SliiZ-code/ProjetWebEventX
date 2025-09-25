<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../services/UserService.php';

class UserController extends Controller {
    private $userService;
    
    public function __construct() {
        $this->userService = new UserService();
    }

    public function getAllUsers() {
        try {
            $users = $this->userService->getAllUsers();
            return $this->successResponse($users, 'Users retrieved successfully');
        } catch (Exception $e) {
            return $this->errorResponse('Failed to retrieve users', 500);
        }
    }

    public function getUser($id) {
        try {
            $user = $this->userService->getUserById($id);
            return $this->successResponse($user, 'User retrieved successfully');
        } catch (InvalidArgumentException $e) {
            return $this->errorResponse($e->getMessage(), 400);
        } catch (Exception $e) {
            return $this->errorResponse('User not found', 404);
        }
    }

    public function createUser() {
        try {
            $data = $this->getRequestData();
            
            $userId = $this->userService->createUser($data);
            
            return $this->successResponse(
                ['id' => $userId], 
                'User created successfully'
            );
            
        } catch (InvalidArgumentException $e) {
            return $this->errorResponse($e->getMessage(), 400);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to create user', 500);
        }
    }

    public function login() {
        try {
            $data = $this->getRequestData();
            $userId = $this->userService->login($data['mail'], $data['password']);
            return $this->successResponse(['userId' => $userId], 'Login successful');
        } catch (InvalidArgumentException $e) {
            return $this->errorResponse($e->getMessage(), 400);
        } catch (Exception $e) {
            return $this->errorResponse('Login failed', 401);
        }
    }

    public function register(){
        try {
            $data = $this->getRequestData();
            $userId = $this->userService->register($data['mail'], $data['password']);
            return $this->successResponse(['userId' => $userId], 'Registration successful');
        } catch (InvalidArgumentException $e) {
            return $this->errorResponse($e->getMessage(), 400);
        } catch (Exception $e) {
            return $this->errorResponse('Registration failed', 500);
        }
    }

}