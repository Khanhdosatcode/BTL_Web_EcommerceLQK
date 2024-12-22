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
    <title>Thêm sản phẩm</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.css" />
    <link rel="stylesheet" href="../assets/css/main.css" />
</head>

<body>
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

                    <div class="container py-4 px-2">
                        <div class="categ-header">
                            <div class="sub-title">
                                <span class="shape"></span>
                                <h2>Thêm sản phẩm</h2>
                            </div>
                        </div>
                        <form enctype="multipart/form-data" id="productForm">
                            <div class="row justify-content-center">
                                <div class="col-7">
                                    <!-- title -->
                                    <div class="form-outline mb-4">
                                        <label for="product_title" class="form-label">Tên sản phẩm</label>
                                        <input type="text" placeholder="Nhập tên sản phẩm" name="product_name" id="product_name" class="form-control">
                                    </div>
                                    <div class="form-outline mb-4">
                                        <label for="product_title" class="form-label">Tên thương hiệu</label>
                                        <input type="text" placeholder="Nhập tên thương hiệu" name="product_brand" id="product_brand" class="form-control">
                                    </div>
                                    <div class="form-outline mb-4">
                                        <label for="product_keywords" class="form-label">Giá nhập</label>
                                        <input min="1" type="number" placeholder="Nhập giá nhập" name="import_price" id="import_price" class="form-control">
                                    </div>
                                    <div class="form-outline mb-4">
                                        <label for="product_keywords" class="form-label">Giá bán</label>
                                        <input min=2 type="number" placeholder="Nhập giá bán" name="export_price" id="export_price" class="form-control">
                                    </div>
                                    <div class="form-outline mb-4">
                                        <label for="product_keywords" class="form-label">Số lượng</label>
                                        <input min=2 type="number" placeholder="Nhập số lượng sản phẩm" name="UnitsAvailable" id="product_keywords" class="form-control">
                                    </div>
                                    <!-- categories -->
                                    <div class="form-outline mb-4">
                                        <select class="form-select" name="product_category" id="product_category">
                                            <option selected value="0">Chọn danh mục</option>
                                            <?php
                                            $select_query = 'SELECT * FROM `categories`';
                                            $select_result = mysqli_query($con, $select_query);
                                            while ($row = mysqli_fetch_assoc($select_result)) {
                                                $category_title = $row['name'];
                                                $category_id = $row['id'];
                                                echo "<option value='$category_id'>$category_title</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <!-- description -->
                                    <div class="form-outline mb-4">
                                        <label for="product_description" class="form-label">Mô tả sản phẩm</label>
                                        <textarea name="product_description" rows="4" id="product_description" class="form-control"></textarea>
                                    </div>

                                    <div class="form-outline mb-4">
                                        <input type="submit" value="Thêm" name="insert_product" class="btn btn-primary">
                                    </div>
                                </div>
                                <div class="col-5">
                                <!-- Image Upload Container -->
                                    <div id="file-upload-container">
                                        <div class="form-outline mb-4 position-relative">
                                            <label for="product_image_1" class="form-label">Ảnh 1</label>
                                            <input type="file" name="product_images[]" id="product_image_1" class="form-control" onchange="previewImage(this, 'preview_product_image_1')">
                                            <img id="preview_product_image_1" style="max-width: 200px;">
                                        </div>
                                    </div>
                                    <button type="button" id="add-file-upload" class="btn btn-primary mb-3">Thêm ảnh</button>
                                </div>

                            </div>
                        </form>
                    </div>
                    <?php include('./footer.php'); ?>
                </div>
            </div>
        </div>
    </div>
    <div id="flashMessage" style="position: fixed; top: 20px; right: 20px; z-index: 9999; display: none;"></div>

</body>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>

</html>


<script>
    $(document).ready(function () {
    $('#productForm').on('submit', function (e) {
        e.preventDefault();
        let formData = new FormData(this);
        $.ajax({
            url: 'api/ajax_add_product.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {    
                console.log(response);
                            
                response = JSON.parse(response);
                console.log(response);
                                
                if (response.status === 'success') {
                    $('#flashMessage').html(
                        `<div class="alert alert-success">${response.message}</div>`
                    ).fadeIn();
                    
                    setTimeout(() => {
                        $('#flashMessage').fadeOut();
                    }, 5000);

                    $('#productForm')[0].reset(); 
                    window.location.href = 'view_products.php'; 

                } else {
                    $('#flashMessage').html(
                        `<div class="alert alert-danger">${response.message}</div>`
                    ).fadeIn(); 
                    
                    setTimeout(() => {
                        $('#flashMessage').fadeOut();
                    }, 5000);
                }

            },
            error: function () {
                $('#responseMessage').html(
                    `<div class="alert alert-danger">Có lỗi xảy ra, vui lòng thử lại.</div>`
                );
            },
        });
    });
});
</script>

<script>
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

