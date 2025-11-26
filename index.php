<?php
    session_start();
    include("admincp/config/config.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <title>Di Thun store</title>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php
                include("pages/header.php");
                include("pages/menu.php");
                include("pages/main.php");
                include("pages/footer.php");
            ?>
        </div>
    </div> 

    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script> 
    
    <script type="text/javascript">
        $('#tab-nav li:first-child').addClass('active'); // Sửa lại selector css (:: -> :)
        $('.tab-content').hide();
        $('.tab-content:first').show();

        $('#tab-nav li').click(function(){
            $('#tab-nav li').removeClass('active');
            $(this).addClass('active');
            $('.tab-content').hide();
            var activeTab = $(this).find('a').attr('href');
            $(activeTab).fadeIn();
            return false;
        });
    </script>

    <script src="https://www.paypal.com/sdk/js?client-id=AWB8zRaQdHDvb-wwBByBse3lmKzJh1kBX_EsFfK0Fr8pkSf9hIlX-x2KT3qEqyOCvMvfrq9ehf4vrfQu&currency=USD"></script>
    <script>
        paypal.Buttons({
            style: {
                layout: 'vertical',
                color:  'blue',
                shape:  'rect',
                label:  'paypal'
            },
            createOrder: function(data, actions) {
                // Lưu ý: Biến $tongtien_usd phải được tính toán trong các file include ở trên
                // Nếu chưa có biến này, code Javascript sẽ bị lỗi cú pháp
                var tongtien = <?php echo isset($tongtien_usd) ? $tongtien_usd : 0; ?>;
                
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: tongtien 
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(orderData) {
                    console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
                    var transaction = orderData.purchase_units[0].payments.captures[0];
                    alert('Giao dịch ' + transaction.status + ': ' + transaction.id + '\n\nXem chi tiết trong console.');
                    // Sửa lại đường dẫn localhost cho chính xác với máy của bạn
                    window.location.replace("http://localhost/dithun/index.php?quanly=camon&thanhtoan=paypal");
                });
            },
            oncancel: function (data) {
                window.location.replace("http://localhost/dithun/index.php?quanly=thongtinthanhtoan");
            }
        }).render('#paypal-button');
    </script>
</body>
</html>