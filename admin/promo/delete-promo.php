<?php
// Menyertakan file konfigurasi database
include '../app/config_query.php';

// Mengecek apakah ada parameter 'id' yang diterima melalui URL
if (isset($_GET['id'])) {
    $promo_id = $_GET['id'];

    // Menghapus promo berdasarkan ID
    $query = "DELETE FROM voucher WHERE id_voucher = ?";

    // Menyiapkan query menggunakan prepared statement untuk menghindari SQL injection
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        // Menampilkan error jika query gagal disiapkan
        die('Error preparing statement: ' . $conn->error);
    }

    // Binding parameter ID ke query
    $stmt->bind_param("i", $promo_id);

    // Eksekusi query
    if ($stmt->execute()) {
        // Berhasil menghapus data promo, arahkan pengguna kembali ke halaman promo
        header("Location: $base_url/promo");
        exit();
    } else {
        // Menampilkan pesan error jika gagal menghapus
        die('Error executing query: ' . $conn->error);
    }

    // Menutup prepared statement
    $stmt->close();
} else {
    // Jika ID promo tidak ditemukan di URL
    die("ID promo tidak ditemukan.");
}
