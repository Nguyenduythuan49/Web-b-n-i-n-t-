<?php

 if(isset($_POST['dangky'])){
    $name=$_POST['hovaten'];
    $email=$_POST['email'];
    $phone=$_POST['sodienthoai'];
    $address=$_POST['diachi'];
    $matkhau=md5($_POST['matkhau']);
    $sql_dangky=mysqli_query($conn,"INSERT INTO tbl_dangky(tenkhachhang,email,diachi,matkhau,sodienthoai) VALUE('".$name."','".$email."','".$address."','".$matkhau."','".$phone."')");
    if($sql_dangky){
        echo '<p style="color:green;">Đăng ký thành công!</p>';
       $_SESSION['dangky']=$name;

         $_SESSION['id_khachhang']=mysqli_insert_id($conn);
        header("Location:index.php?quanly=giohang");
       
    }
 }
?>
<P>Đăng Ký Thành Viên</P>
<style type="text/css">
    table.dangky tr td{
        padding:5px;
    }
</style>
<form action="" method="POST">
<table class="dangky" border="1" style="border-collapse:collapse; width:50%;">
    <tr>

        <td>Họ và tên</td>
        <td><input type="text" size="50"name="hovaten"></td>
    </tr>
    <tr>
        <td>Email</td>
        <td><input type="text" size="50"name="email"></td>
    </tr>
    <tr>
        <td>Điện thoại</td>
        <td><input type="text" size="50"name="sodienthoai"></td>
    </tr>
    <tr>
        <td>Địa chỉ</td>
        <td><input type="text" size="50"name="diachi"></td>
    </tr>
    <tr>
        <td>Mật khẩu</td>
        <td><input type="password" size="50"name="matkhau"></td>
    </tr>
    <tr>

        <td><input type="submit" name="dangky" value="Đăng Ký"></td>
         <td><a href="index.php?quanly=dangnhap">Đăng nhập nếu có tài khoản</a></td>
    </tr>
</table>
</form>