<?php
declare(strict_types=1);

/*
 * 420dw3project AbstractRoute.php
 * 
 * @author Marc-Eric Boury (MEbou)
 * @since 2024-03-26
 * (c) Copyright 2024 Marc-Eric Boury 
 */

namespace Teacher\GivenCode\Domain;

/**
 * TODO: Class documentation
 *
 * @author Marc-Eric Boury
 * @since  2024-03-26
 */
abstract class AbstractRoute {
    
    private string $routePath;
    private mixed $path;
    
    /**
     * @param string $uri
     */
    public function __construct(string $uri) {
        $this->setRoutePath($uri);
    }
    
    
    /**
     * TODO: documentation
     *
     * @return string
     */
    public function getRoutePath() : string {
        return $this->routePath;
    }
    
    /**
     * TODO: Function documentation
     *
     * @param string $route_path
     * @return void
     *
     * @author Marc-Eric Boury
     * @since  2024-03-26
     */
    protected function setRoutePath(string $route_path) : void {
        if (!str_contains($route_path, PRJ_ROOT_DIRNAME)) {
            $route_path = WEB_DIRECTORY_SEPARATOR . PRJ_ROOT_DIRNAME . $route_path;
        }
        $this->routePath = $route_path;
    }
    
    /**
     * Executes the code associated with the current route.
     *
     * @return void
     *
     * @author Marc-Eric Boury
     * @since  2024-03-26
     */
    abstract public function route() : void;
    
}