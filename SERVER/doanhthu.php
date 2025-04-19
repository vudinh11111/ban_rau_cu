<?php
include "connect.php";
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Truy vấn lấy danh sách sản phẩm đã bán
$query = "
    SELECT 
    
       
        
        SUM(soluongct) AS tong_soluong,
        SUM(tongtien) AS tong_doanhthu
    FROM donhang

    ORDER BY tong_doanhthu DESC
";

$result = mysqli_query($conn, $query);
$data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode($data);
?>
