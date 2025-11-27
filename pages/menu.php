<?php
    // Lấy danh mục sản phẩm
    $sql_danhmuc = "SELECT * FROM tbl_danhmuc ORDER BY id_danhmuc DESC";
    $query_danhmuc = mysqli_query($conn, $sql_danhmuc);
    
    // Lấy trang hiện tại để active menu
    $quanly = isset($_GET['quanly']) ? $_GET['quanly'] : '';
?>

<?php
    // Xử lý đăng xuất
    if(isset($_GET['dangxuat']) && $_GET['dangxuat'] == 1){
        unset($_SESSION['dangky']);
        unset($_SESSION['email']);
        unset($_SESSION['id_khachhang']);
        header('Location:index.php');
    }
?>

<style>
    nav.navbar {
        width: 100%;
        background: linear-gradient(to right, #1a1a1a, #333); /* Hiệu ứng nền gradient */
    }

    /* Font chữ menu */
    .navbar-nav .nav-link {
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
        padding: 10px 15px !important;
        transition: all 0.3s;
        color: rgba(255,255,255,0.8) !important;
    }
    
    /* Hiệu ứng hover menu */
    .navbar-nav .nav-link:hover, .navbar-nav .active > .nav-link {
        color: #ffc107 !important; /* Màu vàng cam */
    }

    /* Logo hiệu ứng */
    .navbar-brand img {
        transition: transform 0.3s;
        max-height: 50px; 
    }
    .navbar-brand img:hover {
        transform: scale(1.1);
    }
    
    /* Ô tìm kiếm */
    .input-search-custom {
        border-radius: 20px 0 0 20px;
        border: none;
        padding-left: 20px;
    }
    .btn-search-custom {
        border-radius: 0 20px 20px 0;
        background-color: #ffc107;
        border: none;
        color: #333;
        font-weight: bold;
    }
    .btn-search-custom:hover {
        background-color: #e0a800;
    }

    /* --- PHẦN CSS CHO TÀI KHOẢN (ĐĂNG NHẬP/ĐĂNG KÝ) --- */
    .user-greeting {
        color: #ffc107 !important; 
        font-weight: bold;
        border: 1px solid #ffc107;
        border-radius: 30px;
        padding: 5px 15px !important;
    }
    .user-greeting:hover {
        background-color: #ffc107;
        color: #000 !important;
    }

    .btn-login-custom {
        border: 1px solid #fff;
        border-radius: 30px;
        padding: 6px 20px !important;
        color: #fff !important;
        transition: 0.3s;
    }
    .btn-login-custom:hover {
        background-color: #fff;
        color: #000 !important;
        box-shadow: 0 0 10px rgba(255,255,255,0.5);
    }
</style>

<nav class="navbar navbar-expand-lg navbar-dark shadow" style="min-height: 70px;">
  <div class="container-fluid">
      <a class="navbar-brand pl-3" href="index.php">
          <img src="images/logodithun.png" alt="Logo" style="height: 50px; width: auto;">
      </a>
      
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        
        <ul class="navbar-nav mr-auto">
          <li class="nav-item <?php if($quanly == '') echo 'active'; ?>">
            <a class="nav-link" href="index.php"> <i class="fa-solid fa-house"></i> Trang Chủ</a>
          </li>

          <li class="nav-item dropdown <?php if($quanly == 'danhmucsanpham') echo 'active'; ?>">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
              <i class="fa-solid fa-bars"></i> Danh mục
            </a>
            <div class="dropdown-menu">
               <?php while($row_danhmuc = mysqli_fetch_array($query_danhmuc)){ ?>
                  <a class="dropdown-item" href="index.php?quanly=danhmucsanpham&id=<?php echo $row_danhmuc['id_danhmuc']; ?>">
                      <?php echo $row_danhmuc['tendanhmuc']; ?>
                  </a>
               <?php } ?>
            </div>
          </li>

          <li class="nav-item <?php if($quanly == 'giohang') echo 'active'; ?>">
            <a class="nav-link" href="index.php?quanly=giohang">
                <i class="fa-solid fa-cart-shopping"></i> Giỏ hàng
            </a>
          </li>

          <li class="nav-item <?php if($quanly == 'lienhe') echo 'active'; ?>">
              <a class="nav-link" href="index.php?quanly=lienhe">
                  <i class="fa-solid fa-phone-volume"></i> Liên hệ
              </a>
          </li>
        </ul>
        
        <form class="form-inline my-2 my-lg-0 mr-3" action="index.php?quanly=timkiem" method="POST">
          <div class="input-group">
              <input class="form-control input-search-custom" type="search" placeholder="Bạn tìm gì..." name="tukhoa" required size="25">
              <div class="input-group-append">
                <button class="btn btn-search-custom" name="timkiem" type="submit">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
              </div>
          </div>
        </form>

        <ul class="navbar-nav pr-3">
          <?php if(isset($_SESSION['dangky'])){ ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle user-greeting" href="#" role="button" data-toggle="dropdown">
                        <i class="fa-solid fa-user-circle"></i> 
                        <?php echo mb_strimwidth($_SESSION['dangky'], 0, 10, "..."); ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow border-0 mt-2">
                        <a class="dropdown-item" href="index.php?quanly=lichsudonhang">
                            <i class="fa-solid fa-clock-rotate-left text-info"></i> Lịch sử đơn hàng
                        </a>
                        <a class="dropdown-item" href="index.php?quanly=thaydoimatkhau">
                            <i class="fa-solid fa-key text-warning"></i> Đổi mật khẩu
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-danger" href="index.php?dangxuat=1">
                            <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
                        </a>
                    </div>
                </li>
          <?php } else { ?>
                <li class="nav-item">
                    <a class="nav-link btn-login-custom" href="index.php?quanly=dangky">
                        <i class="fa-solid fa-user"></i> Đăng nhập / Đăng ký
                    </a>
                </li>
          <?php } ?>
        </ul>

      </div>
  </div>
</nav>