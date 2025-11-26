<?php
    session_start();
    include('../../admincp/config/config.php');
    require_once('config_vnpay.php');

    // KIỂM TRA ĐĂNG NHẬP
    // Nếu chưa đăng nhập hoặc mất session thì dừng lại ngay để tránh lỗi Fatal Error
    if(!isset($_SESSION['id_khachhang'])){
        echo "<script>alert('Vui lòng đăng nhập lại để thanh toán!'); window.location.href='../../index.php?quanly=dangnhap';</script>";
        exit();
    }

    $id_khachhang = $_SESSION['id_khachhang'];
    $code_order = rand(0,9999);
    $cart_payment = $_POST['payment'];
    
    // SỬA LỖI THỜI GIAN: Dùng $date_order cho thống nhất
    $date_order = date('Y-m-d H:i:s');
    
    // Lấy id thông tin vận chuyển
    $id_dangky = $_SESSION['id_khachhang'];
    $sql_get_vanchuyen = mysqli_query($conn, "SELECT * FROM tbl_shipping WHERE id_dangky='$id_dangky' LIMIT 1");
    
    if($sql_get_vanchuyen && mysqli_num_rows($sql_get_vanchuyen) > 0){
        $row_get_vanchuyen = mysqli_fetch_array($sql_get_vanchuyen);
        $id_shipping = $row_get_vanchuyen['id_shipping'];
    } else {
        $id_shipping = 0; // Xử lý trường hợp chưa có địa chỉ shipping
    }

    $tongtien = 0;
    if(isset($_SESSION['cart'])){
        foreach($_SESSION['cart'] as $key => $value){
            $thanhtien = $value['soluong'] * $value['giasp'];
            $tongtien += $thanhtien;
        }
    }

    // --- TRƯỜNG HỢP 1: THANH TOÁN TIỀN MẶT / CHUYỂN KHOẢN ---
    if($cart_payment == 'tienmat' || $cart_payment == 'chuyenkhoan'){
        // Thêm đơn hàng vào bảng tbl_cart
        // Đã sửa biến $now thành $date_order
        $insert_cart = "INSERT INTO tbl_cart(id_khachhang, code_cart, cart_status, cart_date, cart_payment, cart_shipping) 
        VALUES('$id_khachhang','$code_order',1,'$date_order','$cart_payment','$id_shipping')";
        
        $cart_query = mysqli_query($conn, $insert_cart);

        if($cart_query){
            // Thêm đơn hàng chi tiết
            foreach($_SESSION['cart'] as $key => $value){
                $id_sanpham = $value['id'];
                $soluong = $value['soluong'];
                $insert_order_details = "INSERT INTO tbl_cart_details(id_sanpham, code_cart, soluongmua) 
                VALUES('$id_sanpham','$code_order','$soluong')";
                mysqli_query($conn, $insert_order_details);
            }
            
            unset($_SESSION['cart']);
            header('Location:../../index.php?quanly=camon');
            exit(); // Dừng code sau khi chuyển trang
        }

    // --- TRƯỜNG HỢP 2: THANH TOÁN VNPAY ---
    } elseif($cart_payment == 'vnpay'){
        
        $vnp_TxnRef = $code_order; 
        $vnp_OrderInfo = 'Thanh toan don hang dat tai web';
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $tongtien * 100;
        $vnp_Locale = 'vn';
        $vnp_BankCode = 'NCB';
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
        
        // Kiểm tra biến expire
        $vnp_ExpireDate = isset($expire) ? $expire : date('YmdHis',strtotime('+15 minutes',strtotime($date_order)));

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

        $returnData = array(
            'code' => '00', 
            'message' => 'success', 
            'data' => $vnp_Url
        );

        if(isset($_POST['redirect'])){
            $_SESSION['code_cart'] = $code_order;
            
            // Insert dữ liệu đơn hàng trước khi chuyển qua VNPAY
            $insert_cart = "INSERT INTO tbl_cart(id_khachhang, code_cart, cart_status, cart_date, cart_payment, cart_shipping) 
            VALUES('$id_khachhang','$code_order','1','$date_order','$cart_payment','$id_shipping')";
            
            $cart_query = mysqli_query($conn, $insert_cart);

            if($cart_query){
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
        } else {
            echo json_encode($returnData);
        }

    } elseif($cart_payment == 'paypal'){
        // Xử lý Paypal
    } elseif($cart_payment == 'momo'){
        // Xử lý Momo
    }
?>