<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project autoloader.php
 * 
 * @author Marc-Eric Boury (MEbou)
 * @since 2024-03-14
 * (c) Copyright 2024 Marc-Eric Boury 
 */

require_once "constants.php";

/**
 * Autoloader function used to load classes defined inside the 'src'
 * directory. PHP will use this function to attempt to load class definition files
 * when a class symbol with no loaded definition is used.
 *
 * @param string $classFQN The class' fully qualified name
 * @return void
 *
 * @author Marc-Eric Boury
 * @since  2024-03-14
 */
function project_autoloader(string $classFQN) : void {
    $path_from_fqn = str_replace("\\", NAMESPACE_PATH_SEPARATOR, $classFQN);
    require_once PRJ_SRC_DIR . $path_from_fqn . ".php";
    
/*    $path_from_fqn = str_replace("\\", DIRECTORY_SEPARATOR, $classFQN);
    $file = PRJ_SRC_DIR . $path_from_fqn . ".php";
    
    echo "Expected to load: $file<br>";
    
    if (file_exists($file)) {
        require_once $file;
    } else {
        echo "Failed to load: $file<br>";
    }*/
    
    
}


spl_autoload_register("project_autoloader");