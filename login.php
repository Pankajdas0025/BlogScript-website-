
<?php
include 'src/db.php';
include 'src/config.php';
if($_SERVER['REQUEST_METHOD']=="POST")
{

    $Email=mysqli_real_escape_string($conn,strip_tags($_POST['uEmail']));// secure -> mysqli_real_escape_string
    $Pass=mysqli_real_escape_string($conn,$_POST['uPass']); // secure

    $sqlcheck="SELECT *FROM users
    WHERE EMAIL='$Email' AND VERIFICATION_STATUS='Success'";
    $response=$conn->query($sqlcheck);


    // $stmt = $conn->prepare("SELECT * FROM users WHERE EMAIL = ? AND VERIFICATION_STATUS = ? ");
    // $stmt->bind_param("ss" , $Email , 'Success');
    // $response = $stmt->execute();

    if($response->num_rows==1)

    {
      $row=$response->fetch_assoc();
      if (password_verify($Pass, $row['PASSWORD']))
      {

session_set_cookie_params(0);
session_start();
$_SESSION['email'] =$Email;
header("Location: admin");
exit();

      }
      else
      {
       echo "<script>alert('Invalid password.');
          window.location.href = 'register';</script>";

      }

     }

    else
    {
        echo "<script>alert('User not registred!');
           window.location.href = 'register';</script>";

    }
}


?>


