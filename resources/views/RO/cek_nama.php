    
<?php
include "koneksi.php";

$norm = $_GET['norm'];
$norm = str_pad($norm, 6, "0", STR_PAD_LEFT);

$query_check_norm = "SELECT nama FROM m_pasien WHERE norm = '$norm'";
$result_check_norm = mysqli_query($koneksi_rsparu, $query_check_norm);

if (!$result_check_norm) {
    $response['error'] = "Database query error: " . mysqli_error($koneksi_rsparu);
} else {
    $row_check_norm = mysqli_fetch_assoc($result_check_norm);
    
    $response = array();

    if ($row_check_norm) {
        $response['nama'] = $row_check_norm['nama'];
    } else {
        $response['nama'] = null;
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>

