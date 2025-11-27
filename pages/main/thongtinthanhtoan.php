<?php
    // KHỞI TẠO SESSION NẾU CHƯA CÓ
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
?>

<div class="container py-4">
    
    <div class="arrow-steps clearfix mb-4">
        <div class="step done"> <span><a href="index.php?quanly=giohang">Giỏ hàng</a></span> </div>
        <div class="step done"> <span><a href="index.php?quanly=vanchuyen">Vận chuyển</a></span> </div>
        <div class="step current"> <span><a href="index.php?quanly=thongtinthanhtoan">Thanh toán</a></span> </div>
    </div>

    <?php
        $id_dangky = $_SESSION['id_khachhang'];
        
        // --- SỬA LOGIC QUAN TRỌNG ---
        // Phải lấy địa chỉ MỚI NHẤT (id_shipping lớn nhất) mà người dùng vừa nhập
        $sql_get_vanchuyen = mysqli_query($conn, "SELECT * FROM tbl_shipping WHERE id_dangky='$id_dangky' ORDER BY id_shipping DESC LIMIT 1");
        $count = mysqli_num_rows($sql_get_vanchuyen);

        if($count > 0){
            $row_get_vanchuyen = mysqli_fetch_array($sql_get_vanchuyen);
            // Dùng htmlspecialchars để xử lý tên có ký tự đặc biệt
            $name = htmlspecialchars($row_get_vanchuyen['name']);
            $phone = htmlspecialchars($row_get_vanchuyen['phone']);
            $address = htmlspecialchars($row_get_vanchuyen['address']);
            $note = htmlspecialchars($row_get_vanchuyen['note']);
        } else {
            $name = ''; $phone = ''; $address = ''; $note = '';
        }
    ?>

    <div class="row">
        
        <div class="col-md-8">
            
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0 text-primary"><i class="fa-solid fa-location-dot"></i> Thông tin nhận hàng</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><i class="fa-solid fa-user mr-2 text-muted"></i> Người nhận: <b><?php echo $name ?></b></li>
                        <li class="list-group-item"><i class="fa-solid fa-phone mr-2 text-muted"></i> Số điện thoại: <b><?php echo $phone ?></b></li>
                        <li class="list-group-item"><i class="fa-solid fa-map-location-dot mr-2 text-muted"></i> Địa chỉ: <b><?php echo $address ?></b></li>
                        <li class="list-group-item"><i class="fa-solid fa-note-sticky mr-2 text-muted"></i> Ghi chú: <span class="text-muted"><?php echo $note ?></span></li>
                    </ul>
                    <div class="mt-3">
                        <a href="index.php?quanly=vanchuyen" class="btn btn-sm btn-outline-warning font-weight-bold">
                            <i class="fa-solid fa-pen-to-square"></i> Thay đổi địa chỉ
                        </a>
                    </div>
                </div>
            </div>

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
                                        <span class="badge badge-secondary" style="font-size: 0.9rem;"><?php echo $cart_item['soluong'] ?></span>
                                    </td>
                                    <td><?php echo number_format($cart_item['giasp'], 0, ',', '.').' đ' ?></td>
                                    <td class="text-danger font-weight-bold"><?php echo number_format($thanhtien, 0, ',', '.').' đ' ?></td>
                                </tr>
                            <?php
                                }
                                $tongtien_vnd = $tongtien; 
                                $tongtien_usd = round($tongtien / 25000); // Tỉ giá ước lượng
                            ?>
                                <tr class="bg-light">
                                    <td colspan="5" class="text-right font-weight-bold pr-4" style="font-size: 1.1rem;">Tổng tiền cần thanh toán:</td>
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

        <div class="col-md-4">
            <div class="card shadow border-0 position-sticky" style="top: 20px;">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fa-solid fa-credit-card"></i> Chọn cách thanh toán</h5>
                </div>
                <div class="card-body">
                    
                    <form action="pages/main/xulythanhtoan.php" method="POST">
                        
                        <div class="form-group">
                            <label class="font-weight-bold mb-3 text-muted">Phương thức truyền thống</label>
                            
                            <div class="custom-control custom-radio p-3 border rounded mb-2 bg-light">
                                <input type="radio" id="pay_cash" name="payment" value="tienmat" class="custom-control-input" checked>
                                <label class="custom-control-label font-weight-bold w-100 cursor-pointer" for="pay_cash">
                                    <i class="fa-solid fa-money-bill-wave text-success mr-2"></i> Tiền mặt (COD)
                                </label>
                            </div>

                            <div class="custom-control custom-radio p-3 border rounded mb-2 bg-light">
                                <input type="radio" id="pay_bank" name="payment" value="chuyenkhoan" class="custom-control-input">
                                <label class="custom-control-label font-weight-bold w-100 cursor-pointer" for="pay_bank">
                                    <i class="fa-solid fa-building-columns text-primary mr-2"></i> Chuyển khoản
                                </label>
                            </div>

                            <div class="custom-control custom-radio p-3 border rounded mb-3 bg-light">
                                <input type="radio" id="pay_vnpay" name="payment" value="vnpay" class="custom-control-input">
                                <label class="custom-control-label font-weight-bold w-100 cursor-pointer" for="pay_vnpay">
                                    <img src="images/VNpay.png" height="18" class="mr-1"> Ví VNPAY
                                </label>
                            </div>

                            <button type="submit" name="redirect" class="btn btn-danger btn-block btn-lg font-weight-bold shadow-sm">
                                ĐẶT HÀNG NGAY <i class="fa-solid fa-paper-plane ml-1"></i>
                            </button>
                        </div>
                    </form>

                    <hr class="my-4">
                    
                    <label class="font-weight-bold mb-3 text-muted">Ví điện tử & Quốc tế</label>

                    <div class="row mb-2">
                        <div class="col-6 pr-1">
                            <form method="POST" target="_blank" action="pages/main/xulythanhtoanmomo.php">
                                <input type="hidden" name="tongtien_vnd" value="<?php echo $tongtien_vnd ?>">
                                <button type="submit" name="momo" class="btn btn-dark btn-block" style="background-color: #a50064; border:none; font-size: 13px;">
                                    <i class="fa-solid fa-qrcode"></i> MoMo QR
                                </button>
                            </form>
                        </div>
                        <div class="col-6 pl-1">
                             <form method="POST" target="_blank" action="pages/main/xulythanhtoanmomo_atm.php">
                                <input type="hidden" name="tongtien_vnd" value="<?php echo $tongtien_vnd ?>">
                                <button type="submit" name="momo_atm" class="btn btn-dark btn-block" style="background-color: #a50064; border:none; font-size: 13px;">
                                    <i class="fa-solid fa-credit-card"></i> MoMo ATM
                                </button>
                            </form>
                        </div>
                    </div>

                    <div id="paypal-button" class="mt-2"></div>
                    <input type="hidden" id="tongtien" value="<?php echo $tongtien_usd; ?>">

                </div>
            </div>
        </div>
    </div>
</div>