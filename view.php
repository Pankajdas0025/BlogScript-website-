 <?php
include 'src/db.php';
include 'src/config.php';
$id = $_GET['id'];
$result = $conn->query("SELECT * FROM posts WHERE id=$id");
$post = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($post['title']) ?></title>

  <!--favicon -->
  <link rel="apple-touch-icon" sizes="180x180" href="favicon_io/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="favicon_io/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="favicon_io/favicon-16x16.png">
  <!-- Fonts & Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <style>
    @import url('style/root.css');
    .Textarea {background: linear-gradient(180deg,#6366f1,#f43f5e);  width: 100%; max-width: 1000px;box-shadow: 0 4px 15px rgba(0,0,0,0.1); padding: 25px;  margin: 50px auto; animation: fadeIn 0.5s ease-in-out; }
   @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }

   .Textarea h2,.content p{margin-bottom:15px}
   .Textarea h2{font-size:22px;display:flex;justify-content:space-between;align-items:center;word-wrap:break-word}
   .Textarea h2 button{background:#f0f0f0;border:none;padding:8px 12px;border-radius:6px;cursor:pointer;transition:.3s}
   .Textarea h2 button:hover{background:#007bff;color:#fff}
   .content{font-size:16px;line-height:1.7;color:#fff;border-top:1px solid #eee;border-bottom:1px solid #eee;padding:20px 0;word-wrap:break-word}
   .content ol,.content ul{margin:10px 20px}
   #footer{display:flex;justify-content:space-between;align-items:center;margin-top:20px;font-size:14px;color:#555;flex-wrap:wrap;gap:10px}
   #footer button,#footer span{display:flex;align-items:center;gap:6px;border:none;background:0 0;cursor:default;font-size:14px}
   #footer button{cursor:pointer;background:#007bff;color:#fff;padding:6px 12px;border-radius:6px;transition:.3s}
   #footer button:hover{background:#0056b3}

    /* Responsive */
    @media (max-width: 700px)
    {
.Textarea{height:auto;padding:15px}
.Textarea h2{font-size:18px;flex-direction:column;align-items:flex-start;gap:10px}
.content{font-size:14px}
#footer{font-size:13px;flex-direction:column;align-items:flex-start}

    }
  </style>
</head>
<body>
  <?php include 'components/header.php'; ?>
  <div class="Textarea">
    <h2>
      <?= htmlspecialchars($post['title']) ?>
      <button onclick="copyLink()"><i class="fa fa-share-alt"></i></button>
    </h2>

    <div class="content">
      <?= $post['content'] ?>

      <!-- HTML formatting preserved -->
    </div>

    <div id="footer">
      <span><i class="fa fa-user"></i> <?= "BLOGGER2025/" . $post['user_id'] ?></span>
      <span><i class="fa fa-calendar"></i> <?= $post['created_at'] ?></span>
    </div>
  </div>

  <script>
    function copyLink() {
      const link = window.location.href;
      navigator.clipboard.writeText(link).then(() => {
        window.alert("Link copied to clipboard!");
      }).catch(() => {
        window.alert("Failed to copy link.");
      });
    }
  </script>
  <?php include 'components/footer.php'; ?>
</body>
</html>
