<?php
include 'src/db.php';
include 'src/config.php';
session_start();
$_SESSION['email'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BlogScript | Read • Write • Share</title>
<meta name="theme-color" content="#ffffff">
<meta name="application-name" content="BlogScript"/>
<meta name="description" content="BlogScript is a simple blogging platform where you can read, write, and share creative blogs. Explore ideas, stories, and knowledge shared by creators."/>
<meta name="keywords" content="BlogScript, blogging platform, write blogs, share stories, creative blogs, read blogs, online writing, blog community, BlogScript creator"/>
<meta name="author" content="BlogScript"/>
<link rel="manifest" href="src/manifest.json">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:url" content="https://blogscriptapp.free.nf/home/">
<meta property="og:title" content="BlogScript - a simple blogging platform to read, write & share blogs">
<meta property="og:description"  content="BlogScript is a simple blogging platform where you can read, write, and share creative blogs. Explore ideas, stories, and knowledge shared by creators."/>
<meta property="og:image" content="https://blogscriptapp.free.nf/Images/og.png">

<!-- Twitter Card for Social Media -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="https://blogscriptapp.free.nf/home/">
<meta property="twitter:title" content="BlogScript - Read, Write & Share Blogs">
<meta property="twitter:description" content="BlogScript is a creative blogging platform where you can read stories, write blogs, and share your ideas with the world. Join our community of creators today!">
<meta property="twitter:image" content="https://blogscriptapp.free.nf/home/Images/og.png">
<!-- Instagram Card for Social Media -->
<meta property="instagram:card" content="summary_large_image">
<meta property="instagram:url" content="https://blogscriptapp.free.nf/home/">
<meta property="instagram:title" content="BlogScript - Read, Write & Share Blogs">
<meta property="instagram:description" content="BlogScript is a creative blogging platform where you can read stories, write blogs, and share your ideas with the world. Join our community of creators today!">
<meta property="instagram:image" content="https://blogscriptapp.free.nf/home/Images/og.png">
<!-- Robots -->
<meta name="robots" content="index, follow">
<!-- Canonical URL -->
<link rel="canonical" href="https://blogscriptapp.free.nf/home/">
<!-- Sitemap -->
<link rel="sitemap" type="application/xml" title="Sitemap" href="src/sitemap.xml">
 <!-- Favicon -->
<link rel="apple-touch-icon" sizes="180x180" href="favicon_io/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="favicon_io/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="favicon_io/favicon-16x16.png">
<!-- Custom CSS -->
<link rel="stylesheet" href="style/style.css">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebSite",
  "name": "Blogscript",
  "url": "https://blogscript.example.com/",
  "logo": "https://blogscript.example.com/images/logo.png",
  "description": "Blogscript is an educational platform that provides BCA notes, blogs, assignments, and project ideas to help students learn and grow.",
  "publisher": {
    "@type": "Organization",
    "name": "Blogscript",
    "logo": {
      "@type": "ImageObject",
      "url": "https://blogscript.example.com/images/logo.png"
    }
  },
  "sameAs": [
    "https://www.facebook.com/blogscript",
    "https://www.instagram.com/blogscript",
    "https://www.linkedin.com/company/blogscript",
    "https://twitter.com/blogscript"
  ],
  "potentialAction": {
    "@type": "SearchAction",
    "target": "https://blogscript.example.com/search?q={search_term_string}",
    "query-input": "required name=search_term_string"
  }
}
</script>
</head>
<body>
<!-- HEADER -->
 <?php include 'components/header.php';?>
<!-- HERO -->
<section class="hero">
  <h1>Read. Write. Share Your Thoughts</h1>
  <p>BlogScript is a modern blogging platform where creators share ideas, stories, and knowledge with the world.</p>

  <div class="hero-actions">
    <a href="<?=$local?>/register" class="primary-btn">Become a Creator</a>
    <a href="<?=$local?>/#blogs" class="outline-btn">Explore Blogs</a>
  </div>
</section>

<!-- BLOGS -->
<section class="container" id="blogs">
  <div class="section-header">
    <h2>Latest Blogs</h2>
    <form method="GET" action="">
        <input type="text" name="search" placeholder="Enter keywords..." style="font-size:16px; font-family:'Poppins', sans-serif;">
        <button type="submit" >Search</button>
    </form>
  </div>

  <div class="blog-grid">

   <?php
      function safe_output($text) {
      return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
      }

      if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
          $search = $conn->real_escape_string($_GET['search']);
          $query = "SELECT * FROM posts WHERE title LIKE '%$search%' OR content LIKE '%$search%' OR created_at LIKE '%$search%'";
          $result = $conn->query($query);

          if ($result && $result->num_rows > 0) {
              while ($row = $result->fetch_assoc())
              {
                  $title = safe_output($row['title']);
                  $content = $row['content'];
                  $created = safe_output($row['created_at']);
                  $id = $row['id'];
                  echo "
                    <div class='blog-card'>
                            <h3> $title</h3>
                            <p>$content</p>
                            <div class='card-footer'>
                            <a href='view?id=$id'>Read More</a>
                            <span> $created</span>
                            </div>
                    </div>

                  ";
              }
          } else {
              echo "<p>No blogs matched your search. Try different keywords.</p>";
          }
      } else {
$result = $conn->query("SELECT * FROM posts ORDER BY created_at DESC LIMIT 5");
while ($row = $result->fetch_assoc()) {
    $title = safe_output($row['title']);
    $content = ($row['content']);
    $created = safe_output($row['created_at']);
    $id = $row['id'];
    echo "
                    <div class='blog-card'>
                            <h3> $title</h3>
                            <p>$content</p>
                            <div class='card-footer'>
                            <a href='view?id=$id'>Read More</a>
                            <span> $created</span>
                            </div>
                    </div>
    ";
}
      }
      ?>




</section>
<!-- FOOTER -->
 <?php include 'components/footer.php';?>
</body>
</html>
