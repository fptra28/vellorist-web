<?php
session_start();

// Memeriksa apakah admin sudah login
if (!isset($_SESSION['username'])) {
    header("Location: ./login.php");
    exit();
}

// Menyertakan file konfigurasi database
include '../app/config_query.php';

// Fungsi untuk mendapatkan daftar kategori
function getCategoryList($conn)
{
    $query = "SELECT * FROM kategori_produk ORDER BY id_kategori ASC";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    return []; // Kembalikan array kosong jika tidak ada data
}

// Fungsi untuk mendapatkan jumlah produk per kategori
function getJumlahProdukPerKategori($conn)
{
    $query = "SELECT id_kategori, COUNT(*) as jumlah_produk FROM produk GROUP BY id_kategori";
    $result = $conn->query($query);

    $jumlahProdukPerKategori = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $jumlahProdukPerKategori[$row['id_kategori']] = $row['jumlah_produk'];
        }
    }
    return $jumlahProdukPerKategori;
}

// Mendapatkan daftar kategori dan jumlah produk per kategori
$categoryList = getCategoryList($conn);
$jumlah_produk_per_kategori = getJumlahProdukPerKategori($conn);
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>Vellorist - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="<?= $base_url ?>/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet" />

    <!-- Custom styles for this template-->
    <link href="<?= $base_url ?>/css/sb-admin-2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?= $base_url ?>/css/bootstrap.css" />
    <script src="<?= $base_url ?>/js/jquery-3.6.0.js"></script>
    <script src="<?= $base_url ?>/js/bootstrap.js"></script>
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion" id="accordionSidebar">
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <img src="../assets-admin/logo-obly.png" alt="logo-vellorist" height="35">
                <div class="sidebar-brand-text mx-3">Vellorist</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0" />

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="<?= $base_url ?>">
                    <i class="fa-solid fa-gauge-high"></i>
                    <span class="text-s">Dashboard</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= $base_url ?>/pesanan">
                    <i class="fas fa-bag-shopping"></i>
                    <span class="text-s">Pesanan</span></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="<?= $base_url ?>/produk">
                    <i class="fa-solid fa-store"></i>
                    <span class="text-s">Produk</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= $base_url ?>/promo">
                    <i class="fa-solid fa-tag"></i>
                    <span class="text-s">Promo</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= $base_url ?>/ulasan">
                    <i class="fas fa-comments"></i>
                    <span class="text-s">Ulasan</span></a>
            </li>
            <!-- Tampilkan Manajemen Admin hanya jika role adalah Superadmin -->
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'Superadmin'): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $base_url ?>/admin-management">
                        <i class="fas fa-comments"></i>
                        <span class="text-s">Manajemen Admin</span></a>
                </li>
            <?php endif; ?>


            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block" />

            <a href="<?= $base_url ?>/logout" class="btn btn-danger mx-3 mb-4">
                Logout
            </a>

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <div class="nav-link dropdown-toggle">
                                <span class="mr-2 d-none d-lg-inline text-dark">Hallo, <strong><?= $_SESSION['nama']; ?></strong></span>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Kategori Produk</h1>
                        <button type="button" class="btn btn-primary" data-toggle="modal"
                            data-target="#addProductModal">
                            Tambah Kategori
                        </button>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <!-- Card Example -->
                        <?php if (!empty($categoryList)) : ?>
                            <?php foreach ($categoryList as $category) : ?>
                                <div class="col-xl-3 col-md-6 mb-4">
                                    <div class="card border-left-primary shadow h-100 py-3 ps-2">
                                        <div class="card-body">
                                            <div class="row no-gutters align-items-center">
                                                <div class="col mr-2">
                                                    <div class="font-weight-bold text-primary text-uppercase mb-2" style="font-size: 1.2rem;">
                                                        <?= htmlspecialchars($category['nama_kategori']) ?>
                                                    </div>
                                                    <div class="text-sm text-gray-800">
                                                        <!-- Menampilkan Jumlah Produk -->
                                                        Jumlah Produk: <strong>
                                                            <?= isset($jumlah_produk_per_kategori[$category['id_kategori']])
                                                                ? $jumlah_produk_per_kategori[$category['id_kategori']]
                                                                : 0 ?> Produk
                                                        </strong>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="col-12 mb-3">
                                                        <a href="<?= $base_url ?>/produk/list">
                                                            <button type="button" class="btn btn-warning w-100 py-2" style="font-size: 1rem;">
                                                                View
                                                            </button>
                                                        </a>
                                                    </div>
                                                    <div class="col-12 mb-3">
                                                        <button type="button" class="btn btn-primary w-100 py-2" style="font-size: 1rem;">
                                                            Edit
                                                        </button>
                                                    </div>
                                                    <div class="col-12">
                                                        <button type="button" class="btn btn-danger w-100 py-2" style="font-size: 1rem;">
                                                            Hapus
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <span colspan="8" class="text-center">Tidak ada data Kategori.</span>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2025</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= $base_url ?>/vendor/jquery/jquery.min.js"></script>
    <script src="<?= $base_url ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= $base_url ?>/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= $base_url ?>/js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="<?= $base_url ?>/vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="<?= $base_url ?>/js/demo/chart-area-demo.js"></script>
    <script src="<?= $base_url ?>/js/demo/chart-pie-demo.js"></script>
</body>

</html>