<?php
// backend/api/register.php

// 1. Set required headers for API communication
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

// 2. Include dependencies
require_once '../config/database.php';
require_once '../helpers/validator.php';
require_once '../helpers/response.php';

// 3. Reject any request that isn't a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Response::send(405, false, "Method Not Allowed. Please use POST.");
}

// 4. Read the incoming JSON payload from the Fetch API
$data = json_decode(file_get_contents("php://input"));

// 5. Check if all required fields are present
if (empty($data->fullname) || empty($data->email) || empty($data->password) || empty($data->confirm_password)) {
    Response::send(400, false, "Please fill in all required fields.");
}

// 6. Sanitize inputs (Do NOT sanitize passwords, as it can strip valid special characters)
$fullname = Validator::sanitize($data->fullname);
$email = Validator::sanitize($data->email);
$password = $data->password;
$confirm_password = $data->confirm_password;

// 7. Perform Validations using our Helper
if (!Validator::isEmail($email)) {
    Response::send(400, false, "Please enter a valid email address.");
}

if (!Validator::isValidPassword($password)) {
    Response::send(400, false, "Password must be at least 6 characters long.");
}

if (!Validator::passwordsMatch($password, $confirm_password)) {
    Response::send(400, false, "Passwords do not match.");
}

// 8. Database Interaction
try {
    $database = new Database();
    $db = $database->getConnection();

    // Check if the email is already registered
    $check_query = "SELECT id FROM users WHERE email = :email LIMIT 1";
    $stmt = $db->prepare($check_query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Email exists, throw a 409 Conflict status
        Response::send(409, false, "Email is already registered. Please log in.");
    }

    // 9. Hash the password securely
    // password_hash() automatically generates a secure salt and uses bcrypt by default
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 10. Insert the new user into the database
    $insert_query = "INSERT INTO users (fullname, email, password) VALUES (:fullname, :email, :password)";
    $insert_stmt = $db->prepare($insert_query);
    
    $insert_stmt->bindParam(':fullname', $fullname);
    $insert_stmt->bindParam(':email', $email);
    $insert_stmt->bindParam(':password', $hashed_password);

    if ($insert_stmt->execute()) {
        // 201 Created
        Response::send(201, true, "Registration successful! You can now log in.");
    } else {
        // 500 Internal Server Error
        Response::send(500, false, "Registration failed. Please try again later.");
    }

} catch (PDOException $e) {
    // Catch any database-related exceptions to prevent script crashes
    Response::send(500, false, "A database error occurred.");
}
?>