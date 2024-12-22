<?php
    include('includes/connect.php');
    session_start();
?>
<header>
    
    <!-- TOP HEADER -->
    <div id="top-header">
        <div class="container ">
            <ul class="header-links pull-left">
                <li><a href="#"><i class="fa fa-phone"></i> +0982020903</a></li>
                <li><a href="#"><i class="fa fa-envelope-o"></i> lyquockhanh020903@email.com</a></li>
                <li><a href="#"><i class="fa fa-map-marker"></i> Đường Lý Quốc Khánh - Bắc Từ Liêm - Hà Nội</a></li>
            </ul>
            <ul class="header-links pull-right">
                <!-- Tên người dùng hoặc "Tài khoản" -->
                <li>
                    <a href="#">
                        <i class="fa fa-user-o"></i> 
                        <span>
                            <?php
                                $username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
                                if ($username == null) {
                                    echo "Tài khoản";  // Khi chưa đăng nhập, hiển thị "Tài khoản"
                                } else {
                                    echo "Xin chào, $username";  // Khi đã đăng nhập, hiển thị tên người dùng
                                }
                            ?>
                        </span>
                    </a>
                </li>

                <!-- Login / Logout -->
                <li>
                    <?php
                        if (!isset($_SESSION['username'])) {
                            echo "<a href='Login.php'><i class='fa fa-sign-in'></i> Đăng nhập</a>";
                        } else {
                            echo "<a href='Logout.php'><i class='fa fa-sign-out'></i> Đăng xuất</a>";
                        }
                    ?>
                </li>
            </ul>
        </div>
    </div>
    <!-- /TOP HEADER -->

    <!-- MAIN HEADER -->
    <div id="header">
        <div class="container">
            <div class="row">
                <!-- LOGO -->
                <div class="col-md-3">
                    <div class="header-logo">
                        <a href="#" class="logo">
                            <img src="assets/assets1/img/logo.png" alt="">
                        </a>
                    </div>
                </div>
                <!-- /LOGO -->

                <!-- SEARCH BAR -->
                <div class="col-md-6">
                    <div class="header-search">
                        <form>
                            <select class="input-select">
                                <option value="0">Danh Mục</option>
                                <option value="1">Laptop</option>
                                <option value="2">Điện thoại</option>
                            </select>
                            <input class="input" placeholder="Search here">
                            <button class="search-btn">Search</button>
                        </form>
                    </div>
                </div>
                <!-- /SEARCH BAR -->

                <!-- ACCOUNT -->
                <div class="col-md-3 clearfix">
                    <div class="header-ctn">
                        <!-- Wishlist -->
                        <div>
                            <a href="#">
                                <i class="fa fa-heart-o"></i>
                                <span>Ưa Thích</span>
                                <div class="qty">2</div>
                            </a>
                        </div>
                        <!-- /Wishlist -->

                        <!-- Cart -->
                        <div class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                <i class="fa fa-shopping-cart"></i>
                                <span>Giỏ Hàng</span>
                                <div class="qty">3</div>
                            </a>
                            <div class="cart-dropdown">
                                <div class="cart-list">
                                    <div class="product-widget">
                                        <div class="product-img">
                                            <img src="assets/assets1/img/product01.png" alt="">
                                        </div>
                                        <div class="product-body">
                                            <h3 class="product-name"><a href="#">Sản phẩm ở đâ</a></h3>
                                            <h4 class="product-price"><span class="qty">1x</span>$980.00</h4>
                                        </div>
                                        <button class="delete"><i class="fa fa-close"></i></button>
                                    </div>

                                    <div class="product-widget">
                                        <div class="product-img">
                                            <img src="assets/assets1/img/product02.png" alt="">
                                        </div>
                                        <div class="product-body">
                                            <h3 class="product-name"><a href="#">product name goes here</a></h3>
                                            <h4 class="product-price"><span class="qty">3x</span>$980.00</h4>
                                        </div>
                                        <button class="delete"><i class="fa fa-close"></i></button>
                                    </div>
                                </div>
                                <div class="cart-summary">
                                    <small>3 Item(s) selected</small>
                                    <h5>SUBTOTAL: $2940.00</h5>
                                </div>
                                <div class="cart-btns">
                                    <a href="#">View Cart</a>
                                    <a href="#">Checkout <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <!-- /Cart -->

                        <!-- Menu Toogle -->
                        <div class="menu-toggle">
                            <a href="#">
                                <i class="fa fa-bars"></i>
                                <span>Menu</span>
                            </a>
                        </div>
                        <!-- /Menu Toogle -->
                    </div>
                </div>
                <!-- /ACCOUNT -->
            </div>
        </div>
    </div>
    <!-- /MAIN HEADER -->
</header>
