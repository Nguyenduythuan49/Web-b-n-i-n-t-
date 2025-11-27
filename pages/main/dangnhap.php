<style>
    /* CSS RIÊNG CHO TRANG ĐĂNG NHẬP */
    .login-wrapper {
        background-color: #f8f9fa; /* Màu nền xám nhạt */
        padding: 50px 0;
        min-height: 80vh; /* Chiều cao tối thiểu */
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .login-card {
        background: #fff;
        width: 100%;
        max-width: 450px; /* Nhỏ hơn form đăng ký một chút cho cân đối */
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1); /* Đổ bóng */
    }

    .login-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .login-header h3 {
        font-weight: 700;
        color: #333;
        text-transform: uppercase;
        margin: 0;
        padding-bottom: 10px;
        border-bottom: 3px solid #ffc107; /* Gạch chân màu vàng */
        display: inline-block;
    }

    .form-group {
        margin-bottom: 20px;
        text-align: left;
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
        box-sizing: border-box; /* Đảm bảo padding không làm vỡ khung */
    }

    .form-control-custom:focus {
        border-color: #ffc107;
        box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.2);
        outline: none;
    }

    .btn-login {
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

    .btn-login:hover {
        background: #ffc107;
        color: #000;
    }

    .register-link {
        text-align: center;
        margin-top: 20px;
        font-size: 14px;
        color: #666;
    }
    .register-link a {
        color: #007bff;
        font-weight: 600;
        text-decoration: none;
    }
    .register-link a:hover {
        text-decoration: underline;
    }
</style>

<?php
    if(isset($_POST['dangnhap'])){
        $email = $_POST['email'];
        $matkhau = md5($_POST['password']);
        $sql = "SELECT * FROM tbl_dangky WHERE email='".$email."' AND matkhau='".$matkhau."' LIMIT 1";
        $row = mysqli_query($conn,$sql);
        $count = mysqli_num_rows($row);
        
        if($count > 0){
            $row_data = mysqli_fetch_array($row);
            $_SESSION['dangky'] = $row_data['tenkhachhang'];
            $_SESSION['id_khachhang'] = $row_data['id_dangky'];
            $_SESSION['email'] = $row_data['email']; // Lưu thêm email vào session để dùng nếu cần
            
            // Chuyển hướng bằng JS
            echo '<script>alert("Đăng nhập thành công!"); window.location.href = "index.php?quanly=giohang";</script>';
            exit(); 
        }else{
            echo '<script>alert("Tài khoản hoặc mật khẩu không đúng!");</script>';
        }
    }
?>

<div class="login-wrapper">
    <div class="login-card">
        <div class="login-header">
            <h3>Đăng Nhập</h3>
        </div>

        <form action="" autocomplete="off" method="POST">
            <div class="form-group">
                <label><i class="fa-solid fa-envelope"></i> Tài khoản (Email)</label>
                <input type="text" class="form-control-custom" name="email" placeholder="Nhập email của bạn..." required>
            </div>

            <div class="form-group">
                <label><i class="fa-solid fa-lock"></i> Mật khẩu</label>
                <input type="password" class="form-control-custom" name="password" placeholder="Nhập mật khẩu..." required>
            </div>
            
            <button type="submit" name="dangnhap" class="btn-login">
                <i class="fa-solid fa-right-to-bracket"></i> Đăng Nhập
            </button>

            <div class="register-link">
                <p>Bạn chưa có tài khoản? <a href="index.php?quanly=dangky">Đăng ký ngay</a></p>
            </div>
        </form>
    </div>
</div>