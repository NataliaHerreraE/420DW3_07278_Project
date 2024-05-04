<?php
declare(strict_types=1);
/**
 * 420DW3_07278_Project UserController.php
 *
 * @author  Natalia Andrea Herrera Espinosa.
 * @since   2024-03-29
 * (c) Copyright 2024 Natalia Herrera.
 */

namespace Project\Controllers;

use Exception;
use Project\DTOs\User;
use Project\Services\LoginService;
use Project\Services\UserService;
use Teacher\GivenCode\Abstracts\AbstractController;
use Teacher\GivenCode\Exceptions\RequestException;
use Teacher\GivenCode\Exceptions\RuntimeException;
use Teacher\GivenCode\Exceptions\ValidationException;
use Teacher\GivenCode\Services\DBConnectionService;
/**
 *
 */
class UserController extends AbstractController {
    private UserService $userService;
    
    public function __construct() {
        parent::__construct();
        $this->userService = new UserService();
    }
    
    /**
     * TODO: Function documentation get
     *
     * @return void
     *
     * @throws RequestException
     * @throws RuntimeException
     *
     * @author Natalia Herrera.
     * @since  2024-03-30
     */
    public function get() : void {
        $this->requireLogin();
        
        if (empty($_REQUEST["user_id"]) || !is_numeric($_REQUEST["user_id"])) {
            throw new RequestException("Bad request: required numeric parameter [user_id] not found in the request.", 400);
        }
        
        $user_id = (int) $_REQUEST["user_id"];
        try {
            $user = $this->userService->getUserById($user_id);
            echo $this->jsonResponse($user->toArray());
        } catch (Exception $e) {
            http_response_code(500);
            echo $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * TODO: Function documentation post
     *
     * @return void
     *
     * @throws RequestException
     *
     * @author Natalia Herrera.
     * @since  2024-03-30
     */
    public function post() : void {
        $this->requireLogin();
        $data = $this->getJsonData();
        
        $created_user = $this->userService->createUser($data['username'], $data['password'], $data['email']);
        echo $this->jsonResponse(['success' => true, 'message' => 'User created successfully', 'userId' => $created_user->getId(), 'user' => $created_user->toArray()]);
        
        /*
        try {
        } catch (ValidationException $ve) {
            http_response_code(400);
            echo $this->jsonResponse(['success' => false, 'message' => $ve->getMessage()]);
        } catch (Exception $e) {
            //http_response_code(500);
            //echo $this->jsonResponse(['success' => false, 'message' => 'Internal server error']);
            throw $e;
        }
        */
    }
    
    
    /**
     * TODO: Function documentation put
     *
     * @return void
     *
     * @throws RequestException
     *
     * @author Natalia Herrera.
     * @since  2024-03-30
     */
    public function put() : void {
        $this->requireLogin();
        $data = $this->getJsonData();
        
        $this->validateUserData($data);
        /* $updated_user = $this->userService->updateUser($data['user_id'], $data['username'], $data['password'], $data['email']);
         echo $this->jsonResponse(['message' => 'User updated successfully', 'userId' => $updated_user->getId(), 'user' => $updated_user->toArray()]);
         */
        
        if (!isset($data['user_id']) || !is_numeric($data['user_id'])) {
            throw new ValidationException("User ID is required and must be an integer.");
        }
        
        $this->userService->updateUser((int) $data['user_id'], $data['username'], $data['password'], $data['email']);
        
    }
    
    
    /**
     * TODO: Function documentation delete
     *
     * @return void
     *
     * @throws RequestException
     *
     * @author Natalia Herrera.
     * @since  2024-03-30
     */
    public function delete() : void {
        $this->requireLogin();
        $data = $this->getJsonData();
        
        if (!isset($data['user_id']) || !is_numeric($data['user_id'])) {
            throw new RequestException("Bad request: User ID is missing or invalid.", 400);
        }
        
        $this->userService->deleteUser((int) $data['user_id']);
        echo $this->jsonResponse(['message' => 'User deleted successfully'], 204);
    }
    
    /**
     * TODO: Function documentation getUserIds
     *
     * @return void
     *
     * @throws Exception
     * @author Natalia Herrera.
     * @since  2024-05-03
     */
    public function getUserIds() : void {
        header('Content-Type: application/json');
        $ids = $this->userService->getAllUserIds();
        echo json_encode($ids);
    }
    
    private function requireLogin() : void {
        if (!LoginService::isLoggedIn()) {
            throw new RequestException("Not authorized.", 401);
        }
    }
    
    private function getJsonData() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            throw new RequestException("Invalid JSON data received.", 400);
        }
        return $data;
    }
    
