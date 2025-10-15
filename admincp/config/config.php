<?php 
$conn = "localhost";
$username   = "root";
$password   = "";
$dbname     = "web_phukien";

// Tạo kết nối
$conn = new mysqli($conn, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
//echo "Kết nối thành công";
?>