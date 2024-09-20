<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Anjungan Pendaftaran Mandiri</title>
    <!-- Google Font: Source Sans Pro -->
    {{-- <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"> --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@300;700;900&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('vendor/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('vendor/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/dist/css/adminlte.min.css') }}">
    <!-- Theme style -->
    <link type="text/css" rel="stylesheet" href="{{ asset('css/mystyle.css') }}">
    <style>
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
            /* Ukuran teks dalam popup */
        }

        /* Jika diperlukan, sesuaikan juga padding dan margin */
        .swal-custom-popup .swal2-title {
            margin: 20px 0;
            /* Atur jarak judul */
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
            background-size: cover;
            margin: 0;
            padding: 0;
            height: 46rem;
        }

        .content {
            color: white;
            max-width: 700px;
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
            height: 15rem;
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
            font-size: 2.5rem;
        }

        .fs-3 {
            font-size: 3rem;
        }

        .identitas {
            top: 16rem;
            left: 20rem;
        }

        .tombol {
            width: 1000px;
            height: 250px;
            border-radius: 50px;
            margin-top: 50px;
            margin-bottom: 20px;
            font-size: 150px;
            font-weight: 600;
        }
    </style>

</head>

<body>
    <div class="container-fluid">
        <header class="row containerHeader text-center text-white py-3 px-2 text-uppercase font-weight-bold"
            style="">
            <div class="col-2">
                <img src="{{ asset('img/LOGO_KKPM.png') }}" alt="KKPM-Logo" height="144" width="144">
            </div>

            <div class="col px-3">
                <h1 class="fs-3 font-weight-bolder bordered-text merriweather-black">Selamat Datang di</h1>
                <h3 class="fs-2 font-weight-bolder bordered-text merriweather-black">Klinik Utama Kesehatan Paru
                    Masyarakat Kelas A</h3>
            </div>
            <div class="col-2">
                <h1 id="jam" class="bordered-text fs-3 bg-gradient-info rounded">{{ date('H:i:s') }}</h1>
                <h3 id="tgl" class="bordered-text fs-2 bg-gradient-info rounded">{{ date('d-m-Y') }}</h3>
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

        <div class="position-absolute container-fluid d-flex justify-content-end text-center" style="height: 44rem;">

            <div class="content">
                <p class="fs-4">Untuk memulai pendaftaran mandiri, silakan klik tombol di bawah ini.</p>
                <div class="row d-flex justify-content-center">
                    <button class="btn btn-success btn-lg col-5 py-3" type="button" data-toggle="modal"
                        data-target="#keyPad" id="mulai_pendaftaran" onclick="setFocus()">
                        <h3>Mulai
                            Pendaftaran</h3>
                    </button>
                </div>
                <p hidden>Untuk scan barcode, silakan klik tombol di bawah ini.</p>
                <div class="row d-flex justify-content-center " hidden>
                    <button class="btn btn-warning btn-lg col-4"id="start-scan" data-toggle="modal"
                        data-target="#modalScan" hidden>Mulai
                        Scan</button>
                </div>
            </div>
        </div>
    </div>
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
                                                placeholder="Masukan No RM"
                                                style="height: 80px; font-size: 30px; padding: 20px;" />
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
                        title: "Data Pasien Ditemukan...!!!" +
                            "\n\n" + data.pasien_no_rm +
                            "\n" + sebutan + data.pasien_nama +
                            "\n" + data.pasien_alamat +
                            "\n" + data.penjamin_nama +
                            "\n\n Dokter\n " + data.dokter_nama +
                            "\n\n Apakah anda ingin melanjutkan Verifikasi Pendaftaran?",
                        showCancelButton: true,
                        allowOutsideClick: false,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "YA",
                        cancelButtonText: "TIDAK",
                        width: '600px',
                        customClass: {
                            popup: 'swal-custom-popup', // Tambahkan class khusus untuk popup
                            title: 'swal-custom-title', // Tambahkan class khusus untuk title
                            icon: 'swal-custom-icon' // Tambahkan class khusus untuk icon
                        }
                    }).then((result) => {
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


        var socketIO = io.connect('wss://kkpm.banyumaskab.go.id:3131/', {
            // path: '/socket.io',
            transports: ['websocket',
                'polling',
                'flashsocket'
            ]
        });
        socketIO.on('connectParu', () => {
            const sessionID = socketIO.id
            $('#socket-id').html(sessionID)
            console.log("Socket ID : " + sessionID)
        });

        function ambil_antrean(penjamin_id) {
            // alert(penjamin_id)
            Swal.fire({
                title: 'Mohon Tunggu Beberapa Saat',
                text: 'Sedang memproses ambil antrean...',
                willOpen: () => {
                    Swal.showLoading();
                    $.ajax({
                        type: "POST",
                        url: "https://kkpm.banyumaskab.go.id/administrator/display_tv/ambil_antrean",
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
                                socketIO.emit('reload', 'paru_loket_pendaftaran');
                                socketIO.emit('reload', 'paru_notifikasi_loket_pendaftaran');
                                result = e.data
                                $("#tanggal_tampil_print").text(result['tanggal_tampil'])
                                $("#antrean_nomor_print").text(result['antrean_nomor'])
                                $("#penjamin_nama_print").text(result['penjamin_nama'])
                                $("#antrean_menunggu_print").text(result['antrean_menunggu_tampil'])

                                var divToPrint = document.getElementById('print');
                                var newWin = window.open('', 'Print-Window');
                                newWin.document.open();
                                newWin.document.write('<html><body onload="window.print()">' +
                                    divToPrint.innerHTML + '</body></html>');
                                newWin.document.close();

                                Swal.fire({
                                    title: 'Mohon Tunggu',
                                    text: "Sedang reload halaman...",
                                    icon: 'info',
                                    showConfirmButton: false,
                                })
                                setTimeout(function() {
                                    newWin.close();
                                    location.reload();
                                }, 1000);
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
