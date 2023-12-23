function fetchDataAntrian(tanggal, callback) {
    $.ajax({
        url: "/api/antrianKasir",
        type: "POST",
        data: {
            date: tanggal,
        },
        success: function (response) {
            callback(response);
        },
        error: function (xhr) {},
    });
}

function initializeDataAntrian(response) {
    response.forEach(function (item) {
        var dokter = `${item.petugas.pegawai.gelar_d} ${item.petugas.pegawai.nama} ${item.petugas.pegawai.gelar_b}`;
        var alamat = `${item.biodata.kelurahan}, ${item.biodata.rtrw}, ${item.biodata.kecamatan}, ${item.biodata.kabupaten}`;
        var layanan = `${item.kelompok.kelompok}`;
        var nama = `${item.biodata.nama}`;
        var kddokter = `${item.petugas.p_dokter_poli}`;
        item.aksi = `<a href="#" class="aksi-button px-2"
                data-norm="${item.norm}"
                data-nama="${nama}"
                data-dokter="${dokter}"
                data-kddokter="${kddokter}"
                data-alamat="${alamat}"
                data-layanan="${layanan}"
                data-notrans="${item.notrans}"
                data-tgltran="${item.tgltran}"><i class="fas fa-pen-to-square"></i></a>`;
        if (item.farmasi === null) {
            item.status = "belum";
        } else {
            item.status = "sudah";
        }
    });

    $("#dataAntrian").DataTable({
        data: response,
        columns: [
            { data: "aksi", className: "text-center" },
            {
                data: "status",
                name: "status",
                render: function (data, type, row) {
                    var backgroundColor =
                        data === "belum" ? "danger" : "success";
                    return `<div class="badge text-bg-${backgroundColor}">${data}</div>`;
                },
            },

            { data: "nourut" },
            { data: "norm" },
            {
                data: null,
                render: function (data, type, full, meta) {
                    if (type === "display" && data.kelompok) {
                        return data.kelompok.kelompok;
                    }
                    return "";
                },
            },
            {
                data: null,
                render: function (data, type, full, meta) {
                    if (type === "display" && data.biodata) {
                        return data.biodata.nama;
                    }
                    return "";
                },
            },

            {
                data: null,
                render: function (data, type, full, meta) {
                    if (
                        type === "display" &&
                        data.petugas &&
                        data.petugas.pegawai
                    ) {
                        return (
                            data.petugas.pegawai.gelar_d +
                            " " +
                            data.petugas.pegawai.nama +
                            " " +
                            data.petugas.pegawai.gelar_b
                        );
                    }
                    return "";
                },
            },
        ],
        order: [
            [1, "asc"],
            [2, "asc"],
        ],
    });
}
function antrian() {
    $("#loadingSpinner").show();
    var tanggal = $("#tanggal").val();

    fetchDataAntrian(tanggal, function (response) {
        $("#loadingSpinner").hide();
        if ($.fn.DataTable.isDataTable("#dataAntrian")) {
            var table = $("#dataAntrian").DataTable();
            response.forEach(function (item) {
                if (item.petugas && item.petugas.pegawai) {
                    var dokter = `${item.petugas.pegawai.gelar_d} ${item.petugas.pegawai.nama} ${item.petugas.pegawai.gelar_b}`;
                    var alamat = `${item.biodata.kelurahan}, ${item.biodata.rtrw}, ${item.biodata.kecamatan}, ${item.biodata.kabupaten}`;
                    var layanan = `${item.kelompok.kelompok}`;
                    var nama = `${item.biodata.nama}`;
                    var kddokter = `${item.petugas.p_dokter_poli}`;
                    item.aksi = `<a href="#" class="aksi-button px-2"
                        data-norm="${item.norm}"
                        data-nama="${nama}"
                        data-dokter="${dokter}"
                        data-kddokter="${kddokter}"
                        data-alamat="${alamat}"
                        data-layanan="${layanan}"
                        data-notrans="${item.notrans}"
                        data-tgltran="${item.tgltran}"><i class="fas fa-pen-to-square"></i></a>`;
                    if (item.tindakan === null) {
                        item.status = "belum";
                    } else {
                        item.status = "sudah";
                    }
                } else {
                    $("#dataAntrian").DataTable();
                }
            });
            table.clear().rows.add(response).draw();
        } else {
            initializeDataAntrian(response);
        }
    });
}
function searchByRM(norm) {
    $.ajax({
        url: "/api/cariRM",
        type: "post",
        data: {
            norm: norm,
        },
        success: function (response) {
            // Mendapatkan data dari respons JSON
            var noRM = response[0].norm; // Menggunakan indeks 0 karena respons adalah array
            var nama = response[0].biodata.nama;
            var notrans = response[0].notrans;
            var layanan = response[0].kelompok.kelompok;
            var dokter = response[0].petugas.p_dokter_poli;
            var alamat = `${response[0].biodata.kelurahan}, ${response[0].biodata.rtrw}, ${response[0].biodata.kecamatan}, ${response[0].biodata.kabupaten}`;
            // Dapatkan data lainnya dari respons JSON sesuai kebutuhan

            // Mengisikan data ke dalam elemen-elemen HTML
            $("#norm").val(noRM);
            $("#nama").val(nama);
            $("#alamat").val(alamat);
            $("#notrans").val(notrans);
            $("#layanan").val(layanan);
            $("#dokter").val(dokter);
            $("#dokter").trigger("change");
            // Mengisi elemen-elemen lainnya sesuai kebutuhan
        },
        error: function (xhr) {
            // Handle error
        },
    });
}

