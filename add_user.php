<?php
include('includes/connect.php'); 

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $conf_password = $_POST['conf_password'];
        $address = $_POST['address'];
        $mobile = $_POST['mobile'];
        $image = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];

        if (!$username || !$email || !$password || !$conf_password || !$address || !$mobile || !$image) {
            throw new Exception('Vui lòng điền đầy đủ thông tin.');
        }

        if ($password !== $conf_password) {
            throw new Exception('Mật khẩu không khớp.');
        }

        $select_query = "SELECT * FROM `users` WHERE username='$username' OR email='$email'";
        $select_result = mysqli_query($con, $select_query);
        if (mysqli_num_rows($select_result) > 0) {
            throw new Exception('Username hoặc email đã tồn tại.');
        }

        $hash_password = password_hash($password, PASSWORD_DEFAULT);

        $image_destination = "users_area/user_images/$image";
        if (!move_uploaded_file($image_tmp, $image_destination)) {
            throw new Exception('Lỗi khi tải lên ảnh.');
        }

        $insert_query = "INSERT INTO `users` (username, email, image, password, address, phone, role_id) 
                         VALUES ('$username', '$email', '$image', '$hash_password', '$address', '$mobile', 2)";
        if (!mysqli_query($con, $insert_query)) {
            throw new Exception('Lỗi khi thêm người dùng.');
        }

        $response = ['status' => 'success', 'message' => 'Đăng ký tài khoản thành công.'];
    } catch (Exception $e) {
        $response = ['status' => 'error', 'message' => $e->getMessage()];
    }
    echo json_encode($response);
    exit;
}
?>
