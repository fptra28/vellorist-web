<?php
// Konfigurasi database
$servername = "localhost"; // Nama server database Anda
$username = "root";        // Username database Anda
$password = "";            // Password database Anda
$dbname = "db_vellorist";   // Nama database Anda

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// define crucial reusable contant
$base_url = 'http://localhost/vellorist-web/admin';
?>