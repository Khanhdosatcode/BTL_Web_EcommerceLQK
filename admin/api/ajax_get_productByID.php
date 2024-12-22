<?php
include('../../includes/connect.php');

$response = array();

if (isset($_POST['id'])) {
    $edit_id = $_POST['id']; 

    $stmt = $con->prepare("SELECT * FROM `products` WHERE id = ?");
    $stmt->bind_param("i", $edit_id); 
    $stmt->execute();
    $get_data_result = $stmt->get_result();

    if ($get_data_result && $get_data_result->num_rows > 0) {
        $row_fetch_data = $get_data_result->fetch_assoc();

        $response['status'] = 'success';
        $response['data'] = array(
            'product_id' => $row_fetch_data['id'],
            'product_name' => $row_fetch_data['name'],
            'product_brand' => $row_fetch_data['brand'],
            'product_description' => $row_fetch_data['description'],
            'category_id' => $row_fetch_data['category_id'],
            'product_image' => explode(',', $row_fetch_data['Image']), 
            'import_price' => $row_fetch_data['import_price'],
            'export_price' => $row_fetch_data['export_price'],
            'UnitsAvailable' => $row_fetch_data['UnitsAvailable']
        );
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Không tìm thấy sản phẩm.';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'ID sản phẩm không hợp lệ.';
}

echo json_encode($response);

$stmt->close();
?>
