<?php
session_start();
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
} else {
    $error = '';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>reset password</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css">
    <script src="function.js"></script>
</head>


<body>
    <div class="container">
        <br><br>
        <div class="row d-flex justify-content-center">
            <div class="col-md-5 bg-light text-dark">
                <br>
                <div class="alert alert-primary h4 text-center mb-4 mt-4 " role="alert">
                    เปลี่ยนรหัสผ่าน
                </div>
                <a href="login.php"><button class="btn btn-danger mb-2" >ยกเลิก</button></a>
                <form method="POST" action="check_email.php">
                    <label>Username</label>
                    <input type="text" name="uname" class="form-control" required>
                    <label>อีเมล</label>
                    <input type="text" name="email" class="form-control" required>
                    <span class="text-danger">
                        <?php echo $error ?>
                    </span><br>
                    <input type="submit" class="btn btn-primary" name="submit" value="ส่ง">
                    <input type="reset" class="btn btn-danger" name="cancel" value="ล้าง"><br><br>
                    
                </form>
            </div>
        </div>
    </div>
</body>

</html>