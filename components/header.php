<?php
$local = "http://localhost/BlogScript";
?>
<style>
/* ========== HEADER ========== */
@import url('<?php echo $local; ?>/assets/css/root.css');
header { position: sticky; top: 0; background: var(--white); box-shadow: 0 2px 10px rgba(0,0,0,0.05);z-index: 1000;}
.nav { max-width: 1200px; margin: auto;  padding: 18px 22px; display: flex; justify-content: space-around; align-items: center;}
.logo { font-size: 1.8rem; font-weight: 700;color: var(--primary); display: flex;align-items: center; align-content: center;}
.nav a { text-decoration: none; margin-left: 22px;  color: var(--text); font-weight: 500;}
.nav .btn {background: var(--primary);color: var(--white);padding: 8px 18px; border-radius: 20px; box-shadow: 2px 6px 14px var(--secondary) inset, -2px 6px 14px rgb(244, 244, 244) ;}
@media (max-width: 768px) {
  .nav {  flex-direction: column;gap: 15px;padding:10px 0 20px 0px;}
  .nav a {margin-left: 12px;}}
</style>
 <!-- HEADER -->
<header>
  <div class="nav">
    <div class="logo"><img src="https://blogscriptapp.free.nf/Images/android-chrome-512x512.png" height="80" width="80"> BlogScript</div>
    <div>
      <a href="<?= $local?>/"><i class="fa-solid fa-house"></i> Home</a>
      <a href="<?= $local?>/#blogs"><i class="fa-solid fa-square-pen"></i> Blogs</a>
      <a href="<?= $local?>/register" class="btn"><i class="fas fa-sign-in-alt"></i> Login</a>
    </div>
  </div>
</header>
<script>
  AOS.init({
    duration: 800,
    easing: 'ease-in-out',
    once: true,
    mirror: false
  });
  </script>