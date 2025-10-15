<?php
    
    ?>
<p>giỏ hàng
<?php
 if(isset($_SESSION['dangky'])){
    echo 'xin chào:'.'<span style="color:red;">'.$_SESSION['dangky'].'</span>';
    echo $_SESSION['id_khachhang'];
  }
  ?>

</p>
<?php
if(isset($_SESSION['cart'])){
    
}
?>
<table style="width:100% ;text-align:center;border-collapse:collapse;" border="1">
  <tr>
    <th>Id</th>

    <th>Mã Sản Phẩm</th>
    <th>Tên Sản Phẩm</th>

    <th>Hình Ảnh</th>
    <th>Số Lượng</th>
    <th>Giá Sản Phẩm</th>
    <th>Thành Tiền</th>
    <th>Quản lý</th>
 </tr>
 <?php
 if(isset($_SESSION['cart'])){
    $i=0;
    $tongtien=0;
   
    foreach($_SESSION['cart'] as $cart_item){
         $thanhtien = $cart_item['soluong'] * $cart_item['giasp'];
            $tongtien+=$thanhtien;
        $i++;
  ?> 
  <tr>
    <td><?php echo $i ?></td>
    <td><?php echo $cart_item['masp'] ?></td>
    <td> <?php echo $cart_item['tensp'] ?></td>
    <td><img src="admincp/modules/quanlysp/uploads/<?php echo $cart_item['hinhanh']; ?>"width="150px"></td>
    <td>
      <a href="pages/main/themgiohang.php?cong=<?php echo $cart_item['id'] ?>"> <i class="fa-solid fa-square-plus fa style" aria-hidden="true"></i></a>
      <?php echo $cart_item['soluong'] ?>
      <a href="pages/main/themgiohang.php?tru=<?php echo $cart_item['id'] ?>"> <i class="fa-solid fa-square-minus fa style" aria-hidden="true"></i></a>
    </td>
    <td><?php echo number_format($cart_item['giasp'], 0, ',', '.').' VNĐ' ?></td>
    <td><?php echo number_format($thanhtien, 0, ',', '.').' VNĐ' ?></td>
    <td><a href="pages/main/themgiohang.php?xoa=<?php echo $cart_item['id'] ?>">Xóa</a></td>
  </tr>
  <?php
    }
    ?>
    <tr>
    <td colspan="8">
      <p style="float:left;">Tổng tiền:<?php echo number_format($tongtien, 0, ',', '.').' VNĐ' ?></p><br/>
      <p style="float:right;"><a href="pages/main/themgiohang.php?xoatatca=1">Xóa tất cả</a></p>
      <div style ="clear:both;"></div>
    <?php
      if(isset($_SESSION['dangky'])){
    ?>
    <P><a href="pages/main/thanhtoan.php"> Đặt Hàng</a></P>
    <?php
      }else{
        ?>
         <P><a href="index.php?quanly=dangky">Đăng Ký Đặt Hàng</a></P>
      <?php
      }
    ?>

    </td>
  

  </tr>
  <?php

    }else{
        ?>
<tr>
    <td colspan="8">Giỏ hàng trống</td>   

  </tr>
<?php
  }
?>
  
</table>