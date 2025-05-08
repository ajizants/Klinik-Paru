@extends('Template.lte')

@section('content')
    {{-- Data per pasien --}}
    <div class="container-fluid">
        <div class="row">
            <label class="col-form-label">Rentang Tanggal :</label>
            <div class="form-group col-3">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="far fa-calendar-alt"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control float-right" id="reservation">
                </div>
            </div>
            <div class="mx-2">
                <button type="button" class="btn btn-success" onclick="segarkan(); toggleSections('#tab_1');">
                    Cari Data Kujungan
                </button>
            </div>
            <div class="mx-2">
                <button type="button" class="btn btn-primary"
                    onclick=" cariDataSEP(tglAwal, tglAkhir); toggleSections('#tab_2');">
                    Cari Data SEP & SK
                </button>
            </div>
            <div class="mx-2">
                <button type="button" class="btn btn-warning" onclick="rekapFaskesPerujuk(); toggleSections('#tab_3');">
                    Cari Data Faskes Perujuk
                </button>
            </div>
            <div class="mx-2">
                <button type="button" class="btn btn-info" onclick="rencanaKontrolPasien(); toggleSections('#tab_4');">
                    Cari Data Rencana Kontrol
                </button>
            </div>
            <div class="mx-2">
                <button type="button" class="btn btn-lime bg-orange" onclick="jumlahTindakan(); toggleSections('#tab_5');">
                    Cari Data Jumlah Tindakan
                </button>
            </div>
        </div>
    </div>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row ml-1">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a type="button" class="nav-link active bg-blue" onclick="toggleSections('#tab_1');"><b>Rekap
                                Kunjungan</b></a>
                    </li>
                    <li class="nav-item">
                        <a type="button" class="nav-link" onclick="toggleSections('#tab_2');"><b>SEP & Surat
                                Kontrol</b></a>
                    </li>
                    <li class="nav-item">
                        <a type="button" class="nav-link" onclick="toggleSections('#tab_3');"><b>Rekap Jumlah Faskes
                                Perujuk</b></a>
                    </li>
                    <li class="nav-item">
                        <a type="button" class="nav-link" onclick="toggleSections('#tab_4');"><b>Rencana Kontrol
                                Pasien</b></a>
                    </li>
                    <li class="nav-item">
                        <a type="button" class="nav-link" onclick="toggleSections('#tab_5');"><b>Rekap Jumlah
                                Tindakan</b></a>
                    </li>
                </ul>
            </div>
            @include('Laporan.Pendaftaran.kunjungan')
            @include('Laporan.Pendaftaran.faskesPerujuk')
            @include('Laporan.Pendaftaran.rencanaKontrol')
            @include('Laporan.Pendaftaran.listSEP')
            @include('Laporan.Pendaftaran.jmlTindakan')
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalSep" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="modalSepLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSepLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formSep">
                        <div class="form-group">
                            <label for="norm">No. RM</label>
                            <input type="text" class="form-control" id="norm" readonly>
                            <input type="text" class="form-control" id="notrans" readonly>
                        </div>
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control" id="nama" readonly>
                        </div>
                        <div class="form-group">
                            <label for="jaminan">Jaminan</label>
                            <input type="text" class="form-control" id="jaminan" readonly>
                        </div>
                        <div class="form-group">
                            <label for="noSep">No. SEP</label>
                            <input type="text" class="form-control" id="noSep" required onkeyup="checkEnter(event)">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal"
                        onclick="selesai();">Simpan</button>
                    <button type="button" class="btn btn-danger"data-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="modal fade" id="modalPendaftaran" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="modalPendaftaranLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPendaftaranLabel">Data Tambahan Pendaftaran</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formPendaftaran">
                        <div class="form-group">
                            <label for="norm">Pekerjaan</label>
                            <label for="pekerjaan">Pekerjaan</label>
                            <select id="pekerjaan" name="pekerjaan" class="form-control select2">
                                <option value="">-- Pilih Pekerjaan --</option>
                                <option value="Belum/Tidak Bekerja">Belum/Tidak Bekerja</option>
                                <option value="Pelajar/Mahasiswa">Pelajar/Mahasiswa</option>
                                <option value="PNS">PNS</option>
                                <option value="PPPK">PPPK</option>
                                <option value="TNI">TNI</option>
                                <option value="Polri">Polri</option>
                                <option value="Pegawai Swasta">Pegawai Swasta</option>
                                <option value="Wiraswasta">Wiraswasta</option>
                                <option value="Petani">Petani</option>
                                <option value="Buruh">Buruh</option>
                                <option value="Nelayan">Nelayan</option>
                                <option value="Ibu Rumah Tangga">Ibu Rumah Tangga</option>
                                <option value="Guru">Guru</option>
                                <option value="Dokter">Dokter</option>
                                <option value="Perawat">Perawat</option>
                                <option value="Apoteker">Apoteker</option>
                                <option value="Pengacara">Pengacara</option>
                                <option value="Notaris">Notaris</option>
                                <option value="Dosen">Dosen</option>
                                <option value="Seniman/Artis">Seniman/Artis</option>
                                <option value="Sopir">Sopir</option>
                                <option value="Ojek Online">Ojek Online</option>
                                <option value="Pedagang">Pedagang</option>
                                <option value="Montir">Montir</option>
                                <option value="Security">Security</option>
                                <option value="Desainer">Desainer</option>
                                <option value="Programmer">Programmer</option>
                                <option value="Teknisi">Teknisi</option>
                                <option value="Arsitek">Arsitek</option>
                                <option value="Akuntan">Akuntan</option>
                                <option value="Analis">Analis</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>

                        </div>
                        <div class="form-group">
                            <label for="ibu">Nama Ibu Kandung</label>
                            <input type="text" class="form-control" id="ibu">
                        </div>
                        <div class="form-group">
                            <label for="jaminan">Jaminan</label>
                            <input type="text" class="form-control" id="jaminan" readonly>
                        </div>
                        <div class="form-group">
                            <label for="noSep">No. SEP</label>
                            <input type="text" class="form-control" id="noSep" required
                                onkeyup="checkEnter(event)">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal"
                        onclick="selesai();">Simpan</button>
                    <button type="button" class="btn btn-danger"data-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- my script -->
    <script src="{{ asset('js/template.js') }}"></script>
    <script src="{{ asset('vendor/plugins/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('vendor/plugins/chart.js/Chart.bundle.min.js') }}"></script>
    <script src="{{ asset('js/reportPendaftaran.js') }}"></script>
    <script>
        socketIO.on("reload", (msg) => {
            if (msg == "paru_loket_pendaftaran") {
                const notif = new Audio("/audio/dingdong.mp3");
                notif.load();
                notif.play();
                // if (prosesPanggilFungsi == false) {
                //     console.log("🚀 ~ socketIO.on ~ prosesPanggilFungsi:", prosesPanggilFungsi)
                reportPendaftaran(tglAwal, tglAkhir);
                // }
            }
        });

        function cariDataSEP(tglAwal, tglAkhir) {
            Swal.fire({
                icon: "info",
                title: "Sedang mencarikan data...!!!\n Mohon Bersabar...!!!",
                showConfirmButton: true,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            });
            prosesCariDataLaporan = true;
            console.log(
                "🚀 ~ reportPendaftaran ~ prosesCariDataLaporan:",
                prosesCariDataLaporan
            );
            var tglA = formatDate(new Date(tglAwal));
            var tglB = formatDate(new Date(tglAkhir));

            if ($.fn.DataTable.isDataTable("#tableSEP")) {
                var tabletindakan = $("#tableSEP").DataTable();
                tabletindakan.destroy();
            }

            $.ajax({
                url: "/api/bpjs/get_data",
                type: "post",
                data: {
                    tanggal_awal: tglAwal,
                    tanggal_akhir: tglAkhir
                },
                success: function(response) {
                    console.log("🚀 ~ cariDataSEP ~ response:", response)

                    $("#tableSEP")
                        .DataTable({
                            data: response,
                            columns: [{
                                    data: "aksi",
                                    // className: "col-3"
                                },
                                {
                                    data: "antrean_nomor"
                                },
                                {
                                    data: "tanggal"
                                },
                                {
                                    data: "detail_sep"
                                },
                                {
                                    data: "detail_surat_kontrol"
                                },
                                {
                                    data: "jenis_kunjungan_nama"
                                },
                                {
                                    data: "penjamin_nama"
                                },
                                {
                                    data: "daftar_by"
                                },
                                {
                                    data: "pasien_no_rm"
                                },
                                {
                                    data: "pasien_nama",
                                    className: "col-2"
                                },

                                {
                                    data: "poli_sub_nama"
                                },
                                {
                                    data: "dokter_nama",
                                    className: "col-2"
                                },
                            ],
                            autoWidth: false,
                            lengthChange: false,
                            order: [
                                [1, "asc"],
                            ],
                            buttons: [{
                                    extend: "excelHtml5",
                                    text: "Excel",
                                    title: "Laporan Pendaftaran Tanggal: " +
                                        tglA +
                                        " s.d. " +
                                        tglB,
                                    filename: "Laporan Pendaftaran Tanggal: " +
                                        tglA +
                                        "  s.d. " +
                                        tglB,
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
                        .appendTo("#report_wrapper .col-md-6:eq(0)");
                    Swal.close();
                    setTimeout(function() {
                        prosesCariDataLaporan = false;
                    }, 3000);
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                    Swal.fire({
                        icon: "error",
                        title: "Terjadi kesalahan saat mengambil data pasien...!!!\n" +
                            error,
                    });
                },
            });
        }
    </script>
@endsection
