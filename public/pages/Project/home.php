<?php
session_start();
/**
 * 420DW3_07278_Project Home.php
 *
 * @author  Natalia Andrea Herrera Espinosa.
 * @since   2024-04-28
 * (c) Copyright 2024 Natalia Herrera.
 */
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
if (!defined('InternalRouter')) {
    header('Location: /');
    exit;
}

$username = htmlspecialchars($_SESSION["username"]); // Good use of htmlspecialchars

// This part is well done. Just ensure $_SESSION['permissions'] is populated correctly on login.
$userPermissions = $_SESSION['permissions'] ?? [];

// Permissions array is well-defined.
$permissionsNeeded = [
    'userForm' => ['CREATE_USERS', 'UPDATE_USERS', 'DELETE_USERS', 'SEARCH_USERS'],
    'permissionForm' => ['CREATE_PERMISSIONS', 'UPDATE_PERMISSIONS', 'DELETE_PERMISSIONS', 'SEARCH_PERMISSIONS'],
    'groupForm' => ['CREATE_USERGROUPS', 'UPDATE_USERGROUPS', 'DELETE_USERGROUPS', 'SEARCH_USERGROUPS'],
];

function hasPermissionForForm(array $requiredPermissions, array $userPermissions): bool {
    return count(array_intersect($requiredPermissions, $userPermissions)) > 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/style.css">
    <script src="<?= WEB_JS_DIR ?>script.js"></script>
</head>
<body>
<form action="login.php" method="post">
    <div class="welcome-message">
        <h1>Welcome, <?= $username; ?>!</h1>
    </div>
    
    <!-- Buttons will only appear if the user has the required permissions -->
    <?php if (hasPermissionForForm($permissionsNeeded['userForm'], $userPermissions)): ?>
        <button onclick="location.href='userForm.php'">User Form</button>
    <?php endif; ?>
    
    <?php if (hasPermissionForForm($permissionsNeeded['permissionForm'], $userPermissions)): ?>
        <button onclick="location.href='permissionForm.php'">Permission Form</button>
    <?php endif; ?>
    
    <?php if (hasPermissionForForm($permissionsNeeded['groupForm'], $userPermissions)): ?>
        <button onclick="location.href='groupForm.php'">Group Form</button>
    <?php endif; ?>
</form>
</body>
</html>