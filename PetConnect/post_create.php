<?php
session_start();

$isLogged = isset($_SESSION['user_id']);

if (!$isLogged) {
    header("Location: login.php");
    exit;
}

$pet_id = $_GET["pet_id"] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Create Post</title>
    <link rel="icon" type="image/png" href="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSXNVYInU6n-54guKmWlNqLHhMtasX6o6wAgQ&s">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="bg-light">
    <?php include 'common/nav.php'; ?>

    <div class="container mt-4">
        <h2>Create a Post for Your Pet</h2>

        <form id="postCreateForm">

            <input type="hidden" name="pet_id" value="<?= $pet_id ?>">

            <div class="form-group mt-3">
                <label>Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>

            <div class="form-group mt-3">
                <label>Content</label>
                <textarea name="content" class="form-control" rows="6" required></textarea>
            </div>

            <div class="form-group mt-3">
                <label>Image (optional)</label>
                <input type="file" name="image" class="form-control">
            </div>

            <button class="btn btn-success mt-4">Create</button>

        </form>

        <div id="postCreateMessage" class="mt-3"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/common.js"></script>
    <script src="assets/js/postAjax.js"></script>

</body>

</html>