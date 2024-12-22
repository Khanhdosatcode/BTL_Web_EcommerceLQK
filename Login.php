<?php
include('includes/connect.php');
include('functions/common_functions.php');
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecommerce Admin Login</title>
    <link rel="stylesheet" href="assets/css/bootstrap.css" />
    <link rel="stylesheet" href="assets/css/main.css" />
</head>

<body>
    <!-- Start Landing Section -->
    <div class="landing admin-register">
        <div class="">
            <h2 class="text-center mb-1 text-danger">Đăng Nhập Hệ Thống</h2>
            <!-- <h4 class="text-center mb-3 fw-light">Đăng nhập</h4> -->
            <div class="row m-0 align-items-center">
                <div class="col-md-6 py-40 px-40 d-none d-md-block">
                <img src="assets/images/Login.png" width="10%" height="10%" class="admin-register" alt="Login photo">
                </div>
                <div class="col-md-6 py-4 px-5 d-flex flex-column gap-4">
                    <div>
                        <form action="" method="post" class="d-flex flex-column gap-4">
                            <div class="form-outline">
                                <label for="username" class="form-label">Tài khoản</label>
                                <input type="text" name="username" id="username" class="form-control" placeholder="Nhập tài khoản" required>
                            </div>
                            <div class="form-outline">
                                <label for="password" class="form-label">Mật khẩu</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Nhập mật khẩu" required>
                            </div>
                            <div class="form-outline">
                                <a href="" class="text-2 text-decoration-underline">Quên mât khẩu?</a>
                            </div>
                            <div class="form-outline">
                                <input type="submit" value="Login" class="btn btn-primary mb-3" name="loginx2">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../assets/js/bootstrap.bundle.js"></script>
</body>

</html>
<?php
if (isset($_POST['loginx2'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $select_query = "SELECT * FROM `users` WHERE username='$username'";
    $select_result = mysqli_query($con, $select_query);
    $row_data = mysqli_fetch_assoc($select_result);
    $row_count = mysqli_num_rows($select_result);

    if ($row_count > 0) {
        // Kiểm tra mật khẩu
        if (password_verify($password, $row_data['password_hash'])) {
            if ($row_data['role'] == 'admin') {
                $_SESSION['admin_username'] = $username;
                echo "<script>window.open('admin/index.php', '_self');</script>";
            } 
            else {
                $_SESSION['username'] = $username;
                $_SESSION['user_id'] = $row_data['id'];
                echo "<script>window.open('./index.php', '_self');</script>";
            }
        } else {
            echo "<script>alert('Mật khẩu không đúng');</script>";
        }
    } else {
        echo "<script>alert('Tài khoản không tồn tại');</script>";
    }
}
?>