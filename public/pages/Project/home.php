<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

/**
 * 420DW3_07278_Project Home.php
 *
 * @author  Natalia Andrea Herrera Espinosa.
 * @since   2024-04-28
 * (c) Copyright 2024 Natalia Herrera.
 */
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
    <script src="<?= WEB_JS_DIR ?>script.js"></script>
</head>
<body>
<div class="container">
    <div class="welcome-message">
        <h1>Welcome, <?= $username; ?>!</h1>
    </div>
    <br>
    <div class="menu">
        <br>
        <!-- Buttons will only appear if the user has the required permissions -->
        <?php if (hasPermissionForForm($permissionsNeeded['userForm'], $userPermissions)): ?>
            <p class="userForm">Explanation about the user form and what it does.</p>
            <button onclick="location.href='<?php echo WEB_ROOT_DIR; ?>userForm'">User Form</button>
        <?php endif; ?>
        <br>
        <?php if (hasPermissionForForm($permissionsNeeded['permissionForm'], $userPermissions)): ?>
            <p class="permissionForm">Explanation about the user form and what it does.</p>
            <button onclick="location.href='permissionForm.php'">Permission Form</button>
        <?php endif; ?>
        <br>
        <?php if (hasPermissionForForm($permissionsNeeded['groupForm'], $userPermissions)): ?>
            <p class="groupForm">Explanation about the user form and what it does.</p>
            <button onclick="location.href='groupForm.php'">Group Form</button>
        <?php endif; ?>
    
    </div>

</div>
</body>
</html>
