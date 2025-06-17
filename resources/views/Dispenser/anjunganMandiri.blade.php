<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Anjungan Pendaftaran Mandiri</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@300;700;900&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('vendor/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('vendor/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/dist/css/adminlte.min.css') }}">
    <style>
        #reader {
            width: 500px;
            height: 300px;
        }

        #scanner-container {
            width: 500px;
            height: 300px;
            border: 1px solid black;
        }

        .merriweather-light {
            font-family: "Merriweather", serif;
            font-weight: 300;
            font-style: normal;
        }

        .merriweather-bold {
            font-family: "Merriweather", serif;
            font-weight: 700;
            font-style: normal;
        }

        .merriweather-black {
            font-family: "Merriweather", serif;
            font-weight: 900;
            font-style: normal;
        }

        /* Mengatur ukuran ikon */
        .swal-custom-icon {
            font-size: 12px;
            /* Ukuran ikon */
        }

        /* Mengatur ukuran teks judul */
        .swal-custom-title {
            font-size: 24px;
            /* Ukuran teks judul */
        }

        /* Mengatur ukuran seluruh modal */
        .swal-custom-popup {
            font-size: 18px;
            width: 1000px !important;
            /* Ukuran teks dalam popup */
        }

        /* Jika diperlukan, sesuaikan juga padding dan margin */
        .swal-custom-popup .swal2-title {
            margin: 20px 0;
            /* Atur jarak judul */
        }

        .btn-large {
            padding: 20px 25px !important;
            /* Perbesar padding */
            font-size: 19px !important;
            /* Perbesar ukuran teks */
        }


        .keypad button {
            width: 100%;
            height: 90px;
            font-size: 2rem;
            font-weight: bold;
        }

        .keypad td {
            padding: 5px;
        }

        body,
        html {
            background: url("{{ asset('img/lungs.jpg') }}") no-repeat center center fixed;
            /* background: url("{{ asset('img/LOGO_KKPM.png') }}") no-repeat center center fixed; */
            background-size: cover;
            margin: 0;
            padding: 0;
            height: 46rem;
        }

        .content {
            color: white;
            max-width: 500px;
            top: 24rem;
            position: absolute;
        }

        .content h1 {
            font-size: 3rem;
            margin-bottom: 20px;
        }

        .content p {
            font-size: 2rem;
            margin-bottom: 30px;
        }

        #key_pad {
            display: none;
        }

        .containerHeader {
            background: linear-gradient(to top,
                    rgba(50, 168, 231, 0) 0%,
                    /* Warna awal */
                    rgba(50, 168, 231, 0.6) 50%,
                    /* Warna tengah */
                    rgba(50, 167, 231, 0.9) 100%
                    /* Warna akhir */
                );
            height: 10rem;
        }

        .logo {
            position: absolute;
            top: 0;
            padding: 10px;
        }

        .bordered-text {
            color: white;
            /* Warna teks */
            -webkit-text-stroke: 1px black;
            /* Tebal garis tepi dan warnanya */
            font-weight: bolder;
        }

        .fs-2 {
            font-size: 2rem;
        }

        .fs-3 {
            font-size: 3rem;
        }

        .fs-4 {
            font-size: 4rem;
        }

        .identitas {
            top: 16rem;
            left: 20rem;
        }

        .btn-lg {
            height: 170px;
            border-radius: 15px;
            font-size: 90px;
            font-weight: 900;
            margin-bottom: 2rem;
        }

        .btn-lg:hover {
            background-color: rgb(0, 255, 85);
        }

        .box {
            /* height: 500px; */
            border-radius: 15px;
            font-size: 100px;
            font-weight: 600;
            /* background-color: rgb(0, 0, 0, 0.3); */
            padding: 1rem;
        }
    </style>

    <!-- Script -->
    <script src="{{ asset('vendor/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('vendor/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('vendor/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- InputMask -->
    <script src="{{ asset('vendor/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('vendor/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
    <!-- QR CODE -->
    <script src="https://unpkg.com/html5-qrcode@2.2.1/minified/html5-qrcode.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>

    <!-- AdminLTE App -->
    <script src="{{ asset('vendor/dist/js/adminlte.min.js') }}"></script>
</head>

