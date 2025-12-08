<?php
require_once 'database.php';
session_start();

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
    <title>Add Pet - 🐾 PetConnect</title>
    <link rel="icon" type="image/png" href="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSXNVYInU6n-54guKmWlNqLHhMtasX6o6wAgQ&s">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <?php include 'common/nav.php'; ?>

    <div class="container">
        <h1 class="text-center">Add New Pet Photo</h1>
        <div class="row">
            <div class="col-lg-3"></div>
            <div class="col-lg-6">
                <form id="pet-create-form" enctype="multipart/form-data">
                    <input name="name" class="form-control mb-2" placeholder="Pet name" required>

                    <select name="type" class="form-control mb-2" required>
                        <option value="">Select pet type</option>
                        <?php foreach ($petTypes as $type): ?>
                            <option value="<?= htmlspecialchars($type['id']) ?>"><?= htmlspecialchars($type['name']) ?></option>
                        <?php endforeach; ?>
                    </select>

                    <input name="age" type="number" class="form-control mb-2" placeholder="Age">
                    <textarea name="description" class="form-control mb-2" placeholder="Description"></textarea>

                    <div class="form-group">
                        <label>Image</label>
                        <input type="file" name="image" class="form-control-file">
                    </div>

                    <button type="submit" id="pet-create-submit" class="btn btn-primary mt-2">Create</button>
                </form>
            </div>
            <div class="col-lg-3"></div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/petAjax.js"></script>
    <script src="assets/js/common.js"></script>
</body>

</html>