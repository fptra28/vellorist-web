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

// Inisialisasi variabel untuk error dan sukses
$error = '';
$success = '';
$id = ''; // Inisialisasi ID admin

// Mendapatkan ID admin yang akan diedit dari parameter URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Ambil data admin berdasarkan ID
    $query = "SELECT * FROM tbl_admin WHERE id_admin = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $admin = $result->fetch_assoc();
        } else {
            $error = "Admin tidak ditemukan.";
        }

        $stmt->close();
    } else {
        $error = "Gagal menyiapkan query: " . $conn->error;
    }
}

// Memproses form saat tombol submit ditekan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    // Mendapatkan data dari form
    $id = intval($_POST['id']);
    $nama = trim($_POST['nama']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);

    // Validasi sederhana
    if (empty($nama) || empty($username) || empty($role)) {
        $error = "Nama, Username, dan Role wajib diisi.";
    } elseif (strlen($username) < 5) {
        $error = "Username harus memiliki minimal 5 karakter.";
    } else {
        // Hash password jika diisi
        $hashedPassword = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : null;

        // Update data ke dalam database
        if ($hashedPassword) {
            $query = "UPDATE tbl_admin SET nama_admin = ?, username_admin = ?, password = ?, role = ? WHERE id_admin = ?";
        } else {
            $query = "UPDATE tbl_admin SET nama_admin = ?, username_admin = ?, role = ? WHERE id_admin = ?";
        }

        $stmt = $conn->prepare($query);

        if ($stmt) {
            if ($hashedPassword) {
                $stmt->bind_param("ssssi", $nama, $username, $hashedPassword, $role, $id);
            } else {
                $stmt->bind_param("sssi", $nama, $username, $role, $id);
            }

            if ($stmt->execute()) {
                // Berhasil menyimpan
                $success = "Admin berhasil diperbarui.";
                header("Location: $base_url/admin-management");
                exit();
            } else {
                // Menangani error jika username sudah ada
                if ($conn->errno === 1062) { // Error code untuk duplikat
                    $error = "Username sudah digunakan. Gunakan username lain.";
                } else {
                    $error = "Terjadi kesalahan saat menyimpan data: " . $conn->error;
                }
            }

            $stmt->close();
        } else {
            $error = "Gagal menyiapkan query: " . $conn->error;
        }
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

    <title>Vellorist - Edit Admin</title>

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
                    <div class="d-flex align-items-center mb-4">
                        <a href="<?= $base_url ?>/admin-management">
                            <button type="button" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Back</button>
                        </a>
                        <h1 class="h3 mb-0 ms-3 text-gray-800">Edit Admin</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="container">
                        <?php if (isset($admin)): ?>
                            <form action="" method="post">
                                <div class="form-group">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($admin['id_admin']) ?>">
                                </div>
                                <div class="form-group">
                                    <label for="nama">Nama<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nama" name="nama" value="<?= htmlspecialchars($admin['nama_admin']) ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="username">Username<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($admin['username_admin']) ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password <span class="text-danger">(kosongkan jika tidak diubah)</span></label>
                                    <input type="password" class="form-control" id="password" name="password">
                                </div>
                                <div class="form-group">
                                    <label for="level">Role<span class="text-danger">*</span></label>
                                    <select class="form-control" id="role" name="role" required>
                                        <option value="Superadmin" <?= $admin['role'] === 'Superadmin' ? 'selected' : '' ?>>Superadmin</option>
                                        <option value="Admin" <?= $admin['role'] === 'Admin' ? 'selected' : '' ?>>Admin</option>
                                    </select>
                                </div>
                                <div>
                                    <div class="text-danger">
                                        <?php if (isset($error)) echo $error; ?>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary w-100 mt-5">SUBMIT</button>
                            </form>
                        <?php else: ?>
                            <p>Data admin tidak ditemukan.</p>
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