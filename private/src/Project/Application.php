<?php
declare(strict_types=1);
/**
 * 420DW3_07278_Project Application.php
 *
 * @author  Natalia Andrea Herrera Espinosa.
 * @since   2024-03-24
 * (c) Copyright 2024 Natalia Herrera.
 */

namespace Project;

use Teacher\GivenCode\Domain\WebpageRoute;
use Teacher\GivenCode\Exceptions\RequestException;
use Teacher\GivenCode\Exceptions\ValidationException;
use Teacher\GivenCode\Services\InternalRouter;

/**
 *
 */
class Application {
    private InternalRouter $router;
    
    /**
     * @throws ValidationException
     */
    public function __construct() {
        $this->router = new InternalRouter();
        
    }
    
    /**
     * TODO: Function documentation run
     *
     * @return void
     *
     * @author Natalia Herrera.
     * @since  2024-03-29
     */
    public function run() : void {
        try {
            $this->router->route();
        } catch (RequestException $request_exception) {
            // Iterate through and set HTTP headers provided by the exception
            foreach ($request_exception->getHttpHeaders() as $header_name => $header_value) {
                header("$header_name: $header_value");
            }
            // Log the exception - ensure project has a Debug
            \Debug::logException($request_exception);
            // Respond with the HTTP status code associated with the exception
            http_response_code($request_exception->getHttpResponseCode());
            \Debug::outputException($request_exception);
            /*
            die($request_exception->getMessage()); //  return the error message
            */
        } catch (\Exception $other_exception) {
            // Log unexpected exceptions for debugging purposes
            \Debug::logException($other_exception);
            \Debug::outputException($other_exception);
            /*
            // Respond with a generic 500 Internal Server Error
            http_response_code(500);
            die('An unexpected error occurred.'); // error message for the client
            */
        }
    }
    
    /**
     * TODO: Function documentation getRouter
     *
     * @return InternalRouter
     *
     * @author Natalia Herrera.
     * @since  2024-04-14
     */
    public function getRouter() : InternalRouter {
        return $this->router;
    }
    
}