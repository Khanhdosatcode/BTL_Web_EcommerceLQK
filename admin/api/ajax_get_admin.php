<?php
session_start(); 
if(!isset($_SESSION['admin_username'])){
    echo "<script>alert('Bạn cần phải đăng nhập vào hệ thống');</script>";
    echo "<script>window.location.href='../../Login.php';</script>"; 
    exit(); 
}
include('../../includes/connect.php');  
$adminUsername = $_SESSION['admin_username']; 

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';  
$searchTerm = '%' . $searchQuery . '%';  
$sql = "SELECT name, username, email, role 
        FROM users 
        WHERE (name LIKE ? OR email LIKE ?) 
        AND role = 'admin' 
        AND username != ?";  

$stmt = $con->prepare($sql);

if (!$stmt) {
    die("Error in SQL prepare statement: " . $con->error);
}

$stmt->bind_param('sss', $searchTerm, $searchTerm ,$adminUsername);  
$stmt->execute();
$result = $stmt->get_result();
$users = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;  
    }
} else {
    $users = ['message' => 'No results found.'];
}
$stmt->close();
$con->close();

header('Content-Type: application/json');
echo json_encode(["users" => $users]);
?>
