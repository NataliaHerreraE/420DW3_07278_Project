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

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

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
$canSearchUsers = in_array('SEARCH_USERS', $permissions);

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Management</title>
    <link rel="stylesheet" href="<?= WEB_CSS_DIR . 'bootstrap.min.css' ?>">
    <link rel="stylesheet" href="<?= WEB_CSS_DIR . 'style.css' ?>">
    <script src="<?= WEB_JS_DIR ?>jquery.min.js"></script>
</head>
<body class="forms-page">
<div class="header-container">
    <?php require_once 'header.php'; ?>
</div>
<div class="container-form">
    <h1>User Management</h1>
    <div class="row">
        <div class="col1">
            <h2>User Operations</h2>
            <form id="userCrudForm">
                <div class="input-form">
                    <label for="userIdInput" class="form-label">User ID</label>
                    <input type="text" class="form-control" id="userIdInput" name="userId" placeholder="User ID" readonly>
                </div>
                <div class="input-form">
                    <label for="usernameInput" class="form-label">Username</label>
                    <input type="text" class="form-control" id="usernameInput" name="username" placeholder="Username" required>
                </div>
                <div class="input-form">
                    <label for="passwordInput" class="form-label">Password</label>
                    <input type="password" class="form-control" id="passwordInput" name="password" placeholder="Password" required>
                </div>
                <div class="input-form">
                    <label for="emailInput" class="form-label">Email</label>
                    <input type="email" class="form-control" id="emailInput" name="email" placeholder="Email" required>
                </div>
                <button type="button" class="btn-create" onclick="createUser()">Create</button>
                <br>
                <button type="button" class="btn-update" onclick="updateUser()">Update</button>
                <br>
                <button type="button" class="btn-delete" onclick="deleteUser()">Delete</button>
                <br>
            </form>
        </div>
        
        <div class="col2">
            <h2>User Search and Display</h2>
            <input type="text" class="form-control" id="searchInput" placeholder="Search criteria">
            <button type="button" class="btn-search" onclick="searchUsers()">Search by</button>
            <button type="button" class="btn-display-all" onclick="fetchAllUsers()">Display All Users</button>
            <div id="searchResults" class="mb-3"></div>
        </div>
    </div>
</div>

<script>
    function createUser() {
        let userData = {
            username: $('#usernameInput').val(),
            password: $('#passwordInput').val(),
            email: $('#emailInput').val()
        };
        $.post('/path_to_api/create_user', userData, function(response) {
            alert('User created successfully!');
            // Refresh or update the user list or UI as needed
        }).fail(function() {
            alert('Failed to create user.');
        });
    }
    
    function updateUser() {
        let userData = {
            id: $('#userIdInput').val(),
            username: $('#usernameInput').val(),
            password: $('#passwordInput').val(),
            email: $('#emailInput').val()
        };
        $.post('/path_to_api/update_user', userData, function(response) {
            alert('User updated successfully!');
            // Refresh or update the user list or UI as needed
        }).fail(function() {
            alert('Failed to update user.');
        });
    }
    
    function deleteUser() {
        let userId = $('#userIdInput').val();
        $.post('/path_to_api/delete_user', { id: userId }, function(response) {
            alert('User deleted successfully!');
            // Refresh or update the user list or UI as needed
        }).fail(function() {
            alert('Failed to delete user.');
        });
    }
    
    function searchUsers() {
        let searchQuery = $('#searchInput').val();
        $.get('/path_to_api/search_users', { query: searchQuery }, function(response) {
            $('#searchResults').html(response);
            // Update the display with search results
        }).fail(function() {
            alert('Failed to search users.');
        });
    }
    
    function fetchAllUsers() {
        $.get('/path_to_api/get_all_users', function(response) {
            $('#searchResults').html(response);
            // Update the display with all users
        }).fail(() => {
            alert('Failed to display all users.');
        });
    }
</script>

</body>
</html>
