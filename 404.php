<?php
include './app/config.php';
header("HTTP/1.0 404 Not Found");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vellorist</title>
    <link rel="stylesheet" href="<?= $url ?>/css/main.css">
    <link rel="stylesheet" href="<?= $url ?>/css/style.css">
    <link rel="stylesheet" href="<?= $url ?>/css/all.css">
    <link rel="icon" type="image/svg+xml" href="/assets/icon.png" />
</head>

<body>
    <div class="container d-flex flex-column justify-content-center align-items-center gap-4" style="height: 100vh;">
        <!-- Ganti <h1> dengan <img> -->
        <img src="<?= $url ?>/assets/404.png" alt="404 Not Found" class="img-fluid" style="max-width: 300px;">
        <div class="text-center">
            <p class="fs-11 fw-bold">Oops! Halaman yang Anda cari tidak ditemukan.</p>
            <p class="fs-13">Kami tidak dapat menemukan halaman yang Anda tuju. Mungkin halaman tersebut sudah dipindahkan atau dihapus.</p>
            <p>
                <a href="<?= $url ?>" class="btn btn-primary">Kembali ke Beranda</a>
            </p>
        </div>
    </div>

    <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./js/script.js"></script>
</body>

</html>