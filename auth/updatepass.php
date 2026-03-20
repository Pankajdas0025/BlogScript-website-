<?php
include '../src/db.php';
include '../src/config.php';
// Get the reset username from GET safely
$reset_token = isset($_GET['token']) ? trim($_GET['token']) : '';
$sql = "SELECT email, token_hash FROM password_resets";
$result = $conn->query($sql);
if($result->num_rows)
{
                $isValid = false;
                $userEmail = null;

                while ($row = $result->fetch_assoc()) {
                    if (password_verify($reset_token, $row['token_hash'])) {
                        $isValid = true;
                        $userEmail = $row['email'];
                        break;
                    }
                }

}

if (!isset($isValid) || !$isValid) {
    echo '<div style="
        max-width: 400px;
        height: 100px;
        margin: 15% auto;
        padding:2% 25px;
        background: #fff;
        border-left: 6px solid #ef4444;  /* red for error */
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        border-radius: 10px;
        font-family: Arial, sans-serif;
        font-size: 16px;
        color: #1e293b;
        text-align: center;

    ">
        Oops! This password reset link is invalid or has expired.<br>
        <a href=\'reset-password\' style=\"
            display: inline-block;
            margin-top: 12px;
            padding: 8px 16px;
            background: #ef4444;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
        \">Request a new one</a>
    </div>';
    exit;
}

else

    {

            if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['update'])) {

                // Get POST values safely
                $newPassword = isset($_POST['new_password']) ? $_POST['new_password'] : '';

            if (strlen($newPassword) < 6) { // Minimum password length
                echo "<script>
                        alert('❌ Password must be at least 6 characters long.');
                        window.history.back(); // go back to the same form
                    </script>";
                exit();
            }

                // Hash the password securely
                $hash = password_hash($newPassword, PASSWORD_BCRYPT);
                // Use prepared statements to prevent SQL injection
                $stmt = $conn->prepare("UPDATE users SET password=? WHERE email=?");
                $stmt->bind_param("ss", $hash, $userEmail);

                if ($stmt->execute()) {
                    echo "<script>alert('✅ Password updated successfully!'); window.location.href='register#';</script>";
                                $delete = "DELETE FROM password_resets WHERE email='$userEmail'";
                                $conn->query($delete);

                } else {
                    echo "<script>alert('❌ Failed to update password. Please try again later.');</script>";
                }
                $stmt->close();
                $conn->close();
            }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Password</title>
    <!-- CSs link -->
    <link rel="stylesheet" href="assets/css/authentication.css" type="text/css">
    <?php include '../components/head.php'; ?>
</head>
<body>
    <div class="box">
        <h2 class="sh">Update Password🔐</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Enter Username" value="<?=  $userEmail ?>" required readonly>
            <input type="password" name="new_password" placeholder="Enter New Password" required>
            <input type="submit" name="update"  value="Update Password" style="background-color: rgb(156, 231, 156);">
        </form>
   <div class='msg'></div>
    </div>
</body>
</html>
