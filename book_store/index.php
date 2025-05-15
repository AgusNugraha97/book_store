<?php
session_start();
include "config.php";

// Ambil data produk
$query = "SELECT * FROM produk";
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
if (!empty($keyword)) {
    $query .= " WHERE nama_produk LIKE '%$keyword%' OR kategori_produk LIKE '%$keyword%'";
}
$result = mysqli_query($conn, $query);

// Ambil riwayat pesanan
$riwayat_pesanan = [];
if (isset($_SESSION['user_id'])) {
    $query_pesanan = mysqli_query($conn, "
        SELECT * FROM pesanan 
        WHERE id_user = {$_SESSION['user_id']}
        ORDER BY created_at DESC
    ");
    while ($row = mysqli_fetch_assoc($query_pesanan)) {
        $riwayat_pesanan[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk - Toko Buku Agus</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-image: url('images/book-background.jpg');
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
            color: #333;
        }

        nav {
            background: linear-gradient(90deg, #1565c0, #1e88e5);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            position: sticky;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        nav.shrink {
            padding: 8px 30px;
            background: linear-gradient(90deg, #0d47a1, #1565c0);
        }

        .nav-left,
        .nav-right {
            display: flex;
            align-items: center;
            gap: 25px;
        }

        nav a,
        nav span {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        nav a:hover {
            color: #ffeb3b;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 25px;
            margin: 0;
            padding: 0;
        }

        .logo-kiri {
            height: 45px;
            width: auto;
            margin-right: 15px;
        }

        .nav-right i {
            margin-right: 5px;
        }

        .container {
            max-width: 1000px;
            margin: 30px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #1976d2;
            text-align: center;
        }

        form {
            text-align: center;
            margin: 20px 0;
        }

        input[type="text"] {
            padding: 8px;
            width: 250px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        button {
            padding: 8px 16px;
            background-color: #1976d2;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }

        button:hover {
            background-color: #1565c0;
            transform: scale(1.05);
        }

        .produk-wrapper {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .produk-card {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            background-color: white;
            text-align: center;
            animation: fadeInUp 0.6s ease forwards;
            opacity: 0;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .produk-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .produk-card img {
            max-width: 100%;
            height: auto;
        }

        .riwayat-pesanan {
            margin-top: 50px;
        }

        .riwayat-pesanan h2 {
            margin-bottom: 15px;
        }

        .status-pending { color: #f39c12; }
        .status-diproses { color: #3498db; }
        .status-dikirim { color: #2ecc71; }
        .status-selesai { color: #27ae60; }
    </style>
</head>
<body>

<nav id="navbar">
    <div class="nav-left">
        <a href="index.php"><img src="logobuku.png" alt="Logo" class="logo-kiri"></a>
        <a href="index.php" class="logo"><strong>TOKO BUKU AGUS</strong></a>
        <ul>
            <li><a href="index.php"><i class="fas fa-book"></i> Produk</a></li>
            <li><a href="about.php"><i class="fas fa-info-circle"></i> Tentang Kami</a></li>
            <li><a href="contact.php"><i class="fas fa-envelope"></i> Kontak</a></li>
        </ul>
    </div>
    <div class="nav-right">
        <?php if (isset($_SESSION['user'])): ?>
            <span><i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['user']); ?></span> |
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a> |
            <a href="keranjang.php"><i class="fas fa-shopping-cart"></i> Keranjang</a>
        <?php else: ?>
            <a href="login_user.php"><i class="fas fa-sign-in-alt"></i> Login User</a> |
            <a href="login_user.php"><i class="fas fa-shopping-cart"></i> Keranjang</a>
        <?php endif; ?>
    </div>
</nav>

<div class="container">
    <h2>DAFTAR PRODUK</h2>
    <form method="GET">
        <input type="text" name="keyword" placeholder="Cari produk atau kategori..." value="<?php echo htmlspecialchars($keyword); ?>">
        
        <select name="kategori" style="padding: 8px; width: 250px; border-radius: 4px; border: 1px solid #ccc;">
            <option value="">Pilih Kategori</option>
            <!-- Menambahkan pilihan kategori secara dinamis -->
            <?php
            $kategori_query = mysqli_query($conn, "SELECT DISTINCT kategori_produk FROM produk");
            while ($kategori = mysqli_fetch_assoc($kategori_query)) {
                $selected = isset($_GET['kategori']) && $_GET['kategori'] == $kategori['kategori_produk'] ? 'selected' : '';
                echo "<option value='" . htmlspecialchars($kategori['kategori_produk']) . "' $selected>" . htmlspecialchars($kategori['kategori_produk']) . "</option>";
            }
            ?>
        </select>
        
        <button type="submit">Cari</button>
    </form>

    <div class="produk-wrapper">
        <?php 
        // Menambahkan filter kategori dalam query produk
        $query = "SELECT * FROM produk";
        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
        $kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';

        $conditions = [];
        if (!empty($keyword)) {
            $conditions[] = "(nama_produk LIKE '%$keyword%' OR kategori_produk LIKE '%$keyword%')";
        }
        if (!empty($kategori)) {
            $conditions[] = "kategori_produk = '$kategori'";
        }
        
        if (count($conditions) > 0) {
            $query .= " WHERE " . implode(' AND ', $conditions);
        }

        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0): 
            $delay = 0.1;
            while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="produk-card" style="animation-delay: <?= $delay; ?>s;">
                    <img src="admin/uploads/<?php echo htmlspecialchars($row['gambar']); ?>" alt="<?php echo htmlspecialchars($row['nama_produk']); ?>">
                    <h3><?php echo htmlspecialchars($row['nama_produk']); ?></h3>
                    <p>Kategori: <?php echo htmlspecialchars($row['kategori_produk']); ?></p>
                    <p>Harga: Rp<?php echo number_format($row['harga'], 0, ',', '.'); ?></p>
                    <p>Stok: <?php echo $row['stok']; ?></p>
                    <button onclick="addToCart(<?php echo $row['id_produk']; ?>)">Add to Cart</button>
                </div>
            <?php $delay += 0.1; endwhile; ?>
        <?php else: ?>
            <p>Tidak ada produk ditemukan.</p>
        <?php endif; ?>
    </div>
</div>


    <div class="produk-wrapper">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php 
            $delay = 0.1;
            while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="produk-card" style="animation-delay: <?= $delay; ?>s;">
                    <img src="admin/uploads/<?php echo htmlspecialchars($row['gambar']); ?>" alt="<?php echo htmlspecialchars($row['nama_produk']); ?>">
                    <h3><?php echo htmlspecialchars($row['nama_produk']); ?></h3>
                    <p>Kategori: <?php echo htmlspecialchars($row['kategori_produk']); ?></p>
                    <p>Harga: Rp<?php echo number_format($row['harga'], 0, ',', '.'); ?></p>
                    <p>Stok: <?php echo $row['stok']; ?></p>
                    <button onclick="addToCart(<?php echo $row['id_produk']; ?>)">Add to Cart</button>
                </div>
            <?php $delay += 0.1; endwhile; ?>
        <?php else: ?>
            <p>Tidak ada produk ditemukan.</p>
        <?php endif; ?>
    </div>
    <!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Pemesanan</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f6fa;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 850px;
            margin: 40px auto;
            padding: 20px;
        }

        .riwayat-pesanan {
            background-color: #ffffff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
        }

        .riwayat-pesanan h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            overflow: hidden;
        }

        thead tr {
            background-color: #1976d2;
            color: #fff;
        }

        th, td {
            padding: 12px 14px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .btn-detail {
            background-color: #1976d2;
            color: #fff;
            padding: 6px 12px;
            border-radius: 5px;
            text-decoration: none;
            transition: 0.3s;
        }

        .btn-detail:hover {
            background-color: #125ea8;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
            display: inline-block;
        }

        .pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .diproses {
            background-color: #cce5ff;
            color: #004085;
        }

        .dikirim {
            background-color: #d4edda;
            color: #155724;
        }

        .selesai {
            background-color: #e2d5f6;
            color: #4b0082;
        }

        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            th, td {
                font-size: 13px;
                padding: 10px 8px;
            }

            .btn-detail {
                font-size: 12px;
                padding: 5px 10px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <?php if (!empty($riwayat_pesanan)): ?>
        <div class="riwayat-pesanan">
            <h2>🧾 Riwayat Pemesanan Anda</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($riwayat_pesanan as $pesanan): ?>
                        <tr>
                            <td><?php echo $pesanan['id_pesanan']; ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($pesanan['created_at'])); ?></td>
                            <td>Rp <?php echo number_format($pesanan['total'], 0, ',', '.'); ?></td>
                            <td>
                                <span class="badge <?php echo $pesanan['status']; ?>">
                                    <?php
                                    $status_map = [
                                        'pending' => 'Pending',
                                        'diproses' => 'Diproses',
                                        'dikirim' => 'Dikirim',
                                        'selesai' => 'Selesai'
                                    ];
                                    echo $status_map[$pesanan['status']] ?? $pesanan['status'];
                                    ?>
                                </span>
                            </td>
                            <td><a href="detail_pesanan_user.php?id=<?php echo $pesanan['id_pesanan']; ?>" class="btn-detail">Detail</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Pemesanan</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
        }

        .riwayat-pesanan {
            background: #ffffff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .riwayat-pesanan h2 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 10px;
            overflow: hidden;
        }

        thead {
            background-color: #1976d2;
            color: white;
        }

        th, td {
            padding: 14px 16px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        tr:nth-child(even) {
            background-color: #f4f6f8;
        }

        tr:hover {
            background-color: #e1f5fe;
        }

        .status-pending {
            color: #fbc02d;
            font-weight: bold;
        }

        .status-diproses {
            color: #0288d1;
            font-weight: bold;
        }

        .status-dikirim {
            color: #43a047;
            font-weight: bold;
        }

        .status-selesai {
            color: #9c27b0;
            font-weight: bold;
        }

        .btn-detail {
            text-decoration: none;
            color: white;
            background-color: #1976d2;
            padding: 8px 14px;
            border-radius: 6px;
            transition: background-color 0.3s ease;
            font-weight: 500;
        }

        .btn-detail:hover {
            background-color: #0d47a1;
        }

        @media (max-width: 768px) {
            .riwayat-pesanan {
                padding: 20px 10px;
            }

            th, td {
                font-size: 14px;
                padding: 10px;
            }

            .btn-detail {
                padding: 6px 10px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>


</div>

<script>
    function addToCart(productId) {
        <?php if (!isset($_SESSION['user_id'])): ?>
            alert("Silakan login terlebih dahulu!");
            window.location.href = "login_user.php";
        <?php else: ?>
            fetch("add_to_cart.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "id_produk=" + productId
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.status === "success") {
                    window.location.reload();
                }
            });
        <?php endif; ?>
    }

    window.addEventListener('scroll', function() {
        const nav = document.getElementById('navbar');
        if (window.scrollY > 20) {
            nav?.classList.add('shrink');
        } else {
            nav?.classList.remove('shrink');
        }
    });
</script>

</body>
</html>

<footer style="margin-top: 60px; background-color: #1976d2; color: white; text-align: center; padding: 20px;">
    <div style="margin-bottom: 20px;">
        <h3 style="color: #fff;">Lokasi Toko Kami</h3>
        <iframe 
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.234851963479!2d106.90686401070559!3d-6.232740761011244!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f34afbd53857%3A0x84d129245f0e703b!2sPusat%20Pelatihan%20dan%20Pengembangan%20Pendidikan%20(P4)%20Kota%20Administrasi%20Jakarta%20Timur!5e0!3m2!1sid!2sid!4v1746753209944!5m2!1sid!2sid" 
            width="100%" 
            height="300" 
            style="border:0; border-radius: 8px; margin-top: 10px;" 
            allowfullscreen="" 
            loading="lazy" 
            referrerpolicy="no-referrer-when-downgrade">
        </iframe>
    </div>
    <p>&copy; <?php echo date("Y"); ?> Toko Buku Agus. All rights reserved.</p>
    <p><a href="../book_store/admin/login.php" style="color: #ffeb3b; text-decoration: underline;">Login sebagai Admin</a></p>
</footer>


</body>
</html>
