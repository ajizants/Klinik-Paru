<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil nilai tanggal dari form
    $tanggal = $_POST["tanggal"];

    // Proses atau lakukan sesuatu dengan nilai tanggal yang diambil
    // Misalnya, Anda bisa menyimpan tanggal ini ke dalam database atau melakukan tindakan lainnya.

    // Contoh tindakan sederhana, menampilkan tanggal yang dipilih kembali ke pengguna:
    echo "Anda memilih tanggal: " . $tanggal;
}
?>
