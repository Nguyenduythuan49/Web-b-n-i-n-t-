<div class="clear"></div>

<footer class="footer">
  <div class="footer-container">

    <!-- Cột 1: Logo và mô tả -->
    <div class="footer-col">
      <h3 class="footer-logo">DiThun</h3>
      <p>Website chuyên cung cấp điện thoại, laptop và phụ kiện chính hãng. Cam kết chất lượng và uy tín hàng đầu.</p>
    </div>

    <!-- Cột 2: Liên kết nhanh -->
    <div class="footer-col">
      <h4>Liên kết nhanh</h4>
      <ul>
        <li><a href="#">Giới thiệu</a></li>
        <li><a href="#">Sản phẩm</a></li>
        <li><a href="#">Khuyến mãi</a></li>
        <li><a href="#">Tin tức</a></li>
        <li><a href="#">Liên hệ</a></li>
      </ul>
    </div>

    <!-- Cột 3: Hỗ trợ khách hàng -->
    <div class="footer-col">
      <h4>Hỗ trợ khách hàng</h4>
      <ul>
        <li><a href="#">Hướng dẫn mua hàng</a></li>
        <li><a href="#">Chính sách bảo hành</a></li>
        <li><a href="#">Chính sách đổi trả</a></li>
        <li><a href="#">Chính sách bảo mật</a></li>
      </ul>
    </div>

    <!-- Cột 4: Liên hệ -->
    <div class="footer-col">
      <h4>Liên hệ</h4>
      <p><strong>Địa chỉ:</strong> 2 Võ Oanh ,phường 25,quận Bình Thạnh, TP.HCM</p>
      <p><strong>Điện thoại:</strong> 0702479538</p>
      <p><strong>Email:</strong> nguyenduythuan.ng@gmail.com</p>
    </div>

  </div>

  <div class="footer-bottom">
    <p class="footer_coppyright">© 2025 DuyThuan Developer. All rights reserved.</p>
  </div>

 <!-- chatbox -->
  <style>
  /* Đây là kiểu dáng của nút tròn */
  .messenger-chat-bubble {
    position: fixed; /* Giúp nút luôn nổi trên màn hình */
    bottom: 25px;    /* Cách lề dưới 25px */
    right: 25px;     /* Cách lề phải 25px */
    width: 60px;       /* Chiều rộng 60px */
    height: 60px;      /* Chiều cao 60px */
    background-color: #0084FF; /* Màu xanh đặc trưng của Messenger */
    color: white;      /* Màu của biểu tượng (SVG) bên trong */
    border-radius: 50%; /* Biến nó thành hình tròn */
    
    /* Căn giữa biểu tượng SVG */
    display: flex;
    align-items: center;
    justify-content: center;

    /* Hiệu ứng đổ bóng cho đẹp */
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    
    /* Hiệu ứng khi di chuột vào */
    transition: all 0.2s ease-in-out;

    /* Đảm bảo nút luôn nằm trên cùng */
    z-index: 1000;
  }

  /* Tùy chỉnh kích thước biểu tượng bên trong (nếu cần) */
  .messenger-chat-bubble svg {
    width: 32px;
    height: 32px;
  }

  /* Hiệu ứng khi di chuột vào: Nút sẽ mờ đi một chút */
  .messenger-chat-bubble:hover {
    background-color: #0073e6; /* Đổi màu đậm hơn một chút */
    transform: scale(1.05); /* Phóng to nhẹ */
  }
</style>

<a href="https://m.me/807076342498406" class="messenger-chat-bubble" target="_blank" aria-label="Chat on Messenger">
  <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path fill-rule="evenodd" clip-rule="evenodd" d="M12 0C5.37258 0 0 5.37258 0 12C0 18.6274 5.37258 24 12 24C13.2066 24 14.3751 23.8051 15.461 23.4475C16.3262 23.1537 17.1519 23.1783 17.9238 23.5152L22.071 25.1768C23.0076 25.5678 23.9015 24.6739 23.5105 23.7373L21.8489 19.59C21.512 18.8181 21.5366 17.9924 21.8304 17.2272C23.2354 15.2285 24 12.793 24 10.2857C24 4.60558 18.6274 0 12 0ZM15.75 14.8889C15.3571 14.8889 15.0004 14.5317 15.0004 14.1389C15.0004 13.746 15.3571 13.3889 15.75 13.3889H15.7504C16.1433 13.3889 16.5 13.746 16.5 14.1389C16.5 14.5317 16.1433 14.8889 15.7504 14.8889H15.75ZM11.9996 14.8889C11.6067 14.8889 11.25 14.5317 11.25 14.1389C11.25 13.746 11.6067 13.3889 11.9996 13.3889H12C12.3929 13.3889 12.75 13.746 12.75 14.1389C12.75 14.5317 12.3929 14.8889 12 14.8889H11.9996ZM8.24919 14.8889C7.8563 14.8889 7.49958 14.5317 7.49958 14.1389C7.49958 13.746 7.8563 13.3889 8.24919 13.3889H8.25C8.64288 13.3889 9 13.746 9 14.1389C9 14.5317 8.64288 14.8889 8.25 14.8889H8.24919Z" fill="white"/>
  </svg>
</a>
</footer>
