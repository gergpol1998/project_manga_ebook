<?php
include "function.php";
connectdb();
if($_SERVER['REQUEST_METHOD'] == "POST"){

    $Search = $_POST['search'];

    $sqlbook = "select * from book inner join publisher on pub_id = book_pubid
    inner join customer on pub_cusid = cus_id
    where book_status = '2' and (book_name like '%$Search%' or pub_name like '%$Search%')";
    $ex_book = connectdb()->query($sqlbook);

    $sqltype = "select * from typebook inner join book_type on type_id = btype_typeid
    inner join book on book_id = btype_bookid
    where book_status = '2' and (type_name like '%$Search%')";
    $ex_type = connectdb()->query($sqltype);

    $sqltag = "select * from tag inner join book_tag on tag_id = btag_tagid
    inner join book on book_id = btag_bookid
    where book_status = '2' and (tag_name like '%$Search%')";
    $ex_tag = connectdb()->query($sqltag);

    $sqlpro = "select * 
    from promotion inner join bookpro on pro_id = bpro_proid
    inner join book on bpro_bookid = book_id
    where pro_edate >= CURDATE()+ INTERVAL 1 DAY and book_status = '2'
    and pro_name like '%$Search%'";
    $ex_pro = connectdb()->query($sqlpro);

    if($ex_book->num_rows > 0){
        while($row = $ex_book->fetch_array()){
            echo '<a href="search_content.php?bookid='.$row['book_id'].'" class="list-group list-group-item-action border p-2">'.$row['book_name'].'</a>';
        }
    }
    elseif($ex_type->num_rows > 0){
        while($row = $ex_type->fetch_array()){
            echo '<a href="search_content.php?bookid='.$row['book_id'].'" class="list-group list-group-item-action border p-2">'.$row['book_name'].'</a>';
        }
    }
    elseif($ex_tag->num_rows > 0){
        while($row = $ex_tag->fetch_array()){
            echo '<a href="search_content.php?bookid='.$row['book_id'].'" class="list-group list-group-item-action border p-2">'.$row['book_name'].'</a>';
        }
    }
    elseif($ex_pro->num_rows > 0){
        while($row = $ex_pro->fetch_array()){
            echo '<a href="search_content.php?bookid='.$row['book_id'].'" class="list-group list-group-item-action border p-2">'.$row['book_name'].'</a>';
        }
    }
    else{
        echo '<p class="list-group list-group-item">ไม่เจอผลการค้นหา</p>';
    }
    connectdb()->close();
}
?>