<?php

require_once 'includes/header.php'; 
require_once 'includes/nav.php';
?>

<?php
$museum_list = [
    ['name' => 'Bảo tàng Hồ Chí Minh', 'link' => 'https://baotang.hochiminh.vn/', 'image' => './images/Bao-tang-Ho-Chi-Minh.jpg'],
    ['name' => 'Khu di tích Hồ Chí Minh tại Phủ Chủ tịch', 'link' => 'http://phuchutich.egal.vn/', 'image' => './images/Khu-di-tich-Ho-Chi-Minh-tai-Phu-Chu-tich.jpg'],
    ['name' => 'Khu di tích Làng Sen Kim Liên', 'link' => 'https://langsenkimlien.hochiminh.vn/', 'image' => './images/Khu-di-tich-Lang-sen-Kim-Lien.jpg'],
    ['name' => 'Khu di tích Bến Nhà Rồng', 'link' => 'https://bennharong.hochiminh.vn/', 'image' => './images/Khu-di-tich-Ben-Nha-Rong.jpg'],
    ['name' => 'Khu di tích Pác Bó', 'link' => 'https://pacbo.hochiminh.vn/', 'image' => './images/Khu-di-tich-Pac-Bo.jpg'],
    ['name' => 'Khu di tích lịch sử ATK Định Hóa', 'link' => 'https://atkdinhhoa.hochiminh.vn', 'image' => './images/ATKDinhHoa.jpg'],
    ['name' => 'Khu di tích lịch sử Đá Chông (K9)', 'link' => 'https://k9dachong.hochiminh.vn', 'image' => './images/K9DaChong.jpg'],
    ['name' => 'Khu di tích Tân Trào - Tuyên Quang', 'link' => 'https://tantrao.hochiminh.vn/', 'image' => './images/Khu-di-tich-Tan-Trao-Tuyen-Quang.jpg'],
    ['name' => 'Bảo tàng Hồ Chí Minh - Chi nhánh Quân Khu 5', 'link' => 'https://baotanghcmqk5.hochiminh.vn', 'image' => './images/Bao-tang-Ho-Chi-Minh-chi-nhanh-quan-khu-5.jpg'],
    ['name' => 'Di tích Nhà số 5 Châu Văn Liêm', 'link' => 'https://so5chauvanliem.hochiminh.vn', 'image' => './images/Di-tich-nha-so-5-Chau-Van-Liem.jpg'],
    ['name' => 'Di tích lịch sử 48 Hàng Ngang', 'link' => 'https://48hangngang.hochiminh.vn', 'image' => './images/Di-tich-lich-su-48-Hang-Ngang.jpg'],
    ['name' => 'Di tích nhà lưu niệm Bác Hồ ở Vạn Phúc', 'link' => 'https://vanphuc.hochiminh.vn', 'image' => './images/Di-tich-nha-luu-niem-Bac-Ho-o-Van-Phuc.jpg'],
    ['name' => 'Bảo tàng Hồ Chí Minh - Thừa Thiên Huế', 'link' => 'https://baotanghcmtthue.hochiminh.vn', 'image' => './images/Bao-tang-Ho-Chi-Minh-Thua-Thien-Hue.jpg'],
    ['name' => 'Tượng đài Nguyễn Sinh Sắc - Nguyễn Tất Thành tại Bình Định', 'link' => 'https://binhdinh.hochiminh.vn/', 'image' => './images/Bao-tang-Ho-Chi-Minh-Binh-Dinh.jpg'],
    ['name' => 'Khu di tích Nguyễn Sinh Sắc - Nơi an nghỉ của cụ Phó bảng Nguyễn Sinh Sắc', 'link' => 'https://dongthap.hochiminh.vn/', 'image' => './images/Bao-tang-Ho-Chi-Minh-Dong-Thap.jpg'],
    ['name' => 'Bảo tàng Hồ Chí Minh - Chi nhánh Đồng bằng sông Cửu Long', 'link' => 'https://cantho.hochiminh.vn/', 'image' => './images/Bao-tang-Ho-Chi-Minh-Can-Tho.jpg'],
    ['name' => 'Khu Tưởng niệm Chủ tịch Hồ Chí Minh tại TP Cà Mau', 'link' => 'https://camau.hochiminh.vn/', 'image' => './images/Bao-tang-Ho-Chi-Minh-Ca-Mau.jpg'],
    ['name' => 'Trung ương cục Miền Nam', 'link' => 'https://trunguongcuc.hochiminh.vn/', 'image' => './images/TrungUongCuc.jpg'],
    ['name' => 'Bảo tàng Hồ Chí Minh - Chi nhánh Bình Thuận', 'link' => 'https://binhthuan.hochiminh.vn/', 'image' => './images/HCM-BinhThuan.jpg'],
    ['name' => 'Căn cứ Bộ Chỉ huy Miền', 'link' => 'https://bochihuymien.hochiminh.vn/', 'image' => './images/ChiHuyMien.jpg'],
    ['name' => 'Quảng trường Tây Bắc và Tượng đài Bắc Hồ', 'link' => 'https://taybac.hochiminh.vn/', 'image' => './images/TayBac.jpg'],
    ['name' => 'Khu Ủy Miền Đông Nam Bộ', 'link' => 'https://khuuymiendong.hochiminh.vn/', 'image' => './images/KhuUyMienDong.jpg'],
    ['name' => 'Trung ương Cục Miền Nam (1961 - 1962)', 'link' => 'https://tucucmiennam.hochiminh.vn/', 'image' => './images/TuCucMienNam.jpg'],
];
?>


