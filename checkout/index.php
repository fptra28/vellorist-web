<?php
include "../app/config.php";
include "../app/footer.php";
require_once './midtrans-php-master/Midtrans.php'; // Path ke autoload Composer

// Ambil ID dari parameter GET
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

// Konfigurasi Midtrans
\Midtrans\Config::$serverKey = 'SB-Mid-server-rfayL0bZCkHAGh6-1qVQdpzQ'; // Ganti dengan Server Key Anda
\Midtrans\Config::$isProduction = false; // Ubah ke true jika ingin menggunakan mode produksi
\Midtrans\Config::$isSanitized = true; // Untuk sanitasi input
\Midtrans\Config::$is3ds = true; // 3D Secure untuk kartu kredit

// Fungsi untuk mengambil detail produk berdasarkan ID
function getDetail($conn, $id)
{
    $query = "SELECT produk.*, kategori_produk.nama_kategori FROM produk 
              JOIN kategori_produk ON produk.id_kategori = kategori_produk.id_kategori 
              WHERE id_produk = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return null; // Produk tidak ditemukan
    }
    return $result->fetch_assoc();
}

// Fungsi untuk mengambil produk acak
function getProducts($conn)
{
    $query = "SELECT produk.*, kategori_produk.nama_kategori FROM produk
              JOIN kategori_produk ON produk.id_kategori = kategori_produk.id_kategori
              ORDER BY RAND() LIMIT 6";

    $result = $conn->query($query);
    if (!$result) {
        die("Error executing query: " . $conn->error);
    }
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Ambil data produk
$produk = getDetail($conn, $id);
if (!$produk) {
    die("Produk tidak ditemukan");
}
$produkList = getProducts($conn);

$harga_produk = $produk['harga_produk'];
$nama_produk = $produk['nama_produk'];

// Menghitung total harga dengan biaya pengiriman
$biaya_pengiriman = 15000;
$total_harga = $harga_produk + $biaya_pengiriman;
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="<?= $url ?>/css/main.css">
    <link rel="stylesheet" href="<?= $url ?>/css/style.css">
    <link rel="stylesheet" href="<?= $url ?>/css/all.css">
    <!-- Atau jika menggunakan file PNG -->
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
                        <a class="nav-link text-white fs-12 fw-bold" href="<?= $url ?>">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white fs-12 fw-bold active" href="<?= $url ?>/produk">Produk</a>
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

    <div class="content-wrapper pt-7 pb-6">
        <section class="container">
            <div class="checkout-section p-3 p-lg-5 bg-light rounded-3">
                <h2 class="text-center mb-4">Checkout</h2>
                <div class="row">
                    <!-- Bagian Produk Checkout -->
                    <div class="col-md-7">
                        <h5 class="mb-3">Informasi Produk</h5>
                        <!-- Daftar Produk Checkout -->
                        <div class="card mb-4 rounded-4">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <img src="<?= $url ?>/assets/uploads/<?= htmlspecialchars($produk['foto_produk']) ?>" alt="Produk A" class="img-fluid rounded-3 me-4" style="width: 120px; height: 120px; object-fit: cover;">
                                    <div class="row align-items-center w-100">
                                        <div class="col-12 col-md-7">
                                            <h6 class="mb-1"><?= htmlspecialchars($produk['nama_produk']) ?></h6>
                                            <small class="text-muted">Kategori: <?= htmlspecialchars($produk['nama_kategori']) ?></small>
                                        </div>
                                        <div class="col-12 col-md-5 text-md-end mt-2 mt-md-0">
                                            <span class="fw-bold">Rp <?= number_format($produk['harga_produk'], 0, ',', '.') ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian Ringkasan Pesanan -->
                    <div class="col-md-5">
                        <div>
                            <form id="paymentForm" method="POST" action="">
                                <h5 class="mb-3">Informasi Pembeli</h5>
                                <div>
                                    <input type="hidden" name="idproduk" id="idproduk" value="<?= $produk['id_produk'] ?>">
                                    <input type="hidden" name="produkName" id="produkName" value="<?= $produk['nama_produk'] ?>">
                                    <input type="hidden" name="produkHarga" id="produkHarga" value="<?= $produk['harga_produk'] ?>">
                                    <input type="hidden" name="shippingPrice" id="shippingPrice" value="<?= $biaya_pengiriman ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Lengkap<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan nama Anda" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email<span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email Anda" required>
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Nomor Telepon<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Masukkan nomor telepon Anda" required>
                                </div>
                                <div class="mb-3">
                                    <label for="address" class="form-label">Alamat Pengiriman<span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="address" rows="3" name="address" placeholder="Masukkan alamat lengkap Anda" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="keterangan" class="form-label">Keterangan Pesanan</label>
                                    <textarea class="form-control" id="keterangan" rows="3" name="keterangan" placeholder="Masukkan keterangan pesanan anda"></textarea>
                                </div>
                                <div>
                                    <h5 class="mb-3">Ringkasan Pesanan</h5>
                                    <div class="card">
                                        <div class="card-body">
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span class="text-break w-75"><?= htmlspecialchars($produk['nama_produk']) ?></span>
                                                    <span>Rp <?= number_format($produk['harga_produk'], 0, ',', '.') ?></span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between">
                                                    <span>Biaya Pengiriman</span>
                                                    <span>Rp <?= number_format($biaya_pengiriman, 0, ',', '.') ?></span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between fw-bold text-end">
                                                    <span>Total</span>
                                                    <span>Rp <?= number_format($total_harga, 0, ',', '.') ?></span>
                                                </li>
                                            </ul>
                                            <button id="pay-button" type="submit" class="btn btn-primary w-100 mt-3">Lanjutkan Pembayaran</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="container mt-6">
            <div class="mb-5 text-center d-flex flex-column justify-content-center align-items-center gap-3">
                <h2 class="fw-bold fs10">Rekomendasi Produk</h2>
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
        </section>
    </div>

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

    <script src="<?= $url ?>/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= $url ?>/js/script.js"></script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-W7zbvZIr_n-kVmbH"></script>
</body>

</html>