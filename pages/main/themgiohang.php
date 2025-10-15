<?php
    session_start();
    include("../../admincp/config/config.php");
    //them san pham vao gio hang
    //them so luong
    if(isset($_GET['cong'])){
        $id=$_GET['cong'];
        foreach($_SESSION['cart'] as $cart_item){
            if($cart_item['id']!=$id){
                $product[]=array('tensp'=>$cart_item['tensp'],'id'=>$cart_item['id'],'giasp'=>$cart_item['giasp'],'hinhanh'=>$cart_item['hinhanh'],'soluong'=>$cart_item['soluong'],'masp'=>$cart_item['masp']);
                $_SESSION['cart']=$product;
            }else{
                $tangsoluong=$cart_item['soluong']+1;
                if($cart_item['soluong']<20){
                $product[]=array('tensp'=>$cart_item['tensp'],'id'=>$cart_item['id'],'giasp'=>$cart_item['giasp'],'hinhanh'=>$cart_item['hinhanh'],'soluong'=>$tangsoluong,'masp'=>$cart_item['masp']);
            }else{
                $product[]=array('tensp'=>$cart_item['tensp'],'id'=>$cart_item['id'],'giasp'=>$cart_item['giasp'],'hinhanh'=>$cart_item['hinhanh'],'soluong'=>$cart_item['soluong'],'masp'=>$cart_item['masp']);
            }
                $_SESSION['cart']=$product;

            }
        }
        header('Location:../../index.php?quanly=giohang');
    }
    //tru so luong
    if(isset($_GET['tru'])){
        $id=$_GET['tru'];
        foreach($_SESSION['cart'] as $cart_item){
            if($cart_item['id']!=$id){
                $product[]=array('tensp'=>$cart_item['tensp'],'id'=>$cart_item['id'],'giasp'=>$cart_item['giasp'],'hinhanh'=>$cart_item['hinhanh'],'soluong'=>$cart_item['soluong'],'masp'=>$cart_item['masp']);
                $_SESSION['cart']=$product;
            }else{
                $tangsoluong=$cart_item['soluong']-1;
                if($cart_item['soluong']>1){
                $product[]=array('tensp'=>$cart_item['tensp'],'id'=>$cart_item['id'],'giasp'=>$cart_item['giasp'],'hinhanh'=>$cart_item['hinhanh'],'soluong'=>$tangsoluong,'masp'=>$cart_item['masp']);
            }else{
                $product[]=array('tensp'=>$cart_item['tensp'],'id'=>$cart_item['id'],'giasp'=>$cart_item['giasp'],'hinhanh'=>$cart_item['hinhanh'],'soluong'=>$cart_item['soluong'],'masp'=>$cart_item['masp']);
            }
                $_SESSION['cart']=$product;

            }
        }
        header('Location:../../index.php?quanly=giohang');
    }
    //xoa san pham
    if(isset($_SESSION['cart'])&&isset($_GET['xoa'])){
        $id=$_GET['xoa'];
        foreach($_SESSION['cart'] as $cart_item){
            if($cart_item['id']!=$id){
                $product[]=array('tensp'=>$cart_item['tensp'],'id'=>$cart_item['id'],'giasp'=>$cart_item['giasp'],'hinhanh'=>$cart_item['hinhanh'],'soluong'=>$cart_item['soluong'],'masp'=>$cart_item['masp']);
            }
            $_SESSION['cart']=$product;
            header('Location:../../index.php?quanly=giohang');
        }
        if(empty($_SESSION['cart'])){
            unset($_SESSION['cart']);
        }
    }
    //xoa tat ca 
    if(isset($_GET['xoatatca'])&&$_GET['xoatatca']==1){
        unset($_SESSION['cart']);
        header('Location:../../index.php?quanly=giohang');

    }
    
//  thêm sản phẩm vào giỏ hàng
if(isset($_POST['themgiohang'])){
    $id = $_GET['idsanpham'];
    $soluong = 1;
    $sql = "SELECT * FROM tbl_sanpham WHERE id_sanpham='$id' LIMIT 1";
    $query = mysqli_query($conn,$sql);
    $row=mysqli_fetch_array($query);
    
    if($row){
        $new_product = array('tensp'=>$row['tensp'],'id'=>$id,'giasp'=>$row['giasp'],'hinhanh'=>$row['hinhanh'],'soluong'=>$soluong,'masp'=>$row['masp']);
        
        // Kiểm tra session giỏ hàng tồn tại
        if(isset($_SESSION['cart'])){
            $found = false;
            
            // Duyệt qua từng sản phẩm trong giỏ hàng
            foreach($_SESSION['cart'] as $key => $cart_item){
                // Nếu sản phẩm đã tồn tại, tăng số lượng
                if($cart_item['id'] == $id){
                    $_SESSION['cart'][$key]['soluong'] += 1;
                    $found = true;
                    break;
                }
            }
            
            // Nếu sản phẩm chưa tồn tại, thêm mới
            if($found == false){
                $_SESSION['cart'][] = $new_product;
            }
        }else{
            // Nếu giỏ hàng chưa tồn tại, tạo mới
            $_SESSION['cart'] = array($new_product);
        }
    }
    
    header('Location:../../index.php?quanly=giohang');
}

    /*
    //them san pham vao gio hang
    if(isset($_POST['themgiohang'])){
        $id = $_GET['idsanpham'];
        $soluong = 1;
        $sql = "SELECT * FROM tbl_sanpham WHERE id_sanpham='$id' LIMIT 1";
        $query = mysqli_query($conn,$sql);
        $row=mysqli_fetch_array($query);
        if($row){
            $new_product=array(array('tensp'=>$row['tensp'],'id'=>$id,'giasp'=>$row['giasp'],'hinhanh'=>$row['hinhanh'],'soluong'=>$soluong,'masp'=>$row['masp']));
        }
        //kiem tra session gio hang ton tai
        if(isset($_SESSION['cart'])){
            $found=false;
            foreach($_SESSION['cart'] as $cart_item){
                //nếu dữ liệu trùng
                if($cart_item['id'] == $id){
                   $product=array(array('tensp'=>$cart_item['tensp'],'id'=>$cart_item['id'],'giasp'=>$cart_item['giasp'],'hinhanh'=>$cart_item['hinhanh'],'soluong'=>$soluong+1,'masp'=>$cart_item['masp']));
                     $found=true;
                }else{
                    // nếu dữ liệu không trùng
                     $product=array(array('tensp'=>$cart_item['tensp'],'id'=>$cart_item['id'],'giasp'=>$cart_item['giasp'],'hinhanh'=>$cart_item['hinhanh'],'soluong'=>$cart_item['soluong'],'masp'=>$cart_item['masp']));
                    
                }
            }
             if($found==false){
                //liên kết dữ liệu new_product với product
               $_SESSION['cart']=array_merge($product,$new_product);
            }else{
                $_SESSION['cart']=$product;

            }
        }else{
            $_SESSION['cart']=$new_product;
        }
    }
       
 
    header('Location:../../index.php?quanly=giohang');
    */        
    
?>