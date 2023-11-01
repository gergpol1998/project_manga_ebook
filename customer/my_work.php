<?php
session_start();
echo "<script> src ='https://code.jquery.com/jquery-3.6.1.min.js' 
</script>
<script src = 'https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.min.js'></script>
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css'/>";

echo "<script src='function.js'></script>";

if (!isset($_SESSION['cusid'])) {
    echo '
        <script>
            sweetalerts("กรุณาลงชื่อเข้าใช้งานก่อน!!","warning","","login.php");
        </script>
        ';
} else {
    $cusid = $_SESSION['cusid'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>my_work</title>
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

        <div class="d-flex justify-content-between">
            <h2>
                <div>ผลงานของฉัน</div>
            </h2>
            <div class="d-flex justify-content-end">
                <?php


                $sqlcheckpro = "select book_id from book
                inner join publisher on pub_id = book_pubid
                inner join customer on cus_id = pub_cusid
                where pub_cusid = '$cusid' and book_status = '2'";
                $ex_sqlcheckpro = connectdb()->query($sqlcheckpro);
                if ($ex_sqlcheckpro->num_rows > 0) {
                    echo '<a class="btn btn-success mb-4 me-2" href="promotion.php" role="button">
                        <h4>โปรโมชั่น</h4>
                    </a>';
                } else {
                ?>
                    <script>
                        function adds(mypage) {
                            let agree = confirm("ยังไม่มีหนังสือที่เผยแพร่");
                            if (agree) {
                                window.location = mypage;
                            }
                        }
                    </script>
                    <a class="btn btn-success mb-4 me-2" onclick="adds(this.href); return false;" href="my_work.php">
                        <h4>โปรโมชั่น</h4>
                    </a>
                <?php
                }
                ?>

                <a class="btn btn-primary mb-4 me-2" href="add_book.php" role="button">
                    <h4>+เพิ่มผลงาน</h4>
                </a>

                <a class="btn btn-warning mb-4 me-2" href="report_bestselling_book.php" role="button">
                    <h4>ดูรายงาน</h4>
                </a>

                <a class="btn btn-info mb-4 me-2" href="dash_board.php" role="button">
                    <h4>แดชบอร์ด</h4>
                </a>
            </div>
        </div>

        <div class="alert alert-primary h4 text-start mb-2 mt-4 " role="alert">
            <?php
            $sqlround = "select round_id from round inner join publisher on round_id = pub_round
            where pub_cusid = '$cusid'";
            $ex_round = connectdb()->query($sqlround);
            $currentdate = date("d/m");
            $checkdate = "01/" . date("m");
            $day = date("d");
            if ($ex_round->num_rows > 0) {

                if ($checkdate === $currentdate) {
                    // Check whether the data has already been inserted
                    $sql = "SELECT date_date FROM date WHERE date_date = '$day'";
                    $result = connectdb()->query($sql);
                    if ($result->num_rows === 0) {
            ?>
                        <form action="insert_round.php" method="POST">
                            <label>เลือกรอบรับเงิน</label>
                            <select name="round" class="form-select mb-2">
                                <?php
                                $sqlround = "select * from round";
                                $ex_round = connectdb()->query($sqlround);
                                if ($ex_round->num_rows > 0) {
                                    while ($row = $ex_round->fetch_assoc()) {
                                ?>
                                        <option value="<?php echo $row['round_id'] ?>"><?php echo $row['round_num'] ?></option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                            <input type="submit" class="btn btn-primary" name="submit" value="เลือก">
                        </form>

                    <?php
                    } else {
                        echo '<form action="insert_round.php" method="POST">';
                        echo '<label>เลือกรอบรับเงิน</label>';
                        echo '<select name="round" class="form-select mb-2" disabled>';
                        $sqlround = "select * from round";
                        $ex_round = connectdb()->query($sqlround);
                        if ($ex_round->num_rows > 0) {
                            while ($row = $ex_round->fetch_assoc()) {

                                echo '<option value="' . $row["round_id"] . '">' . $row['round_num'] . '</option>';
                            }
                        }

                        echo '</select>';
                        echo '<input type="submit" class="btn btn-primary" name="submit" value="เลือก" disabled>';
                        echo '</form>';
                        echo "<span class= 'text-danger'>เลือกได้อีกทีวันที่ 1 เดือนถัดไป</span>";
                    }
                } else {

                    ?>
                    <form action="insert_round.php" method="POST">
                        <label>เลือกรอบรับเงิน</label>
                        <select name="round" class="form-select mb-2" disabled>
                            <?php
                            $sqlround = "select * from round";
                            $ex_round = connectdb()->query($sqlround);
                            if ($ex_round->num_rows > 0) {
                                while ($row = $ex_round->fetch_assoc()) {

                            ?>
                                    <option value="<?php echo $row['round_id'] ?>"><?php echo $row['round_num'] ?></option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                        <input type="submit" class="btn btn-primary" name="submit" value="เลือก" disabled>
                    </form>
                    <span class='text-danger'>เลือกได้อีกทีวันที่ 1 เดือนถัดไป</span>

            <?php
                }
            }
            ?>
        </div>
        <a class="btn btn-success mb-4 me-2" href="income.php" role="button">
            <h6>รายได้</h6>
        </a>
        <h4>
            <div>หนังสือของฉัน</div>
        </h4>
        <div class="mb-3">
            <a href="my_work.php"><button type="button" class="btn btn-success">อนุมัติ</button></a>
            <a href="draf.php"><button type="button" class="btn btn-outline-success">ฉบับร่าง</button></a>
            <a href="waitapp.php"><button type="button" class="btn btn-outline-success">รออนุมัติ</button></a>
        </div>
        <form method="POST" class="form-inline d-flex">
            <input class="form-control me-2" id="search2" type="text" placeholder="ชื่อหนังสือ/ผู้เผยแพร่/หมวดหมู่">
        </form>
        <div class="list-group list-group-item-action" id="content2"></div>


        <script>
            $(document).ready(function() {
                $('#search2').keyup(function() {
                    var Search = $('#search2').val(); // getvalue

                    if (Search != '') {
                        $.ajax({
                            url: "search_book.php",
                            method: "POST",
                            data: {
                                search: Search
                            },
                            success: function(data) {
                                $('#content2').html(data);
                            }
                        })
                    } else {
                        $('#content2').html('');
                    }
                });
            });
        </script>
        <?php

        $itemsPerPage = 4;

        // Get the current page number from the query string
        $currentpage = isset($_GET['page']) ? intval($_GET['page']) : 1;

        // Calculate the offset for pagination
        $offset = ($currentpage - 1) * $itemsPerPage;

        $col = "*";
        $table = "book inner join publisher on pub_id = book_pubid
        inner join customer on cus_id = pub_cusid";
        $where = "pub_cusid = '$cusid' and book_status = '2' ORDER BY book_dateup DESC";
        $sqlbook = select_where($col, $table, $where . " LIMIT $offset, $itemsPerPage");
        ?>
        <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4">
            <?php
            if ($sqlbook->num_rows > 0) {
                while ($row = $sqlbook->fetch_assoc()) {
                    $status = $row['book_status'];
                    if ($status === '2') {
                        $status = 'อนุมัติ';
                    }
            ?>
                    <div class="col sm-3">
                        <div class="text-center mb-3">
                            <img src="<?php echo $row['book_cover'] ?>" width="200px" height="250px" class="mt-5 p-2 my-2 border">
                            <?php
                            echo "<h4 class= 'text-success'>$status</h4>";
                            echo "<h5>ชื่อเรื่อง</h5>";
                            echo "<h4>" . $row['book_name'] . "</h4>";
                            echo "<h5>ราคา</h5>";
                            echo "<h4 class= 'text-danger'>" . number_format($row['book_price'], 2) . " <i class='fas fa-coins'></i></h4>";
                            echo "<h5>ผู้เผยแพร่</h5>";
                            echo "<h4>" . $row['pub_name'] . "</h4>";
                            ?>
                            <!-- Button trigger modal -->
                            <a href='readbook.php?bookid=<?php echo $row['book_id'] ?>'><button class='btn btn-danger'>อ่าน</button></a>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#<?php echo $row['book_id'] ?>">เรื่องย่อ</button>
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
                                            echo "<h4 class= 'text-success'>$status</h4>";
                                            echo "<h5>ชื่อเรื่อง</h5>";
                                            echo "<h4>" . $row['book_name'] . "</h4>";
                                            echo "<h5>ราคา</h5>";
                                            echo "<h4 class= 'text-danger'>" . number_format($row['book_price'], 2) . " <i class='fas fa-coins'></i></h4>";
                                            echo "<h5>เนื้อเรื่องย่อ</h5>";
                                            echo "<textarea class='form-control'>" . $row['book_summary'] . "</textarea>";
                                            echo "<h5>ผู้เผยแพร่</h5>";
                                            echo "<h4>" . $row['pub_name'] . "</h4>";
                                            echo "<a href='testread.php?bookid=" . $row['book_id'] . "'><button class='btn btn-primary'>ทดลองอ่าน</button></a>";
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
            connectdb()->close();
            ?>
        </div>
    </div>

    <div class="d-flex justify-content-center">
        <!-- Get the total number of items from the database -->
        <?php
        $totalItemsQuery = "SELECT COUNT(*) as total FROM $table WHERE $where";
        $totalItemsResult = connectdb()->query($totalItemsQuery);
        $totalItems = $totalItemsResult->fetch_assoc()['total'];

        // Calculate the total number of pages
        $totalPages = ceil($totalItems / $itemsPerPage);
        ?>

        <!-- Display pagination links -->
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <?php
                if ($totalPages > 1) {
                    // Previous page button
                    if ($currentpage > 1) {
                        echo '<li class="page-item"><a class="page-link" href="?page=' . ($currentpage - 1) . '" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
                    }

                    // First page
                    echo '<li class="page-item"><a class="page-link" href="?page=1">1</a></li>';

                    // Ellipsis if needed
                    if ($currentpage > 4) {
                        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                    }

                    // Last page
                    if ($currentpage <= $totalPages - 4) {
                        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                    }
                    echo '<li class="page-item"><a class="page-link" href="?page=' . $totalPages . '">' . $totalPages . '</a></li>';

                    // Next page button
                    if ($currentpage < $totalPages) {
                        echo '<li class="page-item"><a class="page-link" href="?page=' . ($currentpage + 1) . '" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
                    }
                }
                ?>
            </ul>
        </nav>
    </div>
</body>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

</html>