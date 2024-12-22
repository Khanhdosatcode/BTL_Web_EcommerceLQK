<?php
// Kết nối cơ sở dữ liệu
session_start();
    if(!isset($_SESSION['admin_username'])){
        echo "<script>alert('Bạn cần phải đăng nhập vào hệ thống')</script>";
        echo "<script>window.open('../Login.php','_self');</script>";
    }
include('../includes/connect.php');
// Khởi tạo các mảng chứa dữ liệu
$products_revenue = [];  // Mảng chứa thông tin sản phẩm theo doanh thu
$revenues = [];          // Mảng chứa doanh thu của sản phẩm
$images_revenue = [];    // Mảng lưu ảnh sản phẩm theo doanh thu

$products_sales = [];    // Mảng chứa thông tin sản phẩm bán chạy nhất
$unitsSold = [];         // Mảng chứa số lượng bán của sản phẩm
$categories = [];        // Mảng chứa danh mục sản phẩm bán chạy
$images_sales = [];      // Mảng lưu ảnh sản phẩm bán chạy

// Truy vấn SQL để lấy top 5 sản phẩm có doanh thu cao nhất
$sql_revenue = "SELECT p.name AS product_name, 
                       p.export_price, 
                       p.UnitsSold, 
                       (p.export_price * p.UnitsSold) AS revenue,
                       p.image
                FROM products p
                ORDER BY revenue DESC LIMIT 5";  // Lấy top 5 sản phẩm có doanh thu cao nhất

// Truy vấn SQL để lấy top 5 sản phẩm bán chạy nhất
$sql_sales = "SELECT p.name AS product_name, 
                      p.UnitsSold AS units_sold, 
                      c.name AS category_name,
                      p.image
               FROM products p
               JOIN categories c ON p.category_id = c.id
               ORDER BY p.UnitsSold DESC LIMIT 5";  // Lấy top 5 sản phẩm bán chạy nhất

// Thực thi truy vấn doanh thu
$result_revenue = $con->query($sql_revenue);
if ($result_revenue->num_rows > 0) {
    // Lấy dữ liệu từ truy vấn doanh thu
    while ($row = $result_revenue->fetch_assoc()) {
        // Lưu dữ liệu vào mảng riêng biệt cho doanh thu
        $products_revenue[] = $row['product_name'];  // Lưu tên sản phẩm theo doanh thu
        $revenues[] = $row['revenue'];                // Lưu doanh thu của sản phẩm
        $images_revenue[] = $row['image'];            // Lưu ảnh sản phẩm theo doanh thu
    }
} else {
    echo "Không có sản phẩm nào có doanh thu.";
}

// Thực thi truy vấn bán chạy
$result_sales = $con->query($sql_sales);
if ($result_sales->num_rows > 0) {
    // Lấy dữ liệu từ truy vấn bán chạy
    while ($row = $result_sales->fetch_assoc()) {
        // Lưu dữ liệu vào mảng riêng biệt cho số lượng bán
        $products_sales[] = $row['product_name'];    // Lưu tên sản phẩm bán chạy
        $unitsSold[] = $row['units_sold'];           // Lưu số lượng bán của sản phẩm
        $categories[] = $row['category_name'];       // Lưu danh mục sản phẩm bán chạy
        $images_sales[] = $row['image'];             // Lưu ảnh sản phẩm bán chạy
    }
} else {
    echo "Không có sản phẩm nào bán chạy.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecommerce Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.css" />
    <link rel="stylesheet" href="../assets/css/main.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<?php
    include('../includes/connect.php');

    // Truy vấn lấy danh mục và số lượng sản phẩm
    $query = "SELECT c.name, COUNT(p.id) AS product_count
              FROM categories c
              LEFT JOIN products p ON c.id = p.category_id
              GROUP BY c.id
              ORDER BY product_count DESC
              LIMIT 3";
    $result = mysqli_query($con, $query);

    $category_names = [];
    $product_counts = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $category_names[] = $row['name'];
        $product_counts[] = $row['product_count'];
    }

    $category_names_json = json_encode($category_names);
    $product_counts_json = json_encode($product_counts);
