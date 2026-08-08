<?php
// backend/helpers/response.php

class Response {
    /**
     * Send a standardized JSON response and terminate script execution.
     *
     * @param int $statusCode HTTP Status Code (e.g., 200, 400, 500)
     * @param bool $success Indicates if the operation was successful
     * @param string $message A descriptive message for the frontend
     * @param array $data Optional additional data to send back
     */
    public static function send($statusCode, $success, $message, $data = []) {
        // Clear any previous output to prevent breaking JSON format
        if (ob_get_length()) ob_clean();

        // Set the appropriate headers
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=UTF-8');
        
        $response = [
            "success" => $success,
            "message" => $message
        ];

        // Only append the data array if it contains something
        if (!empty($data)) {
            $response["data"] = $data;
        }

        echo json_encode($response);
        exit(); // Stop further execution
    }
}
?>