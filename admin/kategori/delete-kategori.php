<?php
// Memulai session
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ./login.php");
    exit();
}

// Menyertakan file konfigurasi database
include '../app/config_query.php';

// Mendapatkan ID kategori dari URL
$id_kategori = $_GET['id'] ?? null;

// Jika ID tidak ada, redirect ke halaman kategori
if (!$id_kategori) {
    header("Location: $base_url/kategori");
    exit();
}

// Mulai transaksi untuk memastikan kedua query berjalan bersama
$conn->begin_transaction();

try {
    // Query untuk menghapus produk yang terkait dengan kategori
    $deleteProductsQuery = "DELETE FROM produk WHERE id_kategori = ?";
    $stmt = $conn->prepare($deleteProductsQuery);

    if ($stmt === false) {
        throw new Exception('Error preparing statement: ' . $conn->error);
    }

    // Binding parameter dan eksekusi query untuk menghapus produk
    $stmt->bind_param("i", $id_kategori);
    $stmt->execute();
    $stmt->close();

    // Query untuk menghapus kategori berdasarkan ID
    $deleteCategoryQuery = "DELETE FROM kategori_produk WHERE id_kategori = ?";
    $stmt = $conn->prepare($deleteCategoryQuery);

    if ($stmt === false) {
        throw new Exception('Error preparing statement: ' . $conn->error);
    }

    // Binding parameter dan eksekusi query untuk menghapus kategori
    $stmt->bind_param("i", $id_kategori);
    $stmt->execute();
    $stmt->close();

    // Commit transaksi
    $conn->commit();

    // Jika berhasil, redirect ke halaman kategori
    header("Location: $base_url/kategori?message=Kategori dan produk berhasil dihapus");
    exit();
} catch (Exception $e) {
    // Rollback transaksi jika ada kesalahan
    $conn->rollback();

    // Menampilkan pesan error
    echo "Terjadi kesalahan saat menghapus kategori dan produk: " . $e->getMessage();
}

// Menutup koneksi database
$conn->close();
