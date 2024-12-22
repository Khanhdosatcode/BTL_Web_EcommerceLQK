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
                                <h2>Thống Kê Sản Phẩm Theo Doanh Thu</h2>
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
                                    
                                    <!-- <select
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
                                    </select> -->

                                    <button type="button" id="searchButton" class="btn btn-primary">Tìm kiếm</button>
                                </div>
                            </form>
                          
                            <!-- Xuất Dữ Liệu Excel với biểu tượng -->
                            <a href="./api/XuatDuLieuExcel.php">
                                <button type="button" class="btn btn-success" style="color:#fff">
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
                                        <th class="text-center">Tác giả</th>
                                        <th class="text-center">Ảnh sản phẩm</th>
                                        <th class="text-center">Doanh Thu</th>
                                    </tr>
                                </thead>
                                <tbody id="BanChayNhat">
                                    <!-- Data from AJAX will populate here -->
                                </tbody>
                            </table>
                        </div>
                        <nav class="d-flex justify-content-center mt-3">
                            <ul class="pagination" id="paginationControls">
                            </ul>
                        </nav>
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
$(document).ready(function() {
    let currentPage = 1;
    const itemsPerPage = 7;
    let totalPages = 0; 

    function loadData(page) {
        $.ajax({
            url: 'api/ajax_revenue.php',
            type: 'GET',
            data: {
                page: page, 
                limit: itemsPerPage 
            },
            dataType: 'json',
            success: function(response) {
                let tableContent = '';
                response.products.forEach(function(product) {
                    tableContent += '<tr>';
                    tableContent += '<td class="text-center">' + product.id + '</td>';  
                    tableContent += '<td class="text-center">' + product.name + '</td>'; 
                    tableContent += '<td class="text-center">' + product.brand + '</td>'; 
                    tableContent += '<td class="text-center"><img src="./uploaded_image/' + product.Image + '" alt="' + product.name + '" style="width: 40px; height: auto;"></td>';
                    var numberFormat = new Intl.NumberFormat('vi-VN'); // Định dạng theo tiếng Việt (VND)
                    tableContent += '<td class="text-center">' + numberFormat.format(product.revenue) + ' VND</td>';    
                    tableContent += '</tr>';
                });
                $('#BanChayNhat').html(tableContent); 

                
                totalPages = response.totalPages;

              
                renderPagination(totalPages, page);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data:', error);
            }
        });
    }

    
    function renderPagination(totalPages, currentPage) {
        let paginationHtml = '';

        
        if (currentPage > 1) {
            paginationHtml += '<li class="page-item"><a class="page-link" href="#" id="previousPage">Trước</a></li>';
        } else {
            paginationHtml += '<li class="page-item disabled"><a class="page-link" href="#">Trước</a></li>';
        }

        for (let i = 1; i <= totalPages; i++) {
            paginationHtml += '<li class="page-item ' + (i === currentPage ? 'active' : '') + '">';
            paginationHtml += '<a class="page-link" href="#" data-page="' + i + '">' + i + '</a>';
            paginationHtml += '</li>';
        }

        if (currentPage < totalPages) {
            paginationHtml += '<li class="page-item"><a class="page-link" href="#" id="nextPage">Tiếp</a></li>';
        } else {
            paginationHtml += '<li class="page-item disabled"><a class="page-link" href="#">Tiếp</a></li>';
        }

        $('#paginationControls').html(paginationHtml);
    }

    $(document).on('click', '.page-link', function(event) {
        event.preventDefault();

        if ($(this).attr('id') === 'previousPage' && currentPage > 1) {
            currentPage--;
            loadData(currentPage);
        }

        if ($(this).attr('id') === 'nextPage' && currentPage < totalPages) {
            currentPage++;
            loadData(currentPage);
        }

        const page = $(this).data('page');
        if (page) {
            currentPage = page;
            loadData(currentPage);
        }
    });

    loadData(currentPage);
});
</script>
</body>
</html>
