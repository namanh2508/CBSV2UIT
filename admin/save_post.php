<?php

include '../includes/session_check.php';
requireLogin();
include '../includes/db.php';


function upload_file($file_info, $upload_dir, $allowed_types) {
    
    if ($file_info['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'Lỗi upload file. Mã lỗi: ' . $file_info['error']];
    }

    $file_name = basename($file_info['name']);
    $file_type = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    
    if (!in_array($file_type, $allowed_types)) {
        return ['success' => false, 'message' => 'Định dạng file không được phép. Chỉ chấp nhận: ' . implode(', ', $allowed_types)];
    }

    
    $unique_filename = uniqid(pathinfo($file_name, PATHINFO_FILENAME) . '_', true) . '.' . $file_type;
    $target_path = $upload_dir . $unique_filename;

    if (move_uploaded_file($file_info['tmp_name'], $target_path)) {
        return ['success' => true, 'path' => 'uploads/' . basename($upload_dir) . '/' . $unique_filename];
    } else {
        return ['success' => false, 'message' => 'Không thể di chuyển file đã upload.'];
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $conn->begin_transaction();

    try {
        
        $title = $_POST['title'];
        $content = $_POST['content'];
        $category_id = $_POST['category_id'];
        $user_id = $_SESSION['user_id'];
        $featured_image_url = NULL;

        
        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] == 0) {
            $allowed_image_types = ['jpg', 'jpeg', 'png', 'gif'];
            $upload_result = upload_file($_FILES['featured_image'], '../uploads/images/', $allowed_image_types);
            if ($upload_result['success']) {
                $featured_image_url = $upload_result['path'];
            } else {
                throw new Exception("Lỗi ảnh đại diện: " . $upload_result['message']);
            }
        }

        
        $stmt_article = $conn->prepare("INSERT INTO articles (title, content, user_id, category_id, featured_image) VALUES (?, ?, ?, ?, ?)");
        $stmt_article->bind_param("ssiis", $title, $content, $user_id, $category_id, $featured_image_url);
        if (!$stmt_article->execute()) {
            throw new Exception("Lỗi khi lưu bài viết: " . $stmt_article->error);
        }
        $article_id = $stmt_article->insert_id; 
        $stmt_article->close();
        
        
        if (isset($_FILES['attachments'])) {
            $allowed_file_types = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'zip', 'rar'];
            $attachment_files = $_FILES['attachments'];

            
            $stmt_attachment = $conn->prepare("INSERT INTO article_attachments (article_id, file_name, file_url) VALUES (?, ?, ?)");

            
            foreach ($attachment_files['name'] as $key => $name) {
                if ($attachment_files['error'][$key] === UPLOAD_ERR_OK) {
                    $file_info = [
                        'name' => $attachment_files['name'][$key],
                        'type' => $attachment_files['type'][$key],
                        'tmp_name' => $attachment_files['tmp_name'][$key],
                        'error' => $attachment_files['error'][$key],
                        'size' => $attachment_files['size'][$key]
                    ];
                    
                    $upload_result = upload_file($file_info, '../uploads/files/', $allowed_file_types);
                    if ($upload_result['success']) {
                        $file_url = $upload_result['path'];
                        
                        $stmt_attachment->bind_param("iss", $article_id, $name, $file_url);
                        if (!$stmt_attachment->execute()) {
                            throw new Exception("Lỗi khi lưu file đính kèm: " . $stmt_attachment->error);
                        }
                    } else {
                        
                        throw new Exception("Lỗi file đính kèm (" . htmlspecialchars($name) . "): " . $upload_result['message']);
                    }
                }
            }
            $stmt_attachment->close();
        }

        
        $conn->commit();
        echo "Bài viết và các tệp đính kèm đã được đăng thành công!";
        echo '<br><a href="add_post.php">Thêm bài viết khác</a>';
        echo '<br><a href="post_management.php">Về trang quản lý</a>';

    } catch (Exception $e) {
        
        $conn->rollback();
        echo "Đã xảy ra lỗi nghiêm trọng: " . $e->getMessage();
    }

    $conn->close();
}
?>