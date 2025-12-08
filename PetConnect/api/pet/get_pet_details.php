<?php
session_start();
require_once "../../database.php";

header("Content-Type: application/json; charset=utf-8");

if (!isset($_GET["pet_id"])) {
    echo json_encode(["success" => false, "message" => "Missing pet_id"]);
    exit;
}

$petId = intval($_GET["pet_id"]);
$userId = isset($_SESSION["user_id"]) ? intval($_SESSION["user_id"]) : 0;

// Зареждаме детайлите на животното
$stmt = $pdo->prepare("
    SELECT 
        p.id,
        p.user_id,
        p.type_id,
        t.name AS type_name,
        p.name,
        p.age,
        p.description,
        p.image_id,
        i.path AS image_path,
        u.name AS owner_name,
        u.email AS owner_email
    FROM pets p
    JOIN pet_types t ON t.id = p.type_id
    JOIN pet_images i ON i.id = p.image_id
    JOIN users u ON u.id = p.user_id
    WHERE p.id = ?
");
$stmt->execute([$petId]);
$pet = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pet) {
    echo json_encode(["success" => false, "message" => "Pet not found"]);
    exit;
}

$isOwner = ($userId > 0 && $userId === intval($pet["user_id"]));

// Зареждаме постовете с лайкове и коментари в една заявка
$stmtPosts = $pdo->prepare("
    SELECT 
        pp.id,
        pp.title,
        pp.content,
        pp.created_at,
        pi.path AS image_path,
        COALESCE(l.like_count, 0) AS like_count,
        COALESCE(l.is_liked, 0) AS is_liked,
        COALESCE(c.comments_json, '[]') AS comments_json
    FROM pet_posts pp
    LEFT JOIN pet_images pi ON pi.post_id = pp.id
    LEFT JOIN (
        SELECT post_id, COUNT(*) AS like_count, MAX(user_id = :userId) AS is_liked
        FROM pet_post_likes
        GROUP BY post_id
    ) l ON l.post_id = pp.id
    LEFT JOIN (
        SELECT c.post_id, CONCAT('[', GROUP_CONCAT(
            JSON_OBJECT(
                'id', c.id,
                'user_id', c.user_id,
                'user_name', u.name,
                'comment', c.comment,
                'created_at', c.created_at
            )
        ), ']') AS comments_json
        FROM pet_post_comments c
        JOIN users u ON u.id = c.user_id
        GROUP BY c.post_id
    ) c ON c.post_id = pp.id
    WHERE pp.pet_id = :petId
    ORDER BY pp.created_at DESC
");
$stmtPosts->execute(['petId' => $petId, 'userId' => $userId]);
$postsRaw = $stmtPosts->fetchAll(PDO::FETCH_ASSOC);

// Преобразуваме JSON колоната в масив
$posts = array_map(function ($post) {
    $post['comments'] = json_decode($post['comments_json'], true);
    unset($post['comments_json']);
    $post['like_count'] = (int)$post['like_count'];
    $post['is_liked'] = (bool)$post['is_liked'];
    return $post;
}, $postsRaw);

echo json_encode([
    "success" => true,
    "pet" => $pet,
    "isOwner" => $isOwner,
    "isLogged" => $userId > 0,
    "posts" => $posts
]);
