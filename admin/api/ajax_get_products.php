<?php
include('../../includes/connect.php');

// Khởi tạo mảng để trả về dữ liệu
$response = [];

$get_product_query = "SELECT * FROM `products` WHERE 1";

// Lọc theo danh mục
if (isset($_POST['category']) && $_POST['category'] != 0) {
    $category_id = $_POST['category'];
    $get_product_query .= " AND category_id = $category_id";
}

// Lọc theo từ khóa tìm kiếm
$search = isset($_POST['search']) ? $_POST['search'] : '';
if (!empty($search)) {
    $get_product_query .= " AND name LIKE '%$search%'";
}

// Sắp xếp theo ID giảm dần
$get_product_query .= " ORDER BY id DESC";

$get_product_result = mysqli_query($con, $get_product_query);

if (mysqli_num_rows($get_product_result) > 0) {
    $stt = 0;
    
    while ($row_fetch_products = mysqli_fetch_array($get_product_result)) {
        $stt++;
        $product_id = $row_fetch_products['id'];
        $name = $row_fetch_products['name'];
        $image = $row_fetch_products['Image'];
        $product_images = explode(',', $image);
        $image = $product_images[0];
        $import_price = number_format($row_fetch_products['import_price'], 0, '.', '.');
        $export_price = number_format($row_fetch_products['export_price'], 0, '.', '.');
        $UnitsAvailable = $row_fetch_products['UnitsAvailable'];
        $UnitsSold = $row_fetch_products['UnitsSold'];

        // Thêm thông tin sản phẩm vào mảng phản hồi
        $response[] = [
            'stt' => $stt,
            'product_id' => $product_id,
            'name' => $name,
            'image' => "./uploaded_image/$image",
            'import_price' => "$import_price VNĐ",
            'export_price' => "$export_price VNĐ",
            'UnitsAvailable' => $UnitsAvailable,
            'UnitsSold' => $UnitsSold,
        ];
    }

    // Trả về mảng JSON
    echo json_encode($response);
} else {
    echo json_encode(['message' => 'Không có sản phẩm nào.']);
}
?>
