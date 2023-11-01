<?php
include "function.php";
connectdb();
session_start();
$cusid = $_SESSION["cusid"];

echo "<script> src ='https://code.jquery.com/jquery-3.6.1.min.js' 
</script>
<script src = 'https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.min.js'></script>
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css'/>";

echo "<script src='function.js'></script>";

if (isset($_POST['submit'])) {

    $sqlpub = "select * from publisher inner join customer on pub_cusid = cus_id
    where pub_cusid = '$cusid'";
    $ex_pub = connectdb()->query($sqlpub);
    $row = $ex_pub->fetch_assoc();
    $pubid = $row['pub_id'];


    $bookid = $_POST['bid'];
    $bname = $_POST['bname'];
    $summary = $_POST['summary'];
    $price = $_POST['price'];
    $tag = $_POST['tag'];
    $type_book = $_POST['type_book'];

    $cov = '';
    $cont = '';
    $test = '';

    $sqlbook = "select book_cover,book_content,book_test from book where book_id='$bookid'";
    $ex_book = connectdb()->query($sqlbook);
    $book = $ex_book->fetch_assoc();

    // Get the current month and year
    $current_month = date('m');
    $current_year = date('Y');

    //upload cover
    if ($_FILES['file1']) {

        $file = $_FILES['file1'];
        $file_name = $file['name'];

        $file_tmp = $file['tmp_name'];
        $file_size = $file['size'];
        $file_error = $file['error'];
        // Create a new folder using the current month and year
        $new_folder1 = 'uploads/' . $pubid . '/' . $current_year . '/' . $current_month;
        if (!file_exists($new_folder1)) {
            mkdir($new_folder1, 0777, true);
        }
        if ($file_error === 0) {
            // Check the file type
            $file_type1 = exif_imagetype($file_tmp);
            $allowed_types1 = array(IMAGETYPE_JPEG, IMAGETYPE_PNG);
            if (!in_array($file_type1, $allowed_types1)) {
                $_SESSION['error'] = 'Error: Only JPEG and PNG files are allowed.';
            } else {
                unset($_SESSION['error']);
            }
        }
    }



    //upload pdf
    if ($_FILES['file2']) {
        $file2 = $_FILES['file2'];
        $file_name2 = $file2['name'];
        
        $file_tmp2 = $file2['tmp_name'];
        $file_size2 = $file2['size'];
        $file_error2 = $file2['error'];
        // Create a new folder using the current month and year
        $new_folder2 = 'pdf/' . $pubid . '/' . $current_year . '/' . $current_month;
        if (!file_exists($new_folder2)) {
            mkdir($new_folder2, 0777, true);
        }
        if ($file_error2 === 0) {
            // Check the file type
            $file_type2 = mime_content_type($file_tmp2);
            if ($file_type2 !== 'application/pdf') {
                $_SESSION['error2'] = 'Error: Only PDF files are allowed.';
            } else {
                unset($_SESSION['error2']);
            }
        }
    } 


    //upload test pdf
    if ($_FILES['file3']) {
        $file3 = $_FILES['file3'];
        $file_name3 = $file3['name'];
        
        $file_tmp3 = $file3['tmp_name'];
        $file_size3 = $file3['size'];
        $file_error3 = $file3['error'];
        // Create a new folder using the current month and year
        $new_folder3 = 'test/' . $pubid . '/' . $current_year . '/' . $current_month;
        if (!file_exists($new_folder3)) {
            mkdir($new_folder3, 0777, true);
        }
        if ($file_error3 === 0) {
            // Check the file type
            $file_type3 = mime_content_type($file_tmp3);
            if ($file_type3 !== 'application/pdf') {
                $_SESSION['error3'] = 'Error: Only PDF files are allowed.';
            } else {
                unset($_SESSION['error3']);
            }
            if (isset($_SESSION['error']) || isset($_SESSION['error2']) || isset($_SESSION['error3'])) {
                header("location:edit_book.php?bookid=" . $bookid);
            }
        }

    } 



    // Update the file destination to the new folder
    if ($file_name == null) {
        $cover = $book['book_cover'];
    } else {
        $cover = $new_folder1 . '/' . $file_name;
    }
    move_uploaded_file($file_tmp, $cover);

    if ($file_name2 == null) {
        $content = $book['book_content'];
    } else {
        $content = $new_folder2 . '/' . $file_name2;
    }

    move_uploaded_file($file_tmp2, $content);

    if ($file_name3 == null) {
        $testread = $book['book_test'];
    } else {
        $testread = $new_folder3 . '/' . $file_name3;
    }

    move_uploaded_file($file_tmp3, $testread);

    // update the new file path into the database
    $col = "book_id = '$bookid',book_name='$bname',book_cover = '$cover',book_content = '$content'
    ,book_test = '$testread',book_summary = '$summary',book_price = '$price'";
    $where = "book_id = '$bookid'";
    $result = updatedata("book", $col, $where);

    if (!isset($result)) {
        die(mysqli_error(connectdb()));
    } else {
        foreach ($type_book as $type_books) {
            $result2 = deletedata("book_type", "btype_bookid = '$bookid'");
        }
        foreach ($type_book as $type_books) {
            $col_type = "btype_bookid,btype_typeid";
            $values_type = "'$bookid','$type_books'";
            $result3 = insertdata("book_type", $col_type, $values_type);
        }
        if (!isset($result3)) {
            die(mysqli_error(connectdb()));
        } else {
            $tagarr = array();
            $sqltag = "select * from tag inner join book_tag on tag_id = btag_tagid
    inner join book on book_id = btag_bookid
    where btag_bookid = '$bookid'";
            $ex_tag = connectdb()->query($sqltag);
            if ($ex_tag->num_rows > 0) {
                while ($row = $ex_tag->fetch_assoc()) {
                    $tagarr[] = $row['tag_id'];
                }
                foreach ($tag as $tag_books) {
                    $result4 = deletedata("book_tag", "btag_bookid = '$bookid'");
                }
                if (!$result4) {
                    die(mysqli_error(connectdb()));
                } else {
                    foreach ($tagarr as $tag_id) {
                        $result5 = deletedata("tag", "tag_id = '$tag_id'");
                    }
                    if (!$result5) {
                        die(mysqli_error(connectdb()));
                    } else {
                        foreach ($tag as $tag_books) {
                            $lasttagid = tagautoid();

                            $result6 = insertdata("tag", "tag_id,tag_name", "'$lasttagid','$tag_books'");
                            $result7 = insertdata("book_tag", "btag_bookid,btag_tagid", "'$bookid','$lasttagid'");
                        }
                        if (!$result6 && !$result7) {
                            die(mysqli_error(connectdb()));
                        } else {
                            echo '
    <script>
    sweetalerts("บันทึกข้อมูลสำเร็จ!!","success","","draf.php");
    </script>
    ';
                        }
                    }
                }
            }
        }
    }



}

connectdb()->close();