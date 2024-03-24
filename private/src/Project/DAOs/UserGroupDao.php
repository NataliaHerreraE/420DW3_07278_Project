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
}