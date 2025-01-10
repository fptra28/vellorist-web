<?php
include "../app/config.php";
include "../app/footer.php";

// Fungsi untuk mengambil produk dengan pagination dan filter kategori
function getProductsWithPagination($conn, $page = 1, $perPage = 30, $kategoriId = '')
{
    // Hitung offset berdasarkan halaman saat ini
    $offset = ($page - 1) * $perPage;

    // Query dasar
    $query = "
        SELECT 
            produk.*, 
            kategori_produk.nama_kategori 
        FROM produk
        JOIN kategori_produk ON produk.id_kategori = kategori_produk.id_kategori
    ";

    // Jika ada filter kategori, tambahkan ke query
    if ($kategoriId) {
        $query .= " WHERE produk.id_kategori = ?";
    }

    // Tambahkan limit untuk pagination
    $query .= " LIMIT ?, ?";

    // Persiapkan statement dan bind parameter
    $stmt = $conn->prepare($query);
    if ($kategoriId) {
        $stmt->bind_param("iii", $kategoriId, $offset, $perPage);
    } else {
        $stmt->bind_param("ii", $offset, $perPage);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    return [];
}

// Ambil kategori dari parameter GET
$kategoriId = isset($_GET['kategori']) ? (int)$_GET['kategori'] : '';

// Hitung jumlah total produk untuk pagination
$totalQuery = "SELECT COUNT(*) as total FROM produk";
if ($kategoriId) {
    $totalQuery .= " WHERE id_kategori = ?";
}

// Persiapkan statement dan bind parameter
$totalStmt = $conn->prepare($totalQuery);
if ($kategoriId) {
    $totalStmt->bind_param("i", $kategoriId);
}

$totalStmt->execute();
$totalResult = $totalStmt->get_result();
$totalRow = $totalResult->fetch_assoc();
$totalProducts = $totalRow['total'];

// Tentukan jumlah halaman
$perPage = 30;
$totalPages = ceil($totalProducts / $perPage);

// Tentukan halaman saat ini
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Ambil produk dengan filter kategori dan pagination
$produkList = getProductsWithPagination($conn, $page, $perPage, $kategoriId);
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

    <div class="content-wrapper">
        <!-- Hero Section -->
        <section class="pb-6 pt-7" id="hero-produk">
            <div class="container">
                <div class="row align-items-center">
                    <h3 class="fw-bold text-light text-center">
                        <?php
                        if ($kategoriId) {
                            // Ambil nama kategori berdasarkan kategori yang dipilih
                            $kategoriQuery = "SELECT nama_kategori FROM kategori_produk WHERE id_kategori = ?";
                            $kategoriStmt = $conn->prepare($kategoriQuery);
                            $kategoriStmt->bind_param("i", $kategoriId);
                            $kategoriStmt->execute();
                            $kategoriResult = $kategoriStmt->get_result();
                            $kategori = $kategoriResult->fetch_assoc();
                            echo htmlspecialchars($kategori['nama_kategori']);
                        } else {
                            echo "Produk Kami";
                        }
                        ?>
                    </h3>
                </div>
            </div>
        </section>

        <!-- Produk Section  -->
        <section class="py-5" id="produk">
            <div class="container mb-5">
                <form method="GET" action="<?= $_SERVER['PHP_SELF'] ?>" id="filterForm">
                    <div class="row justify-content-end">
                        <!-- Menempatkan form select di kanan -->
                        <div class="col-auto">
                            <div class="d-flex align-items-center">
                                <select name="kategori" id="kategori" class="form-select w-100 w-lg-100 shadow" onchange="this.form.submit()">
                                    <option value="">Semua Kategori</option>
                                    <?php
                                    // Ambil daftar kategori dari database
                                    $kategoriQuery = "SELECT * FROM kategori_produk";
                                    $kategoriResult = $conn->query($kategoriQuery);

                                    if ($kategoriResult && $kategoriResult->num_rows > 0) {
                                        while ($kategori = $kategoriResult->fetch_assoc()) {
                                            echo "<option value='" . $kategori['id_kategori'] . "' " .
                                                ($kategori['id_kategori'] == $kategoriId ? 'selected' : '') .
                                                ">" . htmlspecialchars($kategori['nama_kategori']) . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="container">
                <?php if (!empty($produkList)) : ?>
                    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-6 g-2">
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
                                        <a href="<?= $url ?>/produk/<?= $produk['id_produk'] ?>"
                                            class="btn btn-secondary fw-bold mt-auto">
                                            Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <div class="row justify-content-center my-7">
                        <div class="col-12 text-center">
                            <img src="<?= $url ?>/assets/no-found.png" alt="Data tidak ditemukan" class="img-fluid">
                            <p class=" mt-3 text-muted">Maaf, tidak ada kupon yang tersedia saat ini.</p>
                            <a href="<?= $url ?>" class="btn btn-primary mt-3">Kembali ke Beranda</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- Pagination Section -->
        <section id="pagination" class="my-5">
            <div class="container d-flex justify-content-center">
                <ul class="pagination">
                    <!-- Tombol Previous -->
                    <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= $_SERVER['PHP_SELF'] ?>?page=<?= $page - 1 ?><?= $kategoriId ? '&kategori=' . $kategoriId : '' ?>">Prev</a>
                    </li>

                    <!-- Nomor Halaman -->
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link" href="<?= $_SERVER['PHP_SELF'] ?>?page=<?= $i ?><?= $kategoriId ? '&kategori=' . $kategoriId : '' ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <!-- Tombol Next -->
                    <li class="page-item <?= $page == $totalPages ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= $_SERVER['PHP_SELF'] ?>?page=<?= $page + 1 ?><?= $kategoriId ? '&kategori=' . $kategoriId : '' ?>">Next</a>
                    </li>
                </ul>
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