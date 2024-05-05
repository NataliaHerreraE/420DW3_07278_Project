<?php
declare(strict_types=1);
/**
 * 420DW3_07278_Project PermissionDao.php
 *
 * @author  Natalia Andrea Herrera Espinosa.
 * @since   2024-03-24
 * (c) Copyright 2024 Natalia Herrera.
 */

namespace Project\DAOs;

use PDO;
use Project\DTOs\Permission;
use Teacher\GivenCode\Abstracts\AbstractDTO;
use Teacher\GivenCode\Abstracts\IDAO;
use Teacher\GivenCode\Exceptions\RuntimeException;
use Teacher\GivenCode\Exceptions\ValidationException;
use Teacher\GivenCode\Services\DBConnectionService;

/**
 *
 */
class PermissionDao implements IDAO {
    
    private const GET_QUERY = "SELECT * FROM `" . Permission::TABLE_NAME . "` WHERE `permission_id` = :permission_id;";
    private const CREATE_QUERY = "INSERT INTO `" . Permission::TABLE_NAME .
    "` (`permission_key`, `name`, `description`) VALUES (:permission_key, :name, :description);";
    private const UPDATE_QUERY = "UPDATE `" . Permission::TABLE_NAME .
    "` SET `permission_key` = :permission_key, `name` = :name, `description` = :description WHERE `permission_id` = :permission_id;";
    private const DELETE_QUERY = "DELETE FROM `" . Permission::TABLE_NAME . "` WHERE `permission_id` = :permission_id;";
    private PDO $connection;
    
    public function __construct() {
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
    public function getById(int $id) : ?Permission {
        $stmt = $this->connection->prepare("SELECT * FROM Permissions WHERE permission_id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return Permission::fromDbArray($result);  // Convert DB array to Permission object
        }
        return null;
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
    public function create(object $dto) : Permission {
        if (!($dto instanceof Permission)) {
            throw new RuntimeException("Passed object is not an instance of Permission.");
        }
        $dto->validateForDbCreation();
        
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare(self::CREATE_QUERY);
        $statement->bindValue(":permission_key", $dto->getPermissionKey(), PDO::PARAM_STR);
        $statement->bindValue(":name", $dto->getName(), PDO::PARAM_STR);
        $statement->bindValue(":description", $dto->getDescription(), PDO::PARAM_STR);
        $statement->execute();
        
        $new_id = (int) $connection->lastInsertId();
        return $this->getById($new_id);
    }
    
    /**
     * {@inheritDoc}
     * Updates the record of a certain DTO entity in the database.
     * Returns an updated appropriate DTO object instance.
     *
     * @param AbstractDTO $dto The {@see AbstractDTO} instance to update the record of.
     * @return AbstractDTO An updated {@see AbstractDTO} instance.
     *
     * @throws RuntimeException
     * @author Marc-Eric Boury
     * @since  2024-03-17
     */
    public function update(object $dto) : Permission {
        if (!($dto instanceof Permission)) {
            throw new RuntimeException("Passed object is not an instance of Permission.");
        }
        $dto->validateForDbUpdate();
        
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare(self::UPDATE_QUERY);
        $statement->bindValue(":permission_key", $dto->getPermissionKey(), PDO::PARAM_STR);
        $statement->bindValue(":name", $dto->getName(), PDO::PARAM_STR);
        $statement->bindValue(":description", $dto->getDescription(), PDO::PARAM_STR);
        $statement->bindValue(":permission_id", $dto->getId(), PDO::PARAM_INT);
        $statement->execute();
        
        return $this->getById($dto->getId());
    }
    
    /**
     * Deletes the record of a certain DTO entity in the database.
     *
     * @param object $dto The {@see AbstractDTO} instance to delete the record of.
     * @param bool   $realDeletes
     * @return void
     *
     * @throws RuntimeException
     * @throws ValidationException
     * @author Marc-Eric Boury
     * @since  2024-03-17
     */
    public function delete(object $dto, bool $realDeletes = false) : void {
        if (!($dto instanceof Permission)) {
            throw new RuntimeException("Passed object is not an instance of Permission.");
        }
        $dto->validateForDbDelete();
        
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare(self::DELETE_QUERY);
        $statement->bindValue(":permission_id", $dto->getId(), PDO::PARAM_INT);
        $statement->execute();
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
        $statement = $connection->prepare(self::DELETE_QUERY);
        $statement->bindValue(":permission_id", $id, PDO::PARAM_INT);
        if ($statement->execute()) {
            if ($statement->rowCount() === 0) {
                throw new RuntimeException("No permission found with ID: $id, nothing deleted.");
            }
        } else {
            throw new RuntimeException("Failed to execute delete operation.");
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
                $statement = $connection->prepare("SELECT * FROM " . Permission::TABLE_NAME . ";");
            } else {
                $statement = $connection->prepare("SELECT * FROM " . Permission::TABLE_NAME . ";");
            }
            $statement->execute();
            $results_array = $statement->fetchAll(PDO::FETCH_ASSOC);
            $object_array = [];
            foreach ($results_array as $result) {
                $object_array[] = Permission::fromDbArray($result);
            }
            return $object_array;
        } catch (\PDOException $excep) {
            throw new RuntimeException("Database error: " . $excep->getMessage());
        } catch (\Exception $excep) {
            throw new RuntimeException("Error: " . $excep->getMessage());
        }
    }
    
    /***
     * TODO: Function documentation getUserPermissions
     *
     * @param int $userId
     * @return array
     *
     * @author Natalia Herrera.
     * @since  2024-04-11
     */
    public function getUserPermissions(int $userId) : array {
        $query = "SELECT permission_key FROM permissions
              JOIN user_permissions ON permissions.permission_id = user_permissions.permission_id
              WHERE user_permissions.user_id = :userId";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    }
    
    /**
     * TODO: Function documentation getPermissionById
     *
     * @param $id
     * @return mixed
     *
     * @author Natalia Herrera.
     * @since  2024-05-05
     */
    public function getPermissionById(int $id): ?Permission {
        $stmt = $this->connection->prepare("SELECT * FROM Permissions WHERE permission_id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return new Permission($result);  // Ensure your Permission class can handle this
        }
        return null;
    }
    
    
}