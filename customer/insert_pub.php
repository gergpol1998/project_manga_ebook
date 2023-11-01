<?php
if($_POST['submit']){
    include "function.php";
    connectdb();
    session_start();
    $cusid = $_SESSION['cusid'];
    $name = $_POST['penname'];
    $bankid = $_POST['bankid'];
    $pubacc = $_POST['pubacc'];
    $lastid = autoid("PUB-","pub_id","publisher","0000001");

    $values = "pub_id,pub_name,pub_account,pub_date,pub_cusid,pub_bankid,pub_round,pub_dinc,pub_instatus";
    $data = "'$lastid','$name','$pubacc',NOW(),'$cusid','$bankid','ROU-001',NOW(),'0'";
    $result = insertdata("publisher",$values,$data);

    echo "<script> src ='https://code.jquery.com/jquery-3.6.1.min.js' 
    </script>
    <script src = 'https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.min.js'></script>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css'/>";

    echo "<script src='function.js'></script>";

    if ($result) {
        echo '
            <script>
                sweetalerts("บันทึกข้อมูลสำเร็จ!!","success","","index.php");
            </script>
            ';
    } else {
        echo '
            <script>
                sweetalerts("บันทึกข้อมูลไม่สำเร็จ!!","warning","","publis_register.php");
            </script>
            ';
    }
    
    connectdb()->close();
}
?>