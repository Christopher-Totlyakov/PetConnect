<?php
require_once '../../database.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Not logged"]);
    exit;
}

$input = json_decode(file_get_contents("php://input"), true);
$comment_id = $input["comment_id"] ?? null;

if (!$comment_id) {
    echo json_encode(["success" => false, "message" => "Missing id"]);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT user_id FROM pet_post_comments WHERE id = ?");
    $stmt->execute([$comment_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row || $row["user_id"] != $_SESSION["user_id"]) {
        echo json_encode(["success" => false, "message" => "Not allowed"]);
        exit;
    }

    $del = $pdo->prepare("DELETE FROM pet_post_comments WHERE id = ?");
    $del->execute([$comment_id]);

    echo json_encode(["success" => true, "message" => "Comment deleted"]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
