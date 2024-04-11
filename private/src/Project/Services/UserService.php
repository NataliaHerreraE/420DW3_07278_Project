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

use Project\DAOs\PermissionDao;
use Project\DAOs\UserDao;
use Project\DAOs\UserGroupDao;
use Project\DTOs\User;
use Teacher\GivenCode\Abstracts\IService;
use Teacher\GivenCode\Exceptions\RuntimeException;
use Teacher\GivenCode\Exceptions\ValidationException;

/**
 *
 */
class UserService implements IService {
    private UserDAO $userDao;
    private UserGroupDAO $userGroupDao;
    private PermissionDao $permissionDao;
    
    public function __construct() {
        $this->userDao = new UserDAO();
        $this->userGroupDao = new UserGroupDAO();
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
        return $this->userDao->getAll();
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
        return $this->userDao->getById($id);
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
        $user = new User();
        $user->setUsername($username);
        $user->setPassword($password); // Ensure password is hashed
        $user->setEmail($email);
        return $this->userDao->create($user);
    }
    
    /**
     * TODO: Function documentation updateUser
     *
     * @param int    $id
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
    public function updateUser(int $id, string $username, string $password, string $email) : User {
        $user = $this->userDao->getById($id);
        $user->setUsername($username);
        $user->setPassword($password); // Ensure password is hashed
        $user->setEmail($email);
        return $this->userDao->update($user);
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
        $this->userDao->deleteById($id, $hardDelete);
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
        // Call the DAO method to add a user to a group
        $this->userDao->addToGroup($userId, $groupId);
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
        // Call the DAO method to remove a user from a group
        $this->userDao->removeFromGroup($userId, $groupId);
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
    public function getUserGroups(int $userId): array {
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
    public function getUserPermissions(int $userId): array {
        // Get individual user permissions
        $userPermissions = $this->permissionDao->getUserPermissions($userId);
        
        // Get user group permissions
        $groups = $this->getUserGroups($userId);
        $groupPermissions = [];
        foreach ($groups as $group) {
            $groupPermissions = array_merge($groupPermissions,
                                            $this->userGroupDao->getPermissionsByGroupId($group['group_id'])); // Define this method in UserGroupDAO
        }
        
        // Merge individual user permissions with group permissions
        $mergedPermissions = array_unique(array_merge($userPermissions, $groupPermissions));
        return $mergedPermissions;
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
    public function authenticate(string $username, string $password): ?int {
        // Get the user by username
        try {
            $user = $this->userDao->getByUsername($username);
        } catch (ValidationException|RuntimeException $e) {
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
}