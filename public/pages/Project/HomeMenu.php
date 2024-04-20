<?php
/**
 * 420DW3_07278_Project HomeMenu.php
 *
 * @author  Natalia Andrea Herrera Espinosa.
 * @since   2024-04-14
 * (c) Copyright 2024 Natalia Herrera.
 */

session_start();

// Redirect to login page if not logged in
if (!isset($_SESSION['username']) || !isset($_SESSION['permissions'])) {
    header('Location: login.php');
    exit();
}

$canAccessUsers = in_array('MANAGE_USERS', $_SESSION['permissions']);
$canAccessGroups = in_array('MANAGE_GROUPS', $_SESSION['permissions']);
$canAccessPermissions = in_array('MANAGE_PERMISSIONS', $_SESSION['permissions']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Menu</title>
    <link rel="stylesheet" href="public/css/bootstrap.min.css">
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
<div class="container">
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <div class="menu">
        <?php if ($canAccessUsers): ?>
            <button onclick="window.location.href='userForm.php';">Manage Users</button>
        <?php endif; ?>
        <?php if ($canAccessGroups): ?>
            <button onclick="window.location.href='groupForm.php';">Manage Groups</button>
        <?php endif; ?>
        <?php if ($canAccessPermissions): ?>
            <button onclick="window.location.href='permissionForm.php';">Manage Permissions</button>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
