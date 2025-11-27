<?php
    // SỬA LỖI 1: Kiểm tra kết nối CSDL
    // Nếu chưa có biến $conn (nghĩa là chưa include config từ index), thì mới include.
    // Đường dẫn tính từ thư mục gốc (nơi chứa file index.php)
    if (!isset($conn)) {
        if(file_exists('admincp/config/config.php')){
            include('admincp/config/config.php');
        } else {
            // Trường hợp dự phòng nếu cấu trúc thư mục khác
            include('../../admincp/config/config.php'); 
        }
    }

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
?>

<div class="container py-4">
    <div class="arrow-steps clearfix mb-4">
        <div class="step done"> <span><a href="index.php?quanly=giohang">Giỏ hàng</a></span> </div>
        <div class="step current"> <span><a href="index.php?quanly=vanchuyen">Vận chuyển</a></span> </div>
        <div class="step"> <span><a href="index.php?quanly=thongtinthanhtoan">Thanh toán</a></span> </div>
    </div>

    <?php
        if (isset($_SESSION['id_khachhang'])) {
            $id_dangky = $_SESSION['id_khachhang'];
        } else {
            echo '<div class="alert alert-danger">Bạn cần đăng nhập để nhập thông tin vận chuyển.</div>';
            exit;
        }

        // --- XỬ LÝ: LUÔN LUÔN INSERT (THÊM MỚI) ---
        if (isset($_POST['themvanchuyen']) || isset($_POST['capnhatvanchuyen'])) {
            $name = mysqli_real_escape_string($conn, $_POST['name']);
            $phone = mysqli_real_escape_string($conn, $_POST['phone']);
            $address = mysqli_real_escape_string($conn, $_POST['address']);
            $note = mysqli_real_escape_string($conn, $_POST['note']);

            // Luôn dùng INSERT để tạo ID shipping mới
            $sql_insert_vanchuyen = "INSERT INTO tbl_shipping(name, phone, address, note, id_dangky) VALUES('$name','$phone','$address','$note','$id_dangky')";
            $query_insert = mysqli_query($conn, $sql_insert_vanchuyen);
            
            if($query_insert){
                echo '<script>window.location="index.php?quanly=thongtinthanhtoan";</script>';
            }
        }

        // Lấy thông tin mới nhất để điền vào form
        $sql_get_vanchuyen = mysqli_query($conn, "SELECT * FROM tbl_shipping WHERE id_dangky='$id_dangky' ORDER BY id_shipping DESC LIMIT 1");
        
        $name = ''; $phone = ''; $address = ''; $note = '';
        if ($sql_get_vanchuyen && mysqli_num_rows($sql_get_vanchuyen) > 0) {
            $row_get = mysqli_fetch_array($sql_get_vanchuyen);
            $name = $row_get['name'];
            $phone = $row_get['phone'];
            $address = $row_get['address'];
            $note = $row_get['note'];
        }
    ?>

    <div class="row">
        <div class="col-md-7 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h5 class="mb-0 text-primary"><i class="fa-solid fa-truck-fast"></i> Thông tin giao hàng</h5>
                </div>
                <div class="card-body">
                    <form action="" autocomplete="off" method="POST">
                        <div class="form-group">
                            <label class="font-weight-bold">Họ và tên</label>
                            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($name); ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Số điện thoại</label>
                            <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($phone); ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Địa chỉ nhận hàng</label>
                            <input type="text" name="address" class="form-control" value="<?php echo htmlspecialchars($address); ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Ghi chú</label>
                            <textarea name="note" class="form-control" rows="3"><?php echo htmlspecialchars($note); ?></textarea>
                        </div>

                        <button type="submit" name="themvanchuyen" class="btn btn-primary btn-block py-2 font-weight-bold">
                            <i class="fa-solid fa-check"></i> Xác nhận & Giao đến địa chỉ này
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fa-solid fa-cart-shopping"></i> Tóm tắt đơn hàng</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 text-center">
                            <thead class="text-muted" style="font-size: 14px;">
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>SL</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
                                $tongtien = 0;
                                foreach ($_SESSION['cart'] as $cart_item) {
                                    $thanhtien = $cart_item['soluong'] * $cart_item['giasp'];
                                    $tongtien += $thanhtien;
                            ?>
                                <tr>
                                    <td class="text-left align-middle">
                                        <div class="d-flex align-items-center">
                                            <img src="admincp/modules/quanlysp/uploads/<?php echo $cart_item['hinhanh']; ?>" 
                                                 style="width: 40px; height: 40px; object-fit: cover; margin-right: 10px; border-radius: 4px;">
                                            <span style="font-size: 14px;"><?php echo $cart_item['tensp']; ?></span>
                                        </div>
                                    </td>
                                    <td class="align-middle font-weight-bold"><?php echo $cart_item['soluong']; ?></td>
                                    <td class="align-middle text-danger font-weight-bold" style="font-size: 14px;">
                                        <?php echo number_format($thanhtien, 0, ',', '.'); ?>đ
                                    </td>
                                </tr>
                            <?php
                                }
                            ?>
                                <tr class="bg-light">
                                    <td colspan="2" class="text-right font-weight-bold">Tổng cộng:</td>
                                    <td class="text-danger font-weight-bold" style="font-size: 18px;">
                                        <?php echo number_format($tongtien, 0, ',', '.'); ?>đ
                                    </td>
                                </tr>
                            <?php
                            } else {
                                echo '<tr><td colspan="3" class="text-center py-3">Giỏ hàng trống</td></tr>';
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>