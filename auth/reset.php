

<?php
session_start();
?>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password</title>
  <!-- CSs link -->
  <link rel="stylesheet" href="assets/css/authentication.css" type="text/css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <?php include '../components/head.php'; ?>
</head>
<body>
    <?php include '../components/header.php';?>
    <div class="box">
        <h2 class="sh" style="padding: 25px; margin:25px 0;">Reset Password🔐  </h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Enter Username or Email.." required>
            <input type="submit" name="reset"  value="Reset Link" style="background-color: rgb(156, 231, 156);">
        </form>
   <div class='msg'></div>
    </div>

<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require '../PHPMailer/src/Exception.php';
include '../src/db.php';
include '../src/config.php';



if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['reset'])) {
    $token = bin2hex(random_bytes(32)); // 64-char secure token
    $expiresAt = date('Y-m-d H:i:s', strtotime('+15 minutes'));
    $username = trim($_POST['username']);

    // Check if user exists by email
    $stmt = $conn->prepare("SELECT ID , EMAIL FROM users WHERE EMAIL=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $email = $user['EMAIL'];

$tokenHash = password_hash($token, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO password_resets (email, token_hash, expires_at) VALUES (? , ? , ? )");
$stmt->bind_param("sss", $email , $tokenHash, $expiresAt);
$stmt->execute();

        // Prepare PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username =  $myemail; // your Gmail ID
            $mail->Password = $password;  // Gmail App Password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->CharSet = 'UTF-8';
            $mail->setFrom($myemail, 'Support BlogScript');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = "Reset Your BlogScript Password";
            $resetLink ="{$local}/update-user-password?token={$token}";
            $mail->Body = "
             <!DOCTYPE html>
<html>
<head>
  <meta charset='UTF-8'>
  <meta name='viewport' content='width=device-width, initial-scale=1.0'>
</head>
<body style='margin: 0; padding:10px; background-color: #f4f4f4; font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
  <div style='max-width: 600px; width: 100%; margin: auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow: hidden;'>

    <!-- Header -->
    <div style='background: linear-gradient(to right, #4f46e5, #ec4899); padding: 25px; text-align: center; color: #fff;'>
      <h1 style='margin: 0; font-size: 1.8rem;'>👋 Hey <span style='color: #d1fae5;'>$email</span>,</h1>
      <p style='margin-top: 10px; font-size: 1rem; color:black;'>Welcome to <strong>BlogScript</strong> - Your Space to Create!</p>
    </div>

    <!-- Body -->
    <div style='padding: 30px 20px; background-color: #f9fafb;'>

      <div style='text-align: center; margin: 30px 0;'>
        <a href='{$resetLink}' style='text-decoration: none;'>
          <button style='padding: 14px 30px; background-color: #10b981; color: white; border: none; border-radius: 25px; font-size: 1rem; cursor: pointer; box-shadow: 0 4px 6px rgba(0,0,0,0.1);'>
            ✅ Reset My Password
          </button>
        </a>
      </div>

      <p style='font-size: 0.9rem; color: #555; text-align: center;'>
        This link is valid for a limited time. Make sure to verify now and unlock your dashboard.
      </p>

      <hr style='border: none; border-top: 1px solid #ddd; margin: 30px 0;'>

      <p style='font-size: 0.85rem; color: #777; text-align: center;'>
        Didn't sign up for BlogScript? No worries — just ignore this email and your account will not be created.
      </p>
    </div>

    <!-- Footer -->
    <div style='background-color: #e5e7eb; padding: 15px; text-align: center; font-size: 0.8rem; color: #666;'>
      &copy;2025 BlogScript. All rights reserved.<br>

    </div>
  </div>
</body>
</html>
";

            $mail->send();
             echo "<script>
$(function () {
    $('.msg')
        .html('✅ A reset link has been sent to your email address.')
        .slideDown(400)
        .delay(3000)
        .slideUp(1000);
});
</script>";


        } catch (Exception $e) {
                 echo "<script>
$(function () {
    $('.msg')
        .html('❌ Failed to send reset email. Please try again later.')
        .slideDown(400)
        .delay(3000)
        .slideUp(1000);
});
</script>";

        }

    } else {
       echo "<script>
$(function () {
    $('.msg')
        .html('😟No account found with that username or email.')
        .slideDown(400)
        .delay(3000)
        .slideUp(1000);
});
</script>";

    }
}
?>
  <?php include '../components/footer.php';?>
</body>
</html>
