<?php
session_start(); 
if(!isset($_SESSION['admin_username'])){
    echo "<script>alert('Bạn cần phải đăng nhập vào hệ thống');</script>";
    echo "<script>window.location.href='../../Login.php';</script>"; 
    exit(); 
}
header('Content-Type: application/json; charset=utf-8');
require_once('../../includes/connect.php');  

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;  // Trang hiện tại, mặc định là 1
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 7;  // Số lượng sản phẩm mỗi trang, mặc định là 7

// Tính toán OFFSET
$offset = ($page - 1) * $limit;

$totalQuery = "SELECT COUNT(*) AS total FROM products";
$totalResult = $con->query($totalQuery);
$totalProducts = 0;

if ($totalResult && $totalResult->num_rows > 0) {
    $totalProducts = $totalResult->fetch_assoc()['total'];
}

$totalPages = ceil($totalProducts / $limit);

$sql = "SELECT id, name, brand, Image, UnitsSold 
        FROM products
        ORDER BY UnitsSold DESC
        LIMIT $limit OFFSET $offset";  // Thêm LIMIT và OFFSET để phân trang

$result = $con->query($sql);

$products = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['UnitsSold'] = (int)$row['UnitsSold'];
        $images = explode(',', $row['Image']);
        $row['Image'] = $images[0];  
        $products[] = $row;
    }
}

$con->close();

echo json_encode([
    'products' => $products,  
    'totalPages' => $totalPages,  
    'currentPage' => $page, 
    'totalProducts' => $totalProducts  
], JSON_UNESCAPED_UNICODE);
?>
