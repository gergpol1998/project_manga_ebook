<?php
include "function.php";
connectdb();
session_start();
$cusid = $_SESSION["cusid"];

if (isset($_GET['bookid']) && isset($_GET['rate'])) {
    $bookid = $_GET['bookid'];
    $rating = $_GET['rate'];
    
    $sqlcus = "select bscore_cusid from bookscore
    where bscore_cusid = '$cusid' and bscore_bookid = '$bookid'";
    $ex_cus = connectdb()->query($sqlcus);
    if ($ex_cus->num_rows > 0){
        $sqlup_rate = "update bookscore set bscore_score = '$rating' where bscore_bookid = '$bookid'
        and bscore_cusid = '$cusid'";
        $result = connectdb()->query($sqlup_rate);
        if(!$result){
            die(mysqli_error(connectdb()));
        }
        else{
            header("location:index.php");
        }
    }
    else{
        $sqlins_rate = "insert into bookscore (bscore_bookid,bscore_cusid,bscore_score)
        values ('$bookid','$cusid','$rating')";
        $result = connectdb()->query($sqlins_rate);
        if(!$result){
            die(mysqli_error(connectdb()));
        }
        else{
            header("location:index.php");
        }
    }
}
connectdb()->close();
?>
