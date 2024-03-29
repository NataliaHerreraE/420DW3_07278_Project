<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project ApplicationExample.php
 * 
 * @author Marc-Eric Boury (MEbou)
 * @since 2024-03-14
 * (c) Copyright 2024 Marc-Eric Boury 
 */

namespace Teacher\Examples;

use Teacher\GivenCode\Exceptions\RequestException;
use Teacher\GivenCode\Services\InternalRouter;

/**
 * TODO: Class documentation
 *
 * @author Marc-Eric Boury
 * @since  2024-03-14
 */
class ApplicationExample {
    private InternalRouter $router;
    
    public function __construct() {
        $this->router = new InternalRouter();
    }
    
    /**
     * TODO: Function documentation
     *
     * @return void
     *
     * @author Marc-Eric Boury
     * @since  2024-03-16
     */
    public function run() : void {
        try {
            $this->router->route();
        } catch (RequestException $request_exep) {
            foreach ($request_exep->getHttpHeaders() as $header_name => $header_value) {
                header($header_name . ": " . $header_value);
            }
            \Debug::logException($request_exep);
            http_response_code($request_exep->getHttpResponseCode());
            die();
        } catch (\Exception $other_exception) {
            \Debug::logException($other_exception);
            http_response_code(500);
            die();
        }
    }
}