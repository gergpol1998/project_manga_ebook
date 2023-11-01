<?php
session_start();
if (isset($_GET['typeid']) && isset($_GET['typename'])) {
    $typeid = $_GET['typeid'];
    $typename = $_GET['typename'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</head>

<body>
    <?php
    include "nav.php";
    ?>
    <div class="container px-4 px-lg-5 mt-3">
        <h3><?php echo $typename ?></h3>
        <h3>โปรโมชั่น</h3><a href="display_all.php">
            <h5>ดูทั้งหมด</h5>
        </a>
        <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 ">
            <?php
            $sqlpro = "select *,book_price - pro_discount as discount,date_format(pro_edate,'%d/%m/%Y') as pro_edate
            from promotion inner join bookpro on pro_id = bpro_proid 
            inner join book on bpro_bookid = book_id
            inner join publisher on pub_id = book_pubid
            inner join book_type on btype_bookid = book_id
            inner join typebook on type_id = btype_typeid
            where book_status = '2' and pro_edate >= CURDATE()+ INTERVAL 1 DAY
            and type_id = '$typeid'
            ORDER BY pro_sdate DESC LIMIT 12";
            $ex_pro = connectdb()->query($sqlpro);

            if ($ex_pro->num_rows > 0) {
                while ($row = $ex_pro->fetch_assoc()) {
                    $bookid = $row['book_id'];

            ?>
                    <div class="col sm-3">
                        <div class="text-center mb-3">
                            <img src="<?php echo $row['book_cover'] ?>" class="card-img-top" width="200px" height="250px">
                            <?php
                            $sqlrate = "CALL GetBookRatings('$bookid')";
                            $ex_rate = connectdb()->query($sqlrate);
                            if ($ex_rate->num_rows > 0) {
                                $row2 = $ex_rate->fetch_assoc();
                                $rating = $row2['rating'];
                            } else {
                                $rating = 0;
                            }
                            ?>
                            <h5 class="card-title text-center">คะแนน <?php echo round($rating) ?>/5</h5>

                            <h5 class="card-title text-center text-danger">โปร <?php echo $row['pro_name'] ?>
                                ลดถึงวันที่ <?php echo $row['pro_edate'] ?></h5>
                            <h5 class="card-title text-center">ชื่อเรื่อง</h5>
                            <h5 class="card-title text-center text-success"><?php echo $row['book_name'] ?></h5>
                            <h5 class="card-title text-center">ราคา</h5>
                            <del class='text-danger'><?php echo number_format($row['book_price'], 2) ?></del> <i class="fas fa-coins"></i>
                            <h5 class="card-text text-center text-danger"><?php echo number_format($row['discount'], 2) ?> <i class="fas fa-coins"></i></h5>
                            <h5 class="card-title text-center">ผู้เผยแพร่</h5>
                            <h5 class="card-text text-center text-success"><?php echo $row['pub_name'] ?></h5>
                            <?php
                            if (isset($cusid)) {

                                $sqlcus = select_where("cus_coin", "customer", "cus_id = '$cusid'");
                                if ($sqlcus->num_rows > 0) {
                                    $row2 = $sqlcus->fetch_assoc();

                                    $sqlcheck = select_where("*", "bookshelf", "bshelf_cusid = '$cusid' and bshelf_bookid = '" . $row['book_id'] . "' and bshelf_status = '1'");
                                    if ($sqlcheck->num_rows > 0) {
                                        $id = $row['book_id'];
                                        echo "<a href='readbook.php?bookid=$id'><button class='btn btn-primary mb-2' >อ่าน</button></a>";
                                    } else {
                                        if ($row2['cus_coin'] < $row['discount']) {
                                            echo '<script>
                                                            function checkcoin(mycoin) {
                                                                let conf = confirm("เหรียญไม่พอต้องเติมเหรียญก่อน");
                                                                if (conf) {
                                                                    window.location = mycoin;
                                                                }
                                                            }
                                                        </script>';
                                            echo '<a onclick="checkcoin(this.href); return false;" href="add_coin.php" class="btn btn-danger mb-2">ชำระเงิน</a>';
                                        } else {

                                            $_SESSION['coin'] = $row2['cus_coin'];
                                            $sqlcheck = select_where("*", "bookshelf", "bshelf_cusid = '$cusid' and bshelf_bookid = '" . $row['book_id'] . "' and bshelf_status = '1'");

                                            if ($sqlcheck->num_rows > 0) {
                            ?>

                                            <?php
                                            } else {

                                            ?>
                                                <a href="insert_pay.php?bookid=<?php echo $row['book_id'] ?>&discount=<?php echo $row['discount'] ?>" class="btn btn-danger mb-2">ชำระเงิน</a>
                                <?php
                                            }
                                        }
                                    }
                                }

                                ?>
                                <?php
                                $sql = select_where("*", "bookshelf", "bshelf_cusid = '$cusid' and bshelf_bookid = '" . $row['book_id'] . "' and bshelf_status = '1'");
                                $sqlcart = select_where("*", "cart", "cart_cusid = '$cusid' and cart_bookid = '" . $row['book_id'] . "'");

                                if ($sql->num_rows > 0 || $sqlcart->num_rows > 0) {

                                ?>

                                <?php
                                } else {

                                ?>

                                    <a href="insert_cart.php?bookid=<?php echo $row['book_id'] ?>" class="btn btn-primary mb-2">เพิ่มเข้าตะกร้า</a>

                                <?php
                                }
                                $sqlshelf = "select * from bookshelf
                                        where bshelf_bookid = '" . $row['book_id'] . "' and bshelf_cusid = '$cusid'";
                                $result = connectdb()->query($sqlshelf);
                                if ($result->num_rows > 0) {

                                ?>

                                <?php
                                } else {
                                ?>
                                    <a href="insert_shelf.php?bookid=<?php echo $row['book_id'] ?>" class="btn btn-warning">เพิ่มเข้าชั้นหนังสือ</a>
                                <?php
                                }
                                ?>

                            <?php
                            } else {

                            ?>
                                <script>
                                    function register(mypage2) {
                                        let conf = confirm("ต้องเป็นสมาชิกก่อน");
                                        if (conf) {
                                            window.location = mypage2;
                                        }
                                    }
                                </script>
                                <a onclick="register(this.href); return false;" href="login.php" class="btn btn-danger mb-2">ชำระเงิน</a>
                                <a onclick="register(this.href); return false;" href="login.php" class="btn btn-primary mb-2">เพิ่มเข้าตะกร้า</a>
                                <a onclick="register(this.href); return false;" href="login.php" class="btn btn-warning">เพิ่มเข้าชั้นหนังสือ</a>
                            <?php
                            }
                            ?>
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#<?php echo $row['book_id'] ?>">เรื่องย่อ</button>
                            <!-- Modal -->
                            <div class="modal fade" id="<?php echo $row['book_id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">เรื่องย่อ</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <img src="<?php echo $row['book_cover'] ?>" width="200px" height="250px" class="mt-5 p-2 my-2 border">
                                            <?php
                                            echo "<h5>ให้คะแนนเรื่องนี้</h5>";
                                            if (!isset($_SESSION['cusid'])) {
                                                foreach (range(1, 5) as $rating) {
                                                    echo "<a onclick='register(this.href); return false;' href='login.php'> <i class='fas fa-star'><i hidden>$rating</i></i> </a>";
                                                }
                                            } else {
                                                $receipt_sql = "SELECT * 
                                                FROM receipt
                                                JOIN receipt_detail ON receipt.rec_id = receipt_detail.recd_recid
                                                WHERE receipt.rec_cusid = '" . $_SESSION['cusid'] . "' AND receipt_detail.recd_bookid = '" . $row['book_id'] . "'";
                                                $ex_receipt = connectdb()->query($receipt_sql);
                                                if ($ex_receipt->num_rows > 0){
                                                    foreach (range(1, 5) as $rating) {
                                                        echo "<a href='rate.php?bookid=" . $row['book_id'] . "&rate=$rating'> <i class='fas fa-star'><i hidden>$rating</i></i> </a>";
                                                    }
                                                }
                                                else{
                                                    foreach (range(1, 5) as $rating) {
                                                        echo "<a onclick='mustbuy(this.href); return false;' href='index.php'> <i class='fas fa-star'><i hidden>$rating</i></i> </a>";
                                                    }
                                                }
                                            }
                                            echo "<h5>ชื่อเรื่อง</h5>";
                                            echo "<h4>" . $row['book_name'] . "</h4>";
                                            echo "<h5>ราคา</h5>";
                                            echo "<del class = 'text-danger'>" . number_format($row['book_price'], 2) . "</del> <i class='fas fa-coins'></i><h4 class= 'text-danger'>" .  number_format($row['discount'], 2) . " <i class='fas fa-coins'></i></h4>";
                                            echo "<h5>เนื้อเรื่องย่อ</h5>";
                                            echo "<textarea class='form-control'>" . $row['book_summary'] . "</textarea>";
                                            echo "<h5>ผู้เผยแพร่</h5>";
                                            echo "<h4>" . $row['pub_name'] . "</h4>";
                                            echo "<a href='testread.php?bookid=" . $row['book_id'] . "'><button class='btn btn-primary'>ทดลองอ่าน</button></a>";
                                            echo "<a href='mypage.php?pubid=" . $row['book_pubid'] . "'><button class='btn btn-success'>หน้าร้าน</button></a>";
                                            ?>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo "<h2>ไม่มีโปรโมชั่น</h2>";
            }
            ?>
        </div>
        <h3>ขายดีสูงสุดประจำเดือน</h3><a href="display_all.php">
            <h5>ดูทั้งหมด</h5>
        </a>
        <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 ">
            <?php
            $col = "*,count(recd_bookid)";
            $table = "book
            INNER JOIN receipt_detail ON book.book_id = receipt_detail.recd_bookid
            INNER JOIN receipt ON receipt.rec_id = receipt_detail.recd_recid
            INNER JOIN publisher ON publisher.pub_id = book.book_pubid
            INNER JOIN customer ON customer.cus_id = publisher.pub_cusid
            left outer join bookpro on  book_id =  bpro_bookid
            inner join book_type on btype_bookid = book.book_id
            inner join typebook on type_id = btype_typeid";
            $where = "book_status = '2' and DATE_FORMAT(receipt.rec_date,'%m') = DATE_FORMAT(CURDATE(), '%m')
            and type_id = '$typeid'
            GROUP BY book_id,bpro_proid
            ORDER BY count(recd_bookid) DESC LIMIT 12";
            $sqlbook = select_where($col, $table, $where);
            if ($sqlbook->num_rows > 0) {
                while ($row = $sqlbook->fetch_assoc()) {
                    $bookid = $row['book_id'];
                    $proid = $row['bpro_proid'];

                    $sqlpro = "CALL GetSpecificPromotionBook('$bookid','$proid')";
                    $ex_pro = connectdb()->query($sqlpro);
                    if ($ex_pro->num_rows > 0) {
                        $row = $ex_pro->fetch_assoc();
            ?>
                        <div class="col sm-3">
                            <div class="text-center mb-3">
                                <img src="<?php echo $row['book_cover'] ?>" class="card-img-top" width="200px" height="250px">
                                <?php
                                $sqlrate = "CALL GetBookRatings('$bookid')";
                                $ex_rate = connectdb()->query($sqlrate);
                                if ($ex_rate->num_rows > 0) {
                                    $row2 = $ex_rate->fetch_assoc();
                                    $rating = $row2['rating'];
                                } else {
                                    $rating = 0;
                                }
                                ?>
                                <h5 class="card-title text-center">คะแนน <?php echo round($rating) ?>/5</h5>
                                <h5 class="card-title text-center text-danger">โปร <?php echo $row['pro_name'] ?>
                                    <h5 class="card-title text-center">ชื่อเรื่อง</h5>
                                    <h5 class="card-title text-center text-success"><?php echo $row['book_name'] ?></h5>
                                    <h5 class="card-title text-center">ราคา</h5>
                                    <del class='text-danger'><?php echo number_format($row['book_price'], 2) ?></del> <i class="fas fa-coins"></i>
                                    <h5 class="card-text text-center text-danger"><?php echo number_format($row['discount'], 2) ?> <i class="fas fa-coins"></i></h5>
                                    <h5 class="card-title text-center">ผู้เผยแพร่</h5>
                                    <h5 class="card-text text-center text-success"><?php echo $row['pub_name'] ?></h5>
                                    <?php
                                    if (isset($cusid)) {

                                        $sqlcus = select_where("cus_coin", "customer", "cus_id = '$cusid'");
                                        if ($sqlcus->num_rows > 0) {
                                            $row2 = $sqlcus->fetch_assoc();

                                            $sqlcheck = select_where("*", "bookshelf", "bshelf_cusid = '$cusid' and bshelf_bookid = '" . $row['book_id'] . "' and bshelf_status = '1'");
                                            if ($sqlcheck->num_rows > 0) {
                                                $id = $row['book_id'];
                                                echo "<a href='readbook.php?bookid=$id'><button class='btn btn-primary mb-2' >อ่าน</button></a>";
                                            } else {

                                                if ($row2['cus_coin'] < $row['discount']) {
                                                    echo '<script>
                                                            function checkcoin(mycoin) {
                                                                let conf = confirm("เหรียญไม่พอต้องเติมเหรียญก่อน");
                                                                if (conf) {
                                                                    window.location = mycoin;
                                                                }
                                                            }
                                                        </script>';
                                                    echo '<a onclick="checkcoin(this.href); return false;" href="add_coin.php" class="btn btn-danger mb-2">ชำระเงิน</a>';
                                                } else {

                                                    $_SESSION['coin'] = $row2['cus_coin'];
                                                    $sqlcheck = select_where("*", "bookshelf", "bshelf_cusid = '$cusid' and bshelf_bookid = '" . $row['book_id'] . "' and bshelf_status = '1'");

                                                    if ($sqlcheck->num_rows > 0) {
                                    ?>

                                                    <?php
                                                    } else {


                                                    ?>
                                                        <a href="insert_pay.php?bookid=<?php echo $row['book_id'] ?>&discount=<?php echo $row['discount'] ?>" class="btn btn-danger mb-2">ชำระเงิน</a>
                                        <?php
                                                    }
                                                }
                                            }
                                        }

                                        ?>
                                        <?php
                                        $sql = select_where("*", "bookshelf", "bshelf_cusid = '$cusid' and bshelf_bookid = '" . $row['book_id'] . "' and bshelf_status = '1'");
                                        $sqlcart = select_where("*", "cart", "cart_cusid = '$cusid' and cart_bookid = '" . $row['book_id'] . "'");
                                        if ($sql->num_rows > 0 || $sqlcart->num_rows > 0) {

                                        ?>

                                        <?php
                                        } else {

                                        ?>
                                            <a href="insert_cart.php?bookid=<?php echo $row['book_id'] ?>&pro=<?php echo $row['pro_id'] ?>" class="btn btn-primary mb-2">เพิ่มเข้าตะกร้า</a>
                                        <?php
                                        }
                                        $sqlshelf = "select * from bookshelf
                                        where bshelf_bookid = '" . $row['book_id'] . "' and bshelf_cusid = '$cusid'";
                                        $result = connectdb()->query($sqlshelf);
                                        if ($result->num_rows > 0) {

                                        ?>

                                        <?php
                                        } else {
                                        ?>
                                            <a href="insert_shelf.php?bookid=<?php echo $row['book_id'] ?>&pro=<?php echo $row['pro_id'] ?>" class="btn btn-warning">เพิ่มเข้าชั้นหนังสือ</a>
                                        <?php
                                        }
                                        ?>

                                    <?php
                                    } else {

                                    ?>
                                        <script>
                                            function register(mypage2) {
                                                let conf = confirm("ต้องเป็นสมาชิกก่อน");
                                                if (conf) {
                                                    window.location = mypage2;
                                                }
                                            }
                                        </script>
                                        <a onclick="register(this.href); return false;" href="login.php" class="btn btn-danger mb-2">ชำระเงิน</a>
                                        <a onclick="register(this.href); return false;" href="login.php" class="btn btn-primary mb-2">เพิ่มเข้าตะกร้า</a>
                                        <a onclick="register(this.href); return false;" href="login.php" class="btn btn-warning">เพิ่มเข้าชั้นหนังสือ</a>
                                    <?php
                                    }
                                    ?>
                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#<?php echo $row['book_id'] ?>">เรื่องย่อ</button>
                                    <!-- Modal -->
                                    <div class="modal fade" id="<?php echo $row['book_id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">เรื่องย่อ</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <img src="<?php echo $row['book_cover'] ?>" width="200px" height="250px" class="mt-5 p-2 my-2 border">
                                                    <?php
                                                    echo "<h5>ให้คะแนนเรื่องนี้</h5>";
                                                    if (!isset($_SESSION['cusid'])) {
                                                        foreach (range(1, 5) as $rating) {
                                                            echo "<a onclick='register(this.href); return false;' href='login.php'> <i class='fas fa-star'><i hidden>$rating</i></i> </a>";
                                                        }
                                                    } else {
                                                        $receipt_sql = "SELECT * 
                                                        FROM receipt
                                                        JOIN receipt_detail ON receipt.rec_id = receipt_detail.recd_recid
                                                        WHERE receipt.rec_cusid = '" . $_SESSION['cusid'] . "' AND receipt_detail.recd_bookid = '" . $row['book_id'] . "'";
                                                        $ex_receipt = connectdb()->query($receipt_sql);
                                                        if ($ex_receipt->num_rows > 0){
                                                            foreach (range(1, 5) as $rating) {
                                                                echo "<a href='rate.php?bookid=" . $row['book_id'] . "&rate=$rating'> <i class='fas fa-star'><i hidden>$rating</i></i> </a>";
                                                            }
                                                        }
                                                        else{
                                                            foreach (range(1, 5) as $rating) {
                                                                echo "<a onclick='mustbuy(this.href); return false;' href='index.php'> <i class='fas fa-star'><i hidden>$rating</i></i> </a>";
                                                            }
                                                        }
                                                    }
                                                    echo "<h5>ชื่อเรื่อง</h5>";
                                                    echo "<h4>" . $row['book_name'] . "</h4>";
                                                    echo "<h5>ราคา</h5>";
                                                    echo "<h4 class= 'text-danger'>" . number_format($row['book_price'], 2) . " <i class='fas fa-coins'></i></h4>";
                                                    echo "<h5>เนื้อเรื่องย่อ</h5>";
                                                    echo "<textarea class='form-control'>" . $row['book_summary'] . "</textarea>";
                                                    echo "<h5>ผู้เผยแพร่</h5>";
                                                    echo "<h4>" . $row['pub_name'] . "</h4>";
                                                    echo "<a href='testread.php?bookid=" . $row['book_id'] . "'><button class='btn btn-primary'>ทดลองอ่าน</button></a>";
                                                    echo "<a href='mypage.php?pubid=" . $row['book_pubid'] . "'><button class='btn btn-success'>หน้าร้าน</button></a>";
                                                    ?>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    <?php
                    } else {

                    ?>
                        <div class="col sm-3">
                            <div class="text-center mb-3">
                                <img src="<?php echo $row['book_cover'] ?>" class="card-img-top" width="200px" height="250px">

                                <?php
                                $sqlrate = "CALL GetBookRatings('$bookid')";
                                $ex_rate = connectdb()->query($sqlrate);
                                if ($ex_rate->num_rows > 0) {
                                    $row2 = $ex_rate->fetch_assoc();
                                    $rating = $row2['rating'];
                                } else {
                                    $rating = 0;
                                }
                                ?>
                                <h5 class="card-title text-center">คะแนน <?php echo round($rating) ?>/5</h5>

                                <h5 class="card-title text-center">ชื่อเรื่อง</h5>
                                <h5 class="card-title text-center text-success"><?php echo $row['book_name'] ?></h5>
                                <h5 class="card-title text-center">ราคา</h5>

                                <h5 class="card-text text-center text-danger"><?php echo number_format($row['book_price'], 2) ?> <i class="fas fa-coins"></i></h5>
                                <h5 class="card-title text-center">ผู้เผยแพร่</h5>
                                <h5 class="card-text text-center text-success"><?php echo $row['pub_name'] ?></h5>
                                <?php
                                if (isset($cusid)) {

                                    $sqlcus = select_where("cus_coin", "customer", "cus_id = '$cusid'");
                                    if ($sqlcus->num_rows > 0) {
                                        $row2 = $sqlcus->fetch_assoc();

                                        $sqlcheck = select_where("*", "bookshelf", "bshelf_cusid = '$cusid' and bshelf_bookid = '" . $row['book_id'] . "' and bshelf_status = '1'");
                                        if ($sqlcheck->num_rows > 0) {
                                            $id = $row['book_id'];
                                            echo "<a href='readbook.php?bookid=$id'><button class='btn btn-primary mb-2' >อ่าน</button></a>";
                                        } else {

                                            if ($row2['cus_coin'] < $row['book_price']) {
                                                echo '<script>
                                                            function checkcoin(mycoin) {
                                                                let conf = confirm("เหรียญไม่พอต้องเติมเหรียญก่อน");
                                                                if (conf) {
                                                                    window.location = mycoin;
                                                                }
                                                            }
                                                        </script>';
                                                echo '<a onclick="checkcoin(this.href); return false;" href="add_coin.php" class="btn btn-danger mb-2">ชำระเงิน</a>';
                                            } else {

                                                $_SESSION['coin'] = $row2['cus_coin'];
                                                $sqlcheck = select_where("*", "bookshelf", "bshelf_cusid = '$cusid' and bshelf_bookid = '" . $row['book_id'] . "' and bshelf_status = '1'");

                                                if ($sqlcheck->num_rows > 0) {
                                ?>

                                                <?php
                                                } else {


                                                ?>
                                                    <a href="insert_pay.php?bookid=<?php echo $row['book_id'] ?>&price=<?php echo $row['book_price'] ?>" class="btn btn-danger mb-2">ชำระเงิน</a>
                                    <?php
                                                }
                                            }
                                        }
                                    }

                                    ?>
                                    <?php
                                    $sql = select_where("*", "bookshelf", "bshelf_cusid = '$cusid' and bshelf_bookid = '" . $row['book_id'] . "' and bshelf_status = '1'");
                                    $sqlcart = select_where("*", "cart", "cart_cusid = '$cusid' and cart_bookid = '" . $row['book_id'] . "'");
                                    if ($sql->num_rows > 0 || $sqlcart->num_rows > 0) {

                                    ?>

                                    <?php
                                    } else {

                                    ?>
                                        <a href="insert_cart.php?bookid=<?php echo $row['book_id'] ?>" class="btn btn-primary mb-2">เพิ่มเข้าตะกร้า</a>
                                    <?php
                                    }
                                    $sqlshelf = "select * from bookshelf
                                        where bshelf_bookid = '" . $row['book_id'] . "' and bshelf_cusid = '$cusid'";
                                    $result = connectdb()->query($sqlshelf);
                                    if ($result->num_rows > 0) {

                                    ?>

                                    <?php
                                    } else {
                                    ?>
                                        <a href="insert_shelf.php?bookid=<?php echo $row['book_id'] ?>" class="btn btn-warning">เพิ่มเข้าชั้นหนังสือ</a>
                                    <?php
                                    }
                                    ?>

                                <?php
                                } else {

                                ?>
                                    <script>
                                        function register(mypage2) {
                                            let conf = confirm("ต้องเป็นสมาชิกก่อน");
                                            if (conf) {
                                                window.location = mypage2;
                                            }
                                        }
                                    </script>
                                    <a onclick="register(this.href); return false;" href="login.php" class="btn btn-danger mb-2">ชำระเงิน</a>
                                    <a onclick="register(this.href); return false;" href="login.php" class="btn btn-primary mb-2">เพิ่มเข้าตะกร้า</a>
                                    <a onclick="register(this.href); return false;" href="login.php" class="btn btn-warning">เพิ่มเข้าชั้นหนังสือ</a>
                                <?php
                                }
                                ?>
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#<?php echo $row['book_id'] ?>">เรื่องย่อ</button>
                                <!-- Modal -->
                                <div class="modal fade" id="<?php echo $row['book_id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">เรื่องย่อ</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <img src="<?php echo $row['book_cover'] ?>" width="200px" height="250px" class="mt-5 p-2 my-2 border">
                                                <?php
                                                echo "<h5>ให้คะแนนเรื่องนี้</h5>";
                                                if (!isset($_SESSION['cusid'])) {
                                                    foreach (range(1, 5) as $rating) {
                                                        echo "<a onclick='register(this.href); return false;' href='login.php'> <i class='fas fa-star'><i hidden>$rating</i></i> </a>";
                                                    }
                                                } else {
                                                    $receipt_sql = "SELECT * 
                                                    FROM receipt
                                                    JOIN receipt_detail ON receipt.rec_id = receipt_detail.recd_recid
                                                    WHERE receipt.rec_cusid = '" . $_SESSION['cusid'] . "' AND receipt_detail.recd_bookid = '" . $row['book_id'] . "'";
                                                    $ex_receipt = connectdb()->query($receipt_sql);
                                                    if ($ex_receipt->num_rows > 0){
                                                        foreach (range(1, 5) as $rating) {
                                                            echo "<a href='rate.php?bookid=" . $row['book_id'] . "&rate=$rating'> <i class='fas fa-star'><i hidden>$rating</i></i> </a>";
                                                        }
                                                    }
                                                    else{
                                                        foreach (range(1, 5) as $rating) {
                                                            echo "<a onclick='mustbuy(this.href); return false;' href='index.php'> <i class='fas fa-star'><i hidden>$rating</i></i> </a>";
                                                        }
                                                    }
                                                }
                                                echo "<h5>ชื่อเรื่อง</h5>";
                                                echo "<h4>" . $row['book_name'] . "</h4>";
                                                echo "<h5>ราคา</h5>";
                                                echo "<h4 class= 'text-danger'>" . number_format($row['book_price'], 2) . " <i class='fas fa-coins'></i></h4>";
                                                echo "<h5>เนื้อเรื่องย่อ</h5>";
                                                echo "<textarea class='form-control'>" . $row['book_summary'] . "</textarea>";
                                                echo "<h5>ผู้เผยแพร่</h5>";
                                                echo "<h4>" . $row['pub_name'] . "</h4>";
                                                echo "<a href='testread.php?bookid=" . $row['book_id'] . "'><button class='btn btn-primary'>ทดลองอ่าน</button></a>";
                                                echo "<a href='mypage.php?pubid=" . $row['book_pubid'] . "'><button class='btn btn-success'>หน้าร้าน</button></a>";
                                                ?>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
            <?php
                    }
                }
            } else {
                echo "<h2>ไม่มีหนังสือขายดีประจำเดือน</h2>";
            }
            ?>
        </div>
        <h3>มาใหม่</h3><a href="display_all.php">
            <h5>ดูทั้งหมด</h5>
        </a>
        <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 ">
            <?php
            $col = "*";
            $table = "book inner join publisher on pub_id = book_pubid
            inner join book_type on btype_bookid = book_id
            inner join typebook on type_id = btype_typeid";
            $where = "book_status = '2' and type_id = '$typeid' ORDER BY book_app DESC LIMIT 12";
            $sqlbook = select_where($col, $table, $where);
            if ($sqlbook->num_rows > 0) {
                while ($row = $sqlbook->fetch_assoc()) {
                    $bookdate = $row['book_app'];
                    $passdate = strtotime("+4 days", strtotime($bookdate)); // วันหมดอายุ
                    $currentdate = time(); // วันที่ปัจจุบัน
                    $bookid = $row['book_id'];
                    $sqlpro = "CALL GetSpecificPromotionBook('$bookid','$proid')";
                    $ex_pro = connectdb()->query($sqlpro);
                    if ($ex_pro->num_rows > 0) {
                        $row = $ex_pro->fetch_assoc();
            ?>
                        <div class="col sm-3">
                            <div class="text-center mb-3">
                                <img src="<?php echo $row['book_cover'] ?>" class="card-img-top" width="200px" height="250px">

                                <?php
                                if ($currentdate <= $passdate) {
                                    echo "<h6 class='card-title text-center text-danger'>NEW</h6>";
                                } else {
                                    echo "";
                                }
                                ?>
                                <?php
                                $sqlrate = "CALL GetBookRatings('$bookid')";
                                $ex_rate = connectdb()->query($sqlrate);
                                if ($ex_rate->num_rows > 0) {
                                    $row2 = $ex_rate->fetch_assoc();
                                    $rating = $row2['rating'];
                                } else {
                                    $rating = 0;
                                }
                                ?>
                                <h5 class="card-title text-center">คะแนน <?php echo round($rating) ?>/5</h5>
                                <h5 class="card-title text-center text-danger">โปร <?php echo $row['pro_name'] ?>
                                    <h5 class="card-title text-center">ชื่อเรื่อง</h5>
                                    <h5 class="card-title text-center text-success"><?php echo $row['book_name'] ?></h5>
                                    <h5 class="card-title text-center">ราคา</h5>
                                    <del class='text-danger'><?php echo number_format($row['book_price'], 2) ?></del> <i class="fas fa-coins"></i>
                                    <h5 class="card-text text-center text-danger"><?php echo number_format($row['discount'], 2) ?> <i class="fas fa-coins"></i></h5>
                                    <h5 class="card-title text-center">ผู้เผยแพร่</h5>
                                    <h5 class="card-text text-center text-success"><?php echo $row['pub_name'] ?></h5>
                                    <?php
                                    if (isset($cusid)) {

                                        $sqlcus = select_where("cus_coin", "customer", "cus_id = '$cusid'");
                                        if ($sqlcus->num_rows > 0) {
                                            $row2 = $sqlcus->fetch_assoc();

                                            $sqlcheck = select_where("*", "bookshelf", "bshelf_cusid = '$cusid' and bshelf_bookid = '" . $row['book_id'] . "' and bshelf_status = '1'");
                                            if ($sqlcheck->num_rows > 0) {
                                                $id = $row['book_id'];
                                                echo "<a href='readbook.php?bookid=$id'><button class='btn btn-primary mb-2' >อ่าน</button></a>";
                                            } else {

                                                if ($row2['cus_coin'] < $row['discount']) {
                                                    echo '<script>
                                                            function checkcoin(mycoin) {
                                                                let conf = confirm("เหรียญไม่พอต้องเติมเหรียญก่อน");
                                                                if (conf) {
                                                                    window.location = mycoin;
                                                                }
                                                            }
                                                        </script>';
                                                    echo '<a onclick="checkcoin(this.href); return false;" href="add_coin.php" class="btn btn-danger mb-2">ชำระเงิน</a>';
                                                } else {

                                                    $_SESSION['coin'] = $row2['cus_coin'];
                                                    $sqlcheck = select_where("*", "bookshelf", "bshelf_cusid = '$cusid' and bshelf_bookid = '" . $row['book_id'] . "' and bshelf_status = '1'");

                                                    if ($sqlcheck->num_rows > 0) {
                                    ?>

                                                    <?php
                                                    } else {


                                                    ?>
                                                        <a href="insert_pay.php?bookid=<?php echo $row['book_id'] ?>&discount=<?php echo $row['discount'] ?>" class="btn btn-danger mb-2">ชำระเงิน</a>
                                        <?php
                                                    }
                                                }
                                            }
                                        }

                                        ?>
                                        <?php
                                        $sql = select_where("*", "bookshelf", "bshelf_cusid = '$cusid' and bshelf_bookid = '" . $row['book_id'] . "' and bshelf_status = '1'");
                                        $sqlcart = select_where("*", "cart", "cart_cusid = '$cusid' and cart_bookid = '" . $row['book_id'] . "'");
                                        if ($sql->num_rows > 0 || $sqlcart->num_rows > 0) {

                                        ?>

                                        <?php
                                        } else {

                                        ?>
                                            <a href="insert_cart.php?bookid=<?php echo $row['book_id'] ?>&pro=<?php echo $row['pro_id'] ?>" class="btn btn-primary mb-2">เพิ่มเข้าตะกร้า</a>
                                        <?php
                                        }
                                        $sqlshelf = "select * from bookshelf
                                        where bshelf_bookid = '" . $row['book_id'] . "' and bshelf_cusid = '$cusid'";
                                        $result = connectdb()->query($sqlshelf);
                                        if ($result->num_rows > 0) {

                                        ?>

                                        <?php
                                        } else {
                                        ?>
                                            <a href="insert_shelf.php?bookid=<?php echo $row['book_id'] ?>&pro=<?php echo $row['pro_id'] ?>" class="btn btn-warning">เพิ่มเข้าชั้นหนังสือ</a>
                                        <?php
                                        }
                                        ?>

                                    <?php
                                    } else {

                                    ?>
                                        <script>
                                            function register(mypage2) {
                                                let conf = confirm("ต้องเป็นสมาชิกก่อน");
                                                if (conf) {
                                                    window.location = mypage2;
                                                }
                                            }
                                        </script>
                                        <a onclick="register(this.href); return false;" href="login.php" class="btn btn-danger mb-2">ชำระเงิน</a>
                                        <a onclick="register(this.href); return false;" href="login.php" class="btn btn-primary mb-2">เพิ่มเข้าตะกร้า</a>
                                        <a onclick="register(this.href); return false;" href="login.php" class="btn btn-warning">เพิ่มเข้าชั้นหนังสือ</a>
                                    <?php
                                    }
                                    ?>
                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#<?php echo $row['book_id'] ?>">เรื่องย่อ</button>
                                    <!-- Modal -->
                                    <div class="modal fade" id="<?php echo $row['book_id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">เรื่องย่อ</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <img src="<?php echo $row['book_cover'] ?>" width="200px" height="250px" class="mt-5 p-2 my-2 border">
                                                    <?php
                                                    echo "<h5>ให้คะแนนเรื่องนี้</h5>";
                                                    if (!isset($_SESSION['cusid'])) {
                                                        foreach (range(1, 5) as $rating) {
                                                            echo "<a onclick='register(this.href); return false;' href='login.php'> <i class='fas fa-star'><i hidden>$rating</i></i> </a>";
                                                        }
                                                    } else {
                                                        $receipt_sql = "SELECT * 
                                                        FROM receipt
                                                        JOIN receipt_detail ON receipt.rec_id = receipt_detail.recd_recid
                                                        WHERE receipt.rec_cusid = '" . $_SESSION['cusid'] . "' AND receipt_detail.recd_bookid = '" . $row['book_id'] . "'";
                                                        $ex_receipt = connectdb()->query($receipt_sql);
                                                        if ($ex_receipt->num_rows > 0){
                                                            foreach (range(1, 5) as $rating) {
                                                                echo "<a href='rate.php?bookid=" . $row['book_id'] . "&rate=$rating'> <i class='fas fa-star'><i hidden>$rating</i></i> </a>";
                                                            }
                                                        }
                                                        else{
                                                            foreach (range(1, 5) as $rating) {
                                                                echo "<a onclick='mustbuy(this.href); return false;' href='index.php'> <i class='fas fa-star'><i hidden>$rating</i></i> </a>";
                                                            }
                                                        }
                                                    }
                                                    echo "<h5>ชื่อเรื่อง</h5>";
                                                    echo "<h4>" . $row['book_name'] . "</h4>";
                                                    echo "<h5>ราคา</h5>";
                                                    echo "<h4 class= 'text-danger'>" . number_format($row['book_price'], 2) . " <i class='fas fa-coins'></i></h4>";
                                                    echo "<h5>เนื้อเรื่องย่อ</h5>";
                                                    echo "<textarea class='form-control'>" . $row['book_summary'] . "</textarea>";
                                                    echo "<h5>ผู้เผยแพร่</h5>";
                                                    echo "<h4>" . $row['pub_name'] . "</h4>";
                                                    echo "<a href='testread.php?bookid=" . $row['book_id'] . "'><button class='btn btn-primary'>ทดลองอ่าน</button></a>";
                                                    echo "<a href='mypage.php?pubid=" . $row['book_pubid'] . "'><button class='btn btn-success'>หน้าร้าน</button></a>";
                                                    ?>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    <?php
                    } else {

                    ?>
                        <div class="col sm-3">
                            <div class="text-center mb-3">
                                <img src="<?php echo $row['book_cover'] ?>" class="card-img-top" width="200px" height="250px">

                                <?php
                                if ($currentdate <= $passdate) {
                                    echo "<h6 class='card-title text-center text-danger'>NEW</h6>";
                                } else {
                                    echo "";
                                }
                                ?>
                                <?php
                                $sqlrate = "CALL GetBookRatings('$bookid')";
                                $ex_rate = connectdb()->query($sqlrate);
                                if ($ex_rate->num_rows > 0) {
                                    $row2 = $ex_rate->fetch_assoc();
                                    $rating = $row2['rating'];
                                } else {
                                    $rating = 0;
                                }
                                ?>
                                <h5 class="card-title text-center">คะแนน <?php echo round($rating) ?>/5</h5>

                                <h5 class="card-title text-center">ชื่อเรื่อง</h5>
                                <h5 class="card-title text-center text-success"><?php echo $row['book_name'] ?></h5>
                                <h5 class="card-title text-center">ราคา</h5>

                                <h5 class="card-text text-center text-danger"><?php echo number_format($row['book_price'], 2) ?> <i class="fas fa-coins"></i></h5>
                                <h5 class="card-title text-center">ผู้เผยแพร่</h5>
                                <h5 class="card-text text-center text-success"><?php echo $row['pub_name'] ?></h5>
                                <?php
                                if (isset($cusid)) {

                                    $sqlcus = select_where("cus_coin", "customer", "cus_id = '$cusid'");
                                    if ($sqlcus->num_rows > 0) {
                                        $row2 = $sqlcus->fetch_assoc();

                                        $sqlcheck = select_where("*", "bookshelf", "bshelf_cusid = '$cusid' and bshelf_bookid = '" . $row['book_id'] . "' and bshelf_status = '1'");
                                        if ($sqlcheck->num_rows > 0) {
                                            $id = $row['book_id'];
                                            echo "<a href='readbook.php?bookid=$id'><button class='btn btn-primary mb-2' >อ่าน</button></a>";
                                        } else {

                                            if ($row2['cus_coin'] < $row['book_price']) {
                                                echo '<script>
                                                            function checkcoin(mycoin) {
                                                                let conf = confirm("เหรียญไม่พอต้องเติมเหรียญก่อน");
                                                                if (conf) {
                                                                    window.location = mycoin;
                                                                }
                                                            }
                                                        </script>';
                                                echo '<a onclick="checkcoin(this.href); return false;" href="add_coin.php" class="btn btn-danger mb-2">ชำระเงิน</a>';
                                            } else {

                                                $_SESSION['coin'] = $row2['cus_coin'];
                                                $sqlcheck = select_where("*", "bookshelf", "bshelf_cusid = '$cusid' and bshelf_bookid = '" . $row['book_id'] . "' and bshelf_status = '1'");

                                                if ($sqlcheck->num_rows > 0) {
                                ?>

                                                <?php
                                                } else {


                                                ?>
                                                    <a href="insert_pay.php?bookid=<?php echo $row['book_id'] ?>&price=<?php echo $row['book_price'] ?>" class="btn btn-danger mb-2">ชำระเงิน</a>
                                    <?php
                                                }
                                            }
                                        }
                                    }

                                    ?>
                                    <?php
                                    $sql = select_where("*", "bookshelf", "bshelf_cusid = '$cusid' and bshelf_bookid = '" . $row['book_id'] . "' and bshelf_status = '1'");
                                    $sqlcart = select_where("*", "cart", "cart_cusid = '$cusid' and cart_bookid = '" . $row['book_id'] . "'");
                                    if ($sql->num_rows > 0 || $sqlcart->num_rows > 0) {

                                    ?>

                                    <?php
                                    } else {

                                    ?>
                                        <a href="insert_cart.php?bookid=<?php echo $row['book_id'] ?>" class="btn btn-primary mb-2">เพิ่มเข้าตะกร้า</a>
                                    <?php
                                    }
                                    $sqlshelf = "select * from bookshelf
                                        where bshelf_bookid = '" . $row['book_id'] . "' and bshelf_cusid = '$cusid'";
                                    $result = connectdb()->query($sqlshelf);
                                    if ($result->num_rows > 0) {

                                    ?>

                                    <?php
                                    } else {
                                    ?>
                                        <a href="insert_shelf.php?bookid=<?php echo $row['book_id'] ?>" class="btn btn-warning">เพิ่มเข้าชั้นหนังสือ</a>
                                    <?php
                                    }
                                    ?>

                                <?php
                                } else {

                                ?>
                                    <script>
                                        function register(mypage2) {
                                            let conf = confirm("ต้องเป็นสมาชิกก่อน");
                                            if (conf) {
                                                window.location = mypage2;
                                            }
                                        }
                                    </script>
                                    <a onclick="register(this.href); return false;" href="login.php" class="btn btn-danger mb-2">ชำระเงิน</a>
                                    <a onclick="register(this.href); return false;" href="login.php" class="btn btn-primary mb-2">เพิ่มเข้าตะกร้า</a>
                                    <a onclick="register(this.href); return false;" href="login.php" class="btn btn-warning">เพิ่มเข้าชั้นหนังสือ</a>
                                <?php
                                }
                                ?>
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#<?php echo $row['book_id'] ?>">เรื่องย่อ</button>
                                <!-- Modal -->
                                <div class="modal fade" id="<?php echo $row['book_id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">เรื่องย่อ</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <img src="<?php echo $row['book_cover'] ?>" width="200px" height="250px" class="mt-5 p-2 my-2 border">
                                                <?php
                                                echo "<h5>ให้คะแนนเรื่องนี้</h5>";
                                                if (!isset($_SESSION['cusid'])) {
                                                    foreach (range(1, 5) as $rating) {
                                                        echo "<a onclick='register(this.href); return false;' href='login.php'> <i class='fas fa-star'><i hidden>$rating</i></i> </a>";
                                                    }
                                                } else {
                                                    $receipt_sql = "SELECT * 
                                                    FROM receipt
                                                    JOIN receipt_detail ON receipt.rec_id = receipt_detail.recd_recid
                                                    WHERE receipt.rec_cusid = '" . $_SESSION['cusid'] . "' AND receipt_detail.recd_bookid = '" . $row['book_id'] . "'";
                                                    $ex_receipt = connectdb()->query($receipt_sql);
                                                    if ($ex_receipt->num_rows > 0){
                                                        foreach (range(1, 5) as $rating) {
                                                            echo "<a href='rate.php?bookid=" . $row['book_id'] . "&rate=$rating'> <i class='fas fa-star'><i hidden>$rating</i></i> </a>";
                                                        }
                                                    }
                                                    else{
                                                        foreach (range(1, 5) as $rating) {
                                                            echo "<a onclick='mustbuy(this.href); return false;' href='index.php'> <i class='fas fa-star'><i hidden>$rating</i></i> </a>";
                                                        }
                                                    }
                                                }
                                                echo "<h5>ชื่อเรื่อง</h5>";
                                                echo "<h4>" . $row['book_name'] . "</h4>";
                                                echo "<h5>ราคา</h5>";
                                                echo "<h4 class= 'text-danger'>" . number_format($row['book_price'], 2) . " <i class='fas fa-coins'></i></h4>";
                                                echo "<h5>เนื้อเรื่องย่อ</h5>";
                                                echo "<textarea class='form-control'>" . $row['book_summary'] . "</textarea>";
                                                echo "<h5>ผู้เผยแพร่</h5>";
                                                echo "<h4>" . $row['pub_name'] . "</h4>";
                                                echo "<a href='testread.php?bookid=" . $row['book_id'] . "'><button class='btn btn-primary'>ทดลองอ่าน</button></a>";
                                                echo "<a href='mypage.php?pubid=" . $row['book_pubid'] . "'><button class='btn btn-success'>หน้าร้าน</button></a>";
                                                ?>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
            <?php
                    }
                }
            } else {
                echo "<h2>ไม่มีหนังสือมาใหม่</h2>";
            }
            connectdb()->close();
            ?>
        </div>
    </div>
    <?php
    include "chatbot.php"
    ?>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
            function mustbuy(mypage3) {
            let conf = confirm("ต้องเป็นเจ้าของหนังสือเล่มนี้ก่อน");
                if (conf) {
                    window.location = mypage3;
                }
            }
    </script>
</body>

</html>