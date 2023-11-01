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
    <title>Cart</title>
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
        <div class="row">
            <div class="col-md-10">
                <h2 class="text-center my-3">ประวัติการเติมเหรียญ</h2>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ลำดับ</th>
                            <th>วันที่</th>
                            <th>ยอด</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        $sqlhiscoin = "select date_format(top_date,'%d/%m/%Y') as top_date,top_amount from topup
                        where top_cusid = '$cusid'";
                        $ex_hiscoin = connectdb()->query($sqlhiscoin);
                        if ($ex_hiscoin->num_rows > 0){
                            while($row = $ex_hiscoin->fetch_assoc()){

                         
                        ?>
                        <tr>
                            <td><?php echo $i?></td>
                            <td><?php echo $row['top_date']?></td>
                            <td><?php echo $row['top_amount']?> <i class="fas fa-coins"></i></td>
                        </tr>
                        
                    </tbody>
                    <?php
                            $i++;
                           }
                        }
                        ?>
                </table>
            </div>
        </div>
    </div>
    <?php
    connectdb()->close();
    ?>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

</html>