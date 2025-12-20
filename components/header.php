<?php
include 'src/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <style>
    /* ========== HEADER ========== */
@import url("../style/root.css");
header {
  position: sticky;
  top: 0;
  background: var(--white);
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
  z-index: 1000;
}

.nav {
  max-width: 1200px;
  margin: auto;
  padding: 18px 22px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.logo {
  font-size: 1.8rem;
  font-weight: 700;
  color: var(--primary);
  display: flex;
  align-items: center;
  align-content: center;
}

.nav a {
  text-decoration: none;
  margin-left: 22px;
  color: var(--text);
  font-weight: 500;
}

.nav .btn {
  background: var(--primary);
  color: var(--white);
  padding: 8px 18px;
  border-radius: 20px;
}
@media (max-width: 768px) {
  .nav {
    flex-direction: column;
    gap: 15px;
  }
}
</style>

</head>
<body>

  <!-- HEADER -->
<header>
  <div class="nav">
    <div class="logo"><img src="Images/android-chrome-512x512.png" height="80" width="80"> BlogScript</div>
    <div>
      <a href="<?= $local?>/">Home</a>
      <a href="<?= $local?>/#blogs">Blogs</a>
      <a href="<?= $local?>/register#" class="btn">Login</a>
    </div>
  </div>
</header>


</body>
</html>