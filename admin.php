<?php
include 'src/db.php';
include 'src/config.php';
session_start();

if (!isset($_SESSION['email']))
    {
    echo "<script>alert('Plesase Login ');</script>";
    header("Location:register");
    exit();
    }

$user_email = $_SESSION['email'] ?? '';
$result = $conn->query("SELECT * FROM users WHERE EMAIL='$user_email'");
$row = $result->fetch_assoc();
$Name = $row['USER_NAME'] ?? 'Admin';
$Blogger_id = $row['ID'] ?? 0;
$total_posts = $conn->query("SELECT COUNT(*) AS total FROM posts WHERE user_id='$Blogger_id'")->fetch_assoc()['total'];
$pending_posts = $conn->query("SELECT COUNT(*) AS pending FROM posts WHERE user_id='$Blogger_id' AND status='pending'")->fetch_assoc()['pending'];
$published_posts = $conn->query("SELECT COUNT(*) AS published FROM posts WHERE user_id='$Blogger_id' AND status='published'")->fetch_assoc()['published'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin - Dashboard</title>
<?php include 'components/head.php'; ?>
<style>
@import url('assets/css/root.css');
/* =======================================
   General Layout
======================================= */


.Rightside {
    max-width: 90vw;
    margin: 0 auto;
    min-height: 100vh;
    padding: 20px;
}
/* =========================
   Header & User Section
========================= */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    margin-bottom: 30px;
    padding: 15px 20px;
    background-color: #ffffff;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    transition: box-shadow 0.3s ease;
}

.header:hover {
    box-shadow: 0 6px 25px rgba(0, 0, 0, 0.12);
}

.userlog {
    display: flex;
    align-items: center;
    font-size: 16px;
    color: #1f2937;
    gap: 12px;
}

.userlog img {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    border: 3px solid #6366f1;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.userlog img:hover {
    transform: scale(1.1);
}

.userlog strong {
    font-weight: 600;
    color: #111827;
}

.logout a {
    margin-left: 10px;
    padding: 2px 3px;
    border-radius: 8px;
    background: #8384b1;
    color: #fff;
    text-decoration: none;
    font-weight: 500;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    transition: all 0.3s ease;
}

