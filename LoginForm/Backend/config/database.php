<?php

class Database {
    // Local XAMPP Credentials
    private $host = "localhost";
    private $db_name = "login_system";
    private $username = "root"; 
    private $password = ""; 
    public $conn;

    // Method to get the database connection
    public function getConnection() {
        $this->conn = null;

        try {
            
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                $this->username,
                $this->password
            );
            
            // Set PDO to throw exceptions on error so we can catch them easily
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Fetch results as an associative array by default
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
        } catch(PDOException $exception) {
            // If connection fails, return a JSON error (useful for our API)
            http_response_code(500);
            echo json_encode([
                "success" => false, 
                "message" => "Database Connection Error"
                // "error" => $exception->getMessage() // Uncomment for local debugging only
            ]);
            exit();
        }

        return $this->conn;
    }
}
?>