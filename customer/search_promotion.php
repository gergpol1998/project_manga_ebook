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

    $sqlpro = "select * 
    from promotion inner join bookpro on pro_id = bpro_proid
    inner join book on bpro_bookid = book_id
    where pro_pubid = '$pubid'and pro_edate >= CURDATE()+ INTERVAL 1 DAY
    and pro_name like '%$Search%'";
    $result = connectdb()->query($sqlpro);

    if($result->num_rows > 0){
        while($row = $result->fetch_array()){
            echo '<a href="display_search_promotion.php?proid='.$row['pro_id'].'" class="list-group list-group-item-action border p-2">'.$row['pro_name'].'</a>';
        }
    }
    else{
        echo '<p class="list-group list-group-item">ไม่เจอผลการค้นหา</p>';
    }
    connectdb()->close();
}
?>