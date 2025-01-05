<?php
// Memulai session
session_start();

// Memuat koneksi ke database
include 'app/config_query.php';

// Mengecek apakah form login disubmit
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validasi input kosong
    if (empty($username) || empty($password)) {
        header("Location: $base_url/login.php?error=1");
        exit();
    }

    // Menyiapkan query untuk memeriksa data pengguna dari tbl_admin
    $sql = "SELECT * FROM tbl_admin WHERE username_admin = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Jika data ditemukan, lanjutkan ke validasi password
    if ($result->num_rows > 0) {
        // Mengambil data admin
        $admin = $result->fetch_assoc();

        // Memeriksa password hash
        if (password_verify($password, $admin['password'])) {
            // Password valid, menyimpan session untuk admin
            $_SESSION['username'] = $admin['username_admin'];
            $_SESSION['nama'] = $admin['nama_admin'];
            $_SESSION['id_admin'] = $admin['id_admin'];
            $_SESSION['role'] = $admin['role']; // Menyimpan role (misalnya admin)

            // Mengarahkan pengguna ke halaman dashboard admin
            header("Location: $base_url");
            exit();
        } else {
            // Jika password salah
            header("Location: $base_url/login.php?error=2");
            exit();
        }
    } else {
        // Jika username tidak ditemukan
        header("Location: $base_url/login.php?error=3");
        exit();
    }
}
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
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" />

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="./css/bootstrap.css" />
    <script src="./js/jquery-3.6.0.js"></script>
    <script src="./js/bootstrap.js"></script>
</head>

<body class="bg-gradient-dark">
    <div class="container">
        <div class="d-flex justify-content-center align-items-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image">
                                <img class="img-fluid" src="../assets/id-11134207-7qula-ljz8cwwtsgte34.jpeg" alt="login">
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5 d-flex flex-column align-items-center justify-content-center">
                                    <div class="text-center mb-4">
                                        <h1 class="h4 text-gray-900">Welcome Back!</h1>
                                    </div>

                                    <div class="mb-4">
                                        <?php
                                        if (isset($_GET['error'])) {
                                            if ($_GET['error'] == "1") {
                                                echo '<i class="text-danger">Username atau Password tidak boleh kosong!</i>';
                                            } else if ($_GET['error'] == "2") {
                                                echo '<i class="text-danger">Login Gagal! Password tidak sesuai!!</i>';
                                            } else if ($_GET['error'] == "3") {
                                                echo '<i class="text-danger">Login Gagal! Username tidak ditemukan!</i>';
                                            }
                                        }
                                        ?>
                                    </div>

                                    <form action="login.php" method="POST" class="user w-100 mt-3">
                                        <div class="form-group">
                                            <input type="text" name="username" class="form-control form-control-user" placeholder="Masukkan Username...">
                                        </div>
                                        <div class="form-group mb-5">
                                            <input type="password" name="password" class="form-control form-control-user" placeholder="Masukkan Password...">
                                        </div>
                                        <hr>
                                        <button type="submit" name="submit" class="btn btn-primary btn-user btn-block mt-4">
                                            Login
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
</body>

</html>