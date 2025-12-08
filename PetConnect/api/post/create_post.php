<?php
session_start();
header("Content-Type: application/json; charset=utf-8");

require_once "../../database.php";

if (!isset($_SESSION["user_id"])) {
    echo json_encode(["success" => false, "message" => "You must be logged in."]);
    exit;
}

$user_id = intval($_SESSION["user_id"]);
$pet_id  = intval($_POST["pet_id"] ?? 0);
$title = trim($_POST["title"] ?? "");
$content = trim($_POST["content"] ?? "");

if ($pet_id <= 0) {
    echo json_encode(["success" => false, "message" => "Missing pet."]);
    exit;
}
if ($content === "") {
    echo json_encode(["success" => false, "message" => "Content is required."]);
    exit;
}
if ($title === "") {
    echo json_encode(["success" => false, "message" => "Title is required."]);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT user_id FROM pets WHERE id = ?");
    $stmt->execute([$pet_id]);
    $pet = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pet) {
        echo json_encode(["success" => false, "message" => "Pet not found."]);
        exit;
    }
    if (intval($pet["user_id"]) !== $user_id) {
        echo json_encode(["success" => false, "message" => "You are not allowed to post for this pet."]);
        exit;
    }

    $pdo->beginTransaction();

    $stmt = $pdo->prepare("INSERT INTO pet_posts (pet_id, title, content, created_at) VALUES (?, ?, ?, CURRENT_TIMESTAMP())");
    $stmt->execute([$pet_id, $title, $content]);
    $post_id = $pdo->lastInsertId();

    if (!empty($_FILES["image"]["name"]) && isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
        // 1) проверка дали е изображение
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            $pdo->rollBack();
            echo json_encode(["success" => false, "message" => "Uploaded file is not a valid image"]);
            exit;
        }

        $allowedMime = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        if (!in_array($_FILES["image"]["type"], $allowedMime)) {
            $pdo->rollBack();
            echo json_encode(["success" => false, "message" => "Invalid image format. Allowed: JPG, PNG, WEBP, GIF."]);
            exit;
        }

        $uploadDir = __DIR__ . "/../../uploads/posts/";
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
                $pdo->rollBack();
                echo json_encode(["success" => false, "message" => "Failed to create upload directory."]);
                exit;
            }
        }

        $ext = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $allowedExt = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        if (!in_array($ext, $allowedExt)) {
            $pdo->rollBack();
            echo json_encode(["success" => false, "message" => "Invalid image extension."]);
            exit;
        }

        $filename = uniqid("post_", true) . "." . $ext;
        $destination = $uploadDir . $filename;

        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $destination)) {
            $pdo->rollBack();
            echo json_encode(["success" => false, "message" => "Failed to move uploaded file."]);
            exit;
        }

        $db_path = "uploads/posts/" . $filename;

        $stmtImg = $pdo->prepare("INSERT INTO pet_images (path, post_id) VALUES (?, ?)");
        $stmtImg->execute([$db_path, $post_id]);
    }

    $pdo->commit();

    echo json_encode(["success" => true, "message" => "Post created successfully!", "post_id" => $post_id]);
} catch (Exception $ex) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    
    error_log("create_post error: " . $ex->getMessage());
    echo json_encode(["success" => false, "message" => "Server error: could not create post."]);
}
