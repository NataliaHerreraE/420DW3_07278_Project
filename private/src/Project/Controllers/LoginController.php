<?php
/*
* LoginController.php
* ProjectNatalia
* (c) 2024 Marc-Eric Boury All rights reserved
*/

namespace Project\Controllers;

use Exception;
use Project\Services\UserService;

class LoginController {
    
    public static function doLogin() : void {
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $userService = new UserService();
            
            try {
                $user_id = $userService->authenticate($username, $password);
                if ($user_id) {
                    // Set session variables
                    $_SESSION['loggedin'] = true;
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['username'] = $username;
                    // Retrieve permissions and set in session if needed
                    $_SESSION['permissions'] = $userService->getUserPermissions($user_id);
                    
                    // Redirect to the home page with a GET request
                    header("Location: " . WEB_ROOT_DIR . "home");
                    exit;
                } else {
                    // Authentication failed, prepare error message for the user
                    $error = 'Invalid username or password.';
                }
            } catch (Exception $e) {
                // Handle errors, prepare error message for the user
                $error = 'An error occurred during login.';
            }
            
        }
    }
    
}