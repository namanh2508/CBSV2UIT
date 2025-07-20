<?php
include '../includes/session_check.php';
requireLogin();
include '../includes/db.php';
include '../includes/header.php';
include '../includes/nav.php';


$base_sql = "SELECT a.id, a.title,a.created_at, c.name AS category_name, u.fullname AS author_name 
             FROM articles a 
             LEFT JOIN users u ON a.user_id = u.id 
             LEFT JOIN categories c ON a.category_id = c.id";

$where = [];
$params = [];
$types = "";


if (!isAdmin()) {
    $where[] = "a.user_id = ?";
    $params[] = $_SESSION['user_id'];
    $types .= "i";
}


if (!empty($_GET['search_title'])) {
    $where[] = "a.title LIKE ?";
    $params[] = "%" . $_GET['search_title'] . "%";
    $types .= "s";
}


if (!empty($_GET['search_category'])) {
    $where[] = "a.category_id = ?";
    $params[] = $_GET['search_category'];
    $types .= "i";
}


$sql = $base_sql;
if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY a.created_at DESC";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý bài viết</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f7f9fc;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
        }

        table {
            width: 90%;
            margin: 30px auto;
            border-collapse: collapse;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 14px 20px;
            text-align: left;
            border-bottom: 1px solid #f0f0f0;
        }

        th {
            background-color: #2c3e50;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        a {
            color: #3498db;
            text-decoration: none;
            margin: 0 6px;
        }

        a:hover {
            text-decoration: underline;
        }

        .action-links a {
            font-weight: 500;
        }
    </style>
</head>
<body>

    <h1>Danh sách bài viết</h1>
    <form method="GET" style="text-align: center; margin-top: 20px;">
        <input type="text" name="search_title" placeholder="Tìm theo tiêu đề" value="<?php echo htmlspecialchars($_GET['search_title'] ?? ''); ?>" style="padding: 8px; width: 200px;">
        <select name="search_category" style="padding: 8px;">
            <option value="">Tất cả loại</option>
            <?php
            $category_query = $conn->query("SELECT id, name FROM categories");
            while ($cat = $category_query->fetch_assoc()):
            ?>
                <option value="<?php echo $cat['id']; ?>" <?php if (isset($_GET['search_category']) && $_GET['search_category'] == $cat['id']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($cat['name']); ?>
                </option>
            <?php endwhile; ?>
        </select>
        <button type="submit" style="padding: 8px 16px;">Tìm kiếm</button>
    </form>
    <table>
        <thead>
            <tr>
                <th>Tiêu đề</th>
                <th>Thời gian đăng</th>
                <th>Loại bài viết</th>
                <?php if (isAdmin()): ?><th>Tác giả</th><?php endif; ?>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['title']); ?></td>
                <td><?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?></td>
                <td><?php echo htmlspecialchars($row['category_name'] ?? 'Chưa phân loại'); ?></td>
                <?php if (isAdmin()): ?>
                    <td><?php echo htmlspecialchars($row['author_name']); ?></td>
                <?php endif; ?>
                <td class="action-links">
                    <a href="view_post.php?id=<?php echo $row['id']; ?>">Xem</a> | 
                    <a href="edit_post.php?id=<?php echo $row['id']; ?>">Sửa</a> | 
                    <a href="delete_post.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Bạn chắc chắn muốn xóa?')">Xóa</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <div style="text-align: center; margin-top: 20px;">
            <a href="../index.php" style="display: inline-block; padding: 10px 20px; background: #ffc107; color: #000; border-radius: 5px; text-decoration: none; font-weight: 500;">
                ← Quay lại trang quản lý
            </a>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
