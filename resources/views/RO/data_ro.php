<?php
include "koneksi.php";

$search = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT * FROM foto_thorax";


if (isset($search) && is_numeric($search)) {
    // Add leading zeros if the search value has less than 6 digits
    $formattedSearch = str_pad($search, 6, "0", STR_PAD_LEFT);
    $sql .= " WHERE norm = '$formattedSearch' OR norm LIKE '%$formattedSearch%'";
} 

$result = mysqli_query($koneksi_ro, $sql);

// Rest of your code...

if (mysqli_num_rows($result) > 0) {
    echo "<div class='table-container'>";
    echo "<table class='table  text-center sticky-header'>";
    echo "<thead class='thead-dark'>";
    echo "<tr>
            <th>No RM</th>
            <th>Nama Pasien</th>
            <th>Tanggal</th>
            <th>Aksi</th>
          </tr>";
    echo "</thead>";

    // Menampilkan data dalam tabel
    echo "<tbody class='table-scroll'>"; // Tambahkan class table-scroll pada tbody
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row["norm"] . "</td>";
        echo "<td class='text-start'>" . $row["nama"] . "</td>";
        echo "<td>" . $row["tanggal"] . "</td>";
        echo "<td>
                <a href='lihat_foto.php?norm=" . urlencode($row["norm"]) . "' class='btn btn-warning' >Lihat</a>
                <span class='grid gap-3'></span>
                <a href='delete.php?id=" . $row["id"] . "' class='btn btn-danger' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data ini?\")'>Hapus</a>
            </td>";
        echo "</tr>";
    }


} else {
    echo "<div class='table-container'>";
    echo "<table class='table  text-center shadow sticky-header'>";
    echo "<thead class='thead-dark'>";
    echo "<tr>
            <th>No RM</th>
            <th>Nama Pasien</th>
            <th>Tanggal</th>
            <th>Aksi</th>
          </tr>";
    echo "</thead>";
    echo "</tbody>";
    echo "</table>";
    echo "</div>";
    echo "Tidak ada data, Silahkan menghubungi bagian radiologi";
}

?>

