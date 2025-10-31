<?php 
$conn = "localhost:3307";//Lỗi do thiếu cổng 3307 -> đúng là localhost:cổng
$username   = "root";
$password   = "0702479538";
$dbname     = "web_phukien";

// Tạo kết nối
$conn = new mysqli($conn, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
//echo "Kết nối thành công";
?>


