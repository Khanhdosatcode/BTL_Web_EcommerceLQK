<?php
session_start();
    if(!isset($_SESSION['admin_username'])){
        echo "<script>alert('Bạn cần phải đăng nhập vào hệ thống')</script>";
        echo "<script>window.open('../Login.php','_self');</script>";
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm</title>
    <link rel="stylesheet" href="../assets/css/main.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css" />
</head>

<body>
    <style>
        #example_info{
            display: none;
        }
        #sidebar{
            height: auto !important;
        }
    </style>
    <div class="wrapper">
        <?php
            include('./sidebar.php');
        ?>
        <div id="wrapper" class="w-100">
            <div id="content-wrapper" class="d-flex flex-column">
                <div id="content">
                    <?php
                        include('./header.php');
                    ?>

                    <div class="container">
                        <div class="categ-header">
                            <div class="sub-title">
                                <span class="shape"></span>
                                <h2>Quản lý sản phẩm</h2>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mb-4">
                            <form id="filterForm">
                                <div class="d-flex align-items-center">
                                    <input 
                                        type="text" 
                                        id="searchQuery" 
                                        name="searchQuery" 
                                        class="form-control me-3" 
                                        style="width: 300px;" 
                                        placeholder="Nhập từ khóa tìm kiếm..."
                                    />
                                    
                                    <select
                                        style="padding: 10px 20px; border: 1px solid #ccc;"
                                        class="rounded me-3"
                                        name="category"
                                        id="category"
                                    >
                                        <option value="0">Tìm kiếm theo danh mục</option>
                                        <?php
                                            $selected_category = isset($_GET['category']) ? $_GET['category'] : 0;

                                            $get_category_query = "SELECT * FROM `categories`";
                                            $get_category_result = mysqli_query($con, $get_category_query);

                                            while ($row_fetch_category = mysqli_fetch_array($get_category_result)) {
                                                $category_id = $row_fetch_category['id'];
                                                $category_title = $row_fetch_category['name'];

                                                $selected = ($category_id == $selected_category) ? "selected" : "";

                                                echo "<option value='$category_id' $selected>$category_title</option>";
                                            }
                                        ?>
                                    </select>

                                    <button type="button" id="searchButton" class="btn btn-premary btn-primary">Tìm kiếm</button>
                                </div>
                            </form>
                           <!-- Thêm sản phẩm với biểu tượng -->
                       <!-- Nút Thêm sản phẩm -->
                             <button type="button" id="addProductButton" class="btn btn-primary" style="color:#fff; padding: 10px 20px; font-size: 16px; line-height: 1.5;">
                                <i class="fa fa-plus"></i> Thêm sản phẩm
                            </button>

                            <!-- Nút Xuất Dữ Liệu Excel -->
                            <a href="api/export_excel_product.php">
                            <button type="button" id="exportBtn" class="btn btn-success" style="color:#fff; padding: 10px 20px; font-size: 16px; line-height: 1.5;">
                                <i class="fa fa-file-excel"></i> Xuất Dữ Liệu Excel
                            </button>
                            </a>
                        </div>
                        <div class="table-data">
                            <table id="example" class="table table-bordered table-hover table-striped text-center w-100">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center" >STT</th>
                                        <th class="text-center" style="width:20%">Tên sản phẩm</th>
                                        <th class="text-center">Ảnh sản phẩm</th>
                                        <th class="text-center">Giá nhập</th>
                                        <th class="text-center">Giá bán</th>
                                        <th class="text-center">Sẵn Có</th>
                                        <th class="text-center">Đã Bán</th>
                                        <th class="text-center">Sửa</th>
                                        <th class="text-center">Xóa</th>
                                    </tr>
                                </thead>
                                <tbody id="products">

                                </tbody>
                            </table>
                        </div>
                    </div>
                          <!-- Modal -->
                    <!-- Modal Thêm Sản Phẩm-->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Thêm sản phẩm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form inside modal -->
                <form enctype="multipart/form-data" id="productForm">
                    <div class="row justify-content-center">
                        <div class="col-7">
                            <div class="form-outline mb-4">
                                <label for="product_name" class="form-label">Tên sản phẩm</label>
                                <input type="text" placeholder="Nhập tên sản phẩm" name="product_name" id="product_name" class="form-control">
                            </div>
                            <div class="form-outline mb-4">
                                <label for="product_brand" class="form-label">Tên thương hiệu</label>
                                <input type="text" placeholder="Nhập tên thương hiệu" name="product_brand" id="product_brand" class="form-control">
                            </div>
                            <div class="form-outline mb-4">
                                <label for="import_price" class="form-label">Giá nhập</label>
                                <input min="1" type="number" placeholder="Nhập giá nhập" name="import_price" id="import_price" class="form-control">
                            </div>
                            <div class="form-outline mb-4">
                                <label for="export_price" class="form-label">Giá bán</label>
                                <input min="2" type="number" placeholder="Nhập giá bán" name="export_price" id="export_price" class="form-control">
                            </div>
                            <div class="form-outline mb-4">
                                <label for="UnitsAvailable" class="form-label">Số lượng</label>
                                <input min="2" type="number" placeholder="Nhập số lượng sản phẩm" name="UnitsAvailable" id="UnitsAvailable" class="form-control">
                            </div>
                            <div class="form-outline mb-4">
                                <select class="form-select" name="product_category" id="product_category">
                                    <option selected value="0">Chọn danh mục</option>
                                    <?php
                                    $select_query = 'SELECT * FROM `categories`';
                                    $select_result = mysqli_query($con, $select_query);
                                    while ($row = mysqli_fetch_assoc($select_result)) {
                                        echo "<option value='{$row['id']}'>{$row['name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-outline mb-4">
                                <label for="product_description" class="form-label">Mô tả sản phẩm</label>
                                <textarea name="product_description" rows="4" id="product_description" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="col-5">
                            <div id="file-upload-container">
                                <div class="form-outline mb-4 position-relative">
                                    <label for="product_image_1" class="form-label">Ảnh 1</label>
                                    <input type="file" name="product_images[]" id="product_image_1" class="form-control" onchange="previewImage(this, 'preview_product_image_1')">
                                    <img id="preview_product_image_1" class="mt-3" style="max-width: 200px; display: none;">
                                    <button type="button" class="btn-close position-absolute top-0 end-0" style="display: none;" onclick="removeImage('product_image_1', 'preview_product_image_1')"></button>
                                </div>
                            </div>
                            <button type="button" id="add-file-upload" class="btn btn-secondary">Thêm ảnh</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <!-- Đặt nút Lưu vào phần modal-footer -->
                <button type="submit" form="productForm" class="btn btn-primary">Lưu</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Sửa sản phẩm -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel">Chỉnh sửa sản phẩm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form bên trong Modal -->
                <form enctype="multipart/form-data" id="editProductForm">
                    <div class="row justify-content-center">
                        <div class="col-7">
                            <!-- Tên sản phẩm -->
                            <div class="form-outline mb-4">
                                <label for="product_name" class="form-label">Tên sản phẩm</label>
                                <input 
                                    type="text" 
                                    placeholder="Nhập tên sản phẩm" 
                                    name="product_name" 
                                    id="edit_product_name" 
                                    class="form-control" 
                                    required
                                >
                            </div>
                            <!-- Thương hiệu -->
                            <div class="form-outline mb-4">
                                <label for="product_brand" class="form-label">Tên thương hiệu</label>
                                <input 
                                    type="text" 
                                    placeholder="Nhập tên thương hiệu" 
                                    name="product_brand" 
                                    id="edit_product_brand" 
                                    class="form-control" 
                                    required
                                >
                            </div>
                            <!-- Giá nhập -->
                            <div class="form-outline mb-4">
                                <label for="import_price" class="form-label">Giá nhập</label>
                                <input 
                                    type="number" 
                                    placeholder="Nhập giá nhập" 
                                    name="import_price" 
                                    id="edit_import_price" 
                                    class="form-control" 
                                    required
                                >
                            </div>
                            <!-- Giá bán -->
                            <div class="form-outline mb-4">
                                <label for="export_price" class="form-label">Giá bán</label>
                                <input 
                                    type="number" 
                                    placeholder="Nhập giá bán" 
                                    name="export_price" 
                                    id="edit_export_price" 
                                    class="form-control" 
                                    required
                                >
                            </div>

                            <!-- Số lượng -->
                            <div class="form-outline mb-4">
                                <label for="UnitsAvailable" class="form-label">Số lượng</label>
                                <input 
                                    type="number" 
                                    placeholder="Nhập số lượng sản phẩm" 
                                    name="UnitsAvailable" 
                                    id="edit_UnitsAvailable" 
                                    class="form-control" 
                                    required
                                >
                            </div>
                            <!-- Danh mục -->
                            <div class="form-outline mb-4">
                                <label for="product_category" class="form-label">Danh mục</label>
                                <select class="form-select" name="product_category" id="edit_product_category">
                                    <option value="0">Chọn danh mục</option>
                                    <?php
                                    // Lấy danh mục từ cơ sở dữ liệu
                                    $select_query = 'SELECT * FROM `categories`';
                                    $select_result = mysqli_query($con, $select_query);
                                    while ($row = mysqli_fetch_assoc($select_result)) {
                                        echo "<option value='{$row['id']}'>{$row['name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <!-- Mô tả sản phẩm -->
                            <div class="form-outline mb-4">
                                <label for="product_description" class="form-label">Mô tả sản phẩm</label>
                                <textarea 
                                    name="product_description" 
                                    rows="4" 
                                    id="edit_product_description" 
                                    class="form-control"
                                    required
                                ></textarea>
                            </div>
                        </div>

                        <!-- Hình ảnh sản phẩm -->
                        <div class="col-5">
                            <!-- Hiển thị ảnh sản phẩm hiện tại -->
                            <div id="current_images"></div> <!-- Phần hiển thị ảnh hiện tại -->

                            <!-- Tải ảnh mới -->
                            <div id="file-upload-container">
                                <div class="form-outline mb-4">
                                    <label for="product_image_1" class="form-label">Ảnh sản phẩm</label>
                                    <input 
                                        type="file" 
                                        name="product_images[]" 
                                        id="product_image_1" 
                                        class="form-control"
                                        onchange="previewImage(this, 'preview_product_image_1')"
                                    >
                                    <img id="preview_product_image_1" class="mt-3" style="max-width: 200px; display: none;">
                                </div>
                            </div>
                            <button type="button" id="add-file-upload" class="btn btn-secondary">Thêm ảnh</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="editProductForm" class="btn btn-primary">Cập nhật</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>


            <!-- Modal xóa sản phẩm -->
            <div class="modal fade" id="deleteModal_1" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel">Xóa sản phẩm</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Bạn có chắc chắn muốn xóa sản phẩm này?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                    <button type="button" class="btn btn-danger">Xóa</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php include('./footer.php'); ?>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
<script>
    //Ham tai du lieu
    function getProducts() {
    $.ajax({
        url: "api/ajax_get_products.php",
        method: "POST",
        dataType: "json", // Xác định kiểu dữ liệu trả về là JSON
        success: function (response) {
            console.log(response);  // In dữ liệu trả về từ server ra console để kiểm tra
            
            let html = '';
            // Kiểm tra xem dữ liệu có chứa thông báo lỗi hay không
            if (response.message) {
                // Nếu có thông báo lỗi, hiển thị thông báo trong bảng
                html = `<tr><td colspan="9">${response.message}</td></tr>`;
            } else if (Array.isArray(response) && response.length > 0) {
                // Nếu có sản phẩm, lặp qua mảng sản phẩm và tạo HTML
                response.forEach(function (product, index) { 
                      html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${product.name}</td>
                            <td><img src="${product.image}" alt="product image" style="width: 80px;"/></td>
                            <td>${product.import_price}</td>
                            <td>${product.export_price}</td>
                            <td>${product.UnitsAvailable}</td>
                            <td>${product.UnitsSold}</td>
                            <!-- Nút Sửa với SVG -->
                            <td>
                                <button class="btn btn-warning btn-edit" data-id="${product.product_id}">
                                    <svg xmlns='http://www.w3.org/2000/svg' height='1em' viewBox='0 0 512 512'>
                                        <path d='M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.7 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160V416c0 53 43 96 96 96H352c53 0 96-43 96-96V320c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H96z'/>
                                    </svg> 
                                </button>
                            </td>
                            <!-- Nút Xóa với SVG -->
                            <td>
                                <button class="btn btn-danger btn-delete" data-id="${product.product_id}">
                                    <svg xmlns='http://www.w3.org/2000/svg' height='1em' viewBox='0 0 448 512'>
                                        <path d='M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z'/>
                                    </svg> Xóa
                                </button>
                            </td>
                        </tr>
                    `;
                });
            } else {
                // Nếu không có sản phẩm nào, hiển thị thông báo
                html = '<tr><td colspan="9">Không có sản phẩm nào.</td></tr>';
            }
            // Đưa HTML vào bảng
            $("#products").html(html);
        },
        error: function () {
            alert("Có lỗi xảy ra khi lấy dữ liệu.");
        }
    });
}

    //Ham tim kiem                     
    function filterProducts() {
        let categoryId = $("#category").val();
        let searchQuery = $("#searchQuery").val();

        $.ajax({
            url: "api/ajax_get_products.php",
            method: "POST",
            data: { 
                category: categoryId,
                search: searchQuery
            },
            dataType: "json", 
            success: function (response) {
            console.log(response);  
            let html = '';
            if (response.message) {
                html = `<tr><td colspan="9">${response.message}</td></tr>`;
            } 
            else if (Array.isArray(response) && response.length > 0) {
                response.forEach(function (product, index) { 
                      html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${product.name}</td>
                            <td><img src="${product.image}" alt="product image" style="width: 80px;"/></td>
                            <td>${product.import_price}</td>
                            <td>${product.export_price}</td>
                            <td>${product.UnitsAvailable}</td>
                            <td>${product.UnitsSold}</td>
                            <!-- Nút Sửa với SVG -->
                            <td>
                                <button class="btn btn-warning btn-edit" data-id="${product.product_id}">
                                    <svg xmlns='http://www.w3.org/2000/svg' height='1em' viewBox='0 0 512 512'>
                                        <path d='M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.7 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160V416c0 53 43 96 96 96H352c53 0 96-43 96-96V320c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H96z'/>
                                    </svg> 
                                </button>
                            </td>
                            <!-- Nút Xóa với SVG -->
                            <td>
                                <button class="btn btn-danger btn-delete" data-id="${product.product_id}">
                                    <svg xmlns='http://www.w3.org/2000/svg' height='1em' viewBox='0 0 448 512'>
                                        <path d='M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z'/>
                                    </svg> Xóa
                                </button>
                            </td>
                        </tr>
                    `;
                });
            } else {
                // Nếu không có sản phẩm nào, hiển thị thông báo
                html = '<tr><td colspan="9">Không có sản phẩm nào.</td></tr>';
            }
            // Đưa HTML vào bảng
            $("#products").html(html);
        },
        error: function () {
            alert("Có lỗi xảy ra khi lấy dữ liệu.");
        }
    });
}
    $("#searchButton").click(function () {
        filterProducts();
    });

  $(document).on('click', '.btn-edit', function () {
    var productId = $(this).data('id'); 
    $.ajax({
        url: 'api/ajax_get_productByID.php', 
        type: 'POST',
        data: { id: productId },
        success: function(response) {
            var product = JSON.parse(response); 
            if (product.status === 'success') {
                var data = product.data;
                $('#editProductModal').data('product_id', data.product_id);
                $('#edit_product_name').val(data.product_name);
                $('#edit_product_brand').val(data.product_brand);
                $('#edit_import_price').val(data.import_price);
                $('#edit_export_price').val(data.export_price);
                $('#edit_UnitsAvailable').val(data.UnitsAvailable);
                $('#edit_product_category').val(data.category_id);
                $('#edit_product_description').val(data.product_description);
                var images = data.product_image; 
                var imageHtml = '';
                images.forEach(function(image) {
                    imageHtml += `<div class="form-outline mb-4 position-relative">
                        <img src="./uploaded_image/${image}" alt="Product Image" style="max-width: 200px; margin-right: 10px;">
                        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0" onclick="removeImage('${image}')">X</button>
                    </div>`;
                });
                $('#current_images').html(imageHtml); 
                $('#editProductModal').modal('show');
            } else {
                alert('Không tìm thấy sản phẩm.');
            }
        },
        error: function() {
            alert('Lỗi kết nối đến server.');
        }
    });
});
    $('#editProductForm').submit(function(e) {
        var product_id = $('#editProductModal').data('product_id');
        e.preventDefault();  
        var formData = new FormData(this);  
        formData.append('product_id', product_id);
        $.ajax({
            url: 'api/ajax_edit_product.php', 
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                console.log(response);
                var result = JSON.parse(response);
                if (result.status === 'success') {
                    $('#editProductForm')[0].reset(); 
                    $('#editProductModal').modal('hide'); 
                    alert(result.message);
                    getProducts();
                } else {
                    $('#editproductForm')[0].reset(); 
                    $('#editproductModal').modal('hide');
                }
            },
            error: function() {
                alert('Lỗi kết nối đến server.');
            }
        });
    });





    $("#addProductButton").click(function () {
            $('#productModal').modal('show');
        });

        // Xử lý AJAX khi submit form để thêm sản phẩm
        $("#productForm").submit(function (e) {
            e.preventDefault(); // Ngừng gửi form theo cách thông thường
            var formData = new FormData(this); // Lấy dữ liệu từ form

            $.ajax({
                url: 'api/ajax_add_product.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response.status === 'success') {
                        alert(response.message); // Hiển thị thông báo thành công
                        $('#productForm')[0].reset(); // Reset form
                        $('#productModal').modal('hide'); // Đóng modal
                        getProducts(); // Cập nhật danh sách sản phẩm
                    } else {
                        alert(response.message); // Thông báo lỗi
                    }
                },
                error: function () {
                    alert('Có lỗi xảy ra khi thêm hoặc cập nhật sản phẩm.');
                    $('#productModal').modal('hide'); // Đóng modal
                }
            });
        });
        //Xóa Sản Phẩm
          $(document).on('click', '.btn-delete', function () {
            const productId = $(this).data('id'); // Lấy ID sản phẩm từ data-id
            $('#deleteModal_1').modal('show'); // Hiển thị modal xác nhận xóa
            $('.btn-danger').data('id', productId); // Lưu lại ID vào nút xóa
        });
        // Khi nhấn nút "Xóa" trong modal
        $('.btn-danger').on('click', function () {
            const productId = $(this).data('id'); // Lấy ID sản phẩm cần xóa
            // Gửi yêu cầu AJAX để xóa sản phẩm
            $.ajax({
                url: 'api/ajax_delete_product.php', // Đường dẫn đến file xử lý xóa sản phẩm
                method: 'POST',
                data: { id: productId }, // Gửi ID sản phẩm cần xóa
                success: function (response) {
                    const data = JSON.parse(response); // Phân tích JSON trả về
                    if (data.status === 'success') {
                        $('#deleteModal_1').modal('hide'); // Đóng modal
                        alert(data.message); // Thông báo xóa thành công
                        getProducts(); // Cập nhật lại danh sách sản phẩm
                    } else {
                        alert(data.message); // Thông báo lỗi nếu có
                    }
                    $('#deleteModal_1').modal('hide'); // Đóng modal
                },
                error: function () {
                    alert('Có lỗi xảy ra khi xóa sản phẩm.');
                    $('#deleteModal_1').modal('hide'); // Đóng modal trong trường hợp lỗi
                }
            });
        });

        $(document).ready(function () {
            getProducts();
        });

    let fileIndex = 2; 
    function previewImage(input, previewId) {
        const file = input.files[0];
        const preview = document.getElementById(previewId);

        if (file) {
            const reader = new FileReader();

            // Khi đọc file xong
            reader.onload = function (e) {
                preview.src = e.target.result; 
                preview.style.display = "block";
            };

            reader.readAsDataURL(file); 
            preview.src = "";
            preview.style.display = "none"; 
        }
    }

    document.getElementById('add-file-upload').addEventListener('click', function () {
        const container = document.getElementById('file-upload-container');

        const newFileUpload = document.createElement('div');
        newFileUpload.className = 'form-outline mb-4 position-relative';

        const label = document.createElement('label');
        label.setAttribute('for', `product_image_${fileIndex}`);
        label.className = 'form-label';
        label.innerText = `Ảnh ${fileIndex}`;

        const input = document.createElement('input');
        input.type = 'file';
        input.name = 'product_images[]';
        input.id = `product_image_${fileIndex}`;
        input.className = 'form-control';
        input.required = true;

        const preview = document.createElement('img');
        preview.id = `preview_product_image_${fileIndex}`;
        preview.className = 'mt-3';
        preview.style.maxWidth = '200px';
        preview.style.display = 'none';

        input.addEventListener('change', function () {
            previewImage(this, preview.id);
        });

        // Thêm nút xóa
        const deleteButton = document.createElement('button');
        deleteButton.type = 'button';
        deleteButton.className = 'btn-close position-absolute top-0 end-0';
        deleteButton.style.backgroundColor = 'red';
        deleteButton.style.color = 'white';
        deleteButton.style.display = 'block';
        deleteButton.onclick = function () {
            if (container.children.length > 1) {
                newFileUpload.remove();
            } else {
                alert('Cần ít nhất 1 ảnh, không thể xóa!');
            }
        };

        // Thêm các phần tử con vào newFileUpload
        newFileUpload.appendChild(label);
        newFileUpload.appendChild(input);
        newFileUpload.appendChild(preview);
        newFileUpload.appendChild(deleteButton);

        // Thêm newFileUpload vào container
        container.appendChild(newFileUpload);

        fileIndex++;
    });


    document.getElementById('product_image_1').addEventListener('change', function () {
        previewImage(this, 'preview_product_image_1');

    });
    
    
// Hiển thị ảnh xem trước và nút "X"
function previewImage(input, previewId) {
    const file = input.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            const preview = document.getElementById(previewId);
            preview.src = e.target.result;
            preview.style.display = 'block';

            const closeButton = preview.nextElementSibling;
            closeButton.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}
</script>


</html>