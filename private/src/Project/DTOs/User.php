<?php
declare(strict_types=1);
//used to enforce strict type checking of scalar type declarations in that file.
/**
 * 420DW3_07278_Project User.php
 *
 * @author  Natalia Andrea Herrera Espinosa.
 * @since   2024-03-24
 * (c) Copyright 2024 Natalia Herrera.
 */
//data transfer object for users in the application

namespace Project\DTOs;

use DateTime;
use JetBrains\PhpStorm\Pure;
use Teacher\GivenCode\Abstracts\AbstractDTO;
use Teacher\GivenCode\Exceptions\ValidationException;

/**
 * User DTO-type class
 */
class User extends AbstractDTO {
    
    
    /**
     * Database table name for this DTO.
     */
    public const TABLE_NAME = "Users";
    
    // Max length constants for validation
    private const USERNAME_MAX_LENGTH = 30;
    private const EMAIL_MAX_LENGTH = 30;
    private const PASSWORD_MAX_LENGTH = 25;
    
    // User properties
    private string $username;
    private string $password;
    private string $email;
    private ?DateTime $createdAt;
    private ?DateTime $updatedAt;
    private bool $isDeleted;
    
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * TODO: Function documentation fromValues
     *
     * @param string $username
     * @param string $password
     * @param string $email
     * @return User
     * @throws ValidationException
     *
     * @author Natalia Herrera.
     * @since  2024-03-24
     */
    public static function fromValues(string $username, string $password, string $email) : User {
        $object = new self();
        $object->setUsername($username);
        $object->setPassword($password);
        $object->setEmail($email);
        return $object;
    }
    
