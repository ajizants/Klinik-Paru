var Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
});

$(document).on("select2:open", () => {
    document.querySelector(".select2-search__field").focus();
});

function formatNorm(inputElement) {
    // Pastikan inputElement adalah objek jQuery yang valid
    if (inputElement && inputElement.val) {
        // Hapus karakter selain digit
        let inputValue = inputElement.val().replace(/\D/g, "");

        // Tambahkan 0 di depan jika kurang dari 6 digit
        while (inputValue.length < 6) {
            inputValue = "0" + inputValue;
        }

        // Ambil 6 digit pertama
        inputElement.val(inputValue.slice(0, 6));
    }
}

// function searchByRM(norm, date) {
// function cariPasienTb(norm, date, pasien, pendaftaran) {
function cariPasienTb(norm, date, ruang) {
    Swal.fire({
        icon: "info",
        title: "Sedang Mencari Data Pasien TBC\n Mohon Ditunggu ...!!!",
        // allowOutsideClick: false, // Mencegah interaksi di luar dialog
        didOpen: () => {
            Swal.showLoading(); // Menampilkan loading spinner
        },
    });
    $.ajax({
        url: "/api/pasien/TB",
        type: "POST",
        data: {
            norm: norm,
        },
        success: function (response) {
            if (response.error) {
                console.error("Error: " + response.error);
            } else if (response.metadata) {
                var code = response.metadata.code;
                // console.log("🚀 ~ cariPasienTb ~ code:", code);
                if (code === 404) {
                    Swal.fire({
                        icon: "info",
                        title: response.metadata.message,
                    });
                } else if (code === 204) {
                    Swal.fire({
                        icon: "question",
                        title:
                            "Data dengan norm " +
                            norm +
                            " belum terdaftar sebagai pasien TB",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "YA",
                        cancelButtonText: "TIDAK",
                    }).then((result) => {
                        // Display a confirmation dialog
                        if (result.isConfirmed) {
                            var pasien = response.data[0].pasien;
                            // console.log("🚀 ~ cariPasienTb ~ pasien:", pasien);
                            var pendaftaran = response.data[0].pendaftaran[0];
                            // console.log(
                            //     "🚀 ~ cariPasienTb ~ pendaftaran:",
                            //     pendaftaran
                            // );
                            // cariKominfo(norm, date, ruang);
                            isiBiodataModal(norm, date, pasien, pendaftaran);
                            $("#modal-pasienTB").modal("show");
                        } else {
                        }
                    });
                } else if (code === 200) {
                    var ptb = response.data;
                    var pasien = response.data[0].pasien;
                    var pendaftaran = response.data[0].pendaftaran[0];

                    isiIdentitas(pasien, pendaftaran);
                    showRiwayatKunjungan(norm);
                }
            }
        },
        error: function (xhr) {
            // Handle error
        },
    });
}

