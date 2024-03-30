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

use Project\DAOs\UserDao;
use Project\DTOs\User;
use Teacher\GivenCode\Abstracts\IService;
use Teacher\GivenCode\Exceptions\RuntimeException;
use Teacher\GivenCode\Exceptions\ValidationException;

/**
 *
 */
class UserService implements IService {
    private UserDAO $userDao;
    
    public function __construct() {
        $this->userDao = new UserDAO();
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

}