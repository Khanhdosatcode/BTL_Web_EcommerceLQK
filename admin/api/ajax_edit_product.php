<?php
include('../../includes/connect.php');
$response = array();
try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $product_id = $_POST['product_id'];
        $product_name = $_POST['product_name'];
        $product_brand = $_POST['product_brand'];
        $import_price = $_POST['import_price'];
        $export_price = $_POST['export_price'];
        $UnitsAvailable = $_POST['UnitsAvailable'];
        $product_category = $_POST['product_category'];
        $product_description = $_POST['product_description'];

        $uploaded_images = array();
        if (isset($_FILES['product_images']) && !empty($_FILES['product_images']['name'][0])) {
            foreach ($_FILES['product_images']['name'] as $key => $image_name) {
                $image_tmp = $_FILES['product_images']['tmp_name'][$key];
                $image_ext = pathinfo($image_name, PATHINFO_EXTENSION);
                $image_new_name = uniqid() . '.' . $image_ext;
                $image_path = '../uploaded_image/' . $image_new_name;

                if (move_uploaded_file($image_tmp, $image_path)) {
                    $uploaded_images[] = $image_new_name;
                }
            }
        }

        $query_old_images = "SELECT Image FROM products WHERE id = $product_id";
        $result_old_images = mysqli_query($con, $query_old_images);
        $row_old_images = mysqli_fetch_assoc($result_old_images);
        $old_images = $row_old_images['Image'];

        if (!empty($uploaded_images)) {
            $new_images = !empty($old_images) ? $old_images . ',' . implode(',', $uploaded_images) : implode(',', $uploaded_images);
        } else {
            $new_images = $old_images; 
        }

        $update_query = "UPDATE products SET 
                         name = '$product_name', 
                         brand = '$product_brand', 
                         import_price = '$import_price', 
                         export_price = '$export_price', 
                         UnitsAvailable = '$UnitsAvailable', 
                         category_id = '$product_category', 
                         description = '$product_description', 
                         Image = '$new_images' 
                         WHERE id = $product_id";

        if (mysqli_query($con, $update_query)) {
            $response = ['status' => 'success', 'message' => 'Cập nhật sản phẩm thành công.'];
        } else {
            throw new Exception('Lỗi khi cập nhật sản phẩm.');
        }
    }
} catch (Exception $e) {
    $response = ['status' => 'error', 'message' => $e->getMessage()];
}

echo json_encode($response);
exit;
?>
