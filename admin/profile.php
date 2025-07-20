<?php

require_once '../includes/session_check.php';
requireLogin();
require_once '../includes/db.php';

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_info'])) {
        $fullname = $_POST['fullname'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $position = $_POST['position'];
        $achievements = $_POST['achievements'];
        $notes = $_POST['notes'];
        $avatar_url = $_POST['current_avatar'];

        
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
            $upload_dir = '../uploads/avatars/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

            $file_info = $_FILES['avatar'];
            $file_name = basename($file_info['name']);
            $file_type = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($file_type, $allowed_types)) {
                $unique_filename = 'user_' . $user_id . '_' . time() . '.' . $file_type;
                $target_path = $upload_dir . $unique_filename;

                if (move_uploaded_file($file_info['tmp_name'], $target_path)) {
                    if (!empty($avatar_url) && $avatar_url !== 'uploads/avatars/default.png' && file_exists('../' . $avatar_url)) {
                        unlink('../' . $avatar_url);
                    }
                    $avatar_url = 'uploads/avatars/' . $unique_filename;
                } else {
                    $error = "Lỗi khi tải ảnh lên.";
                }
            } else {
                $error = "Chỉ cho phép upload file ảnh (jpg, jpeg, png, gif).";
            }
        }

        if (empty($error)) {
            // Kiểm tra xem email đã tồn tại chưa (ngoại trừ người hiện tại)
            $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
            $check_stmt->bind_param("si", $email, $user_id);
            $check_stmt->execute();
            $check_stmt->store_result();

            if ($check_stmt->num_rows > 0) {
                $error = "Email này đã được sử dụng. Vui lòng chọn một email khác.";
            } else {
                $stmt = $conn->prepare("UPDATE users SET fullname=?, email=?, phone=?, position=?, avatar=?, achievements=?, notes=? WHERE id=?");
                $stmt->bind_param("sssssssi", $fullname, $email, $phone, $position, $avatar_url, $achievements, $notes, $user_id);
                if ($stmt->execute()) {
                    $_SESSION['user_fullname'] = $fullname;
                    $success = "Cập nhật thông tin thành công!";
                } else {
                    $error = "Lỗi khi cập nhật thông tin: " . $stmt->error;
                }
                $stmt->close();
            }
            $check_stmt->close();
        }
    }

    
    if (isset($_POST['change_password'])) {
        $old_password = $_POST['old_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if (empty($old_password) || empty($new_password) || empty($confirm_password)) {
            $error = "Vui lòng điền đầy đủ các trường mật khẩu.";
        } elseif ($new_password !== $confirm_password) {
            $error = "Mật khẩu mới không khớp.";
        } else {
            $stmt_pass = $conn->prepare("SELECT password FROM users WHERE id = ?");
            $stmt_pass->bind_param("i", $user_id);
            $stmt_pass->execute();
            $user_pass = $stmt_pass->get_result()->fetch_assoc();

            if (password_verify($old_password, $user_pass['password'])) {
                $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt_update_pass = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                $stmt_update_pass->bind_param("si", $new_hashed_password, $user_id);
                if ($stmt_update_pass->execute()) {
                    $success = "Đổi mật khẩu thành công!";
                } else {
                    $error = "Lỗi khi đổi mật khẩu.";
                }
                $stmt_update_pass->close();
            } else {
                $error = "Mật khẩu cũ không đúng.";
            }
            $stmt_pass->close();
        }
    }
}


