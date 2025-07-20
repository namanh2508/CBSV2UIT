<?php


require_once '../includes/session_check.php';
requireLogin();
require_once '../includes/db.php';


$article_id = $_GET['id'] ?? null;
if (!$article_id || !is_numeric($article_id)) { die("ID bài viết không hợp lệ."); }
$sql_article = "SELECT a.*, u.fullname AS author_name, c.name AS category_name FROM articles a LEFT JOIN users u ON a.user_id = u.id LEFT JOIN categories c ON a.category_id = c.id WHERE a.id = ?";
$stmt_article = $conn->prepare($sql_article);
$stmt_article->bind_param("i", $article_id);
$stmt_article->execute();
$result_article = $stmt_article->get_result();
$article = $result_article->fetch_assoc();
if (!$article) { die("Không tìm thấy bài viết."); }
if (!isAdmin() && $article['user_id'] != $_SESSION['user_id']) { die("Bạn không có quyền xem bài viết này."); }
$sql_attachments = "SELECT * FROM article_attachments WHERE article_id = ?";
$stmt_attachments = $conn->prepare($sql_attachments);
$stmt_attachments->bind_param("i", $article_id);
$stmt_attachments->execute();
$result_attachments = $stmt_attachments->get_result();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết bài viết: <?php echo htmlspecialchars($article['title']); ?></title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; color: #333; margin: 0; padding: 0; }
        .container { max-width: 900px; margin: 20px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1, h3 { color: #d90429; }
        h1 { border-bottom: 2px solid #eee; padding-bottom: 10px; }
        .post-meta { color: #666; font-size: 14px; margin-bottom: 20px; }
        .post-meta span { margin-right: 20px; }
        .post-content { line-height: 1.7; margin-top: 20px; }
        .featured-image { max-width: 100%; height: auto; border-radius: 8px; margin-bottom: 20px; border: 1px solid #ddd; }
        .attachments-section { margin-top: 30px; border-top: 2px solid #eee; padding-top: 20px; }
        .attachments-section h2 { font-size: 20px; color: #333; }
        .attachment-list { padding-left: 0; }
        .attachment-list li { list-style: none; padding: 12px; border-bottom: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center; }
        .attachment-list li:last-child { border-bottom: none; }
        .attachment-actions a { text-decoration: none; padding: 6px 12px; border-radius: 4px; color: white; font-size: 13px; margin-left: 10px; transition: opacity 0.2s; cursor: pointer; }
        .attachment-actions a:hover { opacity: 0.8; }
        .btn-view { background-color: #007bff; }
        .btn-download { background-color: #28a745; }
        
        /* --- CSS CHO MODAL XEM FILE --- */
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.7); }
        .modal-content { background-color: #fefefe; margin: 2% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 900px; height: 90%; border-radius: 8px; position: relative; display: flex; flex-direction: column; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 15px; }
        .modal-header h2 { margin: 0; font-size: 20px; }
        .close-btn { color: #aaa; font-size: 30px; font-weight: bold; cursor: pointer; }
        .modal-body { flex-grow: 1; /* Cho phép phần body co giãn chiếm hết không gian còn lại */ }
        .modal-body embed, .modal-body iframe { width: 100%; height: 100%; border: none; }
    </style>
</head>
<body>
    <div class="container">
        <!-- ... (Phần hiển thị tiêu đề, meta, ảnh đại diện, nội dung giữ nguyên) ... -->
        <h1><?php echo htmlspecialchars($article['title']); ?></h1>
        <div class="post-meta">
            <span><strong>Tác giả:</strong> <?php echo htmlspecialchars($article['author_name'] ?? 'N/A'); ?></span>
            <span><strong>Loại:</strong> <?php echo htmlspecialchars($article['category_name'] ?? 'Chưa phân loại'); ?></span>
        </div>
        <?php if (!empty($article['featured_image'])): ?>
            <h3>Ảnh đại diện</h3>
            <img src="../<?php echo htmlspecialchars($article['featured_image']); ?>" alt="Ảnh đại diện" class="featured-image">
        <?php endif; ?>
        <h3>Nội dung bài viết</h3>
        <div class="post-content"><?php echo nl2br(htmlspecialchars($article['content'])); ?></div>
        
        
        <div class="attachments-section">
            <h2>Tệp đính kèm</h2>
            <?php if ($result_attachments->num_rows > 0): ?>
                <ul class="attachment-list">
                    <?php while ($file = $result_attachments->fetch_assoc()): ?>
                        <li>
                            <span>📄 <?php echo htmlspecialchars($file['file_name']); ?></span>
                            <div class="attachment-actions">
                                
                                <a class="btn-view view-file-btn" 
                                   data-url="../<?php echo htmlspecialchars($file['file_url']); ?>" 
                                   data-name="<?php echo htmlspecialchars($file['file_name']); ?>">Xem</a>
                                
                                
                                <a href="../<?php echo htmlspecialchars($file['file_url']); ?>" 
                                   download="<?php echo htmlspecialchars($file['file_name']); ?>" 
                                   class="btn-download">Tải xuống</a>
                            </div>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>Không có tệp đính kèm nào cho bài viết này.</p>
            <?php endif; ?>
        </div>
        
        <br>
        <a href="post_management.php" style="text-decoration: none;">← Quay lại trang quản lý</a>
    </div>

    
    <div id="fileViewerModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalFileName">Tên File</h2>
                <span class="close-btn">×</span>
            </div>
            <div class="modal-body">
                
                <embed id="fileEmbed" src="" type="application/pdf">
            </div>
        </div>
    </div>

    <script>
        
        const modal = document.getElementById('fileViewerModal');
        const modalFileName = document.getElementById('modalFileName');
        const fileEmbed = document.getElementById('fileEmbed');
        const closeBtn = document.querySelector('.close-btn');
        const viewFileButtons = document.querySelectorAll('.view-file-btn');

        
        const closeModal = () => {
            modal.style.display = 'none';
            fileEmbed.src = ''; 
        };
        closeBtn.onclick = closeModal;
        window.onclick = (event) => { if (event.target == modal) closeModal(); };

        
        viewFileButtons.forEach(button => {
            button.addEventListener('click', function() {
                const fileUrl = this.dataset.url;   
                const fileName = this.dataset.name; 

                modalFileName.textContent = fileName; 
                fileEmbed.src = fileUrl;              
                
                modal.style.display = 'block'; 
            });
        });
    </script>
</body>
</html>

<?php
$stmt_article->close();
$stmt_attachments->close();
$conn->close();
?>