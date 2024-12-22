<?php
function getProduct($numToDisplay = '', $col = 4)
{
    global $con;
    $select_product_query = "SELECT * FROM `products` where 1 ";
    $search = isset($_GET['search']) ? $_GET['search'] : null;
    $category = isset($_GET['category']) ? $_GET['category'] : null;
    if($search != null) $select_product_query .= " AND name LIKE '%$search%' ";
    if($category != null) $select_product_query .= " AND category_id = '$category' ";

    empty($numToDisplay) ? $select_product_query = $select_product_query . " ORDER BY id desc" : $select_product_query = $select_product_query . " ORDER BY id DESC LIMIT $numToDisplay";
    $select_product_result = mysqli_query($con, $select_product_query);
    $total_rows = mysqli_num_rows($select_product_result);
    if($total_rows > 0) {
        while ($row = mysqli_fetch_assoc($select_product_result)) {
            $product_id = $row['id'];
            $product_title = $row['name'];
            $product_images = $row['image'];
            $product_images = explode(',', $product_images);
            $product_image_one = $product_images[0];
            $product_price = number_format($row['selling_price'], 0, '', '.');
            $product_qty = $row['quantity'];


            echo "
            <div class='col-md-$col mb-5'>
                <div class='one-card'>
                    <div class='photo'>
                        <img src='./admin/product_images/$product_image_one' alt='$product_title'>
                        <button>
                            <a class='text-light text-decoration-none' href='products.php?add_to_cart=$product_id'>Thêm vào giỏ</a>
                        </button>
                        <button>
                            <a class='text-light text-decoration-none' href='product_details.php?product_id=$product_id'>Xem thêm</a>
                        </button>
                    </div>
                    <div class='content'>
                        <span class='title fw-bold'>$product_title</span>
                        <div class='desc'>
                            <span>$product_price VNĐ</span>
                        </div>
                        <span>Còn $product_qty sản phẩm</span>
                    </div>
                </div>
            </div>
                ";
        }
    }else{
        echo "<h2 class='text-center'>Không tìm thấy sản phẩm nào!</h2>";
    }
}

// display categories in sidenav 
function getCategories()
{
    global $con;
    $select_category_query = "SELECT * FROM `categories`";
    $select_category_result = mysqli_query($con, $select_category_query);
    while ($categories_row_data = mysqli_fetch_assoc($select_category_result)) {
        $category_title = $categories_row_data['name'];
        $category_id = $categories_row_data['id'];
        echo "
        <li class='nav-item'>
            <a href='products.php?category=$category_id' class='nav-link'>
                $category_title
            </a>
        </li>
        ";
    }
}

function getCategoriesAndImage()
{
    global $con;
    $select_category_query = "SELECT * FROM `categories`";
    $select_category_result = mysqli_query($con, $select_category_query);
    while ($categories_row_data = mysqli_fetch_assoc($select_category_result)) {
        $category_title = $categories_row_data['name'];
        $category_id = $categories_row_data['id'];
        $image = $categories_row_data['image'];
        echo "
                <div class='card'>
                <a href='products.php?category=$category_id' class='nav-link text-center'>
                    <span>
                    <img width='80' height='80' src='./admin/category_image/$image' alt='$category_title'>
                    </span>
                    </br>
                    <span >$category_title</span>
                </a>
                </div>
        ";
    }
}

