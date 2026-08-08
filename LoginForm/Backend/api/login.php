        <?php
     
        session_start();

        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST");

    
        require_once '../config/database.php';
        require_once '../helpers/validator.php';
        require_once '../helpers/response.php';

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Response::send(405, false, "Method Not Allowed.");
        }

        $data = json_decode(file_get_contents("php://input"));

        if (empty($data->email) || empty($data->password)) {
            Response::send(400, false, "Please provide both email and password.");
        }

        $email = Validator::sanitize($data->email);
        $password = $data->password;

        if (!Validator::isEmail($email)) {
            Response::send(400, false, "Invalid email format.");
        }

        try {
            $database = new Database();
            $db = $database->getConnection();

            // 5. Fetch the user from the database
            $query = "SELECT id, fullname, password FROM users WHERE email = :email LIMIT 1";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                // User not found (We use a generic message for security to prevent email enumeration)
                Response::send(401, false, "Invalid email or password.");
            }

            $user = $stmt->fetch();

            // 6. Verify the password against the stored hash
            if (password_verify($password, $user['password'])) {
                
                // 7. Security: Regenerate session ID to prevent session fixation attacks
                session_regenerate_id(true);
                
                // 8. Store user data in the session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['fullname'] = $user['fullname'];
                
                // 9. Return success response
                Response::send(200, true, "Login successful!", [
                    "fullname" => $user['fullname']
                ]);
                
            } else {
                // Password did not match
                Response::send(401, false, "Invalid email or password.");
            }

        } catch (PDOException $e) {
            Response::send(500, false, "A database error occurred.");
        }

        ?>