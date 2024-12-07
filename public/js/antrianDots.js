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
            tanggal: date,
        },
        success: function (response) {
            if (response.error) {
                console.error("Error: " + response.error);
                Swal.fire({
                    icon: "error",
                    title:
                        "Koneksi Kominfo, Silahkan Coba Lagi\n Error: " +
                        response.error,
                });
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
                            var dx = response.data[0].diagnosa;
                            isiBiodataModal(
                                norm,
                                date,
                                pasien,
                                pendaftaran,
                                dx
                            );
                            $("#modal-pasienTB").modal("show");
                        }
                    });
                } else if (code === 200) {
                    var ptb = response.data[0].ptb;
                    console.log("🚀 ~ cariPasienTb ~ ptb:", ptb);
                    const statusBerobat = ptb.statusPengobatan;
                    const hasilBerobat = ptb.hasilBerobat;
                    console.log(
                        "🚀 ~ cariPasienTb ~ statusBerobat:",
                        statusBerobat
                    );
                    var pasien = response.data[0].pasien;
                    var pendaftaran = response.data[0].pendaftaran[0];

                    // if (
                    //     pendaftaran === null ||
                    //     pendaftaran === undefined ||
                    //     pendaftaran === ""
                    // ) {
                    //     console.log("Pendaftaran Kosong");
                    //     Swal.fire({
                    //         icon: "info",
                    //         title: "Pasien Tidak Terdaftar di Pendaftaran Kunjungan Hari ini",
                    //     });
                    // } else {
                    if (["95", "96", "97", "98", "99"].includes(hasilBerobat)) {
                        // if (["4", "5"].includes(hasilBerobat)) {
                        Swal.fire({
                            icon: "question",
                            title:
                                "Pasien dinyatakan " +
                                statusBerobat +
                                ", ingin melajutkan pendaftaran pasien?",
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "YA",
                            cancelButtonText: "TIDAK",
                        }).then((result) => {
                            // Display a confirmation dialog
                            if (result.isConfirmed) {
                                var pasien = response.data[0].pasien;
                                var pendaftaran = response.data[0].pendaftaran;
                                console.log(
                                    "🚀 ~ cariPasienTb ~ pendaftaran:",
                                    pendaftaran
                                );
                                var dx = response.data[0].diagnosa;
                                isiBiodataModal(
                                    norm,
                                    date,
                                    pasien,
                                    pendaftaran,
                                    dx
                                );
                                $("#modal-pasienTB").modal("show");
                            }
                        });
                    } else {
                        isiIdentitas(pasien, pendaftaran);
                        showRiwayatKunjungan(norm);
                    }
                    // }
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
    $("#notrans").val(pendaftaran.no_reg);
    $("#layanan").val(pendaftaran.penjamin_nama);
    $("#dokter").val(pendaftaran.nip_dokter).trigger("change");

    setTimeout(function () {
        Swal.close();
        scrollToInputSection();
    }, 1000);
}
function isiBiodataModal(norm, date, pasien, pendaftaran, dx) {
    console.log("🚀 ~ isiBiodataModal ~ pendaftaran:", pendaftaran);
    const noHP = pasien.pasien_no_hp || "";
    const nik = pasien.pasien_nik || "";
    const nama = pasien.pasien_nama || "";
    const alamat = pasien.pasien_alamat || "";
    const noTrans = pendaftaran.no_reg || "";
    const layanan = pendaftaran.penjamin_nama || "";
    const dokter = pendaftaran.nip_dokter || "";
    const kdDx = dx[0] || "";

    $("#modal-pasienTB #modal-norm").val(norm);
    $("#modal-pasienTB #modal-hp").val(noHP);
    $("#modal-pasienTB #modal-nik").val(nik);
    $("#modal-pasienTB #modal-nama").val(nama);
    $("#modal-pasienTB #modal-alamat").val(alamat);
    $("#modal-pasienTB #modal-notrans").val(noTrans);
    $("#modal-pasienTB #modal-layanan").val(layanan);
    $("#modal-pasienTB #modal-dokter").val(dokter).trigger("change");
    $("#modal-pasienTB #modal-kdDx").val(kdDx).trigger("change");
    Swal.close();
}
function editPasienTB(button) {
    console.log("🚀 ~ editPasienTB ~ editPasienTB:", editPasienTB);
    var id = button.getAttribute("data-id");
    var norm = button.getAttribute("data-norm");
    var nik = button.getAttribute("data-nik");
    var nama = button.getAttribute("data-nama");
    var alamat = button.getAttribute("data-alamat");
    var noHP = button.getAttribute("data-noHP");
    var tcm = button.getAttribute("data-tcm");
    var sample = button.getAttribute("data-sample");
    var kdDx = button.getAttribute("data-kdDx");
    var tgl = button.getAttribute("data-tglmulai");
    var tglmulai = new Date(tgl).toISOString().split("T")[0];
    var bb = button.getAttribute("data-bb");
    var obat = button.getAttribute("data-obat");
    var hiv = button.getAttribute("data-hiv");
    var dm = button.getAttribute("data-dm");
    var ket = button.getAttribute("data-ket");
    var hasilBerobat = button.getAttribute("data-hasilBerobat");
    var statusPengobatan = button.getAttribute("data-statusPengobatan");
    var petugas = button.getAttribute("data-petugas");
    var dokter = button.getAttribute("data-dokter");

    $("#modal-update-id").val(id);
    $("#modal-update-norm").val(norm);
    $("#modal-update-nik").val(nik);
    $("#modal-update-nama").val(nama);
    $("#modal-update-alamat").val(alamat);
    $("#modal-update-hp").val(noHP);
    $("#modal-update-kdDx").val(kdDx).trigger("change");
    $("#modal-update-tglmulai").val(tglmulai);
    $("#modal-update-bb").val(bb);
    $("#modal-update-obtDots").val(obat).trigger("change");
    $("#modal-update-hiv").val(hiv).trigger("change");
    $("#modal-update-dm").val(dm).trigger("change");
    $("#modal-update-ket").val(ket);
    $("#modal-update-blnKe").val(hasilBerobat).trigger("change");
    $("#modal-update-status").val(statusPengobatan).trigger("change");
    $("#modal-update-tcm").val(tcm).trigger("change");
    $("#modal-update-sample").val(sample).trigger("change");
    $("#modal-update-dokter").val(dokter).trigger("change");
    $("#modal-update-petugas").val(petugas).trigger("change");
}

