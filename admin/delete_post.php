<?php


require_once '../includes/session_check.php';
requireLogin();
require_once '../includes/db.php';

$article_id = $_GET['id'] ?? null;
if (!$article_id || !is_numeric($article_id)) {
    header("Location: post_management.php?error=invalid_id");
    exit();
}


$stmt_check = $conn->prepare("SELECT user_id, featured_image FROM articles WHERE id = ?");
$stmt_check->bind_param("i", $article_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();
$article = $result_check->fetch_assoc();
$stmt_check->close();

if (!$article) {
    header("Location: post_management.php?error=not_found");
    exit();
}


if (!isAdmin() && $article['user_id'] != $_SESSION['user_id']) {
    header("Location: post_management.php?error=permission_denied");
    exit();
}


if (!empty($article['featured_image']) && file_exists('../' . $article['featured_image'])) {
    unlink('../' . $article['featured_image']);
}


$stmt_files = $conn->prepare("SELECT file_url FROM article_attachments WHERE article_id = ?");
$stmt_files->bind_param("i", $article_id);
$stmt_files->execute();
$result_files = $stmt_files->get_result();
while ($file = $result_files->fetch_assoc()) {
    if (!empty($file['file_url']) && file_exists('../' . $file['file_url'])) {
        unlink('../' . $file['file_url']);
    }
}
$stmt_files->close();


$stmt_delete = $conn->prepare("DELETE FROM articles WHERE id = ?");
$stmt_delete->bind_param("i", $article_id);

if ($stmt_delete->execute()) {
   
    header("Location: post_management.php?status=deleted_success");
} else {
    
    header("Location: post_management.php?error=delete_failed");
}

$stmt_delete->close();
$conn->close();
exit();
?>