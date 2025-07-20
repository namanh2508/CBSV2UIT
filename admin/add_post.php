<?php
// admin/add_post.php
include '../includes/session_check.php';
requireLogin();
include '../includes/db.php';

$categories_result = $conn->query("SELECT id, name FROM categories ORDER BY name ASC");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Thêm bài viết mới</title>
    <style> 
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; }
        .container { max-width: 800px; margin: 20px auto; background: white; padding: 20px; border-radius: 8px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 5px; }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;
        }
        
        .form-group input[type="file"] { padding: 3px; }
        .form-group button { padding: 10px 20px; background-color: #d90429; color: white; border: none; cursor: pointer; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Thêm bài viết mới</h1>
        
        <form action="save_post.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Tiêu đề:</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="category_id">Loại bài viết:</label>
                <select id="category_id" name="category_id" required>
                    <option value="">-- Vui lòng chọn --</option>
                    <?php
                    if ($categories_result->num_rows > 0) {
                        while($category = $categories_result->fetch_assoc()) {
                            echo '<option value="' . $category['id'] . '">' . htmlspecialchars($category['name']) . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            
            <div class="form-group">
                <label for="featured_image">Ảnh đại diện (tùy chọn):</label>
                <input type="file" id="featured_image" name="featured_image" accept="image/*">
            </div>

            <div class="form-group">
                <label for="content">Nội dung:</label>
                <textarea id="content" name="content" rows="15" required></textarea>
            </div>

            
            <div class="form-group">
                <label for="attachments">Tệp đính kèm (giữ Ctrl để chọn nhiều file):</label>
                <input type="file" id="attachments" name="attachments[]" multiple>
            </div>

            <div class="form-group">
                <button type="submit">Đăng bài</button>
            </div>
        </form>
    </div>
</body>
</html>
<?php $conn->close(); ?>