    /**
     * TODO: Function documentation fromDbArray
     *
     * @param array $dbAssocArray
     * @return User
     * @throws ValidationException
     *
     * @author Natalia Herrera.
     * @since  2024-03-24
     */
    public static function fromDbArray(array $dbAssocArray) : User {
        $object = new self();
        $object->setId((int) $dbAssocArray['user_id']);
        $object->setUsername($dbAssocArray['username']);
        $object->setPassword($dbAssocArray['user_password']);
        $object->setEmail($dbAssocArray['email']);
        $object->setCreatedAt(
            DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbAssocArray["created_at"])
        );
        $object->setUpdatedAt(
            DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbAssocArray["updated_at"])
        );
        $object->setIsDeleted($dbAssocArray['is_deleted']);
        return $object;
    }
    
    
    /**
     * TODO: Function documentation getUsername
     * @return string
     *
     * @author Natalia Herrera.
     * @since  2024-03-24
     */
    public function getUsername() : string {
        return $this->username;
    }
    
    /**
     * TODO: Function documentation setUsername
     *
     * @param string $username
     * @return void
     * @throws ValidationException
     *
     * @author Natalia Herrera.
     * @since  2024-03-24
     */
    public function setUsername(string $username) : void {
        if (mb_strlen($username) > self::USERNAME_MAX_LENGTH) {
            throw new ValidationException("Please enter again the Username. Username must not be longer than " . self::USERNAME_MAX_LENGTH . " characters.");
        }
        $this->username = $username;
    }
    
    /**
     * TODO: Function documentation getPassword
     * @return string
     *
     * @author Natalia Herrera.
     * @since  2024-03-24
     */
    public function getPassword() : string {
        return $this->password;
    }
    
    /**
     * TODO: Function documentation setPassword
     *
     * @param string $password
     * @return void
     * @throws ValidationException
     *
     * @author Natalia Herrera.
     * @since  2024-03-24
     */
    public function setPassword(string $password) : void {
        if (mb_strlen($password) > self::PASSWORD_MAX_LENGTH) {
            throw new ValidationException("Please enter a new Password. Password must not be longer than " . self::PASSWORD_MAX_LENGTH . " characters.");
        }
        $this->password = $password;
    }
    
    /**
     * TODO: Function documentation getEmail
     * @return string
     *
     * @author Natalia Herrera.
     * @since  2024-03-24
     */
    public function getEmail() : string {
        return $this->email;
    }
    
    /**
     * TODO: Function documentation setEmail
     *
     * @param string $email
     * @return void
     * @throws ValidationException
     *
     * @author Natalia Herrera.
     * @since  2024-03-24
     */
    public function setEmail(string $email) : void {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new ValidationException("Invalid email format.");
        }
        if (mb_strlen($email) > self::EMAIL_MAX_LENGTH) {
            throw new ValidationException("Please enter a new Email. Email must not be longer than " . self::EMAIL_MAX_LENGTH . " characters.");
        }
        $this->email = $email;
    }
    
    /**
     * TODO: Function documentation getCreatedAt
     * @return DateTime|null
     *
     * @author Natalia Herrera.
     * @since  2024-03-24
     */
    public function getCreatedAt() : ?DateTime {
        return $this->createdAt;
    }
    
    /**
     * TODO: Function documentation setCreatedAt
     *
     * @param DateTime|null $createdAt
     * @return void
     *
     * @author Natalia Herrera.
     * @since  2024-03-24
     */
    public function setCreatedAt(?DateTime $createdAt) : void {
        $this->createdAt = $createdAt;
    }
    
    /**
     * TODO: Function documentation getUpdatedAt
     * @return DateTime|null
     *
     * @author Natalia Herrera.
     * @since  2024-03-24
     */
    public function getUpdatedAt() : ?DateTime {
        return $this->updatedAt;
    }
    
    /**
     * TODO: Function documentation setUpdatedAt
     *
     * @param DateTime|null $updatedAt
     * @return void
     *
     * @author Natalia Herrera.
     * @since  2024-03-24
     */
    public function setUpdatedAt(?DateTime $updatedAt) : void {
        $this->updatedAt = $updatedAt;
    }
    
    /**
     * TODO: Function documentation getIsDeleted
     * @return bool
     *
     * @author Natalia Herrera.
     * @since  2024-03-24
     */
    public function getIsDeleted(): bool {
        return $this->isDeleted;
    }
    
    /**
     * TODO: Function documentation setIsDeleted
     *
     * @param bool $isDeleted
     * @return void
     *
     * @author Natalia Herrera.
     * @since  2024-03-24
     */
    public function setIsDeleted(bool $isDeleted): void {
        $this->isDeleted = $isDeleted;
    }
    
    /**
     * @inheritDoc
     */
    public function getDatabaseTableName() : string {
        return self::TABLE_NAME;
    }
    
    // Validate the instance for creation in the database
    
    /**
     * TODO: Function documentation validateForDbCreation
     *
     * @param bool $optThrowExceptions
     * @return bool
     * @throws ValidationException
     *
     * @author Natalia Herrera.
     * @since  2024-03-24
     */
    public function validateForDbCreation(bool $optThrowExceptions = true) : bool {
        if (empty($this->username)) {
            if ($optThrowExceptions) {
                throw new ValidationException("Username is required to continue.");
            }
            return false;
        }
        if (empty($this->password)) {
            if ($optThrowExceptions) {
                throw new ValidationException("Password is required to continue.");
            }
            return false;
        }
        if (empty($this->email)) {
            if ($optThrowExceptions) {
                throw new ValidationException("Email is required to continue.");
            }
            return false;
        }
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            if ($optThrowExceptions) {
                throw new ValidationException("Invalid email format.");
            }
            return false;
        }
        // No need to check for ID or timestamps since they are set by the database :)
        
        return true;
    }
    
    // Validate the instance for updating in the database
    
    /**
     * TODO: Function documentation validateForDbUpdate
     *
     * @param bool $optThrowExceptions
     * @return bool
     * @throws ValidationException
     *
     * @author Natalia Herrera.
     * @since  2024-03-24
     */
    public function validateForDbUpdate(bool $optThrowExceptions = true) : bool {
        if ($this->id === null) {
            if ($optThrowExceptions) {
                throw new ValidationException("User ID is required for update.");
            }
            return false;
        }
        // Reuse the same validation as for creation for the other fields
        return $this->validateForDbCreation($optThrowExceptions);
    }
    
    // Validate the instance for deletion from the database
    
    /**
     * TODO: Function documentation validateForDbDelete
     *
     * @param bool $optThrowExceptions
     * @return bool
     * @throws ValidationException
     *
     * @author Natalia Herrera.
     * @since  2024-03-24
     */
    public function validateForDbDelete(bool $optThrowExceptions = true) : bool {
        if ($this->id === null) {
            if ($optThrowExceptions) {
                throw new ValidationException("User ID is required for deletion.");
            }
            return false;
        }
        // Check if the user has been already marked as deleted
        if ($this->isDeleted) {
            if ($optThrowExceptions) {
                throw new ValidationException("User is already marked as deleted.");
            }
            return false;
        }
        
        return true;
    }
    
}