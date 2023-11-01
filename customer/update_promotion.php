<?php
include "function.php";
connectdb();
session_start();
$cusid = $_SESSION["cusid"];

echo "<script> src ='https://code.jquery.com/jquery-3.6.1.min.js' 
</script>
<script src = 'https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.min.js'></script>
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css'/>";

echo "<script src='function.js'></script>";
if($_POST['submit']){
    $proid = $_POST['proid'];
    $proname = $_POST['proname'];
    $discount = $_POST['discount'];
    $sdate = $_POST['sdate'];
    $edate = $_POST['edate'];
    $book = $_POST['book'];

    $sqlupdate_pro = "update promotion set pro_name = '$proname',pro_discount = '$discount',
    pro_sdate = '$sdate',pro_edate = '$edate'
    where pro_id = '$proid'";
    $result = connectdb()->query($sqlupdate_pro);
    if (!$result) {
        die(mysqli_error(connectdb()));
    } 
    else {
        
            $sqldel_bookpro = "delete from bookpro where bpro_proid = '$proid'";
            $result2 = connectdb()->query($sqldel_bookpro);
        
        if(!$result2){
            die(mysqli_error(connectdb()));
        }
        else{    
            foreach ($book as $books){

                $sqlins_bookpro = "insert into bookpro (bpro_bookid ,bpro_proid)
                values ('$books','$proid')";
                $result3 = connectdb()->query($sqlins_bookpro);
                if(!$result3){
                    die(mysqli_error(connectdb()));
                }
                else{
                    echo '
                <script>
                    sweetalerts("บันทึกข้อมูลสำเร็จ!!","success","","promotion.php");
                </script>
                ';
                }
            }    
        }
    }
}
