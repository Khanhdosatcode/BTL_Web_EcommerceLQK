<?php
session_start(); 
if(!isset($_SESSION['admin_username'])){
    echo "<script>alert('Bạn cần phải đăng nhập vào hệ thống');</script>";
    echo "<script>window.location.href='../../Login.php';</script>"; 
    exit(); 
}
// Bao gồm autoload từ Composer để sử dụng PhpSpreadsheet
require '../../libs/PhpSpreadSheet/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

require_once('../../includes/connect.php');

$get_product_query = "SELECT * FROM `products` WHERE 1";

if (isset($_POST['category']) && $_POST['category'] != 0) {
    $category_id = $_POST['category'];
    $get_product_query .= " AND category_id = $category_id";
}

$search = isset($_POST['search']) ? $_POST['search'] : '';
if (!empty($search)) {
    $get_product_query .= " AND name LIKE '%$search%'";
}

$get_product_query .= " ORDER BY id DESC";

$get_product_result = mysqli_query($con, $get_product_query);

// Tạo đối tượng Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Đặt tiêu đề cột
$sheet->setCellValue('A1', 'ID')
      ->setCellValue('B1', 'Tên Sản Phẩm')
      ->setCellValue('C1', 'Ảnh')
      ->setCellValue('D1', 'Giá Nhập')
      ->setCellValue('E1', 'Giá Xuất')
      ->setCellValue('F1', 'Số Lượng Hiện Có')
      ->setCellValue('G1', 'Số Lượng Đã Bán');

// Lặp qua dữ liệu và ghi vào file Excel
$rowNum = 2;  // Dòng bắt đầu ghi dữ liệu
while ($row_fetch_products = mysqli_fetch_array($get_product_result)) {
    $product_id = $row_fetch_products['id'];
    $name = $row_fetch_products['name'];
    $image = $row_fetch_products['Image'];
    $product_images = explode(',', $image);
    $image = $product_images[0]; // Lấy ảnh đầu tiên (nếu có nhiều ảnh)
    $import_price = number_format($row_fetch_products['import_price'], 0, '.', '.');
    $export_price = number_format($row_fetch_products['export_price'], 0, '.', '.');
    $UnitsAvailable = $row_fetch_products['UnitsAvailable'];
    $UnitsSold  = $row_fetch_products['UnitsSold'];

    // Ghi thông tin sản phẩm vào các ô trong file Excel
    $sheet->setCellValue('A' . $rowNum, $product_id)
          ->setCellValue('B' . $rowNum, $name)
          ->setCellValue('C' . $rowNum, $image)  // Chứa đường dẫn ảnh
          ->setCellValue('D' . $rowNum, $import_price)
          ->setCellValue('E' . $rowNum, $export_price)
          ->setCellValue('F' . $rowNum, $UnitsAvailable)
          ->setCellValue('G' . $rowNum, $UnitsSold);
    
    $rowNum++;
}

// Thiết lập header để xuất tệp Excel
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="DanhSachSanPham.xlsx"');
header('Cache-Control: max-age=0');

// Xuất file Excel
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

// Đóng kết nối cơ sở dữ liệu
mysqli_close($con);
?>
