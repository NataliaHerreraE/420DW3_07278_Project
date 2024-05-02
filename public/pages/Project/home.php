<?php
declare(strict_types=1);

use Project\Services\LoginService;

/**
 * 420DW3_07278_Project Home.php
 *
 * @author  Natalia Andrea Herrera Espinosa.
 * @since   2024-04-28
 * (c) Copyright 2024 Natalia Herrera.
 */

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);


if (!isset($_SESSION["loggedin"]) || ($_SESSION["loggedin"] !== true) ||
    !in_array('LOGIN_ALLOWED', $_SESSION['permissions'] ?? [])
) {
    header("location: " . WEB_ROOT_DIR . "login");
    exit;
}

// Remove the setting of session variables here, as they should be set during the login process, not on the home page

$username = htmlspecialchars($_SESSION["username"]);
// Permissions should already be set in the session at login
$userPermissions = $_SESSION['permissions'] ?? [];

// Define your permission checks
function hasPermissionForForm(array $requiredPermissions, array $userPermissions): bool {
    return count(array_intersect($requiredPermissions, $userPermissions)) > 0;
}

// Permissions array
$permissionsNeeded = [
    'userForm' => ['CREATE_USERS', 'UPDATE_USERS', 'DELETE_USERS', 'SEARCH_USERS'],
    'permissionForm' => ['CREATE_PERMISSIONS', 'UPDATE_PERMISSIONS', 'DELETE_PERMISSIONS', 'SEARCH_PERMISSIONS'],
    'groupForm' => ['CREATE_USERGROUPS', 'UPDATE_USERGROUPS', 'DELETE_USERGROUPS', 'SEARCH_USERGROUPS'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="<?= WEB_CSS_DIR . 'bootstrap.min.css' ?>">
    <link rel="stylesheet" href="<?= WEB_CSS_DIR . 'style.css' ?>">
</head>
<body class="home-page">
<div class="header-container">
    
    <?php require_once 'header.php'; ?>
</div>

<div class="content-container">
    <div class="sidebar">
        <h2>Forms Available for you</h2><br>
        <?php if (hasPermissionForForm($permissionsNeeded['userForm'], $userPermissions)): ?>
            <a href="<?= WEB_ROOT_DIR ?>userForm" class="sidebar-link">
                <button class="sidebar-btn">User Form</button>
            </a>
        <?php endif; ?>
        <?php if (hasPermissionForForm($permissionsNeeded['permissionForm'], $userPermissions)): ?>
            <a href="<?= WEB_ROOT_DIR ?>permissionForm" class="sidebar-link">
                <button class="sidebar-btn">Permission Form</button>
            </a>
        <?php endif; ?>
        <?php if (hasPermissionForForm($permissionsNeeded['groupForm'], $userPermissions)): ?>
            <a href="<?= WEB_ROOT_DIR ?>groupForm" class="sidebar-link">
                <button class="sidebar-btn">Group Form</button>
            </a>
        <?php endif; ?>
    </div>
    
    
    <div class="main-content">
        <h1>Welcome, <?= $username; ?>!</h1>
        <p>This space is tailored to empower you with tools and functionalities that enhance your workflow. Depending on your access level, you may find various forms ready to use, each designed to streamline your tasks.</p><br>
        <p>User Form: Here's where you can manage user accounts. Add new members, update existing profiles, or remove
            users no longer needing access. This form is a gateway to maintaining a robust user database, ensuring that
            the right people have the right access.</p>
        <p>Permission Form: Security and control are paramount. This form allows you to assign or modify the permissions
            for each user. You can create roles, define access levels, and update them as needed, ensuring everyone has
            the access they need to perform their tasks efficiently.</p>
        <p>Group Form: Organize your users into groups for easier management. This form lets you create, edit, and delete groups, allowing for structured user management and more coherent permission setting.</p><br>
    
    </div>
</div>

</body>
</html>
