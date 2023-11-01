<?php
include "function.php";
connectdb();
session_start();
if (isset($_GET['bookid']) && isset($_SESSION['cusid'])) {

    echo "<script> src ='https://code.jquery.com/jquery-3.6.1.min.js' 
    </script>
    <script src = 'https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.min.js'></script>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css'/>";

    echo "<script src='function.js'></script>";

    $i = 0;
    $bookid = $_GET['bookid'];
    $cusid = $_SESSION['cusid'];
    $total = 0;
    


    $sqlbook_shelf = "select * from bookshelf
    where bshelf_bookid = '$bookid' and bshelf_cusid = '$cusid' and bshelf_status = '0'";
    $ex1 = connectdb()->query($sqlbook_shelf);

    if (isset($_SESSION['coin']) && isset($_GET['discount'])) {
        $coin = $_SESSION['coin'];
        $total = $_GET['discount'];
        $discount = $_GET['discount'];
        $newcoin = floatval($coin);
        $newcoin -= $total;
    } elseif (isset($_SESSION['coin']) && isset($_GET['price'])) {
        $coin = $_SESSION['coin'];
        $total = $_GET['price'];
        $price = $_GET['price'];
        $newcoin = floatval($coin);
        $newcoin -= $total;
    }
    //กรณีมีสินค้าในชั้นหนังสือ
    if ($ex1->num_rows > 0) {
        $sqlcart = select_where("*", "cart", "cart_cusid = '$cusid' and cart_bookid = '$bookid'");
        //กรณีมีสินค้าในตะกร้าสินค้า
        if ($sqlcart->num_rows > 0) {
            $row = $sqlcart->fetch_assoc();
            $paybook = $row['cart_bookid'];

            $i = 1;

            $sqlupcoin = "update customer set cus_coin = '$newcoin' where cus_id = '$cusid'";
            $excoin = connectdb()->query($sqlupcoin);

            //query lastid
            $lastreceiptid = receiptautoid();

            $sqlins_receipt = "insert into receipt (rec_id,rec_total,rec_date,rec_cusid)
            values ('$lastreceiptid','$total',NOW(),'$cusid')";
            $result = connectdb()->query($sqlins_receipt);

            if (!$result){
                die(mysqli_error(connectdb()));
            }
            else{
                $sqlpro = "select *,book_price - pro_discount as discount,date_format(pro_edate,'%d/%m/%Y') as pro_edate
                from promotion inner join bookpro on pro_id = bpro_proid 
                inner join book on bpro_bookid = book_id
                inner join publisher on pub_id = book_pubid
                where book_status = '2' and pro_edate >= CURDATE()+ INTERVAL 1 DAY
                and bpro_bookid = '$bookid'
                ORDER BY pro_sdate DESC LIMIT 10";

                $ex_pro = connectdb()->query($sqlpro);
                if ($ex_pro->num_rows > 0) {
                    $sqlins_detail = "insert into receipt_detail (recd_no,recd_recid,recd_bookid,recd_price)
                    values ('$i','$lastreceiptid','$bookid','$discount')";
                    $result2 = connectdb()->query($sqlins_detail);
                } else {
                    $sqlins_detail = "insert into receipt_detail (recd_no,recd_recid,recd_bookid,recd_price)
                    values ('$i','$lastreceiptid','$bookid','$price')";
                    $result2 = connectdb()->query($sqlins_detail);
                }

                if (!$result2) {
                    die(mysqli_error(connectdb()));
                } else {
                    $sqlupdate_shelf = "update bookshelf set bshelf_status = '1' 
                    where bshelf_bookid = '$bookid' and bshelf_cusid = '$cusid'";
                    $result3 = connectdb()->query($sqlupdate_shelf);

                    if (!$result3) {
                        die(mysqli_error(connectdb()));
                    } else {
                        $sqldel_cart = "delete from cart where cart_cusid = '$cusid' and cart_bookid = '$paybook'";
                        $result4 = connectdb()->query($sqldel_cart);

                        if (!$result4) {
                            die(mysqli_error(connectdb()));
                        } else {
                            if (isset($_SESSION['coin'])) {
                                // ลบข้อมูล session 
                                unset($_SESSION["coin"]);
                            }
                            echo '
                                <script>
                                    sweetalerts("สั่งซื้อสำเร็จ!!","success","","mybook.php");
                                </script>
                                                    ';
                        }
                    }
                }
            } 
        }
        //กรณีไม่มีสินค้าในตะกร้าสินค้า
        else {
            $i = 1;

            $sqlupcoin = "update customer set cus_coin = '$newcoin' where cus_id = '$cusid'";
            $excoin = connectdb()->query($sqlupcoin);

            //query lastid
            $lastreceiptid = receiptautoid();

            $sqlins_receipt = "insert into receipt (rec_id,rec_total,rec_date,rec_cusid)
            values ('$lastreceiptid','$total',NOW(),'$cusid')";
            $result = connectdb()->query($sqlins_receipt);

            if (!$result){
                die(mysqli_error(connectdb()));
            }
            else{
                $sqlpro = "select *,book_price - pro_discount as discount,date_format(pro_edate,'%d/%m/%Y') as pro_edate
                from promotion inner join bookpro on pro_id = bpro_proid 
                inner join book on bpro_bookid = book_id
                inner join publisher on pub_id = book_pubid
                where book_status = '2' and pro_edate >= CURDATE()+ INTERVAL 1 DAY
                and bpro_bookid = '$bookid'
                ORDER BY pro_sdate DESC LIMIT 10";

                $ex_pro = connectdb()->query($sqlpro);
                if ($ex_pro->num_rows > 0) {
                    $sqlins_detail = "insert into receipt_detail (recd_no,recd_recid,recd_bookid,recd_price)
                    values ('$i','$lastreceiptid','$bookid','$discount')";
                    $result2 = connectdb()->query($sqlins_detail);
                } else {
                    $sqlins_detail = "insert into receipt_detail (recd_no,recd_recid,recd_bookid,recd_price)
                    values ('$i','$lastreceiptid','$bookid','$price')";
                    $result2 = connectdb()->query($sqlins_detail);
                }

                if (!$result2) {
                    die(mysqli_error(connectdb()));
                } else {
                    $sqlupdate_shelf = "update bookshelf set bshelf_status = '1' 
                    where bshelf_bookid = '$bookid' and bshelf_cusid = '$cusid'";
                    $result3 = connectdb()->query($sqlupdate_shelf);

                    if (!$result3) {
                        die(mysqli_error(connectdb()));
                    } else {
                        if (isset($_SESSION['coin'])) {
                            // ลบข้อมูล session 
                            unset($_SESSION["coin"]);
                        }
                        echo '
                            <script>
                                sweetalerts("สั่งซื้อสำเร็จ!!","success","","mybook.php");
                            </script>
                            ';
                    }
                }
            } 
        
        }
    }
     //กรณีไม่มีสินค้าในชั้นหนังสือ
    else{
            
        $sqlinsert_shelf = "insert into bookshelf (bshelf_bookid,bshelf_cusid,bshelf_status)
        values ('$bookid','$cusid','0')";
        $result = connectdb()->query($sqlinsert_shelf);

        $sqlcart = select_where("*", "cart", "cart_cusid = '$cusid' and cart_bookid = '$bookid'");
        //กรณีมีสินค้าในตะกร้า
        if ($sqlcart->num_rows > 0) {
            $row = $sqlcart->fetch_assoc();
            $paybook = $row['cart_bookid'];

            $i = 1;

            $sqlupcoin = "update customer set cus_coin = '$newcoin' where cus_id = '$cusid'";
            $excoin = connectdb()->query($sqlupcoin);

            //query lastid
            $lastreceiptid = receiptautoid();

            $sqlins_receipt = "insert into receipt (rec_id,rec_total,rec_date,rec_cusid)
            values ('$lastreceiptid','$total',NOW(),'$cusid')";
            $result = connectdb()->query($sqlins_receipt);

            if (!$result){
                die(mysqli_error(connectdb()));
            }
            else{
                $sqlpro = "select *,book_price - pro_discount as discount,date_format(pro_edate,'%d/%m/%Y') as pro_edate
                from promotion inner join bookpro on pro_id = bpro_proid 
                inner join book on bpro_bookid = book_id
                inner join publisher on pub_id = book_pubid
                where book_status = '2' and pro_edate >= CURDATE()+ INTERVAL 1 DAY
                and bpro_bookid = '$bookid'
                ORDER BY pro_sdate DESC LIMIT 10";

                $ex_pro = connectdb()->query($sqlpro);
                if ($ex_pro->num_rows > 0) {
                    $sqlins_detail = "insert into receipt_detail (recd_no,recd_recid,recd_bookid,recd_price)
                    values ('$i','$lastreceiptid','$bookid','$discount')";
                    $result2 = connectdb()->query($sqlins_detail);
                } else {
                    $sqlins_detail = "insert into receipt_detail (recd_no,recd_recid,recd_bookid,recd_price)
                    values ('$i','$lastreceiptid','$bookid','$price')";
                    $result2 = connectdb()->query($sqlins_detail);
                }

                if (!$result2) {
                    die(mysqli_error(connectdb()));
                } else {
                    $sqlupdate_shelf = "update bookshelf set bshelf_status = '1' 
                    where bshelf_bookid = '$bookid' and bshelf_cusid = '$cusid'";
                    $result3 = connectdb()->query($sqlupdate_shelf);

                    if (!$result3) {
                        die(mysqli_error(connectdb()));
                    } else {
                        $sqldel_cart = "delete from cart where cart_cusid = '$cusid' and cart_bookid = '$paybook'";
                        $result4 = connectdb()->query($sqldel_cart);

                        if (!$result4) {
                            die(mysqli_error(connectdb()));
                        } else {
                            if (isset($_SESSION['coin'])) {
                                // ลบข้อมูล session 
                                unset($_SESSION["coin"]);
                            }
                            echo '
                                <script>
                                    sweetalerts("สั่งซื้อสำเร็จ!!","success","","mybook.php");
                                </script>
                                                    ';
                        }
                    }
                }
            } 
        }
        //กรณีไม่มีสินค้าในตะกร้า
        else{
            $i = 1;

            $sqlupcoin = "update customer set cus_coin = '$newcoin' where cus_id = '$cusid'";
            $excoin = connectdb()->query($sqlupcoin);

            //query lastid
            $lastreceiptid = receiptautoid();

            $sqlins_receipt = "insert into receipt (rec_id,rec_total,rec_date,rec_cusid)
            values ('$lastreceiptid','$total',NOW(),'$cusid')";
            $result = connectdb()->query($sqlins_receipt);

            if (!$result){
                die(mysqli_error(connectdb()));
            }
            else{
                $sqlpro = "select *,book_price - pro_discount as discount,date_format(pro_edate,'%d/%m/%Y') as pro_edate
                from promotion inner join bookpro on pro_id = bpro_proid 
                inner join book on bpro_bookid = book_id
                inner join publisher on pub_id = book_pubid
                where book_status = '2' and pro_edate >= CURDATE()+ INTERVAL 1 DAY
                and bpro_bookid = '$bookid'
                ORDER BY pro_sdate DESC LIMIT 10";

                $ex_pro = connectdb()->query($sqlpro);
                if ($ex_pro->num_rows > 0) {
                    $sqlins_detail = "insert into receipt_detail (recd_no,recd_recid,recd_bookid,recd_price)
                    values ('$i','$lastreceiptid','$bookid','$discount')";
                    $result2 = connectdb()->query($sqlins_detail);
                } else {
                    $sqlins_detail = "insert into receipt_detail (recd_no,recd_recid,recd_bookid,recd_price)
                    values ('$i','$lastreceiptid','$bookid','$price')";
                    $result2 = connectdb()->query($sqlins_detail);
                }

                if (!$result2) {
                    die(mysqli_error(connectdb()));
                } else {
                    $sqlupdate_shelf = "update bookshelf set bshelf_status = '1' 
                    where bshelf_bookid = '$bookid' and bshelf_cusid = '$cusid'";
                    $result3 = connectdb()->query($sqlupdate_shelf);

                    if (!$result3) {
                        die(mysqli_error(connectdb()));
                    } else {
                        if (isset($_SESSION['coin'])) {
                            // ลบข้อมูล session 
                            unset($_SESSION["coin"]);
                        }
                        echo '
                            <script>
                                sweetalerts("สั่งซื้อสำเร็จ!!","success","","mybook.php");
                            </script>
                            ';
                    }
                }
            } 
        
        }
    }

    connectdb()->close();
}
