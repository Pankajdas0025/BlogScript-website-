<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> Verification</title>
   <!--favicon ------------------------------------------------------------------------------>
<link rel="apple-touch-icon" sizes="180x180" href="favicon_io/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="favicon_io/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="favicon_io/favicon-16x16.png">
<link rel="manifest" href="favicon_io/site.webmanifest">

  <!-- CSs link -->
  <link rel="stylesheet" href="Style/Authentication.css" type="text/css">
  <!-- cdn JQUARY -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

</head>
<body>

  <main>
<!--code For signup form------------------------------------------------------------------------------>
  <div class="box">
  <br>
  <br>
  <br>
  <?php
include 'src/db.php';
include 'src/config.php';
$email = $_GET['email'];
$vcode=$_GET['vcode'];

$result = $conn->query("SELECT * FROM users WHERE EMAIL='$email' ");
$data = $result->fetch_assoc();
$Useremail=$data['EMAIL'];
$Verificationcode=$data['VERIFICATION_CODE'];

?>
      <form autocomplete="off" method="POST">
        <input type="email"  name="Femail" placeholder="Email" value="<?= $email?>">
        <input type="text"  name="Fvcode" placeholder="Verification Code" value="<?= $vcode?>" >
        <input type="submit" name="Veripbtn" value="Verify" id="Signupbtn" onmouseover="this.style.backgroundColor='green'" onmouseout="this.style.backgroundColor='#6366f1'">
        <input type="text" id="PP" style="box-shadow:none; border:none; outline:none; color:green; font-size:25px; text-align:center;" readonly>

      </form>
    </div>

 <?php

if ($_SERVER['REQUEST_METHOD']=='POST')
{

$Femail = $_POST['Femail'];
$Fvcode=$_POST['Fvcode'];

if(($Femail==$Useremail)&&($Fvcode==$Verificationcode))
{

$sql="UPDATE users SET VERIFICATION_STATUS='Success' WHERE EMAIL='$Femail'";
if($conn->query($sql))
{

echo "<script>
  $(document).ready(function()
  {
   $('#PP').val('Verification successful');
   $('#PP').css('color', 'green');

  });

   setTimeout(function () {
           window.location.href = 'register#';
          }, 3000);
</script>";
}

}
else
echo "<script>
  $(document).ready(function() {
    $('#PP').val('Verification Failed');
   $('#PP').css('color', 'red');

  });
</script>";

}

 ?>
  </main>
</body>
</html>