<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css" />

    <title>
        <?php
            include "koneksi.php";
            $norm = isset($_GET['norm']) ? $_GET['norm'] : '';
            $sql = "SELECT * FROM foto_thorax WHERE norm = '$norm'";
            
            $result = mysqli_query($koneksi_ro, $sql);
            
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result); // Ambil data dari hasil query
                echo "(" . $row["norm"] . ")  ";
                echo "" . $row["nama"] . "";
            } else {
                echo "Tidak ada data.";
            }
        ?>
     </title>
</head>
<body>
    <!-- navbar -->
<?php include "navbar.php"
?>
    <!-- akhir navbar -->

    <div class="container lihat">
        <div class="row">
            <div class="col">
                <?php
                    include "koneksi.php";
                    // Mengambil nomor RM dari URL (parameter GET)
                    $norm = isset($_GET['norm']) ? $_GET['norm'] : '';

                    // Lakukan pengecekan apakah nomor RM tidak kosong
                    if (!empty($norm)) {
                        // Lakukan query untuk mendapatkan data dari tabel foto_thorax berdasarkan nomor RM
                        $query = "SELECT * FROM foto_thorax WHERE norm LIKE '$norm'";
                        $result = mysqli_query($koneksi_ro, $query);
                        
                        // Tampilkan data foto
                        if (mysqli_num_rows($result) > 0) {
                             echo"<h2 class ='text-center'>".$row['nama']."</h2>";
                             echo "<h2 class ='text-center'>No RM :  " . $norm . " ";
                             echo "<hr>";
                                                        
                            $rowCount = 0; // Untuk menghitung jumlah gambar dalam satu baris

                            while ($row = mysqli_fetch_assoc($result)) {
                                if ($rowCount === 0) {
                                    
                                    echo "<div class='row'>"; // Buka baris baru
                                    }

                                echo "<div class='col-md-6 text-center'>";
                                echo "<h3>Tanggal: " . $row["tanggal"] . "</h3>";
                                echo "<img src='file/" . $row["foto"] . "' alt='Foto Thorax' class='img-fluid mb-2' style='max-height: 450px;'>";
                                echo "<a></a>";
                                //echo "<a href='delete.php?id=" . $row["id"] . "' class='btn btn-danger btn-sm d-grid gap-2 mb-2'>Hapus</a>";
                                echo "</div>";

                                $rowCount++;

                                if ($rowCount === 2) {
                                    echo "</div>"; // Tutup baris
                                    $rowCount = 0; // Reset jumlah gambar dalam satu baris
                                }
                            }

                            // Jika masih ada gambar yang belum selesai ditampilkan dalam baris terakhir
                            if ($rowCount !== 0) {
                                echo "</div>"; // Tutup baris terakhir
                            }
                        } else {
                            echo "<h1 class='text-center pt-5'>Data Foto tidak ditemukan untuk Nomor RM: " . $norm . ", jika pasien melakukan Rontegen di BKPM silahkan menghubungi radiologi</h1>";
                        }
                    } else {
                        echo "<h1 class='text-center pt-5'>No RM tidak valid</h1>";
                    }
                ?>
            </div>
        </div>
    </div>
        
</body>
</html>