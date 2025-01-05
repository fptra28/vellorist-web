<?php
session_start();

// Memeriksa apakah admin sudah login
if (!isset($_SESSION['username'])) {
    header("Location: ./login.php");
    exit();
}

// Menyertakan file konfigurasi database
include '../app/config_query.php';

// Memeriksa apakah admin adalah Superadmin
if ($_SESSION['role'] !== 'Superadmin') {
    header("Location: $base_url"); // Arahkan ke halaman lain jika bukan Superadmin
    exit();
}

// Fungsi untuk mendapatkan daftar admin dengan pencarian
function getAdmin($conn, $search = '')
{
    $search = $conn->real_escape_string($search); // Menghindari SQL injection
    $query = "SELECT * FROM tbl_admin WHERE nama_admin LIKE '%$search%' OR username_admin LIKE '%$search%' OR role LIKE '%$search%'";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    return []; // Kembalikan array kosong jika tidak ada data
}

// Mendapatkan nilai pencarian dari query string, jika ada
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Mendapatkan daftar admin berdasarkan pencarian
$adminList = getAdmin($conn, $search);
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>Vellorist - Manajemen Admin</title>

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
                <li class="nav-item active">
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
                        <h1 class="h3 mb-0 text-gray-800">Manajemen Admin</h1>
                        <div class="d-flex">
                            <!-- Search Bar -->
                            <form method="get" action="index.php" class="d-flex">
                                <input type="text" name="search" class="form-control w-50 mr-3" placeholder="Search Admin" value="<?= htmlspecialchars($search) ?>" aria-label="Search Admin">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </form>

                            <!-- Tambahkan Admin Button -->
                            <a href="<?= $base_url ?>/admin-management/add-admin.php">
                                <button type="button" class="btn btn-primary">Tambahkan Admin</button>
                            </a>
                        </div>
                    </div>

                    <!-- Content Row -->
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="table-dark">
                                <tr class="text-center">
                                    <th>ID Admin</th>
                                    <th>Nama Admin</th>
                                    <th>Username</th>
                                    <th>Role Admin</th>
                                    <th>More</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($adminList)) : ?>
                                    <?php foreach ($adminList as $admin) : ?>
                                        <tr class="text-center">
                                            <td class="align-middle"><?= htmlspecialchars($admin['id_admin']) ?></td>
                                            <td class="align-middle"><?= htmlspecialchars($admin['nama_admin']) ?></td>
                                            <td class="align-middle"><?= htmlspecialchars($admin['username_admin']) ?></td>
                                            <td class="align-middle">
                                                <?php
                                                // Memeriksa role admin dan menampilkan badge yang sesuai
                                                if ($admin['role'] === 'Superadmin') {
                                                    echo '<span class="badge bg-danger">' . htmlspecialchars($admin['role']) . '</span>';
                                                } elseif ($admin['role'] === 'Admin') {
                                                    echo '<span class="badge bg-primary">' . htmlspecialchars($admin['role']) . '</span>';
                                                } else {
                                                    echo '<span class="badge bg-secondary">' . htmlspecialchars($admin['role']) . '</span>';
                                                }
                                                ?>
                                            </td>
                                            <td class="d-flex justify-content-between align-items-center">
                                                <a href="./edit-admin.php?id=<?= $admin['id_admin'] ?>" class="w-50 mr-1">
                                                    <button type="button" class="btn btn-primary w-100">Edit</button>
                                                </a>
                                                <a href="./delete-admin.php?id=<?= $admin['id_admin'] ?>" class="w-50">
                                                    <button type="button" class="btn btn-danger w-100"
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus Admin ini?');">
                                                        Delete
                                                    </button>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada admin ditemukan.</td>
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