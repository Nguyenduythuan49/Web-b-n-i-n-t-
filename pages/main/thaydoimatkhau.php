<style>
    /* CSS CHO TRANG ĐỔI MẬT KHẨU */
    .change-pass-wrapper {
        background-color: #f8f9fa;
        padding: 50px 0;
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .change-pass-card {
        background: #fff;
        width: 100%;
        max-width: 500px;
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    .change-pass-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .change-pass-header h3 {
        font-weight: 700;
        color: #333;
        text-transform: uppercase;
        margin: 0;
        padding-bottom: 10px;
        border-bottom: 3px solid #ffc107; /* Viền vàng chủ đạo */
        display: inline-block;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        font-weight: 600;
        color: #555;
        margin-bottom: 8px;
        display: block;
    }

    .form-control-custom {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 14px;
        transition: 0.3s;
        box-sizing: border-box;
    }

    .form-control-custom:focus {
        border-color: #ffc107;
        box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.2);
        outline: none;
    }

    .btn-save {
        width: 100%;
        padding: 12px;
        background: #333;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: bold;
        text-transform: uppercase;
        cursor: pointer;
        transition: 0.3s;
        margin-top: 10px;
    }

    .btn-save:hover {
        background: #ffc107;
        color: #000;
    }
</style>

<?php
if(isset($_POST['doi_mat_khau'])){
    $taikhoan = $_POST['email'];
    $matkhau_cu = md5($_POST['password_cu']);
    $matkhau_moi = md5($_POST['password_moi']);
    
    // Kiểm tra tài khoản và mật khẩu cũ có đúng không
    $sql = "SELECT * FROM tbl_dangky WHERE email='".$taikhoan."' AND matkhau='".$matkhau_cu."' LIMIT 1";
    $row = mysqli_query($conn,$sql);
    $count = mysqli_num_rows($row);
    
    if($count > 0){
        // --- [QUAN TRỌNG] ĐÃ SỬA LỖI SQL UPDATE ---
        // Phải thêm WHERE email = ... để chỉ đổi mật khẩu của người này
        $sql_update = mysqli_query($conn,"UPDATE tbl_dangky SET matkhau='".$matkhau_moi."' WHERE email='".$taikhoan."'");
        
        echo '<script>alert("Đổi mật khẩu thành công!");</script>';
    } else {
        echo '<script>alert("Tài khoản hoặc mật khẩu cũ không đúng, vui lòng kiểm tra lại.");</script>';
    }
}
?>

<div class="change-pass-wrapper">
    <div class="change-pass-card">
        <div class="change-pass-header">
            <h3><i class="fa-solid fa-key"></i> Đổi Mật Khẩu</h3>
        </div>

        <form action="" autocomplete="off" method="POST">
            
            <div class="form-group">
                <label><i class="fa-solid fa-envelope"></i> Tài khoản (Email)</label>
                <input type="text" class="form-control-custom" name="email" 
                       value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : '' ?>" 
                       placeholder="Nhập email của bạn..." required>
            </div>

            <div class="form-group">
                <label><i class="fa-solid fa-lock"></i> Mật khẩu cũ</label>
                <input type="password" class="form-control-custom" name="password_cu" placeholder="Nhập mật khẩu hiện tại..." required>
            </div>

            <div class="form-group">
                <label><i class="fa-solid fa-unlock-keyhole"></i> Mật khẩu mới</label>
                <input type="password" class="form-control-custom" name="password_moi" placeholder="Nhập mật khẩu mới..." required>
            </div>

            <button type="submit" name="doi_mat_khau" class="btn-save">
                <i class="fa-solid fa-floppy-disk"></i> Lưu thay đổi
            </button>
            
        </form>
    </div>
</div>