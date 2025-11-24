<?php
include('../../config/config.php');

// Lấy tất cả dữ liệu text từ POST
$tensp = $_POST['tensp'];
$masp = $_POST['masp'];
$giasp = $_POST['giasp'];
$soluong = $_POST['soluong'];
$tomtat = $_POST['tomtat'];
$noidung = $_POST['noidung'];
$tinhtrang = $_POST['tinhtrang'];
$danhmuc = $_POST['danhmuc'];
$video_url = $_POST['video_url']; // Thêm video URL

$path_uploads = 'uploads/'; // Định nghĩa đường dẫn upload

// Hàm xử lý upload file (trả về tên file hoặc rỗng)
function upload_image($file_input_name, $upload_path, $prefix = '') {
    if (!empty($_FILES[$file_input_name]['name'])) {
        $file_name = time() . $prefix . '_' . $_FILES[$file_input_name]['name'];
        $file_tmp = $_FILES[$file_input_name]['tmp_name'];
        move_uploaded_file($file_tmp, $upload_path . $file_name);
        return $file_name;
    }
    return '';
}

// Hàm xóa file (kiểm tra trước khi xóa)
function unlink_image($file_name, $upload_path) {
    if ($file_name != '' && file_exists($upload_path . $file_name)) {
        unlink($upload_path . $file_name);
    }
}

// ===================================
//  XỬ LÝ THÊM SẢN PHẨM
// ===================================
if (isset($_POST['themsanpham'])) {
    
    // Xử lý upload 4 ảnh
    $hinhanh = upload_image('hinhanh', $path_uploads);
    $hinhanh_phu_1 = upload_image('hinhanh_phu_1', $path_uploads, '_1');
    $hinhanh_phu_2 = upload_image('hinhanh_phu_2', $path_uploads, '_2');
    $hinhanh_phu_3 = upload_image('hinhanh_phu_3', $path_uploads, '_3');

    // Thêm
    $sql_them = "INSERT INTO tbl_sanpham(tensp, masp, giasp, soluong, hinhanh, hinhanh_phu_1, hinhanh_phu_2, hinhanh_phu_3, video_url, tomtat, noidung, tinhtrang, id_danhmuc) 
                 VALUE(
                     '" . $tensp . "', '" . $masp . "', '" . $giasp . "', '" . $soluong . "', 
                     '" . $hinhanh . "', '" . $hinhanh_phu_1 . "', '" . $hinhanh_phu_2 . "', '" . $hinhanh_phu_3 . "', 
                     '" . $video_url . "', '" . $tomtat . "', '" . $noidung . "', '" . $tinhtrang . "', '" . $danhmuc . "'
                 )";
    
    mysqli_query($conn, $sql_them);
    header('Location:../../index.php?action=quanlysp&query=them');

// ===================================
//  XỬ LÝ SỬA SẢN PHẨM
// ===================================
} elseif (isset($_POST['suasanpham'])) {
    
    $id_sanpham = $_GET['idsanpham'];
    
    // Lấy tên file ảnh cũ để xóa
    $sql_get_old_images = "SELECT hinhanh, hinhanh_phu_1, hinhanh_phu_2, hinhanh_phu_3 FROM tbl_sanpham WHERE id_sanpham='" . $id_sanpham . "' LIMIT 1";
    $query_get_old_images = mysqli_query($conn, $sql_get_old_images);
    $row_old_images = mysqli_fetch_array($query_get_old_images);

    // Bắt đầu câu lệnh UPDATE với các trường text
    $sql_update = "UPDATE tbl_sanpham SET 
                    tensp='" . $tensp . "', masp='" . $masp . "', giasp='" . $giasp . "', 
                    soluong='" . $soluong . "', tomtat='" . $tomtat . "', noidung='" . $noidung . "', 
                    tinhtrang='" . $tinhtrang . "', id_danhmuc='" . $danhmuc . "', video_url='" . $video_url . "'";

    // Xử lý ảnh chính (hinhanh)
    if (!empty($_FILES['hinhanh']['name'])) {
        unlink_image($row_old_images['hinhanh'], $path_uploads); // Xóa ảnh cũ
        $hinhanh_moi = upload_image('hinhanh', $path_uploads); // Upload ảnh mới
        $sql_update .= ", hinhanh='" . $hinhanh_moi . "'"; // Thêm vào câu lệnh SQL
    }

    // Xử lý ảnh phụ 1
    if (!empty($_FILES['hinhanh_phu_1']['name'])) {
        unlink_image($row_old_images['hinhanh_phu_1'], $path_uploads);
        $hinhanh_phu_1_moi = upload_image('hinhanh_phu_1', $path_uploads, '_1');
        $sql_update .= ", hinhanh_phu_1='" . $hinhanh_phu_1_moi . "'";
    }

    // Xử lý ảnh phụ 2
    if (!empty($_FILES['hinhanh_phu_2']['name'])) {
        unlink_image($row_old_images['hinhanh_phu_2'], $path_uploads);
        $hinhanh_phu_2_moi = upload_image('hinhanh_phu_2', $path_uploads, '_2');
        $sql_update .= ", hinhanh_phu_2='" . $hinhanh_phu_2_moi . "'";
    }

    // Xử lý ảnh phụ 3
    if (!empty($_FILES['hinhanh_phu_3']['name'])) {
        unlink_image($row_old_images['hinhanh_phu_3'], $path_uploads);
        $hinhanh_phu_3_moi = upload_image('hinhanh_phu_3', $path_uploads, '_3');
        $sql_update .= ", hinhanh_phu_3='" . $hinhanh_phu_3_moi . "'";
    }

    // Hoàn tất câu lệnh UPDATE
    $sql_update .= " WHERE id_sanpham='" . $id_sanpham . "'";
    
    mysqli_query($conn, $sql_update);
    header('Location:../../index.php?action=quanlysanpham&query=them');

// ===================================
//  XỬ LÝ XÓA SẢN PHẨM
// ===================================
} else {
    $id_sanpham = $_GET['idsanpham'];
    
    // Lấy tên tất cả 4 ảnh để xóa
    $sql_get_images = "SELECT hinhanh, hinhanh_phu_1, hinhanh_phu_2, hinhanh_phu_3 FROM tbl_sanpham WHERE id_sanpham='" . $id_sanpham . "' LIMIT 1";
    $query_get_images = mysqli_query($conn, $sql_get_images);
    
    if ($row = mysqli_fetch_array($query_get_images)) {
        // Xóa tất cả 4 ảnh
        unlink_image($row['hinhanh'], $path_uploads);
        unlink_image($row['hinhanh_phu_1'], $path_uploads);
        unlink_image($row['hinhanh_phu_2'], $path_uploads);
        unlink_image($row['hinhanh_phu_3'], $path_uploads);
    }
    
    // Xóa sản phẩm khỏi CSDL
    $sql_xoa = "DELETE FROM tbl_sanpham WHERE id_sanpham='" . $id_sanpham . "'";
    mysqli_query($conn, $sql_xoa);
    
    header('Location:../../index.php?action=quanlysanpham&query=them');
}

?>