<?php
include "./app/config.php";
include "./app/footer.php";

function getProducts($conn)
{
    $query = "
        SELECT 
            produk.*, 
            kategori_produk.nama_kategori 
        FROM produk
        JOIN kategori_produk ON produk.id_kategori = kategori_produk.id_kategori
        ORDER BY RAND()
        LIMIT 6
    ";

    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    return [];
}

$produkList = getProducts($conn);
?>

<!DOCTYPE html>
<html lang="id">

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
    <!-- Navbar -->
    <nav id="navbar" class="navbar navbar-expand-lg bg-primary fixed-top px-2 px-lg-5 py-3">
        <div class="container-fluid">
            <!-- Logo -->
            <a class="navbar-brand text-white m-0" href="#">
                <img src="<?= $url ?>/assets/Logo.png" alt="Logo" height="40">
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
                        <i class="fa-brands fa-instagram fs-11" height="24"></i>
                    </a>
                    <a href="https://wa.me/yourphonenumber" class="text-white">
                        <i class="fa-brands fa-whatsapp fs-11"></i>
                    </a>
                    <a href="https://maps.app.goo.gl/wHmtLoZLZa2FVWay8" class="text-white">
                        <i class="fa-solid fa-location-dot fs-11"></i>
                    </a>
                </div>

                <!-- Dropdown for Contact Icons in Mobile View -->
                <hr class="d-lg-none text-light">
                <p class="d-lg-none text-light">Contact:</p>
                <div class=" d-lg-none mb-2 d-flex gap-2">
                    <a class="btn btn-secondary flex-grow-1" href="https://instagram.com">
                        <i class="fa-brands fa-instagram fs-11"></i>
                    </a>
                    <a class="btn btn-secondary flex-grow-1" href="https://wa.me/+6285143543557">
                        <i class="fa-brands fa-whatsapp fs-11"></i>
                    </a>
                    <a class="btn btn-secondary flex-grow-1" href="https://maps.app.goo.gl/wHmtLoZLZa2FVWay8">
                        <i class="fa-solid fa-location-dot fs-11"></i>
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
                        <a href="#produk" class="btn btn-secondary fw-bold fs-13 rounded-pill px-4 py-2 shadow">Jelajahi Web</a>
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
                                <i class="card-text">Tersedia lebih dari 20 koleksi bunga eksklusif yang siap menghiasi hari Anda.</i>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Alamat -->
                    <div class="col">
                        <div class="card shadow h-100 border-0 rounded-4 py-2">
                            <img src="<?= $url ?>/assets/basil_location-solid.png" alt="location" class="card-img-top mx-auto mt-3" style="max-width: 40px;">
                            <div class="card-body text-center d-flex flex-column justify-content-around">
                                <h6 class="card-text fw-bold">Jl. Pa'maan Jl. Klp. Dua Raya, Tugu, Kota Depok, Jawa Barat 16451 (Samping Masjid Nurul Ilmi Kampus E Gundar, Belakang Kampus E Gunadarma)</h6>
                                <i><a href="https://maps.app.goo.gl/JSBGG1oFfUPfeYrW6">Lihat Lokasi</a></i>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3: Kontak -->
                    <div class="col">
                        <div class="card shadow-sm h-100 border-0 rounded-4 py-2">
                            <img src="<?= $url ?>/assets/material-symbols_call.png" alt="call" class="card-img-top mx-auto mt-3" style="max-width: 40px;">
                            <div class="card-body text-center d-flex flex-column justify-content-around">
                                <h6 class="card-title fw-bold">0851 - 4354 - 3557</h6>
                                <i class="card-text">Jangan ragu untuk menghubungi kami untuk segala pertanyaan atau bantuan yang berkaitan dengan produk dan layanan kami.</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Produk Section  -->
        <section class="py-5" id="produk">
            <div class="container">
                <div class="mb-5 text-center d-flex flex-column justify-content-center align-items-center gap-3">
                    <h2 class="fw-bold">Produk Kami</h2>
                    <div class="pemisah bg-white rounded-pill"></div> <!-- Garis Pemisah -->
                </div>
                <div class="row row-cols-2 row-cols-md-3 row-cols-lg-6 g-2">
                    <?php if (!empty($produkList)) : ?>
                        <?php foreach ($produkList as $produk) : ?>
                            <!-- Card Produk -->
                            <div class="col">
                                <div class="card shadow border-0 h-100 rounded-4">
                                    <!-- Card Image -->
                                    <div class="m-2 rounded-3 overflow-hidden">
                                        <img
                                            src="<?= $url ?>/assets/uploads/<?= $produk['foto_produk'] ?>"
                                            alt="Gambar produk <?= htmlspecialchars($produk['nama_produk']) ?>"
                                            class="w-100 h-100"
                                            style="object-fit: cover;">
                                    </div>
                                    <!-- Card Body -->
                                    <div class="card-body d-flex flex-column justify-content-between my-0 pt-1">
                                        <!-- Product Name -->
                                        <h6 class="card-title my-0 text-truncate"
                                            title="<?= htmlspecialchars($produk['nama_produk']) ?>">
                                            <?= htmlspecialchars($produk['nama_produk']) ?>
                                        </h6>
                                        <!-- Product Category -->
                                        <p class="card-text text-muted mb-1">
                                            <?= $produk['nama_kategori'] ?>
                                        </p>
                                        <!-- Product Price -->
                                        <p class="card-text text-primary mb-3 fs-12"
                                            title="Rp. <?= number_format($produk['harga_produk'], 0, '', '.') ?>">
                                            <strong>Rp. <?= number_format($produk['harga_produk'], 0, '', '.') ?></strong>
                                        </p>
                                        <!-- Button -->
                                        <a href="<?= $url ?>/produk/detail/index.php?id=<?= $produk['id_produk'] ?>"
                                            class="btn btn-secondary fw-bold mt-auto">
                                            Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <span colspan="8" class="text-center">Tidak ada data produk.</span>
                    <?php endif; ?>
                </div>

                <div class="mt-5 text-center d-flex flex-column justify-content-center align-items-center gap-3">
                    <a href="<?= $url ?>/produk" class="btn btn-primary fw-bold fs-13 rounded-pill px-4 py-2 shadow-lg">Lihat Koleksi</a>
                </div>
            </div>
        </section>

        <!-- Banner Section  -->
        <section class="container my-5">
            <div class="rounded-4 shadow p-3 pb-lg-3 pb-4" id="banner">
                <div class="row align-items-center">
                    <!-- Gambar Banner -->
                    <div class="col-12 col-md-6 mb-4 mb-md-0">
                        <img src="<?= $url ?>/assets/banner-foto-fix.png" alt="foto-banner" class="img-fluid">
                    </div>
                    <!-- Konten Teks -->
                    <div class="col-12 col-md-6">
                        <h1 class="text-black fw-bold fs-3 mb-3">Bunga Segar untuk Setiap Momen Spesial</h1>
                        <i class="text-black fw-light mb-3">
                            Temukan koleksi bunga indah untuk ulang tahun, pernikahan, atau hadiah spesial lainnya. Pesan sekarang dan buat momen Anda lebih berkesan!
                        </i>
                        <div class="pemisah rounded-pill my-3"></div> <!-- Garis Pemisah -->
                        <a href="<?= $url ?>/produk" class="btn btn-secondary fw-bold fs-13 rounded-pill px-4 py-2 shadow">Lihat Koleksi</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer Section  -->
        <footer class="bg-primary text-white pt-5">
            <div class="container">
                <div class="row">
                    <!-- Foto Section -->
                    <div class="col-12 col-md-3 mb-4 text-center">
                        <img src="<?= $url ?>/assets/logo-vertikal.png" alt="Logo" class="img-fluid" style="max-height: 100px; object-fit: contain;">
                    </div>

                    <!-- Kategori Produk Section -->
                    <div class="col-12 col-md-3 mb-4">
                        <h5>Kategori Produk</h5>
                        <ul class="list-unstyled">
                            <?php foreach ($footer as $kategori) : ?>
                                <li><a href="<?= $url ?>/produk/index.php?kategori=<?= $kategori['id_kategori'] ?>" class="text-white"><?= htmlspecialchars($kategori['nama_kategori']) ?></a></li>
                            <?php endforeach; ?>
                            <li><a href="<?= $url ?>/produk" class="text-white">Dan Lainnya...</a></li>
                        </ul>
                    </div>

                    <!-- Useful Links Section -->
                    <div class="col-12 col-md-3 mb-4">
                        <h5>Useful Links</h5>
                        <ul class="list-unstyled">
                            <li><a href="#" class="text-white">Lacak Bunga</a></li>
                            <li><a href="#" class="text-white">Promo</a></li>
                        </ul>
                    </div>

                    <!-- Contact Section -->
                    <div class="col-12 col-md-3 mb-4">
                        <h5>Contact</h5>
                        <p><strong>Instagram:</strong>
                            <a href="https://www.instagram.com/vellorist/" target="_blank" class="text-white">
                                @vellorist
                            </a>
                        </p>
                        <p><strong>WhatsApp:</strong>
                            <a href="https://wa.me/+6285143543557" target="_blank" class="text-white">
                                +62 851-4354-3557
                            </a>
                        </p>
                        <p><strong>Linktree:</strong>
                            <a href="https://linktr.ee/Vellorist?fbclid=PAZXh0bgNhZW0CMTEAAaa5y0fYQ_zp7HM-1Y8sFniGCZrTKsXWc50CrBTh7aB56RYoKXYnfJa6kxA_aem_vMUPcpINxUeR7gKXn-TlTA" class="text-white">
                                Vellorist
                            </a>
                        </p>
                        <p><strong>Address:</strong><a href="https://maps.app.goo.gl/JSBGG1oFfUPfeYrW6" class="link-light">Jl. Pa'maan Jl. Klp. Dua Raya, Tugu, Kota Depok, Jawa Barat 16451 (Samping Masjid Nurul Ilmi Kampus E Gundar, Belakang Kampus E Gunadarma)</a></p>
                    </div>
                </div>
            </div>
            <div class="p-4 border-top">
                <p class="text-center text-white mb-0">&copy; 2025 All Rights Reserved. Designed by <a href="#" class="text-white">Vellorist</a></p>
            </div>
        </footer>

        <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
        <script src="./js/script.js"></script>
</body>

</html>