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
    <title>myinfo</title>
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
                    ข้อมูลผู้ใช้
                </div>
                <?php
                $sqlcus = "select * from customer where cus_id = '$cusid'";
                $result = connectdb()->query($sqlcus);
                if ($result->num_rows > 0){
                    $row = $result->fetch_assoc();
                    $gender = $row['cus_sex'];
                }
                ?>
                <form> 
                    <label>ชื่อ</label>
                    <input type="text" name="fname" value="<?php echo $row['cus_name']?>" class="form-control" readonly>
                    <label>นามสกุล</label>
                    <input type="text" name="lname" value="<?php echo $row['cus_lname']?>" class="form-control" readonly>
                    <?php
                    // เช็คเพศแล้วกำหนด checked ให้กับ input tag ตามค่าที่ได้จากฐานข้อมูล
                    switch ($gender) {
                    case "M":
                        echo '<label>เพศ</label><br>
                            <input type="radio" name="gender" value="M" checked disabled>
                            <label>ชาย</label>
                            <input type="radio" name="gender" value="F" disabled>
                            <label>หญิง</label>
                            <input type="radio" name="gender" value="O" disabled>
                            <label>อื่นๆ</label><br>';
                    break;
                    case "F":
                        echo '<label>เพศ</label><br>
                            <input type="radio" name="gender" value="M"  disabled>
                            <label>ชาย</label>
                            <input type="radio" name="gender" value="F" checked disabled>
                            <label>หญิง</label>
                            <input type="radio" name="gender" value="O" disabled>
                            <label>อื่นๆ</label><br>';
                    break;
                    case "O":
                        echo '<label>เพศ</label><br>
                            <input type="radio" name="gender" value="M" disabled>
                            <label>ชาย</label>
                            <input type="radio" name="gender" value="F" disabled>
                            <label>หญิง</label>
                            <input type="radio" name="gender" value="O"  checked disabled>
                            <label>อื่นๆ</label><br>';
                    break;
                    }
                    ?>
                    <label>อีเมล</label>
                    <input type="email" name="email" value="<?php echo $row['cus_email']?>" class="form-control"  readonly>
                    <span id="email_availability"></span><br>
                    <label>วันเกิด</label>
                    <input type="date" name="bdate" value="<?php echo $row['cus_bdate']?>" class="form-control" readonly>
                    <label>เบอร์โทรศัพท์</label>
                    <input type="text" name="tel" value="<?php echo $row['cus_tel']?>" class="form-control mb-3" required id="txt_telephone" maxlength="10" readonly>
                    <a class="btn btn-primary mb-4 me-2" href="edit_cus.php?cusid=<?php echo $row['cus_id']?>" role="button"><h6>แก้ไขข้อมูลผู้ใช้</h6></a>
                </form>
            </div>
        </div>
    </div>
</body>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

</html>