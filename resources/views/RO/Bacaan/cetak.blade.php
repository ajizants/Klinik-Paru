<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cetak</title>
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
        @media print {
            @page {
                /* size: A4 portrait; */
                margin: 1.5cm;
                scale: 1.7;
            }

            body {
                margin: 0;
            }

            .a4 {
                /* width: 220mm; */
            }
        }

        /* Untuk tampilan layar */
        .a4 {
            width: 210mm;
            min-height: 297mm;
            padding: 20mm;
            margin: auto;
            background: white;
            text-align: left;
            display: block;
            padding-top: 10mm;
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
    <div class=" container border "">
        <div class="container-fluid" style="text-align: center;">
            <div style=" margin-bottom: 0;">
                <p style="font-size: 20pt; font-weight: bold; margin: 0;">dr. SOEGIMIN ARDI SOEWARNO, Sp. Rad.</p>
                <p style="font-size: 12pt; margin: 0;">No. SIP : 3302/53114/01/449.1/247/DS/P/VIII/2022</p>
                <p style="font-size: 12pt; margin: 0;">Alamat Praktek : Jl. Piere Tendean No. 39 Purwokerto</p>
            </div>

            <div style="border-bottom: 4px solid black; margin-top: 5px;"></div>
            <div style="border-bottom: 1px solid black; margin-top: 2px;"></div>
        </div>
        <h4 class="text-center font-weight-bold mb-0"><u>Hasil Pemeriksaan Radiologi</u></h4>
        <h4 class="text-center font-weight-bold">KLINIK UTAMA KESEHATAN PARU MASYARAKAT KELAS A</h4>


        {{-- identitas --}}
        <table class="table table-borderless mb-0" width="100%">
            <tbody>
                <tr>
                    <td width="20%" class="my-0 py-0" style="font-weight: bold;">No RM</td>
                    <td width="30%" class="my-0 py-0">: {{ $data->norm }}</td>
                    <td width="20%" class="my-0 py-0" style="font-weight: bold;">No Radiologi</td>
                    <td width="30%" class="my-0 py-0">: L.{{ $nomorRad }}</td>
                </tr>
                <tr>
                    <td class="my-0 py-0" style="font-weight: bold;">Nama Pasien</td>
                    <td class="my-0 py-0">: {{ $data->nama }}</td>
                    <td class="my-0 py-0" style="font-weight: bold;">Tanggal</td>
                    <td class="my-0 py-0">:
                        {{ Carbon\Carbon::parse($data->created_at)->locale('id')->isoFormat('DD MMMM Y') }}
                    </td>
                </tr>
                <tr>
                    <td class="my-0 py-0" style="font-weight: bold;">Umur</td>
                    <td class="my-0 py-0">:
                        @php
                            use Carbon\Carbon;
                            $umur = Carbon::parse($data->pasien->tgllahir)->diffInYears(Carbon::parse($data->tgltrans));
                            echo $umur . ' Tahun';
                        @endphp
                    </td>
                    <td class="my-0 py-0" style="font-weight: bold;">Pemeriksaan</td>
                    <td class="my-0 py-0">: {{ $data->pemeriksaan->nmLayanan }}</td>
                </tr>
                </tr>
                <tr>
                    <td class="my-0 py-0" style="font-weight: bold;">Alamat</td>
                    <td class="my-0 py-0" colspan="3">: {{ \Illuminate\Support\Str::title($data->alamat) }}
                    </td>

            </tbody>
        </table>
        <div class="container-fluid px-3 row">
            <div class="col">
                <p>TS Yth, </p>
                @if ($data->hasilBacaan)
                    {!! $data->hasilBacaan->bacaan_radiolog !!}
                @else
                    <p><em>Bacaan belum tersedia.</em></p>
                @endif
            </div>
            <div class="col">
                <p style="text-align: center; margin-top: 200px;">Hormat Kami, </p>
                <img src="{{ asset('img/ttd_sprad.png') }}" alt="Tanda Tangan" height="120"
                    style="display: block; margin: 0 auto; object-fit: contain; mix-blend-mode: multiply; transform: rotate(15deg);">

                <p class="mb-0" style="text-align: center;"><u><b>dr. Soegimin Ardi Soewarno, Sp. Rad.</b></u></p>
                <p class="mb-0" style="text-align: center;"><b>Spesialis Radiologi</b></p>
            </div>
        </div>

    </div>

    <script>
        // Swal.fire({
        //     icon: 'info',
        //     title: 'Untuk mencetak hasil lab, silahkan klik tombol \n "ENTER" \n pada tombol keyboard.\n\n' +
        //         'Jangan Lupa Mengisikan Umur Paien dan No Sample. Terima Kasih.',
        // })

        //buatkan fungsi cek, apakah umur dan no_sampel sudah diisi saat sebelum cetak
        function cetak() {
            var umur = document.getElementById("umur").value;
            var no_sampel = document.getElementById("no_sampel").value;
            if (umur == "" || no_sampel == "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Umur dan No Sample harus diisi terlebih dahulu!',
                }).then(() => {
                    if (document.getElementById("no_sampel").value == "") {
                        document.getElementById("no_sampel").focus();
                    } else {
                        document.getElementById("umur").focus();
                    }
                    return false;
                })
                //fokuskan pada umur dan no_sampel
            } else {
                // window.print();
                // window.onafterprint = function() {
                window.close();
                // }
            }
        }
        document.getElementById("no_sampel").focus();

        function removeBgWarning(id) {
            const input = document.getElementById(id);
            if (input.value) {
                input.classList.remove('bg-warning');
            } else {
                input.classList.add('bg-warning');
            }
        }

        //bagaimana untuk mengecek umur dan no_sampel jika cetak dari tombol CTRL + P dan browser
        document.addEventListener("keydown", function(event) {
            if (event.key === "Enter") {
                cetak();
            }
        })
    </script>
</body>

</html>