<body>
    <div class="container-fluid">
        <header class="row containerHeader text-center text-white py-3 px-2 text-uppercase font-weight-bold"
            style="">
            <div class="col-2">
                <img src="{{ asset('img/LOGO_KKPM.png') }}" alt="KKPM-Logo" height="110" width="120">
            </div>

            <div class="col px-3">
                <h1 class="fs-3 font-weight-bolder bordered-text merriweather-black">Selamat Datang</h1>
                <h3 class="fs-2 font-weight-bolder bordered-text merriweather-black">Klinik Utama Kesehatan Paru
                    Masyarakat Kelas A</h3>
            </div>
            <div class="col-2">
                <h1 id="jam" class="bordered-text fs-3 bg-gradient-info rounded">{{ date('H:i:s') }}</h1>
                <h3 id="tgl" class="bordered-text bg-gradient-info rounded">{{ date('d-m-Y') }}</h3>
            </div>
        </header>
        {{-- <div class="identitas container">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Informasi Paien</h3>
                </div>
                <form class="form-horizontal">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="form-group row">
                                    <label for="no_rm" class="col-sm-4 col-form-label">No Rekam Medik</label>
                                    <div class="col-sm">
                                        <input type="text" class="form-control" id="no_rm" placeholder="NORM">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="nama" class="col-sm-4 col-form-label">Nama</label>
                                    <div class="col-sm">
                                        <input type="text" class="form-control" id="nama" placeholder="Nama">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="nama" class="col-sm-4 col-form-label">Nama</label>
                                    <div class="col-sm">
                                        <input type="text" class="form-control" id="nama" placeholder="Nama">
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                            </div>
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-center">
                        <button type="submit" class="col-6 btn btn-lg btn-info">Lanjutkan</button>
                    </div>
                </form>
            </div>
        </div> --}}

        <main class="container-fluid d-flex aligmn-items-center text-center" style="height: 40rem;">
            {{-- <div class="row d-flex justify-content-center"> --}}
            <div class="col">
                <div class=" box">
                    <div style="height: 4rem">
                        <h3 class="bg-dark py-2 text-light mb-1">Untuk ambil antrian, Silakan pilih tombol UMUM/BPJS di
                            bawah ini.
                        </h3>
                    </div>
                    <button class="col btn btn-light btn-lg fw-3" onclick="ambil_antrean(1)">
                        UMUM
                    </button>
                    <button class="col btn btn-light btn-lg fw-3 mb-5" onclick="ambil_antrean(2)">
                        BPJS
                    </button>
                    <div class="p-3 rounded" style="background-color: rgba(0, 0, 0, 0.6)">
                        <div class="mt-5" style="height: 4rem">
                            <h3 class="bg-dark py-2 text-light mb-1">Untuk memulai verifikasi pendaftaran online,
                                silakan
                                klik tombol
                                di
                                bawah
                                ini.</h3>
                        </div>
                        <button class="col btn btn-success btn-lg mb-0" style="font-size: 70px" type="button"
                            data-toggle="modal" data-target="#keyPad" id="mulai_pendaftaran" onclick="setFocus()">
                            Mulai Verifikasi Mandiri
                        </button>
                        {{-- <button class="col btn btn-success btn-lg mb-0" style="font-size: 70px" type="button"
                            data-toggle="modal" data-target="#modalScan" id="mulai_pendaftaran"
                            onclick="startScanner()" style="display: none">
                            Scan Untuk Verifikasi
                        </button> --}}
                    </div>
                </div>
            </div>
            {{-- <div class="col">
                <div class="box">
                    <div style="height: 6rem">
                        <h3 class="text-light mb-4">Untuk memulai verifikasi pendaftaran online, silakan klik tombol
                            di
                            bawah
                            ini.</h3>
                    </div>
                    <button class="col btn btn-success btn-lg" type="button" data-toggle="modal" data-target="#keyPad"
                        id="mulai_pendaftaran" onclick="setFocus()">
                        Mulai Verifikasi
                    </button>
                </div>
            </div> --}}
            <div id="print" style="display: none;">
                <div class="row">
                    <span>
                        <center>Klinik Utama Kesehatan</center>
                    </span>
                    <span style="font-size: 12px">
                        <center><span id="tanggal_tampil_print"></span></center>
                    </span>
                    <hr style="margin-top: 2px; margin-bottom: 2px">
                    <span style="font-size: 16px; margin-top: 0px">
                        <center>Nomor Antrean</center>
                    </span>
                    <span style="font-size: 80px; margin-top: 0px">
                        <center><b id="antrean_nomor_print"></b></center>
                    </span>
                    <span style="font-size: 21px">
                        <center id="penjamin_nama_print"></center>
                    </span>
                    <span style="font-size: 15px">
                        <center id="antrean_menunggu_print"></center>
                    </span>
                    <span style="font-size: 15px; margin-top: 10px">
                        <center>Terimakasih sudah menunggu</center>
                    </span>
                </div>
            </div>
        </main>
        <footer class="text-white py-3 px-2 font-weight-bold" style="height: 4rem; margin-top: 2rem">
            <div class="row d-flex justify-content-center ">
                <H4 class="text-uppercase">TERIMAKASIH TELAH MEMPERCAYAKAN KESEHATAN ANDA KEPADA KAMI</H4>
            </div>
            <div class="row d-flex justify-content-center ">
                <h5>Copyright © {{ date('Y') }}. KKPM KELAS A</h5>
            </div>
        </footer>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="keyPad" data-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="container p-0">
                        <div class="d-flex justify-content-center">
                            <div class="col bg-secondary p-2 rounded">
                                <div class="mr-4" style="margin-left: 40px;">
                                    <div class="row px-2">
                                        <div class="form-group col mb-2 d-flex justify-content-center">
                                            <input type="text" id="norm"
                                                class="form-control form-control-lg col-md-7 text-center"
                                                placeholder="{{ $id_server }}. Masukan No RM"
                                                style="height: 80px; font-size: 30px; padding: 20px;"
                                                {{-- tambahkan batasan 6 angka --}}
                                                onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" />
                                        </div>

                                        <div class="form-group col-md-1 mb-2 d-flex justify-content-end">
                                            <button type="button" class="close col btn btn-secondary text-light"
                                                data-dismiss="modal" aria-label="Close">
                                                <p class="text-light" aria-hidden="true">&times;</p>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <table class="table table-bordered keypad text-center bg-secondary">
                                    <tr>
                                        <td class="col-md-3"><button class="btn btn-primary"
                                                onclick="keypadPress(1)">1</button></td>
                                        <td class="col-3"><button class="btn btn-primary"
                                                onclick="keypadPress(2)">2</button></td>
                                        <td class="col-md-3"><button class="btn btn-primary"
                                                onclick="keypadPress(3)">3</button></td>
                                    </tr>
                                    <tr>
                                        <td class="col-md-3"><button class="btn btn-primary"
                                                onclick="keypadPress(4)">4</button></td>
                                        <td class="col-3"><button class="btn btn-primary"
                                                onclick="keypadPress(5)">5</button></td>
                                        <td class="col-md-3"><button class="btn btn-primary"
                                                onclick="keypadPress(6)">6</button></td>
                                    </tr>
                                    <tr>
                                        <td class="col-md-3"><button class="btn btn-primary"
                                                onclick="keypadPress(7)">7</button></td>
                                        <td class="col-3"><button class="btn btn-primary"
                                                onclick="keypadPress(8)">8</button></td>
                                        <td class="col-md-3"><button class="btn btn-primary"
                                                onclick="keypadPress(9)">9</button></td>
                                    </tr>
                                    <tr>
                                        <td class="col-md-3"><button class="btn btn-danger"
                                                onclick="keypadPress('hapus')">Hapus</button>
                                        </td>
                                        <td class="col-3"><button class="btn btn-primary"
                                                onclick="keypadPress(0)">0</button></td>
                                        <td class="col-md-3"><button class="btn btn-success"
                                                onclick="keypadPress('enter')" data-dismiss="modal">Cari</button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalScan" tabindex="-1" aria-labelledby="modalScanLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <h1>Barcode Scanner</h1>
                    <div id="reader"></div>
                    <div id="scanner-container"></div>
                    <div id="result">Result will be shown here</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                        id="close-scan">Close</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        function startScanner() {
            Quagga.init({
                inputStream: {
                    name: "Live",
                    type: "LiveStream",
                    target: document.querySelector('#scanner-container'), // Target element for video
                    constraints: {
                        width: 500,
                        height: 300,
                        facingMode: "environment" // Use the rear camera
                    },
                },
                decoder: {
                    readers: ["code_128_reader", "ean_reader", "ean_8_reader", "code_39_reader",
                        "code_39_vin_reader", "upc_reader", "upc_e_reader", "codabar_reader"
                    ]
                }
            }, function(err) {
                if (err) {
                    console.log(err);
                    return;
                }
                console.log("QuaggaJS initialized successfully.");
                Quagga.start(); // Mulai kamera
            });

            // Menangani hasil scan
            Quagga.onDetected(function(result) {
                var code = result.codeResult.code;
                document.getElementById('result').innerText = "Barcode detected: " + code;
                console.log("Barcode detected:", code);
            });
        }

        function onScanSuccess(decodedText, decodedResult) {
            // Tampilkan hasil barcode yang dipindai
            document.getElementById('result').innerHTML = `Barcode detected: ${decodedText}`;
        }

        function onScanError(errorMessage) {
            // Tangani error (jika diperlukan)
            console.warn(`Scan error: ${errorMessage}`);
        }


        function scan() {
            let html5QrcodeScanner = new Html5QrcodeScanner("reader", {
                fps: 10, // Frame per second
                qrbox: 250 // Set the scanning box size
            });
            html5QrcodeScanner.render(onScanSuccess, onScanError);

        }

        function updateTime() {
            const now = new Date();

            // Format jam, menit, detik
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');

            // Update elemen jam
            document.getElementById('jam').textContent = `${hours}:${minutes}:${seconds}`;
        }

        // Panggil fungsi updateTime setiap 1000ms (1 detik)
        setInterval(updateTime, 1000);

        // Jalankan fungsi pertama kali untuk menampilkan jam segera saat halaman dimuat
        updateTime();
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("norm").addEventListener("keydown", function(event) {
                if (event.key === "Enter" || event.keyCode === 13) { // Check for Enter key
                    event.preventDefault(); // Prevent the default action (optional)
                    // Your code to execute on Enter key press
                    console.log("Enter key pressed!");

                    cariRM($("#norm").val());
                    $("#keyPad").modal("hide");
                }
            });

        });

        function setFocus() {
            console.log("🚀 ~ setFocus ~ setFocus:", setFocus)
            document.getElementById("norm").focus();
        }

        function keypadPress(value) {
            const inputField = document.getElementById("norm");
            var p = inputField.value
            if (value === "hapus") {
                inputField.value = "";
            } else if (value === "enter") {
                var norm = $("#norm").val().replace(/\D/g, "");
                while (norm.length < 6) {
                    norm = "0" + norm;
                }
                console.log("🚀 ~ keypadPress ~ norm:", norm)
                inputField.value = norm;
                cariRM(norm);
            } else {
                if (p == "" || p.length < 6) {
                    inputField.value += value;
                }
            }
        }

        async function cariRM(norm) {

            norm = norm || formatNorm($("#norm").val);
            var requestData = {
                norm: norm
            };

            Swal.fire({
                icon: "info",
                title: "Sedang mencarikan data pasien...!!!",
                showConfirmButton: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            });

            try {
                const response = await fetch("/api/kominfo/pendaftaran", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify(requestData),
                });

                if (!response.ok) {

                    Swal.fire({
                        icon: "error",
                        title: "Pasien tidak ditemukan di pendaftaran online...!!!\n\n Silahkan melakukan pendaftaran di loket pendaftaran",
                    });
                } else {
                    const data = await response.json();
                    var sebutan = "";
                    if (data.pasien_umur_tahun <= 14) {
                        sebutan = "An. ";
                    } else if (data.pasien_umur_tahun > 14 && data.pasien_umur_tahun <= 30) {
                        if (data.jenis_kelamin_nama == "L") {
                            sebutan = "Sdr. ";
                        } else {
                            sebutan = "Nn. ";
                        }
                    } else if (data.pasien_umur_tahun > 30) {
                        if (data.jenis_kelamin_nama == "L") {
                            sebutan = "Tn. ";
                        } else {
                            sebutan = "Ny. ";
                        }
                    }
                    Swal.fire({
                        icon: "question",
                        title: "Data Pasien Ditemukan...!!!",
                        html: `
                                <table class="table table-bordered text-left ">
                                    <tr>
                                        <th>No Antrean:</th>
                                        <td>${data.antrean_nomor}</td>
                                    </tr>
                                    <tr>
                                        <th>No RM:</th>
                                        <td>${data.pasien_no_rm}</td>
                                    </tr>
                                    <tr>
                                        <th>Nama Pasien:</th>
                                        <td>${sebutan} ${data.pasien_nama}</td>
                                    </tr>
                                    <tr>
                                        <th>Alamat:</th>
                                        <td>${data.pasien_alamat}</td>
                                    </tr>
                                    <tr>
                                        <th>Penjamin:</th>
                                        <td>${data.penjamin_nama}</td>
                                    </tr>
                                    <tr>
                                        <th>Dokter:</th>
                                        <td>${data.dokter_nama}</td>
                                    </tr>
                                </table>
                                <p><strong>Apakah anda ingin melanjutkan Verifikasi Pendaftaran?</strong></p>
                            `,
                        showCancelButton: true,
                        showDenyButton: true,
                        allowOutsideClick: false,
                        confirmButtonText: "Verivikasi Wajah",
                        confirmButtonColor: "#008bff",
                        cancelButtonText: "Verivikasi Sidik Jari",
                        cancelButtonColor: "#00ff55",
                        denyButtonText: 'Batal',
                        denyButtonColor: "#ff0000",
                        customClass: {
                            popup: 'swal-custom-popup', // Tambahkan class khusus untuk popup
                            title: 'swal-custom-title', // Tambahkan class khusus untuk title
                            icon: 'swal-custom-icon', // Tambahkan class khusus untuk icon
                            confirmButton: 'btn-large',
                            cancelButton: 'btn-large text-dark',
                            denyButton: 'btn-large',
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            console.log("🚀 ~ cariRM ~ data:", data)
                            $("key_pad").hide();
                            // cetakNoAntrian(data)
                            verifikasiFR(data)

                            Swal.fire({
                                icon: "info",
                                title: "Proses Verifikasi dan pendaftaran...!!\n Mohon Ditunggu ...!!!",
                                // showConfirmButton: false,
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                },
                            });
                        } else if (result.isDenied) {
                            $("key_pad").hide();
                            cetakNoAntrian(data);
                        } else {
                            console.log("🚀 ~ cariRM ~ data:", data)
                            $("key_pad").hide();
                            verifikasiFP(data);
                            Swal.fire({
                                icon: "info",
                                title: "Proses Verifikasi dan pendaftaran...!!\n Mohon Ditunggu ...!!!",
                                // showConfirmButton: false,
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                },
                            });
                        }
                    });
                }
                $("#norm").val("");
            } catch (error) {
                console.error("Terjadi kesalahan saat mencari data:", error);
                Swal.fire({
                    icon: "error",
                    title: "Terjadi kesalahan saat mencari data...!!! /n" + error,
                });
            }
        }
        const id_server = @json($id_server)

        function verifikasiFP(data) {
            console.log("🚀 ~ verifikasiFP ~ data:", data);
            let id_number = "";
            if (data.penjamin_nomor == "") {
                id_number = data.pasien_nik;
            } else {
                id_number = data.penjamin_nomor;
            }
            fetch('/api/verif/pendaftaran/fp', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        id_number: id_number,
                        id_server: id_server
                    }),
                })
                .then(response => {
                    if (response.status === 500) {
                        Swal.fire({
                            icon: "error",
                            title: "Terjadi Kesalahan",
                            text: "Verifikasi gagal, silakan coba lagi.",
                        });
                        throw new Error('Server error: 500');
                    }
                    return response.json();
                })
                .then(result => {
                    console.log("🚀 ~ cetakNoAntrian ~ data:", data);
                    var no_rm = data.pasien_no_rm;
                    // Jika verifikasi berhasil, langsung cetak tanpa pertanyaan
                    // Swal.fire({
                    //     icon: "success",
                    //     title: "Verifikasi Berhasil...!!!",
                    //     showConfirmButton: false,
                    //     timer: 2000,
                    // }).then(() => {
                    // submitKominfo(no_rm, data);
                    // cetakNoAntrian(data);
                    // });
                    setTimeout(() => {
                        Swal.fire({
                            icon: "question",
                            title: "Lanjutkan Cetak No Antrian?",
                            showCancelButton: true,
                            confirmButtonText: "Ya",
                            cancelButtonText: "Batal",
                            allowOutsideClick: false,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                cetakNoAntrian(data);
                            }
                        })
                    }, 3000);


                })
                .catch(error => {
                    console.error('Error executing automation script:', error);
                });
        }

        function verifikasiFR(data) {
            console.log("🚀 ~ verifikasiFR ~ data:", data);
            let id_number = "";
            if (data.penjamin_nomor == "") {
                id_number = data.pasien_nik;
            } else {
                id_number = data.penjamin_nomor;
            }
            fetch('/api/verif/pendaftaran/fr', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        id_number: id_number,
                        id_server: id_server
                    }),
                })
                .then(response => {
                    if (response.status === 500) {
                        Swal.fire({
                            icon: "error",
                            title: "Terjadi Kesalahan",
                            text: "Verifikasi gagal, silakan coba lagi.",
                        });
                        throw new Error('Server error: 500');
                    }
                    return response.json();
                })
                .then(result => {
                    // Jika verifikasi berhasil, langsung cetak tanpa pertanyaan
                    // Swal.fire({
                    console.log("🚀 ~ cetakNoAntrian ~ data:", data);
                    var no_rm = data.pasien_no_rm;
                    //     icon: "success",
                    //     title: "Verifikasi Berhasil...!!!",
                    //     showConfirmButton: false,
                    //     timer: 2000,
                    // }).then(() => {
                    // submitKominfo(no_rm, data);
                    // cetakNoAntrian(data);
                    // });

                    setTimeout(() => {
                        Swal.fire({
                            icon: "question",
                            title: "Lanjutkan Cetak No Antrian?",
                            showCancelButton: true,
                            confirmButtonText: "Ya",
                            cancelButtonText: "Batal",
                            allowOutsideClick: false,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                cetakNoAntrian(data);
                            }
                        })
                    }, 3000);
                })
                .catch(error => {
                    console.error('Error executing automation script:', error);
                });
        }

        function verifikasiPendaftaran(data, type) {
            console.log("🚀 ~ verifikasiFR ~ data:", data);
            let id_number = "";
            let url = "";
            if (data.penjamin_nomor == "") {
                id_number = data.pasien_nik;
            } else {
                id_number = data.penjamin_nomor;
            }
            if (type == "FR") {
                url = "/api/verif/pendaftaran/fr";
            } else {
                url = "/api/verif/pendaftaran/fp";
            }
            fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        id_number: id_number,
                        id_server: id_server
                    }),
                })
                .then(response => {
                    if (response.status === 500) {
                        Swal.fire({
                            icon: "error",
                            title: "Terjadi Kesalahan",
                            text: "Verifikasi gagal, silakan coba lagi.",
                        });
                        throw new Error('Server error: 500');
                    }
                    return response.json();
                })
                .then(result => {
                    console.log("🚀 ~ cetakNoAntrian ~ data:", data);
                    var no_rm = data.pasien_no_rm;
                    submitKominfo(no_rm, data);
                })
                .catch(error => {
                    console.error('Error executing automation script:', error);
                });
        }

        function submitKominfo(no_rm, data) {
            Swal.fire({
                icon: "info",
                title: "Proses Verifikasi dan pendaftaran...!!!",
                // showConfirmButton: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            });
            console.log("🚀 ~ submitKominfo ~ no_rm:", no_rm);
            fetch('/api/verif/pendaftaran/kominfo/submit', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        no_rm: no_rm
                    }),
                })
                .then(response => {
                    console.log("🚀 ~ submitKominfo ~ response:", response);
                    if (response.status === 500) {
                        Swal.fire({
                            icon: "error",
                            title: "Terjadi Kesalahan",
                            text: "Verifikasi gagal, silakan coba lagi.",
                        });
                        throw new Error('Server error: 500');
                    }
                    return response.json();
                })
                .then(result => {
                    if (result.code == 200) {
                        Swal.fire({
                            icon: "success",
                            title: "Verifikasi Berhasil...!!!",
                            text: result.message,
                            showConfirmButton: false,
                            timer: 3000,
                        })
                    } else if (result.code == 500) {
                        Swal.fire({
                            icon: "error",
                            title: "Terjadi Kesalahan",
                            text: result.message,
                        });
                    } else if (result.code == 201) {
                        Swal.fire({
                            icon: "info",
                            title: "Pasien Sudah terdaftar pada hari ini...!!",
                            text: result.message,
                        });
                    } else if (result.code == 202) {
                        Swal.fire({
                            icon: "info",
                            title: "Pasien dengan NIK ini sudah terdaftar pada hari ini...!!",
                            text: result.message,
                        });
                    }
                    cetakNoAntrian(data);
                    console.log("🚀 ~ submitKominfo ~ result:", result);
                })
                .catch(error => {
                    console.error('Error executing automation script:', error);
                    Swal.fire({
                        icon: "error",
                        title: "Terjadi Kesalahan",
                        text: error,
                    });
                })
        }

        function cetakNoAntrian(data) {
            Swal.fire({
                icon: "success",
                title: "Verifikasi Berhasil...!!",
                timer: 3000,
            });
            var noAntri = data.antrean_nomor;
            var jenis = data.penjamin_nama;
            var no_rm = data.pasien_no_rm ?? "------";
            // var noAntri = "001";
            // var jenis = "UMUM";
            // var no_rm = "000001";
            var date = new Date();

            let options = {
                weekday: "long",
                year: "numeric",
                month: "long",
                day: "numeric",
            };
            let options2 = {
                hour: "2-digit",
                minute: "numeric",
                second: "numeric",
                timeZoneName: "short",
                timeZone: "Asia/Jakarta",
            };

            let tgl = date.toLocaleString("id-ID", options);
            let jam = date.toLocaleString("id-ID", options2);

            let printWindow = window.open("", "_blank");
            printWindow.document.write(
                `<html><head><title>Cetak No Antrian</title>` +
                `<style>body{text-align:center;}h1{font-size:60px;font-family:sans-serif;font-weight:bold;margin-top:6px;margin-bottom:6px; margin-left: 2px; margin-right: 2px;}` +
                `.judul{font-size:20px;font-family:sans-serif;font-weight:bold;margin-top:0px;margin-bottom:0px;}` +
                `h3{margin-top: 0px; margin-bottom: 0px;}` +
                `.jenis{font-size:20px;font-family:sans-serif;font-weight:bold; margin-top: 0px; margin-bottom: 0px;}.time{font-size:12px;font-family:sans-serif;margin-top:0px;margin-bottom:0px;}</style></head><body>`
            );



            printWindow.document.write(`<p class='judul'>Klinik Utama Kesehatan</p>`);
            printWindow.document.write(`<p class='judul'>Paru Masyarakat</p>`);
            printWindow.document.write(`<h1>${noAntri}</h1>`);
            printWindow.document.write(`<h3>No RM: ${no_rm}</h3>`);
            printWindow.document.write(`<p class='jenis'>${jenis}</p>`);
            printWindow.document.write(`<p class='time'>${tgl}, ${jam}</p>`);
            printWindow.document.write(`<p class='time'>Terimakasih sudah menunggu</p>`);
            printWindow.document.write(
                `<div style='margin-top: 2px; margin-bottom: 2px; height: 150px; '>
                   
                </div>`
            );

            printWindow.document.write(`</body></html>`);

            printWindow.print();
            printWindow.close();
        }



        function ambil_antrean(penjamin_id) {
            // alert(penjamin_id)
            Swal.fire({
                title: 'Mohon Tunggu Beberapa Saat',
                text: 'Sedang memproses ambil antrean...',
                willOpen: () => {
                    Swal.showLoading();
                    $.ajax({
                        type: "POST",
                        url: "/api/ambil/no/kominfo",
                        dataType: "json",
                        data: {
                            penjamin_id: penjamin_id
                        },
                        success: function(e) {
                            Swal.close();
                            if (e.code != 200) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Peringatan!',
                                    text: e.message
                                })
                            } else {
                                result = e.data
                                cetakNoAntrian(result)
                                // $("#tanggal_tampil_print").text(result['tanggal_tampil'])
                                // $("#antrean_nomor_print").text(result['antrean_nomor'])
                                // $("#penjamin_nama_print").text(result['penjamin_nama'])
                                // $("#antrean_menunggu_print").text(result['antrean_menunggu_tampil'])

                                // var divToPrint = document.getElementById('print');
                                // var newWin = window.open('', 'Print-Window');
                                // newWin.document.open();
                                // newWin.document.write('<html><body onload="window.print()">' +
                                //     divToPrint.innerHTML + '</body></html>');
                                // newWin.document.close();

                                // Swal.fire({
                                //     title: 'Mohon Tunggu',
                                //     text: "Sedang reload halaman...",
                                //     icon: 'info',
                                //     showConfirmButton: false,
                                // })
                                // setTimeout(function() {
                                //     newWin.close();
                                //     location.reload();
                                // }, 1000);
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            Swal.close();
                            iziToast.warning({
                                title: "'" + xhr.responseText + "'",
                                position: 'topLeft',
                                timeout: 100, //TODO : coba cek segini dulu
                            });
                        }
                    })
                }
            })
        }
    </script>
</body>

</html>
