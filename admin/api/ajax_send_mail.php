<?php
session_start(); 
if(!isset($_SESSION['admin_username'])){
    echo "<script>alert('Bạn cần phải đăng nhập vào hệ thống');</script>";
    echo "<script>window.location.href='../../Login.php';</script>"; 
    exit(); 
}
include('../../includes/connect.php'); 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../libs/PhpMailer/vendor/autoload.php'; 
$baseUrl = 'https://yourwebsite.com'; 

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emails = isset($_POST['emails']) ? $_POST['emails'] : [];

    if (empty($emails)) {
        echo json_encode([
            'success' => false,
            'message' => 'Không có người nhận nào được chọn.'
        ]);
        exit;
    }

    // Validate email format
    foreach ($emails as $email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode([
                'success' => false,
                'message' => "Địa chỉ email không hợp lệ: $email"
            ]);
            exit;
        }
    }

    try {
        $con->set_charset('utf8mb4');

        $sql = "SELECT id, name, brand, Image, import_price, UnitsAvailable, NguongCanhBao 
                FROM products
                WHERE UnitsAvailable <= NguongCanhBao
                ORDER BY UnitsAvailable ASC";
        $result = $con->query($sql);

        if ($result->num_rows > 0) {
            $emailTitle = "Danh Sách Sản phẩm Cần Bổ Sung Kho";
            $emailBody = "<h3>Các sản phẩm gần hết hàng:</h3><table border='1' style='border-collapse: collapse; width: 100%;'>";
            $emailBody .= "<thead><tr>
                            <th>ID</th>
                            <th>Tên sản phẩm</th>
                            <th>Tác giả</th>
                            <th>Hình ảnh</th>
                            <th>Giá nhập</th>
                            <th>Số lượng còn</th>
                            <th>Ngưỡng cảnh báo</th>
                           </tr></thead><tbody>";

            while ($row = $result->fetch_assoc()) {
                $imageUrl = $baseUrl . $row['Image']; 
                $emailBody .= "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['name']}</td>
                                <td>{$row['brand']}</td>
                                <td><img src='{$imageUrl}' alt='Hình ảnh' style='width: 50px; height: auto;'></td>
                                <td>{$row['import_price']}</td>
                                <td>{$row['UnitsAvailable']}</td>
                                <td>{$row['NguongCanhBao']}</td>
                               </tr>";
            }
            $emailBody .= "</tbody></table>";
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Không có sản phẩm nào cần bổ sung kho.'
            ]);
            exit;
        }

        // Initialize PHPMailer
        $mail = new PHPMailer(true);

        // Configure SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP host
        $mail->SMTPAuth = true;
        $mail->Username = 'lyquockhanh020903@gmail.com'; // Replace with your email
        $mail->Password = 'yvof vniw ioov bbzz'; // Replace with your app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->CharSet = 'UTF-8';

        $mail->setFrom('lyquockhanh020903@gmail.com', 'Quản Lý Sản Phẩm'); // Replace with your email/name

        foreach ($emails as $email) {
            $mail->addAddress($email);
        }

        $mail->isHTML(true);
        $mail->Subject = $emailTitle;
        $mail->Body = $emailBody;

        if ($mail->send()) {
            echo json_encode([
                'success' => true,
                'message' => 'Email đã được gửi thành công!'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Gửi email thất bại. Hãy thử lại sau.'
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Lỗi: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Yêu cầu không hợp lệ.'
    ]);
}
?>
