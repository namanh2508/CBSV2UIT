<?php

include 'includes/db.php';
include 'includes/header.php';
include 'includes/nav.php';
?>
<div style="background-color: #f2f2f2; padding: 10px 0; border-top: 1px solid #ccc; border-bottom: 1px solid #ccc;">
    
    <marquee behavior="scroll" direction="left" scrollamount="5" onmouseover="this.stop();" onmouseout="this.start();">
        📌 <a href="post.php?id=1">Bài viết 1</a> &nbsp;&nbsp;&nbsp;&nbsp;
        📌 <a href="post.php?id=2">Bài viết 2</a> &nbsp;&nbsp;&nbsp;&nbsp;
        📌 <a href="post.php?id=3">Bài viết 3</a>
    </marquee>
</div>
<section>
    <h2>Chào mừng đến với Website Chi bộ Sinh viên 02</h2>

    
    <div class="intro-container">
        
        <!-- CỘT 1: HÌNH ẢNH -->
        <div class="intro-image-wrapper">
            <img src="images/tapthe.jpg" alt="Ảnh tập thể Chi bộ">
        </div>

        <!-- CỘT 2: TIỆN ÍCH -->
        <div class="intro-utilities-wrapper">
            <div class="sidebar-widget">
                <div class="widget-title">Tiện ích</div>
                <div class="widget-content">
                    
                    <a href="#" class="utility-banner"><img src="images/vinhdanh.png" alt="Vinh danh"></a>
                    <a href="#" class="utility-banner"><img src="images/quytrinhthutuc.jpg" alt="Quy trình Thủ tục"></a>
                    <a href="lich.php" class="utility-banner"><img src="images/lich-cong-tac.jpg" alt="Lịch hoạt động"></a>
                </div>
            </div>
        </div>

    </div>

    
    <p>Chi bộ Sinh viên 2 trực thuộc Đảng ủy Trường Đại học Công nghệ Thông tin, ĐHQG-HCM.
    Chi bộ là tổ chức chính trị hạt nhân trong sinh viên, có vai trò lãnh đạo công tác tư tưởng, định hướng đạo đức lối sống, triển khai các nghị quyết của Đảng và tổ chức sinh hoạt chính trị định kỳ
    trong đội ngũ đảng viên sinh viên. Chi bộ Sinh viên 2 (CBSV2) là nơi tập hợp Đảng viên Sinh viên từ hai Khoa Công nghệ phần mềm và khoa Hệ thống thông tin thuộc trường Đại học Công nghệ thông tin.
    Hiện tại, chi bộ có 33 đảng viên đang sinh hoạt, trong đó số lượng đảng viên chính thức là 26 đồng chí, số lượng đảng viên dự bị là 07 đồng chí, đảng viên là sinh viên là 31 đồng chí, đảng viên là cán bộ - giảng viên là 02 đồng chí.</p>

    <!-- DANH SÁCH BÀI VIẾT (Vẫn giữ nguyên ở dưới cùng) -->
    <div class="post-listing">
        <h2>Các bài viết mới nhất</h2>
        <div class="post-listing-item"><h3><a href="post.php?id=1">bài viết 1</a></h3><p>bài viết số 1 ...</p><small>Đăng ngày: 17/07/2025</small></div>
    </div>
</section>

<?php
include 'includes/footer.php';
?>