<?php
session_start();
require_once "../../database.php";

header("Content-Type: application/json; charset=utf-8");

$userId = isset($_SESSION["user_id"]) ? intval($_SESSION["user_id"]) : 0;

$petId = intval($_POST["pet_id"] ?? 0);
$name = trim($_POST["name"] ?? "");
$typeId = intval($_POST["type_id"] ?? 0);
$age = intval($_POST["age"] ?? 0);
$description = trim($_POST["description"] ?? "");

if (!$petId || !$name || !$typeId) {
    echo json_encode(["success" => false, "message" => "Missing required fields"]);
    exit;
}

$stmt = $pdo->prepare("SELECT user_id FROM pets WHERE id=?");
$stmt->execute([$petId]);
$pet = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pet) {
    echo json_encode(["success" => false, "message" => "Pet not found"]);
    exit;
}

if ($pet["user_id"] != $userId) {
    echo json_encode(["success" => false, "message" => "Not authorized"]);
    exit;
}

$imageId = null;
if (isset($_FILES["image"]) && $_FILES["image"]["error"] === 0) {

    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'message' => 'Image upload error']);
        exit;
    }

    $uploadDir = dirname(__DIR__, 2) . "/uploads/imgPets/";

    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

    $ext = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
    $filename = uniqid() . "." . $ext;
    $destination = $uploadDir . $filename;

    if (!move_uploaded_file($_FILES["image"]["tmp_name"], $destination)) {
        echo json_encode(["success" => false, "message" => "Failed to upload image"]);
        exit;
    }

    $dbPath = "uploads/imgPets/" . $filename;

    $stmt = $pdo->prepare("INSERT INTO pet_images (path) VALUES (?)");
    $stmt->execute([$dbPath]);
    $imageId = $pdo->lastInsertId();
}

$sql = "UPDATE pets SET name=?, type_id=?, age=?, description=?";
$params = [$name, $typeId, $age, $description];

if ($imageId) {
    $sql .= ", image_id=?";
    $params[] = $imageId;
}

$sql .= " WHERE id=?";
$params[] = $petId;

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

echo json_encode(["success" => true, "message" => "Pet updated successfully"]);
