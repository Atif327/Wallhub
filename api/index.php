<?php
// Minimal API bootstrap: CORS, JSON responses, and a tiny router
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = trim($uri, "/");

$method = $_SERVER['REQUEST_METHOD'];

// Simple routing: GET /hello
if ($uri === 'api/hello' || $uri === 'hello') {
    echo json_encode(["status" => "ok", "message" => "Hello from api/index.php", "method" => $method]);
    exit;
}

// Default response
http_response_code(404);
echo json_encode(["error" => "Not Found", "requested" => $uri]);
