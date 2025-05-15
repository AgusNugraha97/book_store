<?php
include "../config.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $id = mysqli_real_escape_string($conn, $id);

    // Ambil nama gambar dulu
    $querySelect = "SELECT gambar FROM produk WHERE id_produk = $id";
    $resultSelect = mysqli_query($conn, $querySelect);
    $row = mysqli_fetch_assoc($resultSelect);

    if ($row && file_exists("uploads/" . $row['gambar'])) {
        unlink("uploads/" . $row['gambar']); // Hapus gambar dari folder
    }

    // Hapus data dari database
    $queryDelete = "DELETE FROM produk WHERE id_produk = $id";
    $resultDelete = mysqli_query($conn, $queryDelete);

    if ($resultDelete) {
        echo "<script>
                alert('Produk berhasil dihapus!');
                window.location.href = 'index.php';
              </script>";
        exit;
    } else {
        echo "Gagal menghapus produk dari database.";
    }
} else {
    echo "ID tidak ditemukan.";
}
?>
