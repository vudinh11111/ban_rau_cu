<?php
include "connect.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $json = file_get_contents("php://input");
    $data = json_decode($json, true);

    if (!$data || !isset($data['id_donhang']) || !isset($data['trangthai'])) {
        echo json_encode([
            "status" => "error",
            "message" => "Thiếu id_donhang hoặc trangthai để cập nhật.",
            "received_data" => $data
        ]);
        exit();
    }

    $id_donhang = intval($data['id_donhang']);
    $trangthai = intval($data['trangthai']);

    $query = "UPDATE donhang SET trangthai = ? WHERE id_donhang = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        echo json_encode([
            "status" => "error",
            "message" => "Lỗi chuẩn bị truy vấn: " . $conn->error
        ]);
        exit();
    }

    $stmt->bind_param("ii", $trangthai, $id_donhang);

    if ($stmt->execute()) {
        echo json_encode([
            "status" => "success",
            "message" => "Cập nhật trạng thái đơn hàng thành công."
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Lỗi khi cập nhật: " . $stmt->error
        ]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Chỉ hỗ trợ phương thức POST."
    ]);
}
?>
