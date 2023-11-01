<?php
include "function.php";
connectdb();

echo "<script src='https://code.jquery.com/jquery-3.6.1.min.js'></script>";
echo "<script src='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.min.js'></script>";
echo "<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css'/>";

echo "<script src='function.js'></script>";

if (isset($_GET['proid'])) {
    $proid = $_GET['proid'];
    
    $sqldelpro = "delete from promotion where pro_id = '$proid'";
    $result = connectdb()->query($sqldelpro);
    if (!$result) {
        die(mysqli_error(connectdb()));
    } else {
        echo '
        <script>
                sweetalerts("ลบข้อมูลสำเร็จ!!","success","","promotion.php");
        </script>
            ';
    }
}
connectdb()->close();
?>
