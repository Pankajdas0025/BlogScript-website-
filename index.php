<?php
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
  <!-- Custom CSS -->
  <link rel="stylesheet" href="assets/css/style.css">
  <?php include 'components/head.php'; ?>
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
    <h1 data-aos="fade-up">Read. Write. Share Your Thoughts</h1>
    <p data-aos="fade-up">BlogScript is a platform for all tech enthusiasts to read, write, and share technology-based blogs and ideas.</p>
    <div class="hero-actions">
      <a href="<?= $local ?>/register" class="primary-btn" data-aos="fade-right">Become a Creator</a>
      <a href="<?= $local ?>/#blogs" class="outline-btn" data-aos="fade-left">Explore Blogs</a>
    </div>
  </section>

  <!-- Latst BLOGS -->
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
          </form>
        </div>
      </div>
    </div>
    <div class="blog-grid" data-aos="fade-up" data-aos-duration="1000">
    <!-- Fetch latest blogs if no search query ============================================== -->
    </div>
  </section>
  <!-- Latst BLOGS -->
  <section class="container" id="blogs">
    <div class="section-header">
      <div class="header-title">
          <h2>Old Blogs</h2>
      </div>
    </div>
    <div class="old_blog-grid" data-aos="fade-up" data-aos-duration="1000">
    <!-- Fetch old blogs if no search query ============================================== -->
    </div>
  </section>
  <!-- News Letter Section -->
  <section class="newsletter">
      <div class="newsletter-box">
          <h2>Stay Updated 🚀</h2>
          <p>Join our community of tech enthusiasts and get the latest blogs directly in your inbox.</p>
          <form id="newsletterForm" class="newsletter-form">
              <input type="email" name="email" id="email" placeholder="Enter your email" required>
              <button type="submit">Subscribe</button>
          </form>
          <div id="newsletterMsg"></div>
      </div>
  </section>
  <!-- Stats   -->
<section class="stats-section" data-aos="fade">
    <div class="container stats-grid">
        <div class="stat-card" data-aos="fade-left">
            <div class="stat-icon">
                <i class="fa-solid fa-users"></i>
            </div>
            <h2 class="counter" data-target="200">0</h2>
            <p>Total Users</p>
        </div>
        <div class="stat-card" data-aos="fade">
            <div class="stat-icon">
                <i class="fa-solid fa-blog"></i>
            </div>
            <h2 class="counter" data-target="500">0</h2>
            <p>Total Blogs</p>
        </div>
        <div class="stat-card" data-aos="fade-right">
            <div class="stat-icon">
                <i class="fa-solid fa-envelope-open-text"></i>
            </div>
            <h2 class="counter" data-target="250">0</h2>
            <p>Subscribers</p>
        </div>
    </div>
</section>

      <!-- Add jQuery CDN for AJAX  -->
        <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
        <script>
            $(document).ready(function () {
                // Load all data ====================================================
                function load_all_posts(page_v) {
                    $.ajax({
                        url: "actions/load_all_posts.php",
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
                // Load old data ====================================================
                function load_old_posts(page_v) {
                    $.ajax({
                        url: "actions/load_old_posts.php",
                        method: "POST",
                        data: { page: page_v },
                        success: function (data) {
                            $(".old_blog-grid").html(data);
                        }
                    });
                }
                load_old_posts();

                // Load SEARCH data ONLY ON KEY UP ====================================================
                $("#search").on("keyup", function () {
                    let query = $(this).val().trim();
                    if (query !== "") {
                        $.ajax({
                            url: "actions/load_search_posts.php",
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
                $("#newsletterForm").submit(function(e) {
                    e.preventDefault();
                    let email = $("#email").val();
                    console.log("Sending:", email);
                    $.ajax({
                        url: "actions/newsletter.php",
                        method: "POST",
                        data: { email: email },
                        dataType: "json",
                        success: function(data) {
                            console.log("Response:", data);
                            $("#newsletterMsg")
                                .text(data.message)
                                .removeClass('success error')
                                .addClass(data.success ? 'success' : 'error');
                            if (data.success) {
                                $("#email").val('');
                            }
                        },
                        error: function(xhr) {
                            console.log("Error:", xhr.responseText);
                            $("#newsletterMsg").text("Server error. Check console.");
                        }
                    });
                });
            });

document.addEventListener("DOMContentLoaded", () => {

    const counters = document.querySelectorAll('.counter');

    counters.forEach(counter => {
        const target = +counter.dataset.target;
        let count = 0;

        const speed = 200; // smaller = faster
        const step = target / speed;

        const update = () => {
            count += step;

            if (count < target) {
                counter.innerText = Math.floor(count);
                requestAnimationFrame(update);
            } else {
                counter.innerText = target;
            }
        };

        update();
    });

});

          </script>
    <!-- FOOTER -->
    <?php include 'components/chatboat.php' ?>
    <?php include 'components/footer.php'; ?>
  </body>
</html>