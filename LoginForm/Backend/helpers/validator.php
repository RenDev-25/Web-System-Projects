<?php
// backend/helpers/validator.php

class Validator {
    
    public static function sanitize($input) {
        if (is_null($input)) return '';
        
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
        return $input;
    }

  
    public static function isEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

   
    public static function isValidPassword($password) {
        return strlen($password) >= 6;
    }

    
    public static function passwordsMatch($password, $confirm_password) {
        return $password === $confirm_password;
    }
}
?>