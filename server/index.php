<?php
// server/index.php - Vérifiez le routing

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (strpos($uri, '/api') === 0) {
    // API routes
    $_SERVER['PATH_INFO'] = str_replace('/api', '', $uri);
    require_once __DIR__ . '/routes/api.php';
} else {
    // Documentation
    header('Content-Type: application/json');
    echo json_encode([
        "name" => "EventX API",
        "version" => "1.0.0",
        "status" => "running",
        "endpoints" => [
            "GET /api/events" => "Get all events",
            "GET /api/events/{id}" => "Get event by ID",
            "POST /api/events" => "Create new event",
            "PUT /api/events/{id}" => "Update event",
            "DELETE /api/events/{id}" => "Delete event"
        ]
    ]);
}
?>