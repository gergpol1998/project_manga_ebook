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
$i = 0;


if (isset($_SESSION['coin']) && isset($_SESSION['total'])) {
    $coin = $_SESSION['coin'];
    $total = $_SESSION['total'];
    
}
$sqlcart = "select *from book inner join cart on book_id = cart_bookid
where book_status = '2' and cart_cusid = '$cusid'";
$ex_carts = connectdb()->query($sqlcart);
if ($ex_carts->num_rows > 0) {
    $newcoin = floatval($coin);

    $newcoin -= $total;
    $sqlupcoin = "update customer set cus_coin = '$newcoin' where cus_id = '$cusid'";
    $excoin = connectdb()->query($sqlupcoin);

    //query lastid
    $lastreceiptid = receiptautoid();

    $sqlins_receipt = "insert into receipt (rec_id,rec_total,rec_date,rec_cusid)
    values ('$lastreceiptid','$total',NOW(),'$cusid')";
    $result3 = connectdb()->query($sqlins_receipt);

    while ($row = $ex_carts->fetch_assoc()) {
        $bookid = $row['book_id'];
                           
        $sqlcart_pro = "select *,book_price - pro_discount as discount
        from promotion inner join bookpro on pro_id = bpro_proid 
        inner join book on bpro_bookid = book_id
        where bpro_bookid = '$bookid' and book_status = '2' and pro_edate >= CURDATE()+ INTERVAL 1 DAY";
        $ex_cartpro = connectdb()->query($sqlcart_pro);

        
        //มีโปรโมชั่น
        if ($ex_cartpro->num_rows > 0){
            $row2 = $ex_cartpro->fetch_assoc();
            
            $discount = $row2['discount'];


            $sqlbook_shelf = "select * from bookshelf
            where bshelf_bookid = '$bookid' and bshelf_cusid = '$cusid' and bshelf_status = '0'";
            $result2 = connectdb()->query($sqlbook_shelf);
            //กรณีมีสินค้าในชั้นหนังสือ
            if ($result2->num_rows > 0) {
                $i++;

                if (!$result3) {
                    die(mysqli_error(connectdb()));
                } else {
                    $sqlpro = "select *,book_price - pro_discount as discount,date_format(pro_edate,'%d/%m/%Y') as pro_edate
                    from promotion inner join bookpro on pro_id = bpro_proid 
                    inner join book on bpro_bookid = book_id
                    inner join publisher on pub_id = book_pubid
                    where book_status = '2' and pro_edate >= CURDATE()+ INTERVAL 1 DAY
                    and bpro_bookid = '$bookid'
                    ORDER BY pro_sdate DESC LIMIT 10";
                    $ex_pro = connectdb()->query($sqlpro);
                    if ($ex_pro->num_rows > 0){
                        
                            $sqlins_detail = "insert into receipt_detail (recd_no,recd_recid,recd_bookid,recd_price)
                            values ('$i','$lastreceiptid','$bookid','$discount')";
                            $result4 = connectdb()->query($sqlins_detail);
                    }
                    
                    if (!$result4) {
                        die(mysqli_error(connectdb()));
                    } else {
                        $sqlupdate_shelf = "update bookshelf set bshelf_status = '1' 
                        where bshelf_bookid = '$bookid' and bshelf_cusid = '$cusid'";
                        $result5 = connectdb()->query($sqlupdate_shelf);

                        if (!$result5) {
                            die(mysqli_error(connectdb()));
                        }
                        else {
                            $sqldel_cart = "delete from cart where cart_cusid = '$cusid'";
                            $result6 = connectdb()->query($sqldel_cart);

                            if (!$result6) {
                                die(mysqli_error(connectdb()));
                            } 
                            else {
                                // ตรวจสอบว่ามี session ที่เก็บอยู่หรือไม่
                                if (isset($_SESSION['coin'])) {
                                    // ลบข้อมูล session 
                                    unset($_SESSION["coin"]);
                                    unset($_SESSION['total']);
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
            else {
                $sqlinsert_shelf = "insert into bookshelf (bshelf_bookid,bshelf_cusid,bshelf_status)
                values ('$bookid','$cusid','0')";
                $result = connectdb()->query($sqlinsert_shelf);
                if (!$result) {
                    die(mysqli_error(connectdb()));
                } else {
                    $i++;

                    if (!$result2) {
                        die(mysqli_error(connectdb()));
                    } else {
                        $sqlpro = "select *,book_price - pro_discount as discount,date_format(pro_edate,'%d/%m/%Y') as pro_edate
                        from promotion inner join bookpro on pro_id = bpro_proid 
                        inner join book on bpro_bookid = book_id
                        inner join publisher on pub_id = book_pubid
                        where book_status = '2' and pro_edate >= CURDATE()+ INTERVAL 1 DAY
                        and bpro_bookid = '$bookid'
                        ORDER BY pro_sdate DESC LIMIT 10";
                        $ex_pro = connectdb()->query($sqlpro);
                    if ($ex_pro->num_rows > 0){
                        
                            $sqlins_detail = "insert into receipt_detail (recd_no,recd_recid,recd_bookid,recd_price)
                            values ('$i','$lastreceiptid','$bookid','$discount')";
                            $result3 = connectdb()->query($sqlins_detail);
                    }

                        if (!$result3) {
                            die(mysqli_error(connectdb()));
                        } else {
                            $sqlupdate_shelf = "update bookshelf set bshelf_status = '1' 
                            where bshelf_bookid = '$bookid' and bshelf_cusid = '$cusid'";
                            $result4 = connectdb()->query($sqlupdate_shelf);

                            if (!$result4) {
                                die(mysqli_error(connectdb()));
                            }
                            else {
                                $sqldel_cart = "delete from cart where cart_cusid = '$cusid'";
                                $result5 = connectdb()->query($sqldel_cart);

                                if (!$result5) {
                                    die(mysqli_error(connectdb()));
                                } 
                                else {
                                    // ตรวจสอบว่ามี session ที่เก็บอยู่หรือไม่
                                if (isset($_SESSION['coin'])) {
                                    // ลบข้อมูล session 
                                    
                                    unset($_SESSION["coin"]);
                                    unset($_SESSION['total']);
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
            }
        }
        //ไม่มีโปรโมชั่น
        else{
            $price =  $row['book_price'];

            $sqlbook_shelf = "select * from bookshelf
            where bshelf_bookid = '$bookid' and bshelf_cusid = '$cusid' and bshelf_status = '0'";
            $result2 = connectdb()->query($sqlbook_shelf);
            //กรณีมีสินค้าในชั้นหนังสือ
            if ($result2->num_rows > 0) {
                $i++;

                if (!$result3) {
                    die(mysqli_error(connectdb()));
                } else {
                    $sqlins_detail = "insert into receipt_detail (recd_no,recd_recid,recd_bookid,recd_price)
                    values ('$i','$lastreceiptid','$bookid','$price')";
                    $result4 = connectdb()->query($sqlins_detail);
                    
                    
                    if (!$result4) {
                        die(mysqli_error(connectdb()));
                    } else {
                        $sqlupdate_shelf = "update bookshelf set bshelf_status = '1' 
                        where bshelf_bookid = '$bookid' and bshelf_cusid = '$cusid'";
                        $result5 = connectdb()->query($sqlupdate_shelf);

                        if (!$result5) {
                            die(mysqli_error(connectdb()));
                        }
                        else {
                            $sqldel_cart = "delete from cart where cart_cusid = '$cusid'";
                            $result6 = connectdb()->query($sqldel_cart);

                            if (!$result6) {
                                die(mysqli_error(connectdb()));
                            } 
                            else {
                                // ตรวจสอบว่ามี session ที่เก็บอยู่หรือไม่
                                if (isset($_SESSION['coin'])) {
                                    // ลบข้อมูล session 
                                    unset($_SESSION["coin"]);
                                    unset($_SESSION['total']);
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
            else {
                $sqlinsert_shelf = "insert into bookshelf (bshelf_bookid,bshelf_cusid,bshelf_status)
                values ('$bookid','$cusid','0')";
                $result = connectdb()->query($sqlinsert_shelf);
                if (!$result) {
                    die(mysqli_error(connectdb()));
                } else {
                    $i++;

                    if (!$result2) {
                        die(mysqli_error(connectdb()));
                    } else {

                        $sqlins_detail = "insert into receipt_detail (recd_no,recd_recid,recd_bookid,recd_price)
                        values ('$i','$lastreceiptid','$bookid','$price')";
                        $result3 = connectdb()->query($sqlins_detail);

                        if (!$result3) {
                            die(mysqli_error(connectdb()));
                        } else {
                            $sqlupdate_shelf = "update bookshelf set bshelf_status = '1' 
                            where bshelf_bookid = '$bookid' and bshelf_cusid = '$cusid'";
                            $result4 = connectdb()->query($sqlupdate_shelf);

                            if (!$result4) {
                                die(mysqli_error(connectdb()));
                            }
                            else {
                                $sqldel_cart = "delete from cart where cart_cusid = '$cusid'";
                                $result5 = connectdb()->query($sqldel_cart);

                                if (!$result5) {
                                    die(mysqli_error(connectdb()));
                                } 
                                else {
                                    // ตรวจสอบว่ามี session ที่เก็บอยู่หรือไม่
                                if (isset($_SESSION['coin'])) {
                                    // ลบข้อมูล session 
                                    
                                    unset($_SESSION["coin"]);
                                    unset($_SESSION['total']);
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
            }
        }
    }
}
connectdb()->close();
