<?php
session_start();
if(!isset($_SESSION['admin_username'])){
    echo "<script>alert('Bạn cần phải đăng nhập vào hệ thống')</script>";
    echo "<script>window.open('../Login.php','_self');</script>";
}
include('../includes/connect.php');
$query = "SELECT id, name, Image, export_price FROM products";
$result = mysqli_query($con, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($con));
}

// Cập nhật truy vấn để sử dụng cột promotion_name thay vì name
$query_promotions = "SELECT * FROM promotions";
$promotions_result = mysqli_query($con, $query_promotions);
$promotions = mysqli_fetch_array($promotions_result);
$promotion_name = isset($promotions['promotion_name']) ? $promotions['promotion_name'] : 'Chưa có tên khuyến mãi';
$start_time = isset($promotions['start_time']) ? $promotions['start_time'] : '';
$end_time = isset($promotions['end_time']) ? $promotions['end_time'] : '';
$percent = isset($promotions['discount']) ? $promotions['discount'] : '';
$product_id = isset($promotions['product_id']) ? $promotions['product_id'] : '';
$product_ids = explode(',', $product_id);
$product_ids_str = implode(',', array_map('intval', $product_ids));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.css" />
    <link rel="stylesheet" href="../assets/css/main.css" />
</head>
<style>
    #sidebar {
        height: auto !important;
    }
</style>

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

                    <div class="container row">
                        <div class="categ-header">
                            <div class="sub-title">
                                <span class="shape"></span>
                                <h2>Sự Kiện Khuyến Mại</h2>
                            </div>
                        </div>
                        <form id="flashsaleForm" class="row">
                            <div class="col-6">
                                <select class="form-select" aria-label="Chọn sản phẩm" id="productSelect">
                                    <option selected>Chọn sản phẩm</option>
                                    <?php
                            
                                while ($row = mysqli_fetch_assoc($result)) {
                                    var_dump($row);  
                                    $productName = $row['name'];
                                    $productImage = $row['Image'];
                                    $productImages = explode(',', $productImage);
                                    $productImage = $productImages[0];
                                    $productPrice = $row['export_price']; 
                                    if (is_numeric($productPrice)) {
                                        $formattedPrice = number_format($productPrice, 0, ',', '.');

                                    } else {
                                        $formattedPrice = '0';  
                                    }

                                    var_dump($formattedPrice); 
                                    echo '<option value="' . $row['id'] . '" 
                                                data-name="' . $productName . '" 
                                                data-image="./uploaded_image/' . $productImage . '" 
                                                data-price="' . $formattedPrice . '" 
                                                data-id="' . $row['id'] . '">
                                                ' . $productName . '
                                        </option>';
                                }


                                    ?>
                                </select>
                                <div class="row mt-4">
                                <div class="col-12">
                                        <label for="promotionName">Tên Chương Trình Khuyến Mại:</label>
                                        <input 
                                            value="<?php echo isset($promotion_name) ? $promotion_name : ''; ?>" 
                                            type="text" 
                                            name="promotion_name" 
                                            class="form-control" 
                                            id="promotionName">
                                            </div>
                                                 </div>
                                <div class="row mt-4">
                                    <div class="col-6">
                                        <label for="startTime">Thời gian bắt đầu:</label>
                                        <input 
                                            value="<?php echo isset($start_time) ? date('Y-m-d\TH:i', strtotime($start_time)) : ''; ?>" 
                                            type="datetime-local" 
                                            name="start_time" 
                                            class="form-control" 
                                            id="startTime">
                                    </div>
                                    <div class="col-6">
                                        <label for="endTime">Thời gian kết thúc:</label>
                                        <input 
                                            value="<?php echo isset($end_time) ? date('Y-m-d\TH:i', strtotime($end_time)) : ''; ?>" 
                                            type="datetime-local" 
                                            name="end_time" 
                                            class="form-control" 
                                            id="endTime">
                                    </div>
                                </div>

                                <label for="discount" class="mt-4">Giá trị khuyến mãi (%)</label>
                                <input 
                                    value="<?php echo isset($percent) ? $percent : ''; ?>" 
                                    type="number" 
                                    name="discount" 
                                    class="form-control" 
                                    id="discount">

                                <div class="col-12 mt-3">
                                    <input type="submit" value="Lưu" class="btn btn-primary">
                                </div>

                            </div>
                            <div class="col-6">
                                <div id="productInfo" class="row">
                                    <h3>Các sản phẩm khuyến mãi.</h3>
                                    <?php
                                    $query = "SELECT id, name, Image, export_price FROM products WHERE id IN ($product_ids_str)";
                                    $result = mysqli_query($con, $query);
                                    if (mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $productName = $row['name'];
                                            $productImage = $row['Image'];
                                            $productImages = explode(',', $productImage);
                                            $productImage = $productImages[0];
                                            $productPrice = $row['export_price'];
                                            $productId = $row['id'];

                                            echo '
                                            <div class="product-info col-12 d-flex justify-content-between mt-4">
                                                <div class="d-flex">
                                                    <img src="./uploaded_image/' . $productImage . '" alt="' . $productName . '" width="100" height="100">
                                                    <div class="ms-3">
                                                        <h5>Tên sản phẩm: ' . $productName . '</h5>
                                                        <p><strong>Giá bán: </strong>' . number_format($productPrice, 0, ',', '.') . ' VND</p>
                                                    </div>
                                                </div>
                                                <button style="width: 30px; height: 30px" class="btn btn-danger btn-sm" onclick="removeProductInfo(this)">X</button>
                                                <input type="hidden" name="ids[]" value="' . $productId . '" >
                                            </div>';
                                        }
                                    } 
                                    ?>
                            </div>

                        </form>

                    </div>
                </div>
                <?php include('./footer.php'); ?>
            </div>
        </div>
    </div>
    <div id="flashMessage" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>

