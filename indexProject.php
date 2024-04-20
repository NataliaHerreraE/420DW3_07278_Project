<?php
/**
 * 420DW3_07278_Project indexProject.php
 *
 * @author  Natalia Andrea Herrera Espinosa.
 * @since   2024-03-30
 * (c) Copyright 2024 Natalia Herrera.
 */
declare(strict_types=1);

require_once __DIR__ . "/private/helpers/init.php"; //ask if i need to use autoloader

use Project\Application;
use Teacher\GivenCode\Domain\WebpageRoute;
use Teacher\GivenCode\Services\InternalRouter;

// Create the Application instance.
$application = new Application();
Debug::$DEBUG_MODE = false;

// Here you can add your routes to the router if needed
// $application->getRouter()->addRoute(new WebpageRoute('/path', 'Controller@method'));

// Adding CRUD routes for users
try {
    $application->getRouter()->addRoute(new WebpageRoute('/user/create', 'Project\Controllers\UserController@create'));
} catch (\Teacher\GivenCode\Exceptions\ValidationException $e) {
}
try {
    $application->getRouter()->addRoute(new WebpageRoute('/user/read', 'Project\Controllers\UserController@get'));
} catch (\Teacher\GivenCode\Exceptions\ValidationException $e) {
}
try {
    $application->getRouter()->addRoute(new WebpageRoute('/user/update', 'Project\Controllers\UserController@put'));
} catch (\Teacher\GivenCode\Exceptions\ValidationException $e) {
}
try {
    $application->getRouter()->addRoute(new WebpageRoute('/user/delete', 'Project\Controllers\UserController@delete'));
} catch (\Teacher\GivenCode\Exceptions\ValidationException $e) {
}
try {
    $application->getRouter()->addRoute(new WebpageRoute('/user/login', 'Project\Controllers\UserController@login'));
} catch (\Teacher\GivenCode\Exceptions\ValidationException $e) {
}
try {
    $application->getRouter()->addRoute(new WebpageRoute('/user/addToGroup',
                                                         'Project\Controllers\UserController@addUserToGroup'));
} catch (\Teacher\GivenCode\Exceptions\ValidationException $e) {
}
try {
    $application->getRouter()->addRoute(new WebpageRoute('/user/removeFromGroup',
                                                         'Project\Controllers\UserController@removeUserFromGroup'));
} catch (\Teacher\GivenCode\Exceptions\ValidationException $e) {
}


// Run the application.
$application->run();

