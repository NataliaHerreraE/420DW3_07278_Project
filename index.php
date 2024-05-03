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
use Teacher\GivenCode\Domain\APIRoute;
use Teacher\GivenCode\Domain\CallableRoute;
use Teacher\GivenCode\Domain\WebpageRoute;
use Teacher\GivenCode\Services\InternalRouter;
use Project\Controllers\UserController;


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
$application->getRouter()->addRoute(new CallableRoute("/api/create_user", function () {
    $controller = new \Project\Controllers\UserController();
    $controller->post();
}));

$application->getRouter()->addRoute(new CallableRoute("/api/update_user", function () {
    $controller = new \Project\Controllers\UserController();
    $controller->put();
}));

$application->getRouter()->addRoute(new CallableRoute("/api/delete_user", function () {
    $controller = new \Project\Controllers\UserController();
    $controller->delete();
}));

$application->getRouter()->addRoute(new CallableRoute("/api/search_user", function () {
    $controller = new \Project\Controllers\UserController();
    $controller->get();
}));
*/

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

$application->getRouter()->addRoute(new CallableRoute("/api/get_user_ids", function() {
    $controller = new \Project\Controllers\UserController();
    $controller->getUserIds();
}));

/*
$application->getRouter()->addRoute(new CallableRoute("/api/get_user_names", function() {
    $controller = new \Project\Controllers\UserController();
    $controller->getUserNames();
}));
*/

$application->getRouter()->addRoute(new CallableRoute("/api/get_all_users", [UserController::class, "getAllUsers"]));







$application->getRouter()->addRoute(new WebpageRoute("/userGroupForm", "Project/groupForm.php"));
$application->getRouter()->addRoute(new WebpageRoute("/permissionsForm", "Project/permissionForm.php"));



// Run the application
$application->run();
