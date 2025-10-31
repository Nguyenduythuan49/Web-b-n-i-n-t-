<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
       
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <title>Web đồ điện tử</title>
</head>
<body>
    <div class="container-fluid">
        <div class="row">

       <?php
          session_start();
         include("admincp/config/config.php");
         include("pages/header.php");
         include("pages/menu.php");
         include("pages/main.php");
         include("pages/footer.php");
       ?>
       </div>
     </div> 
    
     <script type ="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
     <script type ="text/javascript" >
$('#tab-nav li::first-child').addClass('active');
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
</body>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script> 
</html>