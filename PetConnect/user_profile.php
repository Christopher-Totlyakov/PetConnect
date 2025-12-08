<?php
session_start();

$isLogged = isset($_SESSION['user_id']);

if (!$isLogged) {
    header("Location: login.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>User Profile - 🐾 PetConnect</title>
    <link rel="icon" type="image/png" href="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSXNVYInU6n-54guKmWlNqLHhMtasX6o6wAgQ&s">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <?php include 'common/nav.php'; ?>

    <div class="container">
        <h1>Your Profile</h1>

        <div id="user-info" class="mb-4">
            <p>Loading user information...</p>
        </div>

        <h2>Your Pets</h2>
        <div class="row" id="user-pets-container">
            <p>Loading pets...</p>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/userAjax.js"></script>
    <script src="assets/js/common.js"></script>
</body>

</html>