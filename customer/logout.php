<?php
session_start();
unset($_SESSION["cusname"]);
unset($_SESSION["cusid"]);

echo "<script> src ='https://code.jquery.com/jquery-3.6.1.min.js' 
    </script>
    <script src = 'https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.min.js'></script>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css'/>";

echo "<script src='function.js'></script>";

echo '
    <script>
        sweetalerts("ออกจากระบบ!!","success","","index.php");
    </script>
        ';
?>