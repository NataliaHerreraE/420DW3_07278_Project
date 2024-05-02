<?php
/*
* LoginController.php
* ProjectNatalia
* (c) 2024 Marc-Eric Boury All rights reserved
*/

namespace Project\Controllers;

use Exception;
use JetBrains\PhpStorm\NoReturn;
use Project\Services\UserService;

/**
 *
 */
class LoginController {
    
    /**
     * TODO: Function documentation doLogin
     *
     * @return void
     *
     * @author Natalia Herrera.
     * @since  2024-05-01
     */
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
    
    /***
     * TODO: Function documentation logout
     * @return void
     *
     * @author Natalia Herrera.
     * @since  2024-05-01
     */
    public static function dologout() : void {
        // Clear all session data
        $_SESSION = [];
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                      $params["path"], $params["domain"],
                      $params["secure"], $params["httponly"]);
        }
        
        // Destroy the session
        session_destroy();
        
        // Redirect to login page or homepage
        header("Location: " . WEB_ROOT_DIR . "login");
        exit;
    }
    
}