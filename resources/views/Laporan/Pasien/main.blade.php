@extends('Template.lte')

@section('content')
    <div class="card shadow">
        <div class="card-body">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a type="button" class="nav-link border border-primary active bg-blue" onclick=" toggleSections('#tab_1')"
                        id="link_tab_1"><b>Riwayat
                            Kunjungan</b></a>
                </li>
                <li class="nav-item">
                    <a type="button" class="nav-link border border-primary" onclick=" toggleSections('#tab_2')"><b>Rekap
                            Diagnosa</b></a>
                </li>
                <li class="nav-item">
                    <a type="button" class="nav-link border border-primary" onclick=" toggleSections('#tab_3')"><b>Jumlah
                            Diagnosa</b></a>
                </li>
            </ul>
            @include('Laporan.Pasien.riwayatKunjungan')
            @include('Laporan.Pasien.rekapDX')
            @include('Laporan.Pasien.jumlahDX')
        </div>
    </div>

    {{-- buatkan modalIdentitas --}}
    <div class="modal fade" id="modalIdentitas" tabindex="-1" role="dialog" aria-labelledby="modalIdentitasLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalIdentitasLabel">Identitas Pasien</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formIdentitas">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>NIK</label>
                                <input readonly type="text" class="form-control" name="pasien_nik" id="pasien_nik">
                            </div>
                            <div class="form-group col-md-6">
                                <label>No. KK</label>
                                <input readonly type="text" class="form-control" name="pasien_no_kk" id="pasien_no_kk">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Nama</label>
                                <input readonly type="text" class="form-control" name="pasien_nama" id="pasien_nama">
                            </div>
                            <div class="form-group col-md-6">
                                <label>No. RM</label>
                                <input readonly type="text" class="form-control" name="pasien_no_rm" id="pasien_no_rm">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>Jenis Kelamin</label>
                                <input readonly type="text" class="form-control" name="jenis_kelamin_nama"
                                    id="jenis_kelamin">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Tempat Lahir</label>
                                <input readonly type="text" class="form-control" name="pasien_tempat_lahir"
                                    id="tempat_lahir">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Tanggal Lahir</label>
                                <input readonly type="date" class="form-control" name="pasien_tgl_lahir"
                                    id="tanggal_lahir">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>No. HP</label>
                                <input readonly type="text" class="form-control" name="pasien_no_hp" id="no_hp">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Status Kawin</label>
                                <input readonly type="text" class="form-control" name="status_kawin_nama"
                                    id="status_kawin">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Alamat Domisili</label>
                            <input readonly type="text" class="form-control" name="pasien_domisili" id="domisili">
                        </div>

                        <div class="form-group">
                            <label>Alamat Lengkap</label>
                            <input readonly type="text" class="form-control" name="pasien_alamat" id="alamat">
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label>Provinsi</label>
                                <input readonly type="text" class="form-control" name="provinsi_nama" id="provinsi">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Kabupaten</label>
                                <input readonly type="text" class="form-control" name="kabupaten_nama"
                                    id="kabupaten">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Kecamatan</label>
                                <input readonly type="text" class="form-control" name="kecamatan_nama"
                                    id="kecamatan">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Kelurahan</label>
                                <input readonly type="text" class="form-control" name="kelurahan_nama"
                                    id="kelurahan">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-2">
                                <label>RT</label>
                                <input readonly type="text" class="form-control" name="pasien_rt" id="rt">
                            </div>
                            <div class="form-group col-md-2">
                                <label>RW</label>
                                <input readonly type="text" class="form-control" name="pasien_rw" id="rw">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Agama</label>
                                <input readonly type="text" class="form-control" name="agama_nama" id="agama">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Gol. Darah</label>
                                <input readonly type="text" class="form-control" name="goldar_nama" id="gol_darah">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Pendidikan</label>
                                <input readonly type="text" class="form-control" name="pendidikan_nama"
                                    id="pendidikan">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Pekerjaan</label>
                                <input readonly type="text" class="form-control" name="pekerjaan_nama"
                                    id="pekerjaan">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Penjamin</label>
                                <input readonly type="text" class="form-control" name="penjamin_nama" id="penjamin">
                            </div>
                            <div class="form-group col-md-6">
                                <label>No. Penjamin</label>
                                <input readonly type="text" class="form-control" name="penjamin_nomor"
                                    id="penjamin_nomor">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Daftar By</label>
                                <input readonly type="text" class="form-control" name="pasien_daftar_by"
                                    id="daftar_by">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Tanggal Registrasi</label>
                                <input readonly type="date" class="form-control" name="created_at_tanggal"
                                    id="created_at">
                            </div>
                        </div>
                    </form>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- end modal -->


    <!-- my script -->
    <script src="{{ asset('js/template.js') }}"></script>
    <script>
        let pasien_no_rm = "";

        function lihatIdentitas(no_rm) {
            // Ambil nilai dari input form saat ini
            const current_rm = $("#pasien_no_rm").val();

            // Jika nomor RM yang diklik sama dengan yang sudah diisi, tampilkan modal langsung
            if (current_rm === no_rm) {
                $("#modalIdentitas").modal("show");
                return;
            }

            // Simpan nomor RM yang baru ke variabel global
            pasien_no_rm = no_rm;
            tampilkanLoading("Sedangan mengambil data pasien...");
            // Lakukan request ke API untuk ambil data baru
            $.ajax({
                url: "/api/pasienKominfo",
                method: "POST",
                data: {
                    no_rm: no_rm
                },
                success: function(response) {
                    Swal.close();
                    // Isi form dengan data dari response
                    for (let key in response) {
                        $(`#formIdentitas [name="${key}"]`).val(response[key]);
                    }

                    // Tampilkan modal setelah form terisi
                    $("#modalIdentitas").modal("show");
                },
                error: function(xhr) {
                    alert("Gagal mengambil data pasien.");
                }
            });
        }


        function cariRiwayat(tglAwal, tglAkhir) {
            console.log("🚀 ~ cariRiwayat ~ tglAkhir:", tglAkhir)
            console.log("🚀 ~ cariRiwayat ~ tglAwal:", tglAwal)
            Swal.fire({
                icon: "info",
                title: "Sedang mencarikan data Riwayat Pasien...!!!\n Proses lama jika mencari lebih dari 10 hari\n Mohon ditunggu...",
                showConfirmButton: true,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            });
            reportPasien(tglAwal, tglAkhir);
        }

        function cariJumlah(tglAwal, tglAkhir) {
            console.log("🚀 ~ cariJumlah ~ tglAkhir:", tglAkhir)
            console.log("🚀 ~ cariJumlah ~ tglAwal:", tglAwal)

            Swal.fire({
                icon: "info",
                title: "Sedang mencarikan data Jumlah Diagnosa...!!!\n Proses lama jika mencari lebih dari 10 hari\n Mohon ditunggu...",
                showConfirmButton: true,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            });
            getDiagnosisCounts(tglAwal, tglAkhir);
        }

        function formatDate(date) {
            // Convert the input to a Date object if it isn't already
            if (!(date instanceof Date)) {
                date = new Date(date);
            }

            // Check if the date is valid
            if (isNaN(date)) {
                throw new Error("Invalid date");
            }

            let day = String(date.getDate()).padStart(2, "0");
            let month = String(date.getMonth() + 1).padStart(2, "0"); // getMonth() returns month from 0-11
            let year = date.getFullYear();
            return `${day}-${month}-${year}`;
        }

        function reportPasien(tglAwal, tglAkhir) {
            var tglA = formatDate(new Date(tglAwal));
            var tglB = formatDate(new Date(tglAkhir));

            var diagnosa = $("#diagnosa").val() || "";

            if ($.fn.DataTable.isDataTable("#report, #total")) {
                var tabletindakan = $("#report, #total").DataTable();
                tabletindakan.destroy();
            }

            $.ajax({
                url: "/api/riwayatKunjungan",
                type: "post",
                data: {
                    tanggal_awal: tglAwal,
                    tanggal_akhir: tglAkhir,
                    diagnosa: diagnosa,
                },
                success: function(response) {
                    var dataPasien = response.response.data;
                    dataPasien.forEach(function(item, index) {
                        // Menambahkan tombol aksi
                        item.aksi =
                            `<p>${item.pasien_no_rm}</p>
                            <button type="button" class="btn btn-primary mr-2"
                                    onclick="cariDataPasien('${item.pasien_no_rm}')" placeholder="Lihat">
                                    Rincian
                            </button>`;

                        item.rtrw = item.pasien_rt + "/" + item.pasien_rw;
                    });


                    $("#report").DataTable({
                        data: dataPasien,
                        columns: [{
                                data: "antrean_nomor"
                            },
                            {
                                data: "tanggal"
                            },
                            {
                                data: "penjamin_nama"
                            },
                            {
                                data: "aksi"
                            },
                            {
                                data: "pasien_nama",
                                className: "col-2"
                            },
                            {
                                data: "kelurahan_nama"
                            },
                            {
                                data: "rtrw"
                            },
                            {
                                data: "kecamatan_nama"
                            },
                            {
                                data: "kabupaten_nama"
                            },
                            {
                                data: "diagnosa",
                                render: function(data, type, row) {
                                    if (Array.isArray(data) && data.length > 0) {
                                        return data[0].kode_diagnosa || '-';
                                    } else {
                                        return '-';
                                    }
                                }
                            },
                            {
                                data: "diagnosa",
                                render: function(data, type, row) {
                                    if (Array.isArray(data) && data.length > 0) {
                                        return data[0].nama_diagnosa || '-';
                                    } else {
                                        return '-';
                                    }
                                }
                            },
                            {
                                data: "diagnosa",
                                render: function(data, type, row) {
                                    if (Array.isArray(data) && data.length > 1) {
                                        return data[1].kode_diagnosa || '-';
                                    } else {
                                        return '-';
                                    }
                                }
                            },
                            {
                                data: "diagnosa",
                                render: function(data, type, row) {
                                    if (Array.isArray(data) && data.length > 1) {
                                        return data[1].nama_diagnosa || '-';
                                    } else {
                                        return '-';
                                    }
                                }
                            },
                            {
                                data: "diagnosa",
                                render: function(data, type, row) {
                                    if (Array.isArray(data) && data.length > 2) {
                                        return data[2].kode_diagnosa || '-';
                                    } else {
                                        return '-';
                                    }
                                }
                            },
                            {
                                data: "diagnosa",
                                render: function(data, type, row) {
                                    if (Array.isArray(data) && data.length > 2) {
                                        return data[2].nama_diagnosa || '-';
                                    } else {
                                        return '-';
                                    }
                                }
                            },
                            {
                                data: "dokter_nama",
                                className: "col-3"
                            },
                            {
                                data: "ket_status_pasien_pulang",
                                className: "px-0 col-3",
                                label: "Status Pulang"
                            },
                            {
                                data: "subjek",
                                className: "px-0 col-3",
                                label: "Data Subjektif"
                            }
                        ],
                        autoWidth: true,
                        order: [
                            [1, "asc"],
                            [0, "asc"]
                        ],
                        buttons: [{
                                extend: "excelHtml5",
                                text: "Excel",
                                title: "Riwayat DX Pasien Tanggal: " + tglA + " s.d. " + tglB,
                                filename: "Riwayat DX Pasien Tanggal: " + tglA + " s.d. " + tglB
                            },
                            {
                                extend: "pdfHtml5",
                                text: "PDF",
                                title: "Riwayat DX Pasien Tanggal: " + tglA + " s.d. " + tglB,
                                filename: "Riwayat DX Pasien Tanggal: " + tglA + " s.d. " + tglB
                            },
                            {
                                extend: "print",
                                text: "Cetak",
                                title: "Riwayat DX Pasien Tanggal: " + tglA + " s.d. " + tglB
                            },
                            {
                                extend: "copyHtml5",
                                text: "Salin"
                            },
                            {
                                extend: "colvis",
                                text: "Tampilkan Kolom"
                            }
                        ]
                    }).buttons().container().appendTo("#report_wrapper .col-md-6:eq(0)");


                    Swal.fire({
                        icon: "success",
                        title: "Berhasil mengambil data pasien...!!!",
                        timer: 1500
                    })
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

        function getDiagnosisCounts(tglAwal, tglAkhir) {
            var tglA = formatDate(new Date(tglAwal));
            var tglB = formatDate(new Date(tglAkhir));

            if ($.fn.DataTable.isDataTable("#diagnosisTable")) {
                var tabletindakan = $("#diagnosisTable").DataTable();
                tabletindakan.destroy();
            }

            // Clear the existing table body
            $('#diagnosisTable tbody').empty();

            $.ajax({
                url: '/api/riwayatKunjungan/jumlahDx',
                type: 'POST',
                data: {
                    tanggal_awal: tglAwal,
                    tanggal_akhir: tglAkhir
                },
                success: function(response) {
                    Swal.fire({
                        icon: "success",
                        title: "Berhasil mengambil data pasien...!!!",
                        timer: 1500
                    })
                    const diagnosisCounts = response.diagnosis_counts;
                    const data = [];
                    const addedDiagnoses = new Set(); // Track added diagnoses

                    // Process the diagnosis counts
                    for (const key in diagnosisCounts) {
                        for (const diagnosis in diagnosisCounts[key]) {
                            if (!addedDiagnoses.has(diagnosis)) {
                                addedDiagnoses.add(diagnosis); // Track the added diagnosis
                                data.push({
                                    diagnosa: diagnosis,
                                    kodeDx: diagnosis.split(' - ')[0],
                                    jumlahTotal: diagnosisCounts['Total'][diagnosis] || 0,
                                    jumlahUMUM: diagnosisCounts['UMUM'][diagnosis] || 0,
                                    jumlahBPJS: diagnosisCounts['BPJS'][diagnosis] || 0
                                });
                            }
                        }
                    }

                    // Populate the DataTable
                    data.forEach(item => {
                        $('#diagnosisTable tbody').append(`
                    <tr>
                        <td>${item.diagnosa}</td>
                        <td>${item.kodeDx}</td>
                        <td>${item.jumlahTotal}</td>
                        <td>${item.jumlahUMUM}</td>
                        <td>${item.jumlahBPJS}</td>
                    </tr>
                `);
                    });

                    // Re-initialize the DataTable
                    $('#diagnosisTable').DataTable({
                        order: [
                            [2, "dsc"]
                        ],
                        buttons: [{
                                extend: "excelHtml5",
                                text: "Excel",
                                title: "Laporan Jumlah Diagnosa Tanggal: " + tglA + " s.d. " + tglB,
                                filename: "Laporan Jumlah Diagnosa Tanggal: " + tglA + " s.d. " +
                                    tglB
                            },
                            {
                                extend: "pdfHtml5",
                                text: "PDF",
                                title: "Laporan Jumlah Diagnosa Tanggal: " + tglA + " s.d. " + tglB,
                                filename: "Laporan Jumlah Diagnosa Tanggal: " + tglA + " s.d. " +
                                    tglB
                            },
                            {
                                extend: "print",
                                text: "Cetak",
                                title: "Laporan Jumlah Diagnosa Tanggal: " + tglA + " s.d. " + tglB
                            },
                            {
                                extend: "copyHtml5",
                                text: "Salin"
                            }, {
                                extend: "colvis",
                                text: "Tampilkan Kolom"
                            }
                        ]
                    }).buttons().container().appendTo("#diagnosisTable_wrapper .col-md-6:eq(0)");
                },
                error: function(error) {
                    console.error("Error fetching diagnosis counts:", error);
                }
            });
        }

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




        var tglAwal;
        var tglAkhir;

        window.addEventListener("load", function() {
            setTodayDate();
            var today = new Date().toISOString().split("T")[0];
            $("#tanggal").val(today);
            $("#no_rm").on("keyup", function(event) {
                if (event.key === "Enter") {
                    event.preventDefault();
                    cariRiwayatKunjunganPasien();
                }
            });

            // Inisialisasi tglAwal dan tglAkhir sebagai objek Moment.js
            // tglAwal = moment().subtract(30, "days").format("YYYY-MM-DD");
            tglAwal = moment().subtract(0, "days").format("YYYY-MM-DD");
            tglAkhir = moment().subtract(0, "days").format("YYYY-MM-DD");

            // Menetapkan nilai ke input tanggal
            $("#reservation, #reservation2").val(tglAwal + " to " + tglAkhir);

            // Date range picker
            $("#reservation, #reservation2").daterangepicker({
                startDate: tglAwal,
                endDate: tglAkhir,
                // autoApply: true,
                locale: {
                    format: "YYYY-MM-DD",
                    separator: " to ",
                    applyLabel: "Apply",
                    cancelLabel: "Cancel",
                    customRangeLabel: "Custom Range",
                },
            });

            $("#reservation").on(
                "apply.daterangepicker",
                function(ev, picker) {
                    tglAwal = picker.startDate.format("YYYY-MM-DD");
                    tglAkhir = picker.endDate.format("YYYY-MM-DD");

                    cariRiwayat(tglAwal, tglAkhir);
                }
            );
            $("#reservation2").on(
                "apply.daterangepicker",
                function(ev, picker) {
                    tglAwal = picker.startDate.format("YYYY-MM-DD");
                    tglAkhir = picker.endDate.format("YYYY-MM-DD");

                    cariJumlah(tglAwal, tglAkhir);
                }
            );

            // reportPasien(tglAwal, tglAkhir);
            // getDiagnosisCounts(tglAwal, tglAkhir);

        });
    </script>
@endsection
