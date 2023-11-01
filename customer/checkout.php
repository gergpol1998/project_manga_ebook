<?php
include "function.php";
connectdb();
session_start();
require_once dirname(__FILE__).'/omise-php/lib/Omise.php';
define('OMISE_API_VERSION', '2015-11-17');
// define('OMISE_PUBLIC_KEY', 'PUBLIC_KEY');
// define('OMISE_SECRET_KEY', 'SECRET_KEY');
define('OMISE_PUBLIC_KEY', 'pkey_test_5v1spl4ftbv8zqt5ror');
define('OMISE_SECRET_KEY', 'skey_test_5v1spl6bg4sf6xreo8l');

echo "<script> src ='https://code.jquery.com/jquery-3.6.1.min.js' 
</script>
<script src = 'https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.min.js'></script>
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css'/>";

echo "<script src='function.js'></script>";

if (isset($_SESSION['cusid']) && isset($_POST['coin-amount'])){
    $cusid = $_SESSION['cusid'];
    $coin = $_POST['coin-amount'];
    

    $charge = OmiseCharge::create(array(
        'amount' => $coin."00",
        'currency' => 'thb',
        'card' => $_POST["omiseToken"]
    ));

   if ($charge['status'] === 'successful'){
    
    $sqlcoin = select_where("cus_coin","customer","cus_id = '$cusid'");
    if ($sqlcoin->num_rows > 0){
        $row = $sqlcoin->fetch_assoc();
        $sumcoin = $coin + $row['cus_coin'];
        $result = updatedata("customer","cus_coin = '$sumcoin'","cus_id = '$cusid'");

        if (!$result){
            die(mysqli_error(connectdb()));
        }
        else{
            $last_topid = autoid("TOP-","top_id","topup","0000001");
            $sqlins_topup = "insert into topup (top_id,top_cusid,top_amount,top_date)
            values ('$last_topid','$cusid','$coin',NOW())";
            $result2 = connectdb()->query($sqlins_topup);

            if(!$result2){
                die(mysqli_error(connectdb()));
            }
            else{
                echo '
                    <script>
                        sweetalerts("บันทึกข้อมูลสำเร็จ!!","success","","index.php");
                    </script>
                    ';
            }
        }
    }
   }
}
connectdb()->close();
?>
