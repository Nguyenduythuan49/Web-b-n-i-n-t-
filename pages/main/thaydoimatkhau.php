<?php
if(isset($_POST['doi_mat_khau'])){
    $taikhoan = $_POST['email'];
    $matkhau_cu = md5($_POST['password_cu']);
    $matkhau_moi = md5($_POST['password_moi']);
    $sql = "SELECT * FROM tbl_dangky WHERE email='".$taikhoan."' AND matkhau='".$matkhau_cu."' LIMIT 1";
    $row = mysqli_query($conn,$sql);
    $count = mysqli_num_rows($row);
    if($count>0){
        $sql_update = mysqli_query($conn,"UPDATE tbl_dangky SET matkhau='".$matkhau_moi."'");
        
        echo '<p style="color:green">Đổi mật khẩu thành công.";</p>';
    }else{
        echo '<p style="color:red">Tài khoản hoặc mật khẩu sai, vui lòng nhập lại.</p>';
    }
}


?>
<form action="" autocomplete="off" method="POST">
    <table border="1" class="table-login" style="text-align:center;border-collapse:collapse;">
        <tr>
            <th colspan="2"><h3>Đổi Mật Khẩu </h3></th>
        <tr>
            <td>Tài Khoản</td>
            <td><input type="text" name="email"></td>
        </tr>
        <tr>
            <td>Mật Khẩu Cũ</td>
            <td><input type="password" name="password_cu"></td>
        </tr>
         <tr>
            <td>Mật Khẩu Mới</td>
            <td><input type="password" name="password_moi"></td>
        </tr>
         <tr>

            <td colspan="2"><input type="submit" name="doi_mat_khau" value="Đổi Mật Khẩu"></td>
        </tr>
    </table>
    </form>