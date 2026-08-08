<?php
// backend/api/logout.php

// 1. Set headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

// 2. Start the session so we can access and destroy it
session_start();

// 3. Include the response helper
require_once '../helpers/response.php';

// 4. Unset all of the session variables
$_SESSION = array();

// 5. If it's desired to kill the session completely, also delete the session cookie.
// This will destroy the session, and not just the session data!
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 6. Finally, destroy the session
session_destroy();

// 7. Send the success response
Response::send(200, true, "Successfully logged out.");
?>