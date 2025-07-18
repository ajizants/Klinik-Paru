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
                    Cari SEP & SK
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
            {{-- <div class="mx-2">
                <button type="button" class="btn btn-lime bg-orange"
                    onclick="jumlahTindakan(); setLinkActive('btn5'); toggleSections('#tab_5');">
                    Cari Jml Tindakan
                </button>
            </div> --}}
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
                        <a type="button" class="nav-link" onclick=" toggleSections('#tab_6')" id="link_tab_6"><b>Riwayat
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
                    {{-- <li class="nav-item">
                        <a type="button" id="btn5" class="nav-link" onclick="toggleSections('#tab_5');"><b>Rekap
                                Jumlah
                                Tindakan</b></a>
                    </li> --}}

                </ul>
            </div>
            @include('Laporan.Pendaftaran.kunjungan')
            @include('Laporan.Pendaftaran.faskesPerujuk')
            @include('Laporan.Pendaftaran.rencanaKontrol')
            @include('Laporan.Pendaftaran.listSEP')
            {{-- <div class="" id="tab_5" style="display: none;">
                @include('PusatData.jmlTindakan')
            </div> --}}
            <div class="" id="tab_6" style="display: none;">
                @include('Laporan.Pasien.riwayatKunjungan')
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
                                    data: "status_kasir",
                                    className: "text-center",
                                    render: function(data, type, row) {
                                        const statusClasses = {
                                            Sudah: "success",
                                            Belum: "danger",
                                            default: "secondary",
                                        };

                                        const norm = row.pasien_no_rm;
                                        return `<div class="badge badge-${
                                                statusClasses[data] || statusClasses.default
                                            }">Kasir: ${data}</div><br> <br>
                                            <div class="badge badge-${
                                                statusClasses[row.status_obat] ||
                                                statusClasses.default
                                            }">Obat: ${row.status_obat}</div><br> <br>
                                            <a type="button" class="btn btn-sm btn-info mr-2 mb-2"
                                            href="/kasir/norm/${norm}/${
                                                row.tanggal
                                            }" target="_blank" placeholder="Transaksi Kasir">Input Kasir</a>`;
                                    },
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
                        .appendTo("#tableSEP_wrapper .col-md-6:eq(0)");
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
    {{-- 
    <script>
        function cariDataPasien(no_rm) {
            $("#no_rm").val(no_rm);
            cariRiwayatKunjunganPasien();
            document.querySelector('.active.bg-blue').classList.remove('active', 'bg-blue');
            document.getElementById('link_tab_1').classList.add('active', 'bg-blue');
            toggleSections('#tab_1')
        }

        function cariRiwayatKunjunganPasien() {
            let identitas = `
                            <div class="row">
                                <!-- Kolom 1 -->
                                <div class="col-md-4 col-sm-6 col-12 mb-2">
                                    <p><strong>NO RM:</strong> <span>-</span></p>
                                    <p><strong>Nama:</strong> <span>-</span></p>
                                </div>

                                <!-- Kolom 2 -->
                                <div class="col-md-4 col-sm-6 col-12 mb-2">
                                    <p><strong>Tgl Lahir:</strong> <span>-</span></p>
                                    <p><strong>Umur:</strong> <span>-</span></p>
                                </div>

                                <!-- Kolom 3 -->
                                <div class="col-md-4 col-sm-6 col-12 mb-2">
                                    <p><strong>Kelamin:</strong> <span>-</span></p>
                                    <p><strong>Alamat:</strong> <span>-</span></p>
                                </div>
                            </div>
                            `;
            $("#identitas").html(identitas);
            let no_rm = ($("#no_rm").val()).padStart(6, "0");

            Swal.fire({
                icon: "info",
                title: "Sedang mencarikan data...!!! \n Pencarian dapat membutuhkan waktu lama, \n Mohon ditunggu...!!!",
                showConfirmButton: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            });
            $.ajax({
                url: "/api/kominfo/kunjungan/riwayat",
                type: "POST",
                data: {
                    no_rm
                },
                success: function(response) {
                    Swal.close();
                    console.log("🚀 ~ riwayatKunjungan ~ response:", response);
                    tabelRiwayatKunjungan(response); // Menampilkan tabel
                    document.getElementById("formIdentitas").reset();
                },
                error: function(xhr) {
                    console.error("Error:", xhr.responseText);
                    Swal.fire({
                        icon: "error",
                        title: "Gagal Memuat Riwayat",
                        text: "Terjadi kesalahan, silakan coba lagi.",
                    });
                },
            });
        }



        function tabelRiwayatKunjungan(data) {
            data.forEach((item, index) => {
                item.no = index + 1; // Nomor urut dimulai dari 1

                item.antrean = `
            <div>
                <p>${item.antrean_nomor} </p>                                    
                <p>${item.penjamin_nama}</p>                                    
                <p>${item.dokter_nama}</p>
            </div>`;

                item.diagnosa = `
            <div>
                <p><strong>DX 1 :</strong> ${item.dx1 || "-"}</p>
                <p><strong>DX 2 :</strong> ${item.dx2 || "-"}</p>
                <p><strong>DX 3 :</strong> ${item.dx3 || "-"}</p>
            </div>`;

                item.anamnesa = `
            <div>
                <p><strong>DS :</strong> ${item.ds || "-"}</p>
                <p><strong>DO :</strong> ${item.do || "-"}</p>
                <table>
                    <tr>
                        <td><strong>TD :</strong> ${item.td || "-"} mmHg</td>
                        <td><strong>Nadi :</strong> ${item.nadi || "-"} X/mnt</td>
                    </tr>
                    <tr>
                        <td><strong>BB :</strong> ${item.bb || "-"} Kg</td>
                        <td><strong>Suhu :</strong> ${item.suhu || "-"} °C</td>
                    </tr>
                    <tr>
                        <td><strong>RR :</strong> ${item.rr || "-"} X/mnt</td>
                    </tr>
                </table>
            </div>`;

                let identitas = `
            <div class="row">
                <div class="col-md-4 col-sm-6 col-12 mb-2">
                    <p><strong>NO RM:</strong> ${item.pasien_no_rm}</p>
                    <p><strong>Nama:</strong> ${item.pasien_nama}</p>
                </div>
                <div class="col-md-4 col-sm-6 col-12 mb-2">
                    <p><strong>Tgl Lahir:</strong> ${item.pasien_tgl_lahir}</p>
                    <p><strong>Umur:</strong> ${item.umur}</p>
                </div>
                <div class="col-md-4 col-sm-6 col-12 mb-2">
                    <p><strong>Kelamin:</strong> ${item.jenis_kelamin_nama}</p>
                    <p><strong>Alamat:</strong> ${item.alamat}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-6 col-12 mb-2">
                    <a type="button" class="btn btn-warning font-weight-bold mx-2" href="/RO/Hasil/${item.pasien_no_rm}" target="_blank">Lihat Hasil Penunjang</a>
                </div>
                <div class="col-md-4 col-sm-6 col-12 mb-2">
                    <a type="button" class="btn btn-danger font-weight-bold mx-2"  onclick="lihatIdentitas('${item.pasien_no_rm}')">Lihat Identitas</a>
                </div>                
            </div>`;

                $("#identitas").html(identitas);

                item.ro = generateAsktindString(item.radiologi);
                item.igd = generateAsktindString(item.tindakan, true);
                item.lab = generateAsktindString(item.laboratorium, false, true);
                // item.hasilLab = generateAsktindString(item.hasilLab, false, true);

                let obatHtml = `
            <div>
                <table border="1" style="width:100%; border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th>Nama Obat</th>
                            <th>Aturan</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>`;

                item.obat.forEach(obat => {
                    obat.resep_obat_detail.forEach(detail => {
                        let aturan = obat.aturan_pakai || "";
                        obatHtml += `
                    <tr>
                        <td>${detail.nama_obat}</td>
                        <td>${obat.signa_1} X ${obat.signa_2} ${aturan}</td>
                        <td>${detail.jumlah_obat}</td>
                    </tr>`;
                    });
                });

                obatHtml += `</tbody></table></div>`;
                item.dataObats = obatHtml;

                item.rincian =
                    `
                <div class="mb-2">
                    <p><strong>DS :</strong> ${item.ds || "-"}</p>
                    <p><strong>DO :</strong> ${item.do || "-"}</p>
                    <p><span><strong>TD:</strong> ${item.td || "-"} mmHg, </span>
                    <span><strong>Nadi:</strong> ${item.nadi || "-"} X/mnt, </span>
                    <span><strong>BB:</strong> ${item.bb || "-"} Kg, </span>
                    <span><strong>Suhu:</strong> ${item.suhu || "-"} °C, </span>
                    <span><strong>RR:</strong> ${item.rr || "-"} X/mnt </span></p>
                </div>
                <div class="mb-2" >
                    <p><strong>DX 1 :</strong> ${item.dx1 || "-"}</p>
                    <p><strong>DX 2 :</strong> ${item.dx2 || "-"}</p>
                    <p><strong>DX 3 :</strong> ${item.dx3 || "-"}</p>
                </div>
                <p class="mb-2"><strong>Radiologi :</strong> ${item.ro || "Tidak Ada Pemeriksaan RO"}</p>
                <p class="mb-2"><strong>Tindakan :</strong> ${item.igd || "Tidak Ada Tidankan"}</p>
                <p class="mb-2"><strong>Laboratorium :</strong> ${item.hasilLab || ""}</p>
                <p class="mb-2"><strong>Resep Obat :</strong> ${item.dataObats || "Tidak Ada Resep Obat"}</p>
                <p class="mb-2"> <strong> Status Pulang: </strong> ${item.status_pasien_pulang +", " || ""}  ${item.ket_status_pasien_pulang || "-"}</p > `;
            });

            // Hancurkan DataTable sebelumnya jika ada
            if ($.fn.DataTable.isDataTable("#riwayatKunjungan")) {
                $("#riwayatKunjungan").DataTable().destroy();
            }

            // Inisialisasi DataTable baru
            $("#riwayatKunjungan").DataTable({
                data: data,
                columns: [{
                        data: "antrean",
                        className: "text-wrap",
                        title: "Pendaftaran",
                        width: "25%"
                    },
                    {
                        data: "tanggal",
                        className: "text-center",
                        title: "Tanggal",
                        width: "10%"
                    },
                    {
                        data: "rincian",
                        className: "text-wrap",
                        title: "SOAP"
                    }
                ],
                paging: true,
                order: [
                    [1, "desc"]
                ], // Mengurutkan berdasarkan tanggal
                lengthMenu: [
                    [5, 10, 25, 50, -1],
                    [5, 10, 25, 50, "All"]
                ],
                pageLength: 3,
                responsive: true,
                autoWidth: false,
                scrollX: true
            });
        }
    </script> --}}
@endsection
