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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>addbook</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
</head>


<body>
    <div class="container">
        <br><br>
        <div class="row d-flex justify-content-center">
            <div class="col-md-5 bg-light text-dark">
                <br>
                <div class="alert alert-primary h4 text-center mb-4 mt-4 " role="alert">
                    เพิ่มหนังสือ
                    
                </div>
                <form method="POST" action="insert_book.php" enctype="multipart/form-data">

                    <label>ชื่อหนังสือ</label>
                    <input type="text" name="bname" class="form-control" required placeholder="name">
                    <label>หน้าปก</label>
                    <input type="file" name="file1" class="form-control" accept=".png,.jpeg,.jpg" required>
                    <p class="text-danger">รองรับเฉพาะ img,png เท่านั้น</p>
                    <p class="text-danger"><?php if (isset($_SESSION['error'])) echo $_SESSION['error']?></p>
                    <label>เนื้อหา</label>
                    <input type="file" name="file2" class="form-control" accept="application/pdf" required>
                    <p class="text-danger">รองรับเฉพาะ pdf ที่ขนาด A4 เท่านั้น</p>
                    <p class="text-danger"><?php if (isset($_SESSION['error2'])) echo $_SESSION['error2']?></p>
                    <label>ทดลองอ่าน</label>
                    <input type="file" name="file3" class="form-control" accept="application/pdf" required>
                    <p class="text-danger">รองรับเฉพาะ pdf ที่ขนาด A4 เท่านั้น</p>
                    <p class="text-danger"><?php if (isset($_SESSION['error3'])) echo $_SESSION['error3']?></p>
                    <label>หมวดหมู่</label><br>
                    <?php
                    //query typebook
                    $result = select("*", "typebook");
                    while ($row = $result->fetch_assoc()) {
                    ?>
                        <input type="checkbox" name="type_book[]" value="<?= $row['type_id'] ?>">
                        <?= $row['type_name'] ?>
                    <?php
                    }
                    ?><br>
                    <label>เรื่องย่อ</label>
                    <textarea name="summary" class="form-control" required placeholder="summary"></textarea>
                    <label>ราคา</label>
                    <input type="number" name="price" class="form-control" required placeholder="price"><br>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>แท็ก</label><a href="javascript:void(0)" class="add-more-form float-end btn btn-primary">เพิ่มแท็ก</a>
                                <input type="text" name="tag[]" class="form-control" required placeholder="tag">
                            </div>
                        </div>
                    </div>
                    <div class="paste-new-forms"></div><br>

                    <div style="text-align: center;">
                        <input type="submit" class="btn btn-primary" name="submit" value="บันทึกข้อมูล">
                        <input type="reset" class="btn btn-danger" name="cancel" value="ยกเลิก"> <br><br>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.1.js"></script>
    <script>
        $(document).ready(function() {

            $(document).on('click', '.remove-btn', function() {
                $(this).closest('.row').remove();
            });

            $(document).on('click', '.add-more-form', function() {
                $('.paste-new-forms').append('<div class="row">\
                    <div class="col-md-4">\
                            <div class="form-group">\
                                <label>แท็ก</label>\
                                <input type="text" name="tag[]" class="form-control" required placeholder="tag">\
                            </div>\
                        </div>\
                        <div class="col-md-4">\
                            <div class="form-group"><br>\
                                <button type="button" class="remove-btn btn btn-danger">ลบ</button>\
                            </div>\
                        </div>\
                    </div>');
            });

        });
    </script>
</body>
<?php
connectdb()->close();
?>
</html>
