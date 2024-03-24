<?php
declare(strict_types=1);
/**
 * 420DW3_07278_Project UserGroup.php
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
 * UserGroup DTO-type class
 */
class UserGroup extends AbstractDTO {
    
    /**
     * Database table name for this DTO.
     */
    public const TABLE_NAME = "UserGroups";
    
    private const GROUP_NAME_MAX_LENGTH = 20;
    private const DESCRIPTION_MAX_LENGTH = 70;
    
    private string $groupName;
    private string $description;
    private ?DateTime $createdAt;
    private ?DateTime $updatedAt;
    
    protected function __construct() {
        parent::__construct();
    }
    
    /**
     * TODO: Function documentation fromValues
     *
     * @param string $groupName
     * @param string $description
     * @return UserGroup
     * @throws ValidationException
     *
     * @author Natalia Herrera.
     * @since  2024-03-24
     */
    public static function fromValues(string $groupName, string $description) : UserGroup {
        $object = new self();
        $object->setGroupName($groupName);
        $object->setDescription($description);
        return $object;
    }
    
    /**
     * TODO: Function documentation fromDbArray
     *
     * @param array $dbAssocArray
     * @return UserGroup
     * @throws ValidationException
     *
     * @author Natalia Herrera.
     * @since  2024-03-24
     */
    public static function fromDbArray(array $dbAssocArray) : UserGroup {
        $object = new self();
        $object->setId((int) $dbAssocArray['group_id']);
        $object->setGroupName($dbAssocArray['group_name']);
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
     * TODO: Function documentation getGroupName
     *
     * @return string
     *
     * @author Natalia Herrera.
     * @since  2024-03-24
     */
    public function getGroupName() : string {
        return $this->groupName;
    }
    
    /**
     * TODO: Function documentation setGroupName
     *
     * @param string $groupName
     * @return void
     * @throws ValidationException
     *
     * @author Natalia Herrera.
     * @since  2024-03-24
     */
    public function setGroupName(string $groupName) : void {
        if (mb_strlen($groupName) > self::GROUP_NAME_MAX_LENGTH) {
            throw new ValidationException("Please enter again the Groupe name. Group name must not be longer than " . self::GROUP_NAME_MAX_LENGTH . " characters.");
        }
        $this->groupName = $groupName;
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
            throw new ValidationException("Please enter again the Description. Description must not be longer than " . self::DESCRIPTION_MAX_LENGTH . " characters.");
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
        if (empty($this->groupName)) {
            if ($optThrowExceptions) {
                throw new ValidationException("Group name is required to create a user group.");
            }
            return false;
        }
        // Description can be empty. (?) Is this correct?
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
                throw new ValidationException("User group ID is required for update.");
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
                throw new ValidationException("User group ID is required for deletion.");
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