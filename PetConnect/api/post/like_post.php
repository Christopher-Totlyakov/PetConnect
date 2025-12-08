<?php
require_once '../../database.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "success" => false,
        "message" => "You must be logged in to like posts."
    ]);
    exit;
}

$user_id = $_SESSION['user_id'];

$input = json_decode(file_get_contents('php://input'), true);
$post_id = $input['post_id'] ?? null;

if (!$post_id) {
    echo json_encode([
        "success" => false,
        "message" => "Post ID is required."
    ]);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id FROM pet_post_likes WHERE post_id = ? AND user_id = ?");
    $stmt->execute([$post_id, $user_id]);
    $like = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($like) {
        $stmt = $pdo->prepare("DELETE FROM pet_post_likes WHERE id = ?");
        $stmt->execute([$like['id']]);
        $message = "Post unliked.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO pet_post_likes (post_id, user_id) VALUES (?, ?)");
        $stmt->execute([$post_id, $user_id]);
        $message = "Post liked.";
    }

    $stmt = $pdo->prepare("SELECT COUNT(*) AS count FROM pet_post_likes WHERE post_id = ?");
    $stmt->execute([$post_id]);
    $likes_count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

    echo json_encode([
        "success" => true,
        "message" => $message,
        "likes_count" => $likes_count
    ]);
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
