<?php
    header('Content-type: text/html; charset=utf-8');
    
    include('helper_momo.php');

    $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";

    $partnerCode = 'MOMOBKUN20180529';
    $accessKey = 'klm05TvNBzhg7h7j';
    $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
    $orderInfo = "Thanh toán qua MoMo ATM";
    
    // Kiểm tra xem có nhận được tổng tiền không, nếu không gán mặc định để tránh lỗi
    $amount = isset($_POST['tongtien_vnd']) ? $_POST['tongtien_vnd'] : 10000;
    
    $orderId = time() ."";
    
    // SỬA LẠI ĐƯỜNG DẪN: Đổi localhost thành tên miền thật của bạn để Momo trả về đúng chỗ
    // Nếu bạn test local thì đổi lại thành http://localhost/...
    $redirectUrl = "http://dithun.khqi.io.vn/index.php?quanly=camon";
    $ipnUrl = "http://dithun.khqi.io.vn/index.php?quanly=camon";
    
    $requestId = time() . "";
    $requestType = "payWithATM";
    
    // SỬA LỖI CHÍNH Ở ĐÂY: Kiểm tra isset trước khi dùng
    $extraData = isset($_POST["extraData"]) ? $_POST["extraData"] : "";
    
    //before sign HMAC SHA256 signature
    $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
    
    $signature = hash_hmac("sha256", $rawHash, $secretKey);
    
    $data = array('partnerCode' => $partnerCode,
        'partnerName' => "Test",
        "storeId" => "MomoTestStore",
        'requestId' => $requestId,
        'amount' => $amount,
        'orderId' => $orderId,
        'orderInfo' => $orderInfo,
        'redirectUrl' => $redirectUrl,
        'ipnUrl' => $ipnUrl,
        'lang' => 'vi',
        'extraData' => $extraData,
        'requestType' => $requestType,
        'signature' => $signature);
    
    $result = execPostRequest($endpoint, json_encode($data));
    $jsonResult = json_decode($result, true);  // decode json

    // Kiểm tra nếu có link thanh toán thì mới chuyển hướng
    if(isset($jsonResult['payUrl'])){
        header('Location: ' . $jsonResult['payUrl']);
    } else {
        // Nếu lỗi thì in ra xem Momo báo gì (thường do sai chữ ký hoặc số tiền)
        print_r($jsonResult); 
    }
?>