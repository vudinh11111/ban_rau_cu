<?php
include "connect.php";

header("Content-Type: application/json; charset=UTF-8");

$query = "SELECT * FROM loaisanpham";
$data = mysqli_query($conn, $query);

$result = array();

if ($data) {
    while ($row = mysqli_fetch_assoc($data)) {
        $result[] = $row;
    }

    // Kiểm tra nếu có dữ liệu
    if (!empty($result)) {
        echo json_encode([$result], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(["status" => "error", "message" => "Không có dữ liệu"], JSON_UNESCAPED_UNICODE);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Lỗi truy vấn: " . mysqli_error($conn)], JSON_UNESCAPED_UNICODE);
}

// Đóng kết nối
mysqli_close($conn);
?>
