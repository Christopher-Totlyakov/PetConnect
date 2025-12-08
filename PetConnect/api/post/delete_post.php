<?php
session_start();
require_once "../../database.php";

header("Content-Type: application/json; charset=utf-8");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["post_id"])) {
    echo json_encode(["success" => false, "message" => "Missing post_id"]);
    exit;
}

$postId = intval($data["post_id"]);
$userId = $_SESSION["user_id"] ?? 0;

$stmt = $pdo->prepare("
    SELECT p.id, pets.user_id AS owner_id
    FROM pet_posts p
    JOIN pets ON pets.id = p.pet_id
    WHERE p.id = ?
");
$stmt->execute([$postId]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    echo json_encode(["success" => false, "message" => "Post not found"]);
    exit;
}

if ($post["owner_id"] != $userId) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

$pdo->prepare("DELETE FROM pet_images WHERE post_id = ?")->execute([$postId]);

$pdo->prepare("DELETE FROM pet_posts WHERE id = ?")->execute([$postId]);

echo json_encode(["success" => true]);
