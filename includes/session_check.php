<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Hàm kiểm tra người dùng đã đăng nhập chưa
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Hàm chuyển hướng nếu chưa đăng nhập
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: /cbsv2/login.php"); 
        exit();
    }
}

// Hàm kiểm tra vai trò admin
function isAdmin() {
    return (isLoggedIn() && $_SESSION['user_role'] === 'admin');
}
?>