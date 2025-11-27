<style>
    /* Tổng thể container */
    .history-wrapper {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        margin-bottom: 30px;
    }

    .history-title {
        color: #333;
        font-size: 24px;
        margin-bottom: 20px;
        border-bottom: 2px solid #007bff;
        display: inline-block;
        padding-bottom: 5px;
    }

    /* Style cho bảng */
    .custom-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 1rem;
        font-size: 14px;
    }

    .custom-table thead th {
        background-color: #007bff; /* Màu xanh chủ đạo */
        color: white;
        text-align: left;
        padding: 12px 10px;
        border: none;
        font-weight: 600;
        white-space: nowrap;
    }

    .custom-table tbody td {
        padding: 12px 10px;
        border-bottom: 1px solid #eee;
        color: #444;
        vertical-align: middle;
    }

    .custom-table tbody tr:hover {
        background-color: #f8f9fa; /* Hiệu ứng hover dòng */
    }

    /* Style cho nhãn trạng thái */
    .badge {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: bold;
        display: inline-block;
    }
    .badge-new {
        background-color: #e3f2fd;
        color: #0d47a1;
        border: 1px solid #90caf9;
    }
    .badge-done {
        background-color: #e8f5e9;
        color: #1b5e20;
        border: 1px solid #a5d6a7;
    }

    /* Style cho nút bấm/link */
    .btn-action {
        text-decoration: none;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 13px;
        transition: 0.2s;
        display: inline-block;
        margin-right: 5px;
    }
    .btn-view {
        color: #007bff;
        border: 1px solid #007bff;
    }
    .btn-view:hover {
        background-color: #007bff;
        color: white;
    }
    .btn-print {
        color: #6c757d;
        border: 1px solid #6c757d;
    }
    .btn-print:hover {
        background-color: #6c757d;
        color: white;
    }
    
    /* Responsive cho mobile */
    .table-responsive {
        overflow-x: auto;
    }
</style>

