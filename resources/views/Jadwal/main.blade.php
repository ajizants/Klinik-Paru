@extends('Template.lte')

@section('content')
    <div class="card shadow mb-4">
        <!-- Card Header - Accordion -->
        <a href="#collapseCardAntrian" class="d-block card-header py-1 bg bg-info" data-toggle="collapse" role="button"
            aria-expanded="true" aria-controls="collapseCardExample">
            <h4 class="m-0 font-weight-bold text-dark text-center">Jadwal Karyawan</h4>
        </a>
        <!-- Card Content - Collapse -->
        <div class="collapse show card-body" id="collapseCardAntrian">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Form Jadwal Karyawan</h3>
                </div>
                <div class="card-body">
                    <form enctype="multipart/form-data" class="form-row">
                        @csrf
                        <div class="form-group col-12 col-md-2 text-center">
                            <a href="{{ route('download.template') }}" class="btn btn-warning w-100">
                                Download Template
                            </a>
                        </div>
                        <div class="form-group col-12 col-md-4">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="file" name="file">
                                <label class="custom-file-label" for="customFile">Pilih file jadwal dengan format
                                    excel</label>
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-2">
                            <select name="jabatan" id="jabatan" class="form-control" data-select2-id="17">
                                <option selected="selected" value="">---Pilih Unit---</option>
                                <option value="dokter">Dokter</option>
                                <option value="perawat">Perawat</option>
                                <option value="analis">Analis</option>
                                <option value="radiografer">Radiologi</option>
                                <option value="farmasi">farmasi</option>
                            </select>
                        </div>
                        <div class="form-group col-12 col-md-1">
                            <select name="bulan" id="bulan" class="form-control" data-select2-id="18">
                                <option selected="selected" value="">---Pilih Bulan---</option>
                                <option value="01">Januari</option>
                                <option value="02">Februari</option>
                                <option value="03">Maret</option>
                                <option value="04">April</option>
                                <option value="05">Mei</option>
                                <option value="06">Juni</option>
                                <option value="07">Juli</option>
                                <option value="08">Agustus</option>
                                <option value="09">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                        </div>
                        <div class="form-group col-12 col-md-1">
                            <select name="tahun" id="tahun" class="form-control" data-select2-id="19">
                                <option selected="selected" value="">---Pilih Tahun---</option>
                            </select>
                        </div>
                        <div class="form-group col-12 col-md-2 text-center">
                            <a onclick="uploadJadwal();" class="btn btn-success w-100" id="tblSimpan">Upload Jadwal</a>
                        </div>
                    </form>

                </div>
            </div>
            <div class="mt-3">
                <ul class="nav nav-tabs">
                    <li class="nav-item mr-3">
                        <button type="button" class="btn bg-lime" onclick=" getJadwal();"><b>Update Data</b></button>
                    </li>
                    <li class="nav-item">
                        <a type="button" class="nav-link active bg-blue"
                            onclick=" toggleSections('#tab_1')"><b>Dokter</b></a>
                    </li>
                    <li class="nav-item">
                        <a type="button" class="nav-link" onclick=" toggleSections('#tab_2')"><b>Perawat</b></a>
                    </li>
                    <li class="nav-item">
                        <a type="button" class="nav-link" onclick=" toggleSections('#tab_3')"><b>Analis</b></a>
                    </li>
                </ul>
                <div id="tab_1" class="card-body card-body-hidden p-2">
                    <h5 class="mb-0 text-center"><b>Jadwal Dokter</b></h5>
                    <div class="table-responsive pt-2 px-2">
                        <table id="table_dokter" class="table table-striped table-hover pt-0 mt-0 fs-6" style="width:100%"
                            cellspacing="0">
                            <thead class="bg bg-secondary">
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="tab_2" class="card-body card-body-hidden p-2" style="display: none;">
                    <h5 class="mb-0 text-center"><b>Jadwal Perawat</b></h5>
                    <div class="table-responsive pt-2 px-2">
                        <table id="table_perawat" class="table table-striped table-hover pt-0 mt-0 fs-6"
                            style="width:100%" cellspacing="0">
                            <thead class="bg bg-secondary">
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="tab_3" class="card-body card-body-hidden p-2" style="display: none;">
                    <h5 class="mb-0 text-center"><b>Jadwal Analis</b></h5>
                    <div class="table-responsive pt-2 px-2">
                        <table id="table_analis" class="table table-striped table-hover pt-0 mt-0 fs-6"
                            style="width:100%" cellspacing="0">
                            <thead class="bg bg-secondary">
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Jadwal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <input type="hidden" id="editId" name="id">
                        <div class="form-group">
                            <label for="editNama">Nama</label>
                            <input type="text" class="form-control" id="editNama" name="nama" required>
                        </div>
                        <div class="form-group">
                            <label for="editJabatan">Jabatan</label>
                            <select name="jabatan" id="editJabatan" class="form-control" data-select2-id="17">
                                <option selected="selected" value="">---Pilih Unit---</option>
                                <option value="dokter">Dokter</option>
                                <option value="perawat">Perawat</option>
                                <option value="analis">Analis</option>
                                <option value="radiografer">Radiologi</option>
                                <option value="farmasi">farmasi</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editNip">Nip</label>
                            <input type="text" class="form-control" id="editNip" name="nip" required>
                        </div>
                        <div class="form-group">
                            <label for="editTanggal">Tanggal</label>
                            <input type="date" class="form-control" id="editTanggal" name="tanggal" required>
                        </div>
                        <div class="form-group">
                            <label for="editShift">Shift</label>
                            <input class="form-control" id="editShift" name="shift" required type="text">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="updateJadwal()">Simpan Perubahan</button>
                </div>
            </div>
        </div>
    </div>


    <!-- my script -->
    <script src="{{ asset('js/template.js') }}"></script>
    <script src="{{ asset('js/populate.js') }}"></script>
    <script>
        $(document).ready(function() {
            date = new Date();
            year = date.getFullYear();
            month = String(date.getMonth() + 1).padStart(2, "0");
            day = String(date.getDate()).padStart(2, "0");
            formattedDate = `${year}-${month}-${day}`;
            $("#tanggal").val(formattedDate);

            document.getElementById("file").addEventListener("change", function() {
                var fileName = this.files[0] ? this.files[0].name : "Pilih file jadwal";
                this.nextElementSibling.innerText = fileName;
            });
            isiTglUpload();

        });

        function isiTglUpload() {
            var bulanSelect = document.getElementById("bulan");
            var tahunSelect = document.getElementById("tahun");

            // Ambil tanggal saat ini
            var today = new Date();
            var currentYear = today.getFullYear();
            var currentMonth = today.getMonth() + 1; // getMonth() mulai dari 0 (Januari = 0)

            // Hitung 1 bulan setelah bulan sekarang
            var nextMonth = currentMonth + 1;
            var nextYear = currentYear;

            if (nextMonth > 12) {
                nextMonth = 1; // Jika bulan Desember, pindah ke Januari tahun berikutnya
                nextYear += 1;
            }

            // Format bulan dengan dua digit (01, 02, ..., 12)
            var formattedMonth = nextMonth < 10 ? "0" + nextMonth : nextMonth;

            // Set nilai default dropdown bulan
            bulanSelect.value = formattedMonth;

            // Isi dropdown tahun dengan rentang -5 hingga +5 dari tahun sekarang
            var startYear = currentYear - 5;
            var endYear = currentYear + 2;

            for (var i = startYear; i <= endYear; i++) {
                var option = document.createElement("option");
                option.value = i;
                option.textContent = i;
                if (i === nextYear) {
                    option.selected = true; // Set tahun default
                }
                tahunSelect.appendChild(option);
            }

        }

        function uploadJadwal() {
            Swal.fire({
                title: 'Mohon Tunggu Beberapa Saat',
                text: 'Sedang memproses upload jadwal...',
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading()
                }
            })
            $('#tblSimpan').prop('disabled', true);
            var file = document.getElementById("file").files[0];
            // var tanggal = document.getElementById("tanggal").value;
            var jabatan = document.getElementById("jabatan").value;
            var bulan = document.getElementById("bulan").value;
            var tahun = document.getElementById("tahun").value;
            var formData = new FormData();
            formData.append("file", file);
            // formData.append("tanggal", tanggal);
            formData.append("bulan", bulan);
            formData.append("tahun", tahun);
            formData.append("jabatan", jabatan);

            $.ajax({
                url: "/api/jadwal/upload",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        tampilkanSuccess(response.message);
                        ["dokter", "perawat", "analis"].forEach(jabatan => {
                            drawTable(response.data, jabatan, `table_${jabatan}`);
                        });
                    } else {
                        tampilkanEror(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                    tampilkanEror(
                        `${error}, \n Terjadi kesalahan saat mengambil data: ${xhr.responseJSON?.message || "Unknown error"}`
                    );
                }
            });
            $('#tblSimpan').prop('disabled', false);
        }

        function getJadwal() {
            Swal.fire({
                title: 'Mohon Tunggu Beberapa Saat',
                text: 'Sedang mencari data jadwal...',
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading()
                }
            })
            $.ajax({
                url: "/api/jadwal/get",
                type: "POST",
                data: {
                    jabatan: $("#jabatan").val(),
                },
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        tampilkanSuccess(response.message);
                        ["dokter", "perawat", "analis"].forEach(jabatan => {
                            drawTable(response.data, jabatan, `table_${jabatan}`);
                        });
                    } else {
                        tampilkanEror(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                    tampilkanEror(
                        `${error}, \n Terjadi kesalahan saat mengambil data: ${xhr.responseJSON?.message || "Unknown error"}`
                    );
                }
            });
        }

        function drawTable(data, jabatan, idTable) {
            var filteredData = data.filter(item => item.jabatan === jabatan);
            filteredData.forEach((item, index) => {
                let nip = item.nip || '-';

                item.aksi = `
                                <button type="button" class="btn btn-sm btn-warning mr-2 mb-2 mb-sm-0" 
                                item-id="${item.id}"
                                item-tanggal="${item.tanggal}"
                                item-shift="${item.shift}"
                                item-jabatan="${item.jabatan}"
                                item-nip="${nip}"
                                item-nama="${item.nama}"
                                onclick="editJadwal(this)">Edit</button>
                                <button type="button" class="btn btn-sm btn-danger mr-2 mb-2 mb-sm-0" onclick="deleteJadwal('${item.id}')">Hapus</button>
                            `;
                item.no = index + 1;
            });

            $(`#${idTable}`).DataTable({
                data: filteredData,
                destroy: true,
                columns: [{
                        data: "aksi",
                        title: "Aksi",
                        className: "text-center"
                    },
                    {
                        data: "no",
                        title: "No"
                    },
                    {
                        data: "tanggal",
                        title: "Tanggal",
                        className: "col-1"
                    },
                    {
                        data: "nama",
                        title: "Nama",
                        className: "col-2"
                    },
                    {
                        data: "shift",
                        title: "Piket",
                        className: "col-1"
                    },
                    {
                        data: "jabatan",
                        title: "Jabatan",
                        className: "col-2"
                    },
                    {
                        data: "nip",
                        title: "NIP"
                    }
                ],
                lengthMenu: [
                    [5, 10, 25, 50, -1],
                    [5, 10, 25, 50, "All"],
                ],
                pageLength: 5,
                order: [
                    [0, "desc"]
                ],
                buttons: [{
                        extend: "excelHtml5",
                        text: "Excel",
                        title: "Laporan Surat Ket Medis",
                        filename: "Laporan_Surat_Ket_Medis",
                    },
                    {
                        extend: "colvis",
                        text: "Tampilkan Kolom",
                    },
                ],
            }).buttons().container().appendTo("#" + idTable + "_wrapper .col-md-6:eq(0)");
        }

        function updateJadwal() {
            let id = document.getElementById("editId").value;
            let nama = document.getElementById("editNama").value;
            let tanggal = document.getElementById("editTanggal").value;
            let shift = document.getElementById("editShift").value;
            let jabatan = document.getElementById("editJabatan").value;
            let nip = document.getElementById("editNip").value;

            fetch(`/api/jadwal/${id}`, {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        nama,
                        tanggal,
                        shift,
                        jabatan,
                        nip
                    }),
                })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error("Gagal memperbarui jadwal");
                    }
                    return response.json();
                })
                .then((data) => {
                    Swal.fire({
                        icon: "success",
                        title: "Berhasil",
                        text: "Jadwal berhasil diperbarui",
                    });

                    $("#editModal").modal("hide");

                    // Perbarui tabel setelah edit
                    ["dokter", "perawat", "analis"].forEach((jabatan) => {
                        drawTable(data.lists, jabatan, `table_${jabatan}`);
                    });
                })
                .catch((error) => {
                    console.error("🚀 ~ Error:", error);
                    Swal.fire({
                        icon: "error",
                        title: "Kesalahan",
                        text: error.message || "Terjadi masalah saat memperbarui jadwal",
                    });
                });
        }

        function deleteJadwal(id) {
            Swal.fire({
                icon: "question",
                title: "Hapus Data?",
                showCancelButton: true,
                confirmButtonText: "Hapus",
                cancelButtonText: "Batal",
                customClass: {
                    popup: "swal-custom-popup",
                    title: "swal-custom-title",
                    icon: "swal-custom-icon",
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: "info",
                        title: "Menghapus Data",
                        didOpen: () => {
                            Swal.showLoading();
                        },
                    });

                    fetch(`/api/jadwal/${id}`, {
                            method: "DELETE",
                            headers: {
                                "Content-Type": "application/json",
                            },
                        })
                        .then(async (response) => {
                            if (!response.ok) {
                                throw new Error("Gagal menghapus data");
                            }
                            return response.json().catch(() => {
                                throw new Error("Response tidak valid");
                            });
                        })
                        .then((data) => {
                            if (!data || !data.data) {
                                throw new Error("Data tidak ditemukan");
                            }

                            let msg =
                                `${data.data.nama}\nTanggal : ${data.data.tanggal}\nPiket : ${data.data.shift}`;

                            Swal.fire({
                                icon: "success",
                                title: "Berhasil menghapus data",
                                text: msg,
                            });

                            if (Array.isArray(data.lists)) {
                                ["dokter", "perawat", "analis"].forEach((jabatan) => {
                                    drawTable(data.lists, jabatan, `table_${jabatan}`);
                                });
                            }
                        })
                        .catch((error) => {
                            console.error("🚀 ~ Error:", error);
                            Swal.fire({
                                icon: "error",
                                title: "Kesalahan",
                                text: error.message || "Terjadi masalah yang tidak diketahui",
                            });
                        });
                }
            });
        }

        function editJadwal(button) {
            // Ambil data dari atribut tombol
            let id = button.getAttribute("item-id");
            let nama = button.getAttribute("item-nama");
            let tanggal = button.getAttribute("item-tanggal");
            let shift = button.getAttribute("item-shift");
            let jabatan = button.getAttribute("item-jabatan");
            let nip = button.getAttribute("item-nip");

            // Isi form dalam modal dengan data dari tombol
            document.getElementById("editId").value = id;
            document.getElementById("editNama").value = nama;
            document.getElementById("editTanggal").value = tanggal;
            document.getElementById("editShift").value = shift;
            document.getElementById("editJabatan").value = jabatan;
            document.getElementById("editNip").value = nip;

            // Tampilkan modal edit
            $("#editModal").modal("show");
        }
    </script>
@endsection
