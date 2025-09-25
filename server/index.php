<?php

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (strpos($uri, '/api') === 0) {
    $_SERVER['PATH_INFO'] = str_replace('/api', '', $uri);
    require_once __DIR__ . '/routes/api.php';
} else {
    header('Content-Type: application/json');
    echo json_encode([
        "name" => "EventX API",
        "version" => "1.0.0",
        "status" => "running",
        "endpoints" => [
            "GET /api/events" => [
                "description" => "Get all events",
                "input" => [
                    "query_params" => "none",
                    "body" => "none"
                ],
                "output" => [
                    "success" => "boolean",
                    "message" => "string", 
                    "data" => "array of Event objects"
                ]
            ],
            "GET /api/events/{id}" => [
                "description" => "Get event by ID",
                "input" => [
                    "path_params" => ["id" => "integer"],
                    "body" => "none"
                ],
                "output" => [
                    "success" => "boolean",
                    "message" => "string",
                    "data" => "Event object or null"
                ]
            ],
            "POST /api/events" => [
                "description" => "Create new event",
                "input" => [
                    "body" => [
                        "name" => "string (required)",
                        "description" => "string (optional)",
                        "startDate" => "datetime (required)",
                        "endDate" => "datetime (optional)",
                        "ownerId" => "integer (required)"
                    ]
                ],
                "output" => [
                    "success" => "boolean",
                    "message" => "string",
                    "data" => ["id" => "integer"],
                    "errors" => "array (on validation failure)"
                ]
            ],
            "PUT /api/events/{id}" => [
                "description" => "Update event",
                "input" => [
                    "path_params" => ["id" => "integer"],
                    "body" => [
                        "name" => "string (optional)",
                        "description" => "string (optional)",
                        "startDate" => "datetime (optional)",
                        "endDate" => "datetime (optional)",
                        "ownerId" => "integer (optional)"
                    ]
                ],
                "output" => [
                    "success" => "boolean",
                    "message" => "string",
                    "data" => "null",
                    "errors" => "array (on failure)"
                ]
            ],
            "DELETE /api/events/{id}" => [
                "description" => "Delete event",
                "input" => [
                    "path_params" => ["id" => "integer"],
                    "body" => "none"
                ],
                "output" => [
                    "success" => "boolean",
                    "message" => "string",
                    "data" => "null"
                ]
            ],
            "POST /api/auth/login" => [
                "description" => "User login",
                "input" => [
                    "body" => [
                        "email" => "string (required)",
                        "password" => "string (required)"
                    ]
                ],
                "output" => [
                    "success" => "boolean",
                    "message" => "string",
                    "data" => ["token" => "string", "user" => "User object"],
                    "errors" => "array (on failure)"
                ]
            ],
            "POST /api/auth/register" => [
                "description" => "User registration",
                "input" => [
                    "body" => [
                        "email" => "string (required)",
                        "password" => "string (required)",
                        "firstname" => "string (required)",
                        "lastname" => "string (required)"
                    ]
                ],
                "output" => [
                    "success" => "boolean",
                    "message" => "string",
                    "data" => ["id" => "integer"],
                    "errors" => "array (on validation failure)"
                ]
            ],
            "GET /api/users" => [
                "description" => "Get all users",
                "input" => [
                    "body" => "none"
                ],
                "output" => [
                    "success" => "boolean",
                    "message" => "string",
                    "data" => "array of User objects"
                ]
            ],
            "GET /api/users/{id}" => [
                "description" => "Get user by ID",
                "input" => [
                    "path_params" => ["id" => "integer"],
                    "body" => "none"
                ],
                "output" => [
                    "success" => "boolean",
                    "message" => "string",
                    "data" => "User object or null"
                ]
            ],
            "GET /api/users/me" => [
                "description" => "Get current user info",
                "input" => [
                    "headers" => ["Authorization" => "Bearer token (required)"],
                    "body" => "none"
                ],
                "output" => [
                    "success" => "boolean",
                    "message" => "string",
                    "data" => "User object"
                ]
            ],
            "GET /api/users/{id}/events" => [
                "description" => "Get events registered for user ID",
                "input" => [
                    "path_params" => ["id" => "integer"],
                    "body" => "none"
                ],
                "output" => [
                    "success" => "boolean",
                    "message" => "string",
                    "data" => "array of Event objects"
                ]
            ],
            "POST /api/events/{id}/register" => [
                "description" => "Register for event",
                "input" => [
                    "path_params" => ["id" => "integer"],
                    "body" => [
                        "userId" => "integer (required)"
                    ]
                ],
                "output" => [
                    "success" => "boolean",
                    "message" => "string",
                    "data" => ["registrationId" => "integer"]
                ]
            ],
            "POST /api/events/{id}/unregister" => [
                "description" => "Unregister from event",
                "input" => [
                    "path_params" => ["id" => "integer"],
                    "body" => [
                        "userId" => "integer (required)"
                    ]
                ],
                "output" => [
                    "success" => "boolean",
                    "message" => "string",
                    "data" => "null"
                ]
            ]
        ]
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
}
?>