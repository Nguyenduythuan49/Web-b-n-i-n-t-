<?php
// pages/main/vanchuyen.php

// Chỉ khởi tạo session nếu chưa khởi tạo
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Tự động xác định đường dẫn tới file config (an toàn hơn so với include tương đối)
$projectRoot = dirname(__DIR__, 2); // nếu file này nằm ở pages/main => lên 2 cấp tới thư mục gốc
$configPath = $projectRoot . '/admincp/config/config.php';

// Nếu file config không tồn tại, dừng và thông báo đường dẫn hiện tại để debug
if (!file_exists($configPath)) {
    // Hiển thị đường dẫn đang tìm để bạn kiểm tra
    die("Không tìm thấy file cấu hình: {$configPath} . Vui lòng kiểm tra vị trí file config.php và chỉnh lại đường dẫn.");
}

// Include file config (kết nối DB)
include_once $configPath;
?>

<p>Vận chuyển</p>
<div class="container">
    <div class="arrow-steps clearfix">
        <div class="step done">
            <span><a href="index.php?quanly=giohang">Giỏ hàng</a></span>
        </div>
        <div class="step current">
            <span><a href="index.php?quanly=vanchuyen">Vận chuyển</a></span>
        </div>
        <div class="step">
            <span><a href="index.php?quanly=thongtinthanhtoan">Thanh toán</a></span>
        </div>
        <div class="step">
            <span><a href="index.php?quanly=donhangdadat">Lịch sử đơn hàng</a></span>
        </div>
    </div>

    <h4>Thông tin vận chuyển</h4>

    <?php
    // Lấy id khách hàng từ session
    if (isset($_SESSION['id_khachhang'])) {
        $id_dangky = $_SESSION['id_khachhang'];
    } else {
        echo '<p style="color:red;">Bạn cần đăng nhập để nhập thông tin vận chuyển.</p>';
        exit;
    }

    // XỬ LÝ FORM (cả thêm và cập nhật)
    if (isset($_POST['themvanchuyen']) || isset($_POST['capnhatvanchuyen'])) {
        // Lấy và escape dữ liệu từ form
        $name = isset($_POST['name']) ? mysqli_real_escape_string($conn, trim($_POST['name'])) : '';
        $phone = isset($_POST['phone']) ? mysqli_real_escape_string($conn, trim($_POST['phone'])) : '';
        $address = isset($_POST['address']) ? mysqli_real_escape_string($conn, trim($_POST['address'])) : '';
        $note = isset($_POST['note']) ? mysqli_real_escape_string($conn, trim($_POST['note'])) : '';

        // Kiểm tra xem khách hàng đã có thông tin vận chuyển chưa
        $check_shipping = mysqli_query($conn, "SELECT * FROM tbl_shipping WHERE id_dangky='$id_dangky' LIMIT 1");

        if ($check_shipping === false) {
            die("Lỗi truy vấn kiểm tra shipping: " . mysqli_error($conn));
        }

        if (mysqli_num_rows($check_shipping) > 0) {
            // Cập nhật
            $sql_update_vanchuyen = "UPDATE tbl_shipping 
                SET name='$name', phone='$phone', address='$address', note='$note' 
                WHERE id_dangky='$id_dangky'";
            $res = mysqli_query($conn, $sql_update_vanchuyen);
            if ($res === false) {
                die("Lỗi cập nhật shipping: " . mysqli_error($conn));
            }
        } else {
            // Thêm mới
            $sql_insert_vanchuyen = "INSERT INTO tbl_shipping(name, phone, address, note, id_dangky) 
                VALUES('$name','$phone','$address','$note','$id_dangky')";
            $res = mysqli_query($conn, $sql_insert_vanchuyen);
            if ($res === false) {
                die("Lỗi thêm mới shipping: " . mysqli_error($conn));
            }
        }

        echo '<script>alert("Cập nhật thông tin vận chuyển thành công!");</script>';
        echo '<script>window.location="index.php?quanly=thongtinthanhtoan"</script>';
        exit;
    }

    // Lấy dữ liệu vận chuyển hiện tại (nếu có)
    $sql_get_vanchuyen = mysqli_query($conn, "SELECT * FROM tbl_shipping WHERE id_dangky='$id_dangky' LIMIT 1");
    if ($sql_get_vanchuyen === false) {
        die("Lỗi truy vấn lấy shipping: " . mysqli_error($conn));
    }
    $count = mysqli_num_rows($sql_get_vanchuyen);

    if ($count > 0) {
        $row_get_vanchuyen = mysqli_fetch_assoc($sql_get_vanchuyen);
        $name = $row_get_vanchuyen['name'];
        $phone = $row_get_vanchuyen['phone'];
        $address = $row_get_vanchuyen['address'];
        $note = $row_get_vanchuyen['note'];
    } else {
        $name = '';
        $phone = '';
        $address = '';
        $note = '';
    }
    ?>

    <div class="row">
        <div class="col-md-12">
            <form action="" autocomplete="off" method="POST">
                <div class="form-group">
                    <label for="name">Họ và tên:</label>
                    <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($name); ?>" placeholder="Nhập họ và tên" required>
                </div>
                <div class="form-group">
                    <label for="phone">Số điện thoại:</label>
                    <input type="text" id="phone" name="phone" class="form-control" value="<?php echo htmlspecialchars($phone); ?>" placeholder="Nhập số điện thoại" required>
                </div>
                <div class="form-group">
                    <label for="address">Địa chỉ:</label>
                    <input type="text" id="address" name="address" class="form-control" value="<?php echo htmlspecialchars($address); ?>" placeholder="Nhập địa chỉ" required>
                </div>
                <div class="form-group">
                    <label for="note">Ghi chú:</label>
                    <input type="text" id="note" name="note" class="form-control" value="<?php echo htmlspecialchars($note); ?>" placeholder="Nhập ghi chú (nếu có)">
                </div>

                <?php if ($name == '' && $phone == '') { ?>
                    <button type="submit" name="themvanchuyen" class="btn btn-primary">Thêm thông tin vận chuyển</button>
                <?php } else { ?>
                    <button type="submit" name="capnhatvanchuyen" class="btn btn-success">Cập nhật thông tin vận chuyển</button>
                <?php } ?>
            </form>
        </div>
    </div>

    <!------------------- Giỏ hàng ------------------->
    <h4 style="margin-top:20px;">Giỏ hàng của bạn</h4>
    <table style="width:100%; text-align:center; border-collapse:collapse;" border="1">
        <tr>
            <th>STT</th>
            <th>Mã Sản Phẩm</th>
            <th>Tên Sản Phẩm</th>
            <th>Hình Ảnh</th>
            <th>Số Lượng</th>
            <th>Giá</th>
            <th>Thành Tiền</th>
        </tr>
        <?php
        if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
            $i = 0;
            $tongtien = 0;
            foreach ($_SESSION['cart'] as $cart_item) {
                $i++;
                $thanhtien = $cart_item['soluong'] * $cart_item['giasp'];
                $tongtien += $thanhtien;
        ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo htmlspecialchars($cart_item['masp']); ?></td>
                    <td><?php echo htmlspecialchars($cart_item['tensp']); ?></td>
                    <td><img src="<?php echo 'admincp/modules/quanlysp/uploads/' . htmlspecialchars($cart_item['hinhanh']); ?>" width="100px"></td>
                    <td>
                        <a href="pages/main/themgiohang.php?cong=<?php echo $cart_item['id']; ?>"><i class="fa-solid fa-square-plus"></i></a>
                        <?php echo $cart_item['soluong']; ?>
                        <a href="pages/main/themgiohang.php?tru=<?php echo $cart_item['id']; ?>"><i class="fa-solid fa-square-minus"></i></a>
                    </td>
                    <td><?php echo number_format($cart_item['giasp'], 0, ',', '.'); ?> VNĐ</td>
                    <td><?php echo number_format($thanhtien, 0, ',', '.'); ?> VNĐ</td>
                </tr>
        <?php
            }
            echo '
            <tr>
                <td colspan="7">
                    <p style="float:left;">Tổng tiền: ' . number_format($tongtien, 0, ',', '.') . ' VNĐ</p>
                    <div style="clear:both;"></div>';
            if (isset($_SESSION['dangky'])) {
                echo '<p><a href="index.php?quanly=thongtinthanhtoan" class="btn btn-warning">Tiếp tục thanh toán</a></p>';
            } else {
                echo '<p><a href="index.php?quanly=dangky" class="btn btn-primary">Đăng ký đặt hàng</a></p>';
            }
            echo '</td></tr>';
        } else {
            echo '<tr><td colspan="7">Giỏ hàng trống</td></tr>';
        }
        ?>
    </table>
</div>
