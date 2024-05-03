<?php
declare(strict_types=1);
/**
 * 420DW3_07278_Project UserDao.php
 *
 * @author  Natalia Andrea Herrera Espinosa.
 * @since   2024-03-24
 * (c) Copyright 2024 Natalia Herrera.
 */

namespace Project\DAOs;

use PDO;
use Project\DTOs\User;
use Teacher\GivenCode\Abstracts\AbstractDTO;
use Teacher\GivenCode\Abstracts\IDAO;
use Teacher\GivenCode\Exceptions\RuntimeException;
use Teacher\GivenCode\Exceptions\ValidationException;
use Teacher\GivenCode\Services\DBConnectionService;

/**
 *
 */
class UserDao implements IDAO {
    
    private const GET_QUERY = "SELECT * FROM `" . User::TABLE_NAME . "` WHERE `user_id` = :user_id;";
    private const CREATE_QUERY = "INSERT INTO `" . User::TABLE_NAME .
    "` (`username`, `user_password`, `email`) VALUES (:username, :password, :email);";
    private const UPDATE_QUERY = "UPDATE `" . User::TABLE_NAME .
    "` SET `username` = :username, `user_password` = :password, `email` = :email WHERE `user_id` = :user_id;";
    private const DELETE_QUERY = "DELETE FROM `" . User::TABLE_NAME . "` WHERE `user_id` = :user_id;";
    
