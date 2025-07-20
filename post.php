<?php
// post.php
include 'includes/db.php';

// Kiểm tra xem có id bài viết được truyền qua URL không
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php'); // Chuyển hướng về trang chủ nếu id không hợp lệ
    exit();
}

$post_id = $_GET['id'];

// Sử dụng Prepared Statements để chống SQL Injection
$stmt = $conn->prepare("SELECT title, content, created_at FROM articles WHERE id = ?");
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Không tìm thấy bài viết.";
    exit();
}

$post = $result->fetch_assoc();

include 'includes/header.php';
?>

<h1><?php echo htmlspecialchars($post['title']); ?></h1>
<p><small>Đăng ngày: <?php echo date("d/m/Y", strtotime($post['created_at'])); ?></small></p>
<hr>
<div>
    <?php echo nl2br($post['content']);  ?>
</div>

<?php
$stmt->close();
$conn->close();
include 'includes/footer.php';
?>