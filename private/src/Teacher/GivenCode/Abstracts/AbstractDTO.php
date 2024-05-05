<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project AbstractDTO.php
 * 
 * @author Marc-Eric Boury (MEbou)
 * @since 2024-03-14
 * (c) Copyright 2024 Marc-Eric Boury 
 */

namespace Teacher\GivenCode\Abstracts;


use http\Exception\RuntimeException;

/**
 * TODO: Class documentation
 *
 * @author Marc-Eric Boury
 * @since  2024-03-14
 */
abstract class AbstractDTO implements IDTO {
    use DTOTrait;
    
    
    /**
     * Constructor for {@see AbstractDTO}.
     */
    public function __construct($id = null) {
        $this->id = $id ?? 0;
    }
    
    /**
     * @inheritDoc
     * @throws RuntimeException If the primary key value is not set.
     */
    public function getPrimaryKeyValue() : int {
        if (!isset($this->id)) {
            throw new RuntimeException("Attempt to access uninitialized 'id' property.");
        }
        if (empty($this->id)) {
            throw new RuntimeException("Primary key value is not set.");
        }
        return $this->id;
    }
    
    /**
     * @inheritDoc
     */
    public function getPrimaryKeyColumnName() : string {
        return self::$primaryKeyColumnName;
    }
    
    /**
     * @inheritDoc
     */
    abstract public function getDatabaseTableName() : string;
    
}