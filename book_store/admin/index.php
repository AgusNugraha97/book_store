<?php
session_start();
include "../config.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Proses saat form disubmit
if (isset($_POST['submit'])) {
    $nama_produk = $_POST['nama_produk'];
    $kategori_produk = $_POST['kategori_produk'];
    $harga = $_POST['harga'];
    $deskripsi = $_POST['deskripsi'];
    $stok = $_POST['stok'];

    $gambar = $_FILES['gambar']['name'];
    $tmp_name = $_FILES['gambar']['tmp_name'];
    $upload_dir = "uploads/";

    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $gambar_path = $upload_dir . basename($gambar);

    if (move_uploaded_file($tmp_name, $gambar_path)) {
        $query = "INSERT INTO produk (nama_produk, kategori_produk, gambar, harga, deskripsi, stok) 
                  VALUES ('$nama_produk', '$kategori_produk', '$gambar', '$harga', '$deskripsi', '$stok')";
        if (!mysqli_query($conn, $query)) {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Gagal upload gambar.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            margin: 0;
            padding: 20px;
        }

        h1, h2, h3 {
            color: #333;
        }

        .header {
            background: #007BFF;
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between; /* Aligns content to both left and right */
            align-items: center; /* Centers content vertically */
        }

        .logout a {
            background-color: #dc3545;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
        }

        .logout a:hover {
            background-color: #c82333;
        }

        .container {
            max-width: 1200px;
            margin: auto;
        }

        form {
            background: #fff;
            padding: 20px;
            margin-bottom: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        form label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        form input[type="text"],
        form input[type="number"],
        form select,
        form textarea,
        form input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        form button {
            margin-top: 20px;
            background: #007BFF;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }

        form button:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 3px 6px rgba(0,0,0,0.05);
            margin-bottom: 40px;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #007BFF;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        img {
            border-radius: 4px;
        }

        a {
            color: #007BFF;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        select {
            padding: 8px;
        }

        .status-form {
            display: inline-block;
        }
    </style>
</head>

<body>
<div class="container">
    <div class="header">
        <div>
            <h1>Dashboard Admin</h1>
            <h3>Selamat datang, <?php echo $_SESSION['username']; ?>!</h3>
        </div>
        <div class="logout">
            <a href="login.php">Logout</a>
        </div>
    </div>

    <h2>Tambah Produk</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <label>Nama Produk:</label>
        <input type="text" name="nama_produk" required>

        <label>Kategori Produk:</label>
        <select name="kategori_produk" required>
            <option value="Sejarah">Sejarah</option>
            <option value="MaPel">MaPel</option>
            <option value="Novel">Novel</option>
        </select>

        <label>Gambar:</label>
        <input type="file" name="gambar" required>

        <label>Harga:</label>
        <input type="number" name="harga" required>

        <label>Deskripsi:</label>
        <textarea name="deskripsi" rows="4" required></textarea>

        <label>Stok:</label>
        <input type="number" name="stok" required>

        <button type="submit" name="submit">Tambah Produk</button>
    </form>

    <h2>Data Produk</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Kategori</th>
            <th>Gambar</th>
            <th>Harga</th>
            <th>Deskripsi</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
        <?php
        $no = 1;
        $query_produk = "SELECT * FROM produk";
        $result_produk = mysqli_query($conn, $query_produk);
        if (mysqli_num_rows($result_produk) > 0) :
            while ($row_produk = mysqli_fetch_assoc($result_produk)) :
        ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $row_produk['nama_produk'] ?></td>
                <td><?= $row_produk['kategori_produk'] ?></td>
                <td><img src="uploads/<?= $row_produk['gambar'] ?>" width="50"></td>
                <td>Rp <?= number_format($row_produk['harga'], 0, ',', '.') ?></td>
                <td><?= $row_produk['deskripsi'] ?></td>
                <td><?= $row_produk['stok'] ?></td>
                <td>
                    <a href="edit_produk.php?id=<?= $row_produk['id_produk'] ?>">Edit</a> |
                    <a href="hapus_produk.php?id=<?= $row_produk['id_produk'] ?>" onclick="return confirm('Yakin ingin hapus produk ini?')">Hapus</a>
                </td>
            </tr>
        <?php endwhile;
        else :
            echo "<tr><td colspan='8'>Tidak ada data produk.</td></tr>";
        endif;
        ?>
    </table>

    <h2>Data User</h2>
    <table>
        <tr>
            <th>No</th>
            <th>Username</th>
            <th>Nama Lengkap</th>
            <th>No Telp</th>
            <th>Alamat</th>
            <th>Dibuat Pada</th>
        </tr>
        <?php
        $no = 1;
        $query_user = "SELECT * FROM user";
        $result_user = mysqli_query($conn, $query_user);
        if (mysqli_num_rows($result_user) > 0) :
            while ($row_user = mysqli_fetch_assoc($result_user)) :
        ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $row_user['username'] ?></td>
                <td><?= $row_user['nama_lengkap'] ?></td>
                <td><?= $row_user['no_telepon'] ?></td>
                <td><?= $row_user['alamat'] ?></td>
                <td><?= $row_user['created_at'] ?></td>
            </tr>
        <?php endwhile;
        else :
            echo "<tr><td colspan='6'>Tidak ada data user.</td></tr>";
        endif;
        ?>
    </table>

    <h2>Data Pesanan</h2>
    <table>
        <tr>
            <th>ID Pesanan</th>
            <th>Pelanggan</th>
            <th>Total</th>
            <th>Status</th>
            <th>Tanggal</th>
            <th>Aksi</th>
        </tr>
        <?php
        $query_pesanan = "SELECT pesanan.*, user.username 
                          FROM pesanan 
                          JOIN user ON pesanan.id_user = user.id_user
                          ORDER BY pesanan.created_at DESC";
        $result_pesanan = mysqli_query($conn, $query_pesanan);
        if (mysqli_num_rows($result_pesanan) > 0) :
            while ($row = mysqli_fetch_assoc($result_pesanan)) :
        ?>
            <tr>
                <td><?= $row['id_pesanan'] ?></td>
                <td><?= $row['username'] ?></td>
                <td>Rp <?= number_format($row['total'], 0, ',', '.') ?></td>
                <td>
                    <form action="update_status.php" method="POST" class="status-form">
                        <input type="hidden" name="id_pesanan" value="<?= $row['id_pesanan'] ?>">
                        <select name="status" onchange="this.form.submit()">
                            <option value="pending" <?= ($row['status'] == 'pending') ? 'selected' : '' ?>>Pending</option>
                            <option value="diproses" <?= ($row['status'] == 'diproses') ? 'selected' : '' ?>>Diproses</option>
                            <option value="dikirim" <?= ($row['status'] == 'dikirim') ? 'selected' : '' ?>>Dikirim</option>
                            <option value="selesai" <?= ($row['status'] == 'selesai') ? 'selected' : '' ?>>Selesai</option>
                        </select>
                    </form>
                </td>
                <td><?= date('d/m/Y', strtotime($row['created_at'])) ?></td>
                <td><a href="detail_pesanan.php?id=<?= $row['id_pesanan'] ?>">Detail</a></td>
            </tr>
        <?php endwhile;
        else :
            echo "<tr><td colspan='6'>Tidak ada pesanan.</td></tr>";
        endif;
        ?>
    </table>
</div>
</body>
</html>
