
<?php
include 'src/db.php';
session_start();

if (!isset($_SESSION['email']) || !isset($_SESSION['id'])) {
    header("Location: register");
    exit();
}
$user_id = $_SESSION['id'];
// Fetch user
$stmt = $conn->prepare("SELECT USER_NAME, EMAIL, PROFILE_IMG, PASSWORD FROM users WHERE id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$msg = "";

// ================= PROFILE UPDATE =================
if (isset($_POST['update_profile'])) {

    $name  = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $old_image = $user['PROFILE_IMG'];

    // Image Upload
    if (!empty($_FILES['profile_img']['name'])) {
        $file = $_FILES['profile_img'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        // allow only images
        $allowed = ['jpg','jpeg','png','webp'];
        if (in_array($ext, $allowed)) {

            $new_image = uniqid() . '.' . $ext;
            move_uploaded_file($file['tmp_name'], "uploads/users/" . $new_image);

        } else {
            $msg = "<div class='error'>Only image files allowed</div>";
        }
    }

    $stmt = $conn->prepare("UPDATE users SET USER_NAME=?, EMAIL=?, PROFILE_IMG=? WHERE id=?");
    $stmt->bind_param("sssi", $name, $email, $new_image, $user_id);

    if ($stmt->execute()) {
        $_SESSION['email'] = $email;
      if (isset($old_image) && $old_image != "default.png") {
        unlink("../uploads/posts/$old_image");

    }
        $msg = "<div class='success'>Profile Updated Successfully</div>";
        header("Location:profile");
    }
}


// ================= PASSWORD CHANGE =================
if (isset($_POST['change_password'])) {

    $current = $_POST['current_password'];
    $new     = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    if (!password_verify($current, $user['PASSWORD'])) {
        $msg = "<div class='error'>Current password is incorrect</div>";
    }
    elseif ($new !== $confirm) {
        $msg = "<div class='error'>Passwords do not match</div>";
    }
    elseif (strlen($new) < 6) {
        $msg = "<div class='error'>Minimum 6 characters required</div>";
    }
    else {
        $hashed = password_hash($new, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE users SET PASSWORD=? WHERE id=?");
        $stmt->bind_param("si", $hashed, $user_id);

        if ($stmt->execute()) {
            $msg = "<div class='success'>Password Updated Successfully</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Account Settings</title>
<?php include 'components/head.php'; ?>

<style>
@import url('assets/css/root.css');
body {
    margin:0;
    font-family: Arial;
    background:#f4f6f9;
}

.container {
    max-width:900px;
    margin:40px auto;
}

.card {
    background:#fff;
    padding:25px;
    border-radius:12px;
    margin-bottom:20px;
    box-shadow:0 4px 10px rgba(0,0,0,0.08);
}

.profile {
    display:flex;
    align-items:center;
    gap:20px;
}

.profile img {
    width:90px;
    height:90px;
    border-radius:50%;
    object-fit:cover;
    border:3px solid #007bff;
}

input {
    width:100%;
    padding:10px;
    margin-top:5px;
    border-radius:6px;
    border:1px solid #ccc;
}

.form-group {
    margin-bottom:15px;
}

button {
    background:#007bff;
    color:#fff;
    padding:10px 18px;
    border:none;
    border-radius:6px;
    cursor:pointer;
}

button:hover {
    background:#0056b3;
}

.success {
    background:#d4edda;
    padding:10px;
    margin-bottom:10px;
    border-radius:5px;
}

.error {
    background:#f8d7da;
    padding:10px;
    margin-bottom:10px;
    border-radius:5px;
}
</style>

</head>
<body>
<?php include 'components/header.php'; ?>
<div class="container">

<?php echo $msg; ?>

<!-- PROFILE -->
<div class="card">

    <div class="profile">
        <img src="uploads/users/<?php echo $user['PROFILE_IMG'] ?>">
    </div>

    <h2>Profile Settings</h2>

    <form method="POST" enctype="multipart/form-data">

        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" value="<?php echo $user['USER_NAME']; ?>">
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?php echo $user['EMAIL']; ?>">
        </div>

        <div class="form-group">
            <label>Profile Image</label>
            <input type="file" name="profile_img">
        </div>

        <button name="update_profile">Update Profile</button>
    </form>

</div>

<!-- PASSWORD -->
<div class="card">

    <h2>Change Password</h2>

    <form method="POST">

        <div class="form-group">
            <label>Current Password</label>
            <input type="password" name="current_password">
        </div>

        <div class="form-group">
            <label>New Password</label>
            <input type="password" name="new_password">
        </div>

        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password">
        </div>

        <button name="change_password">Change Password</button>
        <a style="background-color:var(--secondary); padding:10px 15px; color:#fff; padding:8px 18px; border:none;border-radius:6px;cursor:pointer; " href="reset-password">Reset</a>
    </form>

</div>

</div>
<?php include 'components/footer.php'; ?>
</body>
</html>