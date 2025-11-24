<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

<?php
    // === HÀM TỰ ĐỘNG CHUYỂN LINK YOUTUBE (Giữ nguyên) ===
    function getYouTubeEmbedUrl($url) {
        if (!$url) {
            return null;
        }

        $video_id = '';
        if (preg_match("/youtu\.be\/([a-zA-Z0-9_-]+)/", $url, $matches)) {
            $video_id = $matches[1];
        }
        else if (preg_match("/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/", $url, $matches)) {
            $video_id = $matches[1];
        }
        else if (preg_match("/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/", $url, $matches)) {
            $video_id = $matches[1];
        }

        if ($video_id) {
            return "https://www.youtube.com/embed/" . $video_id;
        }

        return null; 
    }
    // === KẾT THÚC HÀM ===


    $sql_chitiet = "SELECT * FROM tbl_sanpham,tbl_danhmuc
                    WHERE tbl_sanpham.id_danhmuc = tbl_danhmuc.id_danhmuc
                    AND tbl_sanpham.id_sanpham='$_GET[id]' LIMIT 1";
    $query_chitiet = mysqli_query($conn, $sql_chitiet);

    while ($row_chitiet = mysqli_fetch_array($query_chitiet)) {
        
        $raw_video_url = (isset($row_chitiet['video_url']) && !empty($row_chitiet['video_url'])) ? $row_chitiet['video_url'] : null;
        $embed_video_url = getYouTubeEmbedUrl($raw_video_url); 
        
        $array_hinh_anh = array();
        $base_path = "admincp/modules/quanlysp/uploads/";
        
        if (!empty($row_chitiet['hinhanh'])) {
            $array_hinh_anh[] = $row_chitiet['hinhanh'];
        }
        if (isset($row_chitiet['hinhanh_phu_1']) && !empty($row_chitiet['hinhanh_phu_1'])) {
            $array_hinh_anh[] = $row_chitiet['hinhanh_phu_1'];
        }
        if (isset($row_chitiet['hinhanh_phu_2']) && !empty($row_chitiet['hinhanh_phu_2'])) {
            $array_hinh_anh[] = $row_chitiet['hinhanh_phu_2'];
        }
        if (isset($row_chitiet['hinhanh_phu_3']) && !empty($row_chitiet['hinhanh_phu_3'])) {
            $array_hinh_anh[] = $row_chitiet['hinhanh_phu_3'];
        }
?>
<div class="wrapper_chitiet">

    <div class="product-gallery">
        <?php 
        if ($embed_video_url || !empty($array_hinh_anh)) { 
        ?>
            <div class="swiper" id="gallery-main">
                <div class="swiper-wrapper">

                    <?php if ($embed_video_url) { ?>
                    <div class="swiper-slide video-slide">
                        <iframe 
                            src="<?php echo htmlspecialchars($embed_video_url); ?>" 
                            title="Video sản phẩm"
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen>
                        </iframe>
                    </div>
                    <?php } ?>

                    <?php
                    // Lặp qua mảng ảnh để tạo các slide chính
                    foreach ($array_hinh_anh as $hinh_anh_file) {
                        if (!empty($hinh_anh_file)) {
                            $full_path = $base_path . $hinh_anh_file;
                    ?>
                    <div class="swiper-slide">
                        <img src="<?php echo $full_path; ?>" alt="Hình ảnh sản phẩm chính">
                    </div>
                    <?php
                        }
                    }
                    ?>
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
            
            <div class="swiper" id="gallery-thumbs">
                <div class="swiper-wrapper">

                    <?php if ($embed_video_url) { ?>
                    <div class="swiper-slide video-thumb">
                        <div class="video-thumb-content">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="30" height="30"><path fill="currentColor" d="M7 4v16l13-8z"></path></svg>
                            <span>Video</span> 
                        </div>
                    </div>
                    <?php } ?>

                    <?php
                    // Lặp qua mảng ảnh LẦN NỮA
                    foreach ($array_hinh_anh as $hinh_anh_file) {
                        if (!empty($hinh_anh_file)) {
                            $full_path = $base_path . $hinh_anh_file;
                    ?>
                    <div class="swiper-slide">
                        <img src="<?php echo $full_path; ?>" alt="Hình ảnh thu nhỏ">
                    </div>
                    <?php
                        }
                    }
                    ?>
                </div>
            </div>
        <?php 
        } else {
            echo "<p>Sản phẩm này hiện chưa có hình ảnh hoặc video.</p>";
        }
        ?>
    </div>
    
    <form method="POST" action="pages/main/themgiohang.php?idsanpham=<?php echo $row_chitiet['id_sanpham'] ?>">
        <div class="chitiet_sanpham">
            <h3 style="margin:0;"> Tên sản phẩm: <?php echo $row_chitiet['tensp'] ?></h3>
            <p>Mã sản phẩm: <?php echo $row_chitiet['masp'] ?></p>
            <p>Giá sản phẩm: <?php echo number_format($row_chitiet['giasp'],0,',','.')." VNĐ" ?></p>
            <p>Số lượng: <?php echo $row_chitiet['soluong'] ?></p>
            <p>Danh mục: <?php echo $row_chitiet['tendanhmuc'] ?></p>
            <p><input class="themgiohang" name="themgiohang" type="submit" value="Thêm giỏ hàng"></p>
        </div>
    </form>
</div>
<div class="clear" style="clear: both;"></div>

<div class="tabs">
    <ul id="tabs-nav">
        <li class="active"><a href="#tab1" onclick="openTab(event, 'tab1')">Thông Số Kỹ Thuật</a></li>
    </ul>
    <div id="tabs-content">
        <div id="tab1" class="tab-content" style="display: block;">
            <?php echo $row_chitiet['tomtat'] ?>
        </div>
    </div> 
</div> 
<?php
    } // Kết thúc vòng lặp while
