<?php  ?>

<nav>
    <a href="./index.php">Trang chủ</a>

    <div class="dropdown">
        <a href="#">Giới thiệu</a>
        <div class="dropdown-content">
            <a href="/cbsv2/admin/post_management.php?search_category=13">Cơ cấu tổ chức của Chi bộ</a>
            <a href="#">Chi ủy</a>
            <a href="#">Đảng viên chi bộ</a>
        </div>
    </div>

    <div class="dropdown">
        <a href="#">Tin tức - Sự kiện</a>
        <div class="dropdown-content">
            <a href="/cbsv2/admin/post_management.php?search_category=14">Hoạt động của Đảng ủy trường</a>
            <a href="/cbsv2/admin/post_management.php?search_category=15">Hoạt động của Chi bộ</a>
            <a href="#">Gương sáng Đảng viên</a>
        </div>
    </div>

    <div class="dropdown">
        <a href="#">Không gian văn hóa Hồ Chí Minh</a>
        <div class="dropdown-content">
            <a href="#">Cuộc đời và sự nghiệp của Chủ tịch Hồ Chí Minh</a>
            <a href="#">Học và làm theo Bác</a>
            <a href="/cbsv2/museum.php">Tham quan Bảo tàng Hồ Chí Minh</a>
        </div>
    </div>

    <div class="dropdown">
        <a href="#">Văn bản triển khai</a>
        <div class="dropdown-content">
            <a href="/cbsv2/admin/post_management.php?search_category=2">Văn bản của Đảng ủy ĐHQG-HCM</a>
            <a href="/cbsv2/admin/post_management.php?search_category=3">Văn bản của Đảng ủy Trường</a>
            <a href="/cbsv2/admin/post_management.php?search_category=4">Các văn bản khác</a>
        </div>
    </div>

    <div class="dropdown">
        <a href="#">Quy trình - Nghiệp vụ</a>
        <div class="dropdown-content">
            <a href="/cbsv2/admin/post_management.php?search_category=5">Quy trình Kết nạp Đảng viên</a>
            <a href="/cbsv2/admin/post_management.php?search_category=6">Quy trình xét Công nhận Đảng viên chính thức</a>
            <a href="/cbsv2/admin/post_management.php?search_category=7">Quy trình Chuyển sinh hoạt Đảng</a>
            <a href="/cbsv2/admin/post_management.php?search_category=8">Quy trình giới thiệu sinh hoạt đảng nơi cư trú</a>
            <a href="/cbsv2/admin/post_management.php?search_category=9">Các quy định về cấp mới, cấp lại, sử dụng và bảo quản thẻ Đảng viên</a>
            <a href="/cbsv2/admin/post_management.php?search_category=10">Quy trình Đánh giá nhận xét, kiểm điểm đảng viên cuối năm</a>
        </div>
    </div>

    <div class="dropdown">
        <a href="#">Tư liệu và Tài nguyên</a>
        <div class="dropdown-content">
            <a href="/cbsv2/admin/post_management.php?search_category=11">Tư liệu Văn kiện đảng</a>
            <a href="/cbsv2/admin/post_management.php?search_category=12">Tư liệu và tài nguyên của Chi bộ</a>
            <a href="#">Hình ảnh Hoạt động</a>
        </div>
    </div>

    <?php


if (function_exists('isLoggedIn') && isLoggedIn()): 
?>
    <div class="dropdown nav-admin-button">
        
        <a href="#">Quản lý</a>
        
        <div class="dropdown-content">
            <?php if (isAdmin()): ?>
                
                <a href="/cbsv2/admin/profile.php">Thông tin cá nhân</a>
                <a href="/cbsv2/admin/post_management.php">Quản lý toàn bộ nội dung</a>
                <a href="#">Quản lý menu</a>
                <a href="#">Quản lý danh mục</a>
                <div class="divider"></div>
                <a href="/cbsv2/admin/add_post.php">Thêm Bài viết</a>
                <a href="#">Thêm Slideshow</a>
                <a href="#">Thêm Album hình</a>
                <a href="#">Thêm Quảng cáo</a>

            <?php else:  ?>
                
                <a href="/cbsv2/admin/profile.php">Thông tin cá nhân</a>
                <a href="/cbsv2/admin/post_management.php">Bài viết của tôi</a>
                <div class="divider"></div>
                <a href="/cbsv2/admin/add_post.php">Thêm bài viết mới</a>
            
            <?php endif; ?>

            
            <div class="divider"></div>
            <a href="/cbsv2/logout.php" style="color: blue;">Đăng xuất</a>
        </div>

        
    </div>
<?php endif; ?>
<?php if (!function_exists('isLoggedIn') || !isLoggedIn()): ?>
    <a href="/cbsv2/login.php" style="color: white;">Đăng nhập</a>
<?php endif; ?>
</nav>