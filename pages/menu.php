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

<!-- CSS TÙY CHỈNH CHO MENU -->
<style>
    
    nav.navbar {
        width: 100%; /* Chiều ngang 100% */
        width: 100vw; /* Chiều ngang 100% viewport (khung nhìn) */
        
      
    }

    /* Font chữ menu gọn gàng, hiện đại */
    .navbar-nav .nav-link {
        font-size: 15px; /* Giảm nhẹ size cho tinh tế */
        font-weight: 600;
        text-transform: uppercase; /* Chữ in hoa sang trọng */
        padding: 10px 15px !important;
        transition: all 0.3s;
    }
    
    /* Hiệu ứng khi di chuột vào */
    .navbar-nav .nav-link:hover {
        color: #ffc107 !important; /* Màu vàng cam nổi bật trên nền đen */
    }

    /* Icon bên cạnh chữ */
    .navbar-nav i {
        margin-right: 5px;
        font-size: 16px;
    }
    
    /* Logo */
    .navbar-brand img {
        transition: transform 0.3s;
        max-height: 50px; /* Giới hạn chiều cao logo */
    }
    .navbar-brand img:hover {
        transform: scale(1.05);
    }
    
    /* Nút tìm kiếm đẹp hơn */
    .btn-search-custom {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }
    .input-search-custom {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }
</style>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark py-2 shadow" style="min-height: 70px;">
  <div class="container-fluid">
      <!-- LOGO -->
      <a class="navbar-brand pl-3" href="index.php">
          <!-- Lưu ý: Đảm bảo đường dẫn ảnh đúng -->
          <img src="images/logodithun.png" alt="Logo" style="height: 100px; width: 100px;">
      </a>
      
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        
        <!-- PHẦN MENU BÊN TRÁI -->
        <ul class="navbar-nav mr-auto">
          
          <li class="nav-item <?php if($quanly == '') echo 'active'; ?>">
            <a class="nav-link" href="index.php">
                <i class="fa-solid fa-house"></i> Trang Chủ
            </a>
          </li>

          <li class="nav-item dropdown <?php if($quanly == 'danhmucsanpham') echo 'active'; ?>">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
              <i class="fa-solid fa-bars"></i> Danh mục
            </a>
            <div class="dropdown-menu">
               <?php while($row_danhmuc = mysqli_fetch_array($query_danhmuc)){ ?>
                  <a class="dropdown-item" href="index.php?quanly=danhmucsanpham&id=<?php echo $row_danhmuc['id_danhmuc']; ?>">
                      <i class="fa-solid fa-caret-right text-muted"></i> <?php echo $row_danhmuc['tendanhmuc']; ?>
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
        
        <!-- PHẦN TÌM KIẾM -->
        <form class="form-inline my-2 my-lg-0 mr-3" action="index.php?quanly=timkiem" method="POST">
          <div class="input-group">
              <input class="form-control input-search-custom" type="search" placeholder="Bạn tìm gì..." name="tukhoa" required size="30">
              <div class="input-group-append">
                <button class="btn btn-light text-dark btn-search-custom" name="timkiem" type="submit">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
              </div>
          </div>
        </form>

        <!-- PHẦN TÀI KHOẢN -->
        <ul class="navbar-nav pr-3">
          <?php if(isset($_SESSION['dangky'])){ ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-warning" href="#" role="button" data-toggle="dropdown">
                        <i class="fa-solid fa-user-circle"></i> 
                        <?php echo mb_strimwidth($_SESSION['dangky'], 0, 15, "..."); ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow">
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
                <li class="nav-item <?php if($quanly == 'dangky') echo 'active'; ?>">
                    <a class="nav-link btn btn-outline-light px-3 py-1 mt-1" href="index.php?quanly=dangky" style="border-radius: 20px; font-size: 14px;">
                        <i class="fa-solid fa-user"></i> Đăng nhập
                    </a>
                </li>
          <?php } ?>
        </ul>

      </div>
  </div>
</nav>