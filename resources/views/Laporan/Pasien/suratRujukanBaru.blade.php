<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Surat Rujukan: {{ $cppt['pasien_nama'] }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page {
            size: 22cm 29, 7cm;
            /* lebar x tinggi */
            margin: 0.3cm;
            /* atur sesuai kebutuhan */
        }

        .pembungkus {
            padding: 1rem;
            /* border: 1px solid black; */
            box-sizing: border-box;
            /* width: 95.5%; */
        }

        .kertas {
            width: 22cm;
            /* atau bisa coba: 29.7cm 21cm */
            margin: 0.2cm 0.2cm 0.2cm 0.2cm;
            /* scale: 0.8; */
            /* border: 1px solid black; */
        }

        @media print {
            body {
                zoom: 0.9;
                /* bisa ganti ke 0.85-0.95 sesuai selera */
            }

            @page {
                .pembungkus {
                    padding: 1rem;
                    /* border: 1px solid black; */
                    box-sizing: border-box;
                    width: 22cm;
                    height: 29.7cm;
                    margin: 0.2cm;

                }
            }
        }
    </style>



</head>


<body class="flex justify-center">
    <div class="kertas">
        <div class="pembungkus">
            <div class="p-4 w-full">
                <div class="relative w-full border-b-2 border-black flex items-center">
                    <!-- Gambar -->
                    <div class="absolute w-[10%] flex justify-center items-center">
                        <img src="{{ asset('img/banyumas.png') }}" class="w-20" alt="banyumas" />
                    </div>
                    <!-- Teks di tengah -->
                    <div class="w-[100%] text-center mb-1">
                        <p class="text-md mb-0">PEMERINTAH KABUPATEN BANYUMAS</p>
                        <p class="text-md font-semibold mb-0">DINAS KESEHATAN</p>
                        <p class="text-md font-bold mb-0">KLINIK UTAMA KESEHATAN PARU MASYARAKAT KELAS A</p>
                        <p class="text-xs">Jln. A. Yani Nomor 33 Purwokerto Timur, Banyumas, Jawa Tengah</p>
                        <p class="text-xs">Kode Pos 53111, Telepon (0281) 635658, Pos-el bkpm_purwokerto@yahoo.com</p>
                    </div>
                </div>
                <!-- Garis bawah tebal -->
                <div class="w-full border-t-4 border-black mt-[2px]"></div>
                <div class="w-full text-center mt-2">
                    <h1 class="text-center font-bold text-sm">SURAT RUJUK BALIK</h1>
                </div>
                <div class="w-full text-left">
                    <p>Teman Sejawat Yth,</p>
                    <p>Mohon pelayanan selanjutnya untuk penderita :</p>
                    <div class="flex text-sm font-bold ml-2">
                        <div class="w-24">Nama</div>
                        <div>: {{ $cppt['pasien_nama'] }}</div>
                    </div>
                    <div class="flex text-sm font-bold ml-2">
                        <div class="w-24">Diagnosa</div>
                        <div>:
                            @php
                                $dxs = $cppt['diagnosa'];
                            @endphp
                            @if (empty($dxs) || count($dxs) == 0)
                                -
                            @elseif ($dxs[0]['kode_diagnosa'] == 'Z09.8')
                                {{ $dxs[1]['nama_diagnosa'] ?? '-' }}
                            @else
                                {{ $dxs[0]['nama_diagnosa'] ?? '-' }}
                            @endif
                        </div>
                    </div>

                    <p>Tindak lanjut yang dianjurkan :</p>
                    @php
                        // Misalnya $cppt adalah array atau object JSON yang memuat key 'ket_status_pasien_pulang'
                        $ketStatus = $cppt['ket_status_pasien_pulang'] ?? '';

                        // Ambil tanggal dari string misalnya: "Tanggal Kontrol : 2025-05-31"
                        preg_match('/Tanggal Kontrol\s*:\s*(\d{4}-\d{2}-\d{2})/', $ketStatus, $matches);
                        $tglKontrol = isset($matches[1])
                            ? \Carbon\Carbon::parse($matches[1])->locale('id')->isoFormat('DD MMMM Y')
                            : '-';
                    @endphp

                    <ul>
                        <li>
                            Kontrol kembali ke KKPM tanggal {{ $tglKontrol }}
                        </li>
                        <li>
                            Membuat Rujukan baru dari PPK 1 karena kondisi belum stabil, namun sudah melakukan pelayanan
                            selama 3 bulan
                        </li>
                    </ul>

                </div>


                <div class="flex items-center justify-between align-top">
                    <div>

                    </div>
                    <div>
                        <h6>Purwokerto,
                            {{ \Carbon\Carbon::parse($cppt['tanggal'])->locale('id')->isoFormat('DD MMMM Y') }}</h6>
                        <h6>Mengetahui DPJP,</h6>
                        <br>
                        <br>
                        <br>
                        @if ($cppt['dokter_nama'] == 'dr. AGIL DANANJAYA, Sp.P')
                            {{ $cppt['dokter_nama'] }}
                            <br>
                            SIP. 3302/53127/03/449.1/100/DS/B/IV/2023
                        @elseif ($cppt['dokter_nama'] == 'dr. Cempaka Nova Intani, Sp.P, FISR., MM.')
                            {{ $cppt['dokter_nama'] }}
                            <br>
                            SIP. 3302/53127/01/449.1/292/DS/P/XI/2022
                        @else
                            dr. AGIL DANANJAYA, Sp.P
                            <br>
                            SIP. 3302/53127/03/449.1/100/DS/B/IV/2023
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        //bagaimana untuk mengecek umur dan no_sampel jika cetak dari tombol CTRL + P dan browser
        document.addEventListener("keydown", function(event) {
            if (event.key === "Enter") {
                window.print();
                // window.onafterprint = function() {
                window.close();
                // }
            }
        })

        // load langsung cetak
        // document.addEventListener("DOMContentLoaded", function() {
        //     window.print();
        //     window.onafterprint = function() {
        //         window.close();
        //     }
        // })
    </script>
</body>

</html>
