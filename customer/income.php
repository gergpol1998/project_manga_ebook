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
    <title>income</title>
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
                <div>รายได้ของฉัน</div>
            </h2>
            <div class="d-flex justify-content-end">
            <?php
                $sqlcheckpro = "select book_id from book
                inner join publisher on pub_id = book_pubid
                inner join customer on cus_id = pub_cusid
                where pub_cusid = '$cusid' and book_status = '2'";
                $ex_sqlcheckpro = connectdb()->query($sqlcheckpro);
                if ($ex_sqlcheckpro->num_rows > 0){
                    echo '<a class="btn btn-success mb-4 me-2" href="promotion.php" role="button">
                        <h4>โปรโมชั่น</h4>
                    </a>' ;
                }
                else{
                ?>
                <script>
                    function adds(mypage) {
                    let agree = confirm("ยังไม่มีหนังสือที่เผยแพร่");
                        if (agree) {
                        window.location = mypage;
                        }
                    }
                </script>
                <a class="btn btn-success mb-4 me-2" onclick="adds(this.href); return false;" href="my_work.php"><h4>โปรโมชั่น</h4></a>
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
        <div class="row">
            <?php
            $sqlpubname = "select pub_name from publisher
            inner join customer on pub_cusid = cus_id
            where pub_cusid = '$cusid'";
            $ex_pubname = connectdb()->query($sqlpubname);
            if ($ex_pubname->num_rows > 0){
                $row2 = $ex_pubname->fetch_assoc();
            ?>
            <div class="col-md-10">
                <h2 class="text-center my-3"><?php echo $row2['pub_name']?></h2>
            <?php }?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ลำดับ</th>
                            <th>เดือน/ปี</th>
                            <th>ยอดที่ได้รับ</th>
                        </tr>
                    </thead>
                    <?php
                    $i = 1;
                    $total = 0;
                    $sqlpub = "select DATE_FORMAT(inc_month,'%d/%Y') as date,inc_amount from publisher inner join income on pub_id = inc_pubid
                    inner join customer on pub_cusid = cus_id
                    where pub_cusid = '$cusid' and DATE_FORMAT(inc_month,'%Y') = DATE_FORMAT(CURDATE(), '%Y')";
                    $ex_pub = connectdb()->query($sqlpub);
                    if ($ex_pub->num_rows > 0){
                        while($row = $ex_pub->fetch_assoc()){
                            
                    ?>
                    <tbody>
                        <tr>
                            <td>
                                <?php echo $i; ?>
                            </td>
                            <td>
                                <?php echo $row['date'] ?>
                            </td>
                            <td>
                                <?php echo $row['inc_amount'] ?>
                            </td>
                        </tr>
                        <?php
                        $i++;
                        $total += $row['inc_amount'];
                        }
                    }
                    else{
                        echo "<td><h5>ไม่มีข้อมูล</h5></td>";
                    }
                    connectdb()->close();
                    ?>
                        <tr>
                            <td class="text-start"><h4>ราคารวมสุทธิ</h4></td>
                            <td class="text-start"><b class="text-danger">
                                <?php echo number_format($total, 2) ?>
                            </b></td>
                        </tr>
                    </tbody>
                    
                </table>
        
    </div>
</body>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

</html>