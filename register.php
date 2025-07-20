<?php

include 'includes/db.php';
$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $fullname = $_POST['fullname'];

    if ($password !== $confirm_password) {
        $error = "Mật khẩu không khớp!";
    } else {
        
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Tên đăng nhập đã tồn tại!";
        } else {
            
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            
            $stmt_insert = $conn->prepare("INSERT INTO users (username, password, fullname) VALUES (?, ?, ?)");
            $stmt_insert->bind_param("sss", $username, $hashed_password, $fullname);

            if ($stmt_insert->execute()) {
                header("Location: login.php?registered=success");
                exit();
            } else {
                $error = "Đăng ký thất bại, vui lòng thử lại.";
            }
            $stmt_insert->close();
        }
        $stmt->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Đăng ký tài khoản</title>
    
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh;}
        .form-container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 0 15px rgba(0,0,0,0.1); width: 350px; }
        .form-container h2 { text-align: center; color: #d90429; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .form-group button { width: 100%; padding: 10px; background: #d90429; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .error { color: red; text-align: center; margin-bottom: 10px; }
        .login-link { text-align: center; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Đăng ký tài khoản</h2>
        <?php if ($error): ?><p class="error"><?php echo $error; ?></p><?php endif; ?>
        <form action="register.php" method="post">
            <div class="form-group"><label>Tên đăng nhập:</label><input type="text" name="username" required></div>
            <div class="form-group"><label>Tên đầy đủ:</label><input type="text" name="fullname" required></div>
            <div class="form-group"><label>Mật khẩu:</label><input type="password" name="password" required></div>
            <div class="form-group"><label>Xác nhận mật khẩu:</label><input type="password" name="confirm_password" required></div>
            <div class="form-group"><button type="submit">Đăng ký</button></div>
        </form>
        <div class="login-link"><a href="login.php">Đã có tài khoản? Đăng nhập</a></div>
    </div>
</body>
</html>