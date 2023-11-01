<?php
include "function.php";
connectdb();
session_start();
$cusid = $_SESSION["cusid"];

require 'vendor/autoload.php';

use setasign\Fpdi;


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
    //query lastid
    $lastbookid = bookautoid();
    

    $bname = $_POST['bname'];
    $summary = $_POST['summary'];
    $price = $_POST['price'];
    $tag = $_POST['tag'];
    $type_book = $_POST['type_book'];

    //upload cover
    $file = $_FILES['file1'];
    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $file_size = $file['size'];
    $file_error = $file['error'];

    //upload pdf
    $file2 = $_FILES['file2'];
    $file_name2 = $file2['name'];
    $file_tmp2 = $file2['tmp_name'];
    $file_size2 = $file2['size'];
    $file_error2 = $file2['error'];


    //upload test pdf
    $file3 = $_FILES['file3'];
    $file_name3 = $file3['name'];
    $file_tmp3 = $file3['tmp_name'];
    $file_size3 = $file3['size'];
    $file_error3 = $file3['error'];


    // Get the current month and year
    $current_month = date('m');
    $current_year = date('Y');

    // Create a new folder using the current month and year
    $new_folder1 = 'uploads/' . $pubid;
    $folder1 = $new_folder1 . '/' . $current_year . '/' . $current_month;
    if (!file_exists($folder1)) {
        mkdir($folder1, 0777, true);
    }

    // Create a new folder using the current month and year
    $new_folder2 = 'pdf/' . $pubid;
    $folder2 = $new_folder2 . '/' . $current_year . '/' . $current_month;
    if (!file_exists($folder2)) {
        mkdir($folder2, 0777, true);
    }

    // Create a new folder using the current month and year
    $new_folder3 = 'test/' . $pubid;
    $folder3 = $new_folder3 . '/' . $current_year . '/' . $current_month;
    if (!file_exists($folder3)) {
        mkdir($folder3, 0777, true);
    }

    if ($file_error === 0 && $file_error2 === 0 && $file_error3 === 0) {
        // Check the file type
        $file_type1 = exif_imagetype($file_tmp);
        $allowed_types1 = array(IMAGETYPE_JPEG, IMAGETYPE_PNG);
        if (!in_array($file_type1, $allowed_types1)) {
            $_SESSION['error'] = 'Only JPEG and PNG files are allowed.';
        }
        else{
            unset($_SESSION['error']);
        }

        // Check the file type
        $file_type2 = mime_content_type($file_tmp2);
        if ($file_type2 !== 'application/pdf') {
            $_SESSION['error2'] = 'Only PDF files are allowed.';   
        }
        else{
            unset($_SESSION['error2']);
        }

        // Check the file type
        $file_type3 = mime_content_type($file_tmp3);
        if ($file_type3 !== 'application/pdf') {
            $_SESSION['error3'] = 'Only PDF files are allowed.';
        }
        else{
            unset($_SESSION['error3']);
        }

        // Check PDF size for the second file
        if (isset($file_tmp2) && file_exists($file_tmp2)) {
            $pdf2 = new Fpdi\Fpdi();
            $pageCount2 = $pdf2->setSourceFile($file_tmp2);
            $template2 = $pdf2->importPage(1);
            $size2 = $pdf2->getTemplateSize($template2);
            $width2 = round($size2['width']);
            $height2 = round($size2['height']);

            if ($width2 != 210 || $height2 != 297) {
                $_SESSION['error2'] = 'The PDF file must be A4 size.';
            } else {
                unset($_SESSION['error2']);
            }
        }

        // Check PDF size for the third file
        if (isset($file_tmp3) && file_exists($file_tmp3)) {
            $pdf3 = new Fpdi\Fpdi();
            $pageCount3 = $pdf3->setSourceFile($file_tmp3);
            $template3 = $pdf3->importPage(1);
            $size3 = $pdf3->getTemplateSize($template3);
            $width3 = round($size2['width']);
            $height3 = round($size2['height']);
            if ($width3 != 210 || $height3 != 297) {
                $_SESSION['error3'] = 'The PDF file must be A4 size.';
            } else {
                unset($_SESSION['error3']);
            }
        }

        if (isset($_SESSION['error']) || isset($_SESSION['error2']) || isset($_SESSION['error3'])){
            header("location:add_book.php");
        }
        else{
            if (in_array($file_type1, $allowed_types1) && isset($file_type2) && isset($file_type3)) {
                // Update the file destination to the new folder
                $cover = $folder1 . '/' . $file_name;
                move_uploaded_file($file_tmp, $cover);
    
    
                $content = $folder2 . '/' . $file_name2;
                move_uploaded_file($file_tmp2, $content);
    
                $testread = $folder3 . '/' . $file_name3;
                move_uploaded_file($file_tmp3, $testread);
    
                // Insert the new file path into the database
                $col = "book_id,book_name,book_cover,book_summary
                ,book_price,book_content,book_test,book_dateup,book_app,book_status,book_empid,book_pubid";
    
                $values = "'$lastbookid','$bname','$cover','$summary','$price',
                '$content','$testread',NOW(),NULL,'0',NULL,'$pubid'";
                $result = insertdata("book", $col, $values);
    
                if (!isset($result)) {
                    die(mysqli_error(connectdb()));
                } else {
                    foreach ($tag as $tags) {
                        $lasttagid = tagautoid();
                        $result2 = insertdata("tag", "tag_id,tag_name", "'$lasttagid','$tags'");
                        $result3 = insertdata("book_tag", "btag_bookid,btag_tagid", "'$lastbookid','$lasttagid'");
                    }
                }
                if (isset($result) && isset($result2) && isset($result3)) {
                    foreach ($type_book as $type_books) {
                        $col_type = "btype_bookid,btype_typeid";
                        $values_type = "'$lastbookid','$type_books'";
                        $result4 = insertdata("book_type", $col_type, $values_type);
                    }
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

connectdb()->close();