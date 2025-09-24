<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/user.php';

$db = new Database();
$conn = $db->getConnection();

$method = $_SERVER['REQUEST_METHOD']; POST
$request = explode('/', trim($_SERVER['PATH_INFO'] ?? '', '/')); mail,passwo
$ressource = $request[0] ?? '';

try {
    switch($ressource){
        case 'user':
            $userControler = new UserController($conn);

            
    }
}

?>