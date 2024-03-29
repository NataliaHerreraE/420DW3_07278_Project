<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project init.php
 * 
 * @author Marc-Eric Boury (MEbou)
 * @since 2024-03-14
 * (c) Copyright 2024 Marc-Eric Boury 
 */

// File made for the convenience of loading all base functions and extras in a single go
// Ready to be included in any endpoint.

/**
 * Activate or deactivate debug information output. Breaks standard output bu echoing a lot of stuff.
 */

require_once "constants.php";
require_once "autoloader.php";
require_once "debug.php";
require_once "session.php";
