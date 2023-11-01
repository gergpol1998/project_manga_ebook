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
    <title>my promotion</title>
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
                <a class="btn btn-success mb-4 me-2" href="add_promotion.php" role="button">
                    <h4>+เพิ่มโปรโมชั่น</h4>
                </a>

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
                    }
                    else{
                        echo '<form action="insert_round.php" method="POST">';
                            echo '<label>เลือกรอบรับเงิน</label>';
                            echo '<select name="round" class="form-select mb-2" disabled>';
                                $sqlround = "select * from round";
                                $ex_round = connectdb()->query($sqlround);
                                if ($ex_round->num_rows > 0) {
                                    while ($row = $ex_round->fetch_assoc()) {
                                
                                        echo '<option value="'.$row["round_id"].'">'.$row['round_num'].'</option>';
                                    }
                                }
                                
                            echo '</select>';
                            echo '<input type="submit" class="btn btn-primary" name="submit" value="เลือก" disabled>';
                        echo '</form>';
                        echo "<span class= 'text-danger'>เลือกได้อีกทีวันที่ 1 เดือนถัดไป</span>";
                    }
                }
                
                else{

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
                    <span class= 'text-danger'>เลือกได้อีกทีวันที่ 1 เดือนถัดไป</span>
                
                <?php
                }
                
            }
            ?>
        </div>
        <a class="btn btn-success mb-4 me-2" href="income.php" role="button">
            <h6>รายได้</h6>
        </a>
        <h4>
            <div>โปรโมชั่นของฉัน</div>
        </h4>
        <div class="mb-3">
            <a href="promotion.php"><button type="button" class="btn btn-outline-success">โปรโมชั่นที่ใช้งานได้</button></a>
            <a href="end_promotion.php"><button type="button" class="btn btn-success">โปรโมชั่นที่หมดอายุ</button></a>
        </div>

        <div class="row">
            <div class="col-md-10">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ชื่อโปรโมชั่น</th>
                            <th>ส่วนลด</th>
                            <th>วันที่เริ่มสร้าง</th>
                            <th>วันที่สิ้นสุด</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sqlpub = "select pub_id from publisher inner join customer on pub_cusid = cus_id
                        where pub_cusid = '$cusid'";
                        $ex_pub = connectdb()->query($sqlpub);
                        if ($ex_pub->num_rows > 0) {
                            $row = $ex_pub->fetch_assoc();
                            $pubid = $row['pub_id'];

                            $sqlpro = "select * 
                            from promotion 
                            where pro_pubid = '$pubid'and pro_edate < CURDATE()+ INTERVAL 1 DAY";
                            $result = connectdb()->query($sqlpro);
                        }

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {

                        ?>
                                <tr>
                                    <td>
                                        <?php echo $row['pro_name']; ?>
                                    </td>
                                    <td><?php echo $row['pro_discount'] ?></td>
                                    <td>
                                        <?= $row['pro_sdate'] ?>
                                    </td>
                                    <td>
                                        <?= $row['pro_edate'] ?>
                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo "<h4 class='text-success'>ไม่มีโปรโมชั่น</h4>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
<?php
connectdb()->close();
?>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

</html>