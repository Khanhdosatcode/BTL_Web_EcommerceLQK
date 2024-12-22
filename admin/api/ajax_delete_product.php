<?php
include('../../includes/connect.php'); 

// Kiểm tra nếu có id sản phẩm để xóa
if (isset($_POST['id'])) {
    $delete_id = $_POST['id'];
    
    // Thực hiện câu lệnh xóa
    $delete_query = "DELETE FROM `products` WHERE id = $delete_id";
    $delete_result = mysqli_query($con, $delete_query);

    // Kiểm tra kết quả và trả về phản hồi JSON
    if (!$delete_result) {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi khi xóa sản phẩm.']);
    } else {
        echo json_encode(['status' => 'success', 'message' => 'Sản phẩm đã được xóa.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy sản phẩm để xóa.']);
}
?>
