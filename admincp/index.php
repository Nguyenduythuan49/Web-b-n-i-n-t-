<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admincp</title>
    <link rel="stylesheet" type="text/css" href="css/styleadmincp.css">
    
</head>
<?php
    session_start();
    if(!isset($_SESSION['dangnhap'])){
        header("Location:login.php");
    }
?>
<body>
    <h3 class="tittle_admin">Welcome to Di Thun admin</h3>
    <div class="wrapper">
         <?php
         include("config/config.php");
         include("modules/header.php");
         include("modules/menu.php");
         include("modules/main.php");
         include("modules/footer.php");
       ?>
</div>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="//cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
    <script type="text/javascript">
        // Show the first tab and hide the rest
$('#tabs-nav li:first-child').addClass('active');
$('.tab-content').hide();
$('.tab-content:first').show();

// Click function
$('#tabs-nav li').click(function(){
  $('#tabs-nav li').removeClass('active');
  $(this).addClass('active');
  $('.tab-content').hide();
  
  var activeTab = $(this).find('a').attr('href');
  $(activeTab).fadeIn();
  return false;
});
    </script>
    <script>
    
	CKEDITOR.replace( 'thongtinlienhe' );
    CKEDITOR.replace( 'tomtat' );
    CKEDITOR.replace( 'noidung' );
	</script>   
</body>
</html>