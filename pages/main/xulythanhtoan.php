<?php
    session_start();
    include('../../admincp/config/config.php');
    require_once('config_vnpay.php');

    // KIỂM TRA ĐĂNG NHẬP
    if(!isset($_SESSION['id_khachhang'])){
        echo "<script>alert('Vui lòng đăng nhập lại để thanh toán!'); window.location.href='../../index.php?quanly=dangnhap';</script>";
        exit();
    }

    $id_khachhang = $_SESSION['id_khachhang'];
    $code_order = rand(0,9999);
    $cart_payment = $_POST['payment'];
    
    // --- SỬA LỖI DATA TRUNCATED Ở ĐÂY ---
    // Đổi format từ 'Y-m-d H:i:s' thành 'Y-m-d' để khớp với cột DATE trong database
    $date_order = date('Y-m-d'); 
    
    // Lấy id thông tin vận chuyển
    $id_dangky = $_SESSION['id_khachhang'];
    $sql_get_vanchuyen = mysqli_query($conn, "SELECT * FROM tbl_shipping WHERE id_dangky='$id_dangky' LIMIT 1");
    
    if($sql_get_vanchuyen && mysqli_num_rows($sql_get_vanchuyen) > 0){
        $row_get_vanchuyen = mysqli_fetch_array($sql_get_vanchuyen);
        $id_shipping = $row_get_vanchuyen['id_shipping'];
    } else {
        $id_shipping = 0;
    }

    $tongtien = 0;
    if(isset($_SESSION['cart'])){
        foreach($_SESSION['cart'] as $key => $value){
            $thanhtien = $value['soluong'] * $value['giasp'];
            $tongtien += $thanhtien;
        }
    }

    // --- THANH TOÁN TIỀN MẶT / CHUYỂN KHOẢN ---
    if($cart_payment == 'tienmat' || $cart_payment == 'chuyenkhoan'){
        // Insert đơn hàng
        $insert_cart = "INSERT INTO tbl_cart(id_khachhang, code_cart, cart_status, cart_date, cart_payment, cart_shipping) 
        VALUES('$id_khachhang','$code_order',1,'$date_order','$cart_payment','$id_shipping')";
        
        $cart_query = mysqli_query($conn, $insert_cart);

        if($cart_query){
            // Insert chi tiết đơn hàng
            foreach($_SESSION['cart'] as $key => $value){
                $id_sanpham = $value['id'];
                $soluong = $value['soluong'];
                $insert_order_details = "INSERT INTO tbl_cart_details(id_sanpham, code_cart, soluongmua) 
                VALUES('$id_sanpham','$code_order','$soluong')";
                mysqli_query($conn, $insert_order_details);
            }
            
            unset($_SESSION['cart']);
            header('Location:../../index.php?quanly=camon');
            exit();
        }

    // --- THANH TOÁN VNPAY ---
    } elseif($cart_payment == 'vnpay'){
        
        $vnp_TxnRef = $code_order; 
        $vnp_OrderInfo = 'Thanh toan don hang dat tai web';
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $tongtien * 100;
        $vnp_Locale = 'vn';
        $vnp_BankCode = 'NCB';
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
        
        // Thời gian hết hạn thanh toán (dùng format đầy đủ cho VNPAY yêu cầu)
        $vnp_ExpireDate = isset($expire) ? $expire : date('YmdHis',strtotime('+15 minutes'));

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
            
            // Insert đơn hàng VNPAY (Dùng $date_order chỉ có ngày)
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