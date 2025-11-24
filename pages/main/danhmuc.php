<?php
     $sql_pro = "SELECT * FROM  tbl_sanpham 
            WHERE tbl_sanpham.id_danhmuc = ".$_GET['id']." 
            ORDER BY id_sanpham DESC";

     $query_pro=mysqli_query($conn,$sql_pro);
    //get ten danh muc
      $sql_cate = "SELECT * FROM  tbl_danhmuc 
            WHERE tbl_danhmuc.id_danhmuc = ".$_GET['id']." Limit 1";
      $query_cate=mysqli_query($conn,$sql_cate);
      $row_tittle=mysqli_fetch_array($query_cate);
    
?>
<h3>Danh mục sản phẩm : <?php echo $row_tittle['tendanhmuc'] ?></h3>
                <div class ="row">
                    <?php
                    while($row=mysqli_fetch_array($query_pro)){
                    ?>
                    <div class="col-md-4 col-sm-6 col-xs-12 product_list">
                        <a href="index.php?quanly=sanpham&id=<?php echo $row['id_sanpham'] ?>">
                        <img class="img-responsive" src="admincp/modules/quanlysp/uploads/<?php echo $row['hinhanh'] ?>">
                        <p class="tittle_product">Tên sản phẩm: <?php echo $row['tensp'] ?></p>
                        <p class="price_product">Giá : <?php echo number_format($row['giasp'],0,',','.')." VNĐ" ?></p>
                        </a>
                    </div>
                    <?php
                    }
                    ?>
                </div>
