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
    <title>Cart</title>
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
        <div class="row">
            <div class="col-md-10">
                <h2 class="text-center my-3">ตะกร้าสินค้า</h2>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ลำดับ</th>
                            <th>รูปภาพ</th>
                            <th>รหัส</th>
                            <th>ชื่อสินค้า</th>
                            <th>ราคา</th>
                            <th>ลบ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        $total = 0;
                        $sum1 = 0;
                        $sum2 = 0;

                        
                        $sqlcart = "select * from book inner join cart on book_id = cart_bookid
                        where book_status = '2' and cart_cusid = '$cusid'";
                        
                        $bookIds = array();
                        $book_names = array();

                        $result = connectdb()->query($sqlcart);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $bookid = $row['book_id'];
                                $book_name = $row['book_name'];
                                $price = $row['book_price'];
                                $proid = $row['cart_proid'];
                                array_push($bookIds, $bookid);
                                array_push($book_names, $book_name);
                                
                                $sqlcart_pro = "select *,book_price - pro_discount as discount
                                from promotion inner join bookpro on pro_id = bpro_proid 
                                inner join book on bpro_bookid = book_id
                                where (bpro_bookid = '$bookid'and bpro_proid = '$proid') and book_status = '2' and pro_edate >= CURDATE()+ INTERVAL 1 DAY";
                                $ex_cartpro = connectdb()->query($sqlcart_pro);
                                

                                ?>
                                <tr>
                                    <td>
                                        <?php echo $i; ?>
                                    </td>
                                    <td><img src="<?php echo $row['book_cover'] ?>" class="card-img-top" width="80px" height="100px"></td>
                                    <td>
                                        <?= $row['book_id'] ?>
                                    </td>
                                    <td>
                                        <?= $row['book_name'] ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($ex_cartpro->num_rows > 0){
                                            $row2 = $ex_cartpro->fetch_assoc();
                                            echo "<del class='text-danger'>$row[book_price]</del>";
                                            echo number_format($row2['discount'], 2);
                                            $sum1 = $sum1 + $row2['discount'];
                                            
                                            
                                        }
                                        else{
                                            echo number_format($price, 2);
                                            $sum2 = $sum2 + $price;
                                            
                                        }
                                        if (isset($sum1)&&isset($sum2)){
                                            $total = $sum1+$sum2;
                                        }
                                        else{
                                            $total = $total + $sum2;
                                        }
                                        
                                        ?>
                                        
                                    </td>
                                    <script>
                                        function canclebook(cancle) {
                                            let agreecancle = confirm("ต้องการลบ");
                                            if (agreecancle) {
                                                window.location = cancle;
                                            }
                                        }
                                    </script>
                                    <td>

                                        <a onclick="canclebook(this.href); return false;" href="remove_cart.php?bookid=<?php echo $row['book_id'] ?>&act=remove"><button type='button' class='btn btn-danger'>ลบ</button></a>

                                    </td>

                                </tr>
                                <?php
                                $i++;
                                
                                ?>
                        </tbody>
                            <?php
                            }
                            ?>
                            <tr>
                                <td class="text-end" colspan="4" id="nn">ราคารวมสุทธิ</td>
                                <td class="text-center"><b class="text-danger">
                                        <?php echo number_format($total, 2) ?>
                                    </b></td>
                                <td>เหรียญ</td>
                            </tr>
                            <tr>
                            <td class="text-end" colspan="4"><a href='index.php'><button type='button' class="btn btn-outline-secondary">เลือกสินค้า</button></a></td>
                                <?php
                                    $sqlcus = select_where("cus_coin", "customer", "cus_id = '$cusid'");
                                    $sqlprice = "select book_id,book_price from book
                                    where book_id = '$bookid'";
                                    $ex_price = connectdb()->query($sqlprice);
                                    if ($sqlcus->num_rows > 0 ) {
                                        $row = $ex_price->fetch_assoc();
                                        $row2 = $sqlcus->fetch_assoc();
                                        if ($row2['cus_coin'] < $total) {
                                            echo '<script>
                                                function checkcoin(mycoin) {
                                                    let conf = confirm("เหรียญไม่พอต้องเติมเหรียญก่อน");
                                                    if (conf) {
                                                        window.location = mycoin;
                                                    }
                                                }
                                            </script>';
                                        
                                            echo "<td class='text-center'><a <a onclick='checkcoin(this.href); return false;' href='add_coin.php'><button type='button' class='btn btn-primary'>ชำระเงิน</button></a></td>";
                                        } else {
                                
                                            $_SESSION['coin'] = $row2['cus_coin'];
                                            $_SESSION['total'] = $total;
                                ?>
                            <td class="text-center"><a href='insert_receipt.php'><button type='button' class="btn btn-primary">ชำระเงิน</button></a></td>
                    <?php
                        }
                    }
                
                                }
                ?>
                <td><a href='cancle_cart.php?act=cancle'><button type='button' class='btn btn-danger'>ล้างตะกร้า</button></a></td>
                    </tr>
                </table>
            </div>
        </div>
        <?php
        // แปลงเป็นสตริงและเติม ""
        $bookIdsString = implode(',', array_map(function($id) {
            return '"' . $id . '"';
        }, $bookIds));
        ?>
        <style>
            .card {
                width: 12rem; /* ปรับขนาดการ์ดตามที่ต้องการ */
                margin: 0.5rem; /* ปรับช่องว่างระหว่างการ์ดตามที่ต้องการ */
            }
        </style>

        <div>หมวดหมู่เดียวกัน</div>
        <div class="card-group" id="cardGroup"></div>

        <script>
            $(document).ready(function(){
                
                let cardCount = 0; // เพิ่มตัวแปรนับการวางไพ่

                let requestData = {
                    titles: [<?php echo $bookIdsString;?>]
                };

                $.ajax({
                    url: "http://localhost:5002/recommendation",
                    type: "POST",
                    contentType: "application/json",
                    data: JSON.stringify(requestData),
                    dataType: "json",
                    success: function(response) {
                        const cardGroup = $('#cardGroup');

                        console.log(response);

                        // เพิ่มตัวแปรสำหรับเก็บแถว
                        let row = $('<div class="row"></div>');

                        for (let i = 0; i < response.recommendations.length; i++) {
                            let item = response.recommendations[i];

                            let card = `
                            <div class="card">
                                <img src="${item.book_cover}" class="card-img-top" alt="${item.book_name}" style="max-height: 120px;">
                                <div class="card-body">
                                    <div class="card-title" style="color: green;">${item.book_name}</div>
                                    <div class="card-title" style="color: red;">ราคา ${item.book_price} <i class="fas fa-coins"></i></div>
                                    <a href="search_content.php?bookid=${item.book_id}" class="btn btn-primary">ดูรายละเอียด</a>
                                </div>
                            </div>
                            `;

                            row.append(card);
                            cardCount++;

                            // เมื่อ cardCount ถึง 5 ให้ปิดแถวปัจจุบันและเริ่มแถวใหม่
                            if (cardCount % 5 === 0) {
                                cardGroup.append(row);
                                row = $('<div class="row"></div>'); // สร้างแถวใหม่
                            }
                        }
                        // ตรวจสอบว่ามีการเพิ่มไพ่ที่ไม่ครบ 5 ในแถวสุดท้ายหรือไม่
                        if (cardCount % 5 !== 0) {
                            cardGroup.append(row);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });
        </script>

        <?php
        // แปลงเป็นสตริงและเติม ""
            $book_namesString = implode(',', array_map(function($name) {
                return '"' . $name . '"';
            }, $book_names));
        ?>

        <div>หนังสือที่คุณอาจชอบ</div>
        <div class="card-group" id="cardGroup2"></div>

        <script>
            $(document).ready(function(){
                let cardCount = 0; // เพิ่มตัวแปรนับการวางไพ่

                let requestData = {
                    book_names: [<?php echo $book_namesString;?>]
                };

                $.ajax({
                    url: "http://localhost:5001/recommendation",
                    type: "POST",
                    contentType: "application/json",
                    data: JSON.stringify(requestData),
                    dataType: "json",
                    success: function(response) {
                        const cardGroup2 = $('#cardGroup2');

                        console.log(response);

                        // เพิ่มตัวแปรสำหรับเก็บแถว
                        let row = $('<div class="row"></div>');

                        for (let i = 0; i < response.recommendations.length; i++) {
                            let items = response.recommendations[i];

                            for (let j = 0; j < items.length; j++) {
                                let item = items[j];

                                let card = `
                                    <div class="card">
                                        <img src="${item.book_cover}" class="card-img-top" alt="${item.book_name}" style="max-height: 120px;">
                                        <div class="card-body">
                                            <div class="card-title" style="color: green;">${item.book_name}</div>
                                            <div class="card-title" style="color: red;">ราคา ${item.book_price} <i class="fas fa-coins"></i></div>
                                            <a href="search_content.php?bookid=${item.book_id}" class="btn btn-primary">ดูรายละเอียด</a>
                                        </div>
                                    </div>
                                `;

                                row.append(card);
                                cardCount++;

                                // เมื่อ cardCount ถึง 5 ให้ปิดแถวปัจจุบันและเริ่มแถวใหม่
                                if (cardCount % 5 === 0) {
                                    cardGroup2.append(row);
                                    row = $('<div class="row"></div>'); // สร้างแถวใหม่
                                }
                            }
                        }

                        // ตรวจสอบว่ามีการเพิ่มไพ่ที่ไม่ครบ 5 ในแถวสุดท้ายหรือไม่
                        if (cardCount % 5 !== 0) {
                            cardGroup2.append(row);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });
        </script>
    </div>
    <?php
    connectdb()->close();
    ?>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

</html>