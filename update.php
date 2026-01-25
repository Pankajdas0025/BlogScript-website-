<?php
ob_start();
session_start();

if (!isset($_SESSION['email'])) {
  echo "<script>alert('Plesase Login ');window.location.href='register';</script>";
  exit();
}

include 'src/db.php';
include 'src/config.php';

// Safe post id
$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($post_id <= 0) {
  echo "<script>alert('Invalid Post ID');window.location.href='admin';</script>";
  exit();
}

// verify user that it only update their own post ===========================================================
$stmt = $conn->prepare("
  SELECT users.EMAIL AS user_email
  FROM posts
  JOIN users ON posts.user_id = users.ID
  WHERE posts.id = ?
  LIMIT 1
");
$stmt->bind_param('i', $post_id);
$stmt->execute();
$verife_user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$verife_user) {
  echo "<script>alert('Post not found!');window.location.href='admin';</script>";
  exit();
}

if ($verife_user['user_email'] != $_SESSION['email']) {
  echo "<script>alert('Unauthorised access !Please login');window.location.href='logout';</script>";
  exit();
}
//===========================================================================================================

// Load post data
$stmt = $conn->prepare("SELECT * FROM posts WHERE id = ? LIMIT 1");
$stmt->bind_param('i', $post_id);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$post) {
  echo "<script>alert('Post not found!');window.location.href='admin';</script>";
  exit();
}

$old_post_image = $post['post_image'];
?>