<style>
    .container {
        padding: 20px;
        background: #f9f9f9;
    }

    .titlebar h1 a {
        text-decoration: none;
        color: #333;
        font-size: 28px;
    }

    .museumlist {
        margin-top: 20px;
    }

    .display-flex {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .museum-item {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        overflow: hidden;
        padding: 10px;
        text-align: center;
        width: calc(25% - 20px); 
        box-sizing: border-box;
    }

    .museum-item img {
        max-width: 100%;
        height: auto;
        border-radius: 6px;
    }

    .museum-item h4 {
        margin-top: 10px;
        font-size: 16px;
    }

    .museum-item h4 a {
        color: #007bff;
        text-decoration: none;
    }

    .museum-item h4 a:hover {
        text-decoration: underline;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .museum-item {
            width: calc(33.333% - 20px);
        }
    }

    @media (max-width: 768px) {
        .museum-item {
            width: calc(50% - 20px);
        }
    }

    @media (max-width: 480px) {
        .museum-item {
            width: 100%; /* 1 cột */
        }
    }
    .titlebar h1 a {
    display: inline-block;
    font-size: 48px;
    font-weight: bold;
    color: #fff;
    text-shadow:
        1px 1px 0 #ccc,
        2px 2px 0 #bbb,
        3px 3px 0 #aaa,
        4px 4px 0 #999,
        5px 5px 0 #888,
        6px 6px 0 #777;
    background: linear-gradient(135deg, #6dd5fa, #2980b9);
    padding: 10px 20px;
    border-radius: 10px;
    text-decoration: none;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.titlebar h1 a:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
}
.titlebar {
    background: linear-gradient(145deg, #e0e0e0, #ffffff);
    border-radius: 12px;
    padding: 30px;
    margin-bottom: 30px;
    box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    font-family: 'Segoe UI', sans-serif;
    animation: fadeInUp 1s ease-out;
}

/* Hiệu ứng chữ 3D */
.titlebar h1 a {
    font-size: 48px;
    font-weight: bold;
    color: #fff;
    background: linear-gradient(to right, #00c6ff, #0072ff);
    padding: 10px 25px;
    border-radius: 10px;
    text-decoration: none;
    display: inline-block;
    text-shadow:
        1px 1px 0 #ccc,
        2px 2px 0 #bbb,
        3px 3px 0 #aaa,
        4px 4px 0 #999;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.titlebar h1 a:hover {
    transform: translateY(-6px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
}

/* Phần mô tả */
.titlebar p {
    font-size: 16px;
    line-height: 1.6;
    color: #333;
    margin-top: 10px;
    animation: fadeIn 1s ease;
}

/* Trích dẫn */
.titlebar i {
    display: block;
    margin-top: 20px;
    font-style: italic;
    color: #555;
}

.titlebar i a {
    color: #0072ff;
    text-decoration: underline;
}


@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    } to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
    } to {
        opacity: 1;
    }
}
</style>
<div class="container clearfix">
    <div class="titlebar clearfix">
        <h1>
            <a href="/bao-tang-3d">Bảo tàng 3D</a>
        </h1>
        <p>Khám phá các bảo tàng 3D về cuộc đời và sự nghiệp của Chủ tịch Hồ Chí Minh.</p>
        <p>Chúng tôi đã tập hợp các bảo tàng 3D từ khắp nơi trên thế giới để bạn có thể tìm hiểu về cuộc đời và sự nghiệp của Chủ tịch Hồ Chí Minh.</p>
        <p>Hãy cùng chúng tôi khám phá những bảo tàng này và tìm hiểu về cuộc đời và sự nghiệp của Chủ tịch Hồ Chí Minh.</p>
        <p>Chúng tôi hy vọng bạn sẽ tìm thấy những thông tin hữu ích và thú vị trong các bảo tàng này.</p>
        <p>Cảm ơn bạn đã ghé thăm trang web của chúng tôi!</p>
        <i>Trích: <a href="https://hochiminh.vn/bao-tang-3d">Bảo tàng Hồ Chí Minh</a></i>
    </div>

    <div class="museumlist clearfix">
        <div class="row display-flex">
            <?php foreach ($museum_list as $museum): ?>
                <div class="museum-item">
                    <a class="avatar" href="<?= htmlspecialchars($museum['link']) ?>" target="_blank">
                        <img alt="<?= htmlspecialchars($museum['name']) ?>" loading="lazy" src="<?= htmlspecialchars($museum['image']) ?>">
                    </a>
                    <h4>
                        <a href="<?= htmlspecialchars($museum['link']) ?>" target="_blank"><?= htmlspecialchars($museum['name']) ?></a>
                    </h4>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>



<?php
require_once 'includes/footer.php';
?>