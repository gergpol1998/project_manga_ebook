<?php
include "function.php";
connectdb();

// Check if a username is available
if (isset($_POST['username'])) {
    $username = mysqli_real_escape_string(connectdb(), $_POST['username']);
    $result = select_where("cus_uname","customer","cus_uname = '$username'");
    if ($result->num_rows > 0) {
        echo "taken";
    } else {
        echo "available";
    }
}

// Check if an email is available
if (isset($_POST['email'])) {
    $email = mysqli_real_escape_string(connectdb(), $_POST['email']);
    $result = select_where("cus_email","customer","cus_email = '$email'");
    if ($result->num_rows > 0) {
        echo "taken";
    } else {
        echo "available";
    }
}

// Close the database connection
connectdb()->close();

?>