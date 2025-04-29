<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Pasien</title>
    <!-- Link ke file CSS yang sudah di-compile (Tailwind CSS) -->
    {{-- @vite('resources/css/app.css') --}}

    <!-- Google Font: Source Sans Pro -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@300;700;900&display=swap" rel="stylesheet">
    <!-- Link ke file JS (untuk interaksi lainnya jika diperlukan) -->
    {{-- <script src="{{ mix('js/app.js') }}"></script> --}}
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background: url("{{ asset('img/halaman kkpm.jpg') }}") no-repeat center center fixed;
            /* background-size: cover; */
            background-size: 100% 100%;
            margin: 0;
            padding: 0;
        }
    </style>
</head>

<body class="bg-gray-100 font-sans antialiased">
    <fieldset class="bg-gray-100 bg-opacity-70 rounded-lg p-8 mx-auto max-w-3xl">
        <h2 class="text-2xl text-center font-bold mb-2">Reservasi Pasien Baru</h2>
        <h3 class="text-lg text-center text-gray-600 mb-6">Reservasi untuk pasien baru di Klinik Utama Kesehatan Paru
            Masyarakat
            Kelas A Kab. banyumas</h3>

        <div class="mb-4">
            <label for="inputNik" class="block text-gray-700 font-medium mb-1">NIK</label>
            <input type="text" id="inputNik" name="nik" placeholder="No. Induk Kependudukan"
                class="w-full border rounded px-3 py-2 inputmask" data-inputmask="'mask': '9999999999999999'">
        </div>

        <div class="mb-4">
            <label for="inputNama" class="block text-gray-700 font-medium mb-1">Nama Lengkap</label>
            <input type="text" id="inputNama" name="nama_pasien" placeholder="Nama Lengkap"
                class="w-full border rounded px-3 py-2">
        </div>

        <div class="mb-4">
            <label for="inputTempatlahir" class="block text-gray-700 font-medium mb-1">Tempat Lahir</label>
            <input type="text" id="inputTempatlahir" name="tempat_lahir" placeholder="Tempat Lahir"
                class="w-full border rounded px-3 py-2">
        </div>

        <div class="mb-4">
            <label for="inputTglLahir" class="block text-gray-700 font-medium mb-1">Tgl. Lahir</label>
            <input type="text" id="inputTglLahir" name="tgl_lahir" placeholder="Tgl. Lahir (ddmmyyyy)"
                class="w-full border rounded px-3 py-2 date inputmask" data-inputmask="'alias': 'dd/mm/yyyy'">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2">Jenis Kelamin</label>
            <div class="flex items-center gap-4">
                <div class="flex items-center">
                    <input type="radio" name="inputGender" id="inputGenderP" value="P" required class="mr-2">
                    <label for="inputGenderP" class="text-gray-700">Perempuan</label>
                </div>
                <div class="flex items-center">
                    <input type="radio" name="inputGender" id="inputGenderL" value="L" required class="mr-2">
                    <label for="inputGenderL" class="text-gray-700">Laki-laki</label>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <label for="selectStatusPerkawinan" class="block text-gray-700 font-medium mb-1">Status Perkawinan</label>
            <select id="selectStatusPerkawinan" name="inputStatusPerkawinan" class="w-full border rounded px-3 py-2">
                <option value="">[Pilih Status Perkawinan]</option>
                <option value="1">KAWIN</option>
                <option value="2">BELUM KAWIN</option>
                <option value="5">TIDAK TAHU</option>
                <option value="4">JANDA</option>
                <option value="3">DUDA</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="selectAgama" class="block text-gray-700 font-medium mb-1">Agama</label>
            <select id="selectAgama" name="inputAgama" class="w-full border rounded px-3 py-2">
                <option value="">[Pilih Agama]</option>
                <option value="1">ISLAM</option>
                <option value="2">KRISTEN</option>
                <option value="3">KATHOLIK</option>
                <option value="4">HINDU</option>
                <option value="5">BUDHA</option>
                <option value="6">TIDAK TAHU</option>
                <option value="7">ATHEIS</option>
                <option value="8">KONG HU CU</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="inputNohp" class="block text-gray-700 font-medium mb-1">No. Handphone</label>
            <input type="number" id="inputNohp" name="no_telp" placeholder="Nomor HP atau Whatsapp"
                class="w-full border rounded px-3 py-2">
        </div>

        <div class="mb-4">
            <label for="inputAlamat" class="block text-gray-700 font-medium mb-1">Alamat</label>
            <input type="text" id="inputAlamat" name="alamat" placeholder="Alamat lengkap"
                class="w-full border rounded px-3 py-2">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-1">RT/RW</label>
            <div class="flex gap-4">
                <input type="text" id="inputRt" name="inputRt" placeholder="RT" maxlength="3"
                    class="w-1/2 border rounded px-3 py-2">
                <input type="text" id="inputRw" name="inputRw" placeholder="RW" maxlength="3"
                    class="w-1/2 border rounded px-3 py-2">
            </div>
        </div>

        <div class="mb-4">
            <label for="selectPropinsi" class="block text-gray-700 font-medium mb-1">Propinsi</label>
            <select id="selectPropinsi"
                onchange="loadListKabupaten($('#selectPropinsi option:selected').val(),'#selectKabupaten')"
                name="inputPropinsi"
                class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">[Pilih Propinsi]</option>
                <option value="11">Aceh</option>
                <option value="12">Sumatera Utara</option>
                <option value="13">Sumatera Barat</option>
                <option value="14">Riau</option>
                <option value="15">Jambi</option>
                <option value="16">Sumatera Selatan</option>
                <option value="17">Bengkulu</option>
                <option value="18">Lampung</option>
                <option value="19">Kepulauan Bangka Belitung</option>
                <option value="21">Kepulauan Riau</option>
                <option value="31">Dki Jakarta</option>
                <option value="32">Jawa Barat</option>
                <option value="33">Jawa Tengah</option>
                <option value="34">Di Yogyakarta</option>
                <option value="35">Jawa Timur</option>
                <option value="36">Banten</option>
                <option value="51">Bali</option>
                <option value="52">Nusa Tenggara Barat</option>
                <option value="53">Nusa Tenggara Timur</option>
                <option value="61">Kalimantan Barat</option>
                <option value="62">Kalimantan Tengah</option>
                <option value="63">Kalimantan Selatan</option>
                <option value="64">Kalimantan Timur</option>
                <option value="65">Kalimantan Utara</option>
                <option value="71">Sulawesi Utara</option>
                <option value="72">Sulawesi Tengah</option>
                <option value="73">Sulawesi Selatan</option>
                <option value="74">Sulawesi Tenggara</option>
                <option value="75">Gorontalo</option>
                <option value="76">Sulawesi Barat</option>
                <option value="81">Maluku</option>
                <option value="82">Maluku Utara</option>
                <option value="99">Tidak Tahu</option>
                <option value="91">Papua Barat</option>
                <option value="94">Papua</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="selectKabupaten" class="block text-gray-700 font-medium mb-1">Kabupaten</label>
            <select id="selectKabupaten"
                onchange="loadListKecamatan($('#selectKabupaten option:selected').val(),'#selectKecamatan')"
                name="inputKabupaten"
                class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">---</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="selectKecamatan" class="block text-gray-700 font-medium mb-1">Kecamatan</label>
            <select id="selectKecamatan"
                onchange="loadListKelurahan($('#selectKecamatan option:selected').val(),'#selectKelurahan')"
                name="inputKecamatan"
                class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">---</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="selectKelurahan" class="block text-gray-700 font-medium mb-1">Kelurahan</label>
            <select id="selectKelurahan" name="inputKelurahan"
                class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">---</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="selectPendidikan" class="block text-gray-700 font-medium mb-1">Pendidikan</label>
            <select id="selectPendidikan" name="inputPendidikan"
                class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">[Pilih Pendidikan]</option>
                <option value="1">S1</option>
                <option value="2">D3</option>
                <option value="3">SMU</option>
                <option value="4">SMP</option>
                <option value="5">SD</option>
                <option value="6">D1</option>
                <option value="7">S2</option>
                <option value="8">S3</option>
                <option value="null">Tidak Sekolah</option>
                <option value="9">Tidak Tahu</option>
                <option value="10">Tmt Akdm</option>
                <option value="11">D2</option>
                <option value="12">D4</option>
                <option value="13">Tdk Tmt SD</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="selectPekerjaan" class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan</label>
            <select id="selectPekerjaan" name="inputPekerjaan"
                class="w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                <option value="">[Pilih Pekerjaan]</option>
                <option value="29">Pegawai Kementerian Kesehatan</option>
                <option value="01">Pegawai Swasta</option>
                <option value="02">Pegawai Negeri</option>
                <option value="03">Wiraswasta</option>
                <option value="04">Ibu Rumah Tangga</option>
                <option value="05">Pensiunan</option>
                <option value="06">Petani</option>
                <option value="07">Pedagang</option>
                <option value="08">Nelayan</option>
                <option value="09">Pelajar</option>
                <option value="10">Mahasiswa</option>
                <option value="11">Tidak Bekerja</option>
                <option value="12">Dokter</option>
                <option value="13">Veteran</option>
                <option value="14">BUMN</option>
                <option value="15">TNI/ POLRI</option>
                <option value="16">Pek Lepas/ Buruh</option>
                <option value="17">Profesional</option>
                <option value="18">Belum Tahu</option>
                <option value="19">Dibawah Umur</option>
                <option value="20">Konsultan</option>
                <option value="21">Pamong</option>
                <option value="22">Dosen</option>
                <option value="23">HONORER</option>
                <option value="24">Guru</option>
                <option value="25">Gubernur Jateng</option>
                <option value="26">Wakil Gubernur Jateng</option>
                <option value="27">Belum Bekerja</option>
                <option value="28">Presiden</option>
            </select>
        </div>

        <div id="selectPegawaiKemenkes" class="mb-4 hidden">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                Anda Memilih Sebagai <b>Pegawai Kemenkes</b>, <br>Mohon Membawa <b>Kartu Kepegawaian</b> Saat Verifikasi
                Identitas Pada Kunjungan Pertama Anda.
            </div>
        </div>

        <div class="mb-4">
            <label for="selectKeterbatasanFisik" class="block text-sm font-medium text-gray-700 mb-1">Keterbatasan
                Fisik</label>
            <select id="selectKeterbatasanFisik" name="inputKeterbatasanFisik"
                onchange="changeKeterbatasanFisik(this.value, '.viewSelectKeterbatasanFisikLainnya')"
                class="w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                <option value="">[Pilih Keterbatasan Fisik]</option>
                <option value="99">Tidak Ada</option>
                <option value="1">Bisu</option>
                <option value="2">Tuli</option>
                <option value="3">Buta</option>
                <option value="98">Lainnya</option>
            </select>
        </div>

        <div class="mb-4 viewSelectKeterbatasanFisikLainnya hidden">
            <label for="inputKeterbatasanFisikLainnya"
                class="block text-sm font-medium text-gray-700 mb-1">&nbsp;</label>
            <input type="text" id="inputKeterbatasanFisikLainnya" name="inputKeterbatasanFisikLainnya"
                placeholder="Keterbatasan Fisik Lainnya"
                class="w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
        </div>

        <div class="mb-4">
            <label for="selectCaraMasuk" class="block text-sm font-medium text-gray-700 mb-1">Asal Pasien</label>
            <select id="selectCaraMasuk" name="caraMasuk"
                class="w-full rounded border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                <option value="">[Pilih Asal Pasien]</option>
                <option value="01">Datang Sendiri</option>
                <option value="08">Rujukan Klinik Pratama /Dokter Keluarga/ Puskesmas</option>
                <option value="09">Rujukan RS Non Pemerintah</option>
                <option value="10">Rujukan RS Pemerintah</option>
            </select>
        </div>


        <div class="space-y-6">
            <div>
                <h3 class="text-xl font-semibold text-gray-800">Penanggung Jawab Pasien</h3>
                <p class="text-sm text-gray-500">Lengkapi data penanggung jawab pasien berikut ini.</p>
            </div>

            <div class="space-y-4">
                <!-- Penanggung Jawab -->
                <div>
                    <label for="selectPJPasien" class="block text-sm font-medium text-gray-700 mb-1">Penanggung
                        Jawab</label>
                    <select id="selectPJPasien" name="inputPJPasien"
                        onchange="changePJPasien(this.value, '.viewSelectPJPasienLainnya')"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">[Pilih Penanggung Jawab]</option>
                        <option value="AYAH">AYAH</option>
                        <option value="IBU">IBU</option>
                        <option value="SUAMI">SUAMI</option>
                        <option value="ISTRI">ISTRI</option>
                        <option value="ANAK">ANAK</option>
                        <option value="LAINNYA">LAINNYA</option>
                    </select>
                </div>

                <!-- Penanggung Jawab Lainnya -->
                <div class="viewSelectPJPasienLainnya hidden">
                    <label for="inputPJPasienLainnya" class="block text-sm font-medium text-gray-700 mb-1">Penanggung
                        Jawab Lainnya</label>
                    <input type="text" id="inputPJPasienLainnya" name="inputPJPasienLainnya"
                        placeholder="Lainnya, contoh: SAUDARA"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Nama Lengkap -->
                <div>
                    <label for="inputNamaPJPasien" class="block text-sm font-medium text-gray-700 mb-1">Nama
                        Lengkap</label>
                    <input type="text" id="inputNamaPJPasien" name="inputNamaPJPasien"
                        placeholder="Nama lengkap penanggung jawab"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Pendidikan -->
                <div>
                    <label for="selectPJPendidikan"
                        class="block text-sm font-medium text-gray-700 mb-1">Pendidikan</label>
                    <select id="selectPJPendidikan" name="inputPJPendidikan"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">[Pilih Pendidikan]</option>
                        <option value="1">S1</option>
                        <option value="2">D3</option>
                        <option value="3">SMU</option>
                        <option value="4">SMP</option>
                        <option value="5">SD</option>
                        <option value="6">D1</option>
                        <option value="7">S2</option>
                        <option value="8">S3</option>
                        <option value="null">Tidak Sekolah</option>
                        <option value="9">Tidak Tahu</option>
                        <option value="10">Tmt Akdm</option>
                        <option value="11">D2</option>
                        <option value="12">D4</option>
                        <option value="13">Tdk Tmt SD</option>
                    </select>
                </div>

                <!-- Pekerjaan -->
                <div>
                    <label for="selectPJPekerjaan"
                        class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan</label>
                    <select id="selectPJPekerjaan" name="inputPJPekerjaan"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">[Pilih Pekerjaan]</option>
                        <option value="29">Pegawai Kementerian Kesehatan</option>
                        <option value="01">Pegawai Swasta</option>
                        <option value="02">Pegawai Negeri</option>
                        <option value="03">Wiraswasta</option>
                        <option value="04">Ibu Rumah Tangga</option>
                        <option value="05">Pensiunan</option>
                        <option value="06">Petani</option>
                        <option value="07">Pedagang</option>
                        <option value="08">Nelayan</option>
                        <option value="09">Pelajar</option>
                        <option value="10">Mahasiswa</option>
                        <option value="11">Tidak Bekerja</option>
                        <option value="12">Dokter</option>
                        <option value="13">Veteran</option>
                        <option value="14">BUMN</option>
                        <option value="15">TNI/ POLRI</option>
                        <option value="16">Pek Lepas/ Buruh</option>
                        <option value="17">Profesional</option>
                        <option value="18">Belum Tahu</option>
                        <option value="19">Dibawah Umur</option>
                        <option value="20">Konsultan</option>
                        <option value="21">Pamong</option>
                        <option value="22">Dosen</option>
                        <option value="23">HONORER</option>
                        <option value="24">Guru</option>
                        <option value="25">Gubernur Jateng</option>
                        <option value="26">Wakil Gubernur Jateng</option>
                        <option value="27">Belum Bekerja</option>
                        <option value="28">Presiden</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- <div class="form-group">
        <label for="inputEmail" class="col-lg-3 control-label">E-mail</label>
        <div class="col-lg-9">
            <input type="email" class="form-control" name="email" value="" id="inputEmail" />
        </div>
    </div> -->
        <div class="form-group">
            <div class="col-lg-12 messengger"></div>
        </div>
        <button type="button" name="next" id="step1" class="action-button">Lanjut</button>
    </fieldset>

</body>

</html>