// Separate function to perform the original AJAX request to "/api/cariRM"
function isiBiodata(pasien, pendaftaran) {
    $("#norm").val(pasien.pasien_no_rm);
    $("#nama").val(pasien.pasien_nama);
    $("#alamat").val(pasien.pasien_alamat);
    $("#notrans").val(pendaftaran.no_trans);
    $("#layanan").val(pendaftaran.penjamin_nama);
    $("#dokter").val(pendaftaran.nip_dokter).trigger("change");

    setTimeout(function () {
        Swal.close();
        scrollToInputSection();
    }, 1000);
}
function isiBiodataModal(norm, date, pasien, pendaftaran) {
    $("#modal-pasienTB #modal-norm").val(norm);
    $("#modal-pasienTB #modal-notrans").val(pendaftaran.no_trans);
    $("#modal-pasienTB #modal-hp").val(pasien.pasien_no_hp);
    $("#modal-pasienTB #modal-nik").val(pasien.pasien_nik);
    $("#modal-pasienTB #modal-nama").val(pasien.pasien_nama);
    $("#modal-pasienTB #modal-alamat").val(pasien.pasien_alamat);
    $("#modal-pasienTB #modal-notrans").val(pendaftaran.no_trans);
    $("#modal-pasienTB #modal-layanan").val(pendaftaran.penjamin_nama);
    $("#modal-pasienTB #modal-dokter")
        .val(pendaftaran.nip_dokter)
        .trigger("change");

    // setTimeout(function () {
    Swal.close();
    // scrollToInputSection();
    // }, 1000);
}
function editPasienTB(button) {
    var id = button.getAttribute("data-id");
    var norm = button.getAttribute("data-norm");
    var status = button.getAttribute("data-status");
    var petugas = button.getAttribute("data-petugas");
    var dokter = button.getAttribute("data-dokter");
    var nama = button.getAttribute("data-nama");
    var alamat = button.getAttribute("data-alamat");
    var statusPengobatan = button.getAttribute("data-hasilBerobat");

    document.getElementById("status-id").value = id;
    document.getElementById("status-norm").value = norm;
    // document.getElementById("status-pengobatan").value = status;
    document.getElementById("status-nama").value = nama;
    document.getElementById("status-alamat").value = alamat;
    $("#statusPengobatan").val(statusPengobatan).trigger("change");
}

