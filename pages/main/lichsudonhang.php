<h3>Lịch sử đơn hàng</h3>
<?php
    // BẢO MẬT: Đảm bảo người dùng đã đăng nhập và ép kiểu ID thành số nguyên
    if (!isset($_SESSION['id_khachhang'])) {
        echo "<p>Vui lòng đăng nhập để xem lịch sử đơn hàng.</p>";
        // Dừng tệp nếu chưa đăng nhập
        return; 
    }
    $id_khachhang = (int)$_SESSION['id_khachhang'];

    $sql_lietke_dh = "SELECT * FROM tbl_cart, tbl_dangky 
                      WHERE tbl_cart.id_khachhang = tbl_dangky.id_dangky 
                      AND tbl_cart.id_khachhang = $id_khachhang 
                      ORDER BY tbl_cart.id_cart DESC";
    
    $query_lietke_dh = mysqli_query($conn, $sql_lietke_dh);
?>
<table style="width:100%; border-collapse: collapse;" border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Mã đơn hàng</th>
            <th>Tên khách hàng</th>
            <th>Số điện thoại</th>
            <th>Địa chỉ</th>
            <th>Email</th>
            <th>Tình Trạng</th>
            <th>Quản lý</th>
            <th>In </th>
            <th>Hình thức thanh toán </th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 0;
        // SỬA LOGIC: Kiểm tra xem có đơn hàng nào không
        if (mysqli_num_rows($query_lietke_dh) > 0) {
            while($row = mysqli_fetch_array($query_lietke_dh)){
                $i++;
        ?>
        <tr>
            <td><?php echo $i ?></td>
            <td><?php echo htmlspecialchars($row['code_cart']); ?></td>
            <td><?php echo htmlspecialchars($row['tenkhachhang']); ?></td>
            <td><?php echo htmlspecialchars($row['sodienthoai']); ?></td>
            <td><?php echo htmlspecialchars($row['diachi']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td>
                <?php 
                if($row['cart_status'] == 1){
                    // Không nên để link xử lý của admin ở trang khách hàng
                    echo 'Đơn hàng mới'; 
                } else {
                    echo 'Đã xử lý';
                } 
                ?>
            </td>
            <td> 
                <a href="index.php?quanly=xemdonhang&code=<?php echo htmlspecialchars($row['code_cart']); ?>">Xem đơn hàng</a> 
            </td>
            <td>
                <a href="main/indonhang.php?code=<?php echo htmlspecialchars($row['code_cart']); ?>" target="_blank">In đơn hàng</a>
            </td>
            <td>
                <?php
                $payment_method = htmlspecialchars($row['cart_payment']);
                if($payment_method == 'vnpay' || $payment_method == 'momo'){
                ?>
                    <a href="index.php?quanly=lichsudonhang&congthanhtoan=<?php echo $payment_method; ?>&code_cart=<?php echo htmlspecialchars($row['code_cart']); ?>">
                        <?php echo $payment_method; ?>
                    </a>
                <?php
                } else {
                    echo $payment_method; // Ví dụ: 'tienmat'
                }
                ?>
            </td>
        </tr>
        <?php
            } // Kết thúc while
        } else {
            // Hiển thị thông báo nếu không có đơn hàng
            echo '<tr><td colspan="10" style="text-align:center;">Bạn chưa có đơn hàng nào.</td></tr>';
        }
        ?>
    </tbody>
</table>

<hr style="margin-top: 20px;">

<?php
// --- KHỐI HIỂN THỊ CHI TIẾT THANH TOÁN (ĐÃ SỬA LỖI) ---
if(isset($_GET['congthanhtoan'])){
    $congthanhtoan = $_GET['congthanhtoan'];
    
    // BẢO MẬT (SQL INJECTION): Làm sạch biến code_cart trước khi dùng
    $code_cart_safe = mysqli_real_escape_string($conn, $_GET['code_cart']);
    
    echo '<h4>Chi tiết thanh toán qua cổng thanh toán: '.htmlspecialchars($congthanhtoan).'</h4>';
    
    // --- Xử lý MoMo ---
    if($congthanhtoan == 'momo'){
        
        $sql_momo = mysqli_query($conn, "SELECT * FROM tbl_momo WHERE code_cart='".$code_cart_safe."' LIMIT 1");
        
        // SỬA LỖI (NULL): Kiểm tra xem $sql_momo có kết quả không
        if ($sql_momo && mysqli_num_rows($sql_momo) > 0) {
            $row_momo = mysqli_fetch_array($sql_momo);
        ?>
            <table class="table table-bordered" style="width:100%; border-collapse: collapse;" border="1">
                <thead>
                    <tr>
                        <th>Partner_code</th>
                        <th>Order_id</th>
                        <th>Amount</th>
                        <th>Order_info</th>
                        <th>Order_Type</th>
                        <th>Trans_id</th>
                        <th>Pay_type</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo htmlspecialchars($row_momo['partner_code']); ?></td>
                        <td><?php echo htmlspecialchars($row_momo['order_id']); ?></td>
                        <td><?php echo number_format($row_momo['amount'], 0, ',', '.').' VNĐ'; ?></td>
                        <td><?php echo htmlspecialchars($row_momo['order_info']); ?></td>
                        <td><?php echo htmlspecialchars($row_momo['order_type']); ?></td>
                        <td><?php echo htmlspecialchars($row_momo['trans_id']); ?></td>
                        <td><?php echo htmlspecialchars($row_momo['pay_type']); ?></td>
                    </tr>
                </tbody>
            </table>
        <?php
        } else {
            // Hiển thị nếu không tìm thấy
            echo '<p>Không tìm thấy chi tiết giao dịch MoMo cho mã đơn hàng: '.htmlspecialchars($_GET['code_cart']).'</p>';
        }

    // --- Xử lý VNPAY ---
    } elseif($congthanhtoan == 'vnpay'){
        
        $sql_vnpay = mysqli_query($conn, "SELECT * FROM tbl_vnpay WHERE code_cart='".$code_cart_safe."' LIMIT 1");
        
        // SỬA LỖI (NULL): Kiểm tra xem $sql_vnpay có kết quả không
        if ($sql_vnpay && mysqli_num_rows($sql_vnpay) > 0) {
            $row_vnpay = mysqli_fetch_array($sql_vnpay);
        ?>
            <table class="table table-bordered" style="width:100%; border-collapse: collapse;" border="1">
                <thead>
                    <tr>
                        <th>vnp_amount</th>
                        <th>vnp_bankcode</th>
                        <th>vnp_banktranno</th>
                        <th>vnp_orderinfo</th>
                        <th>vnp_paydate</th>
                        <th>vnp_tmncode</th>
                        <th>vnp_transactionno</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo number_format($row_vnpay['vnp_amount'] / 100, 0, ',', '.').' VNĐ'; ?></td>
                        <td><?php echo htmlspecialchars($row_vnpay['vnp_bankcode']); ?></td>
                        <td><?php echo htmlspecialchars($row_vnpay['vnp_banktranno']); ?></td>
                        <td><?php echo htmlspecialchars($row_vnpay['vnp_orderinfo']); ?></td>
                        <td><?php echo htmlspecialchars($row_vnpay['vnp_paydate']); ?></td>
                        <td><?php echo htmlspecialchars($row_vnpay['vnp_tmncode']); ?></td>
                        <td><?php echo htmlspecialchars($row_vnpay['vnp_transactionno']); ?></td>
                    </tr>
                </tbody>
            </table>
        <?php
        } else {
            // Hiển thị nếu không tìm thấy
            echo '<p>Không tìm thấy chi tiết giao dịch VNPAY cho mã đơn hàng: '.htmlspecialchars($_GET['code_cart']).'</p>';
        }
    }
}
?>