<?php




require_once '../includes/session_check.php';

// 2. GỌI HÀM KIỂM TRA ĐĂNG NHẬP
requireLogin();

require_once '../includes/db.php';

$article_id = $_GET['id'] ?? null;
if (!$article_id || !is_numeric($article_id)) {
    die("ID bài viết không hợp lệ.");
}



$stmt = $conn->prepare("SELECT * FROM articles WHERE id = ?"); 
$stmt->bind_param("i", $article_id);
$stmt->execute();
$result = $stmt->get_result();
$article = $result->fetch_assoc();
$stmt->close();

if (!$article) {
    die("Không tìm thấy bài viết.");
}

// Kiểm tra quyền sửa
if (!isAdmin() && $article['user_id'] != $_SESSION['user_id']) {
    die("Bạn không có quyền sửa bài viết này.");
}

// Lấy danh sách categories để tạo dropdown
$categories_result = $conn->query("SELECT id, name FROM categories ORDER BY name ASC");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa bài viết</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(to right, #e0eafc, #cfdef3);
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            font-weight: 600;
            display: block;
            margin-bottom: 8px;
            color: #333;
        }
        input[type="text"],
        input[type="file"],
        select,
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        input:focus,
        select:focus,
        textarea:focus {
            border-color: #66afe9;
            outline: none;
        }
        textarea {
            resize: vertical;
            min-height: 200px;
        }
        button {
            background-color: #007bff;
            color: #fff;
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #0056b3;
        }
        .current-image {
            margin-top: 10px;
            max-width: 250px;
            height: auto;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        .note {
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Sửa Bài Viết</h1>
    <form action="update_post.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="article_id" value="<?= $article['id'] ?>">

        <div class="form-group">
            <label for="title">Tiêu đề</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($article['title']) ?>" required>
        </div>

        <div class="form-group">
            <label for="category_id">Danh mục</label>
            <select id="category_id" name="category_id" required>
                <?php while ($category = $categories_result->fetch_assoc()): ?>
                    <option value="<?= $category['id'] ?>" <?= $category['id'] == $article['category_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($category['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="featured_image">Ảnh đại diện mới</label>
            <input type="file" id="featured_image" name="featured_image" accept="image/*">
            <?php if (!empty($article['featured_image'])): ?>
                <p class="note">Ảnh hiện tại:</p>
                <img src="../<?= htmlspecialchars($article['featured_image']) ?>" alt="Ảnh hiện tại" class="current-image">
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="content">Nội dung</label>
            <textarea id="content" name="content" required><?= htmlspecialchars($article['content']) ?></textarea>
        </div>

        <div class="form-group">
            <label for="attachments">Tệp đính kèm</label>
            <input type="file" id="attachments" name="attachments[]" multiple>
            <p class="note">Giữ Ctrl (Windows) hoặc Command (Mac) để chọn nhiều tệp.</p>
        </div>

        <div class="form-group" style="text-align: center;">
            <button type="submit">Cập nhật bài viết</button>
        </div>
    </form>
</div>
</body>
</html>
