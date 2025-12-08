<?php
require_once '../../database.php';
session_start();

header('Content-Type: application/json');

$post_id = $_GET['post_id'] ?? null;

if (!$post_id) {
    echo json_encode(["success" => false, "message" => "No post id"]);
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT c.id, c.comment, c.user_id, c.created_at,
               u.name AS user_name
        FROM pet_post_comments c
        INNER JOIN users u ON c.user_id = u.id
        WHERE c.post_id = ?
        ORDER BY c.created_at DESC
    ");
    $stmt->execute([$post_id]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "comments" => $comments,
        "current_user" => $_SESSION['user_id'] ?? 0
    ]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
