<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Anjungan Pendaftaran Mandiri</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('vendor/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/dist/css/adminlte.min.css') }}">
    <!-- Theme style -->
    <link type="text/css" rel="stylesheet" href="{{ asset('css/mystyle.css') }}">
    <style>
        .keypad button {
            width: 100%;
            height: 60px;
            font-size: 1.5rem;
        }

        .keypad td {
            padding: 5px;
        }

        body,
        html {
            background: url("{{ asset('img/lungs.jpg') }}") no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            height: 46rem;

        }

        .bg {

            background-color: #00000026
        }

        .content {
            color: white;
            max-width: 700px;
        }

        .content h1 {
            font-size: 3rem;
            margin-bottom: 20px;
        }

        .content p {
            font-size: 1.5rem;
            margin-bottom: 30px;
        }

        .btn-custom {
            background-color: #28a745;
            border: none;
            color: white;
            padding: 15px 30px;
            font-size: 1.2rem;
            border-radius: 5px;
            text-decoration: none;
        }

        .btn-custom:hover {
            background-color: #218838;
        }

        #key_pad {
            display: none;
        }
    </style>

    {{-- @vite(['resources/js/scanner.js']) --}}
</head>

<body class="bg">
    <div class="container-fluid d-flex justify-content-end align-items-center text-center" style="height: 44rem;">
        <!-- Modal -->
        <div class="col">
            <div class="modal fade modal-dialog modal-dialog-centered" id="keyPad" data-backdrop="static"
                data-keyboard="false" tabindex="-1" aria-labelledby="keyPadLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="container p-0">
                                <div class="d-flex justify-content-center">
                                    <div class="col bg-secondary p-2 rounded">
                                        <div class="mr-4" style="margin-left: 40px;">
                                            <div class="row px-2">
                                                <div class="form-group col mb-2 d-flex justify-content-center">
                                                    <input type="text" id="norm"
                                                        class="form-control-lg col-md-7 text-center"
                                                        placeholder="Masukan No RM" />
                                                </div>
                                                <div class="form-group col-md-1 mb-2 d-flex justify-content-end">
                                                    <button type="button"
                                                        class="close col btn btn-secondary text-light"
                                                        data-dismiss="modal" aria-label="Close"
                                                        onclick="document.getElementById('norm').focus();">
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
                                                        onclick="keypadPress('enter')"
                                                        data-dismiss="modal">Cari</button>
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
        </div>
        <div class="content ml-2">
            <h1>Selamat Datang di Anjungan Pendaftaran Mandiri</h1>
            <h3>Klinik Utama Kesehatan Paru Masyarakat Kelas A</h3>
            <p>Untuk memulai pendaftaran, silakan klik tombol di bawah ini.</p>
            <div class="row d-flex justify-content-center">
                <button class="btn btn-success btn-lg col-5 py-3" type="button" data-toggle="modal"
                    data-target="#keyPad" id="mulai_pendaftaran">
                    <h3>Mulai
                        Pendaftaran</h3>
                </button>
            </div>
            <p hidden>Untuk scan barcode, silakan klik tombol di bawah ini.</p>
            <div class="row d-flex justify-content-center " hidden>
                <button class="btn btn-warning btn-lg col-4"id="start-scan" data-toggle="modal" data-target="#modalScan"
                    hidden>Mulai
                    Scan</button>
            </div>
        </div>


    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalScan" tabindex="-1" aria-labelledby="modalScanLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div id="scanner-container" style="width: 100%;height: 100%;max-height: 400px;overflow: hidden;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                        id="close-scan">Close</button>
                </div>
            </div>
        </div>
    </div>


    <script src="{{ asset('vendor/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('vendor/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('vendor/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- InputMask -->
    <script src="{{ asset('vendor/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('vendor/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
    <!-- DataTables  & Plugins -->

    <!-- AdminLTE App -->
    <script src="{{ asset('vendor/dist/js/adminlte.min.js') }}"></script>
    {{-- <script src="{{ asset('public/js/anjunganMandiri.js') }}"></script> --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("mulai_pendaftaran").addEventListener("click", function() {
                document.getElementById("norm").focus();
            });
            document.getElementById("norm").addEventListener("keydown", function(event) {
                if (event.key === "Enter" || event.keyCode === 13) { // Check for Enter key
                    event.preventDefault(); // Prevent the default action (optional)
                    // Your code to execute on Enter key press
                    console.log("Enter key pressed!");

                    keypadPress('enter')
                    $("#keyPad").modal("hide");
                }
            });

        });

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
                        title: "Data Pasien Ditemukan...!!!\n \n" + data.pasien_no_rm + "\n Pasien: " +
                            sebutan + data.pasien_nama + "\n\n Dokter\n " + data.dokter_nama +
                            "\n\n Apakah anda ingin melanjutkan Verifikasi Pendaftaran?",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "YA",
                        cancelButtonText: "TIDAK",
                    }).then((result) => {
                        // Display a confirmation dialog
                        if (result.isConfirmed) {
                            console.log("🚀 ~ cariRM ~ data:", data)
                            $("key_pad").hide();
                            // cetakNoAntrian(data)
                            verifikasiPendaftaran(data)
                            // Swal.close();
                        } else {}
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

        function verifikasiPendaftaran(data) {
            console.log("🚀 ~ verifikasiPendaftaran ~ data:", data)
            const nik = data.pasien_nik
            fetch('/api/verif/pendaftaran', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        nik: nik
                    }),
                })
                .then(response => response.json())
                .then(result => Swal.fire({
                    icon: "success",
                    title: "Verifikasi Pendaftaran Berhasil...!!!",
                    text: result.message
                }))
                .catch(error => console.error('Error executing automation script:', error));

        }

        function cetakNoAntrian(data) {
            var noAntri = data.antrean_nomor;
            var jenis = data.penjamin_nama;
            var no_rm = data.pasien_no_rm;
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
                `<style>body{text-align:center;}h1{font-size:60px;font-family:sans-serif;font-weight:bold;margin-top:6px;margin-bottom:6px;}` +
                `.judul{font-size:20px;font-family:sans-serif;font-weight:bold;margin-top:0px;margin-bottom:0px;}` +
                `h3{margin-top: 0px; margin-bottom: 0px;}` +
                `.jenis{font-size:20px;font-family:sans-serif;font-weight:bold; margin-top: 0px; margin-bottom: 0px;}.time{font-size:12px;font-family:sans-serif;margin-top:0px;margin-bottom:0px;}</style></head><body>`
            );

            printWindow.document.write(`<p class='judul'>Klinik Utama Kesehatan</p>`);
            printWindow.document.write(`<p class='judul'>Paru Masyarakat</p>`);
            printWindow.document.write(`<h1>${noAntri}</h1>`);
            printWindow.document.write(`<h3>No RM: ${no_rm}</h3>`);
            printWindow.document.write(`<p class='jenis'>${jenis}</p>`);
            printWindow.document.write(`<p class='time'>${tgl}</p>`);
            printWindow.document.write(`<p class='time'>${jam}</p>`);
            printWindow.document.write(`</body></html>`);

            printWindow.print();
            printWindow.close();
        }
    </script>
</body>

</html>
