<?php
include "connect.php";
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Giả sử bảng donhang có cột id_nguoidung để liên kết đến bảng nguoidung
$query = "
    SELECT 
        donhang.id_donhang,
        donhang.chitiet,
        donhang.tongtien,
        donhang.soluongct,
        donhang.tongtien,
        nguoidung.id_nguoidung,
        nguoidung.user,
        nguoidung.email,
        donhang.trangthai,
        nguoidung.numberphone
    FROM donhang
    JOIN nguoidung ON donhang.id_nguoidung = nguoidung.id_nguoidung
";

$data = mysqli_query($conn, $query);
$result = array();

while ($row = mysqli_fetch_assoc($data)) {
    $result[] = $row;
}

echo json_encode($result);
?>
