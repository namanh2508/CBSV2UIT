<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Xóa tất cả biến session
session_unset();

// Hủy session
session_destroy();

// Chuyển hướng về trang đăng nhập
header("Location: /cbsv2/login.php");
exit();
?>