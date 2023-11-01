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
    <title>edit info</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</head>

<body>
    <?php
    include "nav.php";
    ?>
    <div class="container">
        <br><br>
        <div class="row d-flex justify-content-center">
            <div class="col-md-5 bg-light text-dark">
                <br>
                <div class="alert alert-primary h4 text-center mb-4 mt-4 " role="alert">
                    แก้ไขข้อมูลผู้ใช้
                </div>
                <?php
                $sqlcus = "select * from customer where cus_id = '$cusid'";
                $result = connectdb()->query($sqlcus);
                if ($result->num_rows > 0){
                    $row = $result->fetch_assoc();
                    $gender = $row['cus_sex'];
                }
                ?>
                <form method="POST" action="udpate_customer.php" onsubmit="return check_telephone();"> 
                    <label>ชื่อ</label>
                    <input type="text" name="id" value="<?php echo $row['cus_id']?>"  hidden class="form-control">
                    <input type="text" name="fname" value="<?php echo $row['cus_name']?>" class="form-control" require>
                    <label>นามสกุล</label>
                    <input type="text" name="lname" value="<?php echo $row['cus_lname']?>" class="form-control" require>
                    <?php
                    // เช็คเพศแล้วกำหนด checked ให้กับ input tag ตามค่าที่ได้จากฐานข้อมูล
                    switch ($gender) {
                    case "M":
                        echo '<label>เพศ</label><br>
                            <input type="radio" name="gender" value="M" checked >
                            <label>ชาย</label>
                            <input type="radio" name="gender" value="F" >
                            <label>หญิง</label>
                            <input type="radio" name="gender" value="O" >
                            <label>อื่นๆ</label><br>';
                    break;
                    case "F":
                        echo '<label>เพศ</label><br>
                            <input type="radio" name="gender" value="M"  >
                            <label>ชาย</label>
                            <input type="radio" name="gender" value="F" checked >
                            <label>หญิง</label>
                            <input type="radio" name="gender" value="O" >
                            <label>อื่นๆ</label><br>';
                    break;
                    case "O":
                        echo '<label>เพศ</label><br>
                            <input type="radio" name="gender" value="M" >
                            <label>ชาย</label>
                            <input type="radio" name="gender" value="F" >
                            <label>หญิง</label>
                            <input type="radio" name="gender" value="O"  checked >
                            <label>อื่นๆ</label><br>';
                    break;
                    }
                    ?>
                    <label>อีเมล</label>
                    <input type="email" name="email" value="<?php echo $row['cus_email']?>" class="form-control" id="email"  require>
                    <span id="email_availability"></span><br>
                    <label>วันเกิด</label>
                    <input type="date" name="bdate" value="<?php echo $row['cus_bdate']?>" class="form-control" require>
                    <label>เบอร์โทรศัพท์</label>
                    <input type="text" name="tel" value="<?php echo $row['cus_tel']?>" class="form-control mb-3" required id="txt_telephone" maxlength="10" require>
                    <input type="submit" class="btn btn-primary" name="submit" value="แก้ไขข้อมูล">
                    <input type="reset" class="btn btn-danger" name="cancel" value="ยกเลิก"><br><br>
                </form>
                </form>
            </div>
        </div>
    </div>
</body>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.1.js"></script>
<script>
    $(document).ready(function() {
    
    $('#email').on('blur', function() {
        var email = $(this).val();
        $.ajax({
            url: 'check_availability.php',
            type: 'POST',
            data: {email: email},
            success: function(response) {
                if (response == 'taken') {
                    $('#email_availability').html('<span class="text-danger">This email is already taken </span>');
                } else if (response == 'available') {
                    $('#email_availability').html('<span class="text-success">This email is available</span>');
                }
            }
        });
    });
});

</script>

</html>