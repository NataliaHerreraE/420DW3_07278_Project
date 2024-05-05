<?php
/**
 * 420DW3_07278_Project permissionForm.php
 *
 * @author  Natalia Andrea Herrera Espinosa.
 * @since   2024-04-14
 * (c) Copyright 2024 Natalia Herrera.
 */
declare(strict_types=1);

use Project\Controllers\PermissionController;
use Project\Services\PermissionService;
use Project\Services\LoginService;

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$loginService = new LoginService();
if (!$loginService->isLoggedIn() || !$loginService->hasPermission('LOGIN_ALLOWED')) {
    header('Location: login.php');
    exit;
}
$groupService = new PermissionService();
$userGroupController = new PermissionController();

$permissions = $_SESSION['permissions'] ?? [];
$canCreatePermissions = in_array('CREATE_PERMISSIONS', $permissions);
$canUpdatePermissions = in_array('UPDATE_PERMISSIONS', $permissions);
$canDeletePermissions = in_array('DELETE_PERMISSIONS', $permissions);
$canSearchPermissions = in_array('SEARCH_PERMISSIONS', $permissions);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Permission Management</title>
    <link rel="stylesheet" href="<?= WEB_CSS_DIR . 'bootstrap.min.css' ?>">
    <link rel="stylesheet" href="<?= WEB_CSS_DIR . 'style.css' ?>">
    <script src="<?= WEB_JS_DIR . 'jquery-3.7.1.min.js' ?>"></script>
    <!--<script>
        console.log('Testing jQuery:', $);
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (window.jQuery) {
                console.log("jQuery is loaded");
            } else {
                console.log("jQuery is not loaded");
            }
        });
    </script>-->
    
    <script type="text/javascript" src="<?= WEB_JS_DIR . "permission.js" ?>" defer></script>
    <script type="text/javascript">
        var baseUrl = '<?= WEB_ROOT_DIR ?>';
    </script>
</head>
<body class="forms-page">
<div class="header-container">
    <?php require_once 'header.php'; ?>
</div>
<div class="container-form">
    <h1>Permission Management</h1>
    <br>
    <div class="row">
        <div class="col1">
            <h2>Permission Operations</h2>
            <form id="userCrudForm">
                <div class="input-form">
                    <label for="permissionKeyInput" class="form-label">Permission Key</label>
                    <input type="text" class="form-control" id="permissionKeyInput" name="permissionKey" placeholder="permissionKey" required>
                </div>
                <div class="input-form">
                    <label for="nameInput" class="form-label">Name</label>
                    <input type="text" class="form-control" id="nameInput" name="name" placeholder="name" required>
                </div>
                <div class="input-form">
                    <label for="descriptionInput" class="form-label">Description</label>
                    <input type="text" class="form-control" id="descriptionInput" name="description" placeholder="description" required>
                </div>
                <!-- Form buttons -->
                <button type="button" class="btn-create" onclick="createPermission()">Create</button>
                <button type="button" class="btn-update" onclick="updatePermission()">Update</button>
                <button type="button" class="btn-delete" onclick="deletePermission()">Delete</button>
            </form>
        </div>
        <div class="col2">
            <h2>Permission Search and Display</h2>
            <label for="permissionIdSelect">Select Permission to Search:</label>
            <select class="form-control" id="permissionIdSelect">
            </select>
            <button type="button" class="btn-search" id="permissionIdSelect" onclick="searchByPermissionId()">Search by ID</button>
            <button type="button" class="btn-all" onclick="fetchAllPermissions()">Display All Permissions</button>
            <br>
            <div id="allPermissionDisplay">
                <table class="table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Permission Key</th>
                        <th>name</th>
                        <th>description</th>
                    </tr>
                    </thead>
                    <tbody id="allPermissionsBody">
                    <!-- Rows will be dynamically added here -->
                    </tbody>
                </table>
            </div>
            <div class="col3">
                <h2>Deleted Permission</h2>
                <button type="button" class="btn-view-deleted" onclick="fetchDeletedPermissions()">Display Deleted Permission</button>
                <div id="deletedPermissionDisplay">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Permission Key</th>
                            <th>name</th>
                            <th>description</th>
                        </tr>
                        </thead>
                        <tbody id="deletedPermissionsBody">
                        <!-- Deleted user rows will be dynamically added here -->
                        </tbody>
                    </table>
                </div>
                <br>
                <label for="permissionIdSelectDelete">Select Permissions to Delete from DB:</label>
                <select class="form-control" id="permissionIdSelectDelete">
                </select>
                <button type="button" class="btn-hardDelete" onclick="hardDeletePermission()">Delete from de Database</button>
            </div>
        </div>
    </div>
    <br>
    <div class="row2">
        <div class="col3">
            <h2>Group Permission Management</h2>
            <form id="groupPermissionForm">
                <div class="input-form">
                    <label for="groupIdInput" class="form-label">Group ID</label>
                    <input type="text" class="form-control" id="groupIdInput" name="groupId" placeholder="Group ID">
                    <label for="permissionIdInput" class="form-label">Permission ID</label>
                    <input type="text" class="form-control" id="permissionIdInput" name="permissionId" placeholder="Permission ID">
                </div>
                <button type="button" class="btn-add-to-group" onclick="addPermissionToGroup()">Add to Group</button>
                <button type="button" class="btn-remove-from-group" onclick="removePermissionFromGroup()">Remove from Group</button>
            </form>
        </div>
    </div>
</div>

<!--<div>
    <h2>DEBUG / INFO DATA</h2>
    <div id="debugContents"></div>
</div>-->

</body>
</html>