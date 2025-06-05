<div class="card-header bg-primary py-3 d-flex flex-row align-items-center justify-content-start">
    <h6 class="font-weight-bold ">Riwayat Kunjungan Pasien</h6>
</div>
<div class="card-body">
    <div id="form_cari_riwayat" class="form-row mx-auto">
        <div class="form-group col-10 col-md-4 col-sm-2">
            <input type="text" id="no_rm" class="form-control"
                placeholder="Ketikan NO RM lalu tekan enter atau klik tombol cari" maxlength="6" pattern="[0-9]{6}"
                require />
        </div>
        <div class="col-1">
            <button type="button" class="btn btn-success" onclick="cariRiwayatKunjunganPasien();">
                Cari
            </button>
        </div>
    </div>
    <div class="container-fluid">
        <div class="card card-info">
            <div class="card-body p-2">
                <div class="container-fluid bg-secondary p-2 pt-4 fs-3" id="identitas">
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
                </div>

                <div style="display: block; overflow-x: auto; white-space: nowrap;">
                    <table id="riwayatKunjungan" class="table table-striped table-hover pt-0 mt-0 fs-6"
                        style="width:100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="text-center"></th>
                                <th class="text-center"></th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
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


<script>
    window.addEventListener("load", function() {

        $("#no_rm").on("keyup", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                cariRiwayatKunjunganPasien();
            }
        });
    });

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
</script>
