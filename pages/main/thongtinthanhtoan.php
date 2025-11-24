<?php
    // KHỞI TẠO SESSION NẾU CHƯA CÓ
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
?>

<div class="container py-4">
    
    <!-- THANH TIẾN TRÌNH -->
    <div class="arrow-steps clearfix mb-4">
        <div class="step done"> <span><a href="index.php?quanly=giohang">Giỏ hàng</a></span> </div>
        <div class="step done"> <span><a href="index.php?quanly=vanchuyen">Vận chuyển</a></span> </div>
        <div class="step current"> <span><a href="index.php?quanly=thongtinthanhtoan">Thanh toán</a></span> </div>
        <!-- <div class="step"> <span><a href="index.php?quanly=donhangdadat">Lịch sử đơn hàng</a></span> </div> -->
    </div>

    <?php
        // Lấy thông tin vận chuyển và giỏ hàng
        $id_dangky = $_SESSION['id_khachhang'];
        $sql_get_vanchuyen = mysqli_query($conn, "SELECT * FROM tbl_shipping WHERE id_dangky='$id_dangky' LIMIT 1");
        $count = mysqli_num_rows($sql_get_vanchuyen);

        if($count > 0){
            $row_get_vanchuyen = mysqli_fetch_array($sql_get_vanchuyen);
            $name = $row_get_vanchuyen['name'];
            $phone = $row_get_vanchuyen['phone'];
            $address = $row_get_vanchuyen['address'];
            $note = $row_get_vanchuyen['note'];
        } else {
            $name = ''; $phone = ''; $address = ''; $note = '';
        }
    ?>

    <div class="row">
        
        <!-- CỘT TRÁI: THÔNG TIN & GIỎ HÀNG -->
        <div class="col-md-8">
            
            <!-- 1. Review Thông tin vận chuyển -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0 text-primary"><i class="fa-solid fa-location-dot"></i> Địa chỉ nhận hàng</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><i class="fa-solid fa-user mr-2 text-muted"></i> <b><?php echo $name ?></b></li>
                        <li class="list-group-item"><i class="fa-solid fa-phone mr-2 text-muted"></i> <b><?php echo $phone ?></b></li>
                        <li class="list-group-item"><i class="fa-solid fa-map-location-dot mr-2 text-muted"></i> <b><?php echo $address ?></b></li>
                        <li class="list-group-item"><i class="fa-solid fa-note-sticky mr-2 text-muted"></i> <span class="text-muted"><?php echo $note ?></span></li>
                    </ul>
                    <div class="mt-3">
                        <a href="index.php?quanly=vanchuyen" class="btn btn-sm btn-outline-primary">Thay đổi địa chỉ</a>
                    </div>
                </div>
            </div>

            <!-- 2. Review Giỏ hàng -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0 text-success"><i class="fa-solid fa-cart-flatbed"></i> Kiểm tra đơn hàng</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 text-center align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th>SP</th>
                                    <th>Hình</th>
                                    <th>Tên SP</th>
                                    <th>SL</th>
                                    <th>Giá</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            if(isset($_SESSION['cart'])){
                                $i=0;
                                $tongtien=0;
                                foreach($_SESSION['cart'] as $cart_item){
                                    $thanhtien = $cart_item['soluong'] * $cart_item['giasp'];
                                    $tongtien += $thanhtien;
                                    $i++;
                            ?>
                                <tr>
                                    <td><?php echo $i ?></td>
                                    <td><img src="admincp/modules/quanlysp/uploads/<?php echo $cart_item['hinhanh']; ?>" width="50px" style="border-radius:4px;"></td>
                                    <td class="text-left font-weight-bold"><?php echo $cart_item['tensp'] ?></td>
                                    <td>
                                        <!-- Chỉ hiển thị số lượng, không cho sửa ở bước này để tránh lỗi -->
                                        <span class="badge badge-secondary" style="font-size: 1rem;"><?php echo $cart_item['soluong'] ?></span>
                                    </td>
                                    <td><?php echo number_format($cart_item['giasp'], 0, ',', '.').' đ' ?></td>
                                    <td class="text-danger font-weight-bold"><?php echo number_format($thanhtien, 0, ',', '.').' đ' ?></td>
                                </tr>
                            <?php
                                }
                                // Tính toán tiền tệ
                                $tongtien_vnd = $tongtien; 
                                $tongtien_usd = round($tongtien / 26000); 
                            ?>
                                <tr class="bg-light">
                                    <td colspan="5" class="text-right font-weight-bold pr-4" style="font-size: 1.2rem;">Tổng tiền cần thanh toán:</td>
                                    <td class="text-danger font-weight-bold" style="font-size: 1.2rem;"><?php echo number_format($tongtien, 0, ',', '.').' đ' ?></td>
                                </tr>
                            <?php
                            } else {
                            ?>
                                <tr><td colspan="6">Giỏ hàng trống</td></tr>
                            <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- CỘT PHẢI: PHƯƠNG THỨC THANH TOÁN -->
        <div class="col-md-4">
            <div class="card shadow border-0">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fa-solid fa-credit-card"></i> Chọn cách thanh toán</h5>
                </div>
                <div class="card-body">
                    
                    <!-- FORM THANH TOÁN CƠ BẢN (Tiền mặt, CK, VNPay) -->
                    <form action="pages/main/xulythanhtoan.php" method="POST">
                        
                        <div class="form-group">
                            <label class="font-weight-bold mb-3">Thanh toán khi nhận hàng & Chuyển khoản</label>
                            
                            <div class="form-check p-3 border rounded mb-2 bg-light">
                                <input class="form-check-input ml-1" type="radio" name="payment" id="pay_cash" value="tienmat" checked style="transform: scale(1.2);">
                                <label class="form-check-label ml-4 font-weight-bold cursor-pointer" for="pay_cash">
                                    <i class="fa-solid fa-money-bill-wave text-success"></i> Tiền mặt (COD)
                                </label>
                            </div>

                            <div class="form-check p-3 border rounded mb-2 bg-light">
                                <input class="form-check-input ml-1" type="radio" name="payment" id="pay_bank" value="chuyenkhoan" style="transform: scale(1.2);">
                                <label class="form-check-label ml-4 font-weight-bold cursor-pointer" for="pay_bank">
                                    <i class="fa-solid fa-building-columns text-primary"></i> Chuyển khoản ngân hàng
                                </label>
                            </div>

                            <div class="form-check p-3 border rounded mb-3 bg-light">
                                <input class="form-check-input ml-1" type="radio" name="payment" id="pay_vnpay" value="vnpay" style="transform: scale(1.2);">
                                <label class="form-check-label ml-4 font-weight-bold cursor-pointer d-flex align-items-center" for="pay_vnpay">
                                    <img src="images/VNpay.png" height="20" class="mr-2"> VNPay
                                </label>
                            </div>

                            <input type="submit" name="redirect" value="ĐẶT HÀNG NGAY" class="btn btn-danger btn-block btn-lg font-weight-bold">
                        </div>
                    </form>

                    <hr class="my-4">
                    
                    <!-- CỔNG THANH TOÁN ĐIỆN TỬ KHÁC -->
                    <label class="font-weight-bold mb-3">Ví điện tử & Quốc tế</label>

                    <!-- MoMo Forms -->
                    <div class="row mb-2">
                        <div class="col-6 pr-1">
                            <form method="POST" target="_blank" action="pages/main/xulythanhtoanmomo.php">
                                <input type="hidden" name="tongtien_vnd" value="<?php echo $tongtien_vnd ?>">
                                <button type="submit" name="momo" class="btn btn-dark btn-block" style="background-color: #a50064; border:none;">
                                    <i class="fa-solid fa-qrcode"></i> MoMo QR
                                </button>
                            </form>
                        </div>
                        <div class="col-6 pl-1">
                             <form method="POST" target="_blank" action="pages/main/xulythanhtoanmomo_atm.php">
                                <input type="hidden" name="tongtien_vnd" value="<?php echo $tongtien_vnd ?>">
                                <button type="submit" name="momo_atm" class="btn btn-dark btn-block" style="background-color: #a50064; border:none;">
                                    <i class="fa-solid fa-credit-card"></i> MoMo ATM
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- PayPal -->
                    <div id="paypal-button" class="mt-2"></div>
                    <input type="hidden" id="tongtien" value="<?php echo $tongtien_usd; //Lưu ý: Dùng biến USD cho Paypal ?>">

                </div>
                <div class="card-footer text-center text-muted" style="font-size: 0.85rem;">
                    <i class="fa-solid fa-shield-halved"></i> Giao dịch được bảo mật an toàn 100%
                </div>
            </div>
        </div>
    </div>
</div>