<div class="history-wrapper">
    <h3 class="history-title">Lịch sử đơn hàng</h3>
    <?php
    // BẢO MẬT: Đảm bảo người dùng đã đăng nhập
    if (!isset($_SESSION['id_khachhang'])) {
        echo "<p>Vui lòng đăng nhập để xem lịch sử đơn hàng.</p>";
        return; 
    }
    $id_khachhang = (int)$_SESSION['id_khachhang'];

    // --- SỬA CÂU TRUY VẤN (QUAN TRỌNG) ---
    // JOIN thêm bảng tbl_shipping dựa trên id được lưu trong đơn hàng (cart_shipping)
    // Dùng LEFT JOIN tbl_dangky để phòng hờ trường hợp cần lấy thông tin gốc
    $sql_lietke_dh = "SELECT * FROM tbl_cart 
                      LEFT JOIN tbl_shipping ON tbl_cart.cart_shipping = tbl_shipping.id_shipping
                      LEFT JOIN tbl_dangky ON tbl_cart.id_khachhang = tbl_dangky.id_dangky
                      WHERE tbl_cart.id_khachhang = '$id_khachhang' 
                      ORDER BY tbl_cart.id_cart DESC";
    
    $query_lietke_dh = mysqli_query($conn, $sql_lietke_dh);
    ?>
    
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Mã đơn</th>
                    <th>Ngày đặt</th> 
                    <th>Người nhận</th> <th>SĐT nhận</th>
                    <th>Địa chỉ giao hàng</th>
                    <th>Tình Trạng</th>
                    <th>Thao tác</th>
                    <th>Thanh toán</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 0;
                if (mysqli_num_rows($query_lietke_dh) > 0) {
                    while($row = mysqli_fetch_array($query_lietke_dh)){
                        $i++;
                        
                        // --- XỬ LÝ LOGIC HIỂN THỊ ---
                        // Ưu tiên lấy thông tin từ bảng shipping (name, phone, address)
                        // Nếu không có (đơn cũ quá chưa có id shipping) thì lấy từ bảng đăng ký (tenkhachhang...)
                        
                        $ten_nguoinhan = !empty($row['name']) ? $row['name'] : $row['tenkhachhang'];
                        $sdt_nguoinhan = !empty($row['phone']) ? $row['phone'] : $row['sodienthoai'];
                        $diachi_nguoinhan = !empty($row['address']) ? $row['address'] : $row['diachi'];
                ?>
                <tr>
                    <td><?php echo $i ?></td>
                    <td><b><?php echo htmlspecialchars($row['code_cart']); ?></b></td>
                    <td><?php echo isset($row['cart_date']) ? htmlspecialchars($row['cart_date']) : 'N/A'; ?></td>
                    
                    <td><?php echo htmlspecialchars($ten_nguoinhan); ?></td>
                    <td><?php echo htmlspecialchars($sdt_nguoinhan); ?></td>
                    <td style="max-width: 250px;"><?php echo htmlspecialchars($diachi_nguoinhan); ?></td>
                    
                    <td>
                        <?php 
                        if($row['cart_status'] == 1){
                            echo '<span class="badge badge-new">Đơn mới</span>'; 
                        } else {
                            echo '<span class="badge badge-done">Đã xử lý</span>';
                        } 
                        ?>
                    </td>
                    <td> 
                        <a href="index.php?quanly=xemdonhang&code=<?php echo htmlspecialchars($row['code_cart']); ?>" class="btn-action btn-view">Xem</a> 
                        <a href="main/indonhang.php?code=<?php echo htmlspecialchars($row['code_cart']); ?>" target="_blank" class="btn-action btn-print">In</a>
                    </td>
                    <td>
                        <?php
                        $payment_method = htmlspecialchars($row['cart_payment']);
                        if($payment_method == 'vnpay' || $payment_method == 'momo'){
                        ?>
                            <a href="index.php?quanly=lichsudonhang&congthanhtoan=<?php echo $payment_method; ?>&code_cart=<?php echo htmlspecialchars($row['code_cart']); ?>" style="color: #E91E63; font-weight:bold;">
                                <?php echo strtoupper($payment_method); ?>
                            </a>
                        <?php
                        } else {
                            echo ucfirst($payment_method); // Viết hoa chữ cái đầu
                        }
                        ?>
                    </td>
                </tr>
                <?php
                    } 
                } else {
                    echo '<tr><td colspan="9" style="text-align:center; padding: 20px;">Bạn chưa có đơn hàng nào.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php
    if(isset($_GET['congthanhtoan'])){
    ?>
    <hr style="border-top: 1px dashed #ccc; margin: 30px 0;">
    <?php
        $congthanhtoan = $_GET['congthanhtoan'];
        $code_cart_safe = mysqli_real_escape_string($conn, $_GET['code_cart']);
        
        echo '<h4 style="color:#007bff;">Chi tiết giao dịch: '.strtoupper(htmlspecialchars($congthanhtoan)).'</h4>';
        
        // --- Xử lý hiển thị bảng con (dùng chung class custom-table) ---
        
        if($congthanhtoan == 'momo'){
            $sql_momo = mysqli_query($conn, "SELECT * FROM tbl_momo WHERE code_cart='".$code_cart_safe."' LIMIT 1");
            if ($sql_momo && mysqli_num_rows($sql_momo) > 0) {
                $row_momo = mysqli_fetch_array($sql_momo);
        ?>
                <div class="table-responsive">
                <table class="custom-table" style="background: #fafafa; border: 1px solid #eee;">
                    <thead>
                        <tr>
                            <th>Partner Code</th>
                            <th>Mã GD</th>
                            <th>Số tiền</th>
                            <th>Nội dung</th>
                            <th>Loại GD</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo htmlspecialchars($row_momo['partner_code']); ?></td>
                            <td><?php echo htmlspecialchars($row_momo['trans_id']); ?></td>
                            <td style="color:red; font-weight:bold;"><?php echo number_format($row_momo['amount'], 0, ',', '.').' đ'; ?></td>
                            <td><?php echo htmlspecialchars($row_momo['order_info']); ?></td>
                            <td><?php echo htmlspecialchars($row_momo['pay_type']); ?></td>
                        </tr>
                    </tbody>
                </table>
                </div>
        <?php
            }
        } elseif($congthanhtoan == 'vnpay'){
            $sql_vnpay = mysqli_query($conn, "SELECT * FROM tbl_vnpay WHERE code_cart='".$code_cart_safe."' LIMIT 1");
            if ($sql_vnpay && mysqli_num_rows($sql_vnpay) > 0) {
                $row_vnpay = mysqli_fetch_array($sql_vnpay);
        ?>
                <div class="table-responsive">
                <table class="custom-table" style="background: #fafafa; border: 1px solid #eee;">
                    <thead>
                        <tr>
                            <th>Mã NH</th>
                            <th>Số tiền</th>
                            <th>Nội dung</th>
                            <th>Ngày thanh toán</th>
                            <th>Mã GD VNPAY</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo htmlspecialchars($row_vnpay['vnp_bankcode']); ?></td>
                            <td style="color:red; font-weight:bold;"><?php echo number_format($row_vnpay['vnp_amount'] / 100, 0, ',', '.').' đ'; ?></td>
                            <td><?php echo htmlspecialchars($row_vnpay['vnp_orderinfo']); ?></td>
                            <td><?php echo htmlspecialchars($row_vnpay['vnp_paydate']); ?></td>
                            <td><?php echo htmlspecialchars($row_vnpay['vnp_transactionno']); ?></td>
                        </tr>
                    </tbody>
                </table>
                </div>
        <?php
            }
        }
    }
    ?>
</div>