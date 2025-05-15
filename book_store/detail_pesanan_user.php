<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login_user.php");
    exit;
}

$id_pesanan = (int)$_GET['id'];

$query_pesanan = mysqli_query($conn, "
    SELECT * FROM pesanan 
    WHERE id_pesanan = $id_pesanan 
    AND id_user = {$_SESSION['user_id']}
");
$pesanan = mysqli_fetch_assoc($query_pesanan);

if (!$pesanan) {
    die("Pesanan tidak ditemukan.");
}

$query_items = mysqli_query($conn, "
    SELECT detail_pesanan.*, produk.nama_produk, produk.gambar
    FROM detail_pesanan
    JOIN produk ON detail_pesanan.id_produk = produk.id_produk
    WHERE detail_pesanan.id_pesanan = $id_pesanan
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Pesanan #<?= $pesanan['id_pesanan'] ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f9fc;
            padding: 40px;
            color: #333;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 40px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .info-section {
            margin-bottom: 30px;
        }

        .info-section h3 {
            margin-bottom: 15px;
            color: #34495e;
        }

        p {
            margin: 8px 0;
        }

        .status {
            font-weight: bold;
        }

        .status-menunggu-verifikasi {
            color: #e67e22;
        }

        .status-diproses {
            color: #3498db;
        }

        .status-dikirim {
            color: #2ecc71;
        }

        .status-selesai {
            color: #27ae60;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #f1f1f1;
            color: #333;
        }

        img {
            width: 60px;
            border-radius: 6px;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            background-color: #3498db;
            color: white;
            padding: 10px 18px;
            border-radius: 6px;
            transition: 0.3s;
        }

        a:hover {
            background-color: #2980b9;
        }

        @media (max-width: 768px) {
            body {
                padding: 20px;
            }

            table, thead, tbody, th, td, tr {
                display: block;
            }

            td, th {
                text-align: left;
                padding: 10px;
            }

            td::before {
                font-weight: bold;
                display: inline-block;
                width: 120px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Detail Pesanan #<?= $pesanan['id_pesanan'] ?></h1>

        <div class="info-section">
            <h3>Informasi Pesanan</h3>
            <p><strong>Tanggal:</strong> <?= date('d/m/Y H:i', strtotime($pesanan['created_at'])) ?></p>
            <p><strong>Status:</strong> 
                <span class="status status-<?= str_replace('_', '-', $pesanan['status']) ?>">
                    <?php
                    $status = [
                        'menunggu_verifikasi' => 'Menunggu Verifikasi',
                        'diproses' => 'Diproses',
                        'dikirim' => 'Dikirim',
                        'selesai' => 'Selesai'
                    ];
                    echo $status[$pesanan['status']] ?? $pesanan['status'];
                    ?>
                </span>
            </p>
            <p><strong>Total:</strong> Rp <?= number_format($pesanan['total'], 0, ',', '.') ?></p>

        </div>

        <div class="info-section">
            <h3>Item Pesanan</h3>
            <table>
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Gambar</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($item = mysqli_fetch_assoc($query_items)): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['nama_produk']) ?></td>
                            <td><img src="admin/uploads/<?= $item['gambar'] ?>" alt="Produk"></td>
                            <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                            <td><?= $item['jumlah'] ?></td>
                            <td>Rp <?= number_format($item['harga'] * $item['jumlah'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <a href="index.php">&laquo; Kembali ke Beranda</a>
    </div>
</body>
</html>