function updateStatus(id) {
    console.log("🚀 ~ id:", id);
    var status = document.getElementById("modal-status").value;
    $.ajax({
        url: "/api/update/status/pengobatan",
        type: "POST",
        data: {
            id: id,
            status: status,
        },
        success: function (response) {
            console.log("🚀 ~ updateStatus ~ response:", response);
        },
        error: function (xhr) {
            // Handle error
        },
    });
}
function pasienKontrol() {
    var tanggal = $("#tanggal").val();
    $("#loadingSpinner").show();
    if ($.fn.DataTable.isDataTable("#Pkontrol")) {
        var table = $("#Pkontrol").DataTable();
        table.destroy();
    }

    $.ajax({
        url: "/api/pasien/TB/Kontrol",
        type: "GET",
        data: { date: tanggal },
        success: function (response) {
            $("#loadingSpinner").hide();
            response.forEach(function (item, index) {
                var alamat = `${item.kelurahan} ${item.rtrw} ${item.kecamatan}${item.kabupaten}`;
                item.diagnosa = `${item.diagnosa1}, ${item.diagnosa2}, ${item.diagnosa3}`;

                item.pasien = `${item.namapasien}`;
                item.actions = `<a href="#" class="edit"
                                data-id="${item.id}"
                                data-norm="${item.norm}"
                    data-nama="${item.namapasien}"
                    data-alamat="${alamat}"
                    data-layanan="${item.kelompok}"
                    data-notrans="${item.notrans}"
                    data-tgltrans="${item.tgltrans}"
                                ><i class="fas fa-pen-to-square pr-3"></i></a>`;
                item.no = index + 1;
                if (item.idKunjunganDots !== null) {
                    item.status = "sudah";
                } else {
                    item.status = "belum";
                }
            });

            $("#Pkontrol")
                .DataTable({
                    data: response,
                    columns: [
                        { data: "actions", className: "col-1 text-center" },
                        {
                            data: "status",
                            name: "idKunjunganDots",
                            render: function (data, type, row) {
                                var backgroundColor =
                                    data === "belum" ? "danger" : "success";
                                return `<div class="badge badge-${backgroundColor}">${data}</div>`;
                            },
                            className: "p-2",
                        },
                        { data: "nourut" },
                        { data: "norm" },
                        { data: "noktp" },
                        { data: "namapasien" },
                        { data: "dokterpoli" },
                        { data: "diagnosa" },
                    ],
                    order: [2, "asc"],
                    paging: true,
                    lengthMenu: [
                        [5, 10, 25, 50, -1],
                        [5, 10, 25, 50, "All"],
                    ],
                    pageLength: 5,
                    responsive: true,
                    lengthChange: false,
                    autoWidth: false,
                    buttons: ["copyHtml5", "excelHtml5", "pdfHtml5", "colvis"],
                })
                .buttons()
                .container()
                .appendTo("#Pkontrol_wrapper .col-md-6:eq(0)");

            // Menangani klik pada tombol edit
            $(".edit").on("click", function (e) {
                e.preventDefault();
                var norm = $(this).data("norm");
                var date = $("#tanggal").val();
                searchByRM(norm, date);
            });
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        },
    });
}
function pasienTelat() {
    $("#loadingSpinner").show();
    if ($.fn.DataTable.isDataTable("#Ptelat, #Pdo")) {
        var table = $("#Ptelat, #Pdo").DataTable();
        table.destroy();
    }

    $.ajax({
        url: "/api/pasien/TB/Telat",
        type: "GET",
        success: function (response) {
            $("#loadingSpinner").hide();
            var data = response.data;
            var pasienTelat = data.filter(function (item) {
                // return item.status === "Telat";
                //filter berdasarkan status telat dan blnKe bukan Selesai pengobatab
                return (
                    item.status === "Telat" &&
                    item.blnKe !== "Selesai Pengobatan"
                );
            });
            // console.log("🚀 ~ pasienTelat ~ pasienTelat:", pasienTelat);
            var pasienDo = data.filter(function (item) {
                // return item.status === "DO";
                return (
                    item.status === "DO" && item.blnKe !== "Selesai Pengobatan"
                );
            });
            // console.log("🚀 ~ pasienDo ~ pasienDo:", pasienDo);
            tabelTelat(pasienTelat);
            tabelDo(pasienDo);
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        },
    });
}
function tabelTelat(pasienTelat) {
    pasienTelat.forEach(function (item, index) {
        item.no = index + 1;
        // item.aksi = `<a href="#" class="edit"
        // data-id="${item.id}"
        // data-norm="${item.norm}"
        // onclick="showModal(${item.id})"><i class="fas fa-pen-to-square pr-3"></i></a>`;
        item.aksi = `<button class="editTB bg-danger"
                                    data-id="${item.id}"
                                    data-norm="${item.norm}"
                                    data-petugas="${item.petugas}"
                                    data-dokter="${item.dokter}"
                                    data-nama="${item.nama}"
                                    data-alamat="${item.alamat}"
                                    data-hasilBerobat="${item.hasilBerobat}"
                                    data-toggle="modal"
                                    data-target="#modal-update"
                                    onclick="editPasienTB(this);"><i class="fa-solid fa-file-pen"></i></button>`;
    });
    $("#Ptelat")
        .DataTable({
            data: pasienTelat,
            columns: [
                {
                    data: "aksi",
                },
                { data: "selisih" },
                { data: "nxKontrol" },
                { data: "terakhir" },
                { data: "no" },
                { data: "norm" },
                { data: "noHP" },
                { data: "blnKe" },
                { data: "nama" },
                { data: "alamat" },
                { data: "dokter" },
            ],
            paging: true,
            lengthMenu: [
                [5, 10, 25, 50, -1],
                [5, 10, 25, 50, "All"],
            ],
            pageLength: 5,
            responsive: true,
            lengthChange: false,
            autoWidth: false,
            buttons: ["copyHtml5", "excelHtml5", "pdfHtml5", "colvis"],
        })
        .buttons()
        .container()
        .appendTo("#Ptelat_wrapper .col-md-6:eq(0)");
}
function tabelDo(pasienDo) {
    pasienDo.forEach(function (item, index) {
        item.no = index + 1;
        // item.aksi = `<a href="#" class="edit"
        // data-id="${item.id}"
        // data-norm="${item.norm}"
        // onclick="showModal(${item.id})"><i class="fas fa-pen-to-square pr-3"></i></a>`;
        item.aksi = `<button class="editTB bg-danger"
                                    data-id="${item.id}"
                                    data-norm="${item.norm}"
                                    data-petugas="${item.petugas}"
                                    data-dokter="${item.dokter}"
                                    data-nama="${item.nama}"
                                    data-alamat="${item.alamat}"
                                    data-hasilBerobat="${item.hasilBerobat}"
                                    data-toggle="modal"
                                    data-target="#modal-update"
                                    onclick="editPasienTB(this);"><i class="fa-solid fa-file-pen"></i></button>`;
    });
    $("#Pdo")
        .DataTable({
            data: pasienDo,
            columns: [
                {
                    data: "aksi",
                },
                { data: "selisih" },
                { data: "nxKontrol" },
                { data: "terakhir" },
                { data: "no" },
                { data: "norm" },
                { data: "noHP" },
                { data: "blnKe" },
                { data: "nama" },
                { data: "alamat" },
                { data: "dokter" },
            ],
            paging: true,
            lengthMenu: [
                [5, 10, 25, 50, -1],
                [5, 10, 25, 50, "All"],
            ],
            pageLength: 5,
            responsive: true,
            lengthChange: false,
            autoWidth: false,
            buttons: ["copyHtml5", "excelHtml5", "pdfHtml5", "colvis"],
        })
        .buttons()
        .container()
        .appendTo("#Pdo_wrapper .col-md-6:eq(0)");
}

