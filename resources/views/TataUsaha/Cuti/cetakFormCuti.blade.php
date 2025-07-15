<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page {
            size: 22cm 33cm;
            margin: 1cm;
        }
    </style>
</head>

<body class="bg-white" style="font-size: 12pt;">
    <div class="wrapper mx-auto " style="width: 22cm ; height: 33cm">
        <div>
            <div dir="rtl">
                <div class="relative size-[20rem] h-[8rem] ...">
                    <p class="text-left">Purwokerto,
                        {{ \Carbon\Carbon::parse($data->created_at)->locale('id')->isoFormat('D MMMM Y') }} </p>
                    <p class="text-left mb-0 ml-3">Kepada</p>
                    <p class="text-left mb-0">Yth. Dinas Kesehatan Kab. Banyumas</p>
                    <p class="text-left mb-0 ml-3">di</p>
                    <p class="text-left mb-0">Purwokerto</p>
                </div>
            </div>
        </div>
        <div class="text-center">
            <h2 class="font-bold">FORMULIR PERMINTAAN DAN PEMBERIAN CUTI</h2>
            <h2 class="font-semibold">NOMOR : / {{ $data->id }} /
                {{ \Carbon\Carbon::parse($data->created_at)->locale('id')->isoFormat('Y') }}</h2>
        </div>

        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left border border-black">
                <tbody>
                    <tr class="bg-white border border-black">
                        <td class="px-2 py-[0.15rem]" colspan="4">
                            <h2 class="font-bold">I. DATA PEGAWAI</h2>
                        </td>
                    </tr>
                    <tr class="bg-white border border-black">
                        <td class="px-2 py-[0.15rem] border border-black">Nama</td>
                        <td class="px-2 py-[0.15rem] border border-black">
                            {{ $data->sisaCuti->gelar_d }} {{ $data->sisaCuti->nama }} {{ $data->sisaCuti->gelar_b }}
                        </td>
                        <td class="px-2 py-[0.15rem] border border-black">NIP</td>
                        <td class="px-2 py-[0.15rem] border border-black">{{ $data->sisaCuti->nip }}</td>
                    </tr>
                    <tr class="bg-white border border-black">
                        <td class="px-2 py-[0.15rem] border border-black">Jabatan</td>
                        <td class="px-2 py-[0.15rem] border border-black">{{ $data->sisaCuti->nm_jabatan }} -
                            {{ $data->sisaCuti->nmJenjang }}</td>
                        <td class="px-2 py-[0.15rem] border border-black">Masa Kerja</td>
                        <td class="px-2 py-[0.15rem] border border-black">
                            <input type="text" class="text-center w-5"> Tahun
                            <input type="text" class="text-center w-5"> Bulan
                        </td>
                    </tr>
                </tbody>
            </table>
            <table class="w-full text-sm text-left border border-t-0 border-black">
                <tbody>
                    <tr class="bg-white border border-t-0 border-black">
                        <td class="px-2 py-[0.15rem]" colspan="4">
                            <h2 class="font-bold">II. JENIS CUTI YANG DIAMBIL **</h2>
                        </td>
                    </tr>
                    <tr class="bg-white border border-black">
                        <td class="px-2 py-[0.15rem] border border-black text-nowrap">1. Cuti Tahunan</td>
                        <td class="px-2 py-[0.15rem] border border-black w-16 text-center">
                            @if ($data->alasan == 'Cuti Tahunan')
                                <strong class="text-red-600">✓</strong>
                            @endif
                        </td>
                        <td class="px-2 py-[0.15rem] border border-black text-nowrap">2. Cuti Besar</td>
                        <td class="px-2 py-[0.15rem] border border-black w-16 text-center">
                            @if ($data->alasan == 'Cuti Besar')
                                <strong class="text-red-600">✓</strong>
                            @endif
                        </td>
                    </tr>
                    <tr class="bg-white border border-black">
                        <td class="px-2 py-[0.15rem] border border-black text-nowrap">3. Cuti Sakit</td>
                        <td class="px-2 py-[0.15rem] border border-black w-10 text-center">
                            @if ($data->alasan == 'Cuti Sakit')
                                <strong class="text-red-600">✓</strong>
                            @endif
                        </td>
                        <td class="px-2 py-[0.15rem] border border-black text-nowrap">4. Cuti Melahirkan</td>
                        <td class="px-2 py-[0.15rem] border border-black w-10 text-center">
                            @if ($data->alasan == 'Cuti Melahirkan')
                                <strong class="text-red-600">✓</strong>
                            @endif
                        </td>
                    </tr>
                    <tr class="bg-white border border-black">
                        <td class="px-2 py-[0.15rem] border border-black text-nowrap">5. Cuti karena Alasan Penting</td>
                        <td class="px-2 py-[0.15rem] border border-black w-16 text-center">
                            @if ($data->alasan == 'Cuti Penting')
                                <strong class="text-red-600">✓</strong>
                            @endif
                        </td>
                        <td class="px-2 py-[0.15rem] border border-black text-nowrap">6. Cuti di Luar Tanggungan Negara
                        </td>
                        <td class="px-2 py-[0.15rem] border border-black w-16 text-center">
                            @if ($data->alasan == 'Cuti Luar Negara')
                                <strong class="text-red-600">✓</strong>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <table class="w-full text-sm text-left">
                <tbody>
                    <tr class="bg-white border border-t-0 border-black">
                        <td class="px-2 py-[0.15rem]" colspan="4">
                            <h2 class="font-bold">III. ALASAN CUTI</h2>
                        </td>
                    </tr>
                    <tr class="bg-white border border-t-0 border-black">
                        <td class="px-2 py-[0.15rem] pl-8" colspan="4">
                            <p>{{ $data->keterangan }}</p>
                        </td>
                    </tr>
                    <tr class="bg-white border border-t-0 border-black">
                        <td class="px-2 py-[0.15rem]" colspan="4">
                            <h2 class="font-bold">IV. LAMANYA CUTI</h2>
                        </td>
                    </tr>
                    <tr class="bg-white border border-black">
                        @php
                            use Carbon\Carbon;

                            $start = Carbon::parse($data->tgl_mulai);
                            $end = Carbon::parse($data->tgl_selesai);
                            // $start = Carbon::parse('2025-06-29');
                            // $end = Carbon::parse('2025-07-02');
                            $startCopy = $start->copy();

                            $jumlahHari = 0;
                            while ($start->lte($end)) {
                                if ($start->dayOfWeek != Carbon::SUNDAY) {
                                    $jumlahHari++;
                                }
                                $start->addDay();
                            }
                        @endphp

                        <td class="px-2 py-[0.15rem] border border-black w-36 text-nowrap">
                            Selama <input type="text" value="{{ $jumlahHari }}" class="w-6 text-center"> hari
                        </td>

                        <td class="px-2 py-[0.15rem] border border-black text-nowrap">Mulai Tgl</td>

                        <td class="px-2 py-[0.15rem] border border-black text-center">
                            @if ($data->alasan == 'Cuti Tahunan' || $data->alasan == 'Cuti Sakit')
                                @php
                                    $tanggalList = [];
                                    $bulan = null;
                                    for ($date = $startCopy->copy(); $date <= $end; $date->addDay()) {
                                        if (!$date->isSunday()) {
                                            $tanggalList[] = $date->format('d');
                                            $bulan = $date->locale('id')->isoFormat('MMMM Y');
                                        }
                                    }
                                    echo implode(', ', $tanggalList) . ' ' . $bulan;
                                @endphp
                            @else
                                {{ \Carbon\Carbon::parse($data->tgl_mulai)->locale('id')->isoFormat('D MMMM Y') }}
                            @endif
                        </td>

                        <td class="px-2 py-[0.15rem] border border-black w-8 text-nowrap">
                            @if ($data->alasan == 'Cuti Tahunan' || $data->alasan == 'Cuti Sakit')
                                dan
                            @else
                                sampai Tgl
                            @endif
                        </td>

                        {{-- Tampilkan tanggal bulan berbeda jika rentang mencakup lebih dari satu bulan --}}
                        <td class="px-2 py-[0.15rem] min-w-16 border border-black text-center">
                            @if ($data->alasan == 'Cuti Tahunan' || $data->alasan == 'Cuti Sakit')
                                @php
                                    $datesInLastMonth = [];
                                    $lastMonth = null;

                                    // Ambil tanggal aktif (bukan hari Minggu)
                                    $filteredDates = collect();
                                    for ($d = $startCopy->copy(); $d <= $end; $d->addDay()) {
                                        if (!$d->isSunday()) {
                                            $filteredDates->push($d->copy());
                                        }
                                    }

                                    // Cek apakah ada lebih dari 1 bulan
                                    $months = $filteredDates->map(fn($d) => $d->format('m'))->unique();
                                    if ($months->count() > 1) {
                                        $lastMonth = $filteredDates->last()->format('m');
                                        $bulan = $filteredDates->last()->locale('id')->isoFormat('MMMM Y');
                                        $datesInLastMonth = $filteredDates
                                            ->filter(fn($d) => $d->format('m') === $lastMonth)
                                            ->map(fn($d) => $d->format('d'))
                                            ->toArray();

                                        echo implode(', ', $datesInLastMonth) . ' ' . $bulan;
                                    } else {
                                        echo '-';
                                    }
                                @endphp
                            @else
                                {{ \Carbon\Carbon::parse($data->tgl_selesai)->locale('id')->isoFormat('D MMMM Y') }}
                            @endif
                        </td>

                    </tr>

                </tbody>
            </table>

            <table class="w-full text-sm text-left">
                <tbody>
                    <tr class="bg-white border border-t-0 border-black">
                        <td class="px-2 py-[0.15rem]" colspan="5">
                            <h2 class="font-bold">V. CATATAN CUTI ***</h2>
                        </td>
                    </tr>
                    <tr class="bg-white border border-black">
                        <td class="px-2 py-[0.15rem] border border-black text-nowrap w-64" colspan="3">1. Cuti
                            Tahunan</td>
                        <td class="px-2 py-[0.15rem] border border-black text-nowrap">2. Cuti Besar</td>
                        <td class="px-2 py-[0.15rem] border border-black w-16 text-center">
                            @if ($data->alasan == 'Cuti Besar')
                                <strong class="text-red-600">✓</strong>
                            @endif
                        </td>
                    </tr>
                    <tr class="bg-white border border-black">
                        <td class="px-2 py-[0.15rem] border border-black w-10 text-center">Tahun</td>
                        <td class="px-2 py-[0.15rem] border border-black w-10 text-center">Sisa</td>
                        <td class="px-2 py-[0.15rem] border border-black w-10 text-center">Ket</td>
                        <td class="px-2 py-[0.15rem] border border-black text-left"> 3. Cuti Sakit </td>
                        <td class="px-2 py-[0.15rem] border border-black w-16 text-center">
                            @if ($data->alasan == 'Cuti Sakit')
                                <strong class="text-red-600">✓</strong>
                            @endif
                        </td>
                    </tr>
                    <tr class="bg-white border border-black">
                        <td class="px-2 py-[0.15rem] border border-black text-center"><input
                                value=" {{ \Carbon\Carbon::parse($data->created_at)->locale('id')->isoFormat('Y') }}"
                                type="text" class="w-full text-center"></td>
                        <td class="px-2 py-[0.15rem] border border-black text-center"><input
                                value="{{ $sisaCuti->sisaCuti }}" type="text" class="w-full text-center"></td>
                        <td class="px-2 py-[0.15rem] border border-black text-center"><input value=" "
                                type="text" class="w-full text-center"></td>
                        <td class="px-2 py-[0.15rem] border border-black text-left"> 4. Cuti Melahirkan </td>
                        <td class="px-2 py-[0.15rem] border border-black w-16 text-center">
                            @if ($data->alasan == 'Cuti Melahirkan')
                                <strong class="text-red-600">✓</strong>
                            @endif
                        </td>
                    </tr>
                    <tr class="bg-white border border-black">
                        <td class="px-2 py-[0.15rem] border border-black text-center"><input
                                value=" {{ \Carbon\Carbon::parse($data->created_at)->locale('id')->isoFormat('Y') - 1 }}"
                                type="text" class="w-full text-center"></td>
                        <td class="px-2 py-[0.15rem] border border-black text-center"><input
                                value="{{ $sisaCuti->sisaCuti_1 }}" type="text" class="w-full text-center"></td>
                        <td class="px-2 py-[0.15rem] border border-black text-center"><input value=" "
                                type="text" class="w-full text-center"></td>
                        <td class="px-2 py-[0.15rem] border border-black text-left"> 5. CUTI KARENA ALASAN PENTING
                        </td>
                        <td class="px-2 py-[0.15rem] border border-black w-16 text-center">
                            @if ($data->alasan == 'Cuti Alasan Penting')
                                <strong class="text-red-600">✓</strong>
                            @endif
                        </td>
                    </tr>
                    <tr class="bg-white border border-black">
                        <td class="px-2 py-[0.15rem] border border-black text-center"><input
                                value=" {{ \Carbon\Carbon::parse($data->created_at)->locale('id')->isoFormat('Y') - 2 }}"
                                type="text" class="w-full text-center"></td>
                        <td class="px-2 py-[0.15rem] border border-black text-center"><input
                                value="{{ $sisaCuti->sisaCuti_2 }}" type="text" class="w-full text-center"></td>
                        <td class="px-2 py-[0.15rem] border border-black text-center"><input value=" "
                                type="text" class="w-full text-center"></td>
                        <td class="px-2 py-[0.15rem] border border-black text-left text-nowrap"> 6. CUTI DI LUAR
                            TANGGUNGAN
                            NEGARA </td>
                        <td class="px-2 py-[0.15rem] border border-black w-16 text-center">
                            @if ($data->alasan == 'Cuti Luar Negara')
                                <strong class="text-red-600">✓</strong>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <table class="w-full text-sm text-left">
                <tbody>
                    <tr class="bg-white border border-t-0 border-black">
                        <td class="px-2 py-[0.15rem]" colspan="4">
                            <h2 class="font-bold">VI. ALAMAT SELAMA MENJALANKAN CUTI </h2>
                        </td>
                    </tr>
                    <tr class="bg-white border border-black">
                        <td class="px-2 py-[0.15rem] border border-black w-1/2" colspan="2" rowspan="2">
                            <textarea class="w-full resize-none" rows="6">{{ $data->sisaCuti->alamat }}</textarea>
                        </td>
                        <td class="px-2 py-[0.15rem] border border-black w-10">Telep.</td>
                        <td class="px-2 py-[0.15rem] border border-black" colspan="2">08123456789</td>
                    </tr>
                    <tr class="bg-white border border-black">
                        <td class="px-2 py-[0.15rem] text-center border border-black" colspan="2">
                            <p>Hormat Saya,</p>
                            <div class="h-[4rem]"></div>
                            <p><u>{{ $data->sisaCuti->gelar_d }} {{ $data->sisaCuti->nama }}
                                    {{ $data->sisaCuti->gelar_b }}</u></p>
                            <p>NIP: {{ $data->nip }}</p>
                        </td>
                    </tr>
                </tbody>
            </table>
            {{-- kepala/tu --}}
            @if ($data->pegawai->stat_pns == 'PNS' || $data->pegawai->stat_pns == 'PPPK')
                @include('TataUsaha.Cuti.ttdKepala')
            @else
                @include('TataUsaha.Cuti.ttdTu')
            @endif

            {{-- kepala/Sekdin --}}

            @if ($data->pegawai->stat_pns == 'PNS' || $data->pegawai->stat_pns == 'PPPK')
                @include('TataUsaha.Cuti.ttdSekdin')
            @else
                @include('TataUsaha.Cuti.ttdKepala')
            @endif
        </div>

    </div>
</body>
