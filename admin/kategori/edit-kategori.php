<?php
// Memulai session
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ./login.php");
    exit();
}

// Menyertakan file konfigurasi database
include '../app/config_query.php';

// Inisialisasi variabel untuk error
$error = '';

// Mendapatkan ID kategori dari URL
$id_kategori = $_GET['id'] ?? null;

// Jika ID tidak ada, redirect ke halaman kategori
if (!$id_kategori) {
    header("Location: $base_url/kategori");
    exit();
}

// Ambil data kategori berdasarkan ID
$query = "SELECT * FROM kategori_produk WHERE id_kategori = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_kategori);
$stmt->execute();
$result = $stmt->get_result();

// Jika kategori tidak ditemukan
if ($result->num_rows === 0) {
    header("Location: $base_url/kategori");
    exit();
}

// Ambil data kategori
$kategori = $result->fetch_assoc();

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil input dari form
    $nama_kategori = trim($_POST['kategori']);

    // Validasi input
    if (empty($nama_kategori)) {
        $error = "Nama kategori tidak boleh kosong.";
    } else {
        // Query untuk mengupdate kategori
        $query = "UPDATE kategori_produk SET nama_kategori = ? WHERE id_kategori = ?";
        $stmt = $conn->prepare($query);

        if ($stmt === false) {
            die('Error preparing statement: ' . $conn->error);
        }

        // Binding parameter
        $stmt->bind_param("si", $nama_kategori, $id_kategori);

        // Eksekusi query
        if ($stmt->execute()) {
            // Berhasil memperbarui kategori
            header("Location: $base_url/kategori");
            exit();
        } else {
            // Gagal memperbarui kategori
            $error = "Terjadi kesalahan saat memperbarui kategori.";
        }

        // Menutup prepared statement
        $stmt->close();
    }
}

// Menutup koneksi database
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>Vellorist - Tambah Kategori</title>

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
                    <div class="d-flex align-items-center mb-4">
                        <a href="<?= $base_url ?>/kategori">
                            <button type="button" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Back</button>
                        </a>
                        <h1 class="h3 mb-0 ms-3 text-gray-800">Edit Kategori</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="container">
                        <form action="" method="post">
                            <div class="form-group">
                                <input type="text" class="form-control" id="id" name="id" hidden="true" placeholder>
                            </div>
                            <div class="form-group">
                                <label for="kategori">Nama Kategori<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="kategori" name="kategori" placeholder="<?= htmlspecialchars($kategori['nama_kategori']) ?>" required>
                            </div>
                            <div>
                                <div class="text-danger">
                                    <?php if (isset($error)) echo $error; ?>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 mt-5">SUBMIT</button>
                        </form>
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