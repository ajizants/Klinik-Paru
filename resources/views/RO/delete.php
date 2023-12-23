<?php
include "koneksi.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Cari data yang akan dihapus
    $query = "SELECT * FROM foto_thorax WHERE id = ?";
    $stmt = mysqli_prepare($koneksi_ro, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);

    if ($row) {
        // Hapus file foto jika ada
        $foto = $row['foto'];
        if (file_exists('file/' . $foto)) {
            unlink('file/' . $foto);
        }

        // Hapus data dari tabel
        $delete_query = "DELETE FROM foto_thorax WHERE id = ?";
        $delete_stmt = mysqli_prepare($koneksi_ro, $delete_query);
        mysqli_stmt_bind_param($delete_stmt, "i", $id);
        mysqli_stmt_execute($delete_stmt);
        mysqli_stmt_close($delete_stmt);

        // Setel pesan untuk ditampilkan di halaman index.php
        $pesan = "Data berhasil dihapus.";
    } else {
        // Setel pesan untuk ditampilkan di halaman index.php
        $pesan = "Data tidak ditemukan.";
    }

    mysqli_stmt_close($stmt);
} else {
    // Setel pesan untuk ditampilkan di halaman index.php
    $pesan = "ID tidak valid.";
}

// Kembali ke halaman index.php dengan parameter pesan
header("Location: index.php?pesan=" . urlencode($pesan)."#dataro");
?>
