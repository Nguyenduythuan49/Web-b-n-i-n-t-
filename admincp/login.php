<?php
session_start();
include("config/config.php");
if(isset($_POST['dangnhap'])){
    $taikhoan = $_POST['username'];
    $matkhau = md5($_POST['password']);
    $sql = "SELECT * FROM tbl_admin WHERE username='".$taikhoan."' AND password='".$matkhau."' LIMIT 1";
    $row = mysqli_query($conn,$sql);
    $count = mysqli_num_rows($row);
    if($count>0){
        $_SESSION['dangnhap'] = $taikhoan;
        header("Location:index.php");
    }else{
        echo '<script>alert("Tài khoản hoặc mật khẩu sai, vui lòng nhập lại.");</script>';
        header("Location:login.php");
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập admincp</title>
    <style type="text/css">
        body{
            background: #f2f2f2;

        }
        .wrapper-login{
            width:15%;
            margin: 0 auto;
        }
        table.table-login{
            width:100%;

        }
        table.table-login tr td{
            padding: 5px;
        }
        </style>
</head>
<body>
    <div class="wrapper-login" >
        <form action="" autocomplete="off" method="POST">
    <table border="1" class="table-login" style="text-align:center;border-collapse:collapse;">
        <tr>
            <th colspan="2"><h3>Đăng Nhập Admin</h3></th>
        <tr>
            <td>Tài Khoản</td>
            <td><input type="text" name="username"></td>
        </tr>
        <tr>
            <td>Mật Khẩu</td>
            <td><input type="password" name="password"></td>
        </tr>
         <tr>

            <td colspan="2"><input type="submit" name="dangnhap" value="Đăng Nhập"></td>
        </tr>
    </table>
    </form>
</div>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

</body>
</html>
