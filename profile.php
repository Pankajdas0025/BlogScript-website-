<?php
include 'src/db.php';
include 'src/config.php';
session_start();
$user_id = $_GET['id'];
$result = $conn->query("SELECT * FROM posts
JOIN users ON posts.user_id = users.id
WHERE posts.user_id=$user_id");

$post = $result->fetch_assoc();

if (!$post) {
  header("Location: index.php");
  exit();
}
$user = $post;
$total_posts = $conn->query("SELECT COUNT(*) AS total FROM posts WHERE user_id='$user_id'")->fetch_assoc()['total'];
$pending_posts = $conn->query("SELECT COUNT(*) AS pending FROM posts WHERE user_id='$user_id' AND status='pending'")->fetch_assoc()['pending'];
$published_posts = $conn->query("SELECT COUNT(*) AS published FROM posts WHERE user_id='$user_id' AND status='published'")->fetch_assoc()['published'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($user['USER_NAME']) ?></title>
<?php include 'components/head.php';?>



<style>
@import url("assets/css/root.css");
/* Container */
.profile-container {
  max-width: 1000px;
  margin: 20px auto;
  padding: 15px;
}

/* Banner */
.profile-banner {
  height: 50px;
  background: linear-gradient(135deg, var(--primary), var(--secondary));
  border-radius: 12px;
  position: relative;
}

/* Avatar */
.profile-avatar {
  position: absolute;
  bottom: -60px;
  left: 50%;
  transform: translateX(-50%);
}

.profile-avatar img {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  border: 5px solid #fff;
  object-fit: cover;
}

/* Info */
.profile-info {
  text-align: center;
  margin-top: 70px;
}

.profile-info h1 {
  margin: 10px 0;
}

/* Stats */
.profile-stats {
  display: flex;
  gap: 15px;
  margin-top: 20px;
}

.stat-box {
  flex: 1;
  background: #fff;
  padding: 20px;
  text-align: center;
  border-radius: 10px;
  box-shadow: 0 3px 10px rgba(0,0,0,0.08);
}

.stat-box h2 {
  margin: 0;
  color:var(--primary);
}

/* Posts */
.posts {
  margin-top: 40px;
}

.post-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 15px;
}

.post-card {
  background: #fff;
  border-radius: 1px;
  padding:15px;
  overflow: hidden;
  text-decoration: none;
  color: #000;
  box-shadow: 0 3px 10px rgba(0,0,0,0.08);
  transition: 0.3s;
}

.post-card:hover {
  transform: translateY(-5px);
}

.post-card img {
  width: 100%;
  height: 160px;
  object-fit: cover;
}

.post-card .content {
  padding: 10px;
}

.post-card h4 {
  margin: 0 0 5px;
  font-size: 1rem;
}

.post-card small {
  color: #777;
}

/* Mobile */
@media(max-width:768px) {
  .profile-stats {
    flex-direction: column;
  }
}
</style>
</head>

<body>
<?php include 'components/header.php';?>
<div class="profile-container">

  <!-- Banner -->
  <div class="profile-banner">
    <div class="profile-avatar">
      <img src="uploads/users/<?= $user['PROFILE_IMG'] ?>" alt="Avatar">
    </div>
  </div>

  <!-- Info -->
  <div class="profile-info">
    <h1><?= htmlspecialchars($user['USER_NAME']) ?></h1>
  </div>

  <!-- Stats -->
  <div class="profile-stats">
    <div class="stat-box">
      <h2><?= $total_posts ?></h2>
      <p>Total Posts</p>
    </div>
    <div class="stat-box">
      <h2><?= $pending_posts ?></h2>
      <p>Pending</p>
    </div>
    <div class="stat-box">
      <h2><?= $published_posts ?></h2>
      <p>Published</p>
    </div>
  </div>

  <!-- Posts -->
  <div class="posts">
    <h2>All Posts</h2>

    <div class="post-grid">
      <?php
      // first post already used, show it manually
      echo "<a href='$local/view?id=".$user['id']."' class='post-card'>";
      echo "<img src='uploads/posts/".$user['post_image']."'>";
      echo "<div class='content'>";
      echo "<h4>".htmlspecialchars($user['title'])."</h4>";
      echo "<small>".$user['created_at']."</small>";
      echo "</div></a>";

      while ($row = $result->fetch_assoc()) {
        echo "<a href='$local/view?id=".$row['id']."' class='post-card'>";
        echo "<img src='uploads/posts/".$row['post_image']."'>";
        echo "<div class='content'>";
        echo "<h4>".htmlspecialchars($row['title'])."</h4>";
        echo "<small>".$row['created_at']."</small>";
        echo "</div></a>";
      }
      ?>
    </div>
  </div>
</div>
<?php include 'components/footer.php';?>
</body>
</html>