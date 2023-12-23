function fetchDataAntrian(tanggal, callback) {
    $.ajax({
        url: "/api/antrianFarmasi",
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
        var alamat = `${item.kelurahan}, ${item.rtrwpasien}, ${item.kecamatan}, ${item.kabupaten}`;
        var alamat2 = `${item.kelurahan},  ${item.kecamatan}, ${item.kabupaten}`;
        item.aksi = `<a href="#" class="aksi-button px-2"
                data-norm="${item.norm}"
                data-nama="${item.namapasien}"
                data-dokter="${item.dokterpoli}"
                data-kddokter="${item.kddokter}"
                data-alamat="${alamat}"
                data-layanan="${item.layanan}"
                data-notrans="${item.notrans}"
                data-tgltran="${item.tgltran}"><i class="fas fa-pen-to-square"></i></a>
                <a href="#" class="panggil px-2">
                <i class="fa-solid fa-volume-high" onclick="panggilAntrian('${item.namapasien} dari ${alamat2}')"></i></a>`;
    });

    $("#dataAntrian").DataTable({
        data: response,
        columns: [
            { data: "aksi", className: "text-center p-2" },
            {
                data: "status",
                name: "status",
                render: function (data, type, row) {
                    var backgroundColor =
                        data === "belum" ? "danger" : "success";
                    return `<div class="badge badge-${backgroundColor}">${data}</div>`;
                },
                className: "p-2",
            },
            { data: "nourut", className: "p-2" },
            { data: "norm", className: "p-2" },
            { data: "layanan", className: "p-2" },
            { data: "namapasien", className: "p-2" },
            { data: "dokterpoli", className: "p-2" },
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
                var alamat = `${item.kelurahan}, ${item.rtrwpasien}, ${item.kecamatan}, ${item.kabupaten}`;
                var alamat2 = `${item.kelurahan},  ${item.kecamatan}, ${item.kabupaten}`;
                item.aksi = `<a href="#" class="aksi-button px-2"
                                    data-norm="${item.norm}"
                                    data-nama="${item.namapasien}"
                                    data-dokter="${item.dokterpoli}"
                                    data-kddokter="${item.kddokter}"
                                    data-alamat="${alamat}"
                                    data-layanan="${item.layanan}"
                                    data-notrans="${item.notrans}"
                                    data-tgltran="${item.tgltran}"
                                    ><i class="fas fa-pen-to-square"></i></a>
                                <a href="#" class="panggil px-2 button button-warning">
                                <i class="fa-solid fa-volume-high" onclick="panggilAntrian('${item.namapasien} dari ${alamat2}')"></i></a>`;
            });
            table.clear().rows.add(response).draw();
        } else {
            initializeDataAntrian(response);
        }
    });
}
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
function searchByRM(norm) {
    $.ajax({
        url: "/api/cariRM",
        type: "post",
        data: {
            norm: norm,
        },
        success: function (response) {
            if (response && response.length > 0) {
                Swal.fire({
                    icon: "success",
                    title: "Data pasien ditemukan, lanjutkan transaksi...!!!",
                });
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
                dataFarmasi();
                dataBMHP();
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Data pasien tidak ditemukan pada kunjungan hari ini...!!!",
                });
            }
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
        url: "/api/transaksiFarmasi",
        type: "POST",
        data: { notrans: notrans },
        success: function (response) {
            response.forEach(function (item, index) {
                item.actions = `<a href="" class="edit"
                                    data-id="${item.idAptk}"
                                    data-norm="${item.norm}"
                                    data-idObat="${item.idObat}"
                                    data-product_id="${item.product_id}"
                                    data-obat="${item.nmObat}"
                                    data-qty="${item.qty}"><i class="fas fa-pen-to-square pr-3"></i></a>
                                <a href="" class="delete"
                                    data-id="${item.idAptk}"
                                    data-norm="${item.norm}"
                                    data-idObat="${item.idObat}"
                                    data-obat="${item.nmObat}"
                                    data-qty="${item.qty}"><i class="fas fa-trash"></i></a>`;
                item.no = index + 1;
                item.total = `${item.total.toLocaleString()}`;
            });

            $("#dataFarmasi").DataTable({
                data: response,
                columns: [
                    { data: "actions", className: "px-0 col-1 text-center" },
                    { data: "no", className: "col-1 text-center" },
                    { data: "norm", className: "col-1 " },
                    { data: "nmObat" },
                    { data: "qty", className: "col-1" },
                    { data: "total", className: "totalHarga" },
                ],
                order: [2, "asc"],
            });
            tagihan();
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        },
    });
}

function dataBMHP() {
    var notrans = $("#notrans").val();
    if ($.fn.DataTable.isDataTable("#dataIGD")) {
        var tabletindakan = $("#dataIGD").DataTable();
        tabletindakan.clear().destroy();
    }

    $.ajax({
        url: "/api/cariTotalBmhp",
        type: "post",
        data: { notrans: notrans },
        success: function (response) {
            response.forEach(function (item, index) {
                item.actions = `<a href="" class="delete"
                                    data-id="${item.id}"
                                    data-idTind="${item.idTind}"
                                    data-kdtind="${item.kdTind}"
                                    data-tindakan="${item.tindakan}"
                                    data-kdBmhp="${item.kdBmhp}"
                                    data-jumlah="${item.jumlah}">
                                    <i class="fas fa-trash"></i></a>`;
                item.no = index + 1; // Nomor urut dimulai dari 1, bukan 0
            });

            $("#dataIGD").DataTable({
                data: response,
                columns: [
                    { data: "actions", className: "text-center" },
                    { data: "no" },
                    { data: "tindakan.norm" },
                    { data: "bmhp.nmObat" },
                    { data: "jml" },
                    { data: "biaya", className: "totalHarga" },
                    // { data: "tindakan.petugas_pegawai.nama" },
                    // { data: "tindakan.dokter_pegawai.nama" },
                ],
                order: [2, "asc"],
                paging: true,
                pageLength: 5,
            });
            tagihan();
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        },
    });
}
