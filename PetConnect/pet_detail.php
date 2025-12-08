<?php
$petId = $_GET['pet_id'] ?? 0;

session_start();
$isLogged = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Pet Detail - 🐾 PetConnect</title>
    <link rel="icon" type="image/png" href="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSXNVYInU6n-54guKmWlNqLHhMtasX6o6wAgQ&s">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/pet_detail.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <?php include 'common/nav.php'; ?>

    <div class="container mb-5">
        <div id="pet-detail-container" data-pet-id="<?= htmlspecialchars($petId) ?>"
            class="shadow-sm bg-white p-4 rounded">
            <h2 class="text-center text-muted">Loading pet details...</h2>
        </div>

        <div id="pet-posts-container" class="mt-4"></div>
    </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/petAjax.js"></script>
</body>

</html>