<?php
require_once '../../database.php';
session_start();

header('Content-Type: application/json');

try {
    $sql = "
        SELECT 
            pp.id,
            pp.pet_id,
            pp.title,
            pp.content,
            pp.created_at,
            pets.name AS pet_name,
            u.name AS owner_name,
            pi.path AS image_path,
            COALESCE(like_counts.count, 0) AS likes_count
        FROM pet_posts pp
        INNER JOIN pets ON pp.pet_id = pets.id
        INNER JOIN users u ON pets.user_id = u.id
        LEFT JOIN pet_images pi ON pi.post_id = pp.id
        LEFT JOIN (
            SELECT post_id, COUNT(*) AS count
            FROM pet_post_likes
            GROUP BY post_id
        ) like_counts ON pp.id = like_counts.post_id
        ORDER BY pp.created_at DESC
    ";

    $stmt = $pdo->query($sql);
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "posts" => $posts
    ]);
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
