<?php
    $sql_sua_sp="SELECT * FROM tbl_sanpham WHERE id_sanpham='$_GET[idsanpham]' LIMIT 1";
    $query_sua_sp=  mysqli_query($conn,$sql_sua_sp);
?>
<p>Sửa sản phẩm</p>
<table border = "1" width="100%" style="border-collapse: collapse;">
  <?php
    while($row=mysqli_fetch_array($query_sua_sp)){


?>
  <form method="POST" action="modules/quanlysp/xuly.php?idsanpham=<?php echo $_GET['idsanpham']  ?>" enctype="multipart/form-data">
  <tr>
    <td>Tên sản phẩm</td>
    <td><input type="text"value="<?php echo $row['tensp'] ?>" name="tensp"></td>
  </tr>
  <tr>
    <td>Mã sản phẩm</td>
    <td><input type="text"value="<?php echo $row['masp'] ?>" name="masp"></td>
  </tr>
  <tr>
    <td>Giá sản phẩm</td>
    <td><input type="text"value="<?php echo $row['giasp'] ?>" name="giasp"></td>
  </tr>
  <tr>
    <td>Số lượng sản phẩm</td>
    <td><input type="text"value="<?php echo $row['soluong'] ?>" name="soluong"></td>
  </tr>
  <tr>
    <tr>
    <td>Hình ảnh</td>
    <td>
      <input type="file"name="hinhanh">
      <img src="modules/quanlysp/uploads/<?php echo $row['hinhanh'] ?>" width="150px">
  
  </td>

  </tr>
  <tr>
    <td>Tóm tắt </td>
    <td><textarea rows="10" name="tomtat" style="resize: none;"><?php echo $row['tomtat'] ?></textarea></td>
  </tr>
    <td>Nội dung </td>
    <td><textarea rows="10" name="noidung" style="resize: none;"><?php echo $row['noidung'] ?></textarea></td>
  </tr>

  <tr>
    <tr>
    <td>Danh mục sản phẩm</td>
    <td>
      <select name="danhmuc">
        <?php
         $sql_danhmuc="SELECT * FROM tbl_danhmuc ORDER BY id_danhmuc DESC";
          $query_danhmuc=mysqli_query($conn,$sql_danhmuc);
          while($row_danhmuc=mysqli_fetch_array($query_danhmuc)){
            if($row_danhmuc['id_danhmuc']==$row['id_danhmuc']){
        ?>
        <option  selected value="<?php echo $row_danhmuc['id_danhmuc'] ?>"><?php echo $row_danhmuc['tendanhmuc'] ?></option>
        <?php 
          }else{
        ?>
        <option value="<?php echo $row_danhmuc['id_danhmuc'] ?>"><?php echo $row_danhmuc['tendanhmuc'] ?></option>
        <?php 
          }
        ?>

            
        <?php
          }
        ?>
      
      </select>
     </td>
  </tr> 
    <td>Tình trạng</td>
    <td>
      <select name="tinhtrang">
        <?php
        if($row['tinhtrang']==1){
        ?>
        <option value="1"selected>Kích hoạt</option>
        <option value="0">Ẩn</option>
        <?php
        }else{
        ?>
        <option value="0"selected>Ẩn</option>
        <option value="1">Kích hoạt</option>
        <?php
        }
        ?>
      </select>
     </td>
  </tr> 
  <tr>
    <td colspan="2"><input type="submit" name="suasanpham" value="Sửa sản phẩm"></td>
  </tr>
  </form>
  <?php
  }
  ?>
</table>