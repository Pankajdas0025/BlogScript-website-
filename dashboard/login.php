<?php
session_start();
require './ip_not_allow.php';
include "../src/db.php";
include "../src/config.php";
// If already logged in, redirect to admin
if (!empty($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: author.php');
    exit;
}

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $err = 'Please enter both username and password.';
    } else {
        // Prepared statement to fetch admin
        $stmt = $conn->prepare("SELECT id, username, password_hash FROM admins WHERE username = ? LIMIT 1");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res && $res->num_rows === 1) {
            $admin = $res->fetch_assoc();
            if (password_verify($password, $admin['password_hash'])) {
                // Good login
                session_regenerate_id(true);
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];

                header('Location: author.php');
                exit;
            } else {
                $err = 'Invalid username or password.';
            }
        } else {
            $err = 'Invalid username or password.';
        }
        $stmt->close();
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin Login</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body { background:#f6f7fb; }
    .card { max-width:420px; margin:80px auto; padding:20px; border-radius:8px; }
  </style>
</head>
<body>
  <div class="card">
    <h4 class="mb-3">Admin Login</h4>
    <?php if ($err): ?>
      <div class="alert alert-danger"><?php echo htmlspecialchars($err); ?></div>
    <?php endif; ?>
    <form method="post" autocomplete="off">
      <div class="form-group">
        <label>Username</label>
        <input name="username" class="form-control" required value="<?php echo isset($username)?htmlspecialchars($username):''; ?>">
      </div>
      <div class="form-group">
        <label>Password</label>
        <input name="password" type="password" class="form-control" required>
      </div>
      <button class="btn btn-primary btn-block" type="submit">Login</button>
    </form>
  </div>
</body>
</html>