function updateStatus(id) {
    Swal.fire({
        title: "Poses Update Berlangsung...",
        didOpen: () => {
            Swal.showLoading();
        },
    });
    console.log("🚀 ~ id:", id);
    var id = $("#modal-update-id").val();
    var norm = $("#modal-update-norm").val();
    var nik = $("#modal-update-nik").val();
    var nama = $("#modal-update-nama").val();
    var alamat = $("#modal-update-alamat").val();
    var noHP = $("#modal-update-hp").val();
    var tcm = $("#modal-update-tcm").val();
    var sample = $("#modal-update-sample").val();
    var kdDx = $("#modal-update-kdDx").val();
    var tglMulai = $("#modal-update-tglmulai").val();
    var bb = $("#modal-update-bb").val();
    var obat = $("#modal-update-obtDots").val();
    var hiv = $("#modal-update-hiv").val();
    var dm = $("#modal-update-dm").val();
    var ket = $("#modal-update-ket").val();
    var hasilBerobat = $("#modal-update-blnKe").val();
    var status = $("#modal-update-status").val();
    var petugas = $("#modal-update-petugas").val();
    var dokter = $("#modal-update-dokter").val();
    $.ajax({
        url: "/api/pasien/TB_update",
        type: "POST",
        data: {
            id: id,
            norm: norm,
            nik: nik,
            nama: nama,
            alamat: alamat,
            noHP: noHP,
            tcm: tcm,
            sample: sample,
            kdDx: kdDx,
            tglMulai: tglMulai,
            bb: bb,
            obat: obat,
            hiv: hiv,
            dm: dm,
            ket: ket,
            hasilBerobat: hasilBerobat,
            status: status,
            petugas: petugas,
            dokter: dokter,
        },
        success: function (response) {
            console.log("🚀 ~ updateStatus ~ response:", response);
            Swal.fire({
                icon: "success",
                title: "Berhasil Diupdate, Maturnuwun...!!!",
            });
            document.getElementById("updatePengobatanTB").reset();
            pasienTB();
            pasienTelat();
            $("#modal-update").modal("hide");
        },
        error: function (xhr) {
            // Handle error
        },
    });
}
function riwayatKunjungan(norm) {
    Swal.fire({
        icon: "info",
        title: "Sedang mencarikan data...!!! \n Pencarian dapat membutuhkan waktu lama, \n Mohon ditunggu...!!!",
        showConfirmButton: false,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });
    console.log("🚀 ~ riwayatKunjungan ~ norm:", norm);
    $.ajax({
        url: "/api/kominfo/kunjungan/riwayat",
        type: "POST",
        data: { no_rm: norm },
        success: function (response) {
            Swal.close();
            console.log("🚀 ~ riwayatKunjungan ~ response:", response);
            tabelRiwayatKunjungan(response); // Menampilkan tabel
            $("#historiKunjungan").modal("show"); // Menampilkan modal
        },
        error: function (xhr) {
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
    data.forEach(function (item, index) {
        item.no = index + 1; // Nomor urut dimulai dari 1
        item.diagnosa = `
                            <table>
                                <tr>
                                    <td><strong>DX 1 :</strong></td>
                                    <td>${item.dx1 || "-"}</td>
                                </tr>
                                <tr>
                                    <td><strong>DX 3 :</strong></td>
                                    <td>${item.dx2 || "-"}</td>
                                </tr>
                                <tr>
                                    <td><strong>DX 3 :</strong></td>
                                    <td>${item.dx3 || "-"}</td>
                                </tr>
                            </table>

                        `;
        item.anamnesa = `<div>
                            <p><strong>DS :</strong> ${item.ds || "-"}</p>
                            <p><strong>DO :</strong> ${item.do || "-"}</p>
                            <table>
                                <tr>
                                    <td><strong>TD :</strong> ${
                                        item.td || "-"
                                    } mmHg</td>
                                    <td><strong>Nadi :</strong> ${
                                        item.nadi || "-"
                                    } X/mnt</td>
                                </tr>
                                <tr>
                                    <td><strong>BB :</strong> ${
                                        item.bb || "-"
                                    } Kg</td>
                                    <td><strong>Suhu :</strong> ${
                                        item.suhu || "-"
                                    } °C</td>
                                </tr>
                                <tr>
                                    <td><strong>RR :</strong> ${
                                        item.rr || "-"
                                    } X/mnt</td>
                                </tr>
                            </table>
                        </div>`;
    });

    // Hancurkan DataTable sebelumnya jika ada
    const table = $("#riwayatKunjungan").DataTable();
    if ($.fn.DataTable.isDataTable("#riwayatKunjungan")) {
        table.destroy();
    }

    // Inisialisasi DataTable baru
    $("#riwayatKunjungan").DataTable({
        data: data,
        columns: [
            { data: "tanggal", className: "col-1 text-center" },
            { data: "pasien_no_rm", className: "col-1 text-center" },
            { data: "pasien_nama", className: " text-center" },
            { data: "dokter_nama", className: "text-center" },
            { data: "diagnosa", className: "col-4" },
            { data: "anamnesa" },
        ],
        paging: true,
        order: [0, "desc"], // Mengurutkan berdasarkan tanggal
        lengthMenu: [
            [5, 10, 25, 50, -1],
            [5, 10, 25, 50, "All"],
        ],
        pageLength: 5,
        responsive: true,
    });
}

function pasienTelat() {
    $("#loadingSpinner").show();
    if ($.fn.DataTable.isDataTable("#Ptelat, #Pdo, #Pkontrol")) {
        $("#Ptelat, #Pdo, #Pkontrol").DataTable().destroy();
    }

    $.ajax({
        url: "/api/pasien/TB/Telat",
        type: "GET",
        success: function (response) {
            $("#loadingSpinner").hide();
            const data = response.data;

            const pasienTelat = data.filter(
                (item) =>
                    item.status === "Telat" &&
                    item.blnKe !== "Selesai Pengobatan"
            );
            const pasienDo = data.filter(
                (item) =>
                    item.status === "DO" && item.blnKe !== "Selesai Pengobatan"
            );
            const pasienKontrol = data.filter(
                (item) => item.status === "Tepat Waktu"
            );

            generateTable(
                "#Ptelat",
                pasienTelat,
                "Data Pasien TBC Telat di KKPM"
            );
            generateTable("#Pdo", pasienDo, "Data Pasien TBC DO di KKPM");
            generateTable(
                "#Pkontrol",
                pasienKontrol,
                "Data Pasien TBC Tepat Waktu di KKPM"
            );
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        },
    });
}

function generateTable(selector, data, title) {
    data.forEach((item, index) => {
        item.no = index + 1;
        item.aksi = `
            <button class="editTB bg-danger"
                    data-id="${item.id}"
                    data-norm="${item.norm}"
                    data-nik="${item.nik}"
                    data-nama="${item.nama}"
                    data-alamat="${item.alamat}"
                    data-noHP="${item.noHP}"
                    data-tcm="${item.tcm}"
                    data-sample="${item.sample}"
                    data-kdDx="${item.kdDx}"
                    data-tglmulai="${item.tglMulai}"
                    data-bb="${item.bb}"
                    data-obat="${item.obat}"
                    data-hiv="${item.hiv}"
                    data-dm="${item.dm}"
                    data-ket="${item.ket}"
                    data-hasilBerobat="${item.hasilBerobat}"
                    data-statusPengobatan="${item.statusPengobatan}"
                    data-petugas="${item.petugas}"
                    data-dokter="${item.dokter}"
                    data-toggle="modal"
                    data-target="#modal-update"
                    data-toggle="tooltip" data-placement="right" title="Update Hasil Pengobatan dan TCM"
                    onclick="editPasienTB(this);" placeholder="Update Hasil Pengobatan dan TCM">
                <i class="fa-solid fa-file-pen"></i>
            </button>
            <br><br>
            <button class="riwayat bg-green" onclick="riwayatKunjungan('${item.norm}','modal');">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-folder-fill">
                    <path d="M9.828 3h3.982a2 2 0 0 1 1.992 2.181l-.637 7A2 2 0 0 1 13.174 14H2.825a2 2 0 0 1-1.991-1.819l-.637-7a2 2 0 0 1 .342-1.31L.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3m-8.322.12q.322-.119.684-.12h5.396l-.707-.707A1 1 0 0 0 6.172 2H2.5a1 1 0 0 0-1 .981z"/>
                </svg>
            </button>`;
    });

    $(selector)
        .DataTable({
            data: data,
            columns: [
                { data: "aksi" },
                { data: "selisih" },
                { data: "nxKontrol", className: "col-1" },
                { data: "terakhir", className: "col-1" },
                { data: "no" },
                { data: "norm" },
                { data: "noHP" },
                { data: "blnKe" },
                { data: "nama" },
                { data: "alamat" },
                { data: "namaDokter" },
            ],
            paging: true,
            order: [1, "desc"],
            lengthMenu: [
                [5, 10, 25, 50, -1],
                [5, 10, 25, 50, "All"],
            ],
            pageLength: 5,
            responsive: true,
            lengthChange: false,
            autoWidth: false,
            buttons: [
                { extend: "copyHtml5", text: "Salin" },
                {
                    extend: "excelHtml5",
                    text: "Excel",
                    title: title,
                    filename: title,
                },
                "colvis",
            ],
        })
        .buttons()
        .container()
        .appendTo(`${selector}_wrapper .col-md-6:eq(0)`);
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
            dataArray.forEach(function (item, index) {
                let ket = item.ket ? item.ket : "";

                item.actions = `<button class="editTB bg-danger"
                                   data-id="${item.id}"
                                    data-norm="${item.norm}"
                                    data-nik="${item.nik}"
                                    data-nama="${item.nama}"
                                    data-alamat="${item.alamat}"
                                    data-noHP="${item.noHP}"
                                    data-tcm="${item.tcm}"
                                    data-sample="${item.sample}"
                                    data-kdDx="${item.kdDx}"
                                    data-tglmulai="${item.tglMulai}"
                                    data-bb="${item.bb}"
                                    data-obat="${item.obat}"
                                    data-hiv="${item.hiv}"
                                    data-dm="${item.dm}"
                                    data-ket="${item.ket}"
                                    data-hasilBerobat="${item.hasilBerobat}"
                                    data-statusPengobatan="${item.statusPengobatan}"
                                    data-petugas="${item.petugas}"
                                    data-dokter="${item.dokter.nip}"
                                    data-toggle="modal"
                                    data-target="#modal-update"
                                    data-toggle="tooltip" data-placement="right" title="Update Hasil Pengobatan dan TCM"
                                    onclick="editPasienTB(this);"><i class="fa-solid fa-file-pen"></i></button><br><br>
                                <button class="riwayat bg-green"
                                    data-id="${item.id}"
                                    data-toggle="tooltip" data-placement="right" title="Riwayat Kunjungan"
                                    onclick="showRiwayatKunjungan('${item.norm}','modal');" data-toggle="modal" data-target="#modal-RiwayatKunjungan">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-folder-fill" viewBox="0 0 16 16">
                                    <path d="M9.828 3h3.982a2 2 0 0 1 1.992 2.181l-.637 7A2 2 0 0 1 13.174 14H2.825a2 2 0 0 1-1.991-1.819l-.637-7a2 2 0 0 1 .342-1.31L.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3m-8.322.12q.322-.119.684-.12h5.396l-.707-.707A1 1 0 0 0 6.172 2H2.5a1 1 0 0 0-1 .981z"/>
                                    </svg>
                                    </button>`;
                item.no = index + 1;
                item.dokter = `${item.dokter.gelar_d} ${item.dokter.biodata.nama} ${item.dokter.gelar_b}`;
                item.diagnosa = item.diagnosa
                    ? item.diagnosa.diagnosa
                    : "Diagnosa Belum diisi";
            });

            //filter dataArray, where created_at is today
            var today = new Date();
            var todayDate =
                today.getFullYear() +
                "-" +
                (today.getMonth() + 1) +
                "-" +
                today.getDate();
            var todayData = dataArray.filter(function (item) {
                return item.tglMulai === todayDate;
            });

            creatTabelPTB(todayData, "#modal-Ptb");
            creatTabelPTB(dataArray, "#Ptb");
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        },
    });
}

