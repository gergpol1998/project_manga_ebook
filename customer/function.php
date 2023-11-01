<?php
//connect db
function connectdb(){
    $servername = "103.114.200.105:9906";
    $username = "root";
    $password = "root";
    $db = "ebook";
    
    // Create connection
    $conn = new mysqli($servername, $username, $password ,$db);
    
    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
    //echo "Connected successfully";
    return $conn;
}
function autoid($label,$max_id,$table,$null_id){
    $code = $label; //กำหนดอักษรนำหน้า
    $yearMonth = substr(date("Y") + 543, -2) . date("m"); //ดึงค่าปี เดือน ปัจจุบัน
    //query MAX ID
    $sql = "SELECT MAX($max_id) AS LAST_ID FROM $table";
    $result = connectdb()->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $maxId = substr($row['LAST_ID'],-7); //ดึงค่าไอดีล่าสุดจากตารางข้อมูลที่จะบันทึก
    
    if ($maxId == '') {
        $maxId = $null_id;
    } else {
            $lastyear = substr($row['LAST_ID'],4,-9);
            $lastmonth = substr($row['LAST_ID'],6,-7);
            $currentyear = substr(date("Y") + 543, -2);
            $currentmonth = date("m");

            if ($lastyear !== $currentyear || $lastmonth !== $currentmonth){
                $maxId = $null_id;
            }
            else{
                $maxId = ($maxId + 1);  //บวกค่าเพิ่มอีก 1
            }
    }
    $maxId = str_pad($maxId,7,'0',STR_PAD_LEFT);
    $nextId = $code . $yearMonth . $maxId; //นำข้อมูลทั้งหมดมารวมกัน
    return $nextId;
    }
}

function tagautoid(){
    $code = "TAG-"; //กำหนดอักษรนำหน้า
    //query MAX ID
    $sql = "SELECT MAX(tag_id) AS LAST_ID FROM tag";
    $result = connectdb()->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    $maxId = substr($row['LAST_ID'],-11); //ดึงค่าไอดีล่าสุดจากตารางข้อมูลที่จะบันทึก
    
    if ($maxId == '') {
        $maxId = '00000000001';
    } else {
        $maxId = ($maxId + 1);  //บวกค่าเพิ่มอีก 1
    }
    $maxId = str_pad($maxId,11,'0',STR_PAD_LEFT);
    $nextId = $code . $maxId; //นำข้อมูลทั้งหมดมารวมกัน
    return $nextId;
    }
}
function bookautoid(){
    $code = "BOOK-"; //กำหนดอักษรนำหน้า
    $yearMonth = substr(date("Y") + 543, -2) . date("m"); //ดึงค่าปี เดือน ปัจจุบัน
    //query MAX ID
    $sql = "SELECT MAX(book_id) AS LAST_ID FROM book";
    $result = connectdb()->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $maxId = substr($row['LAST_ID'],-6); //ดึงค่าไอดีล่าสุดจากตารางข้อมูลที่จะบันทึก
    
        if ($maxId == '') {
            $maxId = "000001";
        } else {
            $lastyear = substr($row['LAST_ID'],5,-8);
            $lastmonth = substr($row['LAST_ID'],7,-6);
            $currentyear = substr(date("Y") + 543, -2);
            $currentmonth = date("m");

            if ($lastyear !== $currentyear || $lastmonth !== $currentmonth){
                $maxId = "000001";
            }
            else{
                $maxId = ($maxId + 1);  //บวกค่าเพิ่มอีก 1
            }
        }
        $maxId = str_pad($maxId,6,'0',STR_PAD_LEFT);
        $nextId = $code . $yearMonth . $maxId; //นำข้อมูลทั้งหมดมารวมกัน
        return $nextId;
    }
}
function receiptautoid(){
    $code = "REC-"; //กำหนดอักษรนำหน้า
    $yearMonth = substr(date("Y") + 543, -2) . date("m") .date("d"); //ดึงค่าปี เดือน ปัจจุบัน
    //query MAX ID
    $sql = "SELECT MAX(rec_id) AS LAST_ID FROM receipt";
    $result = connectdb()->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    $maxId = substr($row['LAST_ID'],-5); //ดึงค่าไอดีล่าสุดจากตารางข้อมูลที่จะบันทึก
    
    if ($maxId == '') {
        $maxId = "00001";
    } else {
            $lastyear = substr($row['LAST_ID'],4,-9);
            $lastmonth = substr($row['LAST_ID'],6,-7);
            $currentyear = substr(date("Y") + 543, -2);
            $currentmonth = date("m");

            if ($lastyear !== $currentyear || $lastmonth !== $currentmonth){
                $maxId = "000001";
            }
            else{
                $maxId = ($maxId + 1);  //บวกค่าเพิ่มอีก 1
            }
    }
    $maxId = str_pad($maxId,5,'0',STR_PAD_LEFT);
    $nextId = $code . $yearMonth . $maxId; //นำข้อมูลทั้งหมดมารวมกัน
    return $nextId;
    }
}
//select none where
function select($col,$table){
    $sql = "select $col from $table";
    $result = connectdb()->query($sql);
    return $result;
}
//select have where
function select_where($col,$table,$where){
    $sql = "select $col from $table where $where ";
    $result = connectdb()->query($sql);
    return $result;
}
//insert data
function insertdata($table,$values,$inputdata){
    $sql = "insert into $table ($values)
    values ($inputdata)";
    $result = connectdb()->query($sql);
    return $result;
}
//update data
function updatedata($table,$col,$where){
    $sql = "update $table set $col where $where";
    $result = connectdb()->query($sql);
    return $result;
}
//delete data
function deletedata($table,$where){
    $sql = "delete from $table where $where";
    $result = connectdb()->query($sql);
    return $result;
}

