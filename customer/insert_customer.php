<?php
include "function.php";
connectdb();
if ($_POST['submit']){
    $lastid = autoid('CUS-','cus_id','customer','0000001');
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password = hash('sha512',$password);
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $bdate = $_POST['bdate'];
    $tel = $_POST['tel'];

    //insert data
    $values = "cus_id,cus_uname,cus_pass,cus_name,cus_lname,cus_sex,cus_bdate,cus_tel,
    cus_email,cus_coin";
    $inputdata = "'$lastid','$username','$password','$fname','$lname','$gender','$bdate','$tel'
    ,'$email',0";
    $result = insertdata('customer',$values,$inputdata);

    echo "<script> src ='https://code.jquery.com/jquery-3.6.1.min.js' 
    </script>
    <script src = 'https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.min.js'></script>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css'/>";

    echo "<script src='function.js'></script>";

    if ($result) {
        echo '
            <script>
                sweetalerts("บันทึกข้อมูลสำเร็จ!!","success","","login.php");
            </script>
            ';
    } else {
        echo '
            <script>
                sweetalerts("บันทึกข้อมูลไม่สำเร็จ!!","warning","","register.php");
            </script>
            ';
    }
    
    connectdb()->close();

}
?>