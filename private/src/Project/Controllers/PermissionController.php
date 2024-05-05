<?php
/**
 * 420DW3_07278_Project ${FILE_NAME}
 *
 * @author  Natalia Andrea Herrera Espinosa.
 * @since   2024-03-29
 * (c) Copyright 2024 Natalia Herrera.
 */

namespace Project\Controllers;

use Project\DTOs\Permission;
use Project\Services\LoginService;
use Project\Services\PermissionService;
use Teacher\GivenCode\Abstracts\AbstractController;
use Teacher\GivenCode\Exceptions\RequestException;
use Teacher\GivenCode\Exceptions\RuntimeException;
use Teacher\GivenCode\Exceptions\ValidationException;

/**
 *
 */
class PermissionController extends AbstractController {
    private PermissionService $permissionService;
    
    public function __construct() {
        parent::__construct();
        $this->permissionService = new PermissionService();
    }
    
    /**
     * TODO: Function documentation get
     *
     * @return void
     *
     * @throws RuntimeException
     * @author Natalia Herrera.
     * @since  2024-03-30
     */
    public function get() : void {
        $this->requireLogin();
        
        if (isset($_REQUEST["permission_id"]) && is_numeric($_REQUEST["permission_id"])) {
            $permission_id = (int) $_REQUEST["permission_id"];
            try {
                $permission = $this->permissionService->getPermissionById($permission_id);
                echo $this->jsonResponse($permission->toArray());
            } catch (Exception $e) {
                http_response_code(500);
                echo $this->jsonResponse(['error' => $e->getMessage()], 500);
                
            }
        } else {
            $permissions = $this->permissionService->getAllPermissions();
            echo $this->jsonResponse($permissions);
        }
    }
    
    /**
     * TODO: Function documentation post
     * @return void
     *
     * @throws RequestException
     *
     * @author Natalia Herrera.
     * @since  2024-03-30
     */
    public function post() : void {
        $this->requireLogin();
        $data = $this->getJsonData();
        try{
            $created_permission = $this->permissionService->createPermission($data['permissionKey'], $data['name'],
                                                                             $data['description'] ?? '');
            echo $this->jsonResponse(['success' => true, 'message' => 'Permission created successfully', 'permissionId' => $created_permission->getId(), 'permission' => $created_permission->toArray()]);
            
        } catch (ValidationException $ve) {
            http_response_code(400);
            echo $this->jsonResponse(['success' => false, 'message' => $ve->getMessage()]);
        }
    }
    
    /**
     * TODO: Function documentation put
     * @return void
     *
     * @throws RequestException
     *
     * @author Natalia Herrera.
     * @since  2024-03-30
     */
    public function put() : void {
        $this->requireLogin();
        $data = $this->getJsonData();
        
        $this->validateUserData($data);
        if (!isset($data['permission_id']) || !is_numeric($data['permission_id'])) {
            throw new ValidationException("Permission ID is required and must be an integer.");
        }
        
        $this->permissionService->updatePermission((int) $data['permission_id'], $data['permissionKey'], $data['name'], $data['description'] ?? '');
        echo $this->jsonResponse(['success' => true, 'message' => 'Permission updated successfully']);
        
    }
    
    /**
     * TODO: Function documentation delete
     * @return void
     *
     * @throws RequestException
     *
     * @author Natalia Herrera.
     * @since  2024-03-30
     */
    public function delete() : void {
        $this->requireLogin();
        $data = $this->getJsonData();
        
        if (!isset($data['permission_id']) || !is_numeric($data['permission_id'])) {
            throw new RequestException("Bad request: Permission ID is missing or invalid.", 400);
        }
        
        $this->permissionService->deletePermission((int) $data['permission_id']);
        echo $this->jsonResponse(['message' => 'User deleted successfully'], 204);
        
    }
    
    private function validatePermissionData($data) : bool {
        if (empty($data['permissionKey']) || empty($data['name'])) {
            return false;
        }
        if (!preg_match('/^\w+$/', $data['permissionKey']) || (strlen($data['permissionKey']) > 30)) {
            return false;
        }
        if ((strlen($data['name']) < 3) || (strlen($data['name']) > 30)) {
            return false;
        }
        
        if (isset($data['description']) && (strlen($data['description']) > 70)) {
            return false;
        }
        
        return true;
    }
    
    private function requireLogin() : void {
        if (!LoginService::isLoggedIn()) {
            throw new RequestException("Not authorized.", 401);
        }
    }
    
    private function getJsonData() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            throw new RequestException("Invalid JSON data received.", 400);
        }
        return $data;
    }
    
    private function jsonResponse($data, $statusCode = 200) : void {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
    }
    
    /**
     * TODO: Function documentation getAllPermissions
     *
     * @return void
     *
     * @throws RuntimeException
     *
     * @author Natalia Herrera.
     * @since  2024-05-05
     */
    public static function getAllPermissions() : void {
        header('Content-Type: application/json');
        try {
            $permissionService = new PermissionService();
            $permissions = $permissionService->getAllPermissions();
            $permissionArray = [];
            foreach ($permissions as $permission) {
                if ($permission instanceof Permission) {
                    $permissionArray[] = $permission->toArray();
                }
            }
            echo json_encode(['success' => true, 'data' => $permissionArray]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
}