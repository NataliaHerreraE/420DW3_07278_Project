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

// Routes for the User, UserGroup, and Permissions forms
$application->getRouter()->addRoute(new WebpageRoute("/userForm", "Project/userForm.php"));
$application->getRouter()->addRoute(new WebpageRoute("/userGroupForm", "Project/groupForm.php"));
$application->getRouter()->addRoute(new WebpageRoute("/permissionsForm", "Project/permissionForm.php"));



// Run the application
$application->run();
