<?php

declare(strict_types=1);
use Project\Services\LoginService;
/**
 * 420DW3_07278_Project header.php
 *
 * @author  Natalia Andrea Herrera Espinosa.
 * @since   2024-04-30
 * (c) Copyright 2024 Natalia Herrera.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>
<div class="header-container">
    <nav class="nav-bar">
        <a href="<?= WEB_ROOT_DIR ?>userForm" class="nav-link">User Form</a>
        <a href="<?= WEB_ROOT_DIR ?>groupForm" class="nav-link">UserGroup Form</a>
        <a href="<?= WEB_ROOT_DIR ?>permissionForm" class="nav-link">Permissions Form</a>
        <?php if (LoginService::isLoggedIn()): ?>
            <a href="<?= WEB_ROOT_DIR . "api/logout" ?>" class="logout-link">Logout</a>
        <?php else: ?>
            <a href="<?= WEB_ROOT_DIR . "pages/login" ?>" class="logout-link">Login</a>
        <?php endif; ?>
    </nav>
</div>