?>

<body>
    <div class="wrapper">
        <?php include('./sidebar.php'); ?>

        <div id="wrapper" class="w-100">
            <div id="content-wrapper" class="d-flex flex-column">
                <div id="content">
                    <?php include('./header.php'); ?>

                    <div class="container-fluid">
                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                            <h1 class="h3 mb-0 text-gray-800">Quản Trị</h1>
                        </div>

                        <div class="row">
                            <!-- Card Danh mục -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-primary shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Danh mục</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                    <?php
                                                        $query = "SELECT COUNT(*) FROM categories";
                                                        $result = mysqli_query($con, $query);
                                                        $row = mysqli_fetch_row($result);
                                                        echo $row[0];
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Sản phẩm -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-success shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Sản phẩm</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                    <?php
                                                        $query = "SELECT COUNT(*) FROM products";
                                                        $result = mysqli_query($con, $query);
                                                        $row = mysqli_fetch_row($result);
                                                        echo $row[0];
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Người dùng -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-info shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Người dùng</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                    <?php
                                                        $query = "SELECT COUNT(*) FROM users WHERE role = 'customer'";
                                                        $result = mysqli_query($con, $query);
                                                        $row = mysqli_fetch_row($result);
                                                        echo $row[0];
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Người quản trị -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-info shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Người Quản Trị</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                    <?php
                                                        $query = "SELECT COUNT(*) FROM users WHERE role = 'admin'";
                                                        $result = mysqli_query($con, $query);
                                                        $row = mysqli_fetch_row($result);
                                                        echo $row[0];
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- End Row Cards -->

                        <!-- Biểu đồ -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="m-0 font-weight-bold text-primary">Top 5 sản phẩm có doanh thu cao nhất</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container">
                                            <canvas id="revenueChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="m-0 font-weight-bold text-primary">Top 5 sản phẩm bán chạy nhất</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container">
                                            <canvas id="salesChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- End Biểu đồ -->

                        <!-- Area Chart và Pie Chart -->
                        <div class="row">
                            <div class="col-xl-8 col-lg-7">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                        <h6 class="m-0 font-weight-bold text-primary">Doanh thu theo năm</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-area">
                                            <canvas id="myAreaChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-4 col-lg-5">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                        <h6 class="m-0 font-weight-bold text-primary">Top 3 danh mục có số lượng sản phẩm nhiều nhất</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-pie pt-4 pb-2">
                                            <canvas id="myPieChart"></canvas>
                                        </div>
                                        <div class="mt-4 text-center small">
                                            <span class="mr-2">
                                                <i class="fas fa-circle text-primary"></i> <?php echo $category_names[0]; ?>
                                            </span>
                                            <span class="mr-2">
                                                <i class="fas fa-circle text-success"></i> <?php echo $category_names[1]; ?>
                                            </span>
                                            <span class="mr-2">
                                                <i class="fas fa-circle text-info"></i> <?php echo $category_names[2]; ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- End Area và Pie Chart -->

                    </div> <!-- End Container -->
                    <?php include('./footer.php'); ?>
                </div> <!-- End Content -->
            </div> <!-- End Content Wrapper -->
        </div> <!-- End Wrapper -->
            </div>
