<?php
include "../../app/config.php";
include "../../app/footer.php";

// Ambil ID dari parameter GET
$id = isset($_GET['id']) ? intval($_GET['id']) : null;
$rating = isset($_GET['rating']) ? intval($_GET['rating']) : null;

if (!$id) {
    die("Produk tidak ditemukan.");
}

// Fungsi untuk mengambil detail produk berdasarkan ID
function getDetail($conn, $id)
{
    $query = "SELECT produk.*, kategori_produk.nama_kategori FROM produk JOIN kategori_produk ON produk.id_kategori = kategori_produk.id_kategori WHERE id_produk = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return null; // Produk tidak ditemukan
    }

    return $result->fetch_assoc();
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

function getUlasanByIdProduk($conn, $id)
{
    // Query untuk mengambil ulasan berdasarkan id_produk
    $query = "
        SELECT 
            ulasan.id_ulasan, 
            produk.nama_produk, 
            pelanggan.nama_pelanggan, 
            ulasan.rating, 
            ulasan.komentar 
        FROM ulasan_produk ulasan
        JOIN produk ON ulasan.id_produk = produk.id_produk
        JOIN pelanggan ON ulasan.id_pelanggan = pelanggan.id_pelanggan
        WHERE ulasan.id_produk = ?"; // Menyaring berdasarkan id_produk

    // Menyiapkan query
    $stmt = $conn->prepare($query);

    // Cek apakah prepare berhasil
    if ($stmt === false) {
        die('Query prepare failed: ' . $conn->error); // Menampilkan error jika prepare gagal
    }

    // Bind parameter untuk id_produk
    $stmt->bind_param("i", $id);

    // Eksekusi query
    $stmt->execute();
    $result = $stmt->get_result();

    // Mengembalikan data jika ada ulasan
    return $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

// Ambil data
$produk = getDetail($conn, $id);
$produkList = getProducts($conn);
$ulasan = getUlasanByIdProduk($conn, $id);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk</title>
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
        <!-- Product Detail Section -->
        <?php if (!empty($produk)) : ?>
            <section class="container">
                <!-- Wrapper Section -->
                <div class="p-4 bg-light rounded shadow-sm">
                    <div class="row">
                        <!-- Gambar Produk -->
                        <div class="col-md-4 text-center mb-3 mb-lg-0">
                            <img src="<?= $url ?>/assets/uploads/<?= htmlspecialchars($produk['foto_produk']) ?>"
                                class="img-fluid rounded border shadow"
                                alt="Gambar produk: <?= htmlspecialchars($produk['nama_produk']) ?>"
                                style="max-width: 100%; height: auto;">
                        </div>

                        <!-- Detail Produk -->
                        <div class="col-md-8">
                            <!-- Nama Produk -->
                            <h3 class="fw-bold"><?= htmlspecialchars($produk['nama_produk']) ?></h3>

                            <!-- Kategori Produk -->
                            <p class="text-muted mb-2">
                                <?= htmlspecialchars($produk['nama_kategori']) ?>
                            </p>

                            <!-- Harga Produk -->
                            <h5 class="text-primary mb-3">
                                <strong>Rp <?= number_format($produk['harga_produk'], 0, ',', '.') ?></strong>
                            </h5>

                            <!-- Deskripsi Produk -->
                            <div class="card">
                                <div class="card-header bg-dark-subtle text-dark">
                                    <h5 class="mb-0 fs-12">Deskripsi Produk</h5>
                                </div>
                                <div class="card-body">
                                    <p class="mb-0"><?= nl2br(htmlspecialchars($produk['deskripsi_produk'])) ?></p>
                                </div>
                            </div>

                            <!-- Tombol Tambah ke Keranjang -->
                            <a href="<?= $url ?>/checkout/index.php?id=<?= $produk['id_produk'] ?>" class="btn btn-success w-100 mt-3">
                                <strong>Beli Sekarang</strong>
                            </a>
                        </div>
                    </div>

                    <div class="mt-5">
                        <!-- Header Section -->
                        <h4 class="font-weight-bold mb-4">Ulasan Produk</h4>

                        <div class="row g-2">
                            <!-- Filter Card -->
                            <div class="col-md-2">
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">Rating</h5>
                                        <form method="GET" action="" class="mb-0 d-flex flex-column">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="rating[]" value="1" id="rating1" <?= isset($_GET['rating']) && in_array(1, $_GET['rating']) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="rating1"><i class="fas fa-star text-warning"></i> 1</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="rating[]" value="2" id="rating2" <?= isset($_GET['rating']) && in_array(2, $_GET['rating']) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="rating2"><i class="fas fa-star text-warning"></i> 2</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="rating[]" value="3" id="rating3" <?= isset($_GET['rating']) && in_array(3, $_GET['rating']) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="rating3"><i class="fas fa-star text-warning"></i> 3</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="rating[]" value="4" id="rating4" <?= isset($_GET['rating']) && in_array(4, $_GET['rating']) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="rating4"><i class="fas fa-star text-warning"></i> 4</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="rating[]" value="5" id="rating5" <?= isset($_GET['rating']) && in_array(5, $_GET['rating']) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="rating5"><i class="fas fa-star text-warning"></i> 5</label>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Reviews List -->
                            <div class="col-md-10">
                                <?php if (empty($ulasan)): ?>
                                    <!-- Card untuk menampilkan pesan "Tidak ada ulasan saat ini" -->
                                    <div class="review-item mb-4">
                                        <div class="card bg-light shadow-sm">
                                            <div class="card-body text-center">
                                                <p class="font-weight-medium">Tidak ada ulasan saat ini</p>
                                                <p class="mb-0">Belum ada ulasan yang diberikan untuk produk ini. Jadilah yang pertama memberi ulasan!</p>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <!-- Menampilkan ulasan jika ada -->
                                    <?php foreach ($ulasan as $review): ?>
                                        <div class="review-item mb-4">
                                            <div class="card shadow-md">
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <div class="d-flex justify-content-between mb-2">
                                                            <h5><strong class="text-md"><?= htmlspecialchars($review['nama_pelanggan']); ?></strong></h5>
                                                            <small class="text-muted"><?= htmlspecialchars($review['created_at']); ?></small>
                                                        </div>
                                                        <div class="rating badge text-bg-dark text-warning">
                                                            <?php for ($i = 0; $i < $review['rating']; $i++) : ?>
                                                                <i class="fas fa-star"></i>
                                                            <?php endfor; ?>
                                                        </div>
                                                    </div>
                                                    <i class="mb-0 text-justify">"<?= htmlspecialchars($review['komentar']); ?>"</i>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php else : ?>
            <section class="product-detail-section p-5">
                <div class="row justify-content-center my-7">
                    <div class="col-12 text-center">
                        <img src="<?= $url ?>/assets/no-found-2.png"
                            alt="Detail produk tidak ditemukan"
                            class="img-fluid"
                            style="height: 300px;">
                        <p class="mt-4 text-muted">
                            Detail produk yang Anda cari tidak ditemukan. Produk mungkin telah dihapus atau tidak tersedia saat ini.
                        </p>
                        <a href="<?= $url ?>/produk" class="btn btn-primary mt-3">
                            Kembali ke Katalog Produk
                        </a>
                    </div>
                </div>
            </section>
        <?php endif; ?>

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
</body>

</html>