<?php

header('Content-Type: application/json');
require_once '../config.php'; 


if (!isLoggedIn()) {
    echo json_encode(['error' => 'Chưa đăng nhập']);
    exit();
}


$article_id = $_GET['id'] ?? null;
if (!$article_id || !is_numeric($article_id)) {
    echo json_encode(['error' => 'ID không hợp lệ']);
    exit();
}

$response = [];


$stmt_article = $conn->prepare("SELECT title, featured_image, user_id FROM articles WHERE id = ?");
$stmt_article->bind_param("i", $article_id);
$stmt_article->execute();
$result_article = $stmt_article->get_result();
$article = $result_article->fetch_assoc();

if (!$article) {
    echo json_encode(['error' => 'Không tìm thấy bài viết']);
    exit();
}

if (!isAdmin() && $article['user_id'] != $_SESSION['user_id']) {
    echo json_encode(['error' => 'Không có quyền truy cập']);
    exit();
}

$response['article_title'] = $article['title'];
$response['featured_image'] = $article['featured_image'];



$stmt_attachments = $conn->prepare("SELECT file_name, file_url FROM article_attachments WHERE article_id = ?");
$stmt_attachments->bind_param("i", $article_id);
$stmt_attachments->execute();
$result_attachments = $stmt_attachments->get_result();

$attachments = [];
while ($row = $result_attachments->fetch_assoc()) {
    $attachments[] = $row;
}
$response['attachments'] = $attachments;



echo json_encode($response);

$stmt_article->close();
$stmt_attachments->close();
$conn->close();
?>