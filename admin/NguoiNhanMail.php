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
        #example_info {
            display: none;
        }

        #sidebar {
            height: auto !important;
        }
    </style>
    <div class="wrapper">
        <?php include('./sidebar.php'); ?>
        <div id="wrapper" class="w-100">
            <div id="content-wrapper" class="d-flex flex-column">
                <div id="content">
                    <?php include('./header.php'); ?>

                    <div class="container">
                        <div class="categ-header">
                            <div class="sub-title">
                                <span class="shape"></span>
                                <h2>Chọn Danh Sách Người Nhận Mail</h2>
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
                        </div>
                        <div class="table-data">
                            <table id="example" class="table table-bordered table-hover table-striped text-center w-100">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center">
                                            <input type="checkbox" id="selectAll" />
                                        </th>
                                        <th class="text-center">STT</th>
                                        <th class="text-center">Tên người dùng</th>
                                        <th class="text-center">Email</th>
                                        <th class="text-center">Vai trò</th>
                                    </tr>
                                </thead>
                                <tbody id="TableAdmin">
                                    <!-- Data from AJAX will populate here -->
                                </tbody>
                            </table>
                            <div class="text-end">
                                <button type="button" id="sendEmail" class="btn btn-danger" style="color:#fff; padding: 10px 20px; font-size: 16px; line-height: 1.5;">
                                    <i class="fa fa-envelope"></i> Gửi
                                </button>
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
$(document).ready(function() {
    // Load data without pagination
    function loadData(searchQuery = '') {
        $.ajax({
            url: 'api/ajax_get_admin.php', 
            type: 'GET',
            data: { search: searchQuery
            },
            dataType: 'json',
            success: function(response) {
                let tableContent = '';
                if (response.users && response.users.length > 0) {
                    response.users.forEach(function(user, index) {
                        tableContent += '<tr>';
                        tableContent += '<td class="text-center"><input type="checkbox" class="userCheckbox" data-email="' + user.email + '" /></td>';
                        tableContent += '<td class="text-center">' + (index + 1) + '</td>';
                        tableContent += '<td class="text-center">' + user.name + '</td>';
                        tableContent += '<td class="text-center">' + user.email + '</td>';
                        tableContent += '<td class="text-center">' + user.role + '</td>';
                        tableContent += '</tr>';
                    });
                } else {
                    tableContent = '<tr><td colspan="6" class="text-center">Không có dữ liệu</td></tr>';
                }
                $('#TableAdmin').html(tableContent);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data:', error);
            }
        });
    }

    // Load data on page load
    loadData();

    // Search functionality
    $('#searchButton').on('click', function() {
        let searchQuery = $('#searchQuery').val();
        loadData(searchQuery);
    });

    $('#selectAll').on('click', function() {
        let isChecked = $(this).prop('checked');
        $('.userCheckbox').prop('checked', isChecked);
    });

    // Send email on button click
    $('#sendEmail').on('click', function() {
        let selectedEmails = [];
        $('.userCheckbox:checked').each(function() {
            selectedEmails.push($(this).data('email'));
        });

        if (selectedEmails.length === 0) {
            alert('Vui lòng chọn ít nhất một người nhận!');
            return;
        }

        // AJAX request to send emails
        $.ajax({
            url: 'api/ajax_send_mail.php', // API endpoint for sending emails
            type: 'POST',
            data: { emails: selectedEmails },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('Email đã được gửi thành công!');
                } else {
                    alert('Gửi email thất bại: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error sending emails:', error);
            }
        });
    });
});
</script>
</html>
