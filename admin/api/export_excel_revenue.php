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

// Kết nối cơ sở dữ liệu
require_once('../../includes/connect.php');

$sql = "SELECT * FROM products";
$result = $con->query($sql);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'ID')
      ->setCellValue('B1', 'Tên Sản Phẩm')
      ->setCellValue('C1', 'Ảnh')
      ->setCellValue('D1', 'Doanh Thu');

$rowNum = 2;  // Dòng bắt đầu ghi dữ liệu
while ($row = $result->fetch_assoc()) {
    $sheet->setCellValue('A' . $rowNum, $row['id'])
          ->setCellValue('B' . $rowNum, $row['name'])
          ->setCellValue('C' . $rowNum, $row['Image'])
          ->setCellValue('D' . $rowNum, $row['UnitsSold']);
    $rowNum++;
}

// Thiết lập header để xuất tệp Excel
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="DanhSachSanPhamBanChay.xlsx"');
header('Cache-Control: max-age=0');

// Xuất file Excel
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

$con->close();
?>
