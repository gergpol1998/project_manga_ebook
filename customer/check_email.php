<?php
include "function.php";
connectdb();
session_start();

echo "<script> src ='https://code.jquery.com/jquery-3.6.1.min.js' 
</script>
<script src = 'https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.min.js'></script>
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css'/>";

echo "<script src='function.js'></script>";

if (isset($_POST['submit'])){
    $uname = $_POST['uname'];
    $email = $_POST['email'];
    $sqlemail = "select cus_uname,cus_email from customer
    where cus_email = '$email' and cus_uname='$uname'";
    $ex_email = connectdb()->query($sqlemail);
    if ($ex_email->num_rows > 0){
        unset($_SESSION['error']);
        $_SESSION['uname'] = $uname;
        $_SESSION['email'] = $email;
        header("location:reset_pass.php");
    }
    else{
        $_SESSION['error'] = "username or email ไม่ถูกต้อง";
        header("location:forgot_pass.php");
    }
}

connectdb()->close();
?>
