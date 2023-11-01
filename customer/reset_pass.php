<?php
session_start();
if (isset($_SESSION['error'])){
    $error = $_SESSION['error'];
}
else{
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
                <form method="POST" action="update_pass.php">
                    <label>รหัสผ่านใหม่</label>
                    <input type="password" name="pass1" class="form-control" required>
                    <label>ยืนยันรหัสผ่าน</label>
                    <input type="password" name="pass2" class="form-control" required>
                    <span class="text-danger"><?php echo $error?></span><br>
                    <input type="submit" class="btn btn-primary" name="submit" value="เปลี่ยนรหัส">
                </form>
            </div>
        </div>
    </div>
</body>
</html>