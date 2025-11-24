<?php
    // Code PHP xử lý (giữ nguyên logic của bạn)
?>

<!-- Bắt đầu giao diện -->
<div class="container py-4"> <!-- Thêm container để căn giữa và tạo khoảng cách -->
    
    <!-- Phần tiêu đề và thông tin người dùng -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Giỏ hàng của bạn</h2>
        </div>
        <div class="col-md-4 text-right">
            <?php
            if(isset($_SESSION['dangky'])){
                echo '<span class="text-muted">Xin chào: </span><strong class="text-danger">'.$_SESSION['dangky'].'</strong>';
                // echo $_SESSION['id_khachhang']; // Có thể ẩn ID đi cho đẹp
            }
            ?>
        </div>
    </div>

    <!-- Thanh tiến trình (Arrow Steps) -->
    <!-- Lưu ý: Đảm bảo bạn đã có CSS cho class .arrow-steps trong file style.css -->
    <div class="arrow-steps clearfix mb-4">
        <div class="step current"> <span><a href="index.php?quanly=giohang">Giỏ hàng</a></span> </div>
        <div class="step"> <span><a href="index.php?quanly=vanchuyen">Vận chuyển</a></span> </div>
        <div class="step"> <span><a href="index.php?quanly=thongtinthanhtoan">Thanh toán</a></span> </div>
        <!-- <div class="step"> <span><a href="index.php?quanly=donhangdadat">Lịch sử đơn hàng</a></span> </div> -->
    </div>

    <!-- Bảng giỏ hàng -->
    <div class="table-responsive"> <!-- Giúp bảng có thanh trượt nếu xem trên điện thoại -->
        <table class="table table-bordered table-hover text-center align-middle">
            <thead class="thead-light">
                <tr>
                    <th>ID</th>
                    <th>Mã SP</th>
                    <th>Tên Sản Phẩm</th>
                    <th>Hình Ảnh</th>
                    <th>Số Lượng</th>
                    <th>Giá</th>
                    <th>Thành Tiền</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if(isset($_SESSION['cart'])){
                $i = 0;
                $tongtien = 0;
                
                foreach($_SESSION['cart'] as $cart_item){
                    $thanhtien = $cart_item['soluong'] * $cart_item['giasp'];
                    $tongtien += $thanhtien;
                    $i++;
            ?>
                <tr>
                    <td><?php echo $i ?></td>
                    <td><?php echo $cart_item['masp'] ?></td>
                    <td class="font-weight-bold"><?php echo $cart_item['tensp'] ?></td>
                    <td>
                        <img src="admincp/modules/quanlysp/uploads/<?php echo $cart_item['hinhanh']; ?>" width="100px" style="object-fit: cover; border-radius: 5px;">
                    </td>
                    <td>
                        <!-- Nút Cộng/Trừ số lượng -->
                        <div class="d-flex justify-content-center align-items-center">
                            <a href="pages/main/themgiohang.php?tru=<?php echo $cart_item['id'] ?>" class="text-dark mx-2">
                                <i class="fa-solid fa-minus-square fa-lg"></i>
                            </a>
                            
                            <span class="font-weight-bold" style="font-size: 1.1rem;"><?php echo $cart_item['soluong'] ?></span>

                            <a href="pages/main/themgiohang.php?cong=<?php echo $cart_item['id'] ?>" class="text-dark mx-2">
                                <i class="fa-solid fa-plus-square fa-lg"></i>
                            </a>
                        </div>
                    </td>
                    <td><?php echo number_format($cart_item['giasp'], 0, ',', '.').' đ' ?></td>
                    <td class="text-danger font-weight-bold"><?php echo number_format($thanhtien, 0, ',', '.').' đ' ?></td>
                    <td>
                        <a href="pages/main/themgiohang.php?xoa=<?php echo $cart_item['id'] ?>" class="btn btn-sm btn-outline-danger">
                            <i class="fa-solid fa-trash"></i> Xóa
                        </a>
                    </td>
                </tr>
            <?php
                }
            ?>
                <!-- Dòng tổng tiền -->
                <tr class="bg-light font-weight-bold">
                    <td colspan="6" class="text-right pr-4" style="font-size: 1.2rem;">Tổng tiền thanh toán:</td>
                    <td class="text-danger" style="font-size: 1.2rem;"><?php echo number_format($tongtien, 0, ',', '.').' VNĐ' ?></td>
                    <td>
                        <a href="pages/main/themgiohang.php?xoatatca=1" class="btn btn-sm btn-danger">Xóa tất cả</a>
                    </td>
                </tr>
            
            <!-- Kết thúc vòng lặp session cart -->
            <?php
            } else {
            ?>
                <tr>
                    <td colspan="8" class="py-5 text-muted">
                        <h4><i class="fa-solid fa-cart-arrow-down"></i> Giỏ hàng đang trống</h4>
                        <p>Hãy thêm sản phẩm vào giỏ hàng nhé!</p>
                    </td>
                </tr>
            <?php
            }
            ?>
            </tbody>
        </table>
    </div>

    <!-- Phần nút Đặt hàng / Tiếp tục -->
    <div class="d-flex justify-content-end mt-3">
        <?php
        if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])) { // Chỉ hiện nút khi có hàng
            if(isset($_SESSION['dangky'])){
        ?>
                <a href="index.php?quanly=vanchuyen" class="btn btn-primary btn-lg">
                    Hình thức vận chuyển <i class="fa-solid fa-arrow-right"></i>
                </a>
        <?php
            } else {
        ?>
                <a href="index.php?quanly=dangky" class="btn btn-warning btn-lg text-white">
                    Đăng Ký Đặt Hàng <i class="fa-solid fa-user-plus"></i>
                </a>
        <?php
            }
        }
        ?>
    </div>

</div>