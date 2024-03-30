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
        return $this->permissionDao->getAll();
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
        return $this->permissionDao->getById($id);
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
        $permission = new Permission();
        $permission->setPermissionKey($permissionKey);
        $permission->setName($name);
        $permission->setDescription($description);
        return $this->permissionDao->create($permission);
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
        $permission = $this->permissionDao->getById($id);
        $permission->setPermissionKey($permissionKey);
        $permission->setName($name);
        $permission->setDescription($description);
        return $this->permissionDao->update($permission);
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
        $this->permissionDao->deleteById($id);
    }
}