<?php
/**
 * 420DW3_07278_Project login.php
 *
 * @author  Natalia Andrea Herrera Espinosa.
 * @since   2024-03-30
 * (c) Copyright 2024 Natalia Herrera.
 */
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
//require 'private/src/Project/Services/UserService.php';

use Project\Services\UserService;
require_once __DIR__ . '/../../../private/src/Project/Services/UserService.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $userService = new UserService();
    
    try {
        $user_id = $userService->authenticate($username, $password);
        if ($user_id) {
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            header('Location: /home.php');  // Redirect to home page after successful login
            exit;
        } else {
            // Set an error message and show the login form again
            $error = 'Invalid username or password.';
        }
    } catch (Exception $e) {
        // Log error or handle exceptions
        $error = 'An error occurred during login.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="public/css/bootstrap.min.css">
    <link rel="stylesheet" href="public/css/style.css">
    <script src="<?= WEB_JS_DIR ?>script.js"></script>
</head>
<body>

<div class="container">
    <?php if (!empty($error)): ?>
        <p><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form action="home.php" method="post">
        <h1>Login</h1>
        <div class="input-login-box">
            <!-- Add an image here for the username, if needed -->
            <input type="text" name="username" placeholder="Username" required>
        </div>
        <div class="input-login-box">
            <!-- Add an image here for the password, if needed -->
            <input type="password" name="password" placeholder="Password" required>
        </div>
        <div class="remember-forgot-check">
            <label><input type="checkbox" name="remember"> Remember me</label>
            <a href="#forgotpassword">Forgot password?</a> <!-- Update the href when forgot password page is ready -->
        </div>
        <button type="submit" class="btn">Login</button>
        <div class="register">
            <p>Don't have an account? <a href="#register">Register</a></p> <!-- Update the href when the registration page is ready -->
        </div>
    </form>
</div>
</body>
</html>