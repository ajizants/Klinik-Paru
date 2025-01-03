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
    <div class="modal fade show" id="modalCreateSurat" tabindex="-1" aria-labelledby="modalCreateSuratLabel">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCreateSuratLabel">Formulir Pembuatan Surat</h5>
                </div>
                <div class="modal-body">
                    @csrf
                    <form class="form-horizontal" id="form_identitas">
                        <div class="card-body" id="inputSection">
                            <div class="row">
                                <div class="form-grup col-6 col-md-2">
                                    <label for="nik" class="col-form-label font-weight-bold mb-0">NIK
                                        :</label>
                                    <input type="text" id="nik" class="form-control bg-white"
                                        placeholder="Nomor Transaksi" readonly required />
                                </div>
                                <div class="form-grup col-6 col-md-2">
                                    <label for="norm" class="col-form-label font-weight-bold mb-0 ">No RM
                                        :</label>
                                    <input type="text" name="norm" id="norm" class="form-control"
                                        placeholder="No RM" maxlength="6" pattern="[0-9]{6}" required readonly />
                                </div>
                                <div class="form-grup col-6 col-md-2">
                                    <label for="layanan" class="col-form-label font-weight-bold mb-0">Layanan
                                        :</label>
                                    <input type="text" id="layanan" class="form-control bg-white" placeholder="Layanan"
                                        readonly />
                                </div>

                                <div class="form-grup col-6 col-md-2">
                                    <label for="nama" class="col-form-label font-weight-bold  mb-0">Gender
                                        :</label>
                                    <Select type="text" id="jk" class="form-control bg-white" placeholder="JK">
                                        <option value="">--JK--</option>
                                        <option value="L">Laki-Laki</option>
                                        <option value="P">Perempuan</option>
                                    </Select>
                                </div>
                                <div class="form-grup col-6 col-md-2">
                                    <label for="nama" class="col-form-label font-weight-bold  mb-0">Umur
                                        :</label>
                                    <input type="text" id="umur" class="form-control bg-white" placeholder="Umur"
                                        readonly />
                                </div>
                                <div class="form-grup col-6 col-md-2">
                                    <label for="tglLahir" class="col-form-label font-weight-bold mb-0">Tgl Lahir
                                        :</label>
                                    <input type="date" id="tglLahir" class="form-control bg-white"
                                        placeholder="Tanggal Transaksi" />
                                </div>
                                <div class="form-grup col-12 col-md-4">
                                    <label for="nama" class="col-form-label font-weight-bold  mb-0">Nama
                                        :</label>
                                    <input type="text" id="nama" class="form-control bg-white"
                                        placeholder="Nama Pasien" readonly>
                                </div>
                                <div class="form-grup col-12 col-md-4">
                                    <label for="alamat" class="col-form-label font-weight-bold mb-0">Alamat
                                        :</label>
                                    <input id="alamat" class="form-control bg-white" placeholder="Alamat Pasien" />
                                </div>
                                <div class="form-group col-6 col-md-2">
                                    <label for="tgltrans" class="col-form-label font-weight-bold mb-0">TD
                                        :</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="TD" id="td">
                                        <div class="input-group-append">
                                            <span class="input-group-text">mmHg</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-6 col-md-2">
                                    <label for="tgltrans" class="col-form-label font-weight-bold mb-0">BB
                                        :</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="BB" id="bb">
                                        <div class="input-group-append">
                                            <span class="input-group-text">kg</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-6 col-md-2">
                                    <label for="tgltrans" class="col-form-label font-weight-bold mb-0">TB
                                        :</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="TB" id="tb">
                                        <div class="input-group-append">
                                            <span class="input-group-text">cm</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-6 col-md-2">
                                    <label for="tgltrans" class="col-form-label font-weight-bold mb-0">Nadi
                                        :</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Nadi" id="nadi">
                                        <div class="input-group-append">
                                            <span class="input-group-text">x/mnt</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-grup col-12 col-md-4">
                                    <label for="keperluan"
                                        class="col-form-label font-weight-bold mb-0">Keterangan/Keperluan Surat
                                        :</label>
                                    <input id="keperluan" class="form-control bg-white"
                                        placeholder="Keterangan/Keperluan Surat" />
                                </div>
                                <div class="form-group col-12 col-md-4">
                                    <label for="pekerjaan" class="col-form-label font-weight-bold mb-0">Pekerjaan
                                        :</label>
                                    <input type="text" id="pekerjaan" class="form-control bg-white"
                                        placeholder="Tanggal Transaksi" />
                                </div>
                                {{-- <div class="form-group col-12 col-md-6">
                                    <label for="keperluan" class="col-form-label font-weight-bold mb-0">
                                        Keterangan/Keperluan Surat:
                                    </label>
                                    <input type="text" id="keperluan" class="form-control bg-white"/>
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

                                </div> --}}
                                {{-- <div class="form-group col-6 col-md-2">
                                    <label for="tgltrans" class="col-form-label font-weight-bold mb-0">Hasil
                                        :</label>
                                    <div class="input-group">
                                        <select id="hasil" class="form-control border border-primary">
                                            <option value="">--Pilih Hasil--</option>
                                            <option value="Sehat">Sehat</option>
                                            <option value="Sakit">Sakit</option>
                                        </select>
                                    </div>
                                </div> --}}
                                <div class="form-group col-6 col-md-2">
                                    <label for="tgltrans" class="col-form-label font-weight-bold mb-0">Hasil
                                        :</label>
                                    <div class="row pt-2 ml-1">
                                        <input type="radio" name="hasil" id="sehat" value="Sehat" checked>
                                        <label for="sehat" class="mr-4 ml-2 col-form-label">Sehat
                                        </label>
                                        <input type="radio" name="hasil" id="sakit" value="Sakit">
                                        <label for="sakit" class="ml-2 col-form-label">
                                            Sakit
                                        </label>
                                    </div>
                                    {{-- <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="Sehat"
                                            id="defaultCheck1" checked>
                                        <label class="form-check-label" for="defaultCheck1">
                                            Sehat
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="Sakit"
                                            id="defaultCheck2">
                                        <label class="form-check-label" for="defaultCheck2">
                                            Sakit
                                        </label>
                                    </div> --}}
                                </div>
                                {{-- </div>
                            <div class="row"> --}}
                                <div class="form-group col-6 col-md-2">
                                    <label for="tgltrans" class="col-form-label font-weight-bold mb-0">Tgl Surat
                                        :</label>
                                    <input type="date" id="tglTrans" class="form-control bg-white"
                                        placeholder="Tanggal Transaksi" />
                                </div>
                                <div class="form-group col-12 col-md-4">
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
                                <div class="form-group col-12 col-md-4">
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
                                <input type="text" id="noSurat">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="validateAndSubmit();">Simpan</button>
                    <button type="button" class="btn btn-danger"data-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="historiKunjungan">
        <div class="modal-dialog modal-dialog-scrollable modal-xl-custom">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Histori Kunjungan Pasien: <span id="nama_pasien"></span> - <span
                            id="no_rm"></span></h4>
                </div>
                <div class="modal-body">
                    <div class="card-body p-2">
                        <div class="container-fluid">
                            <div class="card card-info">
                                <div class="card-body p-2">

                                    <div class="table-responsive">
                                        <table id="riwayatKunjungan"
                                            class="table table-striped table-hover pt-0 mt-0 fs-6" style="width:100%"
                                            cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th width="15px" class="text-center">Tanggal</th>
                                                    {{-- <th width="15px" class="text-center">NoRM</th>
                                                    <th class="col-2 text-center">Nama</th> --}}
                                                    <th class="col-2 text-center">Dokter</th>
                                                    <th class="col-3">Diagnosa</th>
                                                    <th>Anamnesa</th>
                                                    <th>Anamnesa</th>
                                                    <th>Anamnesa</th>
                                                    <th>Anamnesa</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Selesai</button>
                </div>
            </div>
        </div>
    </div>

    <!-- my script -->
    <script src="{{ asset('js/template.js') }}"></script>
    <script src="{{ asset('js/populate.js') }}"></script>
    <script>
        const listSurat = @json($lists);
        console.log("🚀 ~ listSurat:", listSurat)
        const pasien = @json($pasien);
        console.log("🚀 ~ pasien:", pasien)
        const dataPasien = pasien.original;
        // console.log("🚀 ~ dataPasien:", dataPasien)
        let jumlahSuratTahunIni = @json($jumlahSuratTahunIni);
        // console.log("🚀 ~ jumlahSuratTahunIni:", jumlahSuratTahunIni)
        let noSuratNext = "";
        let date = "";
        let year = "";
        let month = "";
        let day = "";
        let romawi = ["I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII"];
        let monthRomawi = "";
        let formattedDate = "";

        function generateNoSurat() {
            noSuratNext = "440.6/" + jumlahSuratTahunIni + "/" + monthRomawi + "/" + year,
                console.log("🚀 ~ $ ~ noSuratNext:", noSuratNext)
            $("#noSurat").val(noSuratNext);
        }

        $(document).ready(function() {
            date = new Date();
            year = date.getFullYear();
            month = String(date.getMonth() + 1).padStart(2, "0");
            day = String(date.getDate()).padStart(2, "0");
            formattedDate = `${year}-${month}-${day}`;
            $("#tanggal").val(formattedDate);
            generateNoSurat();

            // //dubah month ke romawi
            // romawi = ["I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII"];
            monthRomawi = romawi[date.getMonth()];
            $("#dAntrian").show();

            noSuratNext = "440.6/" + jumlahSuratTahunIni + "/" + monthRomawi + "/" + year,
                console.log("🚀 ~ $ ~ noSuratNext:", noSuratNext)
            $("#noSurat").val(noSuratNext);
            creatTableDataPemohon(listSurat);
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

        function creatTableDataPemohon(data) {
            const batas = '2024-12-14'
            data.forEach(function(item, index) {
                let statusCetak = item.tanggal < batas ? "hidden" : "";
                item.aksi = `
                        <a type="button" class="btn btn-sm btn-warning mr-2 mb-2" ${statusCetak}
                                href="/api/surat/medis/${item.id}/${item.tanggal}" target="_blank">Cetak</a>
                        <button type="button" class="btn btn-sm btn-danger mr-2 mb-2" ${statusCetak}
                               onclick="deleteSM('${item.id}')">Hapus</button>
                        `;
                item.no = index + 1;
                item.nama_dokter = item.dok === [] || item.dok === null ? item.dokter : item.dok.gelar_d + " " +
                    item.dok.biodata.nama + " " + item.dok.gelar_b;
            });
            $("#dataPemohon")
                .DataTable({
                    data: data,
                    destroy: true,
                    columns: [{
                            data: "no"
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
                            data: "nama_dokter"
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
            var inputsToValidate = [{
                    id: "norm",
                    label: "No RM"
                },
                {
                    id: "nama",
                    label: "Nama"
                },
                {
                    id: "tglTrans",
                    label: "Tgl Surat"
                },
                {
                    id: "alamat",
                    label: "Alamat"
                },
                {
                    id: "nik",
                    label: "NIK"
                },
                {
                    id: "tglLahir",
                    label: "Tgl Lahir"
                },
                {
                    id: "umur",
                    label: "Umur"
                },
                {
                    id: "petugas",
                    label: "Petugas"
                },
                {
                    id: "dokter",
                    label: "Dokter"
                },
                {
                    id: "keperluan",
                    label: "Keperluan"
                },
                {
                    id: "td",
                    label: "Tensi Darah"
                },
                {
                    id: "bb",
                    label: "Berat Badan"
                },
                {
                    id: "tb",
                    label: "Tinggi Badan"
                },
                {
                    id: "nadi",
                    label: "Nadi"
                },
                {
                    id: "noSurat",
                    label: "No Surat"
                },
                {
                    id: "pekerjaan",
                    label: "Pekerjaan"
                },
            ];

            var error = false;
            var dataKurang = [];

            inputsToValidate.forEach(({
                id,
                label
            }) => {
                var inputElement = document.getElementById(id);
                var inputValue = inputElement.value.trim();

                if (inputValue === "") {
                    // Tambahkan error styling
                    if ($(inputElement).hasClass("select2-hidden-accessible")) {
                        $(inputElement).next(".select2-container").addClass("input-error");
                    } else {
                        inputElement.classList.add("input-error");
                    }

                    error = true;
                    dataKurang.push(label);
                } else {
                    // Hapus error styling
                    if ($(inputElement).hasClass("select2-hidden-accessible")) {
                        $(inputElement).next(".select2-container").removeClass("input-error");
                    } else {
                        inputElement.classList.remove("input-error");
                    }
                }
            });

            if (error) {
                Swal.fire({
                    icon: "error",
                    title: `Data Tidak Lengkap...!!!\n\n${dataKurang.join(", ")} Belum Diisi`,
                });
            } else {
                simpan();
            }
        }


        function simpan() {
            var tglTrans = $("#tglTrans").val();
            var noSurat = $("#noSurat").val();
            var norm = $("#norm").val();
            var nama = $("#nama").val();
            var tglLahir = $("#tglLahir").val();
            var umur = $("#umur").val();
            var nik = $("#nik").val();
            var alamat = $("#alamat").val();
            // var hasil = $("#hasil").val();
            var hasil = document.querySelector(
                'input[name="hasil"]:checked'
            ).value;
            var keperluan = $("#keperluan").val();
            var petugas = $("#petugas").val();
            var dokter = $("#dokter").val();
            var td = $("#td").val();
            var bb = $("#bb").val();
            var tb = $("#tb").val();
            var nadi = $("#nadi").val();
            var pekerjaan = $("#pekerjaan").val();

            Swal.fire({
                icon: "info",
                title: "Menyimpan Data",
                didOpen: () => {
                    Swal.showLoading();
                },
            })
            fetch("/api/surat/medis", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        tglTrans: tglTrans,
                        noSurat: noSurat,
                        norm: norm,
                        nama: nama,
                        tglLahir: tglLahir,
                        umur: umur,
                        nik: nik,
                        alamat: alamat,
                        hasil: hasil,
                        keperluan: keperluan,
                        petugas: petugas,
                        dokter: dokter,
                        td: td,
                        bb: bb,
                        tb: tb,
                        nadi: nadi,
                        pekerjaan: pekerjaan
                    }),
                })
                .then((response) => {
                    if (!response.ok) {
                        // Pisahkan status berdasarkan kode HTTP
                        if (response.status === 404) {
                            throw {
                                message: "404: Data tidak ditemukan",
                                icon: "info"
                            };
                        } else if (response.status === 400) {
                            throw {
                                message: "400: Data Sudah Ada",
                                icon: "info"
                            };
                        } else if (response.status === 500) {
                            throw {
                                message: "500: Kesalahan server internal",
                                icon: "error"
                            };
                        } else {
                            throw {
                                message: `${response.status}: Terjadi kesalahan tidak diketahui`,
                                icon: "error"
                            };
                        }
                    }
                    return response.json();
                })
                .then((data) => {
                    // Status 200
                    console.log(data);
                    Swal.fire({
                        icon: "success",
                        title: "Berhasil",
                        text: data.message, // Menampilkan pesan sukses
                    });
                    sukses(data);
                    $("#modalCreateSurat").modal("hide");
                })
                .catch((error) => {
                    console.log("🚀 ~ Error:", error);
                    Swal.fire({
                        icon: error.icon || "error", // Gunakan ikon default jika tidak ada
                        title: "Kesalahan",
                        text: error.message || "Terjadi masalah yang tidak diketahui", // Pesan error
                    });
                });
        }

        function sukses(data) {
            console.log("🚀 ~ sukses ~ data:", data)
            var listSurat = data.lists
            jumlahSuratTahunIni = data.noSurat
            console.log("🚀 ~ .then ~ jumlahSuratTahunIni:", jumlahSuratTahunIni)
            generateNoSurat();
            creatTableDataPemohon(listSurat);
        }

        function deleteSM(id) {
            Swal.fire({
                icon: "question",
                title: "Hapus Data?",
                showCancelButton: true,
                confirmButtonText: "Hapus",
                cancelButtonText: "Batal",
                customClass: {
                    popup: 'swal-custom-popup', // Tambahkan class khusus untuk popup
                    title: 'swal-custom-title', // Tambahkan class khusus untuk title
                    icon: 'swal-custom-icon' // Tambahkan class khusus untuk icon
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: "info",
                        title: "Menghapus Data",
                        didOpen: () => {
                            Swal.showLoading();
                        },
                    })
                    fetch(`/api/surat/medis/delete`, {
                            method: "post",
                            headers: {
                                "Content-Type": "application/json",
                            },
                            body: JSON.stringify({
                                id: id
                            }),
                        })
                        .then((response) => response.json())
                        .then((data) => {
                            console.log(data);
                            let msg = data.namaPasien + "\nNo Surat : " + data.no;

                            Swal.fire({
                                icon: "success",
                                title: "Berhasil menghapus data surat",
                                text: msg, // Menampilkan pesan sukses
                            });
                            sukses(data);

                        })
                        .catch((error) => {
                            console.log("🚀 ~ Error:", error);
                            Swal.fire({
                                icon: error.icon || "error", // Gunakan ikon default jika tidak ada
                                title: "Kesalahan",
                                text: error.message ||
                                    "Terjadi masalah yang tidak diketahui", // Pesan error
                            });
                        });
                }
            })
        }
    </script>
@endsection
