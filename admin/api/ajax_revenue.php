<?php
session_start(); 
if(!isset($_SESSION['admin_username'])){
    echo "<script>alert('Bạn cần phải đăng nhập vào hệ thống');</script>";
    echo "<script>window.location.href='../../Login.php';</script>"; 
    exit(); 
}
header('Content-Type: application/json; charset=utf-8');
include('../../includes/connect.php');

if ($con->connect_error) {
    echo json_encode(['error' => 'Không thể kết nối đến cơ sở dữ liệu.']);
    exit;
}

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 7; 
$offset = ($page - 1) * $limit; 

$sql = "SELECT id, name, brand, Image, (UnitsSold * export_price) AS revenue
        FROM products
        ORDER BY revenue DESC
        LIMIT ?, ?";

$stmt = $con->prepare($sql);
$stmt->bind_param("ii", $offset, $limit); 

if ($stmt === false) {
    echo json_encode(['error' => 'Có lỗi xảy ra khi chuẩn bị truy vấn.']);
    exit;
}

$stmt->execute();
$result = $stmt->get_result();

$products = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['revenue'] = (float)$row['revenue']; // Ép kiểu sang số thực
        $products[] = $row;
    }
} else {
    $products = [];
}

$sqlTotal = "SELECT COUNT(*) AS total_products FROM products";
$resultTotal = $con->query($sqlTotal);
$totalProducts = $resultTotal->fetch_assoc()['total_products'];

$totalPages = ceil($totalProducts / $limit);

$stmt->close();
$con->close();

echo json_encode([
    'products' => $products,
    'totalProducts' => $totalProducts,
    'totalPages' => $totalPages,
    'currentPage' => $page
], JSON_UNESCAPED_UNICODE);
?>
