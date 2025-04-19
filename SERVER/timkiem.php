<?php
include "connect.php";

// Lấy từ khoá từ POST
$tukhoa = isset($_POST['ten_spmoi']) ? $_POST['ten_spmoi'] : '';

// Nếu có từ khoá thì thêm điều kiện lọc
if (!empty($tukhoa)) {
    $query = "SELECT * FROM sanpham_moi WHERE ten_spmoi LIKE '%$tukhoa%'";
} else {
    $query = "SELECT * FROM sanpham_moi"; // Nếu không có từ khoá, trả về tất cả
}

// Thực thi câu truy vấn
$data = mysqli_query($conn, $query);

// Mảng để chứa kết quả
$result = array();

// Lặp qua các kết quả và thêm vào mảng
while ($row = mysqli_fetch_assoc($data)) {
    $result[] = $row;
}

// Trả về dữ liệu dưới dạng JSON
echo json_encode($result);
?>
