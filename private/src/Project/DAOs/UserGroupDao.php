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
    
    public function __construct() {}
    
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
            $statement = $connection->prepare("UPDATE `" . UserGroup::TABLE_NAME . "` SET `is_deleted` = TRUE WHERE `group_id` = :group_id;");
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
            $statement = $connection->prepare(self::DELETE_QUERY);
        } else {
            $statement = $connection->prepare("UPDATE `" . UserGroup::TABLE_NAME . "` SET `is_deleted` = TRUE WHERE `group_id` = :id;");
        }
        
        $statement->bindValue(":id", $id, PDO::PARAM_INT);
        $statement->execute();
        
        // Post-delete check
        if ($realDeletes) {
            $deleted_group = $this->getById($id);
            if ($deleted_group !== null) {
                // If the group can still be found, the deletion didn't work
                throw new RuntimeException("Failed to delete the user group. Group ID: " . $id);
            }
        }
    }
    
    /**
     * TODO: Function documentation getAll
     *
     * @param bool $includeDeleted
     * @return array
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
                $statement = $connection->prepare("SELECT * FROM " . UserGroup::TABLE_NAME . " WHERE 'is_deleted' = FALSE;");
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
    public function addUserToGroup(int $userId, int $groupId): void {
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare("INSERT INTO User_UserGroup (user_id, user_group_id) VALUES (:user_id, :group_id)");
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
    public function removeUserFromGroup(int $userId, int $groupId): void {
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare("DELETE FROM User_UserGroup WHERE user_id = :user_id AND user_group_id = :group_id");
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
    public function getUserGroups(int $userId): array {
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
    public function getPermissionsByGroupId(int $groupId): array {
        $query = "SELECT p.permission_key FROM permissions p JOIN user_group_permissions ugp ON p.id = ugp.permission_id WHERE ugp.group_id = :groupId";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':groupId', $groupId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    }
    
    
}