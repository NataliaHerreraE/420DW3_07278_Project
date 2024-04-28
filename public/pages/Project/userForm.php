<?php
/**
 * 420DW3_07278_Project userForm.php
 *
 * @author  Natalia Andrea Herrera Espinosa.
 * @since   2024-04-14
 * (c) Copyright 2024 Natalia Herrera.
 */


declare(strict_types=1);

use Project\Services\UserService;
use Project\Controllers\UserController;
use Project\Services\LoginService;

session_start();
$loginService = new LoginService();

// Redirect to login if the user is not logged in or doesn't have the LOGIN_ALLOWED permission
if (!$loginService->isLoggedIn() || !$loginService->hasPermission('LOGIN_ALLOWED')) {
    header('Location: indexProject.php');
    exit;
}

// Initialize services and controllers
$userService = new UserService();
$userController = new UserController();

// Permissions check
$permissions = $_SESSION['permissions'] ?? [];
$canCreateUsers = in_array('CREATE_USERS', $permissions);
$canUpdateUsers = in_array('UPDATE_USERS', $permissions);
$canDeleteUsers = in_array('DELETE_USERS', $permissions);
$canSearchUsers = in_array('SEARCH_ALLOWED', $permissions);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>User Management</title>
    <link rel="stylesheet" href="/public/css/bootstrap.min.css">
    <link rel="stylesheet" href="/public/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container">
    <h1>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h1>
    
    <div class="row">
        <!-- Left Column for User CRUD Operations -->
        <div class="col-md-6">
            <h2>User Operations</h2>
            <form id="userCrudForm">
                <div class="mb-3">
                    <label for="userIdInput" class="form-label">User ID</label>
                    <input type="text" class="form-control" id="userIdInput" placeholder="User ID" readonly>
                </div>
                <div class="mb-3">
                    <label for="usernameInput" class="form-label">Username</label>
                    <input type="text" class="form-control" id="usernameInput" placeholder="Username" required>
                </div>
                <div class="mb-3">
                    <label for="passwordInput" class="form-label">Password</label>
                    <input type="password" class="form-control" id="passwordInput" placeholder="Password" required>
                </div>
                <div class="mb-3">
                    <label for="emailInput" class="form-label">Email</label>
                    <input type="email" class="form-control" id="emailInput" placeholder="Email" required>
                </div>
                <div class="mb-3">
                    <button type="button" id="createUserButton" class="btn btn-primary">Create</button>
                    <button type="button" id="updateUserButton" class="btn btn-secondary">Update</button>
                    <button type="button" id="softDeleteUserButton" class="btn btn-danger">Delete</button>
                </div>
                <!-- Group management -->
                <div class="mb-3">
                    <label for="groupSelect" class="form-label">Group</label>
                    <select id="groupSelect" class="form-select">
                        <!-- Group options will be populated with AJAX?? try later -->
                    </select>
                </div>
                <div class="mb-3">
                    <button type="button" id="addToGroupButton" class="btn btn-success">Add to Group</button>
                    <button type="button" id="removeFromGroupButton" class="btn btn-warning">Remove from Group</button>
                </div>
            </form>
        </div>
        
        <!-- Right Column for Search and Display -->
        <div class="col-md-6">
            <h2>User Search and Display</h2>
            <div class="mb-3">
                <input type="checkbox" id="searchByIdCheck">
                <label for="searchByIdCheck" class="form-check-label">Search by User ID</label>
                <input type="checkbox" id="searchByUsernameCheck">
                <label for="searchByUsernameCheck" class="form-check-label">Search by Username</label>
            </div>
            <div class="input-group mb-3">
                <input type="text" class="form-control" id="searchInput" placeholder="Search criteria">
                <button type="button" id="searchButton" class="btn btn-primary">Search by</button>
            </div>
            <div id="searchResults"></div>
            <div class="mb-3">
                <button type="button" id="displayAllUsersButton" class="btn btn-info">Display All Users</button>
                <button type="button" id="displayDeletedUsersButton" class="btn btn-warning">Display Deleted Users
                </button>
            </div>
            <table id="usersTable" class="table">
                <!-- Table to display users will be populated by JavaScript -->
            </table>
            <div class="mb-3">
                <button type="button" id="hardDeleteUserButton" class="btn btn-dark">Permanently Delete User</button>
                <button type="button" onclick="window.location.href='homeMenu.php';" class="btn btn-success">Home Menu
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(() => {
        // Check permissions and adjust UI accordingly
        const permissions = <?= json_encode($permissions); ?>;
        const canCreateUsers = permissions.includes('CREATE_USERS');
        const canUpdateUsers = permissions.includes('UPDATE_USERS');
        const canDeleteUsers = permissions.includes('DELETE_USERS');
        const canSearchUsers = permissions.includes('SEARCH_ALLOWED');
        const canManageGroups = permissions.includes('MANAGE_GROUPS');
        
        $('#createUserButton').toggle(canCreateUsers);
        $('#updateUserButton').toggle(canUpdateUsers);
        $('#softDeleteUserButton').toggle(canDeleteUsers);
        $('#searchButton').toggle(canSearchUsers);
        $('#addToGroupButton').toggle(canManageGroups);
        $('#removeFromGroupButton').toggle(canManageGroups);
        $('#hardDeleteUserButton').toggle(canDeleteUsers);
        
        // Function to fetch groups
        function fetchGroups() {
            $.ajax({
                       url: '/groupController.php?action=getAllGroups',
                       type: 'GET',
                       dataType: 'json',
                       success: groups => {
                           const groupSelect = $('#groupSelect');
                           groupSelect.empty();
                           groups.forEach(group => {
                               groupSelect.append(`<option value="${group.group_id}">${group.group_name}</option>`);
                           });
                       }
                   });
        }
        
        // Event handler for creating a user
        $('#createUserButton').click(() => {
            const username = $('#usernameInput').val();
            const password = $('#passwordInput').val();
            const email = $('#emailInput').val();
            $.ajax({
                       url: '/UserController.php?action=create',
                       type: 'POST',
                       data: { username: username, password: password, email: email },
                       success: response => {
                           alert('User created successfully');
                           fetchUsers(); // Refresh user list
                       },
                       error: function(xhr, status, error) {
                           alert('Error creating user: ' + xhr.responseText);
                       }
                   });
        });
        
        // Event handler for updating a user
        $('#updateUserButton').click(function() {
            const userId = $('#userIdInput').val();
            const username = $('#usernameInput').val();
            const password = $('#passwordInput').val();
            const email = $('#emailInput').val();
            $.ajax({
                       url: '/UserController.php?action=update',
                       type: 'POST',
                       data: {userId: userId, username: username, password: password, email: email},
                       success: response => {
                           alert('User updated successfully');
                           fetchUsers(); // Refresh user list
                       },
                       error: function(xhr, status, error) {
                           alert('Error updating user: ' + xhr.responseText);
                       }
                   });
        });
        
        // Event handler for soft deleting a user
        $('#softDeleteUserButton').click(function() {
            const userId = $('#userIdInput').val();
            $.ajax({
                       url: '/UserController.php?action=delete',
                       type: 'POST',
                       data: {userId: userId},
                       success: function (response) {
                           alert('User deleted successfully');
                           fetchUsers(); // Refresh user list
                       },
                       error: function(xhr, status, error) {
                           alert('Error deleting user: ' + xhr.responseText);
                       }
                   });
        });
        
        // Event handler for fetching all users
        $('#displayAllUsersButton').click(() => {
            fetchUsers();
        });
        
        // Event handler for fetching deleted users
        $('#displayDeletedUsersButton').click(() => {
            fetchDeletedUsers();
        });
        
        // rewrite later
        if (canManageGroups) {
            fetchGroups();
        }
        
        // Fetch all users on page load
        fetchUsers();
    });
    
    //do the rest later
</script>


</body>
</html>
