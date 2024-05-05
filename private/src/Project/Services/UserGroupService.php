<?php
declare(strict_types=1);
/**
 * 420DW3_07278_Project UserGroupService.php
 *
 * @author  Natalia Andrea Herrera Espinosa.
 * @since   2024-03-24
 * (c) Copyright 2024 Natalia Herrera.
 */


namespace Project\Services;

use Project\DAOs\UserGroupDao;
use Project\DTOs\UserGroup;
use Teacher\GivenCode\Abstracts\IService;
use Teacher\GivenCode\Exceptions\RuntimeException;
use Teacher\GivenCode\Exceptions\ValidationException;
use Teacher\GivenCode\Services\DBConnectionService;

/**
 *
 */
class UserGroupService implements IService {
    private UserGroupDAO $userGroupDao;
    
    public function __construct() {
        $pdo = DBConnectionService::getConnection();
        $this->userGroupDao = new UserGroupDAO();
    }
    
    /**
     * TODO: Function documentation getAllUserGroups
     *
     * @return array
     *
     * @throws RuntimeException
     *
     * @author Natalia Herrera.
     * @since  2024-03-29
     */
    public function getAllUserGroups() : array {
        try {
            return $this->userGroupDao->getAll();
        } catch (\Exception $e) {
            throw new RuntimeException("Error fetching all user groups: " . $e->getMessage());
        }
    }
    
    /**
     * TODO: Function documentation getUserGroupById
     *
     * @param int $id
     * @return UserGroup|null
     * @throws RuntimeException
     *
     * @author Natalia Herrera.
     * @since  2024-03-29
     */
    public function getUserGroupById(int $id) : ?UserGroup {
        try {
            return $this->userGroupDao->getById($id);
        } catch (\Exception $e) {
            throw new RuntimeException("Error fetching user group by ID: " . $e->getMessage());
        }
    }
    
    /**
     * TODO: Function documentation createUserGroup
     *
     * @param string $group_name
     * @param string $description
     * @return UserGroup
     * @throws RuntimeException
     * @throws ValidationException
     *
     * @author Natalia Herrera.
     * @since  2024-03-29
     */
    public function createUserGroup(string $group_name, string $description) : UserGroup {
        if (empty($group_name) || empty($description)) {
            throw new ValidationException("Group name and description cannot be empty.");
        }
        
        try {
            $user_group = new UserGroup();
            $user_group->setGroupName($group_name);
            $user_group->setDescription($description);
            return $this->userGroupDao->create($user_group);
        } catch (\Exception $e) {
            throw new RuntimeException("Error creating user group: " . $e->getMessage());
        }
    }
    
    /**
     * TODO: Function documentation updateUserGroup
     *
     * @param int    $id
     * @param string $groupName
     * @param string $description
     * @return UserGroup
     * @throws RuntimeException
     * @throws ValidationException
     *
     * @author Natalia Herrera.
     * @since  2024-03-29
     */
    public function updateUserGroup(int $id, string $groupName, string $description) : UserGroup {
        if (empty($groupName) || empty($description)) {
            throw new ValidationException("Group name and description cannot be empty.");
        }
        
        try {
            $user_group = $this->userGroupDao->getById($id);
            $user_group->setGroupName($groupName);
            $user_group->setDescription($description);
            return $this->userGroupDao->update($user_group);
        } catch (\Exception $e) {
            throw new RuntimeException("Error updating user group: " . $e->getMessage());
        }
    }
    
    /**
     * TODO: Function documentation deleteUserGroup
     *
     * @param int  $id
     * @param bool $hardDelete
     * @return void
     * @throws RuntimeException
     *
     * @author Natalia Herrera.
     * @since  2024-03-29
     */
    public function deleteUserGroup(int $id, bool $hardDelete = false) : void {
        try {
            $group = $this->userGroupDao->getById($id);
            if (!$group){
                throw new RuntimeException("User group not found.");
            }
            $this->userGroupDao->deleteById($id, $hardDelete);
        } catch (\Exception $e) {
            throw new RuntimeException("Failed to delete user group: " . $e->getMessage());
        }
    }
    
    /**
     * TODO: Function documentation isGroupNameTaken
     *
     * @param string   $groupName
     * @param int|null $excludeGroupId
     * @return bool
     *
     * @author Natalia Herrera.
     * @since  2024-04-11
     */
    public function isGroupNameTaken(string $groupName, int $excludeGroupId = null): bool {
        try{
            return $this->userGroupDao->isGroupNameTaken($groupName, $excludeGroupId);
        } catch (\Exception $e) {
            throw new RuntimeException("Error checking if group name is taken: " . $e->getMessage());
            
        }
    }
    
    /**
     * TODO: Function documentation getAllGrpupsId
     *
     * @return array
     *
     * @throws RuntimeException
     *
     * @author Natalia Herrera.
     * @since  2024-05-04
     */
    public function getAllGroupsId() : array {
        try{
            return $this->userGroupDao->fetchAllGroupsIds();
        } catch (\Exception $e) {
            throw new RuntimeException("Error fetching all user groups: " . $e->getMessage());
        }
        
    }
    
    /**
     * TODO: Function documentation getDeleteGroups
     *
     * @return array
     *
     * @throws RuntimeException
     *
     * @author Natalia Herrera.
     * @since  2024-05-04
     */
    public function getDeleteGroups() : array {
        try{
            return $this->userGroupDao->fetchDeletedGroups();
        } catch (\Exception $e) {
            throw new RuntimeException("Error fetching all user groups: " . $e->getMessage());
        }
    }
    
}