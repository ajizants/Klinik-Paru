<?php
    // Koneksi ke database rsparu

    $koneksi_rsparu = mysqli_connect(
        "172.16.10.10",
        "root",
        "",
        "rs_paru"
    );

    if (mysqli_connect_errno()) {
        echo "Koneksi Gagal ke rsparu: " . mysqli_connect_error();
    }

    mysqli_set_charset($koneksi_rsparu, 'utf8');

    // Koneksi ke database rontgn
    $koneksi_ro = mysqli_connect(
        "localhost",
        "root",
        "",
        "rontgn"
    );

    if (mysqli_connect_errno()) {
        echo "Koneksi Gagal ke rontgn: " . mysqli_connect_error();
    }
?>
