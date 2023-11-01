<?php
include "function.php";
connectdb();
session_start();

echo "<script> src ='https://code.jquery.com/jquery-3.6.1.min.js' 
    </script>
    <script src = 'https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.min.js'></script>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css'/>";

    echo "<script src='function.js'></script>";

if ($_POST['submit']){
    $pass1 = $_POST['pass1'];
    $pass2 = $_POST['pass2'];

    if ($pass1 === $pass2){
        $email = $_SESSION['email'];
        $uname = $_SESSION['uname'];
        $password = hash('sha512',$pass1);
        $sqlup_pass = "update customer set cus_pass = '$password'
        where cus_uname = '$uname' and cus_email = '$email'";
        $ex_pass = connectdb()->query($sqlup_pass);

        if (!$ex_pass){
            die(mysqli_error(connectdb()));
        }
        else{
            echo '
            <script>
                sweetalerts("เปลี่ยนรหัสผ่านเรียบร้อย!!","success","","login.php");
            </script>
            ';
            unset($_SESSION['error']);
            unset($_SESSION['email']);
            unset($_SESSION['uname']);
        }
    }
    else{
        $_SESSION['error'] = "รหัสผ่านไม่ตรงกัน";
        header("location:reset_pass.php");
    }
}
?>