.logout a:hover {
    background: #4f46e5;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

/* =========================
   Search Box
========================= */
.search {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.search input {
    padding: 10px 15px;
    width: 220px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 15px;
    outline: none;
    transition: all 0.3s ease;
    background-color: #f9fafb;
}

.search input:focus {
    border-color: #6366f1;
    box-shadow: 0 0 8px rgba(99, 102, 241, 0.4);
    background-color: #fff;
}

.search button {
    padding: 10px 18px;
    border: none;
    background: #6366f1;
    color: #fff;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 500;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    transition: all 0.3s ease;
}

.search button:hover {
    background: #4f46e5;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

/* Create Post */
.Post a {
    display: inline-block;
    margin: 20px 0;
    font-size: 18px;
    padding: 10px 15px;
    background: #6366f1;
    color: #fff;
    border-radius: 6px;
    transition: 0.3s;
}

.Post a:hover {
    background: #4f46e5;
}

/* Table */
.post_container {
    overflow-x: auto;
    margin-top: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    overflow: hidden;
}

table th, table td {
    padding: 12px 15px;
    text-align: left;
}

table th {
    background: #6366f1;
    color: #fff;
    font-weight: 600;
}

table tr:nth-child(odd) {
    background: #f3f4f6;
}

.ttittle {
    font-weight: bold;
    color: #1f2937;
}

.textarea {
    max-height: 60px;
    overflow: hidden;
}

/* Buttons */
button.a1, button.a2, button.a3 {
    border: none;
    padding: 6px 10px;
    margin-right: 4px;
    border-radius: 6px;
    cursor: pointer;
    color: #fff;
    transition: 0.3s;
}

.a1 { background: #6c757d; }   /* View */
.a2 { background: #ffc107; }   /* Edit */
.a3 { background: #dc3545; }   /* Delete */

.a1:hover { background: #5a6268; }
.a2:hover { background: #e0a800; }
.a3:hover { background: #c82333; }

button.a1 a, button.a2 a, button.a3 a {
    color: #fff;
    text-decoration: none;
}

/* Images */
table img {
    border-radius: 6px;
    object-fit: cover;
}

/* Responsive */
@media (max-width: 768px) {
     .header {
        flex-direction: row;
        align-items: flex-end;
        gap: 15px;
        padding: 15px;
    }


    .search input {
        width: 100%;
        margin-bottom: 10px;
    }

    table th, table td {
        font-size: 12px;
        padding: 8px 10px;
    }

    .textarea {
        max-height: 80px;
    }

    .Post a {
        width: 100%;
        text-align: center;
    }
}
</style>
</head>

<body>
<?php include 'components/header.php'; ?>
<div class="Rightside">
    <div class="header">
        <div class="userlog">
            <img src="uploads/users/<?= $row['PROFILE_IMG'] ?>" alt="admin" style="width:50px;height:50px;border-radius:50%;vertical-align:middle;margin-right:10px;border:5px solid #100e0e;">
            <strong><?= $Name ?></strong>
        </div>
        <div class="logout">
            BLOGGER-ID/<?= $Blogger_id ?>
            <a href="logout" onclick="return confirm('Are you sure to Logout?')">
            <i class="fa-solid fa-right-from-bracket fa-flip-horizontal" style="color: #080512ff;"></i> Log out
            </a>
        </div>

        <div class="search">
            <form method="GET" action="">
                <input type="text" name="search" placeholder="Enter keywords...">
                <button type="submit"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
            </form>
        </div>
    </div>

    <h2 class="Post">
        <a href="create?id=<?= $Blogger_id ?>">
            <span class="glyphicon glyphicon-plus-sign"></span> CREATE POST
        </a>
    </h2>
<div class="post_container">
    <table border="2">
        <tr style="background-color: #282836ff; font-weight: bold; color: #ffffff;">
            <td>Total Post: <?= $total_posts ?></td>
            <td>Pending: <?= $pending_posts ?></td>
            <td colspan="4">Published: <?= $published_posts ?></td>

        </tr>
        <tr>
            <th width='10%'>Post Image</th>
            <th width='20%'>Title</th>
            <th width='30%'>Content</th>
            <th width='15%'>Date</th>
            <th width='5%'>Status</th>
            <th width='20%'>Action</th>
        </tr>


  <!-- post table row ==============================================================================================--->

<?php

if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search = $conn->real_escape_string($_GET['search']);
    $query = "SELECT * FROM posts WHERE user_id='$Blogger_id' AND (title LIKE '%$search%' OR content LIKE '%$search%'  OR created_at LIKE '%$search%')";
    $result = $conn->query($query);
     if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc())

        {
           echo "<tr>
                <td ><a href='view?id={$row['id']}'><img src = 'uploads/users/{$row['post_image']}' height='60' width='60'/></a></td>
                <td class='ttittle'>{$row['title']}</td>
                <td><div class='textarea'>{$row['content']}</div></td>
                <td><i class='fa fa-calendar'></i> {$row['created_at']}</td>
                <td>{$row['status']}</td>
                 <td>
                    <button class='a1'><a href='view?id={$row['id']}><i class='fa-solid fa-eye'></i></a></button>
                    <button class='a2'><a href='update?id={$row['id']}'><i class='fa-solid fa-pen-to-square'></i></a></button>
                    <button class='a3'><a href='delete?id={$row['id']}' onclick='return confirm(\"Are you sure?\")'><i class='fa-solid fa-trash'></i></a></button>
                </td>
              </tr>";


            }

    } else
    {
      echo "<script>
            alert('No data found!');
            window.location.href = 'admin';
          </script>";
    }
}

else
{
  $result = $conn->query("SELECT * FROM posts  WHERE user_id='$Blogger_id'");
    while ($row = $result->fetch_assoc())
     {
        echo "<tr>
                <td ><a href='view?id={$row['id']}'><img src='{$local}/uploads/posts/".htmlspecialchars($row['post_image'])."' alt='Post Image' width='100'></a></td>
                <td class='ttittle'>{$row['title']}</td>
                <td><div class='textarea'>{$row['content']}</div></td>
                <td><i class='fa fa-calendar'></i> {$row['created_at']}</td>
                <td>{$row['status']}</td>
                 <td>
                    <button class='a1'><a href='view?id={$row['id']}'><i class='fa-solid fa-eye'></i></a></button>
                    <button class='a2'><a href='update?id={$row['id']}'><i class='fa-solid fa-pen-to-square'></i></a></button>
                    <button class='a3'><a href='delete?id={$row['id']}' onclick='return confirm(\"Are you sure?\")'><i class='fa-solid fa-trash'></i></a></button>

                </td>
              </tr>";
    }
  }
    ?>
    </table>
    </div>
</div>
<?php include 'components/footer.php'; ?>
</body>
</html>
