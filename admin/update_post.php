<?php

require_once '../includes/session_check.php';
requireLogin();


function upload_file($file_info, $upload_dir, $allowed_types) {
    if ($file_info['error'] !== UPLOAD_ERR_OK) return ['success' => false, 'message' => 'Lỗi upload.'];
    $file_name = basename($file_info['name']);
    $file_type = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    if (!in_array($file_type, $allowed_types)) return ['success' => false, 'message' => 'Định dạng file không được phép.'];
    $unique_filename = uniqid(pathinfo($file_name, PATHINFO_FILENAME) . '_', true) . '.' . $file_type;
    $target_path = '../uploads/' . basename($upload_dir) . '/' . $unique_filename;
    if (move_uploaded_file($file_info['tmp_name'], $target_path)) {
        return ['success' => true, 'path' => 'uploads/' . basename($upload_dir) . '/' . $unique_filename];
    }
    return ['success' => false, 'message' => 'Không thể di chuyển file.'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $article_id = $_POST['article_id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category_id = $_POST['category_id'];

    
    $stmt_check = $conn->prepare("SELECT user_id, featured_image FROM articles WHERE id = ?");
    $stmt_check->bind_param("i", $article_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    $article_old = $result_check->fetch_assoc();
    $stmt_check->close();

    if (!$article_old) die("Bài viết không tồn tại.");
    if (!isAdmin() && $article_old['user_id'] != $_SESSION['user_id']) die("Không có quyền sửa bài viết này.");

    
    $featured_image_url = $article_old['featured_image']; 
    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] == 0) {
        $allowed_image_types = ['jpg', 'jpeg', 'png', 'gif'];
        $upload_result = upload_file($_FILES['featured_image'], 'images', $allowed_image_types);
        
        if ($upload_result['success']) {
            
            if (!empty($featured_image_url) && file_exists('../' . $featured_image_url)) {
                unlink('../' . $featured_image_url);
            }
            $featured_image_url = $upload_result['path']; 
        } else {
            die("Lỗi upload ảnh mới: " . $upload_result['message']);
        }
    }

    
    $stmt_update = $conn->prepare("UPDATE articles SET title = ?, content = ?, category_id = ?, featured_image = ? WHERE id = ?");
    $stmt_update->bind_param("ssisi", $title, $content, $category_id, $featured_image_url, $article_id);

    if ($stmt_update->execute()) {
        header("Location: post_management.php?status=updated_success");
    } else {
        echo "Lỗi khi cập nhật: " . $stmt_update->error;
    }

    $stmt_update->close();
    $conn->close();
    exit();
}
?>