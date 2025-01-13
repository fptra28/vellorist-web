<?php
// Mengimpor file konfigurasi database
include "../app/config.php";
include "../app/footer.php";

// Mendapatkan nilai pencarian nomor pesanan dari URL
$nomor_pesanan = $_GET['nomor_pesanan'] ?? '';  // Default ke string kosong jika tidak ada

// Fungsi untuk mendapatkan data pesanan berdasarkan nomor pesanan
function getPesananByNomor($conn, $nomor_pesanan)
{
    // Mengamankan pencarian dari SQL Injection
    $nomor_pesanan = $conn->real_escape_string($nomor_pesanan);

    // Query untuk mencari pesanan berdasarkan nomor_pesanan
    $query = "
        SELECT 
            pelanggan.nama_pelanggan, 
            pelanggan.alamat_pengiriman, 
            pelanggan.nomor_telepon, 
            pelanggan.email_pelanggan, 
            pesanan.kurir,
            pesanan.nomor_resi,
            produk.nama_produk, 
            produk.harga_produk, 
            produk.foto_produk, 
            pesanan.total_harga, 
            pesanan.status_pemesanan, 
            pesanan.metode_pembayaran, 
            pesanan.keterangan, 
            pesanan.tanggal_pemesanan
        FROM pesanan
        LEFT JOIN pelanggan ON pesanan.id_pelanggan = pelanggan.id_pelanggan
        LEFT JOIN produk ON pesanan.id_produk = produk.id_produk
        WHERE pesanan.nomor_pesanan = ?
    ";

    // Menyiapkan statement SQL
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    // Mengikat parameter pencarian
    $stmt->bind_param('s', $nomor_pesanan);  // 's' untuk string (mengubah ke 's' jika nomor_pesanan berupa string)

    // Menjalankan query
    $stmt->execute();

    // Mendapatkan hasil query
    $result = $stmt->get_result();

    // Memastikan hasil query tidak kosong
    if ($result->num_rows > 0) {
        return $result;
    } else {
        return null; // Jika tidak ada hasil
    }
}

// Mendapatkan data pesanan berdasarkan nomor pesanan jika nomor pesanan tersedia
$result = ($nomor_pesanan) ? getPesananByNomor($conn, $nomor_pesanan) : null;

// Pastikan bahwa $result tidak null dan memiliki data
if ($result) {
    $row = $result->fetch_assoc();
} else {
    $row = null; // Atau beri pesan error jika perlu
}

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

