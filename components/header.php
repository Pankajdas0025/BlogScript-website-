<?php
$local = "http://localhost/BlogScript";
?>
<style>
/* ========== HEADER ========== */
@import url('assets/css/root.css');
header { position: sticky; top: 0; background:#FFF; box-shadow: 0 2px 10px rgba(0,0,0,0.05);z-index: 1000;}
.nav { max-width: 1200px; margin: auto;  padding: 18px 22px; display: flex; justify-content: space-around; align-items: center;}
.nav-links{ display: flex;padding:0 15px;justify-content: center; align-items: center;}
.logo { font-size: 1.8rem; font-weight: 700;color: var(--primary); display: flex;align-items: center; align-content: center;}
.nav a { text-decoration: none; margin-left: 22px;  color: var(--text); font-weight: 500;}
.nav .btn , .user-btn{background: var(--primary);color: var(--white);padding: 8px 18px; border-radius: 20px; box-shadow: 2px 6px 14px var(--secondary) inset, -2px 6px 14px rgb(244, 244, 244) ;}

/* Dropdown */
.user-dropdown {
  position: relative;
  margin-left: 15px;
}

/* Menu */
.user-menu {
  position: absolute;
  left: -5%;
  top: 120%;
  background: #fff;
  border-radius: 8px;
  min-width: 150px;
  display: none;
  box-shadow: 0 5px 15px rgba(0,0,0,0.1);
  overflow: hidden;
}

.user-menu a {
  display: block;
  padding: 10px;
  color: #333;
  text-decoration: none;
}

.user-menu a:hover {
  background: #f5f5f5;
}

/* Show */
.user-dropdown.active .user-menu {
  display: block;
}
@media (max-width: 768px) {
  .nav {  flex-direction: column;gap: 15px;padding:10px 0 20px 0px;}
  .nav a {margin-left: 12px;}
  /* Menu */
.user-menu {

  left:-100%;
}

  }
</style>
 <!-- HEADER -->
<header>
  <div class="nav">
    <div class="logo"><img src="https://blogscriptapp.free.nf/Images/android-chrome-512x512.png" height="80" width="80"> BlogScript</div>
    <div class="nav-links">
      <a href="<?= $local?>/"><i class="fa-solid fa-house"></i> Home</a>
      <a href="<?= $local?>/#blogs"><i class="fa-solid fa-square-pen"></i> Blogs</a>
      <?php if (!isset($_SESSION['email']) && !isset($_SESSION['id'])): ?>
        <a href="<?= $local ?>/register" class="btn">Login</a>
      <?php else: ?>
        <!-- Custom Dropdown -->
        <div class="user-dropdown" id="userDropdown">
          <div class="user-btn" onclick="toggleDropdown()">
            <i class="fa-solid fa-user"></i>
          </div>
          <div class="user-menu">
            <a href="<?= $local ?>/profile?id=<?= isset($_SESSION['id']) ? $_SESSION['id'] : '' ?>">Profile</a>
            <a href="<?= $local ?>/admin">Admin Panel</a>
            <a href="<?= $local ?>/setting">Setting</a>
            <a href="<?= $local ?>/logout">Logout</a>

          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</header>
<script>
  function toggleDropdown() {
  document.getElementById("userDropdown").classList.toggle("active");
}

// Close when clicking outside
document.addEventListener("click", function(e) {
  let dropdown = document.getElementById("userDropdown");
  if (dropdown && !dropdown.contains(e.target)) {
    dropdown.classList.remove("active");
  }
});

  AOS.init({
    duration: 800,
    easing: 'ease-in-out',
    once: true,
    mirror: false
  });
  </script>