?>

<!-- <style>
    
    /* 1. Định dạng slide video (Giữ nguyên) */
    #gallery-main .swiper-slide.video-slide {
        position: relative;
        padding-top: 56.25%; 
        height: auto;
        background: #000;
    }
    #gallery-main .video-slide iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    /* 2. Cố định chiều cao cho gallery-thumbs (Giữ nguyên) */
    #gallery-thumbs {
         height: 100px; 
         margin-top: 10px; 
    }
    #gallery-thumbs .swiper-slide {
        height: 100%; 
        border-radius: 4px;
        border: 1px solid #ddd;
    }

    /* 3. Định dạng thumbnail video (Giữ nguyên) */
    #gallery-thumbs .swiper-slide.video-thumb {
        cursor: pointer;
        background: #f4f4f4;
    }
    #gallery-thumbs .video-thumb-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        color: #333;
    }
    #gallery-thumbs .video-thumb-content svg { fill: #555; }
    #gallery-thumbs .video-thumb-content span {
        font-size: 12px;
        font-weight: 600;
        margin-top: 5px;
        text-align: center;
    }

    /* === BẮT ĐẦU SỬA CSS CHO ẢNH === */
    /* 4. Đảm bảo slide ảnh có chiều cao tự động */
    #gallery-main .swiper-slide {
        height: auto; /* Quan trọng: để autoHeight hoạt động */
    }

    /* 5. Đảm bảo ảnh thường vẫn đẹp */
    #gallery-main .swiper-slide img {
        display: block; /* Thêm vào để sửa lỗi layout */
        width: 100%;
        height: auto; /* QUAN TRỌNG: Đổi từ 100% thành auto */
        max-height: 70vh; /* Thêm vào: Giới hạn chiều cao tối đa (70% chiều cao màn hình) */
        object-fit: contain;
    }
    /* === KẾT THÚC SỬA CSS === */

    #gallery-thumbs .swiper-slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        cursor: pointer;
    }

    /* 6. Style cho thumb được chọn (Giữ nguyên) */
    #gallery-thumbs .swiper-slide-thumb-active {
        border: 2px solid #007bff; 
    }
    #gallery-thumbs .video-thumb.swiper-slide-thumb-active {
        border-color: #007bff; 
        background: #e9f5ff;
    }
</style> -->

<script>
    // === JavaScript cho Swiper Gallery ===
    document.addEventListener("DOMContentLoaded", function() {
        // Khởi tạo slider ảnh nhỏ (Thumbs)
        var galleryThumbs = new Swiper("#gallery-thumbs", {
            spaceBetween: 10,
            slidesPerView: 'auto', 
            freeMode: true,
            watchSlidesProgress: true,
        });

        // Khởi tạo slider ảnh lớn (Main)
        var galleryMain = new Swiper("#gallery-main", {
            spaceBetween: 10,
            
            // ===================================
            // === SỬA LỖI: THÊM DÒNG NÀY VÀO ===
            autoHeight: true, // Tự động điều chỉnh chiều cao slider
            // ===================================

            thumbs: {
                swiper: galleryThumbs
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            on: {
                slideChange: function () {
                    // Dừng video khi chuyển slide (Giữ nguyên)
                    var allIframes = document.querySelectorAll('#gallery-main .video-slide iframe');
                    allIframes.forEach(function(iframe) {
                        if (iframe) {
                            var currentSrc = iframe.src;
                            iframe.src = currentSrc; 
                        }
                    });
                }
            }
        });
    });

    // === JavaScript cho Tabs (Giữ nguyên) ===
    document.addEventListener("DOMContentLoaded", function() {
        if (!window.tabsInitialized) {
            var i, tabcontent, tablinks;
            
            tabcontent = document.getElementsByClassName("tab-content");
            for (i = 0; i < tabcontent.length; i++) {
                if(i > 0) tabcontent[i].style.display = "none";
            }
            
            var firstTab = document.getElementById("tab1");
            if (firstTab) {
                firstTab.style.display = "block";
            }

            var firstLink = document.querySelector("#tabs-nav li");
            if (firstLink) {
                firstLink.classList.add("active");
            }
            window.tabsInitialized = true; 
        }
    });

    function openTab(evt, tabName) {
        if(evt) {
            evt.preventDefault(); 
        }
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tab-content");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementById("tabs-nav").getElementsByTagName("li");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].classList.remove("active");
        }
        var tabToOpen = document.getElementById(tabName);
        if (tabToOpen) {
            tabToOpen.style.display = "block";
        }
        if (evt && evt.currentTarget) {
            evt.currentTarget.parentElement.classList.add("active");
        }
    }
</script>