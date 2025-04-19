<?php
include "connect.php";
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json; charset=utf-8');

// Truy vấn lấy tất cả đơn hàng (có thể lọc theo id_nguoidung nếu cần)
$sql = "SELECT * FROM donhang";  // Giả sử bảng của bạn tên là `donhang`
$result = $conn->query($sql);

$data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Chuyển chuỗi chi tiết thành mảng JSON
        $chitiet = json_decode($row['chitiet'], true);

        // Mỗi sản phẩm trong đơn sẽ trở thành một phần tử riêng trong mảng trả về
        foreach ($chitiet as $item) {
            $data[] = [
                "id_donhang" => (int)$row["id_donhang"],
                "ten_spmoi" => $item["ten_sp"],
                "hinh_spmoi" => $item["hinh_sp"],
                "gia_spmoi" => (string)$item["gia_sp"],
                "soluongct" => (int)$item["soluong"],
                "tongtien" => (string)$row["tongtien"],
                "trangthai" => (string)$row["trangthai"] // Thêm cột trạng thái
            ];
        }
    }
}

echo json_encode($data);
?>
