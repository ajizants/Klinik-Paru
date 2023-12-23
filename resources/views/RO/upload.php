<?php
include "koneksi.php"; // Menggunakan koneksi_ro

$response = array(); // Untuk menyimpan respons yang akan dikirim kembali

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tanggal = $_POST['tanggal'];
    $norm = $_POST['norm'];
    $nama = $_POST['nama'];
    // Format nomor RM dengan 6 digit dan diisi nol di depan jika kurang dari 6 digit
    $formattedNorm = sprintf("%06d", $norm);
    
    $foto = $_FILES['foto']['name'];
    $file_tmp = $_FILES['foto']['tmp_name'];

    // Pindahkan file foto ke folder 'file'
    move_uploaded_file($file_tmp, 'file/'.$foto);

    $query = "INSERT INTO foto_thorax SET 
    tanggal = '$tanggal',
    norm = '$norm',
    nama = '$nama',
    foto = '$foto'
    ";

    if (mysqli_query($koneksi_ro, $query)) {
        $response['success'] = true;
        $response['message'] = "Data berhasil diunggah.";
         // Update the totalData in foto_thorax
         $totalDataQuery = "SELECT COUNT(*) as total FROM foto_thorax";
         $totalDataResult = mysqli_query($koneksi_ro, $totalDataQuery);
         $totalData = 0;
         if ($totalDataResult) {
             $row = mysqli_fetch_assoc($totalDataResult);
             $totalData = $row['total'];
         }
 
         // Send the totalData value back as part of the response
         $response['totalData'] = $totalData;
    } else {
        $response['success'] = false;
        $response['message'] = "Error: " . mysqli_error($koneksi_ro);
    }
} else {
    $response['success'] = false;
    $response['message'] = "Metode HTTP tidak valid.";
}

// Mengirimkan respons dalam format JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
