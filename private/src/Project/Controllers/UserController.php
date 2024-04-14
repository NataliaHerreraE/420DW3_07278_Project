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
use Project\Services\UserService;
use Teacher\GivenCode\Abstracts\AbstractController;
use Teacher\GivenCode\Exceptions\RequestException;
use Teacher\GivenCode\Exceptions\RuntimeException;
use Teacher\GivenCode\Exceptions\ValidationException;

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
        ob_start();
        if (empty($_REQUEST["user_id"])) {
            throw new RequestException("Bad request: required parameter [user_id] not found in the request.", 400);
        }
        if (!is_numeric($_REQUEST["user_id"])) {
            throw new RequestException("Bad request: parameter [user_id] value [" . $_REQUEST["user_id"] .
                                       "] is not numeric.", 400);
        }
        $user_id = (int) $_REQUEST["user_id"];
        $user = $this->userService->getUserById($user_id);
        header("Content-Type: application/json;charset=UTF-8");
        echo json_encode($user);
        ob_end_flush();
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
        ob_start();
        
        $data = $_REQUEST;
        
        if (!$this->validateUserData($data)) {
            throw new RequestException("Bad request: Missing or invalid fields.", 400);
        }
        
        try {
            $created_user = $this->userService->createUser($data['username'], $data['password'], $data['email']);
        } catch (ValidationException $exception) {
            throw new RequestException("Validation error: " . $exception->getMessage(), 422);
        } catch (RuntimeException $exception) {
            throw new RequestException("Server error: " . $exception->getMessage(), 500);
        }
        
        header("Content-Type: application/json;charset=UTF-8");
        echo json_encode(['message' => 'User created successfully', 'userId' => $created_user->getId()]);
        
        ob_end_flush();
    }
    
    /**
     * TODO: Function documentation put
     * @return void
     *
     * @throws RequestException
     *
     * @author Natalia Herrera.
     * @since  2024-03-30
     */
    public function put() : void {
        $data = $_REQUEST;
        
        if (empty($data['user_id'])) {
            throw new RequestException("User ID is required for updating.", 400);
        }
        
        if (!$this->validateUserData($data)) {
            throw new RequestException("Bad request: Missing or invalid fields.", 400);
        }
        
        try {
            $updated_user = $this->userService->updateUser((int) $data['user_id'], $data['username'], $data['password'],
                                                           $data['email']);
        } catch (ValidationException $exception) {
            throw new RequestException("Validation error: " . $exception->getMessage(), 422);
        } catch (RuntimeException $exception) {
            throw new RequestException("Server error: " . $exception->getMessage(), 500);
        }
        
        header("Content-Type: application/json;charset=UTF-8");
        echo json_encode(['message' => 'User updated successfully', 'userId' => $updated_user->getId()]);
        
        ob_end_flush();
    }
    
    
    /**
     * TODO: Function documentation delete
     * @return void
     *
     * @throws RequestException
     *
     * @author Natalia Herrera.
     * @since  2024-03-30
     */
    public function delete() : void {
        ob_start();
        
        // Retrieve the user ID from theform
        $user_id = $_REQUEST['userId'] ?? null;
        
        if (is_null($user_id) || !is_numeric($user_id)) {
            throw new RequestException("Bad request: User ID is missing or invalid.", 400);
        }
        
        // Convert the user ID to an integer
        $user_id = (int) $user_id;
        
        try {
            // Check if the user exists
            $user = $this->userService->getUserById($user_id);
            if (!$user) {
                throw new RequestException("User not found.", 404);
            }
            
            // hard or soft delete?
            $hard_delete = false; // Set true for hard delete.... otherwise rise the flag? (maybe change it later)
            $this->userService->deleteUser($user_id, $hard_delete);
            
            // If everything went well, send a success response, hopelly it does :)
            header("Content-Type: application/json;charset=UTF-8");
            echo json_encode(['message' => 'User deleted successfully']);
        } catch (RequestException $exception) {
            http_response_code($exception->getHttpResponseCode());
            echo json_encode(['error' => $exception->getMessage()]);
        } catch (RuntimeException $exception) {
            throw new RequestException("Server error: " . $exception->getMessage(), 500);
        }
        
        ob_end_flush();
    }
    
    
    private function validateUserData($data) : array {
        // Check requiered fields
        if (empty($data['username']) || empty($data['password']) || empty($data['email'])) {
            return ['success' => false, 'message' => 'Username, password, and email are required.'];
        }
        if (!ctype_alnum($data['username'])) {
            return ['success' => false, 'message' => 'Username must contain only alphanumeric characters.'];
        }
        
        // Validate username
        if ((strlen($data['username']) < 3) || (strlen($data['username']) > 30)) {
            return ['success' => false, 'message' => 'Username must be between 3 and 30 characters.'];
        }
        
        // Validate password
        if (strlen($data['password']) < 6) {
            return ['success' => false, 'message' => 'Password must be at least 6 characters.'];
        }
        
        // Validate email format
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Invalid email format.'];
        }
        
        // If the user do a good job all checks pass, return success hopefully :)
        return ['success' => true, 'message' => 'Validation successful.'];
    }
    
    /***
     * TODO: Function documentation addUserToGroup
     * @return void
     *
     * @throws RequestException
     * @throws RuntimeException
     *
     * @author Natalia Herrera.
     * @since  2024-04-11
     */
    public function addUserToGroup() : void {
        ob_start();
        $data = $_REQUEST;
        
        if (empty($data['userId']) || empty($data['groupId'])) {
            throw new RequestException("User ID and Group ID are required.", 400);
        }
        
        try {
            $this->userService->addUserToGroup((int) $data['userId'], (int) $data['groupId']);
            header("Content-Type: application/json;charset=UTF-8");
            echo json_encode(['message' => 'User added to group successfully']);
        } catch (Exception $exception) {
            http_response_code(500);
            echo json_encode(['error' => $exception->getMessage()]);
        }
        
        ob_end_flush();
    }
    
    /***
     * TODO: Function documentation removeUserFromGroup
     * @return void
     *
     * @throws RequestException
     * @throws RuntimeException
     *
     * @author Natalia Herrera.
     * @since  2024-04-11
     */
    public function removeUserFromGroup() : void {
        ob_start();
        $data = $_REQUEST;
        
        if (empty($data['userId']) || empty($data['groupId'])) {
            throw new RequestException("User ID and Group ID are required.", 400);
        }
        
        try {
            $this->userService->removeUserFromGroup((int) $data['userId'], (int) $data['groupId']);
            header("Content-Type: application/json;charset=UTF-8");
            echo json_encode(['message' => 'User removed from group successfully']);
        } catch (Exception $exception) {
            http_response_code(500);
            echo json_encode(['error' => $exception->getMessage()]);
        }
        
        ob_end_flush();
    }
    
    /***
     * TODO: Function documentation login
     * @return void
     *
     *
     * @author Natalia Herrera.
     * @since  2024-04-11
     */
    public function login() : void {
        //$username and $password are obtained from the request
        $username = $_REQUEST['username'];
        $password = $_REQUEST['password'];
        
        try {
            $user_id = $this->userService->authenticate($username, $password);
            if ($user_id) {
                $permissions = $this->userService->getUserPermissions($user_id);
                $_SESSION['permissions'] = $permissions;
                echo json_encode(['message' => 'Login successful']);
            } else {
                throw new RequestException("Authentication failed.", 401);
            }
        } catch (Exception $exception) {
            http_response_code($exception->getCode() ?: 500);
            echo json_encode(['error' => $exception->getMessage()]);
        }
    }
    
    
}