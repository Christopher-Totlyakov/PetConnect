<?php
session_start();
$petId = $_GET['pet_id'] ?? 0;

require_once 'database.php';
$stmt = $pdo->query("SELECT id, name FROM pet_types ORDER BY name");
$petTypes = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <title>Edit Pet - 🐾 PetConnect</title>
    <link rel="icon" type="image/png" href="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSXNVYInU6n-54guKmWlNqLHhMtasX6o6wAgQ&s">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <?php include 'common/nav.php'; ?>

    <div class="container">
        <h2 class="text-center my-3">Edit Pet</h2>
        <form id="pet-edit-form" enctype="multipart/form-data" class="mx-auto" style="max-width: 500px;">
            <input type="hidden" name="pet_id" value="<?= htmlspecialchars($petId) ?>">

            <div class="form-group mb-3">
                <label for="pet-name">Name</label>
                <input type="text" class="form-control" id="pet-name" name="name" value="">
            </div>

            <div class="form-group mb-3">
                <label for="pet-type">Type</label>
                <select class="form-control" id="pet-type" name="type_id">
                    <option value="">Select pet type</option>
                    <?php foreach ($petTypes as $type): ?>
                        <option value="<?= htmlspecialchars($type['id']) ?>"><?= htmlspecialchars($type['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="pet-age">Age</label>
                <input type="number" class="form-control" id="pet-age" name="age" value="">
            </div>

            <div class="form-group mb-3">
                <label for="pet-description">Description</label>
                <textarea class="form-control" id="pet-description" name="description" rows="3"></textarea>
            </div>

            <div class="form-group mb-3">
                <label for="pet-image">Image</label>
                <input type="file" class="form-control-file" id="pet-image" name="image">
                <img id="pet-current-image" src="" width="150" class="mt-2" style="display:none;">
            </div>

            <button type="submit" class="btn btn-primary btn-block">Save</button>
        </form>
    </div>



    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/petAjax.js"></script>
    <script src="assets/js/common.js"></script>
</body>

</html>