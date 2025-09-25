<?php

abstract class Controller {
    
    protected $dataAccess;

    public function __construct($dataAccess) {
        $this->dataAccess = $dataAccess;
    }

    protected function jsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        return json_encode($data);
    }
    
    protected function successResponse($data, $message = 'Success') {
        return $this->jsonResponse([
            'success' => true,
            'message' => $message,
            'data' => $data
        ]);
    }
    
    protected function errorResponse($message, $statusCode = 400, $errors = []) {
        return $this->jsonResponse([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], $statusCode);
    }
    
    protected function getRequestData() {
        $input = file_get_contents('php://input');
        return json_decode($input, true) ?? [];
    }
}
?>