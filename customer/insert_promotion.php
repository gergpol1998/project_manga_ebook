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
if (isset($_POST['submit'])) {
    //query lastid
    $lastproid = autoid('PRO-', 'pro_id', 'promotion', '0000001');
    $proname = $_POST['proname'];
    $discount = $_POST['discount'];
    $sdate = $_POST['sdate'];
    $edate = $_POST['edate'];
    $bookid = $_POST['book'];

    $sqlpub = "select pub_id from publisher inner join customer on pub_cusid = cus_id
    where pub_cusid = '$cusid'";
    $ex_pub = connectdb()->query($sqlpub);
    if ($ex_pub->num_rows > 0){
        $row = $ex_pub->fetch_assoc();
        $pubid = $row['pub_id'];
    }

    $sqlins_pro = "insert into promotion (pro_id,pro_name,pro_discount,pro_sdate,pro_edate,pro_pubid)
    values ('$lastproid','$proname','$discount','$sdate','$edate','$pubid')";
    $result = connectdb()->query($sqlins_pro);

    if (!isset($result)) {
        die(mysqli_error(connectdb()));
    }
    else{
        foreach ($bookid as $books){
            $sqlins_bookpro = "insert into bookpro (bpro_bookid,bpro_proid)
            values ('$books','$lastproid')";
            $result2 = connectdb()->query($sqlins_bookpro);
            if (!isset($result2)) {
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
?>