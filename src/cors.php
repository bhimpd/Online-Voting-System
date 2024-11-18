<?php
// Set allowed origin (replace with the specific origin of your frontend app)
$allowed_origin = "http://localhost:3001";

// CORS Configuration
header("Access-Control-Allow-Origin: $allowed_origin");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // Specify allowed methods as needed
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight request for OPTIONS method
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Send response with allowed headers
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("HTTP/1.1 204 No Content");
    exit;
}
?>
