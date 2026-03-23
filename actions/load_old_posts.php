<!--Load all post data with pagination -->
<?php
include '../src/db.php';
include '../src/config.php';
// Fetch posts
$query  = "SELECT * FROM posts WHERE status='published' ORDER BY created_at ASC LIMIT 5";
$result = $conn->query($query);
$posts = "";
if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $title   = htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8');
    $post_image = $row['post_image'];
    $content = $row['content'];
     $created = date('d M Y', strtotime($row['created_at']));
    $id      = (int)$row['id'];

    $posts .= "
      <div class='blog-card'>
        <div class='post-img' width='100%' height='150px'>
           <a href='view?id={$id}'><img src='uploads/posts/{$post_image}' alt='{$title}' height='150px' width='100%'></a>
        </div>
        <h3>{$title}</h3>
        <div class='card-footer'>
          <a href='view?id={$id}'>Read more <i class='fa fa-angle-right'></i></a>
          <span><i class='fa fa-calendar'></i>{$created}</span>
        </div>
      </div>
    ";
  }
} else {
  $posts .= "<p>No posts found.</p>";
}
echo $posts;
?>