    public function __construct() {
        $this->db = DBConnectionService::getConnection();
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
    public function getById(int $id, bool $includeDeleted = false) : ?User {
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare(self::GET_QUERY);
        $statement->bindValue(":user_id", $id, PDO::PARAM_INT);
        $statement->execute();
        
        $array = $statement->fetch(PDO::FETCH_ASSOC);
        if (!$array) {
            throw new RuntimeException("No record found for user_id# [$id].");
        }
        return User::fromDbArray($array);
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
    public function create(object $dto) : User {
        if (!($dto instanceof User)) {
            throw new RuntimeException("Passed object is not an instance of User.");
        }
        $dto->validateForDbCreation();
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare(self::CREATE_QUERY);
        $statement->bindValue(":username", $dto->getUsername(), PDO::PARAM_STR);
        $statement->bindValue(":password", $dto->getPassword(), PDO::PARAM_STR);
        $statement->bindValue(":email", $dto->getEmail(), PDO::PARAM_STR);
        $statement->execute();
        
        $new_id = (int) $connection->lastInsertId();
        $new_user = $this->getById($new_id);
        if ($new_user === null) {
            throw new RuntimeException("Unable to retrieve the user after creation. User ID: {$new_id}");
        }
        
        return $new_user;
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
    public function update(object $dto) : User {
        if (!($dto instanceof User)) {
            throw new RuntimeException("Passed object is not an instance of User.");
        }
        $dto->validateForDbUpdate();
        
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare(self::UPDATE_QUERY);
        $statement->bindValue(":username", $dto->getUsername(), PDO::PARAM_STR);
        $statement->bindValue(":password", $dto->getPassword(), PDO::PARAM_STR);
        $statement->bindValue(":email", $dto->getEmail(), PDO::PARAM_STR);
        $statement->bindValue(":user_id", $dto->getId(), PDO::PARAM_INT);
        $statement->execute();
        
        // fetch the user to ensure the update was successful
        $updated_user = $this->getById($dto->getId());
        if ($updated_user === null) {
            // in case where the user could not be retrieved after updating
            throw new RuntimeException("Unable to retrieve the user after update. User ID: " . $dto->getId());
        }
        
        return $updated_user;
    }
    
    /**
     * {@inheritDoc}
     * Deletes the record of a certain DTO entity in the database.
     * Soft deletes the record of a User entity in the database.
     *
     * @param AbstractDTO $dto The {@see AbstractDTO} instance to delete the record of.
     * @return void
     *
     * @throws RuntimeException
     * @author Marc-Eric Boury
     * @since  2024-03-17
     */
    public function delete(object $dto, bool $realDeletes = false) : void {
        if (!($dto instanceof User)) {
            throw new RuntimeException("Passed object is not an instance of User.");
        }
        $dto->validateForDbDelete();
        
        $connection = DBConnectionService::getConnection();
        
        if ($realDeletes) {
            // Hard delete - directly remove the user record from the database
            $statement = $connection->prepare(self::DELETE_QUERY);
        } else {
            // Soft delete - set is_deleted to true
            $statement = $connection->prepare("UPDATE `" . User::TABLE_NAME .
                                              "` SET `is_deleted` = TRUE WHERE `user_id` = :user_id;");
        }
        
        $statement->bindValue(":user_id", $dto->getId(), PDO::PARAM_INT);
        $statement->execute();
        
        // For hard deletes, check if the user has indeed been deleted
        if ($realDeletes) {
            $deleted_user = $this->getById($dto->getId());
            if ($deleted_user !== null) {
                throw new RuntimeException("Failed to delete the user. User ID: " . $dto->getId());
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
            $statement = $connection->prepare("UPDATE `" . User::TABLE_NAME .
                                              "` SET `is_deleted` = TRUE WHERE `user_id` = :id;");
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
                $statement = $connection->prepare("SELECT * FROM " . User::TABLE_NAME . ";");
            } else {
                $statement = $connection->prepare("SELECT * FROM " . User::TABLE_NAME . " WHERE 'is_deleted' = FALSE;");
            }
            $statement->execute();
            $results_array = $statement->fetchAll(PDO::FETCH_ASSOC);
            $object_array = [];
            foreach ($results_array as $result) {
                $object_array[] = User::fromDbArray($result);
            }
            return $object_array;
        } catch (\PDOException $excep) {
            // Handle database exceptions
            throw new RuntimeException("Database error: " . $excep->getMessage());
        } catch (\Exception $excep) {
            // Handle exceptions
            throw new RuntimeException("Error: " . $excep->getMessage());
        }
    }
    
    // Adds a user to a user group
    
    /***
     * TODO: Function documentation addToGroup
     *
     * @param int $userId
     * @param int $groupId
     * @return void
     * @throws RuntimeException
     *
     * @author Natalia Herrera.
     * @since  2024-04-11
     */
    public function addToGroup(int $userId, int $groupId) : void {
        $connection = DBConnectionService::getConnection();
        $statement =
            $connection->prepare("INSERT INTO User_UserGroup (user_id, user_group_id) VALUES (:user_id, :group_id)");
        $statement->bindParam(':user_id', $userId);
        $statement->bindParam(':group_id', $groupId);
        $statement->execute();
    }
    
    // Removes a user from a user group
    
    /***
     * TODO: Function documentation removeFromGroup
     *
     * @param int $userId
     * @param int $groupId
     * @return void
     * @throws RuntimeException
     *
     * @author Natalia Herrera.
     * @since  2024-04-11
     */
    public function removeFromGroup(int $userId, int $groupId) : void {
        $connection = DBConnectionService::getConnection();
        $statement =
            $connection->prepare("DELETE FROM User_UserGroup WHERE user_id = :user_id AND user_group_id = :group_id");
        $statement->bindParam(':user_id', $userId);
        $statement->bindParam(':group_id', $groupId);
        $statement->execute();
    }
    
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
    // Retrieves all groups that a user belongs to
    public function getUserGroups(int $userId) : array {
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare(
            "SELECT g.* FROM UserGroups g
             INNER JOIN User_UserGroup uug ON g.group_id = uug.user_group_id
             WHERE uug.user_id = :user_id"
        );
        $statement->bindParam(':user_id', $userId);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC); // or fetch into UserGroup DTOs check later
    }
    
    /***
     * TODO: Function documentation getByUsername
     *
     * @param string $username
     * @return User|null
     * @throws RuntimeException
     * @throws ValidationException
     *
     * @author Natalia Herrera.
     * @since  2024-04-11
     */
    public function getByUsername(string $username) : ?User {
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare("SELECT * FROM " . User::TABLE_NAME . " WHERE username = :username LIMIT 1;");
        $statement->bindValue(":username", $username, PDO::PARAM_STR);
        $statement->execute();
        
        $user_data = $statement->fetch(PDO::FETCH_ASSOC);
        if ($user_data) {
            return User::fromDbArray($user_data);
        } else {
            return null;
        }
    }
    
    /**
     * TODO: Function documentation fetchAllUserIds
     *
     * @return array
     *
     * @author Natalia Herrera.
     * @since  2024-05-03
     */
    public function fetchAllUserIds() : array {
        $sql = "SELECT user_id FROM users";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    }
    
    /**
     * TODO: Function documentation fetchAllUserNames
     * @return array
     *
     * @author Natalia Herrera.
     * @since  2024-05-03
     */
    public function fetchAllUserNames() : array {
        $sql = "SELECT username FROM users";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    }
}