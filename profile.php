 <?php
include 'src/db.php';
include 'src/config.php';
$user_id = $_GET['id'];
$result = $conn->query("SELECT * FROM posts JOIN users ON posts.user_id = users.id WHERE posts.user_id=$user_id");
$post = $result->fetch_assoc();
$total_posts = $conn->query("SELECT COUNT(*) AS total FROM posts WHERE user_id='$user_id'")->fetch_assoc()['total'];
$pending_posts = $conn->query("SELECT COUNT(*) AS pending FROM posts WHERE user_id='$user_id' AND status='pending'")->fetch_assoc()['pending'];
$published_posts = $conn->query("SELECT COUNT(*) AS published FROM posts WHERE user_id='$user_id' AND status='published'")->fetch_assoc()['published'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($post['USER_NAME']) ?></title>
<!-- Favicon -->
<link rel="apple-touch-icon" sizes="180x180" href="favicon_io/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="favicon_io/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="favicon_io/favicon-16x16.png">

<style>
 @import url('style/root.css');
.profile-container{max-width:500px;min-height:75vh;margin:20px auto;padding:20px;background:linear-gradient(180deg,#6366f1,#f43f5e);box-shadow:0 2px 8px rgba(0,0,0,.1)}
.profile-container img{border-radius:50%;margin-bottom:20px;border:2px solid #fff}
.profile-header{text-align:center;margin-bottom:20px}
.profile-header h1{margin:0;font-size:2rem}
.profile-header p{margin:5px 0;color:#ffffffff}
.profile-stats{display:flex;justify-content:space-around;margin-top:20px}
.profile-stats div{text-align:center;background-color:#ffffffff;padding:10px;font-weight:700}
@media (max-width:768px)
{
.profile-header h1{font-size:1.5rem}
.profile-stats{flex-direction:column;align-items:center}
.profile-stats div{margin-bottom:10px;width:80%}
}
</style>
</head>
<body>
  <?php include 'components/header.php'; ?>
  <div class="profile-container">
    <img src="./Images/Admin.png" alt="Avatar" style="width:150px;height:150px;border-radius:50%;display:block;margin:0 auto 20px auto;">
    <div class="profile-header">
      <h1><?= htmlspecialchars($post['USER_NAME']) ?></h1>
      <p><?= htmlspecialchars($post['EMAIL']) ?></p>
    </div>
    <div class="profile-stats">
      <div>Total Posts: <?= $total_posts ?></div>
      <div>Pending Posts: <?= $pending_posts ?></div>
      <div>Published Posts: <?= $published_posts ?></div>
    </div>
  </div>
  <?php include 'components/footer.php'; ?>
</body>
</html>