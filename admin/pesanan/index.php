<?php
session_start();

// Memeriksa apakah admin sudah login
if (!isset($_SESSION['username'])) {
    header("Location: ./login.php");
    exit();
}

// Menyertakan file konfigurasi database
include '../app/config_query.php';

// Mengambil nilai pencarian dari URL jika ada
$search = $_GET['search'] ?? '';  // Default ke string kosong jika tidak ada

// Fungsi untuk mendapatkan daftar pesanan
function getPesananList($conn, $search = '')
{
    // Menangani pencarian menggunakan parameter 'search' dari URL
    $searchTerm = $conn->real_escape_string($search);  // Tanpa '%' untuk pencarian tepat

    // Query untuk mencari berdasarkan id_pesanan atau menampilkan semua data jika kosong
    $query = "
        SELECT 
            pesanan.id_pesanan,
            pelanggan.nama_pelanggan,
            pesanan.tanggal_pemesanan,
            produk.nama_produk,
            pesanan.total_harga,
            pesanan.status_pemesanan,
            pesanan.metode_pembayaran,
            pesanan.keterangan
        FROM pesanan
        LEFT JOIN pelanggan ON pesanan.id_pelanggan = pelanggan.id_pelanggan
        LEFT JOIN produk ON pesanan.id_produk = produk.id_produk
        WHERE 
            (pesanan.id_pesanan = ? OR ? = '')
        ORDER BY pesanan.tanggal_pemesanan DESC
    ";

    // Menyiapkan statement dan mengikat parameter pencarian
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    // Menggunakan parameter bind untuk pencarian
    $stmt->bind_param('ss', $searchTerm, $search);  // 'ss' untuk 2 parameter string

    // Menjalankan query
    $stmt->execute();

    // Mendapatkan hasil query
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    return []; // Kembalikan array kosong jika tidak ada data
}

// Mendapatkan daftar pesanan
$pesananList = getPesananList($conn, $search);
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
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= $base_url ?>">
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
            <li class="nav-item active">
                <a class="nav-link" href="<?= $base_url ?>/pesanan">
                    <i class="fas fa-bag-shopping"></i>
                    <span class="text-s">Pesanan</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= $base_url ?>/kategori">
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
                        <i class="fa-solid fa-user-tie"></i>
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
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Pesanan</h1>
                        <div class="d-flex">
                            <!-- Search Bar -->
                            <form method="get" action="index.php" class="d-flex">
                                <div class="input-group w-50 mr-3">
                                    <!-- Kotak yang menyatu dengan input search -->
                                    <span class="input-group-text">V-</span>
                                    <input type="text" name="search" class="form-control" placeholder="Search pesanan" value="<?= htmlspecialchars($search) ?>" aria-label="Search Admin">
                                </div>
                                <button type="submit" class="btn btn-primary">Search</button>
                            </form>
                            <a href="./add-pesanan.php">
                                <button type="button" class="btn btn-primary">Tambahkan Pesanan</button>
                            </a>
                        </div>
                    </div>

                    <!-- Tabel Pesanan -->
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="table-dark">
                                <tr class="text-center">
                                    <th>ID Pesanan</th>
                                    <th>Nama Pelanggan</th>
                                    <th>Tanggal Pemesanan</th>
                                    <th>Produk</th>
                                    <th>Total Harga</th>
                                    <th>Status Pemesanan</th>
                                    <th>Metode Pembayaran</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($pesananList)) : ?>
                                    <?php foreach ($pesananList as $pesanan) : ?>
                                        <tr class="text-center">
                                            <td class="align-middle">V-<?= htmlspecialchars($pesanan['id_pesanan']) ?></td>
                                            <td class="align-middle"><?= htmlspecialchars($pesanan['nama_pelanggan']) ?></td>
                                            <td class="align-middle"><?= htmlspecialchars($pesanan['tanggal_pemesanan']) ?></td>
                                            <td class="align-middle"><?= htmlspecialchars($pesanan['nama_produk']) ?></td>
                                            <td class="align-middle">Rp <?= number_format($pesanan['total_harga'], 0, ',', '.') ?></td>
                                            <td class="align-middle">
                                                <?php if ($pesanan['status_pemesanan'] == "Dalam Proses"): ?>
                                                    <span class="badge badge text-bg-primary text-white"><?= htmlspecialchars($pesanan['status_pemesanan']) ?></span>
                                                <?php elseif ($pesanan['status_pemesanan'] == "Dalam Pengiriman"): ?>
                                                    <span class="badge badge text-bg-warning text-dark"><?= htmlspecialchars($pesanan['status_pemesanan']) ?></span>
                                                <?php elseif ($pesanan['status_pemesanan'] == "Selesai"): ?>
                                                    <span class="badge badge text-bg-success text-white"><?= htmlspecialchars($pesanan['status_pemesanan']) ?></span>
                                                <?php elseif ($pesanan['status_pemesanan'] == "Dibatalkan"): ?>
                                                    <span class="badge badge text-bg-danger text-white"><?= htmlspecialchars($pesanan['status_pemesanan']) ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="align-middle"><?= htmlspecialchars($pesanan['metode_pembayaran']) ?></td>
                                            <td class="align-middle"><?= htmlspecialchars($pesanan['keterangan']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada data pesanan.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
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