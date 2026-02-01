<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'src/db.php';
include 'src/config.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BlogScript | Read • Write • Share</title>
  <meta name="theme-color" content="#ffffff">
  <meta name="application-name" content="BlogScript" />
  <meta name="description" content="BlogScript is a simple blogging platform where you can read, write, and share creative blogs. Explore ideas, stories, and knowledge shared by creators." />
  <meta name="keywords" content="BlogScript, blogging platform, write blogs, share stories, creative blogs, read blogs, online writing, blog community, BlogScript creator" />
  <meta name="author" content="BlogScript" />
  <link rel="manifest" href="src/manifest.json">

  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:url" content="https://blogscriptapp.free.nf/home/">
  <meta property="og:title" content="BlogScript - a simple blogging platform to read, write & share blogs">
  <meta property="og:description" content="BlogScript is a simple blogging platform where you can read, write, and share creative blogs. Explore ideas, stories, and knowledge shared by creators." />
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
  <?php include 'components/header.php'; ?>
  <!-- HERO -->
  <section class="hero">
    <h1>Read. Write. Share Your Thoughts</h1>
    <p>BlogScript is a modern blogging platform where creators share ideas, stories, and knowledge with the world.</p>
    <div class="hero-actions">
      <a href="<?= $local ?>/register" class="primary-btn">Become a Creator</a>
      <a href="<?= $local ?>/#blogs" class="outline-btn">Explore Blogs</a>
    </div>
  </section>

  <!-- BLOGS -->
  <section class="container" id="blogs">
    <div class="section-header">
      <div class="header-title">
          <h2>Latest Blogs</h2>
      </div>
      <div class="search-box">
        <div class="search-icon"><i class="fa fa-search"></i></div>
        <div>
          <form id="searchForm">
            <input type="text" id="search" name="search" placeholder="Enter keywords..." style="font-size:16px; font-family:'Poppins', sans-serif;">
             <!-- <button type="submit">Search</button> -->
          </form>
        </div>
      </div>
    </div>
    <div class="blog-grid">
    <!-- Fetch latest blogs if no search query ============================================== -->
    </div>
  </section>
      <!-- Add jQuery CDN for AJAX  -->
      <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
            <script>
            $(document).ready(function () {
                // Load all data ====================================================
                function load_all_posts(page_v) {
                    $.ajax({
                        url: "load_all_posts.php",
                        method: "POST",
                        data: { page: page_v },
                        success: function (data) {
                            $(".blog-grid").html(data);
                        }
                    });
                }
                // First load on page start
                load_all_posts();

                // Data Pagination ===============================================
                $(document).on("click", "#pagination a", function (e) {
                    e.preventDefault();
                    let page_value = $(this).data("page");
                    load_all_posts(page_value);
                });


                // Load SEARCH data ONLY ON KEY UP ====================================================
                $("#search").on("keyup", function () {
                    let query = $(this).val().trim();
                    if (query !== "") {
                        $.ajax({
                            url: "load_search_posts.php",
                            method: "POST",
                            data: { search: query },
                            success: function (data) {
                                $(".blog-grid").html(data);
                            }
                        });
                    } else {
                        // If search box is empty, load all posts
                        load_all_posts();
                    }
                });

            });
            </script>

    <!-- FOOTER -->
     <?php include 'chatboat.php' ?>
    <?php include 'components/footer.php'; ?>
  </body>
</html>