// view details function 
function viewDetails()
{
    global $con;
    // condition to check isset or not 
    if (isset($_GET['product_id'])) {
        if (!isset($_GET['category'])) {
            if (!isset($_GET['brand'])) {
                $product_id = $_GET['product_id'];
                $select_product_query = "SELECT * FROM `products` WHERE id=$product_id";
                $select_product_result = mysqli_query($con, $select_product_query);
                while ($row = mysqli_fetch_assoc($select_product_result)) {
                    $product_id = $row['id'];
                    $product_title = $row['name'];
                    $product_desc = $row['description'];
                    $product_images = $row['image'];
                    $product_images_array = explode(',', $product_images);
                    $product_image_one = $product_images_array[0];
                    $product_price = $row['selling_price'];
                    $product_qty = $row['quantity'];
                    echo "
                    <div class='row mx-0 justify-content-md-center gap-3 gap-md-0'>
                    <div class='col-md-2'>
                        <div class='prod-imgs'>";
                        foreach ($product_images_array as $image) {
                            echo "<img src='./admin/product_images/$image' alt='$product_title'>";
                        }
                        
                        echo "
                        </div>
                    </div>
                    <div class='col-md-5'>
                        <div class='main-img'>
                            <img src='./admin/product_images/$product_image_one' alt='$product_title'>
                        </div>
                    </div>
                    <div class='col-md-5'>
                        <div class='info d-flex flex-column gap-2'>
                            <h4 class='fw-bold'>$product_title</h4>
                            <div class='rates d-flex gap-2 flex-wrap'>
                                <span>
                                    (150 đánh giá)
                                </span>
                                <span>| Còn $product_qty sản phẩm</span>
                                <span class='in-stack fw-bold'>
                                    
                                </span>
                            </div>
                            <h4>
                                $product_price VNĐ
                            </h4>
                            <p>
                                $product_desc
                            </p>
                            <div class='divider'>
                            </div>
                            <form action='products.php?add_to_cart=$product_id'>
                                <div class='buy-item d-flex gap-3 justify-content-center align-items-center'>
                                    <div class='num-btns d-flex gap-1'>
                                        <button type='button' class='btn btn-increase' onclick='increaseValueBtn()'>+</button>
                                        <input type='number' class='form-control' name='num_of_items' id='num_of_items' value='1'>
                                        <input type='hidden' class='form-control' name='add_to_cart' id='add_to_cart' value='$product_id'/>
                                        <!-- <span class='num-of-items'>3</span> -->
                                        <button type='button' class='btn btn-decrease' onclick='decreaseValueBtn()'> -</button>
                                    </div>
                                    <div>
                                        <input type='submit' class='btn btn-primary' value='Thêm vào giỏ hàng'>
                                    </div>
                                </div>
                            </form>
                            <div class='delivery d-flex flex-column my-4 gap-3'>
                                <div class='d-flex gap-2 align-items-center'>
                                    <span>
                                        <svg width='40' height='40' viewBox='0 0 40 40' fill='none' xmlns='http://www.w3.org/2000/svg'>
                                            <g clip-path='url(#clip0_261_4843)'>
                                                <path d='M11.6673 31.6667C13.5083 31.6667 15.0007 30.1743 15.0007 28.3333C15.0007 26.4924 13.5083 25 11.6673 25C9.82637 25 8.33398 26.4924 8.33398 28.3333C8.33398 30.1743 9.82637 31.6667 11.6673 31.6667Z' stroke='black' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' />
                                                <path d='M28.3333 31.6667C30.1743 31.6667 31.6667 30.1743 31.6667 28.3333C31.6667 26.4924 30.1743 25 28.3333 25C26.4924 25 25 26.4924 25 28.3333C25 30.1743 26.4924 31.6667 28.3333 31.6667Z' stroke='black' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' />
                                                <path d='M8.33398 28.3335H7.00065C5.89608 28.3335 5.00065 27.4381 5.00065 26.3335V21.6668M3.33398 8.3335H19.6673C20.7719 8.3335 21.6673 9.22893 21.6673 10.3335V28.3335M15.0007 28.3335H25.0007M31.6673 28.3335H33.0007C34.1052 28.3335 35.0007 27.4381 35.0007 26.3335V18.3335M35.0007 18.3335H21.6673M35.0007 18.3335L30.5833 10.9712C30.2218 10.3688 29.5708 10.0002 28.8683 10.0002H21.6673' stroke='black' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' />
                                                <path d='M8 28H6.66667C5.5621 28 4.66667 27.1046 4.66667 26V21.3333M3 8H19.3333C20.4379 8 21.3333 8.89543 21.3333 10V28M15 28H24.6667M32 28H32.6667C33.7712 28 34.6667 27.1046 34.6667 26V18M34.6667 18H21.3333M34.6667 18L30.2493 10.6377C29.8878 10.0353 29.2368 9.66667 28.5343 9.66667H21.3333' stroke='black' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' />
                                                <path d='M5 11.8182H11.6667' stroke='black' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' />
                                                <path d='M1.81836 15.4545H8.48503' stroke='black' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' />
                                                <path d='M5 19.0909H11.6667' stroke='black' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' />
                                            </g>
                                            <defs>
                                                <clipPath id='clip0_261_4843'>
                                                    <rect width='40' height='40' fill='white' />
                                                </clipPath>
                                            </defs>
                                        </svg>
                                    </span>
                                    <div class='d-flex flex-column gap-2'>
                                        <h6>Miễn phí vẫn chuyển</h6>
                                        <span>Nhập mã bưu chính của bạn để biết Khả năng giao hàng</span>
                                    </div>
                                </div>
                                <div class='d-flex gap-2 align-items-center'>
                                    <span>
                                        <svg width='40' height='40' viewBox='0 0 40 40' fill='none' xmlns='http://www.w3.org/2000/svg'>
                                            <g clip-path='url(#clip0_261_4865)'>
                                                <path d='M33.3327 18.3334C32.9251 15.4004 31.5645 12.6828 29.4604 10.5992C27.3564 8.51557 24.6256 7.18155 21.6888 6.80261C18.752 6.42366 15.7721 7.02082 13.208 8.5021C10.644 9.98337 8.6381 12.2666 7.49935 15M6.66602 8.33335V15H13.3327' stroke='black' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' />
                                                <path d='M6.66602 21.6667C7.07361 24.5997 8.43423 27.3173 10.5383 29.4009C12.6423 31.4845 15.3731 32.8185 18.3099 33.1974C21.2467 33.5764 24.2266 32.9792 26.7907 31.4979C29.3547 30.0167 31.3606 27.7335 32.4994 25M33.3327 31.6667V25H26.666' stroke='black' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' />
                                            </g>
                                            <defs>
                                                <clipPath id='clip0_261_4865'>
                                                    <rect width='40' height='40' fill='white' />
                                                </clipPath>
                                            </defs>
                                        </svg>
                                    </span>
                                    <div class='d-flex flex-column gap-2'>
                                        <h6>Trả hàng</h6>
                                        <span>Đổi trả hàng miễn phí trong 30 ngày</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    ";
                }
            }
        }
    }
}

// cart function
function cart($num_of_items = 1)
{
    if (isset($_GET['add_to_cart'])) {
        global $con;

        $getProductId = $_GET['add_to_cart'];

        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        if ($user_id == null) {
            echo "<script>alert('Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng.');</script>";
            echo "<script>window.location.href = 'user_login.php';</script>";
        }

        $select_query = "SELECT * FROM `cart` WHERE product_id = $getProductId AND user_id = '$user_id'";
        $select_result = mysqli_query($con, $select_query);
        $num_of_rows = mysqli_num_rows($select_result);

        if ($num_of_rows > 0) {
            $update_query = "UPDATE `cart` SET amount = amount + $num_of_items WHERE product_id = $getProductId AND user_id = '$user_id'";
            mysqli_query($con, $update_query);

            echo "<script>alert('Sản phẩm đã tồn tại trong giỏ hàng. Số lượng đã được cập nhật.');</script>";
        } else {
            $insert_query = "INSERT INTO `cart` (product_id, user_id, amount) VALUES ($getProductId, '$user_id', $num_of_items)";
            mysqli_query($con, $insert_query);

            echo "<script>alert('Thêm vào giỏ hàng thành công.');</script>";
        }

        echo "<script>window.open('products.php', '_self');</script>";
    }
}