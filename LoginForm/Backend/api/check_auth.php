<?php
// backend/api/check_auth.php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

// 1. Require the auth gatekeeper
require_once '../middleware/auth.php';

// 2. If the middleware doesn't block the request, we send back the session data
Response::send(200, true, "Authenticated", [
    "fullname" => $_SESSION['fullname'],
    "user_id" => $_SESSION['user_id']
]);
?>