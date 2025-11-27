<?php
    session_start();
    include('../../admincp/config/config.php');
    require_once('config_vnpay.php');

    if(!isset($_SESSION['id_khachhang'])){
        echo "<script>alert('Vui lòng đăng nhập lại!'); window.location.href='../../index.php?quanly=dangnhap';</script>";
        exit();
    }

    $id_khachhang = $_SESSION['id_khachhang'];
    $code_order = rand(0,9999);
    $cart_payment = isset($_POST['payment']) ? $_POST['payment'] : 'tienmat';

    // 1. LẤY ID SHIPPING MỚI NHẤT
    // Vì file vanchuyen.php đã INSERT dòng mới, nên ta chỉ cần lấy dòng mới nhất đó.
    $id_dangky = $_SESSION['id_khachhang'];
    $sql_get_shipping = mysqli_query($conn, "SELECT id_shipping FROM tbl_shipping WHERE id_dangky='$id_dangky' ORDER BY id_shipping DESC LIMIT 1");
    
    if(mysqli_num_rows($sql_get_shipping) > 0){
        $row_shipping = mysqli_fetch_array($sql_get_shipping);
        $id_shipping_new = $row_shipping['id_shipping'];
    } else {
        $id_shipping_new = 0; // Trường hợp lỗi
    }

    // Tính tổng tiền
    $tongtien = 0;
    if(isset($_SESSION['cart'])){
        foreach($_SESSION['cart'] as $key => $value){
            $tongtien += ($value['soluong'] * $value['giasp']);
        }
    }

    // --- XỬ LÝ INSERT ĐƠN HÀNG (DÙNG CHUNG HÀM ĐỂ GỌN CODE) ---
    function createOrder($conn, $id_khachhang, $code_order, $cart_payment, $id_shipping_new) {
        // Insert Cart
        $insert_cart = "INSERT INTO tbl_cart(id_khachhang, code_cart, cart_status, cart_date, cart_payment, cart_shipping) 
                        VALUES('$id_khachhang','$code_order',1,NOW(),'$cart_payment','$id_shipping_new')";
        $cart_query = mysqli_query($conn, $insert_cart);
        
        // Insert Details
        if($cart_query && isset($_SESSION['cart'])){
            foreach($_SESSION['cart'] as $key => $value){
                $id_sanpham = $value['id'];
                $soluong = $value['soluong'];
                $insert_details = "INSERT INTO tbl_cart_details(id_sanpham, code_cart, soluongmua) 
                                   VALUES('$id_sanpham','$code_order','$soluong')";
                mysqli_query($conn, $insert_details);
            }
        }
    }

    // --- PHÂN LOẠI THANH TOÁN ---

    if($cart_payment == 'tienmat' || $cart_payment == 'chuyenkhoan'){
        createOrder($conn, $id_khachhang, $code_order, $cart_payment, $id_shipping_new);
        unset($_SESSION['cart']);
        header('Location:../../index.php?quanly=camon');

    } elseif($cart_payment == 'vnpay'){
        // Thanh toán VNPay
        $vnp_TxnRef = $code_order; 
        $vnp_OrderInfo = 'Thanh toan don hang';
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $tongtien * 100;
        $vnp_Locale = 'vn';
        $vnp_BankCode = 'NCB';
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
        $vnp_ExpireDate = date('YmdHis',strtotime('+15 minutes'));

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_ExpireDate" => $vnp_ExpireDate
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
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        if(isset($_POST['redirect'])){
            // Tạo đơn hàng trước khi chuyển hướng
            createOrder($conn, $id_khachhang, $code_order, $cart_payment, $id_shipping_new);
             $_SESSION['code_cart'] = $code_order; // Không cần thiết nếu đã lưu DB
            header('Location: ' . $vnp_Url);
            die();
        } else {
            echo json_encode(array('code' => '00', 'message' => 'success', 'data' => $vnp_Url));
        }
    }
?>