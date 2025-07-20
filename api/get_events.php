<?php


header('Content-Type: application/json');
include '../includes/db.php';


$sql = "SELECT id, title, author, content, start_datetime, end_datetime, color, file_url FROM events";
$result = $conn->query($sql);

$events = [];
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $events[] = [
            'id'      => $row['id'],
            'title'   => $row['title'],
            'start'   => $row['start_datetime'], 
            'end'     => $row['end_datetime'],   
            'color'   => $row['color'],          
            'extendedProps' => [ 
                'author'   => $row['author'],
                'content'  => $row['content'],
                'file_url' => $row['file_url']
            ]
        ];
    }
}

echo json_encode($events);
$conn->close();
?>