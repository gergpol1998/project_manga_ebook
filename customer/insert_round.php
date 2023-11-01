<?php
include "function.php";
connectdb();
session_start();
$cusid = $_SESSION['cusid'];

echo "<script> src ='https://code.jquery.com/jquery-3.6.1.min.js' 
</script>
<script src = 'https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.min.js'></script>
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css'/>";

echo "<script src='function.js'></script>";

if (isset($_POST['submit'])){
    $round = $_POST['round'];
    $day = date("d");
    
    $sqlins_date = "insert into date (date_roundid,date_date)
    values ('$round','$day')";
    $result = connectdb()->query($sqlins_date);
    if (!$result){
        die(mysqli_error(connectdb()));
    }
    else{
        $sqlpub = "select pub_id,pub_round from publisher inner join customer on pub_cusid = cus_id
        where pub_cusid = '$cusid'";
        $ex_pub = connectdb()->query($sqlpub);
        if ($ex_pub->num_rows > 0){
            $row = $ex_pub->fetch_assoc();
            $pubid = $row['pub_id'];

            $sqlup_pub = "update publisher set pub_round = '$round'
            where pub_id = '$pubid'";
            $result3 = connectdb()->query($sqlup_pub);
            if(!$result3){
                die(mysqli_error(connectdb()));
            }
            else{
                echo '
                <script>
                    sweetalerts("บันทึกข้อมูลสำเร็จ!!","success","","my_work.php");
                </script>
                    ';
            }
        }      
    
    }
}
connectdb()->close();
?>