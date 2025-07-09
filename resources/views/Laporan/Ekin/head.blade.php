<table class="table table-borderless mb-7" width="100%" style="color: black;">
    <tbody>
        <tr>
            <td colspan="2" width="20%">
                <div>
                    <img src="{{ asset('img/banyumas.png') }}" style="width: 90px; display: block; margin: auto;">
                </div>
            </td>
            <td colspan="2" width="60%">
                <p style="font-size: 17px; margin-bottom: -5px; text-align: center; margin-top: 0px;">
                    PEMERINTAH KABUPATEN BANYUMAS
                </p>
                <p style="font-size: 20px; margin-bottom: -5px; text-align: center; margin-top: 0px;">
                    DINAS KESEHATAN
                </p>
                <p
                    style="font-size: 17px; margin-bottom: -5px; text-align: center; margin-top: 0px; font-weight: bold;">
                    KLINIK UTAMA KESEHATAN PARU MASYARAKAT KELAS A
                </p>
                <p style="font-size:12px; margin-bottom: -5px; text-align: center; margin-top: 0px;">
                    Jln. A. Yani Nomor 33 Purwokerto Timur, Banyumas, Jawa Tengah
                </p>
                <p style="font-size:12px; margin-bottom: -5px; text-align: center; margin-top: 0px;">
                    Kode Pos 53111, Telepon (0281) 635658, Pos-el bkpm_purwokerto@yahoo.com
                </p>
            </td>
            <td colspan="2" width="20%" class="text-center">
                <div>
                    <img src="{{ asset('img/LOGO_KKPM.png') }}" style="width: 90px; display: block; margin: auto;">
                </div>
            </td>

        </tr>
        <tr>
            <td colspan="6" class="pt-0">
                <hr style="margin-top: 2px; margin-bottom: 0px; border: 2px solid black">
                <hr style="margin-top: 3px; margin-bottom: 0px; border: 0.5px solid black">
            </td>
        </tr>
        <tr>
            <td colspan="6" class="p-0">
                @php
                    $namaBulan = $tgl->locale('id')->translatedFormat('F'); // Contoh: "Maret"
                    $tahun = $tgl->year;
                @endphp

                <p
                    style="font-size: 20px; margin-bottom: -5px; text-align: center; padding:0;margin-top: 7px; font-weight: bold;">
                    Laporan Jumlah Pelayanan Bulan {{ $namaBulan }} Tahun {{ $tahun }}
                </p>
            </td>
        </tr>
    </tbody>
</table>
<table class="mx-4" width="100%">
    <tbody>
        <tr>
            <td width="20%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">Nama</td>
            <td width="80%" class="my-0 py-0" style=" text-align: left;">:
                <input type="text" name="nama" id="nama" value="{{ $biodata['nama'] ?? '-' }}"
                    style="border: none;width: 500px;">
            </td>
        </tr>
        <tr>
            <td width="20%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">NIP</td>
            <td width="80%" class="my-0 py-0" style=" text-align: left;">:
                <input type="text" name="nip" value="{{ $biodata['nip'] ?? '-' }}" id="nip"
                    style="border: none;width: 500px;">
            </td>
        </tr>
        <tr>
            <td width="20%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">Pangkat/Gol.
                Ruang</td>
            <td width="80%" class="my-0 py-0" style=" text-align: left;">:
                <input type="text" name="pangkat" id="pangkat" value="{{ $biodata['pangkat'] ?? '-' }}"
                    style="border: none;width: 500px;">
            </td>
        </tr>
        <tr>
            <td width="20%" class="my-0 py-0" style=" font-weight: bold; text-align: left;">Jabatan</td>
            <td width="80%" class="my-0 py-0" style=" text-align: left;">:
                <input type="text" name="jabatan" id="jabatan"
                    value="{{ $biodata['jabatan'] ?? '-' }} {{ $biodata['jenjang'] ?? '-' }}"
                    style="border: none;width: 500px;">
            </td>
        </tr>
    </tbody>
</table>
