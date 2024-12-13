@extends('Template.lte')

@section('content')
    {{-- Data per pasien --}}
    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-start">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Surat Keterangan Medis</h6>
        </div>
        <div class="card-body mb-2">
            <div class="row">
                <label class="col-form-label">Tanggal :</label>
                <div class="form-group col-2">
                    <input type="date" id="tanggal" class="form-control bg-white" placeholder="Tanggal" />
                </div>
                <div class="col">
                    <button type="button" class="btn btn-success" onclick="antrianAll('surat');">
                        Segarkan
                        <span class="fa-solid fa-rotate" data-toggle="tooltip" data-placement="top" title="Update Data"
                            id="cariantrian"></span>
                    </button>
                </div>

            </div>
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title"><b>Daftar Pasien Hari ini</b></h3>
                </div>
                <div class="card-body p-2">
                    @include('Template.Table.all')
                </div>
            </div>
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title"><b>List Surat Keterangan Medis</b></h3>
                </div>
                <div class="card-body p-2">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover dataTable dtr-inline" id="dataPemohon" width="100%"
                            cellspacing="0">
                            <thead class="bg bg-teal table-bordered border-warning">
                                <tr>
                                    <th class="text-center">NO</th>
                                    <th class="text-center">aksi</th>
                                    <th class="text-center">No Surat</th>
                                    <th class="text-center">Tanggal</th>
                                    <th class="text-center">NoRM</th>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">Tgl Lahir</th>
                                    <th class="text-center">NIK</th>
                                    <th class="text-center">Alamat</th>
                                    <th class="text-center">Keterangan Surat</th>
                                    <th class="text-center">Dokter</th>
                                </tr>
                            </thead>
                            <tbody class="table-bordered border-warning">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade show" id="modalCreateSurat" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="modalCreateSuratLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCreateSuratLabel">Formulir Pembuatan Surat</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <form class="form-horizontal" id="form_identitas">
                        <div class="card-body" id="inputSection">
                            <div class="row">
                                <div class="form-grup col-6 col-md-3">
                                    <label for="notrans" class="col-form-label font-weight-bold mb-0">NoTran
                                        :</label>
                                    <input type="text" id="notrans" class="form-control bg-white"
                                        placeholder="Nomor Transaksi" readonly required />
                                </div>
                                <div class="form-grup col-6 col-md-3">
                                    <label for="norm" class="col-form-label font-weight-bold mb-0 ">No RM
                                        :</label>
                                    <input type="text" name="norm" id="norm" class="form-control"
                                        placeholder="No RM" maxlength="6" pattern="[0-9]{6}" required readonly />
                                </div>
                                <div class="form-grup col-6 col-md-3">
                                    <label for="layanan" class="col-form-label font-weight-bold mb-0">Layanan
                                        :</label>
                                    <input type="text" id="layanan" class="form-control bg-white" placeholder="Layanan"
                                        readonly />
                                </div>

                                <div class="form-grup col-6 col-md-3">
                                    <label for="nama" class="col-form-label font-weight-bold  mb-0">Gender
                                        :</label>
                                    <Select type="text" id="jk" class="form-control bg-white" placeholder="JK">
                                        <option value="">--JK--</option>
                                        <option value="L">Laki-Laki</option>
                                        <option value="P">Perempuan</option>
                                    </Select>
                                </div>
                                <div class="form-grup col-6 col-md-3">
                                    <label for="nama" class="col-form-label font-weight-bold  mb-0">Umur
                                        :</label>
                                    <input type="text" id="umur" class="form-control bg-white"
                                        placeholder="Umur" readonly />
                                </div>
                                <div class="form-grup col-6 col-md-3">
                                    <label for="tglLahir" class="col-form-label font-weight-bold mb-0">Tgl Lahir
                                        :</label>
                                    <input type="date" id="tglLahir" class="form-control bg-white"
                                        placeholder="Tanggal Transaksi" />
                                </div>
                                <div class="form-grup col-12 col-md-6">
                                    <label for="nama" class="col-form-label font-weight-bold  mb-0">Nama
                                        :</label>
                                    <input type="text" id="nama" class="form-control bg-white"
                                        placeholder="Nama Pasien" readonly>
                                </div>
                                <div class="form-grup col-12 col-md-6">
                                    <label for="alamat" class="col-form-label font-weight-bold mb-0">Alamat
                                        :</label>
                                    <input id="alamat" class="form-control bg-white" placeholder="Alamat Pasien" />
                                </div>

                                {{-- <div class="form-grup col-12 col-md-12">
                                    <label for="keperluan"
                                        class="col-form-label font-weight-bold mb-0">Keterangan/Keperluan Surat
                                        :</label>
                                    <input id="keperluan" class="form-control bg-white"
                                        placeholder="Keterangan/Keperluan Surat" />
                                </div> --}}
                                <div class="form-group col-12 col-md-6">
                                    <label for="keperluan" class="col-form-label font-weight-bold mb-0">
                                        Keterangan/Keperluan Surat:
                                    </label>
                                    <select id="keperluan" class="form-control border border-primary">
                                        <option value="">Pilih Keterangan/Keperluan Surat...</option>
                                        <option value="KETERANGAN DIAGNOSA MEDIS">KETERANGAN DIAGNOSA MEDIS</option>
                                        <option value="KETERANGAN UNTUK BEKERJA">KETERANGAN UNTUK BEKERJA</option>
                                        <option value="KETERANGAN SEHAT UNTUK SEKOLAH">KETERANGAN SEHAT UNTUK SEKOLAH
                                        </option>
                                        <option value="KETERANGAN SAKIT UNTUK PENGAJUAN KIS">KETERANGAN SAKIT UNTUK
                                            PENGAJUAN KIS</option>
                                        <option value="KETERANGAN SEHAT UNTUK LATSAR">KETERANGAN SEHAT UNTUK LATSAR
                                        </option>
                                        <option value="KETERANGAN SELESAI PENGOBATAN TBC">KETERANGAN SELESAI PENGOBATAN TBC
                                        </option>
                                        <option value="PEMBUATAN SIP">PEMBUATAN SIP</option>
                                    </select>

                                </div>

                            </div>
                            <div class="row">
                                <div class="form-group col-12 col-md-2">
                                    <label for="tgltrans" class="col-form-label font-weight-bold mb-0">Tanggal
                                        :</label>
                                    <input type="date" id="tgltrans" class="form-control bg-white"
                                        placeholder="Tanggal Transaksi" />
                                </div>
                                <div class="form-group col-12 col-md-5">
                                    <label for="petugas" class="col-form-label font-weight-bold">Admin
                                        :</label>
                                    <select id="petugas" class="form-control border border-primary" required>
                                        <option value="">--Pilih Petugas--</option>
                                        @foreach ($petugas as $item)
                                            <option value="{{ $item->nip }}">{{ $item->gelar_d }}
                                                {{ $item->nama }} {{ $item->gelar_b }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-12 col-md-5">
                                    <label for="dokter" class="col-form-label font-weight-bold">Dokter
                                        :</label>
                                    <select id="dokter" class="form-control mb-3 border border-primary" required>
                                        <option value="">--Pilih Dokter--</option>
                                        @foreach ($dokter as $item)
                                            <option value="{{ $item->nip }}">{{ $item->gelar_d }}
                                                {{ $item->nama }} {{ $item->gelar_b }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal"
                        onclick="validateAndSubmit();">Simpan</button>
                    <button type="button" class="btn btn-danger"data-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>

    <!-- my script -->
    <script src="{{ asset('js/template.js') }}"></script>
    <script src="{{ asset('js/populate.js') }}"></script>
    <script>
        const listSurat = @json($lists);
        const pasien = @json($pasien);
        console.log("🚀 ~ pasien:", pasien)
        const dataPasien = pasien.original;
        console.log("🚀 ~ dataPasien:", dataPasien)
        const jumlahSuratTahunIni = @json($jumlahSuratTahunIni);
        console.log("🚀 ~ jumlahSuratTahunIni:", jumlahSuratTahunIni)
        let noSuratNext = "";

        $(document).ready(function() {
            const date = new Date();
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, "0");
            const day = String(date.getDate()).padStart(2, "0");
            const formattedDate = `${year}-${month}-${day}`;
            $("#tanggal").val(formattedDate);

            //dubah month ke romawi
            const romawi = ["I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII"];
            const monthRomawi = romawi[date.getMonth()];
            $("#dAntrian").show();

            noSuratNext = "440.6/" + jumlahSuratTahunIni + "/" + monthRomawi + "/" + year,
                console.log("🚀 ~ $ ~ noSuratNext:", noSuratNext)
            creatTableDataPemohon();
            // antrianAll("surat");
            const antrian = processResponse(dataPasien, "surat", "Tidak Ada Transaksi");
            const antrianAll = antrian.data;
            console.log("🚀 ~ $ ~ antrianAll:", antrianAll)
            initializeDataTable(
                "#antrianall",
                antrianAll,
                getColumnDefinitions("status_pulang", "surat"),
                "surat"
            );

        });

        function creatTableDataPemohon() {
            listSurat.forEach(function(item, index) {
                item.aksi = `
                            <a type="button" class="btn btn-sm btn-warning mr-2 mb-2"
                                    href="/api/cetak/surat/${item.id}" target="_blank">Cetak Surat</a>
                            `;
            });
            $("#dataPemohon")
                .DataTable({
                    data: listSurat,
                    columns: [{
                            data: "id"
                        },
                        {
                            data: "aksi"
                        },
                        {
                            data: "noSurat"
                        },
                        {
                            data: "tanggal",
                            className: "col-1"
                        },
                        {
                            data: "norm"
                        },
                        {
                            data: "nama"
                        },
                        {
                            data: "tglLahir"
                        },
                        {
                            data: "nik"
                        },
                        {
                            data: "alamat"
                        },

                        {
                            data: "keperluan"
                        },
                        {
                            data: "dokter"
                        },
                    ],
                    lengthMenu: [
                        [5, 10, 25, 50, -1],
                        [5, 10, 25, 50, "All"],
                    ],
                    pageLength: 5,
                    order: [
                        [0, "dsc"],
                    ],
                    buttons: [{
                            extend: "excelHtml5",
                            text: "Excel",
                            title: "Laporan Surat Ket Medis",
                            filename: "Laporan Surat Ket Medis",
                        },
                        {
                            extend: "colvis",
                            text: "Tampilkan Kolom",
                        },
                        // "colvis", // Tombol untuk menampilkan/menyembunyikan kolom
                    ],
                })
                .buttons()
                .container()
                .appendTo("#dataPemohon_wrapper .col-md-6:eq(0)");
        }

        function validateAndSubmit() {
            var inputsToValidate = [
                "norm",
                "layanan",
                "nama",
                "tglTrans",
                "alamat",
                "nik",
                "tglLahir",
                "dokter",
                "keperluan",
            ];

            var error = false;

            inputsToValidate.forEach(function(inputId) {
                var inputElement = document.getElementById(inputId);
                var inputValue = inputElement.value.trim();

                if (inputValue === "") {
                    if ($(inputElement).hasClass("select2-hidden-accessible")) {
                        // Select2 element
                        $(inputElement)
                            .next(".select2-container")
                            .addClass("input-error");
                    } else {
                        // Regular input element
                        inputElement.classList.add("input-error");
                    }
                    error = true;
                } else {
                    if ($(inputElement).hasClass("select2-hidden-accessible")) {
                        // Select2 element
                        $(inputElement)
                            .next(".select2-container")
                            .removeClass("input-error");
                    } else {
                        // Regular input element
                        inputElement.classList.remove("input-error");
                    }
                }
            });
            if (error) {
                var dataKurang = [];
                var norm = $("#norm").val();
                var notrans = $("#notrans").val();
                var nama = $("#nama").val();
                var jaminan = $("#layanan").val();
                var alamat = $("#alamat").val();
                var umur = $("#umur").val();
                var petugas = $("#analis").val();
                var dokter = $("#dokter").val();
                var noSampel = $("#no_sampel").val();
                if (!norm) dataKurang.push("No RM ");
                if (!notrans) dataKurang.push("No Transaksi ");
                if (!nama) dataKurang.push("Nama Pasien ");
                if (!jaminan) dataKurang.push("Jaminan ");
                if (!alamat) dataKurang.push("Alamat ");
                if (!umur) dataKurang.push("Umur ");
                if (!petugas) dataKurang.push("Petugas ");
                if (!dokter) dataKurang.push("Dokter ");
                if (!noSampel) dataKurang.push("No Sampel ");

                Swal.fire({
                    icon: "error",
                    title: "Data Tidak Lengkap...!!! \n\n" +
                        dataKurang.join(", ") +
                        "Belum Diisi",
                });
            } else {
                // Lakukan pengiriman data atau proses selanjutnya jika semua data valid
                simpan(); // Contoh: Panggil fungsi simpan() jika semua data valid
            }
        }

        function simpan() {
            var norm = $("#norm").val();
            var nama = $("#nama").val();
            var alamat = $("#alamat").val();
            var nik = $("#nik").val();
            var jaminan = $("#layanan").val();
            var dokter = $("#dokter").val();
            var tglLahir = $("#tglLahir").val();
            var tanggal = $("#tgltrans").val();
            var noSampel = $("#no_sampel").val();
            var umur = $("#umur").val();
            var jk = $("#jk").val();

            if (!norm || !dokter || !petugas || !noSampel || !umur || !jk) {
                var dataKurang = [];
                if (!norm) dataKurang.push("No RM ");
                if (!notrans) dataKurang.push("Nomor Transaksi ");
                if (!dokter) dataKurang.push("Dokter ");
                if (!petugas) dataKurang.push("Petugas ");
                if (!noSampel) dataKurang.push("No Sampel ");
                if (!umur) dataKurang.push("Umur ");

                Swal.fire({
                    icon: "error",
                    title: "Data Tidak Lengkap...!!! " +
                        dataKurang.join(", ") +
                        "Belum Diisi",
                });
                if (!norm) $("#norm").focus();
                if (!notrans) $("#notrans").focus();
                if (!dokter) $("#dokter").focus();
                if (!petugas) $("#petugas").focus();
                if (!noSampel) $("#no_sampel").focus();
                if (!umur) $("#umur").focus();
                if (!jk) $("#jk").focus();
            } else {
                fetch("/api/addTransaksiLab", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify({
                            tanggal: tanggal,
                            norm: norm,
                            nama: nama,
                            tglLahir: tglLahir,
                            nik: nik,
                            alamat: alamat,
                            keperluan: keperluan,
                            noSurat: noSurat,
                            jaminan: jaminan,
                            petugas: petugas,
                            dokter: dokter,
                        }),
                    })
                    .then((response) => {
                        if (!response.ok) {
                            console.log("Response status:", response.status);
                            console.log("Response status text:", response.message);
                            throw new Error("Network response was not ok");
                        }
                        return response.json();
                    })
                    .then((data) => {
                        console.log(data);
                        var massage = data.message;
                        Swal.fire({
                            icon: "success",
                            title: massage,
                        });
                        var notrans = $("#notrans").val();
                        tampilkanOrder(notrans);
                        $('table thead input[type="checkbox"]').prop("checked", false);
                        $('table tbody input[type="checkbox"]').prop("checked", false);
                    })
                    .catch((error) => {
                        console.error(
                            "There has been a problem with your fetch operation:",
                            error
                        );
                        Swal.fire({
                            icon: "error",
                            title: "There has been a problem with your fetch operation:" +
                                error,
                        });
                    });
            }
        }
    </script>
@endsection
