<?php
/**
 * 420DW3_07278_Project UserRoleEnum.php
 *
 * @author  Natalia Andrea Herrera Espinosa.
 * @since   2024-03-29
 * (c) Copyright 2024 Natalia Herrera.
 */

namespace Project\Ennumerations;

enum UserRoleEnum: string {
    case ADMINISTRATORS = 'Administrators';
    case MANAGERS = 'Managers';
    case EDITORS = 'Editors';
    case DELETERS = 'Deleters';
    case USERS = 'Users';
    
    
    // Method to get the enumeration value from the string
    
    /**
     * TODO: Function documentation fromString
     *
     * @param string $value
     * @return self
     *
     * @author Natalia Herrera.
     * @since  2024-03-29
     */
    public static function fromString(string $value) : self {
        return self::tryFrom(ucfirst(strtolower($value))) ?? throw new \InvalidArgumentException("Invalid group name");
    }
    
    //note:
    //from() method:
    //This method takes a scalar value and returns the corresponding enum case.
    //If the provided value does not correspond to any case in the enum, from() will throw a ValueError.
    
    //tryFrom() method:
    //This method also takes a scalar value and returns the corresponding enum case.
    //Unlike from(), if the provided value does not match any case, tryFrom() will return null instead of throwing an error.
}

