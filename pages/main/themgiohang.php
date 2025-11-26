<?php
    ob_start(); // Bắt đầu đệm đầu ra (Quan trọng để sửa lỗi header)
    session_start();
    include("../../admincp/config/config.php");

    // Tăng số lượng
    if(isset($_GET['cong'])){
        $id = $_GET['cong'];
        $product = array(); // Khởi tạo mảng rỗng để tránh lỗi undefined
        
        foreach($_SESSION['cart'] as $cart_item){
            if($cart_item['id'] != $id){
                $product[] = array(
                    'tensp' => $cart_item['tensp'],
                    'id' => $cart_item['id'],
                    'giasp' => $cart_item['giasp'],
                    'hinhanh' => $cart_item['hinhanh'],
                    'soluong' => $cart_item['soluong'],
                    'masp' => $cart_item['masp']
                );
            } else {
                $tangsoluong = $cart_item['soluong'] + 1;
                // Giới hạn số lượng (ví dụ dưới 20)
                if($cart_item['soluong'] < 20){
                    $product[] = array(
                        'tensp' => $cart_item['tensp'],
                        'id' => $cart_item['id'],
                        'giasp' => $cart_item['giasp'],
                        'hinhanh' => $cart_item['hinhanh'],
                        'soluong' => $tangsoluong,
                        'masp' => $cart_item['masp']
                    );
                } else {
                    $product[] = array(
                        'tensp' => $cart_item['tensp'],
                        'id' => $cart_item['id'],
                        'giasp' => $cart_item['giasp'],
                        'hinhanh' => $cart_item['hinhanh'],
                        'soluong' => $cart_item['soluong'],
                        'masp' => $cart_item['masp']
                    );
                }
            }
        }
        $_SESSION['cart'] = $product;
        header('Location:../../index.php?quanly=giohang');
        exit();
    }

    // Trừ số lượng
    if(isset($_GET['tru'])){
        $id = $_GET['tru'];
        $product = array();
        
        foreach($_SESSION['cart'] as $cart_item){
            if($cart_item['id'] != $id){
                $product[] = array(
                    'tensp' => $cart_item['tensp'],
                    'id' => $cart_item['id'],
                    'giasp' => $cart_item['giasp'],
                    'hinhanh' => $cart_item['hinhanh'],
                    'soluong' => $cart_item['soluong'],
                    'masp' => $cart_item['masp']
                );
            } else {
                $trusoluong = $cart_item['soluong'] - 1;
                if($cart_item['soluong'] > 1){
                    $product[] = array(
                        'tensp' => $cart_item['tensp'],
                        'id' => $cart_item['id'],
                        'giasp' => $cart_item['giasp'],
                        'hinhanh' => $cart_item['hinhanh'],
                        'soluong' => $trusoluong,
                        'masp' => $cart_item['masp']
                    );
                } else {
                    $product[] = array(
                        'tensp' => $cart_item['tensp'],
                        'id' => $cart_item['id'],
                        'giasp' => $cart_item['giasp'],
                        'hinhanh' => $cart_item['hinhanh'],
                        'soluong' => $cart_item['soluong'],
                        'masp' => $cart_item['masp']
                    );
                }
            }
        }
        $_SESSION['cart'] = $product;
        header('Location:../../index.php?quanly=giohang');
        exit();
    }

    // Xóa sản phẩm
    if(isset($_SESSION['cart']) && isset($_GET['xoa'])){
        $id = $_GET['xoa'];
        $product = array();

        foreach($_SESSION['cart'] as $cart_item){
            if($cart_item['id'] != $id){
                $product[] = array(
                    'tensp' => $cart_item['tensp'],
                    'id' => $cart_item['id'],
                    'giasp' => $cart_item['giasp'],
                    'hinhanh' => $cart_item['hinhanh'],
                    'soluong' => $cart_item['soluong'],
                    'masp' => $cart_item['masp']
                );
            }
        }

        $_SESSION['cart'] = $product;
        
        // Nếu xóa hết thì unset luôn session
        if(empty($_SESSION['cart'])){
            unset($_SESSION['cart']);
        }
        
        header('Location:../../index.php?quanly=giohang');
        exit();
    }

    // Xóa tất cả
    if(isset($_GET['xoatatca']) && $_GET['xoatatca'] == 1){
        unset($_SESSION['cart']);
        header('Location:../../index.php?quanly=giohang');
        exit();
    }

    // Thêm sản phẩm vào giỏ hàng (Form POST)
    if(isset($_POST['themgiohang'])){
        // Chú ý: Code cũ của bạn dùng $_GET['idsanpham'] nhưng form lại là POST?
        // Tôi sửa lại logic để lấy ID chính xác hơn
        $id = $_GET['idsanpham']; 
        $soluong = 1;
        $sql = "SELECT * FROM tbl_sanpham WHERE id_sanpham='$id' LIMIT 1";
        $query = mysqli_query($conn, $sql);
        $row = mysqli_fetch_array($query);

        if($row){
            $new_product = array(
                'tensp' => $row['tensp'],
                'id' => $id,
                'giasp' => $row['giasp'],
                'hinhanh' => $row['hinhanh'],
                'soluong' => $soluong,
                'masp' => $row['masp']
            );

            if(isset($_SESSION['cart'])){
                $found = false;
                foreach($_SESSION['cart'] as $key => $cart_item){
                    // Nếu dữ liệu trùng ID thì tăng số lượng
                    if($cart_item['id'] == $id){
                        $_SESSION['cart'][$key]['soluong'] += 1;
                        $found = true;
                        break; // Dừng vòng lặp ngay khi tìm thấy
                    }
                }
                if($found == false){
                    // Nếu không tìm thấy trùng thì thêm mới vào mảng session
                    $_SESSION['cart'][] = $new_product;
                }
            } else {
                $_SESSION['cart'][] = $new_product;
            }
        }
        header('Location:../../index.php?quanly=giohang');
        exit();
    }
    
    ob_end_flush(); // Kết thúc đệm đầu ra
?>