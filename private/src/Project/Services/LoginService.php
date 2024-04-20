<?php
/**
 * 420DW3_07278_Project LoginService.php
 *
 * @author  Natalia Andrea Herrera Espinosa.
 * @since   2024-04-14
 * (c) Copyright 2024 Natalia Herrera.
 */


declare(strict_types=1);

namespace Project\Services;

use JetBrains\PhpStorm\NoReturn;
use Project\DTOs\User;
use Teacher\GivenCode\Abstracts\IService;
use Teacher\GivenCode\Exceptions\RequestException;
use Teacher\GivenCode\Exceptions\RuntimeException;

/**
 *
 */
class LoginService implements IService {
    
    private UserService $userService;
    
    public function __construct() {
        $this->userService = new UserService();
    }
    
    /**
     * TODO: Function documentation isLoggedIn
     *
     * @return bool
     *
     * @author Natalia Herrera.
     * @since  2024-04-14
     */
    public function isLoggedIn() : bool {
        return isset($_SESSION["LOGGED_IN_USER"]);
    }
    
    /**
     * TODO: Function documentation hasPermission
     *
     * @param string $permissionKey
     * @return bool
     *
     * @author Natalia Herrera.
     * @since  2024-04-14
     */
    public function hasPermission(string $permissionKey) : bool {
        return in_array($permissionKey, $_SESSION["permissions"] ?? []);
    }
    
    /**
     * TODO: Function documentation redirectToLogin
     *
     * @return void
     *
     * @author Natalia Herrera.
     * @since  2024-04-14
     */
    #[NoReturn] public function redirectToLogin() : void {
        header("Location: loginPage.php");
        exit();
    }
    
    /**
     * TODO: Function documentation doLogout
     *
     * @return void
     *
     * @author Natalia Herrera.
     * @since  2024-04-14
     */
    public function doLogout() : void {
        unset($_SESSION["LOGGED_IN_USER"], $_SESSION["permissions"]);
        session_destroy();
    }
    
    /**
     * TODO: Function documentation authenticate
     *
     * @param string $username
     * @param string $password
     * @return bool
     * @throws RequestException
     * @throws RuntimeException
     *
     * @author Natalia Herrera.
     * @since  2024-04-14
     */
    public function authenticate(string $username, string $password) : bool {
        $user = $this->userService->getUserByUsername($username);
        if (!$user || !password_verify($password, $user->getPassword())) {
            throw new RequestException("Authentication failed.", 401); // 401 Unauthorized
        }
        
        $_SESSION["LOGGED_IN_USER"] = $user;
        $_SESSION["permissions"] = $this->userService->getUserPermissions($user->getId());
        return true;
    }
    
    /**
     * TODO: Function documentation requireLoggedInUser
     *
     * @return void
     *
     * @author Natalia Herrera.
     * @since  2024-04-14
     */
    public function requireLoggedInUser() : void {
        if (!$this->isLoggedIn()) {
            $this->redirectToLogin();
        }
    }
    
    /**
     * TODO: Function documentation getUser
     *
     * @return User|null
     *
     * @author Natalia Herrera.
     * @since  2024-04-14
     */
    public function getUser() : ?User {
        return $_SESSION["LOGGED_IN_USER"] ?? null;
    }
    
}
