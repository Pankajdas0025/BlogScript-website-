<?php

session_start();
if (!isset($_SESSION['email']))
    {
    echo "<script>alert('Please Login ');</script>";
    header("Location:#register");
    exit();
    }
include 'src/db.php';
include 'src/config.php';
$user = $_GET['id'];
$verife_user = $conn->query("SELECT * FROM users WHERE EMAIL='{$_SESSION['email']}'")->fetch_assoc();
if ($verife_user['ID'] != $user) {
  //redirect to login page
    echo "<script>alert('Unauthorised access! Please login');</script>";
    header("Location:logout");
    exit();
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create Post</title>
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- jQuery Toast Plugin CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css">
  <!-- jQuery Toast Plugin JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"></script>
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
    #imagePreview{margin-top:15px;padding:10px;}
    #imagePreview img{max-width:40%;height:auto; border: 2px solid white;}
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
    #imagePreview img{max-width:100%;height:auto;}
    #footer button{padding:8px 14px}
    }
    </style>
</head>
<body>
  <?php include 'components/header.php';?>
  <div class="Textarea">
    <form method="POST" onsubmit="saveContent()" action="" enctype="multipart/form-data">
      <!--Title Input -->
      <h2>
        <input type="text" name="title" maxlength="60" id="secureInput" placeholder="Enter your blog Title Here ...." required>
      </h2>

      <!-- Toolbar -->
      <div class="toolbar">
        <button type="button" onclick="format('bold')"><b>B</b></button>
        <button type="button" onclick="format('italic')"><i>I</i></button>
        <button type="button" onclick="format('underline')"><u>U</u></button>
        <button type="button" onclick="format('insertUnorderedList')"><i class="fa-solid fa-list-ul"></i></button>
        <button type="button" onclick="format('insertOrderedList')"><i class="fa-solid fa-list-ol"></i></button>
        <button type="button" onclick="format('createLink')"><i class="fa-solid fa-link"></i></button>
        <button type="button" onclick="format('unlink')"><i class="fa-solid fa-link-slash"></i></button>
        <button type="button" onclick="format('undo')"><i class="fa-solid fa-rotate-left"></i></button>
        <button type="button" onclick="format('redo')"><i class="fa-solid fa-rotate-right"></i></button>
        <!--font size button -->
        <button type="button" onclick="format('fontSize', prompt('Enter font size (1-7):'))"><i class="fa-solid fa-font"></i></button>
        <!-- Text color picker  -->
        <button type="button"><input type="color" onchange="format('foreColor', this.value)" title="Text Color" style="border-radius: 50%; height:30px; width:30px"></button>
      </div>

      <!-- Editor body  -->
      <div class="editor" id="editor" contenteditable="true">
        Write your blog post here...
      </div>
      <!-- Hidden input for content -->
      <input type="hidden" name="content" id="hiddenContent">
      <!-- post image upload -->
       <h2><input type="file" id="post_image" name="post_image" accept="image/*" enctype="multipart/form-data" style="margin-top:15px;"></h2>
      <script>
        // Image preview function
        document.getElementById('post_image').addEventListener('change', function(event) {
          const file = event.target.files[0];
          if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
              document.getElementById('previewImg').style.display = 'block';
              document.getElementById('previewImg').src = e.target.result;
            }
            reader.readAsDataURL(file);
          }
        });
        </script>
       <!-- View Image Preview: -->
      <div id="imagePreview" style="margin-bottom:15px;">
        <img id="previewImg" src="" alt="Image Preview" style="display: none;">
      </div>
       <!-- Footer -->
       <div id="footer">
        <a href="admin"><i class="fa-solid fa-backward-step"></i> Back </a>
        <button type="submit"><i class="fa-solid fa-upload"></i> Publish</button>
      </div>
    </form>
  </div>

  <!-- PHP -->
    <?php
      if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title']) && isset($_POST['content'])) {
          $title = $_POST['title'];
          $post_image = $_FILES['post_image'];
          $database_post_image = 'default.png'; // default image

            // Handle image upload ------------------------------------------------
          if ($post_image && $post_image['error'] === UPLOAD_ERR_OK) {
            $fileExt = strtolower(pathinfo($post_image['name'], PATHINFO_EXTENSION));
            $allowed_Ext = ['jpg', 'jpeg', 'png'];
            $fileSize = $post_image['size'];

            // File size check must be ------------------------------------
            if ($fileSize > 2097152) {
              $result = array("status" => "error", "message" => "File size must be 2mb or lower.");
            } else if (in_array($fileExt, $allowed_Ext)) {
              $database_post_image = uniqid() . "." . $fileExt;
              $image_folder = "uploads/posts/" . $database_post_image;
            } else {
              $result = array("status" => "error", "message" => "This extension file not allowed, Please choose a JPG or PNG file.");
            }
          }
          if (!isset($result) || $result['status'] != 'error') {
            $content = $_POST['content'];
            $stmt = $conn->prepare("INSERT INTO posts (title, post_image, content, user_id) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $title, $database_post_image, $content, $user);
            if ($stmt->execute()){
              // Move uploaded image to server folder ---------------------------
              if ($post_image && $post_image['error'] === UPLOAD_ERR_OK) {
                move_uploaded_file($post_image['tmp_name'], $image_folder);
              }
              $result = array("status" => "success", "message" => "Post created successfully.");
              //reset form fields and refresgh page after 2 seconds and reset form
              echo "<script> setTimeout(function(){ window.location.href = 'create?id={$user}'; }, 2000); </script>";
            } else {
              $result = array("status" => "error", "message" => "Error: " . $stmt->error);
            }
          }
      }
      if (isset($result))
        {
        if ($result['status'] == 'success') {
          echo "<script>
            \$.toast({
              heading: 'Success',
              text: '{$result['message']}',
              showHideTransition: 'slide',
              icon: 'success',
              position: 'top-right'
            });
          </script>";

        } else if ($result['status'] == 'error') {
          echo "<script>
            \$.toast({
              heading: 'Error',
              text: '".$result['message']."',
              showHideTransition: 'fade',
              icon: 'error',
              position: 'top-right'
            });
          </script>";
        }
        }

    ?>
      <script>
        // const input = document.getElementById("secureInput");
        // ["copy", "paste", "cut"].forEach(ev =>input.addEventListener(ev, e => e.preventDefault()));
        // Text formatting function---------------------------------------------------------------------
        function format(command) { document.execCommand(command, false, null);}

        // Save content to hidden input before submitting------------------------------------------------
        function saveContent() {document.getElementById("hiddenContent").value = document.getElementById("editor").innerHTML;}
      </script>
    <?php include 'components/footer.php';?>
  </body>
</html>
