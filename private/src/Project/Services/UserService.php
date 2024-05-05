<?php
declare(strict_types=1);
/**
 * 420DW3_07278_Project UserService.php
 *
 * @author  Natalia Andrea Herrera Espinosa.
 * @since   2024-03-24
 * (c) Copyright 2024 Natalia Herrera.
 */


namespace Project\Services;

use Exception;
use PDO;
use PDOException;
use Project\DAOs\PermissionDao;
use Project\DAOs\UserDao;
use Project\DAOs\UserGroupDao;
use Project\DTOs\User;
use Teacher\GivenCode\Abstracts\IService;
use Teacher\GivenCode\Exceptions\RuntimeException;
use Teacher\GivenCode\Exceptions\ValidationException;
use Teacher\GivenCode\Services\DBConnectionService;

/**
 *
 */
class UserService implements IService {
    private UserDAO $userDao;
    private UserGroupDAO $userGroupDao;
    private PermissionDao $permissionDao;
    
    /**
     * @throws RuntimeException
     */
    public function __construct() {
        $pdo = DBConnectionService::getConnection();
        $this->userDao = new UserDao($pdo);
        $this->userGroupDao = new UserGroupDao($pdo);
        $this->permissionDao = new PermissionDao($pdo);
    }
    
    /**
     * TODO: Function documentation getAllUsers
     *
     * @return array
     *
     * @throws RuntimeException
     * @author Natalia Herrera.
     * @since  2024-03-29
     */
    public function getAllUsers() : array {
        try {
            return $this->userDao->getAll();
        } catch (\Exception $e) {
            throw new RuntimeException("Error fetching all users: " . $e->getMessage());
        }
    }
    
    /**
     * TODO: Function documentation getUserById
     *
     * @param int $id
     * @return User|null
     * @throws RuntimeException
     *
     * @author Natalia Herrera.
     * @since  2024-03-29
     */
    public function getUserById(int $id) : ?User {
        try {
            return $this->userDao->getById($id);
        } catch (\Exception $e) {
            throw new RuntimeException("Error fetching user by ID: " . $e->getMessage());
        }
    }
    
    /**
     * TODO: Function documentation createUser
     *
     * @param string $username
     * @param string $password
     * @param string $email
     * @return User
     * @throws RuntimeException
     * @throws ValidationException
     *
     * @author Natalia Herrera.
     * @since  2024-03-29
     */
    public function createUser(string $username, string $password, string $email) : User {
        try {
            if (empty($username) || empty($password) || empty($email)) {
                throw new ValidationException("Username, password, and email cannot be empty.");
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new ValidationException("Invalid email format.");
            }
            if (strlen($username) < 3 || strlen($username) > 30) {
                throw new ValidationException("Username must be between 3 and 30 characters.");
            }
            if (strlen($password) < 8) {
                throw new ValidationException("Password must be at least 8 characters long.");
            }
            
            // Proceed with user creation
            $user = new User();
            $user->setUsername($username);
            $user->setPassword(password_hash($password, PASSWORD_BCRYPT));
            $user->setEmail($email);
            
            return $this->userDao->create($user);
        } catch (PDOException $e) {
            throw new RuntimeException("Database error occurred. Please try again later.");
        } catch (Exception $e) {
            throw new RuntimeException("Failed to create user.", 0, $e);
        }
        
    }
    
    
    /**
     * TODO: Function documentation updateUser
     *
     * @param int         $id
     * @param string      $username
     * @param string|null $password
     * @param string      $email
     * @return User
     * @throws RuntimeException
     * @throws ValidationException
     * @author Natalia Herrera.
     * @since  2024-03-29
     */
    public function updateUser(int $id, string $username, string $password = null, string $email) : User {
        try {
            echo "updateUser method called\n";
            $user = $this->userDao->getById($id);
            if (!$user) {
                throw new ValidationException("User not found.");
            }
            
            // Validate the inputs
            if (empty($username) || empty($email)) {
                throw new ValidationException("Username and email cannot be empty.");
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new ValidationException("Invalid email format.");
            }
            if (strlen($username) < 3 || strlen($username) > 30) {
                throw new ValidationException("Username must be between 3 and 30 characters.");
            }
            if ($password !== null && strlen($password) < 8) {
                throw new ValidationException("Password must be at least 8 characters long.");
            }
            
            // Update the user data
            $user->setUsername($username);
            if ($password !== null) {
                $user->setPassword(password_hash($password, PASSWORD_BCRYPT));
            }
            $user->setEmail($email);
            
            return $this->userDao->update($user);
        } catch (PDOException $e) {
            throw new RuntimeException("Database error occurred. Please try again later.");
        } catch (Exception $e) {
            throw new RuntimeException("An error occurred. Please try again later.");
        }
    }
    
    
    /**
     * TODO: Function documentation deleteUser
     *
     * @param int  $id
     * @param bool $hardDelete
     * @return void
     * @throws RuntimeException
     *
     * @author Natalia Herrera.
     * @since  2024-03-29
     */
    public function deleteUser(int $id, bool $hardDelete = false) : void {
        try {
            $user = $this->userDao->getById($id);
            if (!$user) {
                throw new ValidationException("User not found.");
            }
            
            $this->userDao->deleteById($id, $hardDelete);
        } catch (PDOException $e) {
            throw new RuntimeException("Database error occurred. Please try again later.");
        } catch (Exception $e) {
            throw new RuntimeException("An error occurred. Please try again later.");
        }
    }
    
