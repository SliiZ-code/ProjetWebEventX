<?php

require_once __DIR__ . '/../controllers/EventController.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'] ?? '', '/'));
$resource = $request[0] ?? '';

try {
    switch($resource) {
        case 'events':
            $eventController = new EventController();
            
            switch($method) {
                case 'GET':
                    if(isset($request[1]) && is_numeric($request[1])) {
                        echo $eventController->getEvent($request[1]);
                    } else {
                        echo $eventController->getAllEvents();
                    }
                    break;
                    
                case 'POST':
                    echo $eventController->createEvent();
                    break;
                    
                case 'PUT':
                    if(isset($request[1]) && is_numeric($request[1])) {
                        echo $eventController->updateEvent($request[1]);
                    } else {
                        http_response_code(400);
                        echo json_encode(["success" => false, "message" => "Event ID required"]);
                    }
                    break;
                    
                case 'DELETE':
                    if(isset($request[1]) && is_numeric($request[1])) {
                        echo $eventController->deleteEvent($request[1]);
                    } else {
                        http_response_code(400);
                        echo json_encode(["success" => false, "message" => "Event ID required"]);
                    }
                    break;
                    
                default:
                    http_response_code(405);
                    echo json_encode(["success" => false, "message" => "Method not allowed"]);
            }
            break;
            
        default:
            http_response_code(404);
            echo json_encode(["success" => false, "message" => "Resource not found"]);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false, 
        "message" => "Server error", 
        "error" => $e->getMessage()
    ]);
}