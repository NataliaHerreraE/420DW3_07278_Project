<?php
declare(strict_types=1);
/**
 * 420DW3_07278_Project PermissionService.php
 *
 * @author  Natalia Andrea Herrera Espinosa.
 * @since   2024-03-24
 * (c) Copyright 2024 Natalia Herrera.
 */


namespace Project\Services;

use Exception;
use PDOException;
use Project\DAOs\PermissionDao;
use Project\DTOs\Permission;
use Teacher\GivenCode\Abstracts\IService;
use Teacher\GivenCode\Exceptions\RuntimeException;
use Teacher\GivenCode\Exceptions\ValidationException;

/**
 *
 */
class PermissionService implements IService {
    private PermissionDAO $permissionDao;
    
    public function __construct() {
        $this->permissionDao = new PermissionDAO();
    }
    
    /**
     * TODO: Function documentation getAllPermissions
     *
     * @return array
     *
     * @throws RuntimeException
     *
     * @author Natalia Herrera.
     * @since  2024-03-29
     */
    public function getAllPermissions() : array {
        try {
            return $this->permissionDao->getAll();
        } catch (PDOException $e) {
            throw new RuntimeException("Database error occurred: " . $e->getMessage());
        } catch (Exception $e) {
            throw new RuntimeException("An unexpected error occurred: " . $e->getMessage());
        }
    }
    
    /**
     * TODO: Function documentation getPermissionById
     *
     * @param int $id
     * @return Permission|null
     * @throws RuntimeException
     *
     * @author Natalia Herrera.
     * @since  2024-03-29
     */
    public function getPermissionById(int $id) : ?Permission {
        try {
            return $this->permissionDao->getById($id);
        } catch (PDOException $e) {
            throw new RuntimeException("Database error occurred: " . $e->getMessage());
        } catch (Exception $e) {
            throw new RuntimeException("An unexpected error occurred: " . $e->getMessage());
        }
    }
    
    /**
     * TODO: Function documentation createPermission
     *
     * @param string $permissionKey
     * @param string $name
     * @param string $description
     * @return Permission
     * @throws RuntimeException
     * @throws ValidationException
     *
     * @author Natalia Herrera.
     * @since  2024-03-29
     */
    public function createPermission(string $permissionKey, string $name, string $description) : Permission {
        try {
            if (empty($permissionKey) || empty($name) || empty($description)) {
                throw new ValidationException("Permission key, name, and description cannot be empty.");
            }
            
            $permission = new Permission();
            $permission->setPermissionKey($permissionKey);
            $permission->setName($name);
            $permission->setDescription($description);
            
            return $this->permissionDao->create($permission);
        } catch (PDOException $e) {
            throw new RuntimeException("Database error occurred: " . $e->getMessage());
        } catch (Exception $e) {
            throw new RuntimeException("An unexpected error occurred: " . $e->getMessage());
        }
    }
    
    /**
     * TODO: Function documentation updatePermission
     *
     * @param int    $id
     * @param string $permissionKey
     * @param string $name
     * @param string $description
     * @return Permission
     * @throws RuntimeException
     * @throws ValidationException
     *
     * @author Natalia Herrera.
     * @since  2024-03-29
     */
    public function updatePermission(int $id, string $permissionKey, string $name, string $description) : Permission {
        try {
            $permission = $this->getPermissionById($id);
            if (!$permission) {
                throw new ValidationException("Permission not found.");
            }
            
            $permission->setPermissionKey($permissionKey);
            $permission->setName($name);
            $permission->setDescription($description);
            
            return $this->permissionDao->update($permission);
        } catch (PDOException $e) {
            throw new RuntimeException("Database error occurred: " . $e->getMessage());
        } catch (Exception $e) {
            throw new RuntimeException("An unexpected error occurred: " . $e->getMessage());
        }
    }
    
    /**
     * TODO: Function documentation deletePermission
     *
     * @param int $id
     * @return void
     * @throws RuntimeException
     *
     * @author Natalia Herrera.
     * @since  2024-03-29
     */
    public function deletePermission(int $id) : void {
        try {
            $this->permissionDao->deleteById($id);
        } catch (PDOException $e) {
            throw new RuntimeException("Database error occurred: " . $e->getMessage());
        } catch (Exception $e) {
            throw new RuntimeException("An unexpected error occurred: " . $e->getMessage());
        }
    }
}