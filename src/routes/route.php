<?php

include_once __DIR__ . "/../cors.php"; 

// Helper function to send JSON responses
function sendResponse($statusCode, $data) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
}

// Define constants for HTTP methods
const HTTP_METHOD_POST = 'POST';
const HTTP_METHOD_GET = 'GET';
const HTTP_METHOD_NOT_ALLOWED = 405;
const HTTP_STATUS_INTERNAL_SERVER_ERROR = 500;
const HTTP_STATUS_NOT_FOUND = 404;

// Get request URI and method
$request = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$method = $_SERVER["REQUEST_METHOD"];

// Split the path to get the endpoint
$path = explode("/", trim($request, "/"));
$endpoint = $path[0] ?? '';

// Routing logic
function route($endpoint, $method) {
    switch ($endpoint) {
        case '':
            sendResponse(200, ["message" => "Welcome to the API!"]);
            break;

        case 'register':
            handleRoute('userRegisterRoute.php', $method);
            break;

        case 'login':
            handleRoute('userLoginRoute.php', $method);
            break;

        case 'getgroups':
            handleGetGroupsRoute('getGroupsRoute.php', $method);
            break;

        case 'vote':
            handleRoute('voteRoute.php', $method);
            break;

        default:
            sendResponse(HTTP_STATUS_NOT_FOUND, ["message" => "Route not found"]);
            break;
    }
}

function handleRoute($file, $method) {
    if ($method === HTTP_METHOD_POST) {
        $filePath = __DIR__ . "/$file";

        if (file_exists($filePath)) {
            include_once $filePath;
        } else {
            sendResponse(HTTP_STATUS_INTERNAL_SERVER_ERROR, ["message" => "Service unavailable"]);
        }
    } else {
        sendResponse(HTTP_METHOD_NOT_ALLOWED, ["message" => "Method Not Allowed"]);
    }
}

// Handle the 'getgroups' GET route
function handleGetGroupsRoute($file, $method) {
    if ($method === HTTP_METHOD_GET) {
        $filePath = __DIR__ . "/$file"; 
        
        if (file_exists($filePath)) {
            include_once $filePath;  // Include the file containing the logic for the getgroups endpoint
        } else {
            sendResponse(HTTP_STATUS_INTERNAL_SERVER_ERROR, ["message" => "Service unavailable"]);
        }
    } else {
        sendResponse(HTTP_METHOD_NOT_ALLOWED, ["message" => "Method Not Allowed"]);
    }
}

// Execute routing
route($endpoint, $method);
