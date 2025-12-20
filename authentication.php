<?php
session_start();
if (isset($_SESSION['email']))
    {
    header("Location:admin");
    exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Signup | Login</title>
   <!--favicon ------------------------------------------------------------------------------>
<link rel="apple-touch-icon" sizes="180x180" href="favicon_io/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="favicon_io/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="favicon_io/favicon-16x16.png">
<!-- cdn JQUARY -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="style/authentication.css">
</head>
<body>
  <!-- HEADER -->
<?php include 'components/header.php';?>
  <main>
<!--code For signup form------------------------------------------------------------------------------>
    <div class="box">
      <div class="sh">
         &nbsp; Create Your Account ✨
      </div>
      <form autocomplete="off" >
        <input type="text" id="uName" placeholder="Enter your name" onchange="checkName() "required>
             <input type="email" id="uEmail" placeholder="Enter your email"  onchange="checkEmail() "required>
                 <input type="password" id="uPass" placeholder="Password" onchange="checkPass() "required>
                             <input type="submit" name="Signupbtn" value="Confirm" id="Signupbtn">
                                    <p>Allready have an account ? &nbsp; <a href="register#" id="lbtn">Login</a></p>
                                    <div class="message"> </div>
                                          </form>
<!--code For login form ------------------------------------------------------------------------------>
      <div class="box2">
          <div class="sh">
                  Welcome Back 👋
                        </div>
      <P>Login to continue to your blog dashboard</P>
      <form action="login" method="post">
            <input type="text" name="uEmail" placeholder="Enter your email" required>
                  <input type="password" name="uPass" placeholder="Password" required>
                           <input type="submit" name="Loginbtn" value="Login" id="Loginbtn">
                          <center> <a href="reset-password" id="Sibtn">Forgot Password</a></center>
                                      <p>New user? &nbsp; <a href="#" id="Sibtn">Sign Up</a></p>
      </form>
      </div>
    </div>


<!-- Jquary and AJAX reqest to send signup from data to signup.php and recive result ---------------------------------------------------------->
    <script>
  $(document).ready(function () {
    $("#Signupbtn").click(function (e) {
      e.preventDefault(); // Prevent default form submission
      // variable assignments
      var username = $("#uName").val().trim();
      var email = $("#uEmail").val().trim();
      var pass = $("#uPass").val().trim();
      if(username==="" && email==="" && pass==="")
    {
      alert("All files is Requied!");
    }

      $.ajax({
        type: "POST",
        url: "signup",
        data: {
          Name: username,
          Email: email,
          Password: pass
        },
        success: function (response) {
          $(".message").html(response).show();
// set time out to hide the popup alert  window .............................................................................>
          setTimeout(function () {
            $(".message").hide();
          },50000);
        }
      });
    });
  });
</script>
  </main>

  <script src="script.js"></script>
  <?php include 'components/footer.php';?>
</body>
</html>