function pasienTB() {
    $("#loadingSpinner").show();
    if ($.fn.DataTable.isDataTable("#Ptb,#modal-Ptb")) {
        var table = $("#Ptb,#modal-Ptb").DataTable();
        table.destroy();
    }

    $.ajax({
        url: "/api/pasien/TB",
        type: "POST",
        success: function (response) {
            $("#loadingSpinner").hide();
            var dataArray = response.data || [];
            // console.log("🚀 ~ pasienTB ~ dataArray:", dataArray);
            dataArray.forEach(function (item, index) {
                item.actions = `<button class="editTB bg-danger"
                                   data-id="${item.id}"
                                    data-norm="${item.norm}"
                                    data-petugas="${item.petugas}"
                                    data-dokter="${item.dokter}"
                                    data-nama="${item.nama}"
                                    data-alamat="${item.alamat}"
                                    data-hasilBerobat="${item.hasilBerobat}"
                                    data-toggle="modal"
                                    data-target="#modal-update"
                                    onclick="editPasienTB(this);"><i class="fa-solid fa-file-pen"></i></button>
                                <button class="riwayat bg-green"
                                    data-id="${item.id}"
                                    onclick="showRiwayatKunjungan('${item.norm}','modal');" data-toggle="modal" data-target="#modal-RiwayatKunjungan"><i class="fa-regular fa-folder-open"></i></button>`;
                item.no = index + 1;
                item.dokter = `${item.dokter.gelar_d} ${item.dokter.biodata.nama} ${item.dokter.gelar_b}`;
            });

            $("#Ptb, #modal-Ptb")
                .DataTable({
                    data: dataArray,
                    columns: [
                        { data: "actions", className: "col-1 text-center" },
                        {
                            data: "tglMulai",
                            className: "col-1 text-center",
                        },
                        { data: "no" },
                        { data: "norm" },
                        { data: "noHP" },
                        { data: "statusPengobatan" },
                        { data: "nama" },
                        { data: "alamat" },
                        { data: "dokter" },
                    ],
                    order: [2, "dsc"],
                    paging: true,
                    lengthMenu: [
                        [5, 10, 25, 50, -1],
                        [5, 10, 25, 50, "All"],
                    ],
                    pageLength: 5,
                    responsive: true,
                    lengthChange: false,
                    autoWidth: false,
                    buttons: ["copyHtml5", "excelHtml5", "pdfHtml5", "colvis"],
                })
                .buttons()
                .container()
                .appendTo("#Ptb_wrapper .col-md-6:eq(0)");
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        },
    });
}

