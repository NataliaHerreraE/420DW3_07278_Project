<?php
/**
 * 420DW3_07278_Project ${FILE_NAME}
 *
 * @author  Natalia Andrea Herrera Espinosa.
 * @since   2024-03-29
 * (c) Copyright 2024 Natalia Herrera.
 */

namespace Project\Controllers;

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
     * @return void
     *
     * @author Natalia Herrera.
     * @since  2024-03-30
     */
    public function get() : void {
        ob_start();
        
        $permissionId = $_REQUEST['permissionId'] ?? null;
        
        try {
            if ($permissionId) {
                if (!is_numeric($permissionId)) {
                    throw new RequestException("Bad request: parameter [permissionId] value is not numeric.", 400);
                }
                
                $permission = $this->permissionService->getPermissionById((int) $permissionId);
                
                if (!$permission) {
                    throw new RequestException("Permission not found.", 404);
                }
                
                header("Content-Type: application/json;charset=UTF-8");
                echo json_encode($permission);
            } else {
                // No specific ID - return all permissions??
                $permissions = $this->permissionService->getAllPermissions();
                header("Content-Type: application/json;charset=UTF-8");
                echo json_encode($permissions);
            }
        } catch (RequestException $exception) {
            http_response_code($exception->getHttpResponseCode());
            echo json_encode(['error' => $exception->getMessage()]);
        } catch (\Exception $exception) {
            http_response_code(500);
            echo json_encode(['error' => 'Internal Server Error']);
        }
        
        ob_end_flush();
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
        ob_start();
        
        $data = $_REQUEST; // For form data
        
        if (!$this->validatePermissionData($data)) {
            throw new RequestException("Bad request: Missing or invalid fields.", 400);
        }
        
        try {
            $newPermission =
                $this->permissionService->createPermission($data['permissionKey'], $data['name'], $data['description']);
            header("Content-Type: application/json;charset=UTF-8");
            echo json_encode([
                                 'message' => 'Permission created successfully',
                                 'permissionId' => $newPermission->getId()
                             ]);
        } catch (ValidationException $exception) {
            throw new RequestException("Validation error: " . $exception->getMessage(), 422);
        } catch (RuntimeException $exception) {
            throw new RequestException("Server error: " . $exception->getMessage(), 500);
        }
        
        ob_end_flush();
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
        ob_start();
        
        $data = $_REQUEST;
        
        if (empty($data['permissionId'])) {
            throw new RequestException("Permission ID is required for updating.", 400);
        }
        
        $permissionId = (int) $data['permissionId'];
        
        if (!$this->validatePermissionData($data)) {
            throw new RequestException("Bad request: Missing or invalid fields.", 400);
        }
        
        try {
            $updated_permission = $this->permissionService->updatePermission($permissionId, $data['permissionKey'], $data['name'], $data['description'] ?? '');
        } catch (ValidationException $exception) {
            throw new RequestException("Validation error: " . $exception->getMessage(), 422);
        } catch (RuntimeException $exception) {
            throw new RequestException("Server error: " . $exception->getMessage(), 500);
        }
        
        header("Content-Type: application/json;charset=UTF-8");
        echo json_encode([
                             'message' => 'Permission updated successfully',
                             'permissionId' => $updated_permission->getId()
                         ]);
        
        ob_end_flush();
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
        ob_start();
        $permissionId = $_REQUEST['permissionId'] ?? null;
        
        if (is_null($permissionId) || !is_numeric($permissionId)) {
            throw new RequestException("Bad request: Permission ID is missing or invalid.", 400);
        }
        
        $permissionId = (int) $permissionId;
        
        try {
            $permission = $this->permissionService->getPermissionById($permissionId);
            if (!$permission) {
                throw new RequestException("Permission not found.", 404);
            }
            
            $hardDelete = isset($_REQUEST['hardDelete']) && ($_REQUEST['hardDelete'] === 'true');
            
            $this->permissionService->deletePermission($permissionId, $hardDelete);
            
            header("Content-Type: application/json;charset=UTF-8");
            echo json_encode(['message' => 'Permission deleted successfully']);
        } catch (RequestException $exception) {
            http_response_code($exception->getHttpResponseCode());
            echo json_encode(['error' => $exception->getMessage()]);
        } catch (RuntimeException $exception) {
            throw new RequestException("Server error: " . $exception->getMessage(), 500);
        }
        ob_end_flush();
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
    
}