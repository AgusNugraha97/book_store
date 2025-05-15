<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id']) || !isset($_GET['id_pesanan'])) {
    header("Location: index.php");
    exit;
}

$id_pesanan = (int)$_GET['id_pesanan'];
$query = mysqli_query($conn, "
    SELECT * FROM pesanan 
    WHERE id_pesanan = $id_pesanan 
    AND id_user = {$_SESSION['user_id']}
");
$pesanan = mysqli_fetch_assoc($query);

if (!$pesanan) {
    die("Pesanan tidak ditemukan.");
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pesanan</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 60px auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #2c3e50;
            text-align: center;
        }

        .info p {
            font-size: 16px;
            color: #34495e;
            margin: 8px 0;
        }

        .payment-instructions {
            background-color: #f9f9f9;
            border-left: 4px solid #3498db;
            padding: 15px 20px;
            margin-top: 25px;
            border-radius: 8px;
        }

        .payment-instructions h3 {
            margin-top: 0;
            color: #2980b9;
        }

        a.button {
            display: inline-block;
            text-align: center;
            padding: 12px 20px;
            margin-top: 30px;
            background-color: #3498db;
            color: #ffffff;
            text-decoration: none;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        a.button:hover {
            background-color: #2980b9;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>✅ Pesanan Berhasil Dibuat!</h2>
        <div class="info">
            <p><strong>ID Pesanan:</strong> <?php echo $pesanan['id_pesanan']; ?></p>
            <p><strong>Total:</strong> Rp <?php echo number_format($pesanan['total'], 0, ',', '.'); ?></p>
            <p><strong>Status:</strong> <?php echo ucfirst(str_replace('_', ' ', $pesanan['status'])); ?></p>
            <p><strong>Metode Pembayaran:</strong> <?php echo strtoupper($pesanan['metode_pembayaran']); ?></p>
        </div>

        <?php if ($pesanan['metode_pembayaran'] == 'transfer_bank'): ?>
            <div class="payment-instructions">
                <h3>💳 Instruksi Pembayaran</h3>
                <p>Transfer ke: <strong>BANK ABC (1234567890)</strong></p>
                <p>Jumlah: <strong>Rp <?php echo number_format($pesanan['total'], 0, ',', '.'); ?></strong></p>
                <p>Kode Referensi: <strong>ORDER-<?php echo $pesanan['id_pesanan']; ?></strong></p>
            </div>
        <?php endif; ?>

        <div style="text-align: center;">
            <a href="index.php" class="button">🏠 Kembali ke Beranda</a>
        </div>
    </div>
</body>

</html>
