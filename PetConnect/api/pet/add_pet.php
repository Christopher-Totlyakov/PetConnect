<?php
session_start();
require_once "../../database.php";

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];

$name = $_POST['name'] ?? '';
$type_id = $_POST['type'] ?? '';
$age = $_POST['age'] ?? null;
$description = $_POST['description'] ?? '';

if (!$name || !$type_id) {
    echo json_encode(['success' => false, 'message' => 'Name and type are required']);
    exit;
}

if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'Image is required']);
    exit;
}

$check = getimagesize($_FILES['image']['tmp_name']);
if ($check === false) {
    echo json_encode(['success' => false, 'message' => 'Uploaded file is not a valid image']);
    exit;
}

try {
    $pdo->beginTransaction();

    $uploadDir = dirname(__DIR__, 2) . '/uploads/imgPets/';

    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '.' . $ext;
    $destination = $uploadDir . $filename;

    if (!move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
        throw new Exception('Failed to upload image');
    }

    $image_path = 'uploads/imgPets/' . $filename; 
    
    $stmtImg = $pdo->prepare("INSERT INTO pet_images (path) VALUES (?)");
    $stmtImg->execute([$image_path]);
    $image_id = $pdo->lastInsertId();

    $stmtPet = $pdo->prepare("
        INSERT INTO pets (user_id, type_id, name, age, description, image_id) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmtPet->execute([$user_id, $type_id, $name, $age, $description, $image_id]);

    $pdo->commit();

    echo json_encode(['success' => true, 'message' => 'Pet created successfully']);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
