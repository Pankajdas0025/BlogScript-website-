<?php
$a = $_GET['id'];
include 'src/db.php';
include 'src/config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create Post</title>
  <!--favicon -->
  <link rel="apple-touch-icon" sizes="180x180" href="favicon_io/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="favicon_io/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="favicon_io/favicon-16x16.png">


  <!-- FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <!-- Custom CSS -->
  <style>
@import url('style/root.css');
 .Textarea { background: linear-gradient(180deg,#6366f1,#f43f5e); width: 100%;max-width: 1000px;margin: 50px auto;box-shadow: 0 4px 15px rgba(0,0,0,0.1);padding:50px 25px;animation: fadeIn 0.5s ease-in-out;}
   @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .editor,h2 input{padding:12px;outline:0;transition:.3s}
    #footer a,#footer button{color:#fff;font-size:16px;display:flex;gap:6px}
    h2 input{width:100%;border:1px solid #ccc;font-size:16px}
    h2 input:focus{border-color:#007bff;box-shadow:0 0 4px rgba(0,123,255,.3)}
    .toolbar{display:flex;gap:10px;margin:15px 0;flex-wrap:wrap}
    .toolbar button{background:#f0f0f0;border:none;padding:8px 12px;border-radius:6px;cursor:pointer;transition:.3s;font-size:14px}
    .toolbar button:hover{background:#007bff;color:#fff}
    .editor{min-height:200px;border:1px solid #ccc;font-size:15px;line-height:1.6;background:#fafafa}
    .editor:focus{border-color:#007bff;background:#fff}
    #footer{display:flex;justify-content:space-between;align-items:center;margin-top:25px}
    #footer a{text-decoration:none;align-items:center}
    #footer button{background:#007bff;border:none;padding:10px 18px;border-radius:8px;cursor:pointer;align-items:center;transition:.3s}
    #footer button:hover{background:#0056b3}

    /* Responsive */
    @media (max-width: 600px) {
   #footer button,.editor,h2 input{font-size:14px}
   .Textarea{padding:15px;height:auto}
   .toolbar button{padding:6px 10px;font-size:12px}
   .editor{min-height:150px}
   #footer button{padding:8px 14px}
    }
  </style>
</head>

<body>
  <?php include 'components/header.php';?>
  <div class="Textarea">
    <form method="POST" onsubmit="saveContent()">
      <h2>
        <input type="text" name="title" maxlength="50" id="secureInput" placeholder="Enter your blog Title Here ...." required>
      </h2>

      <!-- Toolbar -->
      <div class="toolbar">
        <button type="button" onclick="format('bold')"><b>B</b></button>
        <button type="button" onclick="format('italic')"><i>I</i></button>
        <button type="button" onclick="format('underline')"><u>U</u></button>
        <button type="button" onclick="format('insertUnorderedList')">• List</button>
        <button type="button" onclick="format('insertOrderedList')">1. List</button>
      </div>

      <!-- Editor -->
      <div class="editor" id="editor" contenteditable="true">
        Write your blog post here...
      </div>

      <!-- Hidden input -->
      <input type="hidden" name="content" id="hiddenContent">

      <div id="footer">
        <a href="admin"><i class="fa-solid fa-backward-step"></i> Back </a>
        <button type="submit"><i class="fa-solid fa-upload"></i> Publish</button>
      </div>
    </form>
  </div>

  <!-- PHP -->
 <?php
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $title = $_POST['title'];
      $content = $_POST['content'];

      $stmt = $conn->prepare("INSERT INTO posts (title, content, user_id) VALUES (?, ?, ?)");
      $stmt->bind_param("ssi", $title, $content, $a);
      $stmt->execute();
      echo "<script>alert('Post created successfully!'); window.location.href = 'admin';</script>";
      exit();
  }
  ?>

  <script>
    const input = document.getElementById("secureInput");
    ["copy", "paste", "cut"].forEach(ev =>
      input.addEventListener(ev, e => e.preventDefault())
    );

    function format(command) {
      document.execCommand(command, false, null);
    }

    function saveContent() {
      document.getElementById("hiddenContent").value = document.getElementById("editor").innerHTML;
    }
  </script>
  <?php include 'components/footer.php';?>
</body>
</html>
