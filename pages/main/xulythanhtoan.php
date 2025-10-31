<?php
session_start();
include('../../admincp/config/config.php');

$id_khachhang = $_SESSION['id_khachhang'];
$code_order = rand(0,9999);
$cart_payment = $_POST['payment'];

// Lấy id thông tin vận chuyển
$id_dangky = $_SESSION['id_khachhang'];
$sql_get_vanchuyen = mysqli_query($conn, "SELECT * FROM tbl_shipping WHERE id_dangky='$id_dangky' LIMIT 1");
$row_get_vanchuyen = mysqli_fetch_array($sql_get_vanchuyen);
$id_shipping = $row_get_vanchuyen['id_shipping'];

// Thời gian hiện tại
$date_order = date('Y-m-d H:i:s');

// Thêm đơn hàng vào bảng tbl_cart
$insert_cart = "INSERT INTO tbl_cart(id_khachhang, code_cart, cart_status, cart_date, cart_payment, cart_shipping)
VALUES('$id_khachhang','$code_order','1','$date_order','$cart_payment','$id_shipping')";
$cart_query = mysqli_query($conn, $insert_cart);

if(!$cart_query){
    die("Lỗi khi thêm đơn hàng: " . mysqli_error($conn));
}

// Nếu thêm thành công, thêm chi tiết đơn hàng
if($cart_query && isset($_SESSION['cart'])){
    foreach($_SESSION['cart'] as $key => $value){
        $id_sanpham = $value['id'];
        $soluong = $value['soluong'];
        $insert_order_details = "INSERT INTO tbl_cart_details(id_sanpham, code_cart, soluongmua)
                                 VALUES('$id_sanpham','$code_order','$soluong')";
        mysqli_query($conn, $insert_order_details);
    }

    // Gửi mail xác nhận
    $tieude = "Đặt hàng website thành công";
    $noidung = "<p>Cảm ơn bạn đã đặt hàng tại website chúng tôi với mã đơn hàng: ".$code_order."</p>";
    $noidung .= "<h4>Đơn hàng bao gồm:</h4>";

    foreach($_SESSION['cart'] as $key => $value){
        $noidung .= "<ul style='border:1px solid blue; margin:10px;'>
            <li>".$value['tensanpham']."</li>
            <li>Giá: ".number_format($value['giasp'],0,',','.')."đ</li>
            <li>Số lượng: ".$value['soluong']."</li>
        </ul>";
    }

   // include('../../mail/sendmail.php');
   // $maildathang = $_SESSION['email'];
   // $mail = new Mailer();
   // $mail->dathangmail($tieude, $noidung, $maildathang);

    // Xóa giỏ hàng
    unset($_SESSION['cart']);
    header('Location:../../index.php?quanly=camon');
    exit();
}
?>
