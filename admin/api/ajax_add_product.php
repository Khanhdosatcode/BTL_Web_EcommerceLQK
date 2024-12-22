<?php
// session_start(); 
// if(!isset($_SESSION['admin_username'])){
//     echo "<script>alert('Bạn cần phải đăng nhập vào hệ thống');</script>";
//     echo "<script>window.location.href='../../Login.php';</script>"; 
//     exit(); 
// }
header('Content-Type: application/json');
include('../../includes/connect.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = [];
    try {
        $product_id = $_POST['id'] ?? null; 
        $product_name = $_POST['product_name'];
        $product_brand = $_POST['product_brand'];
        $import_price = $_POST['import_price'];
        $export_price = $_POST['export_price'];
        $UnitsAvailable = $_POST['UnitsAvailable'];
        $product_category = $_POST['product_category'];
        $product_description = $_POST['product_description'];

        if (!$product_name || !$product_brand || !$import_price || !$export_price || !$UnitsAvailable || $product_category == 0 || !$product_description) {
            throw new Exception('Vui lòng điền đầy đủ thông tin sản phẩm.');
        }
        if ($UnitsAvailable < 0 || $import_price < 0 || $export_price < 0) {
            throw new Exception('Giá trị phải lớn hơn 0.');
        }

        if (!$product_id) {
            $check_query = "SELECT * FROM products WHERE name = '$product_name' LIMIT 1";
            $check_result = mysqli_query($con, $check_query);

            if (mysqli_num_rows($check_result) > 0) {
                throw new Exception('Tên sản phẩm đã tồn tại trong hệ thống.');
            }
        }

        $uploaded_images = [];
        if (isset($_FILES['product_images'])) {
            foreach ($_FILES['product_images']['tmp_name'] as $key => $tmp_name) {
                $file_name = $_FILES['product_images']['name'][$key];
                $file_tmp = $_FILES['product_images']['tmp_name'][$key];
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

                $valid_extensions = ['jpg', 'jpeg', 'png', 'webp'];
                if (in_array($file_ext, $valid_extensions)) {
                    $new_file_name = uniqid() . '.' . $file_ext;
                    $destination = '../uploaded_image/' . $new_file_name;
                    if (!move_uploaded_file($file_tmp, $destination)) {
                        throw new Exception('Lỗi khi tải lên ảnh: ' . $file_name);
                    }
                    $uploaded_images[] = $new_file_name;
                } else {
                    throw new Exception('Định dạng ảnh không hợp lệ: ' . $file_name . '. Vui lòng chọn định dạng file jpg, jpeg, png');
                }
            }

        }
        if ($product_id) {
            $query_old_images = "SELECT Image FROM products WHERE id = $product_id";
            $result_old_images = mysqli_query($con, $query_old_images);
            $row_old_images = mysqli_fetch_assoc($result_old_images);
        
            $old_images = $row_old_images['Image'];
        
            if (!empty($uploaded_images)) {
                if (!empty($old_images)) {
                    $image = $old_images . ',' . implode(',', $uploaded_images);
                } else {
                    $image = implode(',', $uploaded_images);
                }
            } 
            else {
                $image = $old_images;
            }
            $update_query = "UPDATE products 
                             SET name = '$product_name',
                                 brand = '$product_brand', 
                                 import_price = '$import_price', 
                                 export_price = '$export_price', 
                                 UnitsAvailable = '$UnitsAvailable', 
                                 category_id = '$product_category', 
                                 description = '$product_description'";
            if (!empty($uploaded_images)) {
                $update_query .= ", Image = '$image'";
            }
            $update_query .= " WHERE id = $product_id";

            if (!mysqli_query($con, $update_query)) {
                throw new Exception('Lỗi khi cập nhật sản phẩm.');
            }

            $response = ['status' => 'success', 'message' => 'Cập nhật sản phẩm thành công.'];
        } 
        else {
            $image = implode(',', $uploaded_images);
            $insert_query = "INSERT INTO products (name,brand, import_price, export_price, UnitsAvailable, category_id, description, Image) 
                             VALUES ('$product_name', '$product_brand','$import_price', '$export_price', '$UnitsAvailable', '$product_category', '$product_description', '$image')";
            if (!mysqli_query($con, $insert_query)) {
                throw new Exception('Lỗi khi thêm sản phẩm vào cơ sở dữ liệu.');
            }

            $response = ['status' => 'success', 'message' => 'Thêm sản phẩm thành công.'];
        }
    } catch (Exception $e) {
        $response = ['status' => 'error', 'message' => $e->getMessage()];
    }
    echo json_encode($response);
    exit;
}
?>
