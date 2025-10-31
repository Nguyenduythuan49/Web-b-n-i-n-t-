<?php
include("../../admincp/config/config.php");
if(isset($_POST['guilienhe'])){
    $username = $_POST['username'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $note = $_POST['note'];
    $status_lienhe='1';
    $sql = "INSERT INTO tbl_thongbaolienhe (username, phone, email, note,status_lienhe) VALUES ('$username', '$phone', '$email', '$note','$status_lienhe')";
    $row = mysqli_query($conn, $sql);
header('Location:../../index.php?quanly=lienhe');
    }
?>