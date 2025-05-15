<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login_user.php");
    exit;
}

$query = mysqli_query($conn, "
    SELECT produk.nama_produk, produk.harga, keranjang.jumlah 
    FROM keranjang 
    JOIN produk ON keranjang.id_produk = produk.id_produk 
    WHERE keranjang.id_user = {$_SESSION['user_id']}
");

$total = 0;
$item_count = mysqli_num_rows($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keranjang Belanja</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #e9f1fb; /* Warna latar belakang biru muda */
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #007bff; /* Warna biru untuk judul */
            margin-bottom: 30px;
        }

        .item {
            border-bottom: 1px solid #eee;
            padding: 15px 0;
        }

        .item h3 {
            margin: 0;
            font-size: 18px;
            color: #003366; /* Warna biru tua untuk nama produk */
        }

        .item p {
            margin: 5px 0;
            color: #666;
        }

        .total {
            text-align: right;
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
            color: #003366; /* Warna biru tua untuk total */
        }

        .checkout-btn {
            display: block;
            width: max-content;
            margin: 30px auto 0;
            padding: 12px 25px;
            background-color: #007bff; /* Warna biru untuk tombol checkout */
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            transition: background 0.3s;
        }

        .checkout-btn:hover {
            background-color: #0056b3; /* Warna biru gelap saat hover */
        }

        @media (max-width: 600px) {
            .container {
                margin: 20px;
                padding: 20px;
            }

            .item h3 {
                font-size: 16px;
            }

            .total {
                font-size: 16px;
            }
        }
    </style>
    <script>
        function validateCheckout() {
            <?php if ($item_count == 0): ?>
                alert("Keranjang kosong! Tambahkan produk terlebih dahulu.");
                window.location.href = "index.php";
                return false;
            <?php else: ?>
                return true;
            <?php endif; ?>
        }
    </script>
</head>

<body>
    <div class="container">
        <h2>🛒 Keranjang Belanja</h2>
        <?php while ($row = mysqli_fetch_assoc($query)): ?>
            <div class="item">
                <h3><?php echo $row['nama_produk']; ?></h3>
                <p>Harga: Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></p>
                <p>Jumlah: <?php echo $row['jumlah']; ?></p>
            </div>
            <?php $total += $row['harga'] * $row['jumlah']; ?>
        <?php endwhile; ?>

        <div class="total">
            Total: Rp <?php echo number_format($total, 0, ',', '.'); ?>
        </div>

        <a href="checkout.php" onclick="return validateCheckout()" class="checkout-btn">Checkout Sekarang</a>
    </div>
</body>
</html>
