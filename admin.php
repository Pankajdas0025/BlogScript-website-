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

echo "<script> setTimeout(function() {alert('session expired!');  window.location.href = 'logout';},1000*60*60*12); </script>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin - Dashboard</title>

<!-- favicon ============================================================================================-->
<link rel="apple-touch-icon" sizes="180x180" href="favicon_io/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="favicon_io/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="favicon_io/favicon-16x16.png">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">


<style>
    @import url('style/root.css');

.Post a,.userlog a{text-decoration:none;transition:.3s}
.Rightside{max-width:100%;min-height:100vh;height:auto;background:linear-gradient(45deg,#6366f1,#f43f5e); padding: 15px;}
.header{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;margin-bottom:20px}
.userlog{color:#ffffffff;font-size:15px}
.userlog img{width:50px;border-radius:50%;vertical-align:middle;margin-right:10px;border:2px dotted #fff}
.userlog a{margin-left:10px;color:#fff;border:none;outline:0}
.userlog a:hover{color:#0056b3}
.search input{padding:6px 10px;width:200px;margin-left:5px;border:1px solid #ccc}
.search button{padding:6px 12px;border:none;background:#6366f1;color:#fff;border-radius:4px;cursor:pointer}
.search button:hover{background:#0056b3}
.Post a:hover,table th{background:#6366f1}
.Post a{display:inline-block;margin:15px 0;font-size:20px;padding:8px 12px;background:#ffffffff;color:#110a0aff}
table{width:100%;border-collapse:collapse;margin-top:10px;background:#fff;box-shadow:0 4px 15px rgba(0,0,0,.1)}
table td,table th{padding:12px 15px;text-align:left;height:auto}
table th{color:#fff}
table tr:nth-child(odd){background-color: #bfb8b8ff;}
.textarea{max-height:50px;overflow:hidden; margin: 0; }
button.a1,button.a2,button.a3{border:none;padding:6px 10px;margin-right:4px;border-radius:6px;cursor:pointer;color:#fff}
.a1 { background: #8f9fa2ff; } /* view */
.a2 { background: #ffc107; } /* edit */
.a3 { background: #dc3545; } /* delete */

.a1:hover { background: #7ded61ff; }
.a2:hover { background: #e0a800; }
.a3:hover { background: #e760f3ff; }

@media (max-width: 768px)
{
.Rightside{margin: 0;}
.header{flex-direction: column;align-items: flex-start;gap: 10px;}
.search input { width: 100%; margin-bottom: 20px; }
table{min-width:100%; overflow-x:auto;}
.textarea{max-height:70px;overflow:hidden;margin: 0;width: 100px;}
table th, table td { font-size: 10px; padding: 8px 2px; }
button.a1, button.a2, button.a3 {  padding: 6px 10px; margin: 2.5px; border-radius: 0px; cursor: pointer;color: #fff;}
}

</style>
</head>

<body>
<?php include 'components/header.php'; ?>
<div class="Rightside">
    <div class="header">
        <div class="userlog">
            <img src="uploads/users/<?= $row['PROFILE_IMG'] ?>" alt="admin" style="width:50px;height:50px;border-radius:50%;vertical-align:middle;margin-right:10px;border:5px solid #100e0e;">
            <strong><?= $Name ?></strong> BLOGGER-ID/<?= $Blogger_id ?>

            <a href="logout" onclick="return confirm('Are you sure to Logout?')" style="background-color: #6366f1; padding: 5px 10px; border-radius: 5px; color: #ffffff; margin-left: 15px; text-decoration: none;">
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

    <table>
        <tr style="background-color: #282836ff; font-weight: bold; color: #ffffff;">
            <td width="30%">Total Post: <?= $total_posts ?></td>
            <td width="30%">Pending: <?= $pending_posts ?></td>
            <td width="0%"></td>
            <td width="60%">Published: <?= $published_posts ?></td>

        </tr>
        <tr>
            <th width='20%'>Title</th>
            <th width='30%'>Content</th>
            <th width='20%'>Date</th>
            <th width='30%'>Action</th>
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


                <td class='ttittle'>{$row['title']}</td>
                <td><div class='textarea'>{$row['content']}</div></td>
                <td>{$row['created_at']}</td>
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
                <td class='ttittle'>{$row['title']}</td>
                <td><div class='textarea'>{$row['content']}</div></td>
                <td>{$row['created_at']}</td>
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
<?php include 'components/footer.php'; ?>
</body>
</html>
