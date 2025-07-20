<?php


header('Content-Type: application/json');
include '../includes/db.php';


$title = $_POST['title'] ?? '';
$author = $_POST['author'] ?? '';
$content = $_POST['content'] ?? '';
$start_datetime = $_POST['start_datetime'] ?? '';
$end_datetime = !empty($_POST['end_datetime']) ? $_POST['end_datetime'] : NULL; // Cho phép trống
$color = $_POST['color'] ?? '#3788d8';
$file_url = NULL; 

$response = ['success' => false, 'message' => 'Dữ liệu không hợp lệ.'];


if (isset($_FILES['event_file']) && $_FILES['event_file']['error'] == 0) {
    $upload_dir = '../uploads/files';
    
    $file_extension = pathinfo($_FILES['event_file']['name'], PATHINFO_EXTENSION);
    $unique_filename = uniqid('event_', true) . '.' . $file_extension;
    $target_file = $upload_dir . $unique_filename;

   
    if (move_uploaded_file($_FILES['event_file']['tmp_name'], $target_file)) {
        
        $file_url = 'uploads/files' . $unique_filename;
    } else {
        $response['message'] = 'Lỗi khi tải file lên.';
        echo json_encode($response);
        exit();
    }
}


if (!empty($title) && !empty($author) && !empty($start_datetime)) {
    $sql = "INSERT INTO events (title, author, content, start_datetime, end_datetime, color, file_url) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $title, $author, $content, $start_datetime, $end_datetime, $color, $file_url);

    if ($stmt->execute()) {
        $response = ['success' => true, 'message' => 'Đã thêm sự kiện thành công!'];
    } else {
        $response['message'] = 'Lỗi CSDL: ' . $stmt->error;
    }
    $stmt->close();
}

echo json_encode($response);
$conn->close();
?>