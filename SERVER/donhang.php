<?php
include "connect.php";  // Đảm bảo bạn chỉ sử dụng một thẻ PHP mở và đóng

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Lấy dữ liệu từ input POST
    $id_nguoidung = intval($_POST['id_nguoidung'] ?? 0);
    $soluongct = intval($_POST['soluongct'] ?? 0);
    $tongtien = floatval($_POST['tongtien'] ?? 0);
    $emailnd = trim($_POST['emailnd'] ?? '');
    $sodienthoai = trim($_POST['sodienthoai'] ?? '');
    $diachi = trim($_POST['diachi'] ?? '');
    $chitiet_arr = isset($_POST['chitiet']) ? json_decode($_POST['chitiet'], true) : [];
    $chitiet = json_encode($chitiet_arr, JSON_UNESCAPED_UNICODE);  // chuyển mảng thành chuỗi JSON
    $trangthai = intval($_POST['trangthai'] ?? 0);  // mặc định là 0

    // Kiểm tra dữ liệu
    if (!$id_nguoidung || !$soluongct || !$tongtien || !$emailnd || !$sodienthoai || !$diachi || !$chitiet) {
        echo json_encode([
            "status" => "error", 
            "message" => "Thiếu dữ liệu đầu vào", 
            "received_data" => $_POST
        ]);
        exit();
    }

    // Thêm vào DB
    $query = "INSERT INTO donhang (id_nguoidung, soluongct, tongtien, emailnd, sodienthoai, trangthai, diachi, chitiet)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "Lỗi prepare: " . $conn->error]);
        exit();
    }

    // Bind parameters (sửa lại cho đúng)
    $stmt->bind_param("iiidssss", 
        $id_nguoidung, $soluongct, $tongtien,
        $emailnd, $sodienthoai, $trangthai,
        $diachi, $chitiet // chỉ cần bind 8 tham số
    );

    if ($stmt->execute()) {
        echo json_encode(true); // Trả về true khi đơn hàng thành công
    } else {
        echo json_encode([
            "status" => "error", 
            "message" => "Lỗi khi thêm đơn hàng: " . $stmt->error
        ]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Chỉ hỗ trợ phương thức POST."]);
}
?>