    private function validateUserData($data) : void {
        // Check required fields
        if (empty($data['username']) || empty($data['password']) || empty($data['email'])) {
            throw new ValidationException('Username, password, and email are required.');
        }
        if (!ctype_alnum($data['username'])) {
            throw new ValidationException('Username must contain only alphanumeric characters.');
        }
        // Validate username length
        if ((strlen($data['username']) < 3) || (strlen($data['username']) > 30)) {
            throw new ValidationException('Username must be between 3 and 30 characters.');
        }
        // Validate password length
        if (strlen($data['password']) < 6) {
            throw new ValidationException('Password must be at least 6 characters.');
        }
        // Validate email format
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new ValidationException('Invalid email format.');
        }
    }
    
    private function jsonResponse($data, $statusCode = 200) : void {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
    }
    
    /***
     * TODO: Function documentation addUserToGroup
     *
     * @return void
     *
     * @throws RequestException
     * @throws RuntimeException
     *
     * @author Natalia Herrera.
     * @since  2024-04-11
     */
    public function addUserToGroup() : void {
        $this->requireLogin(); // Ensures the user is logged in.
        
        $data = $this->getJsonData(); // Get JSON data from the request.
        
        if (empty($data['userId']) || empty($data['groupId']) || !is_numeric($data['userId']) || !is_numeric($data['groupId'])) {
            throw new RequestException("User ID and Group ID are required and must be numeric.", 400);
        }
        
        try {
            $this->userService->addUserToGroup((int) $data['userId'], (int) $data['groupId']);
            echo $this->jsonResponse(['message' => 'User added to group successfully']);
        } catch (Exception $exception) {
            http_response_code(500);
            echo $this->jsonResponse(['error' => $exception->getMessage()], 500);
        }
    }
    
    /***
     * TODO: Function documentation removeUserFromGroup
     *
     * @return void
     *
     * @throws RequestException
     * @throws RuntimeException
     *
     * @author Natalia Herrera.
     * @since  2024-04-11
     */
    public function removeUserFromGroup() : void {
        $this->requireLogin(); // Ensures the user is logged in.
        
        $data = $this->getJsonData(); // Get JSON data from the request.
        
        if (empty($data['userId']) || empty($data['groupId']) || !is_numeric($data['userId']) || !is_numeric($data['groupId'])) {
            throw new RequestException("User ID and Group ID are required and must be numeric.", 400);
        }
        
        try {
            $this->userService->removeUserFromGroup((int) $data['userId'], (int) $data['groupId']);
            echo $this->jsonResponse(['message' => 'User removed from group successfully']);
        } catch (Exception $exception) {
            http_response_code(500);
            echo $this->jsonResponse(['error' => $exception->getMessage()], 500);
        }
    }
    
    /***
     * TODO: Function documentation login
     *
     * @return void
     *
     *
     * @author Natalia Herrera.
     * @since  2024-04-11
     */
    public function login() : void {
        $username = $_POST['username'] ?? null;
        $password = $_POST['password'] ?? null;
        
        try {
            $user_id = $this->userService->authenticate($username, $password);
            if ($user_id) {
                $permissions = $this->userService->getUserPermissions($user_id);
                
                // Check if the user has the LOGIN_ALLOWED permission
                if (in_array('LOGIN_ALLOWED', $permissions)) {
                    // Set the session variables
                    $_SESSION['loggedin'] = true;
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['username'] = $username;
                    $_SESSION['permissions'] = $permissions;
                    
                    // Redirect to home page
                    header("location: " . WEB_ROOT_DIR . "home");
                    exit;
                } else {
                    // Authentication succeeded but the user does not have the LOGIN_ALLOWED permission
                    $_SESSION['error'] = 'Access denied. You do not have permission to login.';
                    header("location: " . WEB_ROOT_DIR . "login");
                    
                    exit;
                }
            } else {
                // Authentication failed
                $_SESSION['error'] = 'Invalid username or password.';
                header("location: " . WEB_ROOT_DIR . "login");
                
                exit;
            }
        } catch (Exception $exception) {
            // Handle errors and redirect back to the login page
            $_SESSION['error'] = 'An error occurred during login.';
            header("location: " . WEB_ROOT_DIR . "login");
            exit;
        }
    }
    
    /**
     * TODO: Function documentation getUserNames
     *
     * @return void
     *
     * @author Natalia Herrera.
     * @since  2024-05-03
     */
    public static function getUserNames() : void {
        header('Content-Type: application/json');
        try {
            $userService = new UserService();
            $users = $userService->getAllUsers();
            echo json_encode(['success' => true, 'data' => $users]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    /**
     * TODO: Function documentation getAllUsers
     *
     * @return void
     *
     * @author Natalia Herrera.
     * @since  2024-05-04
     */
    public static function getAllUsers() : void {
        header('Content-Type: application/json');
        try {
            $userService = new UserService();
            $users = $userService->getAllUsers();
            $usersArray = [];
            foreach ($users as $user) {
                if ($user instanceof User) {
                    /*$usersArray[$user->getId()] = $user->toArray();*/
                    $usersArray[] = $user->toArray();
                    
                }
            }
            echo json_encode(['success' => true, 'data' => $usersArray]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    /**
     * TODO: Function documentation getDeletedUsers
     *
     * @return void
     *
     * @author Natalia Herrera.
     * @since  2024-05-04
     */
    public function getDeletedUsers() : void {
        header('Content-Type: application/json');
        try {
            $users = $this->userService->getDeletedUsers();
            echo json_encode(['success' => true, 'data' => $users]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    
}