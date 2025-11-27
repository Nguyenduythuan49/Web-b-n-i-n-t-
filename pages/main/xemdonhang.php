<style>
    /* Wrapper bao quanh */
    .details-wrapper {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #fff;
        padding: 25px;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        margin-bottom: 30px;
    }

    .details-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 2px solid #eee;
        padding-bottom: 15px;
        margin-bottom: 20px;
    }

    .details-title {
        color: #333;
        font-size: 22px;
        font-weight: 700;
        margin: 0;
        text-transform: uppercase;
    }

    .btn-back {
        background: #6c757d;
        color: #fff;
        padding: 8px 15px;
        border-radius: 5px;
        text-decoration: none;
        font-size: 14px;
        transition: 0.3s;
    }
    .btn-back:hover {
        background: #5a6268;
        color: #fff;
    }

    /* Style bảng chi tiết */
    .table-details {
        width: 100%;
        border-collapse: collapse;
        font-size: 15px;
    }

    .table-details thead th {
        background-color: #f8f9fa;
        color: #555;
        font-weight: 700;
        text-align: left;
        padding: 15px;
        border-bottom: 2px solid #ddd;
    }

    .table-details tbody td {
        padding: 15px;
        border-bottom: 1px solid #eee;
        vertical-align: middle;
        color: #333;
    }

    .table-details tbody tr:last-child td {
        border-bottom: none;
    }

    /* Ảnh sản phẩm */
    .product-img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #eee;
    }

    /* Tên sản phẩm */
    .product-name {
        font-weight: 600;
        color: #007bff;
        font-size: 16px;
    }

    /* Tổng tiền footer */
    .total-wrapper {
        display: flex;
        justify-content: flex-end;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 2px solid #f1f1f1;
    }

    .total-box {
        text-align: right;
    }

    .total-label {
        font-size: 16px;
        color: #666;
        margin-right: 10px;
    }

    .total-price {
        font-size: 24px;
        color: #dc3545; /* Màu đỏ */
        font-weight: 800;
    }
</style>

<?php
    $code_cart = $_GET['code'];
    // JOIN bảng chi tiết với bảng sản phẩm để lấy tên, giá và HÌNH ẢNH
    $sql_lietke_dh = "SELECT * FROM tbl_cart_details, tbl_sanpham 
                      WHERE tbl_cart_details.id_sanpham = tbl_sanpham.id_sanpham 
                      AND tbl_cart_details.code_cart = '".$code_cart."' 
                      ORDER BY tbl_cart_details.id_cart_details DESC";
    $query_lietke_dh = mysqli_query($conn, $sql_lietke_dh);
?>

<div class="details-wrapper">
    
    <div class="details-header">
        <h3 class="details-title">Chi tiết đơn hàng: #<?php echo $code_cart; ?></h3>
        <a href="index.php?quanly=lichsudonhang" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Quay lại</a>
    </div>

    <table class="table-details">
        <thead>
            <tr>
                <th width="5%" class="text-center">STT</th>
                <th width="15%">Hình ảnh</th> <th width="40%">Tên sản phẩm</th>
                <th width="10%" class="text-center">Số lượng</th>
                <th width="15%">Đơn giá</th>
                <th width="15%" class="text-right">Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 0;
            $tongtien = 0;
            while($row = mysqli_fetch_array($query_lietke_dh)){
                $i++;
                $thanhtien = $row['giasp'] * $row['soluongmua'];
                $tongtien += $thanhtien;
            ?>
            <tr>
                <td class="text-center"><?php echo $i ?></td>
                
                <td>
                    <img src="admincp/modules/quanlysp/uploads/<?php echo $row['hinhanh']; ?>" class="product-img" alt="SP">
                </td>
                
                <td>
                    <div class="product-name"><?php echo $row['tensp'] ?></div>
                    <small style="color:#999;">Mã SP: <?php echo $row['masp']; ?></small>
                </td>
                
                <td class="text-center" style="font-weight:bold;"><?php echo $row['soluongmua'] ?></td>
                
                <td><?php echo number_format($row['giasp'], 0, ',', '.') ?> đ</td>
                
                <td class="text-right" style="font-weight:bold; color: #333;">
                    <?php echo number_format($thanhtien, 0, ',', '.') ?> đ
                </td>
            </tr>
            <?php
            }
            ?>
        </tbody>
    </table>

    <div class="total-wrapper">
        <div class="total-box">
            <span class="total-label">Tổng tiền thanh toán:</span>
            <span class="total-price"><?php echo number_format($tongtien, 0, ',', '.') ?> VNĐ</span>
        </div>
    </div>
</div>