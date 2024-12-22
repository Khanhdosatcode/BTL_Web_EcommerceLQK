<?php
$con = new mysqli('localhost', 'root', '', 'ecommercelqk');

// Kiểm tra kết nối
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
?>
