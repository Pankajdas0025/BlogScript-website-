<?php
session_start();
if (isset($_SESSION['email'])) {
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
  <?php include 'components/header.php'; ?>
  <main>
    <!--code For signup form------------------------------------------------------------------------------>
    <div class="box">
      <div class="sh">
        &nbsp; Create Your Account ✨
      </div>
      <form autocomplete="off" id="signupForm">
        <input type="text" id="uName" placeholder="Enter your name" title="Your Full name " onchange="checkName() " required>
        <input type="email" id="uEmail" placeholder="Enter your email" title="Your valid email" onchange="checkEmail() " required>
        <input type="file" id="uProfile" title=" 🧐 Upload Your Profile Image" required>
        <input type="password" id="uPass" placeholder="Password" title="Create a strong password!" onchange="checkPass() " required>
        <input type="submit" name="Signupbtn" value="Confirm" id="Signupbtn">
        <p>Allready have an account ? &nbsp; <a href="register#" id="lbtn">Login</a></p>

        <!-- Model box for showing error use inline css  ================================ -->
        <div class="message">
          <div class="model-box">

          </div>
        </div>



      </form>
      <!--code For login form ------------------------------------------------------------------------------>
      <div class="box2">
        <div class="sh">
          Welcome Back 👋
        </div>
        <P>Login to continue to your blog dashboard</P>
        <form action="login" method="post" autocomplete="off">
          <input type="text" name="uEmail" placeholder="Enter your email" required>
          <input type="password" name="uPass" placeholder="Password" required>
          <input type="submit" name="Loginbtn" value="Login" id="Loginbtn">
          <a style="margin:1.2px auto;" href="reset-password" id="Sibtn">Forgot Password</a>
          <p>New user? &nbsp; <a href="#" id="Sibtn">Sign Up</a></p>
        </form>
      </div>
    </div>
  </main>
  <!-- Jquary and AJAX reqest to send signup from data to signup.php and recive result ---------------------------------------------------------->
  <script>
    $(document).ready(function() {
      $("#signupForm").on("submit", function(e) {
        e.preventDefault();
        var username = $("#uName").val().trim();
        var email = $("#uEmail").val().trim();
        var pass = $("#uPass").val().trim();
        var profile = $("#uProfile")[0].files[0];
        if (username === "" || email === "" || pass === "" || !profile) {
          alert("All fields are required!");
          return false;
        }
        var formData = new FormData();
        formData.append('Name', username);
        formData.append('Email', email);
        formData.append('Profile', profile);
        formData.append('Password', pass);
        $.ajax({
          type: 'POST',
          url: 'signup',
          data: formData,
          dataType: 'json',
          processData: false,
          contentType: false,
          success: function(response) {
            if (response.status === "error") {
              $(".message").show();
              $(".model-box").html(response.message).css('color', 'red');
            } else if (response.status === "success") {
              $(".message").show();
              $(".model-box").html(response.message).css('color', 'green');
              $("#signupForm")[0].reset();
            }
            setTimeout(function() {
              $(".message").hide();
            }, 5000);
          }
        });
      });
    });
  </script>


  <script src="script.js"></script>
  <?php include 'components/footer.php'; ?>
</body>

</html>