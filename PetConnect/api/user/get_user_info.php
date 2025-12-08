<?php
session_start();
require_once "../../database.php";

header("Content-Type: application/json; charset=utf-8");

$userId = $_SESSION['user_id'] ?? 0;
if (!$userId) {
    echo json_encode(["success" => false, "message" => "Not logged in"]);
    exit;
}

$stmt = $pdo->prepare("SELECT id, name, email, created_at FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo json_encode(["success" => false, "message" => "User not found"]);
    exit;
}

$stmt = $pdo->prepare("
    SELECT pets.id, pets.name, pets.age, pets.description, pets.type_id, pet_types.name AS type_name, pet_images.path AS image_path
    FROM pets
    LEFT JOIN pet_types ON pets.type_id = pet_types.id
    LEFT JOIN pet_images ON pets.image_id = pet_images.id
    WHERE pets.user_id = ?
    ORDER BY pets.created_at DESC
");
$stmt->execute([$userId]);
$pets = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    "success" => true,
    "user" => $user,
    "pets" => $pets
]);
