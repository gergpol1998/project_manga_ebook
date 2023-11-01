<?php
session_start();
include "function.php";
connectdb();

echo "<script> src ='https://code.jquery.com/jquery-3.6.1.min.js' 
    </script>
    <script src = 'https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.min.js'></script>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css'/>";

echo "<script src='function.js'></script>";
if (!isset($_SESSION["cusid"])) {
    echo '
        <script>
            sweetalerts("กรุณาลงชื่อเข้าใช้งานระบบก่อน!!","warning","","login.php");
        </script>
    ';
}
else{
    $cusid = $_SESSION['cusid'];
}
$act = $_REQUEST['act'];
//removeproduct
if ($act == 'cancle') {
    $sqldel_cart = "delete from cart where cart_cusid = '$cusid'";
    $result = connectdb()->query($sqldel_cart);
    if (!$result){
        die(mysqli_error(connectdb()));
    }
    else{
        header("location:cart.php");
    }
}
connectdb()->close();
?>