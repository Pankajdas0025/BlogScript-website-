<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer library files
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';
include 'src/db.php';
include 'src/config.php';

if ($_SERVER['REQUEST_METHOD'] === "POST"  && isset($_POST['Name']) && isset($_POST['Email']) && isset($_POST['Password']) && isset($_FILES['Profile'])) {

  // Get POST data and sanitize
  $Username = trim(mysqli_real_escape_string($conn, $_POST['Name']));
  $Email = trim(mysqli_real_escape_string($conn, $_POST['Email']));
  $Password = trim($_POST['Password']); // hash later
  $Profile_img = $_FILES['Profile'];
  $vcode = rand(100000, 999999); // 6-digit verification code

  // Validate email and allow only gmail.com and outlook.com , .in domains

  if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
    $result = array("status" => "error", "message" => "Invalid email format!");
    echo json_encode($result);
    exit();
  } else if (!preg_match('/@((gmail\.com)|(outlook\.com)|(yahoo\.com)|(hotmail\.com)|(live\.com)|(icloud\.com)|(protonmail\.com)|(aol\.com))$/i', $Email)) {
    $result = array("status" => "error", "message" => "Only Gmail, Outlook, Yahoo, Hotmail, Live domains are allowed!");
    echo json_encode($result);
    exit();
  }

  // Check if email already exists
  $check_stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
  $check_stmt->bind_param("s", $Email);
  $check_stmt->execute();
  $check_result = $check_stmt->get_result();

  if ($check_result && $check_result->num_rows >= 1) {
    $result = array("status" => "error", "message" => "😟This email is already registered!");
    echo json_encode($result);
    exit();
  }

  // All fields required
  if (!empty($Username) && !empty($Email) && !empty($Password) && !empty($Profile_img)) {

    // Hash the password
    $hashed_password = password_hash($Password, PASSWORD_DEFAULT);
    // Profile image upload handling--------------------------------------------------------------
    $fileExt = strtolower(pathinfo($Profile_img['name'], PATHINFO_EXTENSION));
    $allowed_Ext = ['jpg', 'jpeg', 'png'];
    $fileSize = $Profile_img['size'];

    // File size check must be
    if ($fileSize > 5*1024) {
      $result = array("status" => "error", "message" => "File size must be 500KB or lower.");
      echo json_encode($result);
      exit();
    }

    // Extension check correct
    if (in_array($fileExt, $allowed_Ext)) {
      $newName = uniqid() . "." . $fileExt;
      $fileDest = "uploads/users/" . $newName;
    } else {
      $result = array("status" => "error", "message" => "This extension file not allowed, Please choose a JPG or PNG file.");
      echo json_encode($result);
      exit();
    }

    // Insert user using prepared statement
    $stmt = $conn->prepare("INSERT INTO users (USER_NAME, EMAIL, PASSWORD, PROFILE_IMG, VERIFICATION_CODE) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $Username, $Email, $hashed_password, $newName, $vcode);

    if ($stmt->execute()) {

      // Move uploaded file to destination
      move_uploaded_file($Profile_img['tmp_name'], $fileDest);

      // PHPMailer setup
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
        $mail->addAddress($Email);
        $mail->isHTML(true);
        $mail->Subject = "Email Verification";
        $mail->Body = "
                <!DOCTYPE html>
<html>
<head>
  <meta charset='UTF-8'>
  <meta name='viewport' content='width=device-width, initial-scale=1.0'>
</head>
<body style='margin: 0; padding:10px; background-color: #f4f4f4; font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
  <div style='max-width: 600px; width: 100%; margin: auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow: hidden;'>
    <div style='background: linear-gradient(to right, #4f46e5, #ec4899); padding: 25px; text-align: center; color: #fff;'>
      <h1 style='margin: 0; font-size: 1.8rem;'>👋 Hey <span style='color: #d1fae5;'> $Username</span>,</h1>
      <p style='margin-top: 10px; font-size: 1rem;'>Welcome to <strong>BlogScript</strong> - Your Space to Create!</p>
    </div>
    <div style='padding: 30px 20px; background-color: #f9fafb;'>
      <p style='font-size: 1rem; text-align: center;'><strong>You're almost there!</strong></p>
      <p style='font-size: 0.95rem; color: #444;'>
        Thank you for joining <strong>BlogScrip</strong>, where you can share your thoughts, tell your stories, and publish meaningful blogs. To get started with your personal admin panel and begin creating content, we just need to verify your email address.
      </p>
      <p style='font-size: 0.95rem; color: #444;'>
        Your admin panel allows you to:
        <ul style='padding-left: 20px; color: #444;'>
          <li>Create and edit blog posts</li>
          <li>Manage your profile and drafts</li>
          <li>Track your publishing activity</li>
          <li>Connect with readers</li>
        </ul>
      </p>
      <div style='text-align: center; margin: 30px 0;'>
        <a href='{$local}/verification?email=$Email&vcode=$vcode' style='text-decoration: none;'>
          <button style='padding: 14px 30px; background-color: #10b981; color: white; border: none; border-radius: 25px; font-size: 1rem; cursor: pointer; box-shadow: 0 4px 6px rgba(0,0,0,0.1);'>
            ✅ Verify Email
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
    <div style='background-color: #e5e7eb; padding: 15px; text-align: center; font-size: 0.8rem; color: #666;'>
      &copy;2025 BlogScript. All rights reserved.<br>
    </div>
  </div>
</body>
</html>
";

        $mail->send();
        $result = array("status" => "success", "message" => "Verification code sent to your registered email!");
        echo json_encode($result);
        exit();
      } catch (Exception $e) {

        // status should be error on failure
        $result = array("status" => "error", "message" => "Something went wrong while sending email. Please try again later.");
        echo json_encode($result);
        exit();
      }
    } else {
      echo "Failed to insert data into database. Error: " . $conn->error;
    }
  } else {
    $result = array("status" => "error", "message" => "All fields are required!");
    echo json_encode($result);
    exit();
  }
} else {
  echo "Invalid request method!";
}
