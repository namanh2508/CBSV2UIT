<?php



if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (file_exists('includes/session_check.php')) {
   
    require_once 'includes/session_check.php';
} elseif (file_exists('../includes/session_check.php')) {
   
    require_once '../includes/session_check.php';
} else {
   
    die('Không thể xác định vị trí file session_check.php!');
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi bộ Sinh viên 02 - Trường Đại học CNTT</title>
    <link rel="stylesheet" href="/cbsv2/css/style.css"> 

   
    <style>
        .admin-bar {
            background-color: #004a99; 
            padding: 5px 0;
            text-align: right;
        }
        .admin-bar .container {
            max-width: 1100px;
            margin: auto;
            padding: 0 10px;
        }
        .admin-menu {
            position: relative;
            display: inline-block;
        }
        .admin-menu-btn {
            background-color: #0056b3;
            color: white;
            padding: 8px 15px;
            font-size: 14px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }
        .admin-menu-btn:hover {
            background-color: #00418a;
        }
        .admin-dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            min-width: 220px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 100;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .admin-dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            text-align: left;
            font-size: 14px;
        }
        .admin-dropdown-content a:hover {
            background-color: #f1f1f1;
        }
        .admin-menu:hover .admin-dropdown-content {
            display: block;
        }
        .admin-dropdown-content .divider {
            height: 1px;
            margin: 8px 0;
            overflow: hidden;
            background-color: #e5e5e5;
        }
    </style>
</head>
<body>


    
    <header>
        <img src="/cbsv2/images/dang-logo.png" alt="Logo Đảng">
        <span style="font-family: 'arial', arial, bold; font-weight: bold; font-size: 24px;">TRANG THÔNG TIN ĐIỆN TỬ CHI BỘ SINH VIÊN 2</span>
        <p style="margin-top: 10px;">Website chính thức Chi bộ sinh viên 2 thuộc Đảng bộ Trường Đại học Công nghệ Thông tin, ĐHQG-HCM</p>
    </header>