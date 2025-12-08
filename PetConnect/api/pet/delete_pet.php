<?php
session_start();
require_once "../../database.php";

header("Content-Type: application/json; charset=utf-8");

$userId = $_SESSION["user_id"] ?? 0;
$petId = intval($_POST["pet_id"] ?? 0);

if (!$petId) {
    echo json_encode(["success" => false, "message" => "Invalid pet ID"]);
    exit;
}

$stmt = $pdo->prepare("SELECT user_id FROM pets WHERE id = ?");
$stmt->execute([$petId]);
$pet = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pet) {
    echo json_encode(["success" => false, "message" => "Pet not found"]);
    exit;
}

if ($pet["user_id"] != $userId) {
    echo json_encode(["success" => false, "message" => "You are not authorized to delete this pet"]);
    exit;
}

$stmt = $pdo->prepare("DELETE FROM pets WHERE id = ?");
$stmt->execute([$petId]);

echo json_encode(["success" => true, "message" => "Pet deleted successfully"]);
