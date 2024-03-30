<?php
/**
 * 420DW3_07278_Project indexProject.php
 *
 * @author  Natalia Andrea Herrera Espinosa.
 * @since   2024-03-30
 * (c) Copyright 2024 Natalia Herrera.
 */

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="public/css/bootstrap.min.css">
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <div class="container">
    <form action="">
        <h1>Login</h1>
        <div class="input-login-box">
            <!--Add nice image here for user, dont forget naty of the future-->
            <input type="text" placeholder="Username" required>
        </div>
        <div class="input-login-box">
            <!--Add nice image here for password, dont forget naty of the future-->
            <input type="password" placeholder="Password" required>
        </div>
        <div class="remember-forgot-check">
            <label><input type="checkbox">Remember me</label>
            <a href="#no exisitingrightnow">Forgot password?</a>
        </div>
        <button type="submit" class="btn">Login</button>
        <div class="register">
            <p>Don't have an account? <a href="#noexistingrightnow">Register</a></p>
        </div>
    </form>
    </div>
</body>
</html>