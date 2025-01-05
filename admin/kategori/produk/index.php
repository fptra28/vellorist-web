<?php
session_start();

// Memeriksa apakah admin sudah login
if (!isset($_SESSION['username'])) {
    header("Location: ./login.php");
    exit();
}

// Menyertakan file konfigurasi database
include "../../app/config_query.php";
include "../../../app/config.php";

// Mendapatkan id_kategori dari query string
$id_kategori = isset($_GET['id']) ? $_GET['id'] : 0;

// Fungsi untuk mengambil data produk berdasarkan id_kategori
function getProductsByCategory($id_kategori)
{
    global $conn;

    $query = "SELECT * FROM produk WHERE id_kategori = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $id_kategori);
        $stmt->execute();
        $result = $stmt->get_result();
        $products = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $products;
    }

    return [];
}

// Fungsi untuk mengambil data kategori berdasarkan id_kategori
function getCategory($conn, $id_kategori)
{
    $query = "SELECT * FROM kategori_produk WHERE id_kategori = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $id_kategori);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result->fetch_assoc(); // Hanya ambil satu kategori
        }
    }

    return null; // Kembalikan null jika tidak ditemukan kategori
}


// Ambil produk berdasarkan id_kategori
$productList = getProductsByCategory($id_kategori);
$category = getCategory($conn, $id_kategori);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>Vellorist - Kategori</title>

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
                <img src="<?= $base_url ?>/assets-admin/logo-obly.png" alt="logo-vellorist" height="35">
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
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <a href="<?= $base_url ?>/kategori">
                                <button type="button" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Back</button>
                            </a>
                            <h1 class="h3 mb-0 text-gray-800">Produk <?= htmlspecialchars($category['nama_kategori']) ?></h1>
                        </div>
                        <a href="<?= $base_url ?>/kategori/produk/add-produk.php?id_kategori=<?= $category['id_kategori'] ?>" type="button" class="btn btn-primary">
                            Tambah Produk
                        </a>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <!-- Card Example -->
                        <?php if (!empty($productList)) : ?>
                            <?php foreach ($productList as $product): ?>
                                <div class="col-md-4 col-sm-6 mb-4">
                                    <div class="card shadow-sm d-flex flex-row" style="height: 200px; overflow: hidden; border-radius: 10px;">
                                        <!-- Gambar Produk di Samping Kiri -->
                                        <img src="<?= $url ?>/assets/uploads/<?= htmlspecialchars($product['foto_produk']) ?>"
                                            class="card-img-left"
                                            alt="Produk Image"
                                            style="height: 100%; object-fit: cover;">
                                        <!-- Body Card di Samping Kanan -->
                                        <div class="card-body d-flex flex-column justify-content-between p-2">
                                            <div>
                                                <h5 class="card-title"><?= htmlspecialchars($product['nama_produk']) ?></h5>
                                                <div class="card-text text-muted fs-6 d-flex justify-content-around mb-2 gap-2">
                                                    <p class="mb-0 badge text-bg-success flex-grow-1 fs-6">
                                                        Harga: Rp <?= number_format($product['harga_produk'], 0, ',', '.') ?>
                                                    </p>
                                                </div>
                                                <i class="card-text fs-6">"<?= htmlspecialchars($product['deskripsi_produk']) ?>"</i>
                                            </div>
                                            <!-- Tombol Aksi -->
                                            <div class="d-flex justify-content-between gap-2">
                                                <a href="edit-produk.php?id=<?= $product['id_produk'] ?>" class="btn btn-primary flex-grow-1">Edit</a>
                                                <a href="<?= $base_url ?>/kategori/produk/delete-produk/index.php?id=<?= $product['id_produk'] ?>&id_kategori=<?= $product['id_kategori'] ?>"
                                                    class="btn btn-danger flex-grow-1"
                                                    onclick="return confirm('Yakin ingin menghapus produk ini?');">Hapus</a>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <span colspan="8" class="text-center">Tidak ada data Produk untuk kategori <strong><?= htmlspecialchars($category['nama_kategori']) ?></strong>.</span>
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

    <script src="<?= $base_url ?>/js/script.js"></script>

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