<?php
declare(strict_types=1);
/**
 * 420DW3_07278_Project Permission.php
 *
 * @author  Natalia Andrea Herrera Espinosa.
 * @since   2024-03-24
 * (c) Copyright 2024 Natalia Herrera.
 */


namespace Project\DTOs;

use DateTime;
use JetBrains\PhpStorm\Pure;
use Teacher\GivenCode\Abstracts\AbstractDTO;
use Teacher\GivenCode\Exceptions\ValidationException;

/**
 * User DTO-type class
 */
class Permission extends AbstractDTO {
    
    /**
     *Database table name for this DTO.
     */
    public const TABLE_NAME = "Permissions";
    
    private const PERMISSION_KEY_MAX_LENGTH = 30;
    private const NAME_MAX_LENGTH = 30;
    private const DESCRIPTION_MAX_LENGTH = 70;
    
    private string $permissionKey;
    private string $name;
    private string $description;
    private ?DateTime $createdAt;
    private ?DateTime $updatedAt;
    
    protected function __construct() {
        parent::__construct();
    }
    
    /**
     * TODO: Function documentation fromValues
     *
     * @param string $permissionKey
     * @param string $name
     * @param string $description
     * @return Permission
     * @throws ValidationException
     *
     * @author Natalia Herrera.
     * @since  2024-03-24
     */
    public static function fromValues(string $permissionKey, string $name, string $description) : Permission {
        $object = new self();
        $object->setPermissionKey($permissionKey);
        $object->setName($name);
        $object->setDescription($description);
        return $object;
    }
    
    /**
     * TODO: Function documentation fromDbArray
     *
     * @param array $dbAssocArray
     * @return Permission
     * @throws ValidationException
     *
     * @author Natalia Herrera.
     * @since  2024-03-24
     */
    public static function fromDbArray(array $dbAssocArray) : Permission {
        $object = new self();
        $object->setId((int) $dbAssocArray['permission_id']);
        $object->setPermissionKey($dbAssocArray['permission_key']);
        $object->setName($dbAssocArray['name']);
        $object->setDescription($dbAssocArray['description']);
        $object->setCreatedAt(
            DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbAssocArray["created_at"])
        );
        $object->setUpdatedAt(
            DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbAssocArray["updated_at"])
        );
        return $object;
    }
    
    /**
     * TODO: Function documentation getPermissionKey
     *
     * @return string
     *
     * @author Natalia Herrera.
     * @since  2024-03-24
     */
    public function getPermissionKey() : string {
        return $this->permissionKey;
    }
    
    /**
     * TODO: Function documentation setPermissionKey
     *
     * @param string $permissionKey
     * @return void
     * @throws ValidationException
     *
     * @author Natalia Herrera.
     * @since  2024-03-24
     */
    public function setPermissionKey(string $permissionKey) : void {
        if (mb_strlen($permissionKey) > self::PERMISSION_KEY_MAX_LENGTH) {
            throw new ValidationException("Permission key must not be longer than " . self::PERMISSION_KEY_MAX_LENGTH . " characters.");
        }
        $this->permissionKey = $permissionKey;
    }
    
    /**
     * TODO: Function documentation getName
     *
     * @return string
     *
     * @author Natalia Herrera.
     * @since  2024-03-24
     */
    public function getName() : string {
        return $this->name;
    }
    
    /**
     * TODO: Function documentation setName
     *
     * @param string $name
     * @return void
     * @throws ValidationException
     *
     * @author Natalia Herrera.
     * @since  2024-03-24
     */
    public function setName(string $name) : void {
        if (mb_strlen($name) > self::NAME_MAX_LENGTH) {
            throw new ValidationException("Please enter again the permission name. Permission name must not be longer than " . self::NAME_MAX_LENGTH . " characters.");
        }
        $this->name = $name;
    }
    
    /**
     * TODO: Function documentation getDescription
     *
     * @return string
     *
     * @author Natalia Herrera.
     * @since  2024-03-24
     */
    public function getDescription() : string {
        return $this->description;
    }
    
    /**
     * TODO: Function documentation setDescription
     *
     * @param string $description
     * @return void
     * @throws ValidationException
     *
     * @author Natalia Herrera.
     * @since  2024-03-24
     */
    public function setDescription(string $description) : void {
        if (mb_strlen($description) > self::DESCRIPTION_MAX_LENGTH) {
            throw new ValidationException("Please enter again the description. Description must not be longer than " . self::DESCRIPTION_MAX_LENGTH . " characters.");
        }
        $this->description = $description;
    }
    
    /**
     * TODO: Function documentation getCreatedAt
     *
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
     *
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
        if (empty($this->permissionKey)) {
            if ($optThrowExceptions) {
                throw new ValidationException("Permission key is required to create a permission.");
            }
            return false;
        }
        if (empty($this->name)) {
            if ($optThrowExceptions) {
                throw new ValidationException("Permission name is required to create a permission.");
            }
            return false;
        }
        // Description can be empty. ask later(?) if it is required
        return true;
    }
    
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
                throw new ValidationException("Permission ID is required for update.");
            }
            return false;
        }
        return $this->validateForDbCreation($optThrowExceptions);
    }
    
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
                throw new ValidationException("Permission ID is required for deletion.");
            }
            return false;
        }
        return true;
    }
    
    /**
     * @inheritDoc
     */
    public function getDatabaseTableName() : string {
        return self::TABLE_NAME;
    }
}