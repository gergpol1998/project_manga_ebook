<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>register</title>
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
                    สมัครสมาชิก
                </div>
                <form method="POST" action="insert_customer.php" onsubmit="return check_telephone();" >
                    <label>ชื่อ</label>
                    <input type="text" name="fname" class="form-control" required>
                    <label>นามสกุล</label>
                    <input type="text" name="lname" class="form-control" required>
                    <label>username</label>
                    <input type="text" name="username" class="form-control" id="username" required>
                    <span id="username_availability"></span><br>
                    <label>password</label>
                    <input type="password" name="password" class="form-control" required>
                    <label>เพศ</label><br>
                    <input type="radio" name="gender" value="M" required>
                    <label>ชาย</label>
                    <input type="radio" name="gender" value="F" required>
                    <label>หญิง</label>
                    <input type="radio" name="gender" value="O" required>
                    <label>อื่นๆ</label><br>
                    <label>อีเมล</label>
                    <input type="email" name="email" class="form-control" id="email" required>
                    <span id="email_availability"></span><br>
                    <label>วันเกิด</label>
                    <input type="date" name="bdate" class="form-control" required>
                    <label>เบอร์โทรศัพท์</label>
                    <input type="text" name="tel" class="form-control mb-3" required id="txt_telephone" maxlength="10">
                    <input type="submit" class="btn btn-primary" name="submit" value="สมัครสมาชิก" id="register" disabled>
                    <input type="reset" class="btn btn-danger" name="cancel" value="ยกเลิก"><br><br>
                </form>
            </div>
        </div>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.6.1.js"></script>
<script>
    $(document).ready(function() {
    $('#username').on('blur', function() {
        var username = $(this).val();
        $.ajax({
            url: 'check_availability.php',
            type: 'POST',
            data: {username: username},
            success: function(response) {
                if (response == 'taken') {
                    $('#username_availability').html('<span class="text-danger"> This username is already taken </span>');
                    $('#register').attr("disabled", true);
                } else if (response == 'available') {
                    $('#username_availability').html('<span class="text-success"> This username is available </span>');
                    $('#register').attr("disabled", false);
                }
            }
        });
    });
    
    $('#email').on('blur', function() {
        var email = $(this).val();
        $.ajax({
            url: 'check_availability.php',
            type: 'POST',
            data: {email: email},
            success: function(response) {
                if (response == 'taken') {
                    $('#email_availability').html('<span class="text-danger">This email is already taken </span>');
                    $('#register').attr("disabled", true);
                } else if (response == 'available') {
                    $('#email_availability').html('<span class="text-success">This email is available</span>');
                    $('#register').attr("disabled", false);
                }
            }
        });
    });
});

</script>

</html>