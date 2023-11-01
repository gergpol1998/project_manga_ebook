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
    <title>dashboard2</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- นำเข้า library Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>
    <?php
    include "nav.php";
    ?>
    <div class="container px-4 px-lg-5 mt-3">

        <div class="d-flex justify-content-between">
            <h2>
                <div>แดชบอร์ดของฉัน</div>
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

        <div class="mb-3">
            <a href="dash_board.php"><button type="button" class="btn btn-outline-success">หนังสือขายดีเลือกตามช่วงเวลา</button></a>
            <a href="dash_board2.php"><button type="button" class="btn btn-success">หนังสือแต่ละเล่มขายดีในช่วงไหน</button></a>
        </div>

        <?php
        $sqlbookname = "SELECT book_name FROM book
                    INNER JOIN publisher ON pub_id = book_pubid
                    INNER JOIN customer ON cus_id = pub_cusid
                    WHERE pub_cusid = '$cusid' AND book_status = '2'";
        $ex_sqlbookname = connectdb()->query($sqlbookname);
        ?>

        <form action="dash_board2.php" method="get">
            <div class="mb-3">
                <label for="book_name" class="form-label">เลือกหนังสือ</label>
                <select class="form-select" name="book_name">
                    <option value="">--เลือกหนังสือ--</option>
                    <?php
                    if ($ex_sqlbookname->num_rows > 0) {
                        while ($row = $ex_sqlbookname->fetch_assoc()) {
                            $bookName = $row['book_name'];
                            echo "<option value=\"$bookName\">$bookName</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="year" class="form-label">เลือกปี</label>
                <select id="year" class="form-select" name="year">
                    <option value="">--เลือกปี--</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="month" class="form-label">เลือกเดือน</label>
                <select id="month" class="form-select" name="month">
                    <option value="">--เลือกเดือน--</option>
                    <option value="01">01</option>
                    <option value="02">02</option>
                    <option value="03">03</option>
                    <option value="04">04</option>
                    <option value="05">05</option>
                    <option value="06">06</option>
                    <option value="07">07</option>
                    <option value="08">08</option>
                    <option value="09">09</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">ค้นหา</button>
        </form>
        <?php

        if (isset($_GET['year']) && isset($_GET['month']) && isset($_GET['book_name'])) {
            // รับค่าช่วงเวลาจากฟอร์ม
            $bname = $_GET['book_name'];
            $cusid = $_SESSION['cusid'];
            $year = $_GET['year'];
            $month = $_GET['month'];

            $sqlpub = "select pub_id from publisher inner join customer on cus_id = pub_cusid
            where pub_cusid = '$cusid'";
            $ex_pub = connectdb()->query($sqlpub);
            if ($ex_pub->num_rows > 0) {
                $row = $ex_pub->fetch_assoc();
                $pubid = $row['pub_id'];

                $col = "*,count(recd_bookid) as total_quantity";
                $table = "book
                INNER JOIN receipt_detail ON book.book_id = receipt_detail.recd_bookid
                INNER JOIN receipt ON receipt.rec_id = receipt_detail.recd_recid
                INNER JOIN publisher ON publisher.pub_id = book.book_pubid
                INNER JOIN customer ON customer.cus_id = publisher.pub_cusid";
                $where = "UPPER(book_name) LIKE UPPER('%$bname%') AND YEAR(rec_date) = '$year' AND MONTH(rec_date) = '$month' AND pub_id = '$pubid'
                GROUP BY DATE_FORMAT(rec_date, '%Y-%m-%d') DESC ";
                $sqlbook = select_where($col, $table, $where);

                // สร้าง arrays สำหรับเก็บข้อมูลที่ดึงมาจากฐานข้อมูล
                $book_names = array();
                $sales = array();
                $sales_date = array();

                if ($sqlbook->num_rows > 0) {

                    while ($row = $sqlbook->fetch_assoc()) {


                        array_push($book_names, $row["book_name"]);
                        array_push($sales, $row['total_quantity']);
                        array_push($sales_date, $row['book_name'] . " - " . $row['rec_date']);
                    }
                } else {
                    echo "ไม่พบข้อมูล";
                }
            }
            connectdb()->close();
        }
        ?>

        <!-- สร้างกราฟแท่งด้วย canvas -->
        <canvas id="myChart"></canvas>

        <script>
            // สร้างกราฟแท่ง
            var ctx = document.getElementById('myChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($sales_date); ?>,
                    datasets: [{
                        label: 'จำนวนขาย',
                        data: <?php echo json_encode($sales); ?>,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>

    </div>
</body>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    var select = document.getElementById("year");
    var currentYear = new Date().getFullYear();
    var startYear = 1990; // เปลี่ยนเป็นปีเริ่มต้นที่คุณต้องการ
    for (var i = startYear; i <= currentYear; i++) {
        var opt = document.createElement('option');
        opt.value = i;
        opt.innerHTML = i;
        select.appendChild(opt);
    }
</script>

</html>