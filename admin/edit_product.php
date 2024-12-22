<?php
    session_start();
    if(!isset($_SESSION['admin_username'])){
        echo "<script>alert('Bạn cần phải đăng nhập vào hệ thống')</script>";
        echo "<script>window.open('../Login.php','_self');</script>";
    }
    include('../includes/connect.php');
    if(isset($_GET['edit_product'])){
        $edit_id = $_GET['edit_product'];
        $get_data_query = "SELECT * FROM `products` WHERE id = $edit_id";
        $get_data_result = mysqli_query($con,$get_data_query);
        $row_fetch_data = mysqli_fetch_array($get_data_result);

        $product_id = $row_fetch_data['id'];
        $product_name = $row_fetch_data['name'];
        $product_brand = $row_fetch_data['brand'];
        $product_description = $row_fetch_data['description'];
        $category_id = $row_fetch_data['category_id'];
        $product_image_one_old = $row_fetch_data['Image'];
        $product_images = explode(',', $product_image_one_old);
        
        $import_price = $row_fetch_data['import_price'];
        $export_price = $row_fetch_data['export_price'];
        $UnitsAvailable = $row_fetch_data['UnitsAvailable'];
    }
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Products - Admin Dashboard</title>
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
                                <h2>Chỉnh Sửa sản phẩm</h2>
                            </div>
                        </div>
                        <form enctype="multipart/form-data" id="productForm">
                            <div class="row justify-content-center">
                                <div class="col-7">
                                    <!-- Title -->
                                    <div class="form-outline mb-4">
                                        <label for="product_name" class="form-label">Tên sản phẩm</label>
                                        <input 
                                            type="text" 
                                            placeholder="Nhập tên sản phẩm" 
                                            name="product_name" 
                                            id="product_name" 
                                            class="form-control" 
                                            value="<?php echo ($product_name); ?>"
                                            
                                        >
                                    </div>

                                    <div class="form-outline mb-4">
                                        <label for="product_brand" class="form-label">Tên thương hiệu</label>
                                        <input 
                                            type="text" 
                                            placeholder="Nhập tên thương hiệu" 
                                            name="product_brand" 
                                            id="product_brand" 
                                            class="form-control" 
                                            value="<?php echo ($product_brand); ?>"
                                            
                                        >
                                    </div>

                                    <!-- Giá nhập -->
                                    <div class="form-outline mb-4">
                                        <label for="import_price" class="form-label">Giá nhập</label>
                                        <input 
                                            min="1" 
                                            type="number" 
                                            placeholder="Nhập giá nhập" 
                                            name="import_price" 
                                            id="import_price" 
                                            class="form-control" 
                                            value="<?php echo ($import_price); ?>"
                                            
                                        >
                                    </div>

                                    <!-- Giá bán -->
                                    <div class="form-outline mb-4">
                                        <label for="export_price" class="form-label">Giá bán</label>
                                        <input 
                                            min="1" 
                                            type="number" 
                                            placeholder="Nhập giá bán" 
                                            name="export_price" 
                                            id="export_price" 
                                            class="form-control" 
                                            value="<?php echo ($export_price); ?>"
                                            
                                        >
                                    </div>

                                    <!-- Số lượng -->
                                    <div class="form-outline mb-4">
                                        <label for="quantity" class="form-label">Số lượng</label>
                                        <input 
                                            min="1" 
                                            type="number" 
                                            placeholder="Nhập số lượng sản phẩm" 
                                            name="UnitsAvailable" 
                                            id="UnitsAvailable" 
                                            class="form-control" 
                                            value="<?php echo ($UnitsAvailable); ?>"
                                            
                                        >
                                    </div>

                                    <!-- Categories -->
                                    <div class="form-outline mb-4">
                                        <label for="product_category" class="form-label">Danh mục</label>
                                        <select class="form-select" name="product_category" id="product_category" >
                                            <option value="0">Chọn danh mục</option>
                                            <?php
                                            $select_query = 'SELECT * FROM `categories`';
                                            $select_result = mysqli_query($con, $select_query);
                                            while ($row = mysqli_fetch_assoc($select_result)) {
                                                $category_title = $row['name'];
                                                $category_id_option = $row['id'];
                                                $selected = ($category_id == $category_id_option) ? 'selected' : '';
                                                echo "<option value='$category_id_option' $selected>$category_title</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <!-- Description -->
                                    <div class="form-outline mb-4">
                                        <label for="product_description" class="form-label">Mô tả sản phẩm</label>
                                        <textarea 
                                            name="product_description" 
                                            rows="10" 
                                            id="product_description" 
                                            class="form-control"
                                            
                                        ><?php echo ($product_description); ?></textarea>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="form-outline mb-4">
                                        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                        <input type="submit" value="Cập nhật sản phẩm" name="update_product" class="btn btn-primary">
                                        <button type="button" class="btn btn-secondary" onclick="window.history.back();">Hủy</button>
                                    </div>
                                </div>

                                <!-- Image -->
                                <div class="col-5">
                                <div id="file-upload-container">
                                <?php foreach ($product_images as $index => $image) : ?>
                                        <div class="form-outline mb-4 position-relative" id="file-upload-<?php echo $index; ?>">
                                            <label for="product_image_<?php echo $index; ?>" class="form-label">Ảnh sản phẩm</label>
                                            <img 
                                                src="uploaded_image/<?php echo htmlspecialchars(trim($image)); ?>" 
                                                alt="Ảnh sản phẩm <?php echo $index + 1; ?>" 
                                                style="max-width: 200px; display: block;"
                                            >
                                            <button 
                                                type="button" 
                                                class="btn btn-danger btn-sm position-absolute top-0 end-0" 
                                                onclick="removeImage(<?php echo $index; ?>)"
                                            >X</button>
                                        </div>
                                    <?php endforeach; ?>
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
        formData.append('id', '<?php echo $edit_id; ?>');
        
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
        label.innerText = `Ảnh sản phẩm`;

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

