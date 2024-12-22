<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "trackify";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$sql_total_data = "SELECT COUNT(*) as count FROM laporan_kerusakan";
$sql_menunggu_verifikasi = "SELECT COUNT(*) as count FROM laporan_kerusakan WHERE status = 'Menunggu Verifikasi'";
$sql_dalam_proses = "SELECT COUNT(*) as count FROM laporan_kerusakan WHERE status = 'Dalam Proses Perbaikan'";
$sql_selesai = "SELECT COUNT(*) as count FROM laporan_kerusakan WHERE status = 'Selesai'";

$result_total_data = $conn->query($sql_total_data);
$result_menunggu_verifikasi = $conn->query($sql_menunggu_verifikasi);
$result_dalam_proses = $conn->query($sql_dalam_proses);
$result_selesai = $conn->query($sql_selesai);

if ($result_total_data && $result_menunggu_verifikasi && $result_dalam_proses && $result_selesai) {
    $total_data_count = $result_total_data->fetch_assoc()['count'];
    $menunggu_verifikasi_count = $result_menunggu_verifikasi->fetch_assoc()['count'];
    $dalam_proses_count = $result_dalam_proses->fetch_assoc()['count'];
    $selesai_count = $result_selesai->fetch_assoc()['count'];
} else {
    echo "Error in fetching data: " . $conn->error;
}

$sql_kategori = "SELECT kategori, COUNT(*) as count FROM laporan_kerusakan GROUP BY kategori";
$result_kategori = $conn->query($sql_kategori);

$kategori_labels = [];
$kategori_counts = [];
while ($row = $result_kategori->fetch_assoc()) {
    $kategori_labels[] = $row['kategori'];
    $kategori_counts[] = $row['count'];
}

$sql = "SELECT id, deskripsi, lokasi, kategori, status, tanggal FROM laporan_kerusakan ORDER BY tanggal DESC";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Dashboard - Trackify</title>
        <link rel="shortcut icon" href="img/Trackify.png">
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand ps-3" href="index.php">Trackify</a>
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
                <div class="input-group">
                    <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                    <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
                </div>
            </form>
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="tentang.php">About Trackify</a></li>
                        <li><a class="dropdown-item" href="costumer_service.php">Customer Service</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item" href="login.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Core</div>
                            <a class="nav-link" href="index.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>
                            <div class="sb-sidenav-menu-heading">Interface</div>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Layouts
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="Laporan.php">Laporan Kerusakan</a>
                                    <a class="nav-link" href="peta_lokasi.php">Peta Lokasi</a>
                                    <a class="nav-link" href="perkiraan_cuaca.php">Perkiraan Cuaca</a>
                                    <a class="nav-link" href="chatbot_laporan.php">ChatBot</a>
                                </nav>
                            </div>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                                <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                                Pages
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseAuth" aria-expanded="false" aria-controls="pagesCollapseAuth">
                                        Authentication
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="pagesCollapseAuth" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            <a class="nav-link" href="login.php">Login</a>
                                            <a class="nav-link" href="register.php">Register</a>
                                            <a class="nav-link" href="password.php  ">Forgot Password</a>
                                        </nav>
                                    </div>
                                </nav>
                            </div>
                            <div class="sb-sidenav-menu-heading">Addons</div>
                            <a class="nav-link" href="chart.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                                Charts
                            </a>
                            <a class="nav-link" href="tabel.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                                Tables
                            </a>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        Trackify
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Dashboard</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                        <div class="row">
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-primary text-white mb-4">
                                    <div class="card-body">Jumlah Data Masuk: <?php echo $total_data_count; ?></div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="data.php">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-danger text-white mb-4">
                                    <div class="card-body">Menunggu Verifikasi: <?php echo $menunggu_verifikasi_count; ?></div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="data.php">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-warning text-white mb-4">
                                    <div class="card-body">Dalam Proses Perbaikan: <?php echo $dalam_proses_count; ?></div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="data.php">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-success text-white mb-4">
                                    <div class="card-body">Selesai: <?php echo $selesai_count; ?></div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="data.php">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fa-solid fa-chart-pie"></i>
                                        Donut Chart: Persentase Status
                                    </div>
                                    <div class="card-body">
                                        <canvas id="myDonutChart"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-bar me-1"></i>
                                        Bar Chart: Jumlah Laporan per Kategori
                                    </div>
                                    <div class="card-body">
                                        <canvas id="myBarChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                DataTable Laporan Kerusakan Jalan
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                </main>
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                                <thead>
                                    <tr>
                                        <th class="center">ID</th>
                                        <th>Deskripsi</th>
                                        <th>Lokasi</th>
                                        <th class="center">Kategori</th>
                                        <th class="center">Status</th>
                                        <th class="center">Tanggal</th>
                                    </tr>
                                </thead>
                            <?php if ($result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td data-label="ID" class="center"><?php echo $row['id']; ?></td>
                                    <td data-label="Deskripsi"><?php echo htmlspecialchars($row['deskripsi']); ?></td>
                                    <td data-label="Lokasi"><?php echo htmlspecialchars($row['lokasi']); ?></td>
                                    <td data-label="Kategori" class="center"><?php echo htmlspecialchars($row['kategori']); ?></td>
                                    <td data-label="Status" class="center"><?php echo htmlspecialchars($row['status']); ?></td>
                                    <td data-label="Tanggal" class="center"><?php echo htmlspecialchars($row['tanggal']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="center">Tidak ada laporan</td>
                                </tr>
                            <?php endif; ?>
                        </div>
                    </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
        <script>
            var ctx1 = document.getElementById('myDonutChart').getContext('2d');
            var myDonutChart = new Chart(ctx1, {
                type: 'doughnut',
                data: {
                    labels: ['Menunggu Verifikasi', 'Dalam Proses Perbaikan', 'Selesai'],
                    datasets: [{
                        data: [<?php echo $menunggu_verifikasi_count; ?>, <?php echo $dalam_proses_count; ?>, <?php echo $selesai_count; ?>],
                        backgroundColor: ['#dc3545', '#ffc107', '#198754'],
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return tooltipItem.label + ": " + tooltipItem.raw;
                                }
                            }
                        }
                    }
                }
            });

            var ctx2 = document.getElementById('myBarChart').getContext('2d');
            var myBarChart = new Chart(ctx2, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($kategori_labels); ?>,
                    datasets: [{
                        label: 'Jumlah Laporan per Kategori',
                        data: <?php echo json_encode($kategori_counts); ?>,
                        backgroundColor: '#4e73df',
                        borderColor: '#4e73df',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    </body>
</html>