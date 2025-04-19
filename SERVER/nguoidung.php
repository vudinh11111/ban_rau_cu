<?php
include "connect.php";

header("Content-Type: application/json");

// Nhận dữ liệu từ JSON hoặc Form Data
$data = json_decode(file_get_contents("php://input"), true);
if (!$data) {
    $data = $_POST;
}

// Kiểm tra dữ liệu đầu vào
$email = $data['email'] ?? null;
$user = $data['user'] ?? null;
$pass = $data['pass'] ?? null;
$numberphone = $data['numberphone'] ?? null;

if (!$email || !$user || !$pass || !$numberphone) {
    echo "error";
    exit;
}

try {
    // Kiểm tra email đã tồn tại chưa
    $checkQuery = "SELECT email FROM nguoidung WHERE email = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        echo "emailtontai";
        exit;
    }

    // Thêm người dùng vào database
    $query = "INSERT INTO nguoidung (email, user, pass, numberphone) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $email, $user, $pass, $numberphone);

    if ($stmt->execute()) {
        echo "true";
    } else {
        echo "error";
    }
} catch (Exception $e) {
    echo "error";
}

// Đóng kết nối
$checkStmt->close();
$stmt->close();
$conn->close();
?>