function dataFarmasi() {
    var notrans = $("#notrans").val();

    if ($.fn.DataTable.isDataTable("#dataFarmasi")) {
        var table = $("#dataFarmasi").DataTable();
        table.destroy();
    }

    $.ajax({
        url: "/api/transaksifarmasi",
        type: "GET",
        data: { notrans: notrans },
        success: function (response) {
            response.forEach(function (item, index) {
                var dokter = `${item.dokter_pegawai.gelar_d} ${item.dokter_pegawai.nama} ${item.dokter_pegawai.gelar_b}`;
                var petugas = `${item.petugas_pegawai.gelar_d} ${item.petugas_pegawai.nama} ${item.petugas_pegawai.gelar_b}`;
                var tindakan = `${item.tindakan.nmTindakan}`;
                item.actions = `<a href="" class="edit"
                                    data-id="${item.id}"
                                    data-kdtind="${item.kdTind}"
                                    data-tindakan="${tindakan}"
                                    data-norm="${item.norm}"
                                    data-petugas="${petugas}"
                                    data-dokter="${dokter}"><i class="fas fa-pen-to-square pr-3"></i></a>
                                <a href="" class="delete"
                                    data-id="${item.id}"
                                    data-kdTind="${item.kdTind}"
                                    data-tindakan="${tindakan}"
                                    data-norm="${item.norm}"
                                    data-petugas="${item.petugas_pegawai.nama}"
                                    data-dokter="${item.dokter_pegawai.nama}"><i class="fas fa-trash"></i></a>`;
                item.no = index + 1;
                if (item.transbmhp.length > 0) {
                    item.status = "sudah";
                } else {
                    item.status = "belum";
                }
            });

            $("#dataFarmasi").DataTable({
                data: response,
                columns: [
                    { data: "actions", className: "text-center" },
                    { data: "no" },
                    {
                        data: "status",
                        name: "kdTind",
                        render: function (data, type, row) {
                            var backgroundColor =
                                data === "belum" ? "danger" : "success";
                            return `<div class="badge text-bg-${backgroundColor}">${data}</div>`;
                        },
                    },

                    { data: "norm" },

                    {
                        data: "tindakan.nmTindakan",
                    },

                    {
                        data: "petugas_pegawai.nama",
                    },

                    {
                        data: "dokter_pegawai.nama",
                    },
                ],
                order: [2, "asc"],
            });
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        },
    });
}
