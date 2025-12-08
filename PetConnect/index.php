<?php
session_start();
$isLogged = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Welcome - 🐾 PetConnect</title>
    <link rel="icon" type="image/png" href="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSXNVYInU6n-54guKmWlNqLHhMtasX6o6wAgQ&s">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <?php include 'common/nav.php'; ?>

    <div class="container">
        <div class="jumbotron">
            <img style="width:100%" src="https://activepets.co.za/web/ap-content/uploads/2019/08/Active-pets-dogs-and-cats.png">
            <h1 class="display-4">Welcome to PetConnect 🐾</h1>
            <p class="lead">Share your pets photos❣</p>
            <?php if (!$isLogged): ?>
                <a href="login.php" class="btn btn-primary btn-lg">Login</a>
                <a href="signup.php" class="btn btn-secondary btn-lg">Register</a>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/petAjax.js"></script>
    <script src="assets/js/common.js"></script>
</body>

</html>