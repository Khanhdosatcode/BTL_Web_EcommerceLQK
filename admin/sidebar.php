
<link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
<link rel="stylesheet" href="../assets/css/style1.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">

<aside id="sidebar">
    <div class="d-flex">
        <button class="toggle-btn" type="button">
            <i class="lni lni-grid-alt"></i>
        </button>
        <div class="sidebar-logo">
            <a href="./">Thu gọn
            </a>
        </div>
    </div>
    <ul class="sidebar-nav">
        <li class="sidebar-item">
            <a href="index.php" class="sidebar-link">
                <i class="lni lni-dashboard"></i>
                <span>Quản Trị</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="view_products.php" class="sidebar-link">
                <i class="fa fa-cube"></i>
                <span>Sản phẩm</span>
            </a>
        </li>
        <li class="sidebar-item">
    <a href="#collapseThongKe" 
       class="sidebar-link collapsed" 
       data-bs-toggle="collapse" 
       role="button" 
       aria-expanded="false" 
       aria-controls="collapseThongKe">
        <i class="fa fa-chart-bar"></i>
        <span>Thống Kê</span>
    </a>
    <div id="collapseThongKe" 
         class="collapse" 
         data-bs-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="ThongkeBanChay.php">Bán Chạy</a>
            <a class="collapse-item" href="ThongKeDoanhThu.php">Doanh Thu</a>
        </div>
    </div>
        </li>
        </li>
        <li class="sidebar-item">
            <a href="SanPhamGanHetHang.php" class="sidebar-link">
                <i class="fa fa-arrow-alt-circle-up" aria-hidden="true"></i>
                <span>Sản Phẩm Gần Hết Hàng </span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="SanPhamKhuyenMai.php" class="sidebar-link">
                <i class="fa fa-bolt" aria-hidden="true"></i>
                <span>Khuyến Mại Sản Phẩm</span>
            </a>
        </li>

    </ul>

    <div class="sidebar-footer">
        <a href="../Logout.php" class="sidebar-link mt-3">
            <i class="lni lni-exit"></i>
            <span>Đăng xuất</span>
        </a>
    </div>
</aside>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
<script src="../assets/js/script.js"></script>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function() {
    var sidebar = $('#sidebar');
    if (sidebar.outerHeight() < $(window).height()) {
      sidebar.css('height', '100vh');
    }
  });
</script>