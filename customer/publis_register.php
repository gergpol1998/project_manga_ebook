<?php
session_start();

echo "<script> src ='https://code.jquery.com/jquery-3.6.1.min.js' 
    </script>
    <script src = 'https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.min.js'></script>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css'/>";

    echo "<script src='function.js'></script>";

if(!isset($_SESSION['cusid'])){
    echo '
            <script>
                sweetalerts("กรุณาลงชื่อเข้าใช้งานก่อน!!","warning","","login.php");
            </script>
            ';
}
include "function.php";
connectdb();
$sqlbank = select("*","bank");
?>

<!DOCTYPE html>
<html>
  <head>
    <title>publisher register Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css">
    <script src="function.js"></script>
  </head>
  <body>
    <div class="container mt-5">
      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="card">
            <div class="card-header bg-primary text-white">
              <h4 class="text-center">สมัครเป็นผู้เผยแพร่</h4>
            </div>
            <div class="card-body">
              <form method="POST" action="insert_pub.php" onsubmit="return check_bankaccount();">
                <div class="form-group">
                  <label>ชื่อผู้เผยแพร่</label>
                  <input type="text" class="form-control"  name="penname" required> 
                </div>
                <div class="form-group">
                  <label>ธนาคาร</label>
                  <select class="form-select" name="bankid" required>
                    <?php
                        if($sqlbank->num_rows > 0){
                            while($row = $sqlbank->fetch_assoc()){ 
                    ?>
                        <option value="<?php echo $row['bank_id']?>"><?php echo $row['bank_name']?></option>
                    <?php
                            }
                        }
                    ?>
                  </select>
                </div>
                <div class="form-group mb-3">
                  <label>เลขบีญชี</label>
                  <input type="text" class="form-control"  name="pubacc" id="txt_bankaccount" maxlength="10" required>
                </div>
                <input type="submit" class="btn btn-primary" name="submit" value="สมัคร">
                <input type="reset" class="btn btn-danger" name="cancel" value="ยกเลิก">
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
