<?php
session_start();
if (empty($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
require '../src/config.php';
require '../src/db.php';
require './ip_not_allow.php';
//Display Email Status
if(isset($_GET['email']) && $_GET['email'] =='send')
    {
        echo "<script>alert('Message sent successful!');window.location.href = `$local/dashboard/author.php`;</script>";
    }
    else if(isset($_GET['email']) && $_GET['email'] == 'fail'){
        echo "<script>alert('Message sent Fail!');window.location.href = `$local/dashboard/author.php`;</script>";

    }

// Delete Pending Users
if (isset($_POST['user_id'])) {
    $userId = intval($_POST['user_id']);
    $profileImage = $conn->query("SELECT PROFILE_IMG FROM users WHERE ID='$userId'")->fetch_assoc()['PROFILE_IMG'];
    $conn->query("DELETE FROM users WHERE ID='$userId'");
    if ($profileImage && file_exists("../uploads/users/" . $profileImage)) {
        unlink("../uploads/users/" . $profileImage);
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

/* Update Pending post */
if (isset($_POST['update_post_status'])) {
    $postId = intval($_POST['post_id']);
    $newStatus = ($_POST['status'] == '1') ? 'published' : 'pending';
    $conn->query("UPDATE posts SET status='$newStatus' WHERE id='$postId'");

/* Delete Pending post */
    if($_POST['status'] == '2'){
        $post_image = $conn->query("SELECT post_image FROM posts WHERE id='$postId'")->fetch_assoc()['post_image'];
        $conn->query("DELETE FROM posts WHERE id='$postId'");
        if($post_image && file_exists("../uploads/posts/".$post_image)){
            unlink("../uploads/posts/".$post_image);
        }
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

/* Counts */
$verifiedCount   = $conn->query("SELECT COUNT(*) as c FROM users WHERE VERIFICATION_STATUS='Success'")->fetch_assoc()['c'];
$unverifiedCount = $conn->query("SELECT COUNT(*) as c FROM users WHERE VERIFICATION_STATUS!='Success'")->fetch_assoc()['c'];
$pendingCount    = $conn->query("SELECT COUNT(*) as c FROM posts WHERE status='pending'")->fetch_assoc()['c'];
$publishedCount  = $conn->query("SELECT COUNT(*) as c FROM posts WHERE status='published'")->fetch_assoc()['c'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Author Dashboard</title>
<!-- jQuery Toast Plugin CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css">
<!-- jQuery Toast Plugin JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"></script>

<style>
@import url('../style/root.css');
*{box-sizing:border-box}
body{ background:#f5f7fb;font-family:'Poppins',sans-serif;}

main{padding:25px 50px}

/* nav */
nav {min-width: 100%;padding: 2.5px;background: linear-gradient(270deg, var(--primary), var(--secondary));justify-content: center;text-align: center;top: 120px;}
nav a { background-color: white; color: black; padding: 2.5px 5px; margin: 0px 2.5px; text-decoration: none; font-weight: bold; z-index: 999;}
nav a i { margin:0 5px; color: var(--primary);}

/* Header */
.dashboard-header{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap; margin-bottom:25px;}
.dashboard-header h1{font-size:28px;font-weight:600;}
.dashboard-header p{color:var(--muted)}
.logout-btn a{background:var(--primary);color:#fff;padding:10px 18px;border-radius:10px;text-decoration:none;font-size:14px;}
.logout-btn a:hover{opacity:0.9}

/* Stats */
.count{ display:grid; grid-template-columns:auto auto auto auto;gap:50px;margin-bottom:35px;}
.count-box{ padding:22px; border-radius:1px;box-shadow:0px 0px 5px black inset; border-top: 5px solid black; background-color:white; transition:0.3s;}
.count-box p{color:black;font-size:14px}
.count-box i{color:var(--primary);margin-right:8px ; font-size: 2rem;}
.count-box strong{font-size:28px;color:black}



/* Table */
h2{margin-bottom:15px}
.table-wrapper{background:#fff;box-shadow:0 10px 25px rgba(0,0,0,0.05); overflow-x:auto;}
.table{ width:100%; border-collapse:collapse;min-width:900px; border-color: black;}
.table thead{background:var(--primary);color:#fff;}
.table th,.table td{ padding:14px 16px;font-size:14px; text-align: center;}
.table tbody tr{border-bottom:1px solid #050505}
.table tbody tr:hover{background:#f1f5ff}
/* Status */
.status{ padding:5px 12px; border-radius:20px; font-size:12px; text-transform:capitalize }
.status.pending{background:#fff4e5;color:#b45309}
.status.published{background:#e6fffa;color:#047857}

/* Form */
form{display:flex;gap:8px}
select{padding:6px 10px; border-radius:8px; border:1px solid #d1d5db;}
button{background:var(--secondary); color:#fff;  border:none; padding:7px 14px; border-radius:8px;cursor:pointer;}
button:hover{opacity:0.9}

/* Responsive */
@media(max-width:768px){
   nav{ display: block; gap:10px; padding:10px; height:auto;}
   nav a { display:block; margin:10px 0;}
  .count{ display:grid; grid-template-columns:auto auto;gap:20px;margin-bottom:35px;}
   main{padding:25px 5px}
  .dashboard-header{flex-direction:column;align-items:flex-start;gap:10px}
  .dashboard-header h1{font-size:22px}
}
</style>
</head>

<body>
<!-- Main  Header  -->
<?php include '../components/header.php'; ?>
<main>
<!-- Navigation  -->
 <nav>
  <a href="#" id="active_users"><i class='fa-solid fa-user-check'></i>Active Users</a>
  <a href="#" id="pending_users"><i class='fa-solid fa-user-clock'></i>Pending Users</a>
  <a href="#" id="published_posts"><i class='fa-solid fa-file-alt'></i>Published Posts</a>
  <a href="#" id="pending_posts"><i class='fa-solid fa-clock'></i>Pending Posts</a>
 </nav>


<!-- HEADER -->
<div class="dashboard-header">
  <div>
    <h1>Author Dashboard</h1>
    <p>Welcome back, <?php echo htmlspecialchars($_SESSION['admin_username']); ?> 👋</p>
  </div>
  <div class="logout-btn">
    <a href="logout.php">Logout</a>
  </div>
</div>

<!-- POST and USER STATS -->
<div class="count">
  <div class="count-box">
    <p><i class='fa-solid fa-user-check'></i> Total Active Users</p>
    <strong><?php echo $verifiedCount; ?></strong>
  </div>
  <div class="count-box">
    <p><i class='fa-solid fa-user-clock'></i> Total Pending Users</p>
    <strong><?php echo $unverifiedCount; ?></strong>
  </div>
  <div class="count-box">
    <p><i class='fa-solid fa-file-alt'></i>Total Published Posts</p>
    <strong><?php echo $publishedCount; ?></strong>
  </div>
  <div class="count-box">
    <p><i class='fa-solid fa-clock'></i>Total Pending Posts</p>
    <strong><?php echo $pendingCount; ?></strong>
  </div>
</div>

<h2 id="load_header"></h2>
<div class="table-wrapper" id="load_data">
<!-- AJAX content will be loaded here -->
</div>
</main>

    <!-- Add jQuery CDN for AJAX  -->
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

    <script>
    $(document).ready(function(){
        // Load all pending posts using ajax ===============================
        function load_pending()
        {
            $.ajax({
                url: 'load_pending_posts.php',
                method: 'GET',
                success: function(data){
                    $('#load_header').text('Pending Posts');
                    $('#load_data').html(data);
                }
            });
        }
        load_pending();
        $('#pending_posts').click(function(e){
            e.preventDefault();
            $.ajax({
                url: 'load_pending_posts.php',
                method: 'GET',
                success: function(data){
                    $('#load_header').text('Pending Posts');
                    $('#load_data').html(data);
                }
            });
        });
            // Load all published posts using ajax ===============================
        $('#published_posts').click(function(e){
            e.preventDefault();
            $.ajax({
                url: 'load_published_posts.php',
                method: 'GET',
                success: function(data){
                    $('#load_header').text('Published Posts');
                    $('#load_data').html(data);
                }
            });
        });

        // Load all pending users using ajax ===============================
        $('#pending_users').click(function(e){
            e.preventDefault();
            $.ajax({
                url: 'load_pending_users.php',
                method: 'GET',
                success: function(data){
                    $('#load_header').text('Pending Users');
                    $('#load_data').html(data);
                }
            });
        });
            // Load all active users using ajax ===============================
        $('#active_users').click(function(e){
            e.preventDefault();
            $.ajax({
                url: 'load_active_users.php',
                method: 'GET',
                success: function(data){
                    $('#load_header').text('Active Users');
                    $('#load_data').html(data);
                }
            });
        });
    });
    </script>















    <!-- Load all pending users using ajax -->
    <!-- Load all published posts using ajax -->
    <!-- Load all pending posts  using ajax -->










<?php include '../components/footer.php'; ?>
</body>
</html>
