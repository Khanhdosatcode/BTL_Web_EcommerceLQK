<?php
session_start(); 
if(!isset($_SESSION['admin_username'])){
    echo "<script>alert('Bạn cần phải đăng nhập vào hệ thống');</script>";
    echo "<script>window.location.href='../../Login.php';</script>"; 
    exit(); 
}
require '../../libs/PhpSpreadSheet/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

require_once('../../includes/connect.php');

$sql = "SELECT * FROM products";
$result = $con->query($sql);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'ID')
      ->setCellValue('B1', 'Tên Sản Phẩm')
      ->setCellValue('C1', 'Thương Hiệu')
      ->setCellValue('D1', 'Ảnh')
      ->setCellValue('E1', 'Số Lượng Bán');

$rowNum = 2;  // Dòng bắt đầu ghi dữ liệu
while ($row = $result->fetch_assoc()) {
    $sheet->setCellValue('A' . $rowNum, $row['id'])
          ->setCellValue('B' . $rowNum, $row['name'])
          ->setCellValue('C' . $rowNum, $row['brand'])
          ->setCellValue('D' . $rowNum, $row['Image'])
          ->setCellValue('E' . $rowNum, $row['UnitsSold']);
    $rowNum++;
}

//Thiết lập các header cho trình duyệt để tải file excel
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="DanhSachSanPhamBanChay.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

$con->close();
?>
