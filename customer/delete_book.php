<?php
include "function.php";
connectdb();

echo "<script src='https://code.jquery.com/jquery-3.6.1.min.js'></script>";
echo "<script src='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.min.js'></script>";
echo "<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css'/>";

echo "<script src='function.js'></script>";

if (isset($_GET['bookid'])) {
    $bookid = $_GET['bookid'];
    $tagid = array();
    $sqltag = "select tag_id from tag inner join book_tag on tag_id = btag_tagid
    inner join book on btag_bookid = book_id
    where btag_bookid = '$bookid'";
    $ex_tag = connectdb()->query($sqltag);
    if ($ex_tag->num_rows > 0){
        while($row2 = $ex_tag->fetch_assoc()){
            $tagid[] = $row2['tag_id'];
        }
    }
    $sqldel_booktag = "delete from book_tag where btag_bookid = '$bookid'";
    $result = connectdb()->query($sqldel_booktag);
    if (!$result){
        die(mysqli_error(connectdb()));
    }
    else{
        $sqldel_booktype = "delete from book_type where btype_bookid = '$bookid'";
        $result2 = connectdb()->query($sqldel_booktype);
        if (!$result2){
            die(mysqli_error(connectdb()));
        }
        else{
            foreach ($tagid as $tags){
                $sqldel_tag = "delete from tag where tag_id = '$tags'";
                $result3 = connectdb()->query($sqldel_tag);
            }
            if (!$result3){
                die(mysqli_error(connectdb()));
            }
            else{
                $sqldelbook = "delete from book where book_id = '$bookid'";
                $result4 = connectdb()->query($sqldelbook);
                if (!$result4){
                    die(mysqli_error(connectdb()));
                }
                else{
                    echo '
                    <script>
                            sweetalerts("ลบข้อมูลสำเร็จ!!","success","","draf.php");
                    </script>
                        ';
                }
            }
        }
    }
}
connectdb()->close();
?>