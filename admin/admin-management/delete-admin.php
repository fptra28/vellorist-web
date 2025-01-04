<?php
session_start();

// Memeriksa apakah admin sudah login
if (!isset($_SESSION['username'])) {
    header("Location: ./login.php");
    exit();
}

// Menyertakan file konfigurasi database
include '../app/config_query.php';

// Memeriksa apakah admin adalah Superadmin
if ($_SESSION['role'] !== 'Superadmin') {
    header("Location: $base_url"); // Arahkan ke halaman lain jika bukan Superadmin
    exit();
}

// Memproses penghapusan admin
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitasi ID admin

    // Mencegah Superadmin menghapus dirinya sendiri
    if ($id === intval($_SESSION['id_admin'])) {
        header("Location: $base_url/admin-management?error=Anda tidak dapat menghapus akun Anda sendiri.");
        exit();
    }

    // Menghapus admin berdasarkan ID
    $query = "DELETE FROM tbl_admin WHERE id_admin = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            // Penghapusan berhasil
            header("Location: $base_url/admin-management?success=Admin berhasil dihapus.");
            exit();
        } else {
            // Jika terjadi kesalahan saat menghapus
            header("Location: $base_url/admin-management?error=Terjadi kesalahan saat menghapus admin.");
            exit();
        }

        $stmt->close();
    } else {
        header("Location: $base_url/admin-management?error=Gagal menyiapkan query.");
        exit();
    }
} else {
    // Jika ID tidak disediakan
    header("Location: $base_url/admin-management?error=ID admin tidak valid.");
    exit();
}

// Menutup koneksi database
$conn->close();
