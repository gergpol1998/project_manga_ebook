<?php
include "function.php";
connectdb();
session_start();
if (!isset($_SESSION['cusid'])) {
    echo '
        <script>
            sweetalerts("กรุณาลงชื่อเข้าใช้งานก่อน!!","warning","","login.php");
        </script>
        ';
} else {
    $cusid = $_SESSION['cusid'];
    $col = "*";
    $table = "book inner join publisher on pub_id = book_pubid
    inner join customer on cus_id = pub_cusid";
    $where = "pub_cusid = '$cusid' and book_status = '2' ORDER BY book_dateup DESC";
    $sqlpub = select_where($col, $table, $where);
    if ($sqlpub->num_rows > 0) {
        $row = $sqlpub->fetch_assoc();
        $pubid = $row['pub_id'];
    }
}

if($_SERVER['REQUEST_METHOD'] == "POST"){

    $Search = $_POST['search'];

    $sqlbook = "select * from book inner join publisher on pub_id = book_pubid
    inner join customer on pub_cusid = cus_id
    where book_pubid = '$pubid' and book_status = '1' and (book_name like '%$Search%' or pub_name like '%$Search%')";
    $ex_book = connectdb()->query($sqlbook);

    $sqltype = "select * from typebook inner join book_type on type_id = btype_typeid
    inner join book on book_id = btype_bookid
    where book_pubid = '$pubid' and book_status = '1' and (type_name like '%$Search%')";
    $ex_type = connectdb()->query($sqltype);

    $sqltag = "select * from tag inner join book_tag on tag_id = btag_tagid
    inner join book on book_id = btag_bookid
    where book_pubid = '$pubid' and book_status = '1' and (tag_name like '%$Search%')";
    $ex_tag = connectdb()->query($sqltag);

    if($ex_book->num_rows > 0){
        while($row = $ex_book->fetch_array()){
            echo '<a href="display_search_wait.php?bookid='.$row['book_id'].'" class="list-group list-group-item-action border p-2">'.$row['book_name'].'</a>';
        }
    }
    elseif($ex_type->num_rows > 0){
        while($row = $ex_type->fetch_array()){
            echo '<a href="display_search_wait.php?bookid='.$row['book_id'].'" class="list-group list-group-item-action border p-2">'.$row['book_name'].'</a>';
        }
    }
    elseif($ex_tag->num_rows > 0){
        while($row = $ex_tag->fetch_array()){
            echo '<a href="display_search_wait.php?bookid='.$row['book_id'].'" class="list-group list-group-item-action border p-2">'.$row['book_name'].'</a>';
        }
    }
    else{
        echo '<p class="list-group list-group-item">ไม่เจอผลการค้นหา</p>';
    }
    connectdb()->close();
}
?>