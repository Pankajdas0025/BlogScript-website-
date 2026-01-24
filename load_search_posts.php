<!--Load all search post data with pagination -->
<?php
include 'src/db.php';
include 'src/config.php';

// Pagination settings
$posts_per_page = 3;
$visible_pages  = 3;
if (isset($_POST['search']) && !empty(trim($_POST['search']))) {
    $search_term = $conn->real_escape_string(trim($_POST['search']));
} else {
    $search_term = '';
}

$page = 1;
$offset = ($page - 1) * $posts_per_page;

// Count total posts
$count_query  = "SELECT COUNT(*) AS total FROM posts WHERE status='published' AND (title LIKE '%$search_term%' OR content LIKE '%$search_term%')";
$count_result = $conn->query($count_query);
$total_posts  = ($count_result && $count_result->num_rows > 0) ? (int)$count_result->fetch_assoc()['total'] : 0;

$total_pages = ($total_posts > 0) ? (int)ceil($total_posts / $posts_per_page) : 1;
if ($page > $total_pages) $page = $total_pages;

// Fetch posts
$query  = "SELECT * FROM posts WHERE status='published' AND (title LIKE '%$search_term%' OR content LIKE '%$search_term%') ORDER BY created_at DESC LIMIT $posts_per_page OFFSET $offset";
$result = $conn->query($query);

$posts = "";
if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $title   = htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8');
    $content = $row['content'];
    $created = htmlspecialchars($row['created_at'], ENT_QUOTES, 'UTF-8');
    $id      = (int)$row['id'];

    $posts .= "
      <div class='blog-card'>
        <div class='post-img' width='100%' height='150px'>
          <img src='uploads/posts/default.png' alt='{$title}' height='150px' width='100%'>
        </div>
        <h3>{$title}</h3>
        <div id='content'>{$content}</div>
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

// Pagination controls
        $start_page = (int)(floor(($page - 1) / $visible_pages) * $visible_pages) + 1;
        $end_page   = $start_page + $visible_pages - 1;
        if ($end_page > $total_pages) $end_page = $total_pages;

$posts .= "<div id='pagination'>";

// Prev button
$prev_page = $page - 1;
if ($page <= 1) {
  $posts .= "<a href='#' class='disabled'>Prev</a>";
} else {
  $posts .= "<a href='#' data-page='{$prev_page}'>Prev</a>";
}

// Page numbers (only 5)
for ($i = $start_page; $i <= $end_page; $i++) {
  if ($i == $page) {
    $posts .= "<a href='#' data-page='{$i}' class='active'>{$i}</a>";
  } else {
    $posts .= "<a href='#' data-page='{$i}'>{$i}</a>";
  }
}

// Next button
$next_page = $page + 1;
if ($page >= $total_pages) {
  $posts .= "<a href='#' class='disabled'>Next</a>";
} else {
  $posts .= "<a href='#' data-page='{$next_page}'>Next</a>";
}

$posts .= "</div>";

// Close the database connection
$conn->close();
echo $posts;
?>
