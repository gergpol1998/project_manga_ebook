<?php
session_start();
include "function.php";
connectdb();

echo "<script> src ='https://code.jquery.com/jquery-3.6.1.min.js' 
    </script>
    <script src = 'https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.min.js'></script>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css'/>";

echo "<script src='function.js'></script>";
if (!isset($_SESSION["cusid"])) {
    echo '
        <script>
            sweetalerts("กรุณาลงชื่อเข้าใช้งานระบบก่อน!!","warning","","login.php");
        </script>
    ';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Add Coins</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
</head>
<body>
  <div class="container mt-5">
    <h1>เติมเหรียญ</h1>
    <form action="payment.php" method="POST">
      <div class="mb-3">
        <label for="coin-amount" class="form-label">จำนวนเหรียญ</label>
        <select class="form-select" name="coin-amount">
        <?php
        $sqlcoin = "select * from coin";
        $ex_coin = connectdb()->query($sqlcoin);
        if ($ex_coin->num_rows > 0){
          
          while ($row = $ex_coin->fetch_assoc()){
        ?>
          <option value="<?php echo $row['coin_amount']?>"><?php echo $row['coin_amount']?></option>
        <?php
          }
        }
        connectdb()->close();
        ?>
        </select>
      </div>
      <input type="submit" class="btn btn-primary" value="เติมเหรียญ">
    </form>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
