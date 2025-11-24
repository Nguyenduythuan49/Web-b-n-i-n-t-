 <?php

session_start();

include('../../admincp/config/config.php');

require_once('config_vnpay.php');



$id_khachhang = $_SESSION['id_khachhang'];

$code_order = rand(0,9999);

$cart_payment = $_POST['payment'];
// Thời gian hiện tại
$date_order = date('Y-m-d H:i:s');
// Lấy id thông tin vận chuyển

$id_dangky = $_SESSION['id_khachhang'];

$sql_get_vanchuyen = mysqli_query($conn, "SELECT * FROM tbl_shipping WHERE id_dangky='$id_dangky' LIMIT 1");

$row_get_vanchuyen = mysqli_fetch_array($sql_get_vanchuyen);

$id_shipping = $row_get_vanchuyen['id_shipping'];
$tongtien=0;
 foreach($_SESSION['cart'] as $key => $value){
    $thanhtien= $value['soluong']*$value['giasp'];
    $tongtien+=$thanhtien;
 }

if($cart_payment=='tienmat'||$cart_payment=='chuyenkhoan'){

// Thêm đơn hàng vào bảng tbl_cart

$insert_cart = "INSERT INTO tbl_cart(id_khachhang, code_cart, cart_status, cart_date, cart_payment, cart_shipping)

VALUES('".$id_khachhang."','".$code_order."',1,'".$now."','".$cart_payment."','".$id_shipping."')";

$cart_query = mysqli_query($conn, $insert_cart);



//thêm đơn hàng chi tiết

    foreach($_SESSION['cart'] as $key => $value){

        $id_sanpham = $value['id'];

        $soluong = $value['soluong'];

        $insert_order_details = "INSERT INTO tbl_cart_details(id_sanpham, code_cart, soluongmua)

        VALUES('$id_sanpham','$code_order','$soluong')";

        mysqli_query($conn, $insert_order_details);

    }

header('Location:../../index.php?quanly=camon');
}elseif($cart_payment=='vnpay'){

    // Thanh toán VNPAY
$vnp_TxnRef=$code_order; //Mã đơn hàng
$vnp_OrderInfo='Thanh toan đơn hàng đặt tại web';
$vnp_Ordertype='billpayment';
$vnp_Amount=$tongtien*100;
$vnp_Locale='vn';
$vnp_BankCode='NCB';
$vnp_IpAddr=$_SERVER['REMOTE_ADDR'];
$vnp_ExpireDate=$expire;
$inputData=array(
    "vnp_Version"=>"2.1.0",
    "vnp_TmnCode"=>$vnp_TmnCode,
    "vnp_Amount"=>$vnp_Amount,
    "vnp_Command"=>"pay",
    "vnp_CreateDate"=>date('YmdHis'),
    "vnp_CurrCode"=>"VND",
    "vnp_IpAddr"=>$vnp_IpAddr,
    "vnp_Locale"=>$vnp_Locale,
    "vnp_OrderInfo"=>$vnp_OrderInfo,
    "vnp_OrderType"=>$vnp_Ordertype,
    "vnp_ReturnUrl"=>$vnp_Returnurl,
    "vnp_TxnRef"=>$vnp_TxnRef,
    "vnp_ExpireDate"=>$vnp_ExpireDate
);
if (isset($vnp_BankCode) && $vnp_BankCode != "") {
    $inputData['vnp_BankCode'] = $vnp_BankCode;
}

ksort($inputData);
$query = "";
$i = 0;
$hashdata = "";

foreach ($inputData as $key => $value) {
    if ($i == 1) {
        $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
    } else {
        $hashdata .= urlencode($key) . "=" . urlencode($value);
        $i = 1;
    }
    $query .= urlencode($key) . "=" . urlencode($value) . '&';
}
$vnp_Url = $vnp_Url . "?" . $query;
if (isset($vnp_HashSecret)) {
    $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);//  
    $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
}
$returnData = array('code' => '00'
    , 'message' => 'success'
    , 'data' => $vnp_Url);
    if(isset($_POST['redirect'])){
        $_SESSION['code_cart']=$code_order;
        // Thêm đơn hàng vào bảng tbl_cart
        $insert_cart = "INSERT INTO tbl_cart(id_khachhang, code_cart, cart_status, cart_date, cart_payment, cart_shipping)

VALUES('$id_khachhang','$code_order','1','$date_order','$cart_payment','$id_shipping')";

$cart_query = mysqli_query($conn, $insert_cart);



//thêm đơn hàng chi tiết

    foreach($_SESSION['cart'] as $key => $value){

        $id_sanpham = $value['id'];

        $soluong = $value['soluong'];

        $insert_order_details = "INSERT INTO tbl_cart_details(id_sanpham, code_cart, soluongmua)

        VALUES('$id_sanpham','$code_order','$soluong')";

        mysqli_query($conn, $insert_order_details);

    }
        header('Location: ' . $vnp_Url);
        die();
    }
    else {
        echo json_encode($returnData);
    }

}elseif($cart_payment=='paypal'){
// Thanh toán Paypal
    
}elseif($cart_payment=='momo'){
// Thanh toán Momo
    

}
if($cart_query){



// Gửi mail xác nhận

/*


$tieude = "Đặt hàng website thành công";

$noidung = "<p>Cảm ơn bạn đã đặt hàng tại website chúng tôi với mã đơn hàng: ".$code_order."</p>";

$noidung .= "<h4>Đơn hàng bao gồm:</h4>";



    foreach($_SESSION['cart'] as $key => $value){

        $noidung .= "<ul style='border:1px solid blue; margin:10px;'>

            <li>".$value['tensp']."</li>

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

*/
//header('Location:../../index.php?quanly=camon');
}
?>