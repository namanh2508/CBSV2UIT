<?php
// login.php
include 'includes/db.php';
include 'includes/session_check.php';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password, role, fullname FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password, $role, $fullname);
        $stmt->fetch();
        
        // Xác thực mật khẩu
        if (password_verify($password, $hashed_password)) {
            
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            $_SESSION['user_role'] = $role;
            $_SESSION['user_fullname'] = $fullname;
            
            header("Location: /cbsv2/index.php"); 
            exit();
        } else {
            $error = "Sai tên đăng nhập hoặc mật khẩu!";
        }
    } else {
        $error = "Sai tên đăng nhập hoặc mật khẩu!";
    }
    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Đăng nhập</title>
    <style> 
        body { font-family: Arial, sans-serif; background: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh;}
        .form-container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 0 15px rgba(0,0,0,0.1); width: 350px; }
        .form-container h2 { text-align: center; color: #d90429; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .form-group button { width: 100%; padding: 10px; background: #d90429; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .error { color: red; text-align: center; margin-bottom: 10px; }
        .register-link { text-align: center; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Đăng nhập</h2>
        <?php if ($error): ?><p class="error"><?php echo $error; ?></p><?php endif; ?>
        <?php if (isset($_GET['registered'])): ?><p style="color:green; text-align:center;">Đăng ký thành công! Vui lòng đăng nhập.</p><?php endif; ?>
        <form action="login.php" method="post">
            <div class="form-group"><label>Tên đăng nhập:</label><input type="text" name="username" required></div>
            <div class="form-group"><label>Mật khẩu:</label><input type="password" name="password" required></div>
            <div class="form-group"><button type="submit">Đăng nhập</button></div>
        </form>
        <div class="register-link"><a href="register.php">Chưa có tài khoản? Đăng ký</a></div>
    </div>
</body>
</html>