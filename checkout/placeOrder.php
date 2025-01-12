<?php
require_once dirname(__FILE__) . '/midtrans-php-master/Midtrans.php'; // Pastikan pathnya benar
include '../app/config.php';

// Set Merchant Server Key untuk Midtrans
\Midtrans\Config::$serverKey = 'SB-Mid-server-rfayL0bZCkHAGh6-1qVQdpzQ';
\Midtrans\Config::$isProduction = false; // Mode development/sandbox
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

// Ambil data dari form
$name = $_POST['name'] ?? 'Guest';
$email = $_POST['email'] ?? 'guest@example.com';
$phone = $_POST['phone'] ?? '08123456789';
$address = $_POST['address'] ?? 'Alamat tidak diisi';
$keterangan = $_POST['keterangan'] ?? '-';
$produk = [
    'id_produk' => $_POST['idproduk'],
    'harga_produk' => $_POST['produkHarga'],
    'nama_produk' => $_POST['produkName']
];
$biaya_pengiriman = $_POST['shippingPrice'] ?? 0;
$total_harga = $produk['harga_produk'] + $biaya_pengiriman;
$order_id = 'V-' . uniqid(); // ID unik untuk pesanan

// Data transaksi untuk Midtrans
$transaction_details = [
    'order_id' => $order_id,
    'gross_amount' => $total_harga, // Total harga
];

// Data item detail untuk Midtrans
$item_details = [
    [
        'id' => $produk['id_produk'],
        'price' => $produk['harga_produk'],
        'quantity' => 1,
        'name' => $produk['nama_produk'],
    ],
    [
        'id' => 'shipping_fee',
        'price' => $biaya_pengiriman,
        'quantity' => 1,
        'name' => 'Biaya Pengiriman',
    ],
];

// Data pelanggan untuk Midtrans
$customer_details = [
    'first_name' => $name,
    'email' => $email,
    'phone' => $phone,
    'address' => $address,
    'shipping_address' => [
        'first_name' => $name,
        'address' => $address,
    ],
];

// Data lengkap untuk Snap API
$params = [
    'transaction_details' => $transaction_details,
    'item_details' => $item_details,
    'customer_details' => $customer_details,
];

// Mendapatkan Snap Token dari Midtrans API
try {
    $snapToken = \Midtrans\Snap::getSnapToken($params);

    // Simpan data pelanggan ke tabel pelanggan
    $sql_pelanggan = "INSERT INTO pelanggan (nama_pelanggan, alamat_pengiriman, nomor_telepon, email_pelanggan) 
                      VALUES ('$name', '$address', '$phone', '$email')";
    if ($conn->query($sql_pelanggan) === TRUE) {
        $id_pelanggan = $conn->insert_id;  // Ambil ID pelanggan yang baru saja disimpan

        // Simpan data pesanan ke tabel pesanan
        $sql_pesanan = "INSERT INTO pesanan (id_pelanggan, nomor_pesanan, total_harga, status_pemesanan, metode_pembayaran, keterangan, id_produk, nama_kurir) 
                VALUES ('$id_pelanggan', '$order_id', '$total_harga', 'Belum Dibayar', 'Midtrans', '$keterangan', '{$produk['id_produk']}', '-')";

        if ($conn->query($sql_pesanan) === TRUE) {
            // Data pesanan berhasil disimpan
            echo json_encode(['snapToken' => $snapToken]);
        } else {
            echo json_encode(['error' => 'Error saving order data: ' . $conn->error]);
        }
    } else {
        echo json_encode(['error' => 'Error saving customer data: ' . $conn->error]);
    }
} catch (Exception $e) {
    // Menangani error jika terjadi kesalahan dalam pengambilan Snap Token
    echo json_encode(['error' => $e->getMessage()]);
}

$conn->close();
