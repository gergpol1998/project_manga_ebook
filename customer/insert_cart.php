<?php
include "function.php";
connectdb();
session_start();
if (isset($_GET['bookid']) && isset($_SESSION['cusid'])){

    
    
    $bookid = $_GET['bookid'];
    $cusid = $_SESSION['cusid'];
    if (isset($_GET['pro'])){
        $proid = $_GET['pro'];
        $result = insertdata("cart","cart_bookid,cart_cusid,cart_proid","'$bookid','$cusid','$proid'");
    }
    else{
        $result = insertdata("cart","cart_bookid,cart_cusid,cart_proid","'$bookid','$cusid',NULL");
    }
    if ($result) {
        header("location:index.php");
    }

    connectdb()->close();
}
?>