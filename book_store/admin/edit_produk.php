<?php
include "../config.php";

if (!isset($_GET['id'])) {
    echo "ID tidak ditemukan!";
    exit;
}

$id = $_GET['id'];
$query = "SELECT * FROM produk WHERE id_produk = $id";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "Produk tidak ditemukan!";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Produk</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f2f4f8;
            padding: 0;
            margin: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #ffffff;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #2c3e50;
        }

        label {
            font-weight: bold;
            color: #34495e;
        }

        input[type="text"],
        input[type="number"],
        select,
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 14px;
        }

        textarea {
            resize: vertical;
            height: 80px;
        }

        img {
            margin-top: 6px;
            max-width: 100px;
            border-radius: 5px;
        }

        button {
            width: 100%;
            background-color: #2980b9;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #1f6691;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>🛠️ Edit Produk</h2>

        <form action="proses_edit.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_produk" value="<?php echo $data['id_produk']; ?>">
            <input type="hidden" name="gambar_lama" value="<?php echo $data['gambar']; ?>">

            <label for="nama_produk">Nama Produk:</label>
            <input type="text" name="nama_produk" id="nama_produk" value="<?php echo $data['nama_produk']; ?>" required>

            <label for="kategori_produk">Kategori:</label>
            <select name="kategori_produk" id="kategori_produk" required>
                <option value="Elektronik" <?php echo ($data['kategori_produk'] == 'Elektronik') ? 'selected' : ''; ?>>Elektronik</option>
                <option value="Pakaian" <?php echo ($data['kategori_produk'] == 'Pakaian') ? 'selected' : ''; ?>>Pakaian</option>
                <option value="Makanan" <?php echo ($data['kategori_produk'] == 'Makanan') ? 'selected' : ''; ?>>Makanan</option>
                <option value="Aksesoris" <?php echo ($data['kategori_produk'] == 'Aksesoris') ? 'selected' : ''; ?>>Aksesoris</option>
            </select>

            <label for="harga">Harga:</label>
            <input type="number" name="harga" id="harga" value="<?php echo $data['harga']; ?>" required>

            <label for="deskripsi">Deskripsi:</label>
            <textarea name="deskripsi" id="deskripsi"><?php echo $data['deskripsi']; ?></textarea>

            <label for="stok">Stok:</label>
            <input type="number" name="stok" id="stok" value="<?php echo $data['stok']; ?>" required>

            <label>Gambar Saat Ini:</label><br>
            <img src="uploads/<?php echo $data['gambar']; ?>" alt="Gambar Produk"><br><br>

            <label for="gambar">Gambar Baru:</label>
            <input type="file" name="gambar" id="gambar">

            <button type="submit">💾 Simpan Perubahan</button>
        </form>
    </div>
</body>

</html>