function creatTabelPTB(data, id) {
    $(id)
        .DataTable({
            data: data,
            columns: [
                { data: "actions", className: "text-center" },
                {
                    data: "tglMulai",
                    className: "col-1 text-center",
                },
                { data: "no" },
                { data: "norm" },
                { data: "noHP" },
                { data: "nik" },
                { data: "nama" },
                { data: "alamat" },
                { data: "dokter" },
                { data: "status" },
                { data: "hasilPengobatan" },
                { data: "diagnosa" },
                { data: "ket" },
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
            buttons: [
                {
                    extend: "copyHtml5",
                    text: "Salin",
                },
                {
                    extend: "excelHtml5",
                    text: "Excel",
                    title: "Data Pasien TBC di KKPM",
                    filename: "Data Pasien TBC di KKPM",
                },
                "colvis",
            ],
        })
        .buttons()
        .container()
        .appendTo(id + "_wrapper .col-md-6:eq(0)");
}

function showRiwayatKunjungan(norm, modal) {
    if (norm == null) norm = $("#norm").val();

    // Pilih tabel yang akan digunakan berdasarkan kondisi modal
    var tableId = modal ? "#modal-kunjDots" : "#kunjDots";

    if (modal === "modal") {
        Swal.fire({
            title: "Loading...",
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: function () {
                Swal.showLoading();
            },
        });
        $("#modal-RiwayatKunjungan").modal("show");
    }

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
            Swal.close();
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
                    // order: [2, "desc"],
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
