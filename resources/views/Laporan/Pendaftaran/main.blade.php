@extends('Template.lte')

@section('content')
    {{-- Data per pasien --}}
    <div class="container-fluid">
        <div class="form-row">
            <label class="col-form-label">Tanggal :</label>
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
                <button type="button" class="btn btn-success"
                    onclick="segarkan(); setLinkActive('btn1'); toggleSections('#tab_1');">
                    Cari Data Kujungan
                </button>
            </div>
            <div class="mx-2">
                <button type="button" class="btn btn-primary"
                    onclick=" cariDataSEP(tglAwal, tglAkhir); setLinkActive('btn2'); toggleSections('#tab_2');">
                    Cari Data SEP & SK
                </button>
            </div>
            <div class="mx-2">
                <button type="button" class="btn btn-warning"
                    onclick="rekapFaskesPerujuk(); setLinkActive('btn3'); toggleSections('#tab_3');">
                    Cari Faskes Perujuk
                </button>
            </div>
            <div class="mx-2">
                <button type="button" class="btn btn-info"
                    onclick="rencanaKontrolPasien(); setLinkActive('btn4'); toggleSections('#tab_4');">
                    Cari Rencana Kontrol
                </button>
            </div>
            <div class="mx-2">
                <button type="button" class="btn btn-lime bg-orange"
                    onclick="jumlahTindakan(); setLinkActive('btn5'); toggleSections('#tab_5');">
                    Cari Jumlah Tindakan
                </button>
            </div>
        </div>
        <script>
            function setLinkActive(id) {
                //remove semua class active dan bg-blue di element yang memiliki class nav-link
                $('.nav-link').removeClass('active bg-blue');
                //tambah class active dan bg-blue di element yang memiliki id yang sama dengan id parameter
                $('#' + id).addClass('active bg-blue');
            }
        </script>
    </div>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row ml-1">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a type="button" id="btn1" class="nav-link active bg-blue"
                            onclick="toggleSections('#tab_1');"><b>Rekap
                                Kunjungan</b></a>
                    </li>
                    <li class="nav-item">
                        <a type="button" id="btn2" class="nav-link" onclick="toggleSections('#tab_2');"><b>SEP & Surat
                                Kontrol</b></a>
                    </li>
                    <li class="nav-item">
                        <a type="button" id="btn3" class="nav-link" onclick="toggleSections('#tab_3');"><b>Rekap
                                Jumlah Faskes
                                Perujuk</b></a>
                    </li>
                    <li class="nav-item">
                        <a type="button" id="btn4" class="nav-link" onclick="toggleSections('#tab_4');"><b>Rencana
                                Kontrol
                                Pasien</b></a>
                    </li>
                    <li class="nav-item">
                        <a type="button" id="btn5" class="nav-link" onclick="toggleSections('#tab_5');"><b>Rekap
                                Jumlah
                                Tindakan</b></a>
                    </li>
                </ul>
            </div>
            @include('Laporan.Pendaftaran.kunjungan')
            @include('Laporan.Pendaftaran.faskesPerujuk')
            @include('Laporan.Pendaftaran.rencanaKontrol')
            @include('Laporan.Pendaftaran.listSEP')
            <div class="" id="tab_5" style="display: none;">
                @include('PusatData.jmlTindakan')
            </div>
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
    </div>

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
