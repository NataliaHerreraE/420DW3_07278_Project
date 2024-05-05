<?php
/**
 * 420DW3_07278_Project groupForm.php
 *
 * @author  Natalia Andrea Herrera Espinosa.
 * @since   2024-04-14
 * (c) Copyright 2024 Natalia Herrera.
 */
declare(strict_types=1);

use Project\Controllers\UserGroupController;
use Project\Services\UserGroupService;
use Project\Services\LoginService;


if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$groupService = new UserGroupService();
$groups = $groupService->getAllGroupsId();

$loginService = new LoginService();
if (!$loginService->isLoggedIn() || !$loginService->hasPermission('LOGIN_ALLOWED')) {
    header('Location: login.php');
    exit;
}
$groupService = new UserGroupService();
$userGroupController = new UserGroupController();

$permissions = $_SESSION['permissions'] ?? [];
$canCreateUserGroups = in_array('CREATE_USERGROUPS', $permissions);
$canUpdateUserGroups = in_array('UPDATE_USERGROUPS', $permissions);
$canDeleteUserGroups = in_array('DELETE_USERGROUPS', $permissions);
$canSearchUserGroups = in_array('SEARCH_USERGROUPS', $permissions);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Group Management</title>
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
    
    <script type="text/javascript" src="<?= WEB_JS_DIR . "usergroup.js" ?>" defer></script>
    <script type="text/javascript">
        var baseUrl = '<?= WEB_ROOT_DIR ?>';
    </script>
</head>
<body class="forms-page">
<div class="header-container">
    <?php require_once 'header.php'; ?>
</div>
<div class="container-form">
    <h1>User Group Management</h1>
    <br>
    <div class="row">
        <div class="col1">
            <h2>User Group Operations</h2>
            <form id="groupCrudForm">
                <!-- groupname input -->
                <div class="input-form">
                    <label for="groupnameInput" class="form-label">Group Name</label>
                    <input type="text" class="form-control" id="groupnameInput" name="groupname" placeholder="groupname" required>
                </div>
                <!-- description input -->
                <div class="input-form">
                    <label for="descriptionInput" class="form-label">Description</label>
                    <input type="description" class="form-control" id="descriptionInput" name="description" placeholder="description" required>
                </div>
                <!-- Form buttons -->
                <button type="button" class="btn-create" onclick="createGroup()">Create</button>
                <button type="button" class="btn-update" onclick="updateGroup()">Update</button>
                <button type="button" class="btn-delete" onclick="deleteGroup()">Delete</button>
            </form>
        </div>
        <div class="col2">
            <h2>Group Search and Display</h2>
            <label for="groupIdSelect">Select Group to Search:</label>
            <select class="form-control" id="groupIdSelect">
            </select>
            <button type="button" class="btn-search" onclick="searchByGroupId()">Search by ID</button>
            <button type="button" class="btn-allgroups" onclick="fetchAllGroups()">Display All Groups</button>
            <div id="allGroupsBody">
                <table class="table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>UserGroup</th>
                        <th>Description</th>
                    </tr>
                    </thead>
                    <tbody id="allgroupsBody">
                    <!-- Rows will be dynamically added here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <br>
    <div class="col3">
        <h2>Deleted Users</h2>
        <button type="button" class="btn-view-deleted" onclick="fetchDeletedUsers()">Display Deleted Users</button>
        <div id="deletedUsersDisplay">
            <table class="table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                </tr>
                </thead>
                <tbody id="deletedUsersBody">
                <!-- Deleted user rows will be dynamically added here -->
                </tbody>
            </table>
        </div>
        <label for="groupIdSelectDelete">Select Group to Delete from DB:</label>
        <select class="form-control" id="groupIdSelectDelete">
        </select>
        <button type="button" class="btn-hardDelete" onclick="hardDeleteGroup()">Delete from de Database</button>
    </div>
</div>
<br>
</div>

<!--<div>
    <h2>DEBUG / INFO DATA</h2>
    <div id="debugContents"></div>
</div>-->

</body>
</html>

