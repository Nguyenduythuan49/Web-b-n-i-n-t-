<?php
    // 1. KHỞI TẠO VÀ KẾT NỐI
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $projectRoot = dirname(__DIR__, 2); 
    $configPath = $projectRoot . '/admincp/config/config.php';

    if (!file_exists($configPath)) {
        die("Không tìm thấy file cấu hình: {$configPath}");
    }

    include_once $configPath;
?>

<!-- BẮT ĐẦU GIAO DIỆN -->
<div class="container py-4">
    
    <!-- Thanh tiến trình (Arrow Steps) -->
    <div class="arrow-steps clearfix mb-4">
        <div class="step done"> <span><a href="index.php?quanly=giohang">Giỏ hàng</a></span> </div>
        <div class="step current"> <span><a href="index.php?quanly=vanchuyen">Vận chuyển</a></span> </div>
        <div class="step"> <span><a href="index.php?quanly=thongtinthanhtoan">Thanh toán</a></span> </div>
        <!-- <div class="step"> <span><a href="index.php?quanly=donhangdadat">Lịch sử đơn hàng</a></span> </div> -->
    </div>

    <?php
        // Lấy id khách hàng
        if (isset($_SESSION['id_khachhang'])) {
            $id_dangky = $_SESSION['id_khachhang'];
        } else {
            echo '<div class="alert alert-danger">Bạn cần đăng nhập để nhập thông tin vận chuyển.</div>';
            exit;
        }

        // --- XỬ LÝ PHP: THÊM / CẬP NHẬT ---
        if (isset($_POST['themvanchuyen']) || isset($_POST['capnhatvanchuyen'])) {
            $name = isset($_POST['name']) ? mysqli_real_escape_string($conn, trim($_POST['name'])) : '';
            $phone = isset($_POST['phone']) ? mysqli_real_escape_string($conn, trim($_POST['phone'])) : '';
            $address = isset($_POST['address']) ? mysqli_real_escape_string($conn, trim($_POST['address'])) : '';
            $note = isset($_POST['note']) ? mysqli_real_escape_string($conn, trim($_POST['note'])) : '';

            $check_shipping = mysqli_query($conn, "SELECT * FROM tbl_shipping WHERE id_dangky='$id_dangky' LIMIT 1");

            if (mysqli_num_rows($check_shipping) > 0) {
                // Cập nhật
                $sql_update_vanchuyen = "UPDATE tbl_shipping SET name='$name', phone='$phone', address='$address', note='$note' WHERE id_dangky='$id_dangky'";
                mysqli_query($conn, $sql_update_vanchuyen);
                echo '<script>alert("Cập nhật thông tin vận chuyển thành công!"); window.location="index.php?quanly=thongtinthanhtoan";</script>';
            } else {
                // Thêm mới
                $sql_insert_vanchuyen = "INSERT INTO tbl_shipping(name, phone, address, note, id_dangky) VALUES('$name','$phone','$address','$note','$id_dangky')";
                mysqli_query($conn, $sql_insert_vanchuyen);
                echo '<script>alert("Thêm thông tin vận chuyển thành công!"); window.location="index.php?quanly=thongtinthanhtoan";</script>';
            }
        }

        // Lấy dữ liệu cũ để điền vào form
        $sql_get_vanchuyen = mysqli_query($conn, "SELECT * FROM tbl_shipping WHERE id_dangky='$id_dangky' LIMIT 1");
        $count = mysqli_num_rows($sql_get_vanchuyen);
        
        $name = ''; $phone = ''; $address = ''; $note = '';
        if ($count > 0) {
            $row_get = mysqli_fetch_assoc($sql_get_vanchuyen);
            $name = $row_get['name'];
            $phone = $row_get['phone'];
            $address = $row_get['address'];
            $note = $row_get['note'];
        }
    ?>

    <!-- CHIA CỘT: TRÁI (FORM) - PHẢI (GIỎ HÀNG) -->
    <div class="row">
        
        <!-- CỘT TRÁI: FORM NHẬP LIỆU -->
        <div class="col-md-7 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h5 class="mb-0 text-primary"><i class="fa-solid fa-truck-fast"></i> Thông tin giao hàng</h5>
                </div>
                <div class="card-body">
                    <form action="" autocomplete="off" method="POST">
                        <div class="form-group">
                            <label class="font-weight-bold">Họ và tên</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                                </div>
                                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($name); ?>" placeholder="Nguyễn Văn A" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="font-weight-bold">Số điện thoại</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                </div>
                                <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($phone); ?>" placeholder="09xxxxxxxxx" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Địa chỉ nhận hàng</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-map-marker-alt"></i></span>
                                </div>
                                <input type="text" name="address" class="form-control" value="<?php echo htmlspecialchars($address); ?>" placeholder="Số nhà, tên đường, phường xã..." required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Ghi chú (Tùy chọn)</label>
                            <textarea name="note" class="form-control" rows="3" placeholder="Ví dụ: Giao giờ hành chính..."><?php echo htmlspecialchars($note); ?></textarea>
                        </div>

                        <?php if ($name == '' && $phone == '') { ?>
                            <button type="submit" name="themvanchuyen" class="btn btn-primary btn-block py-2 font-weight-bold">
                                <i class="fa-solid fa-check"></i> Xác nhận & Giao đến địa chỉ này
                            </button>
                        <?php } else { ?>
                            <button type="submit" name="capnhatvanchuyen" class="btn btn-success btn-block py-2 font-weight-bold">
                                <i class="fa-solid fa-pen-to-square"></i> Cập nhật & Giao đến địa chỉ này
                            </button>
                        <?php } ?>
                    </form>
                </div>
            </div>
        </div>

        <!-- CỘT PHẢI: TÓM TẮT GIỎ HÀNG -->
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
                                    <th>Số Lượng</th>
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
                <div class="card-footer bg-white text-center text-muted" style="font-size: 13px;">
                    <i class="fa-solid fa-shield-halved"></i> Thông tin được bảo mật tuyệt đối
                </div>
            </div>
        </div>

    </div> <!-- End Row -->
</div>