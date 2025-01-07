<?php
include "./app/config.php";
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vellorist</title>
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/style.css">
    <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./js/script.js"></script>
    <link rel="stylesheet" href="./css/all.css">
</head>

<body>
    <!-- Navbar -->
    <nav id="navbar" class="navbar bg-primary navbar-expand-lg fixed-top px-3 py-3">
        <div class="container-fluid">
            <!-- Logo -->
            <a class="navbar-brand text-white" href="#">
                <img src="assets/Logo.png" alt="Logo" height="40">
            </a>
            <!-- Toggle Button for Mobile View -->
            <button id="navbar-toggler" class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
                aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa-solid fa-bars" style="color: #ffffff;"></i>
            </button>
            <!-- Navbar Links -->
            <div class="collapse navbar-collapse justify-content-between" id="navbarContent">
                <!-- Center Menu -->
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white fs-12 fw-bold active" href="<?= $url ?>">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white fs-12 fw-bold" href="<?= $url ?>/produk">Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white fs-12 fw-bold" href="<?= $url ?>/promo">Promo</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white fs-12 fw-bold" href="<?= $url ?>/tracking">Tracking</a>
                    </li>
                </ul>

                <!-- Contact Icons for Desktop (Tampil hanya di layar besar) -->
                <div class="d-none d-lg-flex align-items-center social-icons gap-3 my-3 my-lg-0">
                    <a href="https://instagram.com" class="text-white">
                        <img src="assets/logo/instagram_logo.png" alt="Instagram" height="24">
                    </a>
                    <a href="https://wa.me/yourphonenumber" class="text-white">
                        <img src="assets/logo/whatsapp_logo.png" alt="WhatsApp" height="24">
                    </a>
                    <a href="https://maps.app.goo.gl/wHmtLoZLZa2FVWay8" class="text-white">
                        <img src="assets/logo/maps_logo.png" alt="Maps" height="24">
                    </a>
                </div>

                <!-- Dropdown for Contact Icons in Mobile View -->
                <hr class="d-lg-none text-light">
                <p class="d-lg-none text-light">Contact:</p>
                <div class=" d-lg-none mb-2 d-flex gap-2">
                    <a class="btn btn-secondary flex-grow-1" href="https://instagram.com">
                        <img src="assets/logo/instagram_logo_dark.png" alt="Instagram" height="24">
                    </a>
                    <a class="btn btn-secondary flex-grow-1" href="https://wa.me/yourphonenumber">
                        <img src="assets/logo/whatsapp_logo_dark.png" alt="WhatsApp" height="24">
                    </a>
                    <a class="btn btn-secondary flex-grow-1" href="https://maps.app.goo.gl/wHmtLoZLZa2FVWay8">
                        <img src="assets/logo/maps_logo_dark.png" alt="Maps" height="24">
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="content-wrapper">
        <!-- Hero Section -->
        <section class="bg-primary py-6" id="hero">
            <div class=" container">
                <div class="row align-items-center">
                    <!-- Gambar Banner -->
                    <div class="col-12 col-md-6 mb-4 mb-md-0">
                        <img src="<?= $url ?>/assets/foto-banner.png" alt="foto" class="img-fluid rounded">
                    </div>
                    <!-- Konten Teks -->
                    <div class="col-12 col-md-6">
                        <h1 class="text-white fw-bold fs-3 mb-3">Selamat datang di Vellorist</h1> <!-- Menambahkan margin bawah -->
                        <i class="text-white fw-light mb-3">
                            Jelajahi pesona bunga segar yang disusun dengan penuh kasih sayang.
                            Kami berkomitmen untuk mempercantik setiap momen spesial dalam hidup Anda.
                        </i> <!-- Menambahkan margin bawah -->
                        <hr class="text-light mb-3"> <!-- Menambahkan margin bawah -->
                        <button class="btn btn-secondary btn-lg w-30 fs-13">Lihat Koleksi</button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Information Section  -->
        <section class="py-5">
            <div class="container">
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    <!-- Card 1: 20+ Pilihan Koleksi Terbaik -->
                    <div class="col">
                        <div class="card shadow-sm h-100 border-0 rounded-4 py-2">
                            <img src="<?= $url ?>/assets/mdi_flower.png" alt="flower" class="card-img-top mx-auto mt-3" style="max-width: 40px;">
                            <div class="card-body text-center d-flex flex-column justify-content-around">
                                <h6 class="card-title fw-bold">20+ Pilihan Koleksi Terbaik</h6>
                                <p class="card-text">Tersedia lebih dari 20 koleksi bunga eksklusif yang siap menghiasi hari Anda.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Alamat -->
                    <div class="col">
                        <div class="card shadow-sm h-100 border-0 rounded-4 py-2">
                            <img src="<?= $url ?>/assets/basil_location-solid.png" alt="location" class="card-img-top mx-auto mt-3" style="max-width: 40px;">
                            <div class="card-body text-center d-flex flex-column justify-content-around">
                                <h6 class="card-text fw-bold">Jl. Pa'maan Jl. Klp. Dua Raya, Tugu, Kota Depok, Jawa Barat 16451 (Samping Masjid Nurul Ilmi Kampus E Gundar, Belakang Kampus E Gunadarma)</h6>
                                <a href="https://maps.app.goo.gl/JSBGG1oFfUPfeYrW6">Lihat Lokasi</a>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3: Kontak -->
                    <div class="col">
                        <div class="card shadow-sm h-100 border-0 rounded-4 py-2">
                            <img src="<?= $url ?>/assets/material-symbols_call.png" alt="call" class="card-img-top mx-auto mt-3" style="max-width: 40px;">
                            <div class="card-body text-center d-flex flex-column justify-content-around">
                                <h6 class="card-title fw-bold">0851 - 4354 - 3557</h6>
                                <p class="card-text">Jangan ragu untuk menghubungi kami untuk segala pertanyaan atau bantuan yang berkaitan dengan produk dan layanan kami.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Produk Section  -->
        <section class="py-5">
            <div class="container">
                <div class="mb-5 text-center d-flex flex-column justify-content-center align-items-center gap-3">
                    <h2 class="fw-bold">Produk Kami</h2>
                    <div class="pemisah bg-white rounded-pill"></div> <!-- Garis Pemisah -->
                </div>
                <div class="row row-cols-1 row-cols-md-3 g-4">

                </div>
            </div>
        </section>

</body>

</html>