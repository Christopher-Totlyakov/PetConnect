<?php
session_start();
$isLogged = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>All Pet Posts - 🐾 PetConnect</title>
    <link rel="icon" type="image/png" href="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSXNVYInU6n-54guKmWlNqLHhMtasX6o6wAgQ&s">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/pet_post.css">

    <script>
        const isLogged = <?= $isLogged ? 'true' : 'false'; ?>;
    </script>
</head>

<body>
    <?php include 'common/nav.php'; ?>

    <div class="container" id="all-posts-container">
        <h2>Loading posts...</h2>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/postAjax.js"></script>
    <script src="assets/js/common.js"></script>
</body>

</html>