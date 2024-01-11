function dataTindakan() {
    var notrans = $("#notrans").val();

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
function fetchDataAntrian(tanggal, callback) {
    $.ajax({
        url: "/api/antrianIGD",
        type: "post",
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
        var asktind = "";
        if (item.nebulizer) asktind += "Nebu: " + item.nebulizer + ", " + "\n";
        if (item.oksigenasi) asktind += "O2: " + item.oksigenasi + ", " + "\n";
        if (item.injeksi) asktind += "Injeksi: " + item.injeksi + ", " + "\n";
        if (item.infus) asktind += "Infus: " + item.infus + ", " + "\n";
        if (item.mantoux == "1") asktind += "Mantoux" + ", ";
        if (item.ekg == "1") asktind += "EKG" + ", ";
        if (item.spirometri == "1") asktind += "Spirometri" + ", ";

        item.asktind = asktind;
        var trim = `${item.tgltrans}`;
        var tgl = new Date(trim).toISOString().split("T")[0];
        var alamat = `${item.kelurahan}, ${item.rtrw}, ${item.kecamatan}, ${item.kabupaten}`;
        item.aksi = `<a href="#" class="aksi-button btn-sm btn-primary px-2 icon-link icon-link-hover"
                data-norm="${item.norm}"
                data-nama="${item.namapasien}"
                data-dokter="${item.dokterpoli}"
                data-asktind="${asktind}"
                data-kddokter="${item.nip}"
                data-alamat="${alamat}"
                data-layanan="${item.kelompok}"
                data-notrans="${item.notrans}"
                data-tgl="${tgl}"
                data-tgltrans="${item.tgltrans}"><i class="fas fa-pen-to-square"></i></a>`;
    });

    $("#dataAntrian").DataTable({
        data: response,
        columns: [
            { data: "aksi", className: "text-center p-2" },
            {
                data: "status",
                className: "text-center p-2",
                name: "status",
                render: function (data, type, row) {
                    var backgroundColor =
                        data === "belum" ? "danger" : "success";
                    return `<div class="badge badge-${backgroundColor}">${data}</div>`;
                },
            },

            { data: "nourut", className: "text-center p-2" },
            { data: "norm", className: "text-center p-2" },
            { data: "kelompok", className: "text-center p-2" },
            { data: "namapasien", className: " p-2" },
            { data: "asktind", className: " p-2" },
            { data: "dokterpoli", className: " p-2" },
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
                var asktind = "";
                if (item.nebulizer)
                    asktind += "Nebu: " + item.nebulizer + ", " + "\n";
                if (item.oksigenasi)
                    asktind += "O2: " + item.oksigenasi + ", " + "\n";
                if (item.injeksi)
                    asktind += "Injeksi: " + item.injeksi + ", " + "\n";
                if (item.infus) asktind += "Infus: " + item.infus + ", " + "\n";
                if (item.mantoux == "1") asktind += "Mantoux" + ", ";
                if (item.ekg == "1") asktind += "EKG" + ", ";
                if (item.spirometri == "1") asktind += "Spirometri" + ", ";

                item.asktind = asktind;
                var trim = `${item.tgltrans}`;
                var tgl = new Date(trim).toISOString().split("T")[0];
                var alamat = `${item.kelurahan}, ${item.rtrw}, ${item.kecamatan}, ${item.kabupaten}`;
                item.aksi = `<a href="#" class="aksi-button btn-sm btn-primary px-2 icon-link icon-link-hover"
                data-norm="${item.norm}"
                data-nama="${item.namapasien}"
                data-dokter="${item.dokterpoli}"
                data-asktind="${asktind}"
                data-kddokter="${item.nip}"
                data-alamat="${alamat}"
                data-layanan="${item.kelompok}"
                data-notrans="${item.notrans}"
                data-tgl="${tgl}"
                data-tgltrans="${item.tgltrans}"><i class="fas fa-pen-to-square"></i></a>`;
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
