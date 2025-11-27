<style>
    /* CSS RIÊNG CHO TRANG ĐĂNG KÝ */
    .register-wrapper {
        background-color: #f8f9fa; /* Màu nền xám nhạt cho cả vùng */
        padding: 50px 0;
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .register-card {
        background: #fff;
        width: 100%;
        max-width: 600px; /* Độ rộng tối đa của khung */
        padding: 40px;
        border-radius: 15px; /* Bo tròn góc */
        box-shadow: 0 10px 25px rgba(0,0,0,0.1); /* Đổ bóng mềm */
    }

    .register-title {
        text-align: center;
        margin-bottom: 30px;
        color: #333;
        font-weight: 700;
        text-transform: uppercase;
        border-bottom: 2px solid #ffc107;
        display: inline-block;
        padding-bottom: 10px;
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
        transition: all 0.3s;
    }

    .form-control-custom:focus {
        border-color: #ffc107;
        box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.2);
        outline: none;
    }

    .btn-register {
        width: 100%;
        padding: 12px;
        background: #333; /* Màu đen sang trọng */
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: bold;
        text-transform: uppercase;
        cursor: pointer;
        transition: background 0.3s;
        margin-top: 10px;
    }

    .btn-register:hover {
        background: #ffc107; /* Hover chuyển màu vàng */
        color: #000;
    }

    .login-link {
        text-align: center;
        margin-top: 20px;
        font-size: 14px;
    }
    .login-link a {
        color: #007bff;
        text-decoration: none;
        font-weight: 600;
    }
    .login-link a:hover {
        text-decoration: underline;
    }
</style>

<div class="register-wrapper">
    <div class="register-card">
        <div style="text-align: center;">
            <h3 class="register-title">Đăng Ký Thành Viên</h3>
        </div>
        
        <form action="" method="POST" autocomplete="off">
            <div class="form-group">
                <label>Họ và tên</label>
                <input type="text" class="form-control-custom" name="hovaten" placeholder="Nhập họ tên của bạn..." required>
            </div>
            
            <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control-custom" name="email" placeholder="Nhập địa chỉ email..." required>
            </div>

            <div class="form-group">
                <label>Điện thoại</label>
                <input type="text" class="form-control-custom" name="dienthoai" placeholder="Nhập số điện thoại..." required>
            </div>

            <div class="form-group">
                <label>Địa chỉ</label>
                <input type="text" class="form-control-custom" name="diachi" placeholder="Nhập địa chỉ giao hàng..." required>
            </div>

            <div class="form-group">
                <label>Mật khẩu</label>
                <input type="password" class="form-control-custom" name="matkhau" placeholder="Nhập mật khẩu..." required>
            </div>

            <button type="submit" name="dangky" class="btn-register">Đăng Ký Ngay</button>
            
            <div class="login-link">
                <p>Bạn đã có tài khoản? <a href="index.php?quanly=dangnhap">Đăng nhập tại đây</a></p>
            </div>
        </form>
    </div>
</div>