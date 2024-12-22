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
                                <h2>Sản Phẩm Gần Hết Hàng</h2>
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
                                    
                                    <button type="button" id="searchButton" class="btn btn-primary">Tìm kiếm</button>
                                </div>
                            </form>
                          
                           <a href="./NguoiNhanMail.php">
                                <button type="button" class="btn btn-danger" style="color:#fff;padding: 10px 20px; font-size: 16px; line-height: 1.5;">
                                    <i class="fa fa-envelope"></i> Gửi Mail
                                </button>
                           </a>
                            <!-- Xuất Dữ Liệu Excel với biểu tượng -->
                                <a href="./api/export_excel_threshold.php">
                                <button type="button" class="btn btn-success" style="color:#fff;padding: 10px 20px; font-size: 16px; line-height: 1.5;">
                                    <i class="fa fa-file-excel"></i> Xuất Dữ Liệu Excel
                                </button>
                            </a>

                        </div>
                        <div class="table-data">
                            <table id="example" class="table table-bordered table-hover table-striped text-center w-100">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center">Mã Sản Phẩm</th>
                                        <th class="text-center" style="width:20%">Tên sản phẩm</th>
                                        <th class="text-center">Tên thương hiệu</th>
                                        <th class="text-center">Ảnh sản phẩm</th>
                                        <th class="text-center">Giá nhập</th>
                                        <th class="text-center">Sẵn Có</th>
                                        <th class="text-center">Ngưỡng Cảnh Báo</th>
                                    </tr>
                                </thead>
                                <tbody id="SachGanHetHang">
                                    <!-- Data from AJAX will populate here -->
                                </tbody>
                            </table>
                        </div>
                        <nav class="d-flex justify-content-center mt-3">
                            <ul class="pagination" id="paginationControls">
                            </ul>
                        </nav>
                    </div>
                </div>
                <?php include('./footer.php'); ?>

            </div>
        </div>
    </div>

<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>

<script>
$(document).ready(function() {
    let searchQuery = $('#searchQuery').val();
    function loadData() {
        $.ajax({
            url: 'api/ajax_product_warning.php',
            type: 'GET',
            data: {
                search: searchQuery 
            },
            dataType: 'json',
            success: function(response) {
                let tableContent = '';
                response.books.forEach(function(book) {
                    tableContent += '<tr>';
                    tableContent += '<td class="text-center">' + book.id + '</td>';  
                    tableContent += '<td class="text-center">' + book.name + '</td>';  
                    tableContent += '<td class="text-center">' + book.brand + '</td>'; 
                    tableContent += '<td class="text-center"><img src="./uploaded_image/' + book.Image + '" alt="' + book.name + '" style="width: 40px; height: auto;"></td>';
                    tableContent += '<td class="text-center">' + book.import_price + '</td>';  
                    tableContent += '<td class="text-center">' + book.UnitsAvailable + '</td>';  
                    tableContent += '<td class="text-center">' + book.NguongCanhBao + '</td>';  
                    tableContent += '</tr>';
                });
                $('#SachGanHetHang').html(tableContent);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data:', error);
            }
        });
    }
    $('#searchButton').click(function() {
        searchQuery = $('#searchQuery').val();  
        loadData();  
    });
    loadData();
});
</script>

</body>
</html>