<script>
    var ctx = document.getElementById("myAreaChart").getContext("2d");
    var myAreaChart = new Chart(ctx, {
        type: 'line',  // Biểu đồ đường
        data: {
            labels: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],  // 12 tháng trong năm
            datasets: [{
                label: 'Doanh thu (tỷ VND)',  // Tên của dữ liệu
                data: [5, 3, 4, 7.5, 9, 5.2, 6, 7.2, 6, 8, 10.5, 13],  // Dữ liệu doanh thu theo tháng (tính theo triệu VND)
                backgroundColor: 'rgba(28, 200, 138, 0.2)',  // Màu nền dưới biểu đồ
                borderColor: 'rgba(28, 200, 138, 1)',  // Màu đường biểu đồ
                borderWidth: 1,  // Độ dày của đường biểu đồ
                fill: true,  // Tô màu nền dưới đường biểu đồ (tạo kiểu diện tích)
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Tháng',  // Nhãn cho trục X
                    },
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Doanh thu (tỷ VND)',  // Nhãn cho trục Y
                    },
                    ticks: {
                        callback: function(value) {
                            return value + ' tỷ';  // Hiển thị đơn vị "triệu" cho trục Y
                        }
                    }
                }
            },
        },
    });
    // Đảm bảo dữ liệu PHP được truyền vào JavaScript đúng cách
    var categoryNames = <?php echo $category_names_json; ?>;  // Dữ liệu danh mục
    var productCounts = <?php echo $product_counts_json; ?>;  // Dữ liệu số sản phẩm

    // Thiết lập cấu hình cho biểu đồ Doughnut
    var ctx = document.getElementById("myPieChart").getContext("2d");

    var myPieChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: categoryNames,  // Dữ liệu danh mục
            datasets: [{
                data: productCounts,  // Dữ liệu số sản phẩm
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
                hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            plugins: {
                tooltip: {
                    backgroundColor: "rgb(255, 255, 255)",
                    bodyColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                    bodyFont: {
                    size: 24, // Kích thước chữ của nội dung trong tooltip
                    family: 'Arial'
                     }
                },
                legend: {
                    display: false
                }
            },
            cutoutPercentage: 80,
        },
    });
// PHP: Lấy dữ liệu từ PHP và chuyển sang JSON
var productNamesRevenue = <?php echo json_encode($products_revenue); ?>;
var revenues = <?php echo json_encode($revenues); ?>;
var imagesRevenue = <?php echo json_encode($images_revenue); ?>; // Dữ liệu ảnh sản phẩm theo doanh thu

var productNamesSales = <?php echo json_encode($products_sales); ?>;
var unitsSold = <?php echo json_encode($unitsSold); ?>;
var categories = <?php echo json_encode($categories); ?>;
var imagesSales = <?php echo json_encode($images_sales); ?>; // Dữ liệu ảnh sản phẩm bán chạy

// Biểu đồ doanh thu (Revenue Chart)
var ctxRevenue = document.getElementById('revenueChart').getContext('2d');
var revenueChart = new Chart(ctxRevenue, {
    type: 'bar', // Biểu đồ cột
    data: {
        labels: productNamesRevenue, // Tên sản phẩm theo doanh thu
        datasets: [{
                label: 'Doanh Thu (Cột)', // Doanh thu biểu diễn dưới dạng cột
                data: revenues,
                backgroundColor: 'rgba(28, 200, 138, 0.4)', // Màu nền cột
                borderColor: 'rgba(28, 200, 138, 1)',  // Màu viền cột
                borderWidth: 2,
                fill: false,
                type: 'bar',  // Xác định kiểu cột
            },
            {
                label: 'Doanh Thu (Đường)', // Doanh thu biểu diễn dưới dạng đường
                data: revenues,
                borderColor: 'rgba(28, 200, 138, 1)',  // Màu đường
                backgroundColor: 'rgba(28, 200, 138, 0.2)', // Màu nền đường (mờ)
                fill: true,  // Tô màu dưới đường
                borderWidth: 2,
                tension: 0.4, // Độ cong của đường
                type: 'line', // Xác định kiểu đường
            }
        ]
    },
    options: {
        responsive: true,
        scales: {
            x: {
                ticks: {
                    autoSkip: false,
                    maxRotation: 45,
                    minRotation: 45,
                    font: {
                        size: 3, // Điều chỉnh kích thước chữ
                        family: 'Arial',
                        color: '#555',
                        weight: 'normal'
                    }
                },
                grid: { display: false }
            },
            y: {
                beginAtZero: true,
                ticks: {
                    color: '#555',
                    font: { weight: 'normal', size: 14 },
                    callback: function(value) {
                        return value.toLocaleString(); // Định dạng số liệu
                    }
                }
            }
        },
        plugins: {
            legend: {
                labels: { color: '#555', font: { weight: 'bold' } }
            },
            tooltip: {
                backgroundColor: 'rgba(75, 192, 192, 0.8)',
                titleColor: '#000',
                bodyColor: '#000',
                bodyFont: { weight: 'normal' }
            }
        }
    },
    plugins: [{
        afterDraw: function(chart) {
            var ctx = chart.ctx;
            var xScale = chart.scales.x;
            var loadedImages = [];

            // Tải tất cả ảnh trước khi vẽ
            var imagePromises = chart.data.labels.map(function(value, index) {
                return new Promise(function(resolve, reject) {
                    var img = new Image();
                    img.src = "http://localhost/EcommerceLQK/admin/uploaded_image/" + imagesRevenue[index];
                    img.onload = function() {
                        loadedImages[index] = img; // Lưu ảnh đã tải
                        resolve();
                    };
                    img.onerror = function() {
                        reject('Lỗi tải ảnh: ' + img.src);
                    };
                });
            });

            // Sau khi tất cả ảnh đã tải, vẽ lên biểu đồ
            Promise.all(imagePromises).then(function() {
                chart.data.labels.forEach(function(value, index) {
                    var img = loadedImages[index];
                    var xPos = xScale.getPixelForValue(index); // Vị trí X của mỗi tick trên trục X
                    var yPos = chart.chartArea.bottom + 10; // Vị trí Y bên dưới trục X
                    var width = 40; // Kích thước ảnh
                    var height = 40; // Kích thước ảnh

                    // Vẽ hình ảnh lên canvas
                    ctx.drawImage(img, xPos - width / 2, yPos, width, height);
                });
            }).catch(function(error) {
                console.error(error); // Hiển thị lỗi nếu ảnh không tải được
            });
        }
    }]
});

