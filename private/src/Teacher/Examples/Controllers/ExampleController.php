<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project ExampleController.php
 * 
 * @author Marc-Eric Boury (MEbou)
 * @since 2024-03-16
 * (c) Copyright 2024 Marc-Eric Boury 
 */

namespace Teacher\Examples\Controllers;

use Teacher\Examples\DAOs\ExampleDAO;
use Teacher\Examples\Services\ExampleService;
use Teacher\GivenCode\Abstracts\AbstractController;
use Teacher\GivenCode\Exceptions\RequestException;

/**
 * TODO: Class documentation
 *
 * @author Marc-Eric Boury
 * @since  2024-03-16
 */
class ExampleController extends AbstractController {
    private ExampleService $exampleService;
    
    public function __construct() {
        parent::__construct();
        $this->exampleService = new ExampleService();
    }
    
    public function get() : void {
        ob_start();
        if (empty($_REQUEST["exampleId"])) {
            throw new RequestException("Bad request: required parameter [exampleID] not found in the request.", 400);
        }
        if (!is_numeric($_REQUEST["exampleId"])) {
            throw new RequestException("Bad request: parameter [exampleID] value [" . $_REQUEST["exampleId"] .
                                       "] is not numeric.", 400);
        }
        $int_id = (int) $_REQUEST["exampleId"];
        $instance = $this->exampleService->getById($int_id);
        header("Content-Type: application/json;charset=UTF-8");
        echo $instance->toJson();
        ob_end_flush();
    }
    
    /**
     * TODO: Function documentation post
     *
     * @return void
     *
     * @author Natalia Herrera.
     * @since  2024-03-30
     */
    public function post() : void {
        // TODO: HTTP POST handling
    }
    
    public function put() : void {
        // TODO: HTTP PUT handling
    }
    
    public function delete() : void {
        // TODO: HTTP DELETE handling
    }
}