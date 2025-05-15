<?php
session_start();
include "config.php";

// Redirect jika belum login
if (!isset($_SESSION['user_id'])) {
    header("Location: login_user.php");
    exit;
}

// Ambil data user
$user_query = mysqli_query($conn, "SELECT * FROM user WHERE id_user = {$_SESSION['user_id']}");
$user_data = mysqli_fetch_assoc($user_query);

// Ambil data keranjang user
$cart_query = mysqli_query($conn, "
    SELECT produk.*, keranjang.jumlah 
    FROM keranjang 
    JOIN produk ON keranjang.id_produk = produk.id_produk 
    WHERE keranjang.id_user = {$_SESSION['user_id']}
");

// Hitung total harga
$total = 0;
$items = [];
while ($row = mysqli_fetch_assoc($cart_query)) {
    $total += $row['harga'] * $row['jumlah'];
    $items[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f4fb;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 850px;
            margin: 40px auto;
            background-color: #ffffff;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        h2,
        h3 {
            color: #003366;
            text-align: center;
        }

        .order-summary {
            background: #e9f2ff;
            padding: 20px;
            border-left: 5px solid #007BFF;
            margin-bottom: 30px;
            border-radius: 8px;
        }

        .order-summary p {
            margin: 8px 0;
            color: #333;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            font-weight: 600;
            display: block;
            margin-bottom: 6px;
        }

        input[type="text"],
        input[type="tel"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
        }

        .bank-info {
            background: #f0f8ff;
            padding: 20px;
            border-radius: 8px;
            margin: 30px 0;
            border-left: 5px solid #0056b3;
        }

        .bank-info p {
            margin: 5px 0;
            color: #003366;
        }

        .btn-submit {
            display: inline-block;
            width: 100%;
            padding: 14px;
            background-color: #007BFF;
            color: #ffffff;
            font-weight: bold;
            text-align: center;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn-submit:hover {
            background-color: #0056b3;
        }

        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>🧾 Checkout</h2>

        <!-- Ringkasan Pesanan -->
        <div class="order-summary">
            <h3>Ringkasan Pesanan</h3>
            <?php foreach ($items as $item): ?>
                <p><?= htmlspecialchars($item['nama_produk']) ?> (<?= $item['jumlah'] ?>x) -
                    Rp <?= number_format($item['harga'] * $item['jumlah'], 0, ',', '.') ?></p>
            <?php endforeach; ?>
            <p><strong>Total: Rp <?= number_format($total, 0, ',', '.') ?></strong></p>
        </div>

        <!-- Formulir Checkout -->
        <form action="proses_checkout.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" value="<?= htmlspecialchars($user_data['nama_lengkap'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label>Nomor Telepon</label>
                <input type="tel" name="no_telepon" value="<?= htmlspecialchars($user_data['no_telepon'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label>Alamat Lengkap</label>
                <textarea name="alamat" rows="4" required><?= htmlspecialchars($user_data['alamat'] ?? '') ?></textarea>
            </div>

            <input type="hidden" name="metode_pembayaran" value="transfer_bank">

            <!-- Instruksi Pembayaran -->
            <div class="bank-info">
                <h3>Instruksi Pembayaran</h3>
                <p><strong>Bank: ABC</strong></p>
                <p>Nomor Rekening: <strong>1234 5678 9012</strong></p>
                <p>Atas Nama: <strong>Nama Toko Anda</strong></p>
                <p>Total Pembayaran: <strong>Rp <?= number_format($total, 0, ',', '.') ?></strong></p>
                <p>Kode Referensi: <strong>ORDER-<?= time() ?></strong></p>
            </div>

            <!-- Upload Bukti -->
            <div class="form-group">
                <label>Upload Bukti Transfer (JPG/PNG, max 2MB)</label>
                <input type="file" name="bukti_transfer" accept="image/jpeg, image/png" required>
            </div>

            <button type="submit" class="btn-submit">Konfirmasi & Pesan Sekarang</button>
        </form>
    </div>

</body>

</html>
