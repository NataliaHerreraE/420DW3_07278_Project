<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project index.php
 * 
 * @author Marc-Eric Boury (MEbou)
 * @since 2024-03-14
 * (c) Copyright 2024 Marc-Eric Boury 
 */
require_once 'private/helpers/autoloader.php';
require_once "private/helpers/init.php";

use Project\Application;
use Project\Controllers\PermissionController;
use Project\Controllers\UserGroupController;
use Teacher\GivenCode\Domain\APIRoute;
use Teacher\GivenCode\Domain\CallableRoute;
use Teacher\GivenCode\Domain\WebpageRoute;
use Teacher\GivenCode\Services\InternalRouter;
use Project\Controllers\UserController;

ini_set('display_errors', 1);
error_reporting(E_ALL);

Debug::$DEBUG_MODE = false;
//Debug::$DEBUG_MODE = true;

//error_log('Requested Path: ' . $_SERVER['REQUEST_URI']);

//define('InternalRouter', true);
$application = new Application();
$application->getRouter()->addRoute(new WebpageRoute("/", "Project/login.php"));
$application->getRouter()->addRoute(new WebpageRoute("/index", "Project/login.php"));
$application->getRouter()->addRoute(new WebpageRoute("/login", "Project/login.php"));
$application->getRouter()->addRoute(new WebpageRoute("/home", "Project/home.php"));
$application->getRouter()->addRoute(new CallableRoute("/api/doLogin",
                                                      [\Project\Controllers\LoginController::class, "doLogin"]));
$application->getRouter()->addRoute(new CallableRoute("/api/logout",
                                                      [\Project\Controllers\LoginController::class, 'dologout']));


$application->getRouter()->addRoute(new WebpageRoute("/userForm", "Project/userForm.php"));

/*
 * This single 'APIRoute' route will handle the 4 base operations for users. It will replace your
 * individual callable routes commented above.
 *
 * - get a user by id (URL /api/manage_user with method 'GET' will call function get() of UserController)
 * - create new user (URL /api/manage_user with method 'POST' will call function post() of UserController)
 * - update a user (URL /api/manage_user with method 'PUT' will call function put() of UserController)
 * - delete a user (URL /api/manage_user with method 'DELETE' will call function delete() of UserController)
 */
$application->getRouter()->addRoute(new APIRoute("/api/manage_user", UserController::class));


/*$application->getRouter()->addRoute(new CallableRoute("/api/get_user_ids", function () {
    $controller = new \Project\Controllers\UserController();
    $controller->getUserIds();
}));*/

$application->getRouter()->addRoute(new CallableRoute("/api/get_all_users", [UserController::class, "getAllUsers"]));
$application->getRouter()->addRoute(new CallableRoute("/api/getDeletedUsers",
                                                      [UserController::class, "getDeletedUsers"]));




$application->getRouter()->addRoute(new WebpageRoute("/groupForm", "Project/groupForm.php"));
$application->getRouter()->addRoute(new APIRoute("/api/manage_group", UserGroupController::class));
$application->getRouter()->addRoute(new CallableRoute("/api/get_all_groups", [UserGroupController::class, "getAllGroups"]));
$application->getRouter()->addRoute(new CallableRoute("/api/getDeletedGroups",
                                                      [UserGroupController::class, "getDeletedGroup"]));

$application->getRouter()->addRoute(new WebpageRoute("/permissionForm", "Project/permissionForm.php"));
$application->getRouter()->addRoute(new APIRoute("/api/manage_permission", PermissionController::class));
$application->getRouter()->addRoute(new CallableRoute("/api/get_all_permissions", [PermissionController::class, "getAllPermissions"]));


// Run the application
$application->run();
