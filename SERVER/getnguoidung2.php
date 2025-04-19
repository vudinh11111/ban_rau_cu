<?php
include "connect.php";
header("Access-Control-Allow-Origin: *");  // Cho phép tất cả các domain truy cập
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");  // Các phương thức HTTP cho phép
header("Access-Control-Allow-Headers: Content-Type");  // Cho phép các header cần thiết
$query = "SELECT id_nguoidung, email, numberphone, user, pass FROM nguoidung";
$result = mysqli_query($conn, $query);

$users = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }

    // Kiểm tra nếu có dữ liệu
    if (!empty($users)) {
        echo json_encode($users);
    } else {
        echo json_encode(["status" => "error", "message" => "Không có dữ liệu"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Lỗi truy vấn: " . mysqli_error($conn)]);
}

mysqli_close($conn);
?>
