<?php
include "function.php";
connectdb();
session_start();
$username = $_POST['username'];
$password = $_POST['password'];

$password = hash('sha512', $password);

$col = 'cus_id,cus_uname,cus_pass';
$where = "cus_uname = '$username' and cus_pass = '$password'";
$result = select_where($col, 'customer', $where);

echo "<script> src ='https://code.jquery.com/jquery-3.6.1.min.js' 
</script>
<script src = 'https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.min.js'></script>
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css'/>";

echo "<script src='function.js'></script>";

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    if (isset($username) && isset($password)) {
        $_SESSION['cusname'] = $row['cus_uname'];
        $_SESSION["cusid"] = $row["cus_id"];
        echo '
        <script>
            sweetalerts("เข้าสู่ระบบสำเร็จ!!","success","","index.php");
        </script>
        ';
    }
}
else {
    echo '
    <script>
        sweetalerts("เข้าสู่ระบบไม่สำเร็จ!!","warning","Username or Password ผิด","login.php");
    </script>
    ';
}
connectdb()->close();
