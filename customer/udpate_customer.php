<?php

use Mpdf\Tag\Q;

include "function.php";
connectdb();
if ($_POST['submit']){
    $cusid = $_POST['id'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $password = hash('sha512',$password);
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $bdate = $_POST['bdate'];
    $tel = $_POST['tel'];

    $sqlup_cus = "update customer set cus_name = '$fname',
    cus_lname = '$lname',cus_sex = '$gender',cus_bdate = '$bdate',cus_tel = '$tel',cus_email = '$email'
    where cus_id = '$cusid'";
    $result = connectdb()->query($sqlup_cus);

    echo "<script> src ='https://code.jquery.com/jquery-3.6.1.min.js' 
    </script>
    <script src = 'https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.min.js'></script>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css'/>";

    echo "<script src='function.js'></script>";

    if (!$result) {
        die(mysqli_error(connectdb()));
    } else {
        echo '
            <script>
                sweetalerts("บันทึกข้อมูลสำเร็จ!!","success","","myinfo.php");
            </script>
            ';
    }
    
    connectdb()->close();

}
?>