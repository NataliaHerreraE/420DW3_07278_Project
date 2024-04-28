<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project InternalRouter.php
 * 
 * @author Marc-Eric Boury (MEbou)
 * @since 2024-03-14
 * (c) Copyright 2024 Marc-Eric Boury 
 */

namespace Teacher\GivenCode\Services;

use Project\Controllers\UserController;
use Teacher\GivenCode\Abstracts\IService;
use Teacher\GivenCode\Domain\AbstractRoute;
use Teacher\GivenCode\Domain\APIRoute;
use Teacher\GivenCode\Domain\CallableRoute;
use Teacher\GivenCode\Domain\RouteCollection;
use Teacher\GivenCode\Domain\WebpageRoute;
use Teacher\GivenCode\Exceptions\RequestException;
use Teacher\GivenCode\Exceptions\ValidationException;

/**
 * TODO: Class documentation
 *
 * @author Marc-Eric Boury
 * @since  2024-03-14
 */
class InternalRouter implements IService {
    
    private string $uriBaseDirectory;
    private RouteCollection $routes;
    
    /**
     * @param string $uri_base_directory
     * @throws ValidationException
     */
    public function __construct(string $uri_base_directory = "/") {
        $this->uriBaseDirectory = $uri_base_directory;
        $this->routes = new RouteCollection();
        
    }
    
    /**
     * @throws ValidationException
     */
    
    
    /**
     * TODO: Function documentation
     *
     * @return void
     * @throws RequestException
     *
     * @author Marc-Eric Boury
     * @since  2024-03-16
     */
    public function route() : void {
        $path = REQUEST_PATH;
        $route = $this->routes->match($path);
        
        if (is_null($route)) {
            throw new RequestException("Route [$path] not found.", 404);
        }
        
        $route->route();
    }
    
    /**
     * Adds an {@see AbstractRoute internal route definition} to the {@see InternalRouter}'s {@see RouteCollection}.
     *
     * @param AbstractRoute $route The route definition to add to the route collection.
     * @return void
     * @throws ValidationException
     *
     * @author Marc-Eric Boury
     * @since  2024-04-12
     */
    public function addRoute(AbstractRoute $route) : void {
        $this->routes->addRoute($route);
    }
}