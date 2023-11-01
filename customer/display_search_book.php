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
    $bookid = $_GET['bookid'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>my_work</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</head>

<body>
    <?php
    include "nav.php";
    ?>
    <div class="container px-4 px-lg-5 mt-3">

        <div class="d-flex justify-content-between">
            <h2>
                <div>ผลงานของฉัน</div>
            </h2>
            <div class="d-flex justify-content-end">
                <a class="btn btn-success mb-4 me-2" href="promotion.php" role="button">
                    <h4>+โปรโมชั่น</h4>
                </a>

                <a class="btn btn-primary mb-4" href="add_book.php" role="button">
                    <h4>+เพิ่มผลงาน</h4>
                </a>
            </div>
        </div>
        <div class="alert alert-primary h4 text-start mb-4 mt-4 " role="alert">
            รายรับที่ได้ 0
        </div>
        <h4>
            <div>หนังสือของฉัน</div>
        </h4>
        <div class="mb-3">
            <a href="my_work.php"><button type="button" class="btn btn-outline-success">อนุมัติ</button></a>
            <a href="draf.php"><button type="button" class="btn btn-outline-success">ฉบับร่าง</button></a>
            <a href="waitapp.php"><button type="button" class="btn btn-outline-success">รออนุมัติ</button></a>
        </div>
        <form method="POST" class="form-inline d-flex">
            <input class="form-control me-2" id="search2" type="text" placeholder="ชื่อหนังสือ/ผู้เผยแพร่/หมวดหมู่">
        </form>
        <div class="list-group list-group-item-action" id="content2"></div>
        

        <script>
            $(document).ready(function() {
                $('#search2').keyup(function() {
                    var Search = $('#search2').val(); // getvalue

                    if (Search != '') {
                        $.ajax({
                            url: "search_book.php",
                            method: "POST",
                            data: {
                                search: Search
                            },
                            success: function(data) {
                                $('#content2').html(data);
                            }
                        })
                    } else {
                        $('#content2').html('');
                    }
                });
            });
        </script>
        
        <?php
        $col = "*";
        $table = "book inner join publisher on pub_id = book_pubid
        inner join customer on cus_id = pub_cusid";
        $where = "pub_cusid = '$cusid' and book_status = '2' and book_id = '$bookid' ORDER BY book_dateup DESC";
        $sqlbook = select_where($col, $table, $where);
        ?>
        <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4">
            <?php
            if ($sqlbook->num_rows > 0) {
                while ($row = $sqlbook->fetch_assoc()) {
                    $status = $row['book_status'];
                    if ($status === '2') {
                        $status = 'อนุมัติ';
                    }
            ?>
                    <div class="col sm-3">
                        <div class="text-center mb-3">
                            <img src="<?php echo $row['book_cover'] ?>" width="200px" height="250px" class="mt-5 p-2 my-2 border">
                            <?php
                            echo "<h4 class= 'text-success'>$status</h4>";
                            echo "<h5>ชื่อเรื่อง</h5>";
                            echo "<h4>" . $row['book_name'] . "</h4>";
                            echo "<h5>ราคา</h5>";
                            echo "<h4 class= 'text-danger'>" . number_format($row['book_price'], 2) . " <i class='fas fa-coins'></i></h4>";
                            echo "<h5>ผู้เผยแพร่</h5>";
                            echo "<h4>" . $row['pub_name'] . "</h4>";
                            ?>
                            <!-- Button trigger modal -->
                            <a href='readbook.php?bookid=<?php echo $row['book_id'] ?>'><button class='btn btn-danger'>อ่าน</button></a>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#<?php echo $row['book_id'] ?>">รายละเอียด</button>
                            <!-- Modal -->
                            <div class="modal fade" id="<?php echo $row['book_id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">รายละเอียด</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <img src="<?php echo $row['book_cover'] ?>" width="200px" height="250px" class="mt-5 p-2 my-2 border">
                                            <?php
                                            echo "<h4 class= 'text-success'>$status</h4>";
                                            echo "<h5>ชื่อเรื่อง</h5>";
                                            echo "<h4>" . $row['book_name'] . "</h4>";
                                            echo "<h5>ราคา</h5>";
                                            echo "<h4 class= 'text-danger'>" . number_format($row['book_price'], 2) . " <i class='fas fa-coins'></i></h4>";
                                            echo "<h5>เนื้อเรื่องย่อ</h5>";
                                            echo "<p>" . $row['book_summary'] . "</p>";
                                            echo "<h5>ผู้เผยแพร่</h5>";
                                            echo "<h4>" . $row['pub_name'] . "</h4>";
                                            echo "<a href='testread.php?bookid=" . $row['book_id'] . "'><button class='btn btn-primary'>ทดลองอ่าน</button></a>";
                                            ?>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php
                }
            }
            connectdb()->close();
            ?>
        </div>
    </div>
</body>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

</html>