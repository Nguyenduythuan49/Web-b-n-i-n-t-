<p>Liệt kê đơn hàng</p>
<?php
    // --- SỬA CÂU TRUY VẤN QUAN TRỌNG ---
    // Thay vì so sánh id_khachhang, chúng ta so sánh cart_shipping của đơn hàng với id_shipping của bảng vận chuyển
    // Dùng LEFT JOIN để hiển thị cả những đơn hàng cũ chưa có id_shipping (tránh bị mất đơn hàng cũ)
    
    $sql_lietke_dh = "SELECT * FROM tbl_cart 
                      LEFT JOIN tbl_shipping ON tbl_cart.cart_shipping = tbl_shipping.id_shipping 
                      ORDER BY tbl_cart.id_cart DESC";
                      
    $query_lietke_dh = mysqli_query($conn, $sql_lietke_dh);
?>
<table style="width:100%; border-collapse: collapse;" border="1">
  <tr>
    <th>ID</th>
    <th>Mã đơn hàng</th>
    <th>Tên khách hàng</th>
    <th>Số điện thoại</th>
    <th>Địa chỉ</th>
    <th>Ghi chú</th>
    <th>Tình Trạng</th>
    <th>Quản lý</th>
  </tr>
  <?php
    $i = 0;
    while($row = mysqli_fetch_array($query_lietke_dh)){
        $i++;
  ?>
  <tr>
    <td><?php echo $i ?></td>
    <td><?php echo $row['code_cart'] ?></td>
    
    <td><?php echo isset($row['name']) ? $row['name'] : 'N/A' ?></td>
    <td><?php echo isset($row['phone']) ? $row['phone'] : 'N/A' ?></td>
    <td><?php echo isset($row['address']) ? $row['address'] : 'N/A' ?></td>
    <td><?php echo isset($row['note']) ? $row['note'] : '' ?></td>
    
    <td>
        <?php 
        if($row['cart_status'] == 1){
            echo '<a href="modules/quanlydonhang/xuly.php?code='.$row['code_cart'].'">Đơn hàng mới</a>';
        } else {
            echo 'Đã xem';
        } 
        ?>
    </td>
    <td> 
        <a href="index.php?action=donhang&query=xemdonhang&code=<?php echo $row['code_cart'] ?>">Xem đơn hàng</a> 
    </td>

  </tr>
  <?php
    }
  ?>
</table>