<?php
// Memulai session
session_start();

// Mengecek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: ./login.php");
    exit();
}

// Menyertakan file konfigurasi database
include "../../../app/config_query.php";
include "../../../../app/config.php";

// Menangani ID produk yang akan dihapus
$id_produk = $_GET['id'] ?? null; // Mengambil id_produk dari URL
$id_kategori = $_GET['id_kategori'] ?? null; // Mengambil id_kategori dari URL, pastikan ada

if (!$id_produk || !$id_kategori) {
    echo "<p class='text-danger'>ID Produk atau ID Kategori tidak ditemukan. Pastikan URL sudah benar.</p>";
    exit();
}

// Mengambil data produk berdasarkan ID untuk menampilkan gambar lama
$query = "SELECT * FROM produk WHERE id_produk = ?";
$stmt = $conn->prepare($query);

// Cek apakah prepare berhasil
if ($stmt === false) {
    die("Error preparing query: " . $conn->error);
}

$stmt->bind_param("i", $id_produk);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p class='text-danger'>Produk tidak ditemukan.</p>";
    exit();
}

$product = $result->fetch_assoc();
$stmt->close();

// Menyusun path gambar yang akan dihapus
$upload_dir = "../../../../assets/uploads/"; // Pastikan $url sudah terdefinisi
$file_path = $upload_dir . $product['foto_produk'];

// Menghapus gambar jika ada
if ($product['foto_produk'] && file_exists($file_path)) {
    if (unlink($file_path)) {
        echo "<p>Gambar produk berhasil dihapus.</p>";
    } else {
        echo "<p class='text-warning'>Gagal menghapus gambar produk.</p>";
    }
}

// Query untuk menghapus produk dari database
$query = "DELETE FROM produk WHERE id_produk = ?";
$stmt = $conn->prepare($query);

// Cek apakah prepare berhasil
if ($stmt === false) {
    die("Error preparing delete query: " . $conn->error);
}

$stmt->bind_param("i", $id_produk);
if ($stmt->execute()) {
    // Redirect ke halaman produk dengan pesan sukses
    header("Location: $base_url/kategori/produk/index.php?id=" . $id_kategori);
    exit();
} else {
    echo "Terjadi kesalahan saat menghapus data produk: " . $conn->error;
}

// Tutup statement dan koneksi
$stmt->close();
$conn->close();
