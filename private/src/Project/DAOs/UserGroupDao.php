<?php
declare(strict_types=1);
/**
 * 420DW3_07278_Project UserGroupDao.php
 *
 * @author  Natalia Andrea Herrera Espinosa.
 * @since   2024-03-24
 * (c) Copyright 2024 Natalia Herrera.
 */
namespace Project\DAOs;

use PDO;
use PDOException;
use Project\DTOs\UserGroup;
use Teacher\GivenCode\Abstracts\AbstractDTO;
use Teacher\GivenCode\Abstracts\IDAO;
use Teacher\GivenCode\Exceptions\RuntimeException;
use Teacher\GivenCode\Exceptions\ValidationException;
use Teacher\GivenCode\Services\DBConnectionService;

/**
 *
 */
class UserGroupDao implements IDAO {
    
    private const GET_QUERY = "SELECT * FROM `" . UserGroup::TABLE_NAME . "` WHERE `group_id` = :group_id;";
    private const CREATE_QUERY = "INSERT INTO `" . UserGroup::TABLE_NAME .
    "` (`group_name`, `description`) VALUES (:group_name, :description);";
    private const UPDATE_QUERY = "UPDATE `" . UserGroup::TABLE_NAME .
    "` SET `group_name` = :group_name, `description` = :description WHERE `group_id` = :group_id;";
    private const DELETE_QUERY = "DELETE FROM `" . UserGroup::TABLE_NAME . "` WHERE `group_id` = :group_id;";
    private PDO $connection;
    
    /**
     * @throws RuntimeException
     */
    public function __construct() {
        //$this->connection = $connection;
        $this->connection = DBConnectionService::getConnection();
    }
    
    /**
     * {@inheritDoc}
     * Retrieves a record of a certain DTO entity from the database and returns
     * an appropriate DTO object instance.
     *
     * @param int $id The identifier value of the record to obtain.
     * @return AbstractDTO|null The created object DTO instance or null if no record was found for the specified id.
     *
     * @throws RuntimeException
     * @author Marc-Eric Boury
     * @since  2024-03-17
     */
    public function getById(int $id, bool $includeDeleted = false) : ?UserGroup {
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare(self::GET_QUERY);
        $statement->bindValue(":group_id", $id, PDO::PARAM_INT);
        $statement->execute();
        
        $array = $statement->fetch(PDO::FETCH_ASSOC);
        if (!$array) {
            throw new RuntimeException("No record found for group_id# [$id].");
        }
        return UserGroup::fromDbArray($array);
    }
    
    /**
     * {@inheritDoc}
     * Creates a record for a certain DTO entity in the database.
     * Returns an updated appropriate DTO object instance.
     *
     * @param AbstractDTO $dto The {@see AbstractDTO} instance to create a record of.
     * @return AbstractDTO An updated {@see AbstractDTO} instance.
     *
     * @throws RuntimeException
     * @author Marc-Eric Boury
     * @since  2024-03-17
     */
    public function create(object $dto) : UserGroup {
        if (!($dto instanceof UserGroup)) {
            throw new RuntimeException("Passed object is not an instance of UserGroup.");
        }
        $dto->validateForDbCreation();
        
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare(self::CREATE_QUERY);
        $statement->bindValue(":group_name", $dto->getGroupName(), PDO::PARAM_STR);
        $statement->bindValue(":description", $dto->getDescription(), PDO::PARAM_STR);
        $statement->execute();
        
        $new_id = (int) $connection->lastInsertId();
        $new_group = $this->getById($new_id);
        if ($new_group === null) {
            throw new RuntimeException("Unable to retrieve the user group after creation. Group ID: {$new_id}");
        }
        
        return $new_group;
    }
    
