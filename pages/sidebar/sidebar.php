 <h4 >Danh mục sản phẩm</h4>
 <ul class="list_sidebar">
    <?php
        $sql_danhmuc="SELECT * FROM tbl_danhmuc ORDER BY id_danhmuc DESC";
        $query_danhmuc= mysqli_query($conn,$sql_danhmuc);
        while($row_danhmuc=mysqli_fetch_array($query_danhmuc)){
    ?>
    <li Style:text-transform:uppercase;><a href="index.php?quanly=danhmucsanpham&id=<?php echo $row_danhmuc['id_danhmuc'] ?>"><?php echo $row_danhmuc['tendanhmuc'] ?></a></li>
    <?php
        }
    ?>
    </ul>