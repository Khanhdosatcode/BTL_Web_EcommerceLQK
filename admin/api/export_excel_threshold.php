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

// Truy vấn dữ liệu
$sql = "SELECT id, name, brand, Image, import_price, UnitsAvailable, NguongCanhBao 
        FROM products
        WHERE UnitsAvailable <= NguongCanhBao
        ORDER BY UnitsAvailable ASC";
$result = $con->query($sql);

// Tạo đối tượng Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Đặt tiêu đề cột
$sheet->setCellValue('A1', 'ID')
      ->setCellValue('B1', 'Tên Sản Phẩm')
      ->setCellValue('C1', 'Thương Hiệu')
      ->setCellValue('D1', 'Ảnh')
      ->setCellValue('E1', 'Giá Nhập')
      ->setCellValue('F1', 'Số Lượng Hiện Có')
      ->setCellValue('G1', 'Ngưỡng Cảnh Báo');

// Lặp qua dữ liệu và ghi vào file Excel
$rowNum = 2;  // Dòng bắt đầu ghi dữ liệu
while ($row = $result->fetch_assoc()) {
    $sheet->setCellValue('A' . $rowNum, $row['id'])
          ->setCellValue('B' . $rowNum, $row['name'])
          ->setCellValue('C' . $rowNum, $row['brand'])
          ->setCellValue('D' . $rowNum, $row['Image'])
          ->setCellValue('E' . $rowNum, $row['import_price'])
          ->setCellValue('F' . $rowNum, $row['UnitsAvailable'])
          ->setCellValue('G' . $rowNum, $row['NguongCanhBao']);
    $rowNum++;
}

// Thiết lập header để xuất tệp Excel
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="SanPhamSapHetHang.xlsx"');
header('Cache-Control: max-age=0');

// Xuất file Excel
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

// Đóng kết nối cơ sở dữ liệu
$con->close();
?>
