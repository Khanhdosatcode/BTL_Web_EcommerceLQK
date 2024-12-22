<?php
session_start(); 
if(!isset($_SESSION['admin_username'])){
    echo "<script>alert('Bạn cần phải đăng nhập vào hệ thống');</script>";
    echo "<script>window.location.href='../../Login.php';</script>"; 
    exit(); 
}
header('Content-Type: application/json; charset=utf-8');

include('../../includes/connect.php');


$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';  
$searchTerm = '%' . $searchQuery . '%'; 

$sql = "SELECT id, name, brand, Image, import_price, UnitsAvailable, NguongCanhBao 
        FROM products
        WHERE (name LIKE ? OR brand LIKE ?) 
        AND UnitsAvailable <= NguongCanhBao
        ORDER BY UnitsAvailable ASC";

$stmt = $con->prepare($sql);
$stmt->bind_param('ss', $searchTerm, $searchTerm);  // Liên kết tham số tìm kiếm
$stmt->execute();
$result = $stmt->get_result();

$books = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $row['import_price'] = (int)$row['import_price'];
        $images = explode(',', $row['Image']);
        $row['Image'] = $images[0];  // Lấy ảnh đầu tiên
        $row['UnitsAvailable'] = (int)$row['UnitsAvailable'];
        $row['NguongCanhBao'] = (int)$row['NguongCanhBao'];
        $books[] = $row;
    }
} else {
    die(json_encode(['error' => "Query Failed: " . $con->error]));
}

// Đóng kết nối
$stmt->close();
$con->close();

// Trả về dữ liệu dưới dạng JSON
echo json_encode([
    'books' => $books,
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
?>