<!DOCTYPE html>
<html lang='en'>
<head>
  <meta charset='UTF-8'>
  <meta name='viewport' content='width=device-width, initial-scale=1.0'>
  <title>Update Post</title>

  <!-- jQuery -->
  <script src='https://code.jquery.com/jquery-3.6.0.min.js'></script>
  <!-- jQuery Toast Plugin CSS -->
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css'>
  <!-- jQuery Toast Plugin JS -->
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js'></script>
  <!-- FontAwesome -->
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css' crossorigin='anonymous' referrerpolicy='no-referrer' />

  <style>
    @import url('style/root.css');
    .Textarea { background: linear-gradient(180deg,#6366f1,#f43f5e); width: 100%;max-width: 1000px;margin: 50px auto;box-shadow: 0 4px 15px rgba(0,0,0,0.1);padding:50px 25px;animation: fadeIn 0.5s ease-in-out;}
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
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

<div class='Textarea'>
  <form method='POST' onsubmit='saveContent()' action='' enctype='multipart/form-data'>

    <h2>
      <input type='text' name='title' maxlength='60' id='secureInput'
        value='<?= htmlspecialchars($post['title']) ?>'
        placeholder='Enter your blog Title Here ....' required>
    </h2>

    <div class='toolbar'>
      <button type='button' onclick="format('bold')"><b>B</b></button>
      <button type='button' onclick="format('italic')"><i>I</i></button>
      <button type='button' onclick="format('underline')"><u>U</u></button>
      <button type='button' onclick="format('insertUnorderedList')"><i class='fa-solid fa-list-ul'></i></button>
      <button type='button' onclick="format('insertOrderedList')"><i class='fa-solid fa-list-ol'></i></button>
      <button type='button' onclick="createLinkPrompt()"><i class='fa-solid fa-link'></i></button>
      <button type='button' onclick="format('unlink')"><i class='fa-solid fa-link-slash'></i></button>
      <button type='button' onclick="format('undo')"><i class='fa-solid fa-rotate-left'></i></button>
      <button type='button' onclick="format('redo')"><i class='fa-solid fa-rotate-right'></i></button>

      <button type='button' onclick="fontSizePrompt()"><i class='fa-solid fa-font'></i></button>

      <button type='button'>
        <input type='color' onchange="format('foreColor', this.value)" title='Text Color'
          style='border-radius: 50%; height:30px; width:30px'>
      </button>
    </div>

    <div class='editor' id='editor' contenteditable='true'>
      <?= htmlspecialchars_decode($post['content']) ?>
    </div>

    <input type='hidden' name='content' id='hiddenContent'>

    <h2>
      <input type='file' id='post_image' name='post_image' accept='image/*' style='margin-top:15px;'>
    </h2>

    <script>
      document.getElementById('post_image').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = function(e) {
            document.getElementById('previewImg').style.display = 'block';
            document.getElementById('previewImg').src = e.target.result;
          };
          reader.readAsDataURL(file);
        }
      });
    </script>

    <div id='imagePreview' style='margin-bottom:15px;'>
      <img
        id='previewImg'
        src='<?= 'uploads/posts/' . htmlspecialchars($old_post_image) ?>'
        alt='Image Preview'
        style='<?= !empty($old_post_image) ? 'display:block;' : 'display:none;' ?>'
      >
    </div>

    <div id='footer'>
      <a href='admin'><i class='fa-solid fa-backward-step'></i> Back </a>
      <button type='submit'><i class='fa-solid fa-upload'></i> Publish</button>
    </div>

  </form>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title']) && isset($_POST['content'])) {

  $title = trim($_POST['title']);
  $content = $_POST['content'];

  $new_post_image = isset($_FILES['post_image']) ? $_FILES['post_image'] : null;
  $database_post_image = $old_post_image;

  if ($new_post_image && $new_post_image['error'] === UPLOAD_ERR_OK) {

    $fileExt = strtolower(pathinfo($new_post_image['name'], PATHINFO_EXTENSION));
    $allowed_Ext = ['jpg', 'jpeg', 'png'];
    $fileSize = (int)$new_post_image['size'];

    if ($fileSize > 2097152) {
      $result = ['status' => 'error', 'message' => 'File size must be 2mb or lower.'];
    } else if (in_array($fileExt, $allowed_Ext)) {
      $database_post_image = uniqid() . '.' . $fileExt;
      $image_folder = 'uploads/posts/' . $database_post_image;
    } else {
      $result = ['status' => 'error', 'message' => 'This extension file not allowed, Please choose a JPG or PNG file.'];
    }
  }

  if (!isset($result) || $result['status'] != 'error') {

    $stmt = $conn->prepare('UPDATE posts SET title=?, post_image=?, content=? WHERE id=?');
    $stmt->bind_param('sssi', $title, $database_post_image, $content, $post_id);

    if ($stmt->execute()) {

      if ($new_post_image && $new_post_image['error'] === UPLOAD_ERR_OK) {
        if (!empty($old_post_image) && file_exists('uploads/posts/' . $old_post_image)) {
          if($old_post_image != 'default.jpg')
            {
              unlink('uploads/posts/' . $old_post_image);
            }

        }
        move_uploaded_file($new_post_image['tmp_name'], $image_folder);
      }

      $result = ['status' => 'success', 'message' => 'Post updated successfully.'];
      echo "<script> setTimeout(function(){ window.location.href = 'admin'; }, 2000); </script>";

    } else {
      $result = ['status' => 'error', 'message' => 'Error: ' . $stmt->error];
    }

    $stmt->close();
  }
}

if (isset($result)) {
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
        text: '" . $result['message'] . "',
        showHideTransition: 'fade',
        icon: 'error',
        position: 'top-right'
      });
    </script>";
  }
}
?>

<script>
  function format(command, value = null) {
    document.execCommand(command, false, value);
  }

  function saveContent() {
    document.getElementById('hiddenContent').value = document.getElementById('editor').innerHTML;
  }

  function createLinkPrompt() {
    const url = prompt('Enter URL:');
    if (url) format('createLink', url);
  }

  function fontSizePrompt() {
    const size = prompt('Enter font size (1-7):');
    if (size) format('fontSize', size);
  }
</script>

<?php include 'components/footer.php';?>
</body>
</html>

<?php ob_end_flush(); ?>
