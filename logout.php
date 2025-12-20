<?php
session_start();
session_unset();   // saare session variables clear ho jaenge
session_destroy(); // session khatam ho jayega
header("Location:login.php");
exit();
?>
