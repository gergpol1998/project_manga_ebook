<!DOCTYPE html>
<html>
  <head>
    <title>Login Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css">
  </head>
  <body>
    <div class="container mt-5">
      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="card">
            <div class="card-header bg-primary text-white">
              <h4 class="text-center">เข้าสู่ระบบ</h4>
            </div>
            <div class="card-body">
              <form method="POST" action="login_check.php">
                <div class="form-group">
                  <label for="username">Username</label>
                  <input type="text" class="form-control" id="username" name="username">
                </div>
                <div class="form-group">
                  <label for="password">Password</label>
                  <input type="password" class="form-control mb-2" id="password" name="password">
                </div>
                <button type="submit" class="btn btn-primary">เข้าสู่ระบบ</button>
                <hr>
                <p>ยังไม่มีบัญชี ? <a href="register.php">สมัครสมาชิกที่นี่</a> ลืมรหัสผ่าน ? <a href="forgot_pass.php">เปลี่ยนรหัสผ่าน</a></p>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