    /***
     * TODO: Function documentation addUserToGroup
     *
     * @param int $userId
     * @param int $groupId
     * @return void
     * @throws RuntimeException
     *
     * @author Natalia Herrera.
     * @since  2024-04-11
     */
    public function addUserToGroup(int $userId, int $groupId) : void {
        try {
            // Check existence of user and group
            $user = $this->userDao->getById($userId);
            $group = $this->userGroupDao->getById($groupId);
            if (!$user || !$group) {
                throw new ValidationException("User or Group not found.");
            }
            
            $this->userDao->addToGroup($userId, $groupId);
        } catch (PDOException $e) {
            throw new RuntimeException("Database error occurred. Please try again later.");
        } catch (Exception $e) {
            throw new RuntimeException("An error occurred. Please try again later.");
        }
    }
    
    /***
     * TODO: Function documentation removeUserFromGroup
     *
     * @param int $userId
     * @param int $groupId
     * @return void
     * @throws RuntimeException
     *
     * @author Natalia Herrera.
     * @since  2024-04-11
     */
    public function removeUserFromGroup(int $userId, int $groupId) : void {
        try {
            // Check existence of user and group
            $user = $this->userDao->getById($userId);
            $group = $this->userGroupDao->getById($groupId);
            if (!$user || !$group) {
                throw new ValidationException("User or Group not found.");
            }
            
            $this->userDao->removeFromGroup($userId, $groupId);
        } catch (PDOException $e) {
            throw new RuntimeException("Database error occurred. Please try again later.");
        } catch (Exception $e) {
            throw new RuntimeException("An error occurred. Please try again later.");
        }
    }
    
    /***
     * TODO: Function documentation getUserGroups
     *
     * @param int $userId
     * @return array
     * @throws RuntimeException
     *
     * @author Natalia Herrera.
     * @since  2024-04-11
     */
    public function getUserGroups(int $userId) : array {
        // Call the DAO method to get user groups
        return $this->userDao->getUserGroups($userId);
    }
    
    /***
     * TODO: Function documentation getUserPermissions
     *
     * @param int $userId
     * @return array
     * @throws RuntimeException
     *
     * @author Natalia Herrera.
     * @since  2024-04-11
     */
    public function getUserPermissions(int $userId) : array {
        // Get individual user permissions
        $user_permissions = $this->permissionDao->getUserPermissions($userId);
        
        // Get user group permissions
        $groups = $this->getUserGroups($userId);
        $group_permissions = [];
        foreach ($groups as $group) {
            $group_permissions = array_merge($group_permissions,
                                             $this->userGroupDao->getPermissionsByGroupId($group['group_id']));
        }
        
        // Merge individual user permissions with group permissions
        $merged_permissions = array_unique(array_merge($user_permissions, $group_permissions));
        return $merged_permissions;
    }
    
    /***
     * TODO: Function documentation authenticate
     *
     * @param string $username
     * @param string $password
     * @return int|null
     *
     * @author Natalia Herrera.
     * @since  2024-04-11
     */
    public function authenticate(string $username, string $password) : ?int {
        // Get the user by username
        try {
            $user = $this->userDao->getByUsername($username);
        } catch (ValidationException|RuntimeException $exception) {
            return null;
        }
        if (!$user) {
            // User not found
            return null;
        }
        // Verify the password
        if (password_verify($password, $user->getPassword())) {
            // Password is correct
            return $user->getId();
        }
        // Authentication failed
        return null;
    }
    
    /**
     * TODO: Function documentation getUserByUsername
     *
     * @param string $username
     * @return User|null
     *
     * @author Natalia Herrera.
     * @since  2024-04-14
     */
    public function getUserByUsername(string $username) : ?User {
        try {
            return $this->userDao->getByUsername($username);
        } catch (ValidationException|RuntimeException $e) {
            return null;
        }
    }
    
    
    /**
     * TODO: Function documentation getAllUserIds
     *
     * @return array
     *
     * @throws \Exception
     * @author Natalia Herrera.
     * @since  2024-05-03
     */
    public function getAllUserIds() : array {
        try {
            return $this->userDao->fetchAllUserIds();
        } catch (\Exception $e) {
            error_log('Failed to fetch user IDs: ' . $e->getMessage());
            throw new \Exception("Database error occurred: " . $e->getMessage());
        }
    }
    
    
    /**
     * TODO: Function documentation getDeletedUsers
     *
     * @return array
     *
     * @throws RuntimeException
     * @author Natalia Herrera.
     * @since  2024-05-04
     */
    public function getDeletedUsers() : array {
        $users = $this->userDao->getDeletedUsers();
        error_log('Fetched deleted users: ' . print_r($users, true));
        return array_map(function ($user) {
            return $user->toArray();
        }, $users);
    }
    
}