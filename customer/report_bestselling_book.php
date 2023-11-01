<?php
require_once __DIR__ . '/vendor/autoload.php';

$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];
$mpdf = new \Mpdf\Mpdf([
    'mode' => 'utf-8',
    'format' => 'A4',
    'margin_left' => 15,
    'margin_right' => 15,
    'margin_top' => 16,
    'margin_bottom' => 16,
    'margin_header' => 9,
    'margin_footer' => 9,
    'mirrorMargins' => true,

    'fontDir' => array_merge($fontDirs, [
        __DIR__ . 'vendor/mpdf/mpdf/custom/font/directory',
    ]),
    'fontdata' => $fontData + [
        'thsarabun' => [
            'R' => 'THSarabunNew.ttf',
            'I' => 'THSarabunNew Italic.ttf',
            'B' => 'THSarabunNew Bold.ttf',
            'U' => 'THSarabunNew BoldItalic.ttf'
        ]
    ],
    'default_font' => 'thsarabun',
    'defaultPageNumStyle' => 1
]);



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
    <title>report</title>
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
                <div>รายงานของฉัน</div>
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

        <form action="report_bestselling_book.php" method="get">
            <div class="mb-3">
                <label for="start_date" class="form-label">วันที่เริ่มต้น</label>
                <input type="date" class="form-control" id="start_date" name="start_date">
            </div>
            <div class="mb-3">
                <label for="end_date" class="form-label">วันที่สิ้นสุด</label>
                <input type="date" class="form-control" id="end_date" name="end_date">
            </div>
            <button type="submit" class="btn btn-primary">ค้นหา</button>
        </form>
        <?php

        if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
            // รับค่าช่วงเวลาจากฟอร์ม
            $start_date = $_GET['start_date'];
            $end_date = $_GET['end_date'];
            $cusid = $_SESSION['cusid'];

            $sqlpub = "select pub_id from publisher inner join customer on cus_id = pub_cusid
            where pub_cusid = '$cusid'";
            $ex_pub = connectdb()->query($sqlpub);
            if ($ex_pub->num_rows > 0) {
                $row = $ex_pub->fetch_assoc();
                $pubid = $row['pub_id'];

                $col = "*,count(recd_bookid) as total_quantity,sum(recd_price) as sumtotal";
                $table = "book
                INNER JOIN receipt_detail ON book.book_id = receipt_detail.recd_bookid
                INNER JOIN receipt ON receipt.rec_id = receipt_detail.recd_recid
                INNER JOIN publisher ON publisher.pub_id = book.book_pubid
                INNER JOIN customer ON customer.cus_id = publisher.pub_cusid";
                $where = "DATE_FORMAT(rec_date, '%Y-%m-%d') BETWEEN '$start_date' AND '$end_date' AND pub_id = '$pubid'
                GROUP BY recd_bookid";
                $sqlbook = select_where($col, $table, $where);
                if ($sqlbook->num_rows > 0) {

                    //show ในหน้า pdf
                    $tableh1 = '
                    <h3>วันที่พิมพ์รายงาน ' . date("d/m/Y") . '</h3>
                    <h2 style="text-align:center">รายการหนังสือขายดี</h2>

                    <table id="bg-table" width="100%" style="border-collapse: collapse;font-size:12pt;margin-top:8px;">
                        <thead>
                            <tr style="border:1px solid #000;padding:4px;">
                                <td  style="border-right:1px solid #000;padding:4px;text-align:center;"   width="10%">รหัสหนังสือ</td>
                                <td  style="border-right:1px solid #000;padding:4px;text-align:center;"  width="15%">ชื่อหนังสือ</td>
                                <td  style="border-right:1px solid #000;padding:4px;text-align:center;"  width="15%">วันที่ขาย</td>
                                <td  style="border-right:1px solid #000;padding:4px;text-align:center;"  width="15%">จำนวนที่ขายได้</td>
                                <td  style="border-right:1px solid #000;padding:4px;text-align:center;" width="15%">ราคารวม</td>
                            </tr>

                        </thead>
                    <tbody>';

                    //show ในหน้าเว็บ
                    echo '<h2 style="text-align:center">รายการหนังสือขายดี</h2>

                    <table id="bg-table" width="100%" style="border-collapse: collapse;font-size:12pt;margin-top:8px;">
                        <thead>
                            <tr style="border:1px solid #000;padding:4px;">
                                <td  style="border-right:1px solid #000;padding:4px;text-align:center;"   width="10%">รหัสหนังสือ</td>
                                <td  style="border-right:1px solid #000;padding:4px;text-align:center;"  width="15%">ชื่อหนังสือ</td>
                                <td  style="border-right:1px solid #000;padding:4px;text-align:center;"  width="15%">วันที่ขาย</td>
                                <td  style="border-right:1px solid #000;padding:4px;text-align:center;"  width="15%">จำนวนที่ขายได้</td>
                                <td  style="border-right:1px solid #000;padding:4px;text-align:center;" width="15%">ราคารวม</td>
                            </tr>

                        </thead>
                    <tbody>';

                    $total = 0;
                    $tablebody = '<tr></tr>';
                    $tablebody2 = '<tr></tr>';
                    
                    while ($row = $sqlbook->fetch_assoc()) {

                        $total += $row['sumtotal'];

                        //show ในหน้า pdf
                        $tablebody .= '<tr style="border:1px solid #000;">
                            <td style="border-right:1px solid #000;padding:3px;text-align:center;"  >' . $row['book_id'] . '</td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:center;">' . $row['book_name'] . '</td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:center;">' . $row['rec_date'] . '</td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:center;">' . $row['total_quantity'] . '</td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:center;">' . number_format($row['sumtotal'], 2) . '</td>
			            </tr>';


                        //show ในหน้าเว็บ
                        echo '<tr style="border:1px solid #000;">
                            <td style="border-right:1px solid #000;padding:3px;text-align:center;"  >' . $row['book_id'] . '</td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:center;">' . $row['book_name'] . '</td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:center;">' . $row['rec_date'] . '</td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:center;">' . $row['total_quantity'] . '</td>
                            <td style="border-right:1px solid #000;padding:3px;text-align:center;">' . number_format($row['sumtotal'], 2) . '</td>
                        </tr>';
                        
                    }
                    //show ในหน้าเว็บ
                    echo '<tr style="border:1px solid #000;">
                    <td colspan="4" style="border-right:1px solid #000;padding:3px;text-align:center;"> ราคารวมสุทธิ์</td>
                    <td style="border-right:1px solid #000;padding:3px;text-align:center;"  >' . number_format($total, 2) . '</td>
                    </tr>';

                    //show ในหน้า pdf
                    $tablebody2 .= '<tr style="border:1px solid #000;">
                    <td colspan="4" style="border-right:1px solid #000;padding:3px;text-align:center;"> ราคารวมสุทธิ์</td>
                    <td style="border-right:1px solid #000;padding:3px;text-align:center;"  >' . number_format($total, 2) . '</td>
                    </tr>';

                    //ปิด tag
                    $tableend1 = "</tbody>
                    </table>";
                    $mpdf->WriteHTML($tableh1);

                    $mpdf->WriteHTML($tablebody);
                    $mpdf->WriteHTML($tablebody2);

                    $mpdf->WriteHTML($tableend1);

                    $mpdf->Output("MyReport.pdf");
                    echo '<a class="btn btn-success mb-4" href="MyReport.pdf" role="button">โหลดรายงาน</a>';
                } 
                else {
                    echo "ไม่พบข้อมูล";
                }
                
            }

            connectdb()->close();
        }
        ?>
    </div>
</body>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

</html>