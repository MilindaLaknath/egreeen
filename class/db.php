<?php
// 1. Create a database connection
$connection = mysqli_connect("localhost", "root", "", "greeen");
//$connection = mysqli_connect("localhost", "milindal", "M!1!n6aL", "green");
// Test if connection succeeded
if (mysqli_connect_errno()) {
    die("Database connection failed: " .
            mysqli_connect_error() .
            " (" . mysqli_connect_errno() . ")"
    );
}
?>



