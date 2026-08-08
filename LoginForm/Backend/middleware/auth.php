<?php
// backend/middleware/auth.php

// Start the session if it hasn't been started already
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure the Response helper is available
require_once __DIR__ . '/../helpers/response.php';

// Check if the user_id session variable exists
if (!isset($_SESSION['user_id'])) {
    // If not logged in, immediately return a 401 Unauthorized status and stop execution
    Response::send(401, false, "Unauthorized access. Please log in.");
}

// If the script reaches this point, the user is authenticated.
// Endpoints that include this middleware can now safely assume $_SESSION['user_id'] is valid.
?>