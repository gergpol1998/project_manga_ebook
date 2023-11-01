<?php
include "function.php";
connectdb();

echo "<script> src ='https://code.jquery.com/jquery-3.6.1.min.js' 
</script>
<script src = 'https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.min.js'></script>
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css'/>";

echo "<script src='function.js'></script>";

if(isset($_GET['bookid'])){
    $bookid = $_GET['bookid'];
    $result = deletedata("bookshelf","bshelf_bookid = '$bookid'");
    if (!$result) {
        die(mysqli_error(connectdb()));
    } 
    else {
        echo '
            <script>
                sweetalerts("ลบข้อมูลสำเร็จ!!","success","","shelf.php");
            </script>
                ';
    }
}
?>