function showRiwayatKunjungan(norm, modal) {
    if (norm == null) norm = $("#norm").val();
    $("#loadingSpinner").show();

    // Pilih tabel yang akan digunakan berdasarkan kondisi modal
    var tableId = modal ? "#modal-kunjDots" : "#kunjDots";

    if ($.fn.DataTable.isDataTable(tableId)) {
        var table = $(tableId).DataTable();
        table.destroy();
    }

    $.ajax({
        url: "/api/kunjungan/Dots",
        type: "POST",
        data: {
            norm: norm,
        },
        success: function (response) {
            $("#loadingSpinner").hide();
            response.forEach(function (item, index) {
                item.actions = `<button class="editTB bg-danger"
                                data-id="${item.id}"
                                data-norm="${item.norm}"
                                ><i class="fas fa-pen-to-square"></i></button>`;
                item.no = index + 1;
                item.dokter = `${item.dokter.gelar_d} ${item.dokter.biodata.nama} ${item.dokter.gelar_b}`;
                item.petugas = `${item.petugas.gelar_d} ${item.petugas.biodata.nama} ${item.petugas.gelar_b}`;
                item.tgl = new Date(item.created_at).toLocaleDateString(
                    "id-ID",
                    { year: "numeric", month: "numeric", day: "numeric" }
                );
                item.bb = item.bb + " kg";
            });

            $(tableId)
                .DataTable({
                    data: response,
                    columns: [
                        { data: "actions", className: "col-1 text-center" },
                        { data: "norm" },
                        { data: "tgl" },
                        { data: "bln.nmBlnKe" },
                        { data: "bta" },
                        { data: "bb" },
                        { data: "terapi" },
                        { data: "petugas", className: "col-3" },
                        { data: "dokter", className: "col-3" },
                    ],
                    order: [2, "desc"],
                    paging: true,
                    lengthMenu: [
                        [5, 10, 25, 50, -1],
                        [5, 10, 25, 50, "All"],
                    ],
                    pageLength: 5,
                    responsive: true,
                    lengthChange: false,
                    autoWidth: false,
                    buttons: ["copyHtml5", "excelHtml5", "pdfHtml5", "colvis"],
                })
                .buttons()
                .container()
                .appendTo(
                    $(tableId)
                        .DataTable()
                        .buttons()
                        .container()
                        .appendTo(
                            `#${$(tableId).attr("id")}_wrapper .col-md-6:eq(0)`
                        )
                );

            // Menangani klik pada tombol edit
            $(".editTB").on("click", function (e) {
                e.preventDefault();
                var norm = $(this).data("norm");
                var date = $("#tanggal").val();
                // performCariRM(norm);
                // searchPTB(norm);
            });
            fillIdentitasTBRiwayat(response);
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        },
    });
}

function fillIdentitasTBRiwayat(data) {
    console.log("🚀 ~ fillIdentitasTBRiwayat ~ data:", data);

    // Pastikan data yang diterima adalah array dan memiliki minimal satu elemen
    if (!Array.isArray(data) || data.length === 0) {
        console.error("Data tidak valid atau kosong");
        return;
    }

    var riwayatNorm = $("#riwayat-norm");
    var riwayatNama = $("#riwayat-nama");
    var riwayatHp = $("#riwayat-hp");
    var riwayatNik = $("#riwayat-nik");
    var riwayatAlamat = $("#riwayat-alamat");

    // Ambil data pertama dari array (asumsi hanya satu data yang dikirim)
    var riwayat = data[0];

    // Mengisi nilai ke dalam elemen-elemen HTML
    riwayatNorm.text(": " + riwayat.norm);
    riwayatNama.text(": " + riwayat.pasien.nama);
    riwayatHp.text(": " + riwayat.pasien.noHP);
    riwayatNik.text(": " + riwayat.pasien.nik);
    riwayatAlamat.text(": " + riwayat.pasien.alamat);
}
