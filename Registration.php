<?php
include('includes/connect.php');
include('functions/common_functions.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecommerce User Registeration Page</title>
    <link rel="stylesheet" href="assets/css/bootstrap.css" />
    <link rel="stylesheet" href="assets/css/main.css" />
</head>

<style>
.alert {
    padding: 15px;
    border-radius: 5px;
    font-size: 16px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}
</style>

<body>

    <div class="register">
        <div class="container py-3">
            <h2 class="text-center text-danger mb-4">Đăng ký tài khoản mới</h2>
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <form id="registerForm" enctype="multipart/form-data" class="d-flex flex-column gap-4">

                    <div class="form-outline">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" placeholder="Nhập tên của bạn" required name="username" id="username" class="form-control">
                        </div>

                        <div class="form-outline">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" placeholder="Nhập email" required name="email" id="email" class="form-control">
                        </div>

                        <div class="form-outline">
                            <label for="image" class="form-label">Ảnh đại diện</label>
                            <input type="file" required name="image" id="image" class="form-control">
                        </div>

                        <div class="form-outline">
                            <label for="password" class="form-label">Mật khẩu</label>
                            <input type="password" placeholder="Nhập mật khẩu" required name="password" id="password" class="form-control">
                        </div>

                        <div class="form-outline">
                            <label for="conf_password" class="form-label">Xác nhận mật khẩu</label>
                            <input type="password" placeholder="Xác nhận mật khẩu" required name="conf_password" id="conf_password" class="form-control">
                        </div>

                        <div class="form-outline">
                            <label for="address" class="form-label">Địa chỉ</label>
                            <input type="text" placeholder="Nhập địa chỉ" required name="address" id="address" class="form-control">
                        </div>

                        <div class="form-outline">
                            <label for="mobile" class="form-label">Số điện thoại</label>
                            <input type="text" placeholder="Nhập số điện thoại" required name="mobile" id="user_mobile" class="form-control">
                        </div>
                        <div>
                            <input type="submit" value="Đăng ký" class="btn btn-primary mb-2" name="user_register">
                            <p>
                                Đã có tài khoản ? <a href="Login.php" class="text-primary text-decoration-underline"><strong>Đăng nhập</strong></a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="flashMessage" style="position: fixed; top: 20px; right: 20px; z-index: 9999; display: none;"></div>
    <script src="./assets//js/bootstrap.bundle.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

</body>

</html>
<script>
    $(document).ready(function () {
    $('#registerForm').on('submit', function (e) {
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: 'add_user.php', 
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            
            success: function (response) {
                let res = JSON.parse(response);
                if (res.status === 'success') {
                    showFlashMessage(res.message, 'success'); 
                    setTimeout(() => {
                        window.location.href = 'user_login.php'; 
                    }, 1500);
                } else {
                    showFlashMessage(res.message, 'error'); 
                }
            },
            error: function () {
                showFlashMessage('Đã xảy ra lỗi. Vui lòng thử lại.', 'error');
            }
        });
    });

    function showFlashMessage(message, type) {
        const flashMessage = $('#flashMessage');
        flashMessage
            .removeClass()
            .addClass(`alert alert-${type === 'success' ? 'success' : 'danger'}`)
            .html(message)
            .fadeIn();

        setTimeout(() => {
            flashMessage.fadeOut();
        }, 5000);
    }
});

</script>