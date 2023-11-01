<?php
session_start();

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
if (isset($_POST['coin-amount'])){
    $coin = $_POST['coin-amount'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>payment</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
</head>
<body>
  <div class="container mt-5">
    <?php
    echo "ราคารวม ".$coin;
    ?>
  <form name="checkoutForm" method="POST" action="checkout.php">
  <script type="text/javascript" src="https://cdn.omise.co/omise.js"
    data-key="pkey_test_5v1spl4ftbv8zqt5ror"
    data-image="http://bit.ly/customer_image"
    data-frame-label="my shop"
    data-button-label="ชำระเงิน"
    data-submit-label="ชำระเงิน"
    data-location="no"
    data-amount="<?php echo $coin?>00"
    data-currency="thb">
  </script>
  <!--the script will render <input type="hidden" name="omiseToken"> for you automatically-->
  <input type="hidden" name="coin-amount" value="<?php echo $coin?>">
</form>

<!-- data-key="YOUR_PUBLIC_KEY" -->

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>