// Menutup koneksi database
$conn->close();

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
                        <a class="nav-link text-white fs-12 fw-bold" href="<?= $url ?>">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white fs-12 fw-bold" href="<?= $url ?>/produk">Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white fs-12 fw-bold" href="<?= $url ?>/promo">Promo</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white fs-12 fw-bold active" href="<?= $url ?>/tracking">Tracking</a>
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
        <section class="pb-6 pt-7" id="hero-produk">
            <div class="container">
                <div class="row align-items-center">
                    <h3 class="fw-bold text-light text-center">Tracking Pesanan</h3>

                    <!-- Form untuk input pencarian -->
                    <form method="GET" action="<?= $_SERVER['PHP_SELF'] ?>" class="mb-4">
                        <div class="mb-3">
                            <label for="nomor_pesanan" class="form-label text-light">Nomor Pesanan</label>
                            <input type="text" name="nomor_pesanan" id="nomor_pesanan" class="form-control" placeholder="Masukkan nomor pesanan" value="<?= htmlspecialchars($nomor_pesanan) ?>">
                        </div>
                        <button type="submit" class="btn btn-secondary shadow">Cari Pesanan</button>
                    </form>
                </div>
            </div>
        </section>

        <!-- Tracking Section  -->
        <?php if (empty($nomor_pesanan)): ?>
            <section class="invoice-section p-5">
                <div class="row justify-content-center my-7">
                    <div class="col-12 text-center">
                        <img src="<?= $url ?>/assets/tracking.png" alt="Data tidak ditemukan" class="img-fluid" style="height: 300px;">
                        <p class="mt-5 text-muted">Masukkan nomor pesanan untuk mendapatkan informasi pesanan.</p>
                    </div>
                </div>
            </section>
        <?php elseif ($result && $result->num_rows > 0): ?>
            <section class="invoice-section px-1 py-5">
                <div class="container">
                    <div class="card shadow-sm">
                        <!-- Header Invoice -->
                        <div class="card-header bg-primary text-white text-center">
                            <h3 class="mb-0">Pesanan Anda</h3>
                            <small>No. Pesanan: <?= htmlspecialchars($nomor_pesanan) ?></small>
                        </div>

                        <!-- Body Invoice -->
                        <div class="card-body">
                            <!-- Informasi Pemesan -->
                            <div class="rounded-2 p-3">
                                <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mb-5">
                                    <!-- Informasi Pemesan -->
                                    <div class="card flex-grow-1 rounded-2 border-0 mb-3 mb-md-0">
                                        <h5 class="text-primary fw-bold">Informasi Pemesan</h5>
                                        <p class="m-0 mb-2">
                                            <strong>Nama:</strong> <?= htmlspecialchars($row['nama_pelanggan']) ?>
                                        </p>
                                        <p class="m-0 mb-2">
                                            <strong>Nomor Telepon:</strong> <?= htmlspecialchars($row['nomor_telepon']) ?>
                                        </p>
                                        <p class="m-0 mb-2">
                                            <strong>Alamat:</strong> <?= htmlspecialchars($row['alamat_pengiriman']) ?>
                                        </p>
                                        <p class="m-0">
                                            <strong>Metode Pembayaran:</strong> <?= htmlspecialchars($row['metode_pembayaran']) ?>
                                        </p>
                                    </div>

                                    <!-- Detail Pengiriman -->
                                    <div class="card flex-shrink-1 rounded-2 border-0">
                                        <h5 class="text-primary fw-bold">Detail Pengiriman</h5>
                                        <p class="m-0 mb-2">
                                            <strong>Kurir:</strong> <?= htmlspecialchars($row['kurir']) ?>
                                        </p>
                                        <div class="d-flex align-items-center mb-2">
                                            <strong class="me-2">Nomor Resi:</strong>
                                            <span id="resi-code" class="me-2"><?= htmlspecialchars($row['nomor_resi']) ?></span>
                                            <button class="btn btn-sm" onclick="copyResi()">
                                                <i class="fa-solid fa-copy"></i>
                                            </button>
                                        </div>
                                        <p class="m-0 align-items-center">
                                            <strong>Status:</strong>
                                            <?php if ($row['status_pemesanan'] == "Diproses"): ?>
                                                <span class="badge badge text-bg-warning text-dark"><?= htmlspecialchars($row['status_pemesanan']) ?></span>
                                            <?php elseif ($row['status_pemesanan'] == "Dikirim"): ?>
                                                <span class="badge badge text-bg-primary text-dark"><?= htmlspecialchars($row['status_pemesanan']) ?></span>
                                            <?php elseif ($row['status_pemesanan'] == "Selesai"): ?>
                                                <span class="badge badge text-bg-success text-white"><?= htmlspecialchars($row['status_pemesanan']) ?></span>
                                            <?php elseif ($row['status_pemesanan'] == "Dibatalkan"): ?>
                                                <span class="badge badge text-bg-danger text-white"><?= htmlspecialchars($row['status_pemesanan']) ?></span>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>

                                <!-- Detail Pesanan -->
                                <h5 class="text-primary fw-bold">Detail Pesanan</h5>
                                <div class="card mb-4 rounded-4 m-0 p-0">
                                    <div class="row g-0">
                                        <div class="col-md-3 p-3 border-1">
                                            <img src="<?= $url ?>/assets/uploads/<?= htmlspecialchars($row['foto_produk']) ?>" class="img-fluid rounded-3" alt="Produk">
                                        </div>
                                        <div class="col-md-9">
                                            <div class="card-body">
                                                <h5 class="card-title"><strong><?= htmlspecialchars($row['nama_produk']) ?></strong></h5>

                                                <p class="card-text mb-2">
                                                    <strong>Keterangan:</strong><br>
                                                    <span><?= htmlspecialchars($row['keterangan']) ?></span>
                                                </p>

                                                <p class="card-text mb-2">
                                                    <strong>Jumlah:</strong> 1
                                                </p>

                                                <p class="card-text mb-2">
                                                    <strong>Harga:</strong> Rp <?= number_format($row['harga_produk'], 0, ',', '.') ?>
                                                </p>

                                                <p class="card-text mb-2">
                                                    <strong>Pengiriman:</strong> Rp. 15.000
                                                </p>

                                                <p class="mt-auto h-100">
                                                    <strong>Total:</strong> Rp <?= number_format($row['total_harga'], 0, ',', '.') ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Catatan -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5 class="text-primary">Catatan</h5>
                                        <?php if ($row['status_pemesanan'] == "Diproses"): ?>
                                            <p class="text-primary">Pesanan Anda sedang dalam proses pengerjaan. Terima kasih telah berbelanja di Vellorist.</p>
                                        <?php elseif ($row['status_pemesanan'] == "Dikirim"): ?>
                                            <p class="text-warning">Pesanan Anda sedang dalam proses pengiriman. Terima kasih telah berbelanja di Vellorist.</p>
                                        <?php elseif ($row['status_pemesanan'] == "Selesai"): ?>
                                            <p class="text-success">Pesanan Anda sudah sampai tujuan. Terima kasih telah berbelanja di Vellorist.</p>
                                        <?php elseif ($row['status_pemesanan'] == "Dibatalkan"): ?>
                                            <p class="text-danger">Pesanan Anda Dibatalkan. Terima kasih telah berbelanja di Vellorist.</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Invoice -->
                        <div class="card-footer text-center bg-light mt-0">
                            <p class="mb-0">
                                Jika ada pertanyaan, silakan hubungi kami di WhatsApp <a href="https://wa.me/+6285143543557">+62 851-4354-3557</a>.
                            </p>
                        </div>
                    </div>
                </div>
            </section>
        <?php elseif ($nomor_pesanan): ?>
            <section class="invoice-section p-5">
                <div class="row justify-content-center my-7">
                    <div class="col-12 text-center">
                        <img src="<?= $url ?>/assets/no-found-2.png" alt="Data tidak ditemukan" class="img-fluid" style="height: 300px;">
                        <p class="mt-5 text-muted">Masukkan nomor pesanan untuk mendapatkan informasi pesanan.</p>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <section class="mb-7 mt-5">
            <div class="container">
                <div class="mb-5 text-center d-flex flex-column justify-content-center align-items-center gap-3">
                    <h2 class="fw-bold">Rekomendasi Produk</h2>
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
</body>

</html>