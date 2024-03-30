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

/**
 *
 */
class UserGroupService implements IService {
    private UserGroupDAO $userGroupDao;
    
    public function __construct() {
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
        return $this->userGroupDao->getAll();
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
        return $this->userGroupDao->getById($id);
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
        $user_group = new UserGroup();
        $user_group->setGroupName($group_name);
        $user_group->setDescription($description);
        return $this->userGroupDao->create($user_group);
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
        $user_group = $this->userGroupDao->getById($id);
        $user_group->setGroupName($groupName);
        $user_group->setDescription($description);
        return $this->userGroupDao->update($user_group);
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
        $this->userGroupDao->deleteById($id, $hardDelete);
    }
    
    //resume for me:(same for user and permission)
    //getAllUserGroups() retrieves all user groups, option to include is_deleted groups.
    //getUserGroupById() fetchs a specific user group by ID.
    //createUserGroup() creates a new user group with name and description.
    //updateUserGroup() updates the details of an user group.
    //deleteUserGroup() deletes a user group by ID, with an option for hard or soft delete.
}