// Biểu đồ số lượng bán (Sales Chart)
var ctxSales = document.getElementById('salesChart').getContext('2d');
var salesChart = new Chart(ctxSales, {
    type: 'bar', // Biểu đồ cột
    data: {
        labels: productNamesSales,  // Tên sản phẩm bán chạy
        datasets: [{
            label: 'Số lượng bán',
            data: unitsSold,
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)'
            ],
            borderWidth: 3 // Tăng độ đậm của đường viền các cột
        }]
    },
    options: {
        responsive: true,
        scales: {
            x: {
                ticks: {
                    autoSkip: false,
                    maxRotation: 45,
                    minRotation: 45,
                    font: {
                        size: 3, // Điều chỉnh kích thước chữ
                        family: 'Arial',
                        color: '#333',
                        weight: 'normal' // Đặt chữ đậm cho trục x
                    },
                    callback: function(value, index) {
                        return productNamesSales[index]; // Hiển thị tên sản phẩm
                    }
                },
                grid: { display: false }
            },
            y: {
                beginAtZero: true,
                ticks: {
                    color: '#333',
                    font: { weight: 'normal' } // Đặt chữ đậm cho trục y
                }
            }
        },
        plugins: {
            legend: {
                labels: { color: '#333' }
            },
            tooltip: {
                backgroundColor: 'rgba(255, 99, 132, 0.8)',
                titleColor: '#000',
                bodyColor: '#000',
            }
        }
    },
    plugins: [{
        afterDraw: function(chart) {
            var ctx = chart.ctx;
            var xScale = chart.scales.x;
            var images = imagesSales; // Dữ liệu ảnh sản phẩm bán chạy

            chart.data.labels.forEach(function(value, index) {
                var img = new Image();
                img.src = "http://localhost/EcommerceLQK/admin/uploaded_image/" + images[index];
                img.onload = function() {
                    var xPos = xScale.getPixelForValue(index); // Vị trí X của mỗi tick trên trục X
                    var yPos = chart.chartArea.bottom + 10; // Vị trí Y bên dưới trục X
                    var width = 40; // Kích thước ảnh
                    var height = 40; // Kích thước ảnh
                    // Vẽ hình ảnh lên canvas
                    ctx.drawImage(img, xPos - width / 2, yPos, width, height);
                };
            });
        }
    }]
});

</script>
</body>
</html>

