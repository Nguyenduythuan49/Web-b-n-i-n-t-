<?php
// Bắt đầu session nếu chưa được bắt đầu
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include('admincp/config/config.php');
$now = date('Y-m-d H:i:s');

// ==================================================================
// --- KHỐI XỬ LÝ VNPAY ---
// ==================================================================
if(isset($_GET['vnp_Amount'])){

    $vnp_Amount = $_GET['vnp_Amount'];
    $vnp_BankCode = $_GET['vnp_BankCode'];
    $vnp_BankTranNo = $_GET['vnp_BankTranNo'];
    $vnp_OrderInfo = $_GET['vnp_OrderInfo'];
    $vnp_PayDate = $_GET['vnp_PayDate'];
    $vnp_TmnCode = $_GET['vnp_TmnCode'];
    $vnp_TransactionNo = $_GET['vnp_TransactionNo'];
    $vnp_CardType = $_GET['vnp_CardType'];
    
    // Giả định 'code_cart' được lưu vào session từ trang 'xulythanhtoan.php'
    $code_cart = isset($_SESSION['code_cart']) ? $_SESSION['code_cart'] : null;

    if ($code_cart) {
        $insert_vnpay = "INSERT INTO tbl_vnpay(
            vnp_amount, code_cart, vnp_bankcode, vnp_banktranno, vnp_cardtype, vnp_orderinfo, vnp_paydate, vnp_tmncode, vnp_transactionno
        ) VALUE(
            '".$vnp_Amount."', '".$code_cart."', '".$vnp_BankCode."', '".$vnp_BankTranNo."', '".$vnp_CardType."', 
            '".$vnp_OrderInfo."', '".$vnp_PayDate."', '".$vnp_TmnCode."', '".$vnp_TransactionNo."'
        )";
        $cart_query = mysqli_query($conn, $insert_vnpay);

        if($cart_query){
            echo'<h3>✅ Giao dịch thanh toán bằng VNPAY thành công</h3>';
            echo '<p>Vui lòng vào trang <a target="_blank" href="index.php?quanly=lichsudonhang">Lịch sử đơn hàng</a> để xem chi tiết đơn hàng đã đặt</p>';
            
        }else{
            echo'<h3>❌ Có lỗi xảy ra trong quá trình lưu thông tin VNPAY</h3>';
            echo '<p>Lỗi Database VNPAY: ' . mysqli_error($conn) . '</p>';
        }
    } else {
        echo '<h3>❌ Lỗi: Không tìm thấy mã đơn hàng (code_cart) trong Session.</h3>';
    }

}
// ==================================================================
// --- KHỐI XỬ LÝ MOMO ---
// ==================================================================
elseif(isset($_GET['partnerCode'])){
    
    // Lấy thông tin MoMo
    $id_khachhang = $_SESSION['id_khachhang'];
    $code_order = rand(0,9999);
    $partnerCode = $_GET['partnerCode'];
    $orderId = $_GET['orderId'];
    $amount = $_GET['amount'];
    $orderInfo = $_GET['orderInfo'];
    $orderType = $_GET['orderType'];
    $transId = $_GET['transId'];
    $payType = $_GET['payType'];
    $cart_payment = 'momo';

    // Lấy thông tin shipping
    $sql_get_vanchuyen = mysqli_query($conn, "SELECT * FROM tbl_shipping WHERE id_dangky='$id_khachhang' LIMIT 1");
    $row_get_vanchuyen = mysqli_fetch_array($sql_get_vanchuyen);
    $id_shipping = $row_get_vanchuyen['id_shipping'];

    // 1. Insert vào tbl_momo
    // Sửa lỗi "Unknown column": Đảm bảo cột trong DB là 'partner_code' (chữ thường)
    $insert_momo = "INSERT INTO tbl_momo(partner_code, order_Id, amount, order_Info, order_Type, trans_Id, pay_Type,code_cart) VALUE(
            '".$partnerCode."', '".$orderId."', '".$amount."', '".$orderInfo."', '".$orderType."', '".$transId."', '".$payType."','".$code_order."'
        )";
    $momo_query = mysqli_query($conn, $insert_momo);
        
    if($momo_query){
        // 2. Insert vào tbl_cart (Đơn hàng chính)
        $insert_cart = "INSERT INTO tbl_cart(id_khachhang, code_cart, cart_status, cart_date, cart_payment, cart_shipping)
        VALUES('$id_khachhang','$code_order','1','$now','$cart_payment','$id_shipping')";
        $cart_query = mysqli_query($conn, $insert_cart);

        // 3. Insert vào tbl_cart_details (Chi tiết đơn hàng)
        
        // *** SỬA LỖI 1 & 2: Kiểm tra giỏ hàng có tồn tại không (để tránh lỗi khi F5) ***
        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            foreach($_SESSION['cart'] as $key => $value){
                $id_sanpham = $value['id'];
                $soluong = $value['soluong'];
                $insert_order_details = "INSERT INTO tbl_cart_details(id_sanpham, code_cart, soluongmua)
                VALUES('$id_sanpham','$code_order','$soluong')";
                mysqli_query($conn, $insert_order_details);
            }
            // Xóa giỏ hàng CHỈ KHI nó tồn tại và đã được xử lý
            unset($_SESSION['cart']); 
        }
        
        // *** SỬA LỖI 3: Thông báo thành công NẰM TRONG khối MoMo ***
        echo'<h3>✅ Giao dịch thanh toán bằng Momo thành công</h3>';
        echo '<p>Vui lòng vào trang <a target="_blank" href="index.php?quanly=lichsudonhang">Lịch sử đơn hàng</a> để xem chi tiết đơn hàng đã đặt</p>';
        
    }else{
        echo'<h3>❌ Có lỗi xảy ra trong quá trình lưu thông tin Momo</h3>';
        echo '<p>Lỗi Database Momo: ' . mysqli_error($conn) . '</p>';
    }

}
// ==================================================================
// --- KHỐI XỬ LÝ PAYPAL / TIỀN MẶT ---
// ==================================================================
else { 
    
    // Chỉ xử lý PayPal NẾU có tham số 'thanhtoan=paypal'
    if(isset($_GET['thanhtoan']) && $_GET['thanhtoan'] == 'paypal'){ 
        
        $code_order = rand(0,9999);
        $cart_payment = 'paypal';
        $id_khachhang = isset($_SESSION['id_khachhang']) ? $_SESSION['id_khachhang'] : null;
        
        if ($id_khachhang) {
            $sql_get_vanchuyen = mysqli_query($conn, "SELECT * FROM tbl_shipping WHERE id_dangky='$id_khachhang' LIMIT 1");
            $row_get_vanchuyen = mysqli_fetch_array($sql_get_vanchuyen);
            
            if ($row_get_vanchuyen) {
                $id_shipping = $row_get_vanchuyen['id_shipping'];

                // 1. Insert đơn hàng
                $insert_cart = "INSERT INTO tbl_cart(id_khachhang, code_cart, cart_status, cart_date, cart_payment, cart_shipping)
                VALUES('".$id_khachhang."','".$code_order."',1,'".$now."','".$cart_payment."','".$id_shipping."')";
                $cart_query = mysqli_query($conn, $insert_cart);
                
                if ($cart_query) {
                    // 2. Insert chi tiết đơn hàng (Kiểm tra giỏ hàng trước)
                    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
                        foreach($_SESSION['cart'] as $key => $value){
                            $id_sanpham = $value['id'];
                            $soluong = $value['soluong'];
                            $insert_order_details = "INSERT INTO tbl_cart_details(id_sanpham, code_cart, soluongmua)
                            VALUES('$id_sanpham','$code_order','$soluong')";
                            mysqli_query($conn, $insert_order_details);
                            
                        }
                        unset($_SESSION['cart']); // Xóa giỏ hàng
                    }
                    
                    
                    // *** SỬA LỖI 3: Thông báo thành công NẰM TRONG khối PayPal ***
                    echo'<h3>✅ Giao dịch thanh toán bằng Paypal thành công</h3>';
                    echo '<p>Vui lòng vào trang <a target="_blank" href="index.php?quanly=lichsudonhang">Lịch sử đơn hàng</a> để xem chi tiết đơn hàng đã đặt</p>';

                } else {
                    echo '<h3>❌ Lỗi Database: Không thể tạo đơn hàng (tbl_cart)</h3>';
                    echo '<p>Lỗi MySQL: ' . mysqli_error($conn) . '</p>';
                }
            } else {
                echo '<h3>❌ Lỗi: Không tìm thấy thông tin vận chuyển (Shipping) của bạn.</h3>';
            }
        } else {
            echo '<h3>❌ Lỗi: Không tìm thấy ID khách hàng trong Session. Vui lòng đăng nhập lại.</h3>';
        }
    
    } else {
        // Các trường hợp khác (ví dụ: Thanh toán tiền mặt đã xử lý, hoặc F5 lại trang)
        echo'<h3>✅ Đặt hàng thành công!</h3>';
        echo '<p>Cảm ơn bạn đã mua hàng, chúng tôi sẽ liên hệ với bạn sớm nhất có thể để xử lý.</p>';
    }
} // Kết thúc khối else (PayPal/Tiền mặt)
?>