$stmt_get = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt_get->bind_param("i", $_SESSION['user_id']);
$stmt_get->execute();
$current_user = $stmt_get->get_result()->fetch_assoc();
$stmt_get->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thông tin cá nhân</title>
    
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f7f6; }
        .container { max-width: 900px; margin: 30px auto; }
        .profile-card { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); margin-bottom: 30px; }
        .profile-card h2 { color: #cc0000; border-bottom: 2px solid #f0f0f0; padding-bottom: 10px; margin-top: 0; }
        .form-row { display: flex; flex-wrap: wrap; gap: 20px; }
        .form-group { margin-bottom: 20px; flex: 1 1 45%; } /* Flexbox cho responsive */
        .form-group label { display: block; font-weight: 600; margin-bottom: 8px; color: #555; }
        .form-group input, .form-group textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        .form-group .readonly-input { background-color: #f0f0f0; }
        .avatar-preview { width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 3px solid #eee; margin-top: 10px; }
        .btn { padding: 12px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: 600; font-size: 15px; }
        .btn-update { background-color: #28a745; color: white; }
        .btn-change-pass { background-color: #007bff; color: white; }
        .message { padding: 15px; margin-bottom: 20px; border-radius: 5px; text-align: center; }
        .error { background-color: #f8d7da; color: #721c24; }
        .success { background-color: #d4edda; color: #155724; }
        .welcome-message {
            text-align: center;
            margin-bottom: 30px;
            padding: 15px;
            background-color: #e8f4fc;
            border-left: 6px solid #007bff;
            border-radius: 8px;
            font-size: 22px;
            color: #333;
        }

        .welcome-message h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="welcome-message">
        <?php if (isAdmin()): ?>
            <h1>Xin chào! Quản trị viên <strong><?php echo htmlspecialchars($current_user['fullname']); ?></strong></h1>
        <?php else: ?>
            <h1>Xin chào! Đồng chí <strong><?php echo htmlspecialchars($current_user['fullname']); ?></strong></h1>
        <?php endif; ?>
    </div>
    <div class="container">
        
        <?php if ($error): ?>
            <div class="message error">
                <?php echo $error; ?>
                <?php if (strpos($error, 'Email') !== false): ?>
                    <br>Vui lòng nhập một địa chỉ email khác bên dưới.
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <?php if ($success): ?><div class="message success"><?php echo $success; ?></div><?php endif; ?>

        
        <div class="profile-card">
            <h2>Thông tin cá nhân</h2>
            <?php 
                $avatar_path = !empty($current_user['avatar']) ? '../' . htmlspecialchars($current_user['avatar']) : '../uploads/avatars/default.png';
            ?>
            <div style="text-align:center; margin-bottom: 20px;">
                <img src="<?php echo $avatar_path; ?>" alt="Ảnh đại diện" class="avatar-preview">
            </div>
            <form method="POST" action="profile.php" enctype="multipart/form-data">
                <input type="hidden" name="current_avatar" value="<?php echo htmlspecialchars($current_user['avatar']); ?>">
                <div class="form-row">
                    <div class="form-group">
                        <label>Tên đăng nhập:</label>
                        <input type="text" value="<?php echo htmlspecialchars($current_user['username']); ?>" class="readonly-input" readonly>
                    </div>
                    <div class="form-group">
                        <label for="fullname">Tên đầy đủ:</label>
                        <input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($current_user['fullname']); ?>" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($current_user['email']); ?>" required>
                    </div>
                     <div class="form-group">
                        <label for="phone">Số điện thoại:</label>
                        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($current_user['phone']); ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="position">Chức vụ:</label>
                    <input type="text" id="position" name="position" value="<?php echo htmlspecialchars($current_user['position']); ?>">
                </div>
                <div class="form-group">
                    <label for="avatar">Ảnh đại diện (để trống nếu không muốn thay đổi):</label>
                    <input type="file" id="avatar" name="avatar" accept="image/*">
                     <?php 
                        $avatar_path = !empty($current_user['avatar']) ? '../' . htmlspecialchars($current_user['avatar']) : '../uploads/avatars/default.png';
                     ?>
                    
                </div>
                <div class="form-group">
                    <label for="achievements">Thành tích:</label>
                    <textarea id="achievements" name="achievements" rows="4"><?php echo htmlspecialchars($current_user['achievements']); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="notes">Ghi chú:</label>
                    <textarea id="notes" name="notes" rows="4"><?php echo htmlspecialchars($current_user['notes']); ?></textarea>
                </div>
                
                <button type="submit" name="update_info" class="btn btn-update">Cập nhật thông tin</button>
            </form>
        </div>

        <!-- Form thay đổi mật khẩu -->
        <div class="profile-card">
            <h2>Thay đổi mật khẩu</h2>
            <form method="POST" action="profile.php">
                <div class="form-group">
                    <label for="old_password">Mật khẩu cũ:</label>
                    <input type="password" id="old_password" name="old_password" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="new_password">Mật khẩu mới:</label>
                        <input type="password" id="new_password" name="new_password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Xác nhận mật khẩu mới:</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                </div>
                <button type="submit" name="change_password" class="btn btn-change-pass">Đổi mật khẩu</button>
            </form>
        </div>
        <div style="text-align: center; margin-top: 20px;">
            <a href="../index.php" style="display: inline-block; padding: 10px 20px; background: #ffc107; color: #000; border-radius: 5px; text-decoration: none; font-weight: 500;">
                ← Quay lại trang quản lý
            </a>
        </div>
    </div>
    

</body>
</html>