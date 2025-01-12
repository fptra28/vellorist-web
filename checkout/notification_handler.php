<?php
require_once dirname(__FILE__) . '/midtrans-php-master/Midtrans.php'; // Path library Midtrans
include '../app/config.php'; // Koneksi database

// Set konfigurasi Midtrans
\Midtrans\Config::$serverKey = 'SB-Mid-server-rfayL0bZCkHAGh6-1qVQdpzQ';
\Midtrans\Config::$isProduction = false;
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

// Ambil data notifikasi dari Midtrans
$input = file_get_contents('php://input');
$notification = json_decode($input, true);

if (!$notification) {
    http_response_code(400); // Bad Request jika data kosong
    echo "Invalid notification data";
    exit();
}

try {
    $notif = new \Midtrans\Notification();

    $transaction_status = $notif->transaction_status;
    $order_id = $notif->order_id;
    $payment_type = $notif->payment_type;
    $fraud_status = $notif->fraud_status;

    // Logika untuk menangani status transaksi
    if ($transaction_status == 'capture') {
        if ($fraud_status == 'accept') {
            // Transaksi berhasil
            $status_pemesanan = 'Dibayar';
        } else {
            // Transaksi mencurigakan
            $status_pemesanan = 'Fraud';
        }
    } else if ($transaction_status == 'settlement') {
        // Pembayaran selesai
        $status_pemesanan = 'Dibayar';
    } else if ($transaction_status == 'pending') {
        // Menunggu pembayaran
        $status_pemesanan = 'Menunggu Pembayaran';
    } else if ($transaction_status == 'deny') {
        // Pembayaran ditolak
        $status_pemesanan = 'Ditolak';
    } else if ($transaction_status == 'expire') {
        // Pembayaran kadaluarsa
        $status_pemesanan = 'Kadaluarsa';
    } else if ($transaction_status == 'cancel') {
        // Pembayaran dibatalkan
        $status_pemesanan = 'Dibatalkan';
    } else {
        // Status tidak dikenal
        $status_pemesanan = 'Tidak Dikenal';
    }

    // Update status pemesanan di database
    $stmt = $conn->prepare("UPDATE pesanan SET status_pemesanan = ?, metode_pembayaran = ? WHERE nomor_pesanan = ?");
    $stmt->bind_param("sss", $status_pemesanan, $payment_type, $order_id);

    if ($stmt->execute()) {
        http_response_code(200); // OK
        echo "Status updated successfully";
    } else {
        http_response_code(500); // Internal Server Error
        echo "Failed to update status: " . $conn->error;
    }
    $stmt->close();
} catch (Exception $e) {
    http_response_code(500); // Internal Server Error
    echo "Notification handling error: " . $e->getMessage();
}

$conn->close();
