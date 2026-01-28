<?php
require '../src/config.php';
require '../src/db.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require '../PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Handle send email for post update
if(isset($_POST['send_email']) && isset($_POST['post_id'])){
    $post_id = intval($_POST['post_id']);

    // Get post details
    $query = "SELECT p.*, u.EMAIL, u.USER_NAME FROM posts p
              LEFT JOIN users u ON p.user_id = u.ID
              WHERE p.id = '$post_id'";
    $result = mysqli_query($conn, $query);

    if($result && mysqli_num_rows($result) > 0){
        $post = mysqli_fetch_assoc($result);
        $user_email = $post['EMAIL'];
        $user_name = $post['USER_NAME'];
        $post_title = $post['title'];
        $post_status = $post['status'];

        // Determine status message

        $status_message = 'Your post is still Pending approval.';

        // Send email using PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username =  $myemail; // your Gmail ID
            $mail->Password = $password;  // Gmail App Password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->CharSet = 'UTF-8';
            $mail->setFrom($myemail, 'Support BlogScript');
            $mail->addAddress($user_email, $user_name);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Post Status Update: ' . $post_title;
            $mail->Body = "
                            <!DOCTYPE html>
                    <html>
                    <head>
                    <meta charset='UTF-8'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    <title>Post Review Update</title>
                    </head>
                    <body style='margin:0; padding:0; background-color:#f4f6f8; font-family: sans-serif;'>
                    <div style='max-width:620px; margin:20px auto; background-color:#ffffff;  overflow:hidden; box-shadow:0 6px 18px rgba(0,0,0,0.06);'>
                        <!-- Header -->
                        <div style='background-color:#0d6efd; padding:18px 20px;'>
                        <h2 style='margin:0; font-size:18px; color:#ffffff; font-weight:bold;'>
                            Post Review Status
                        </h2>
                        </div>

                        <!-- Content -->
                        <div style='padding:20px; color:#1f2937; font-size:14px; line-height:1.6;'>
                        <p style='margin:0 0 12px;'>Hello <strong>$user_name</strong>,</p>
                        <p style='margin:0 0 12px;'>
                            Thank you for submitting your post titled
                            <strong style='color:#111827;'>$post_title</strong>.
                        </p>
                        <p style='margin:0 0 12px;'>
                            After reviewing your submission, we noticed a few issues that need to be addressed.
                            As a result, your post has not been approved at this time.
                        </p>
                        <!-- Status Box -->
                        <div style='margin:14px 0; padding:14px; background-color:#f8fafc; border:1px solid #e5e7eb; border-radius:10px;'>
                            <p style='margin:0 0 6px; font-size:12px; color:#6b7280;'>Current Status</p>
                            <p style='margin:0; font-size:14px; font-weight:bold; color:#111827;'>$post_status</p>
                        </div>
                        <!-- Message -->
                        <div style='margin:12px 0; padding:12px 14px; background-color:#eef5ff; border-left:4px solid #0d6efd; border-radius:8px;'>
                            <p style='margin:0; font-size:13px;'>
                            $status_message
                            </p>
                        </div>
                        <p style='margin:12px 0 0;'>
                            Please update your post according to the feedback and resubmit it for review.
                            If you need any assistance, feel free to contact our support team.
                        </p>

                        <p style='margin:16px 0 0;'>
                            Best regards,<br>
                            <strong>BlogScript Admin Team</strong>
                        </p>
                        </div>
                    </div>
                    </body>
                    </html>

            ";

            $mail->send();
            header("location:author.php?email=send");
        } catch (Exception $e) {
            header("location:author.php?email=fail");

        }
    } else {
 }
}
?>
