function dataTindakan(notrans) {
    console.log("🚀 ~ dataTindakan ~ notrans:", notrans);
    var notrans = notrans ? notrans : $("#notrans").val();
    console.log("🚀 ~ dataTindakan ~ notrans:", notrans);

    if ($.fn.DataTable.isDataTable("#dataTindakan")) {
        var tabletindakan = $("#dataTindakan").DataTable();
        tabletindakan.destroy();
    }

    $.ajax({
        url: "/api/cariDataTindakan",
        type: "post",
        data: { notrans: notrans },
        success: function (response) {
            response.forEach(function (item, index) {
                var dokter = `${item.dokter.gelar_d} ${item.dokter.biodata.nama} ${item.dokter.gelar_b}`;
                var petugas = `${item.petugas.gelar_d} ${item.petugas.biodata.nama} ${item.petugas.gelar_b}`;
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
                                    data-petugas="${petugas}"
                                    data-dokter="${dokter}"><i class="fas fa-trash"></i></a>`;
                item.no = index + 1;
                if (item.transbmhp.length > 0) {
                    item.status = "sudah";
                } else {
                    item.status = "belum";
                }
            });

            $("#dataTindakan").DataTable({
                data: response,
                columns: [
                    { data: "actions", className: "text-center col-1 p-2" },
                    {
                        data: "status",
                        name: "kdTind",
                        render: function (data, type, row) {
                            var backgroundColor =
                                data === "belum" ? "danger" : "success";
                            return `<div class="badge badge-${backgroundColor}">${data}</div>`;
                        },
                        className: "p-2",
                    },
                    { data: "norm", className: "text-center col-2 p-2" },
                    { data: "tindakan.nmTindakan", className: "p-2" },
                    { data: "petugas.biodata.nama", className: "p-2" },
                    { data: "dokter.biodata.nama", className: "p-2" },
                ],
                order: [2, "asc"],
            });
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        },
    });
}
function dataBMHP() {
    var idTind = $("#modalidTind").val();
    if ($.fn.DataTable.isDataTable("#transaksiBMHP")) {
        var tabletindakan = $("#transaksiBMHP").DataTable();
        tabletindakan.clear().destroy();
    }

    $.ajax({
        url: "/api/cariTransaksiBmhp",
        type: "post",
        data: { idTind: idTind },
        success: function (response) {
            response.forEach(function (item, index) {
                item.actions = `<a href="" class="delete"
                                    data-id="${item.id}"
                                    data-idTind="${item.idTind}"
                                    data-kdtind="${item.kdTind}"
                                    data-bmhp="${item.bmhp.nmObat}"
                                    data-kdBmhp="${item.kdBmhp}"
                                    data-jumlah="${item.jumlah}">
                                    <i class="fas fa-trash"></i></a>`;
                item.no = index + 1; // Nomor urut dimulai dari 1, bukan 0
            });

            $("#transaksiBMHP").DataTable({
                data: response,
                columns: [
                    { data: "actions", className: "text-center" },
                    { data: "no" },
                    { data: "bmhp.nmObat" },
                    { data: "jml" },
                    { data: "biaya" },
                ],
                order: [2, "asc"],
                paging: true,
                pageLength: 5,
            });
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        },
    });
}
function fetchDataAntrian(params, callback) {
    console.log("🚀 ~ fetchDataAntrian ~ params:", params);
    $.ajax({
        url: "/api/cpptKominfo",
        type: "post",
        data: params, // Mengirimkan array params sebagai data
        success: function (response) {
            callback(response);
        },
        error: function (xhr) {
            // Tangani kesalahan jika diperlukan
        },
    });
}

function initializeDataAntrian(response) {
    // Pastikan response.data adalah objek yang berisi data pasien
    if (response && response.response && response.response.data) {
        // var dataArray = Object.values(response.response.data); // Mengubah objek ke dalam array nilai-nilai
        var dataArray = response.response.data.filter(function (item) {
            // s;
            return item.status === "belum";
        });
        dataArray.forEach(function (item) {
            var asktind = "";
            // Pastikan item.tindakan adalah array sebelum mengaksesnya
            if (item.tindakan && Array.isArray(item.tindakan)) {
                item.tindakan.forEach(function (tindakan) {
                    asktind += `${tindakan.nama_tindakan} : ${tindakan.nama_obat},\n`;
                });
            }
            item.asktind = asktind;
            item.index = dataArray.indexOf(item) + 1;

            var alamat = `${item.kelurahan_nama}, ${item.pasien_rt}/${item.pasien_rw}, ${item.kecamatan_nama}, ${item.kabupaten_nama}`;
            item.aksi = `<a href="#" class="aksi-button btn-sm btn-primary px-2 icon-link icon-link-hover"
                            data-norm="${item.pasien_no_rm}"
                            data-nama="${item.pasien_nama}"
                            data-dokter="${item.dokter_nama}"
                            data-asktind="${asktind}"
                            data-kddokter="${item.nip_dokter}"
                            data-alamat="${alamat}"
                            data-layanan="${item.penjamin_nama}"
                            data-notrans="${item.no_trans}"
                            data-tgltrans="${item.tanggal}"><i class="fas fa-pen-to-square"></i></a>`;
        });

        $("#dataAntrian").DataTable({
            data: dataArray,
            columns: [
                { data: "aksi", className: "text-center p-2" },
                {
                    data: "status",
                    className: "text-center p-2",
                    render: function (data, type, row) {
                        var backgroundColor =
                            data === "belum" ? "danger" : "success";
                        return `<div class="badge badge-${backgroundColor}">${data}</div>`;
                    },
                },
                { data: "index", className: "text-center p-2" },
                { data: "pasien_no_rm", className: "text-center p-2" },
                { data: "penjamin_nama", className: "text-center p-2" },
                { data: "pasien_nama", className: "p-2 col-3" },
                { data: "asktind", className: "p-2 col-3" },
                { data: "dokter_nama", className: "p-2 col-3" },
            ],
            order: [
                [1, "asc"],
                [2, "asc"],
            ],
        });
    } else {
        console.error(
            "Invalid response or response.response.data is not available:",
            response
        );
        // Handle error or display appropriate message
    }
}

function antrian() {
    $("#loadingSpinner").show();
    var tanggal_awal = $("#tanggal").val(); // Ganti id input tanggal_awal
    var tanggal_akhir = $("#tanggal").val(); // Ganti id input tanggal_akhir
    // var no_rm = $("#norm").val(); // Ganti id input no_rm

    var param = {
        tanggal_awal: tanggal_awal,
        tanggal_akhir: tanggal_akhir,
        // no_rm: no_rm,
    };

    fetchDataAntrian(param, function (response) {
        $("#loadingSpinner").hide();

        if ($.fn.DataTable.isDataTable("#dataAntrian")) {
            var table = $("#dataAntrian").DataTable();
            if (response && response.response && response.response.data) {
                // var dataArray = Object.values(response.response.data); // Mengubah objek ke dalam array nilai-nilai
                var dataArray = response.response.data.filter(function (item) {
                    // s;
                    return item.status === "belum";
                });
                dataArray.forEach(function (item) {
                    var asktind = "";
                    // Pastikan item.tindakan adalah array sebelum mengaksesnya
                    if (item.tindakan && Array.isArray(item.tindakan)) {
                        item.tindakan.forEach(function (tindakan) {
                            asktind += `${tindakan.nama_tindakan} : ${tindakan.nama_obat},\n`;
                        });
                    }
                    item.asktind = asktind;
                    item.index = dataArray.indexOf(item) + 1;

                    var alamat = `${item.kelurahan_nama}, ${item.pasien_rt}/${item.pasien_rw}, ${item.kecamatan_nama}, ${item.kabupaten_nama}`;
                    item.aksi = `<a href="#" class="aksi-button btn-sm btn-primary px-2 icon-link icon-link-hover"
                                    data-norm="${item.pasien_no_rm}"
                                    data-nama="${item.pasien_nama}"
                                    data-dokter="${item.dokter_nama}"
                                    data-asktind="${asktind}"
                                    data-kddokter="${item.nip_dokter}"
                                    data-alamat="${alamat}"
                                    data-layanan="${item.penjamin_nama}"
                                    data-notrans="${item.no_trans}"
                                    data-tgltrans="${item.tanggal}"><i class="fas fa-pen-to-square"></i></a>`;
                });
            } else {
                console.error(
                    "Invalid response or response.response.data is not available:",
                    response
                );
                // Handle error or display appropriate message
            }
            table.clear().rows.add(dataArray).draw();
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
            var tgltrans = response[0].tgltrans;
            // var dokter = `${response[0].petugas.pegawai.gelar_d} ${response[0].petugas.pegawai.nama} ${response[0].petugas.pegawai.gelar_b}`;
            var alamat = `${response[0].biodata.kelurahan}, ${response[0].biodata.rtrw}, ${response[0].biodata.kecamatan}, ${response[0].biodata.kabupaten}`;
            var asktind = "";

            if (response[0].poli) {
                if (response[0].poli.nebulizer)
                    asktind += "Nebu: " + response[0].poli.nebulizer + "\n";
                if (response[0].poli.oksigenasi)
                    asktind += "O2: " + response[0].poli.oksigenasi + "\n";
                if (response[0].poli.injeksi)
                    asktind += "Injeksi: " + response[0].poli.injeksi + "\n";
                if (response[0].poli.infus)
                    asktind += "Infus: " + response[0].poli.infus + "\n";
                if (response[0].poli.mantoux) asktind += "Mantoux" + ", ";
                if (response[0].poli.ekg) asktind += "EKG" + ", ";
                if (response[0].poli.spirometri) asktind += "Spirometri";
            }

            // The rest of your code goes here, if any.

            // Dapatkan data lainnya dari respons JSON sesuai kebutuhan

            // Mengisikan data ke dalam elemen-elemen HTML
            $("#asktind").val(asktind);
            $("#norm").val(noRM);
            $("#nama").val(nama);
            $("#alamat").val(alamat);
            $("#notrans").val(notrans);
            $("#tgltrans").val(tgltrans);
            $("#layanan").val(layanan);
            $("#dokter").val(dokter);
            $("#dokter").trigger("change");

            dataTindakan();
            // Mengisi elemen-elemen lainnya sesuai kebutuhan
        },
        error: function (xhr) {
            // Handle error
        },
    });
}
