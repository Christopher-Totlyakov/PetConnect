<?php
require_once '../../database.php';
session_start();

function e($v)
{
    return htmlspecialchars($v ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

$sql = "
    SELECT 
        p.id, p.user_id, p.type_id, p.name AS pet_name,
        p.age, p.description, p.created_at,
        t.name AS type_name,
        pi.path AS image_path,
        u.name AS owner_name
    FROM pets p
    LEFT JOIN pet_types t ON p.type_id = t.id
    LEFT JOIN pet_images pi ON p.image_id = pi.id
    LEFT JOIN users u ON p.user_id = u.id
    ORDER BY p.created_at DESC
";

$stmt = $pdo->query($sql);
$pets = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    "success" => true,
    "pets" => $pets
]);