</body>

<script src="https://code.jquery.com/jquery-3.7.1.js"></script>

<script>
    document.getElementById('productSelect').addEventListener('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        var productName = selectedOption.getAttribute('data-name');
        var productImage = selectedOption.getAttribute('data-image');
        var productPrice = selectedOption.getAttribute('data-price');
        var productid = selectedOption.getAttribute('data-id');

        var productInfoDiv = document.getElementById('productInfo');
        let addedProductIds = [];

        if (productName && !addedProductIds.includes(productid)) {
            var newProductInfo = `
            <div class="product-info col-12 d-flex justify-content-between mt-4">
                <div class="d-flex">            
                    <img src="${productImage}" alt="${productName}" width="100" height="100">
                    <div class="ms-3">
                        <h5>Tên sản phẩm: ${productName}</h5>
                        <p><strong>Giá bán: </strong>${productid} VND</p>
                    </div>
                </div>
                <button style="width: 30px; height: 30px" class="btn btn-danger btn-sm" onclick="removeProductInfo(this)">X</button>
                <input type="hidden" name="ids[]" value="${productid}" >
            </div>
            `;

            productInfoDiv.innerHTML += newProductInfo;

            addedProductIds.push(productid);
        }
    });

    function removeProductInfo(button) {
    var productInfoDiv = button.closest('.product-info');
    if (productInfoDiv) {
        productInfoDiv.remove();
    }
}
</script>

<script>
    $(document).ready(function () {
    $('#flashsaleForm').on('submit', function (e) {        
        e.preventDefault();
        
        let formData = new FormData(this);
        console.log(formData);
        
        
        $.ajax({
            url: 'api/add_flashsale.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {    
                console.log(response);
                            
                response = JSON.parse(response);
                console.log(123);
                                
                if (response.status === 'success') {
                    $('#flashMessage').html(
                        `<div class="alert alert-success">${response.message}</div>` 
                    ).fadeIn();
                    
                    setTimeout(() => {
                        $('#flashMessage').fadeOut();
                    }, 5000);

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

</html>
