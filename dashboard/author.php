<?php
session_start();
if (empty($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
require '../src/config.php';
require '../src/db.php';
require './ip_not_allow.php';


/* Update Post Status */
if (isset($_POST['update_post_status'])) {
    $postId = intval($_POST['post_id']);
    $newStatus = ($_POST['status'] == '1') ? 'published' : 'pending';
    $conn->query("UPDATE posts SET status='$newStatus' WHERE id='$postId'");
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

<style>
@import url('../style/root.css');
*{box-sizing:border-box}
body{ background:#f5f7fb;font-family:'Poppins',sans-serif;}

main{padding:25px}
/* Header */
.dashboard-header{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap; margin-bottom:25px;}
.dashboard-header h1{font-size:28px;font-weight:600;}
.dashboard-header p{color:var(--muted)}
.logout-btn a{background:var(--primary);color:#fff;padding:10px 18px;border-radius:10px;text-decoration:none;font-size:14px;}
.logout-btn a:hover{opacity:0.9}

/* Stats */
.count{ display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px;margin-bottom:35px;}
.count-box{background: linear-gradient(270deg, var(--primary), var(--secondary)); padding:22px; border-radius:14px;box-shadow:0px 0px 5px black inset; transition:0.3s;}
.count-box:hover{transform:translateY(-4px)}
.count-box p{color:white;font-size:14px}
.count-box strong{font-size:28px;color:black}

/* Table */
h2{margin-bottom:15px}
.table-wrapper{background:#fff;box-shadow:0 10px 25px rgba(0,0,0,0.05); overflow-x:auto;
}
.table{ width:100%; border-collapse:collapse;min-width:900px;
}
.table thead{background:var(--primary);color:#fff;
}
.table th,.table td{ padding:14px 16px;font-size:14px;}
.table tbody tr{border-bottom:1px solid #e5e7eb}
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
  .dashboard-header{flex-direction:column;align-items:flex-start;gap:10px}
  .dashboard-header h1{font-size:22px}
}
</style>
</head>

<body>

<?php include '../components/header.php'; ?>

<main>

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
    <p>Total Verified Users</p>
    <strong><?php echo $verifiedCount; ?></strong>
  </div>
  <div class="count-box">
    <p>Total Unverified Users</p>
    <strong><?php echo $unverifiedCount; ?></strong>
  </div>
  <div class="count-box">
    <p>Published Posts</p>
    <strong><?php echo $publishedCount; ?></strong>
  </div>
  <div class="count-box">
    <p>Pending Posts</p>
    <strong><?php echo $pendingCount; ?></strong>
  </div>
</div>

<!-- POST Pending TABLE -->
<h2>Posts Pending Approval</h2>

<div class="table-wrapper">
<table class="table">
<thead>
<tr>
  <th>ID</th>
  <th>Title</th>
  <th>Content</th>
  <th>Date</th>
  <th>User</th>
  <th>Status</th>
  <th>Action</th>
</tr>
</thead>
<tbody>

<?php
$query = "SELECT * FROM posts WHERE status='pending'";
$result = mysqli_query($conn,$query);

if($result && mysqli_num_rows($result)>0){
  while($row=mysqli_fetch_assoc($result)){
    echo "
    <tr>
      <td><a href='{$local}/view?id={$row['id']}'>{$row['id']}</a></td>
      <td>".htmlspecialchars($row['title'])."</td>
      <td>".htmlspecialchars(substr($row['content'],0,50))."...</td>
      <td>{$row['created_at']}</td>
      <td>{$row['user_id']}</td>
      <td><span class='status pending'>pending</span></td>
      <td>
        <form method='POST'>
          <input type='hidden' name='post_id' value='{$row['id']}'>
          <select name='status'>
            <option value='1'>Publish</option>
            <option value='0' selected>Pending</option>
          </select>
          <button name='update_post_status'>Update</button>
        </form>
      </td>
    </tr>";
  }
}else{
  echo "<tr><td colspan='7' align='center' style='color:red; font-weight:bold'>No pending posts found.</td></tr>";
}
?>

</tbody>
</table>
</div>
</main>

<?php include '../components/footer.php'; ?>

</body>
</html>
