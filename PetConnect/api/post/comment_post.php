<?php
require_once '../../database.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "success" => false,
        "message" => "You must be logged in to comment."
    ]);
    exit;
}

$user_id = $_SESSION['user_id'];
$input = json_decode(file_get_contents('php://input'), true);

$post_id = $input['post_id'] ?? null;
$comment = trim($input['comment'] ?? '');

if (!$post_id || !$comment) {
    echo json_encode([
        "success" => false,
        "message" => "Post ID and comment are required."
    ]);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO pet_post_comments (post_id, user_id, comment) VALUES (?, ?, ?)");
    $stmt->execute([$post_id, $user_id, $comment]);

    echo json_encode([
        "success" => true,
        "message" => "Comment added successfully."
    ]);
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