    /**
     * {@inheritDoc}
     * Updates the record of a certain DTO entity in the database.
     * Returns an updated appropriate DTO object instance.
     *
     * @param AbstractDTO $dto The {@see AbstractDTO} instance to update the record of.
     * @return AbstractDTO An updated {@see AbstractDTO} instance.
     *
     * @throws ValidationException|RuntimeException
     * @author Marc-Eric Boury
     * @since  2024-03-17
     */
    public function update(object $dto) : UserGroup {
        if (!($dto instanceof UserGroup)) {
            throw new RuntimeException("Passed object is not an instance of UserGroup.");
        }
        $dto->validateForDbUpdate();
        
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare(self::UPDATE_QUERY);
        $statement->bindValue(":group_name", $dto->getGroupName(), PDO::PARAM_STR);
        $statement->bindValue(":description", $dto->getDescription(), PDO::PARAM_STR);
        $statement->bindValue(":group_id", $dto->getId(), PDO::PARAM_INT);
        $statement->execute();
        
        $updated_group = $this->getById($dto->getId());
        if ($updated_group === null) {
            throw new RuntimeException("Unable to retrieve the user group after update. Group ID: " . $dto->getId());
        }
        
        return $updated_group;
    }
    
    /**
     * {@inheritDoc}
     * Deletes the record of a certain DTO entity in the database.
     *
     * @param AbstractDTO $dto The {@see AbstractDTO} instance to delete the record of.
     * @return void
     *
     * @throws ValidationException|RuntimeException
     * @author Marc-Eric Boury
     * @since  2024-03-17
     */
    public function delete(object $dto, bool $realDeletes = false) : void {
        if (!($dto instanceof UserGroup)) {
            throw new RuntimeException("Passed object is not an instance of UserGroup.");
        }
        $dto->validateForDbDelete();
        
        $connection = DBConnectionService::getConnection();
        
        if ($realDeletes) {
            $statement = $connection->prepare(self::DELETE_QUERY);
        } else {
            $statement = $connection->prepare("UPDATE `" . UserGroup::TABLE_NAME .
                                              "` SET `is_deleted` = TRUE WHERE `group_id` = :group_id;");
        }
        
        $statement->bindValue(":group_id", $dto->getId(), PDO::PARAM_INT);
        $statement->execute();
        
        if ($realDeletes) {
            $deleted_group = $this->getById($dto->getId());
            if ($deleted_group !== null) {
                throw new RuntimeException("Failed to delete the user group. Group ID: " . $dto->getId());
            }
        }
    }
    
    /**
     * {@inheritDoc}
     * Deletes the record of a certain DTO entity in the database based on its identifier.
     *
     * @param int $id The identifier of the DTO entity to delete
     * @return void
     *
     * @throws RuntimeException
     * @author Marc-Eric Boury
     * @since  2024-03-17
     */
    public function deleteById(int $id, bool $realDeletes = false) : void {
        $connection = DBConnectionService::getConnection();
        
        if ($realDeletes) {
            // Hard delete - directly remove the user record from the database
            $statement = $connection->prepare(self::DELETE_QUERY);
        } else {
            // Soft delete - set is_deleted to true
            $statement = $connection->prepare("UPDATE `" . UserGroup::TABLE_NAME .
                                              "` SET `is_deleted` = TRUE WHERE `group_id` = :id;");
        }
        
        $statement->bindValue(":id", $id, PDO::PARAM_INT);
        $statement->execute();
        
        // verify that the user has indeed been deleted.
        if ($realDeletes) {
            $deleted_user = $this->getById($id);
            if ($deleted_user !== null) {
                // If the user can still be found, the deletion didn't work as expected.
                throw new RuntimeException("Failed to delete the user. User ID: " . $id);
            }
        }
    }
    
    
    /**
     * TODO: Function documentation getAll
     *
     * @throws RuntimeException
     *
     * @author Natalia Herrera.
     * @since  2024-03-29
     */
    public function getAll(bool $includeDeleted = false) : array {
        $connection = DBConnectionService::getConnection();
        try {
            if ($includeDeleted) {
                $statement = $connection->prepare("SELECT * FROM " . UserGroup::TABLE_NAME . ";");
            } else {
                $statement =
                    $connection->prepare("SELECT * FROM " . UserGroup::TABLE_NAME . " WHERE 'is_deleted' = FALSE;");
            }
            $statement->execute();
            $results_array = $statement->fetchAll(PDO::FETCH_ASSOC);
            $object_array = [];
            foreach ($results_array as $result) {
                $object_array[] = UserGroup::fromDbArray($result);
            }
            return $object_array;
        } catch (\PDOException $excep) {
            throw new RuntimeException("Database error: " . $excep->getMessage());
        } catch (\Exception $excep) {
            throw new RuntimeException("Error: " . $excep->getMessage());
        }
    }
    
    // Associates a user with a user group
    
    /***
     * TODO: Function documentation addUserToGroup
     *
     * @param int $userId
     * @param int $groupId
     * @return void
     * @throws RuntimeException
     *
     * @author Natalia Herrera.
     * @since  2024-04-11
     */
    public function addUserToGroup(int $userId, int $groupId) : void {
        $connection = DBConnectionService::getConnection();
        $statement =
            $connection->prepare("INSERT INTO User_UserGroup (user_id, user_group_id) VALUES (:user_id, :group_id)");
        $statement->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $statement->bindParam(':group_id', $groupId, PDO::PARAM_INT);
        $statement->execute();
    }
    
    // Removes a user from a user group
    
    /***
     * TODO: Function documentation removeUserFromGroup
     *
     * @param int $userId
     * @param int $groupId
     * @return void
     * @throws RuntimeException
     *
     * @author Natalia Herrera.
     * @since  2024-04-11
     */
    public function removeUserFromGroup(int $userId, int $groupId) : void {
        $connection = DBConnectionService::getConnection();
        $statement =
            $connection->prepare("DELETE FROM User_UserGroup WHERE user_id = :user_id AND user_group_id = :group_id");
        $statement->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $statement->bindParam(':group_id', $groupId, PDO::PARAM_INT);
        $statement->execute();
    }
    
    // Retrieves all user groups a user belongs to
    
    /***
     * TODO: Function documentation getUserGroups
     *
     * @param int $userId
     * @return array
     * @throws RuntimeException
     *
     * @author Natalia Herrera.
     * @since  2024-04-11
     */
    public function getUserGroups(int $userId) : array {
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare("SELECT ug.* FROM UserGroups ug
                                           INNER JOIN User_UserGroup uug ON ug.group_id = uug.user_group_id
                                           WHERE uug.user_id = :user_id");
        $statement->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC); // Convert to UserGroup DTOs as needed CHECK LATER
    }
    
    /***
     * TODO: Function documentation getPermissionsByGroupId
     *
     * @param int $groupId
     * @return array
     *
     * @author Natalia Herrera.
     * @since  2024-04-11
     */
    public function getPermissionsByGroupId(int $groupId) : array {
        $query =
            "SELECT p.permission_key FROM permissions p JOIN user_group_permissions ugp ON p.permission_id = ugp.permission_id WHERE ugp.user_group_id = :groupId";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':groupId', $groupId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
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
    public function isGroupNameTaken(string $groupName, int $excludeGroupId = null) : bool {
        $query = "SELECT COUNT(*) FROM UserGroups WHERE group_name = :groupName";
        $params = ['groupName' => $groupName];
        
        if ($excludeGroupId !== null) {
            $query .= " AND group_id != :excludeGroupId";
            $params['excludeGroupId'] = $excludeGroupId;
        }
        
        $stmt = $this->connection->prepare($query);
        foreach ($params as $key => &$val) {
            $stmt->bindParam($key, $val);
        }
        $stmt->execute();
        $count = $stmt->fetchColumn();
        
        return $count > 0;
    }
    
    /**
     * TODO: Function documentation fetchAllGroupsIds
     *
     * @return array
     *
     * @author Natalia Herrera.
     * @since  2024-05-04
     */
    public function fetchAllGroupsIds() : array {
        $sql = "SELECT group_id FROM UserGroups";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    }
    
    /**
     * TODO: Function documentation fetchDeletedGroups
     *
     * @return array
     *
     * @author Natalia Herrera.
     * @since  2024-05-04
     */
    public function fetchDeletedGroups() : array {
        $sql = "SELECT * FROM UserGroups WHERE is_deleted = 1";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    }
    
    
}