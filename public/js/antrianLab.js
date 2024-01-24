function fetchDataAntrian(tanggal, callback) {
    $.ajax({
        url: "/api/antrianLaboratorium",
        // url: "/api/antrialAll",
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
        var alamat2 = `${item.kelurahan}, ${item.kecamatan}`;
        item.aksi = `<a href="#" class="aksi-button px-2 btn btn-sm btn-danger"
                data-norm="${item.norm}"
                data-nama="${item.namapasien}"
                data-dokter="${item.dokterpoli}"
                data-kddokter="${item.nip}"
                data-alamat="${alamat}"
                data-kelompok="${item.kelompok}"
                data-notrans="${item.notrans}"
                data-nik="${item.noktp}"
                data-tgltrans="${item.tgltrans}"><i class="fas fa-pen-to-square"></i></a>
            <a href="#" class="panggil px-2 btn btn-sm btn-success"
                data-panggil="${item.pang} ${item.namapasien} dari ${alamat2}, silahkan menuju ke loket laboratorium">
                <i class="fa-solid fa-volume-high"></i></a>`;
    });

    $("#dataAntrian").DataTable({
        data: response,
        columns: [
            { data: "aksi", className: "text-center p-2 col-1" },
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
            { data: "kelompok", className: "p-2" },
            { data: "noktp", className: "p-2" },
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
                var alamat2 = `${item.kelurahan} , ${item.kecamatan}`;
                item.aksi = `<a href="#" class="aksi-button px-2 btn btn-sm btn-danger"
                                    data-norm="${item.norm}"
                                    data-nama="${item.namapasien}"
                                    data-dokter="${item.dokterpoli}"
                                    data-kddokter="${item.nip}"
                                    data-alamat="${alamat}"
                                    data-kelompok="${item.kelompok}"
                                    data-notrans="${item.notrans}"
                                    data-nik="${item.noktp}"
                                    data-tgltrans="${item.tgltrans}"
                                    ><i class="fas fa-pen-to-square"></i></a>
                                <a href="#" class="panggil px-2 btn btn-sm btn-success"
                                    data-panggil="${item.pang} ${item.namapasien} dari ${alamat2}, silahkan menuju ke loket farmasi">
                                    <i class="fa-solid fa-volume-high"></i></a>`;
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
            if (response && response.length > 0) {
                Swal.fire({
                    icon: "success",
                    title: "Data pasien ditemukan, lanjutkan transaksi...!!!",
                });
                // Mendapatkan data dari respons JSON
                var noRM = response[0].norm; // Menggunakan indeks 0 karena respons adalah array
                var nama = response[0].biodata.nama;
                var nik = response[0].biodata.noktp;
                var notrans = response[0].notrans;
                var tgltrans = response[0].tgltrans;
                var layanan = response[0].kelompok.kelompok;
                var dokter = response[0].petugas.p_dokter_poli;
                var alamat = `${response[0].biodata.kelurahan}, ${response[0].biodata.rtrw}, ${response[0].biodata.kecamatan}, ${response[0].biodata.kabupaten}`;
                // Dapatkan data lainnya dari respons JSON sesuai kebutuhan

                // Mengisikan data ke dalam elemen-elemen HTML
                $("#norm").val(noRM);
                $("#nama").val(nama);
                $("#nik").val(nik);
                $("#alamat").val(alamat);
                $("#notrans").val(notrans);
                $("#tgltrans").val(tgltrans);
                $("#layanan").val(layanan);
                $("#dokter").val(dokter);
                $("#dokter").trigger("change");
                // Mengisi elemen-elemen lainnya sesuai kebutuhan
                dataLab();
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
async function searchRMObat() {
    Swal.fire({
        icon: "success",
        title: "Sedang mencarikan data pasien...!!!",
    });
    var norm = "000001";
    try {
        const response = await $.ajax({
            url: "/api/cariRMObat",
            type: "post",
            data: { norm: norm },
        });

        if (response.length > 0) {
            Swal.fire({
                icon: "success",
                title: "Data pasien ditemukan, lanjutkan transaksi...!!!",
            });

            // Extracting data from the JSON response
            var noRM = response[0].norm;
            var nama = response[0].nama;
            var nik = response[0].noktp;
            var notrans = response[0].notrans;
            var alamat = `${response[0].kelurahan}, ${response[0].rtrw}, ${response[0].kecamatan}, ${response[0].kabupaten}`;

            // Updating HTML elements with the extracted data
            $("#norm").val(noRM);
            $("#nama").val(nama);
            $("#nik").val(nik);
            $("#alamat").val(alamat);
            $("#notrans").val(notrans);
            $("#layanan").val("UMUM");
            $("#dokter").val("198907252019022004").trigger("change");
            $("#apoteker").val("197609262011012003").trigger("change");

            dataLab();
        } else {
            Swal.fire({
                icon: "error",
                title: "Data pasien tidak ditemukan...!!!",
            });
        }
    } catch (error) {
        console.error("Error:", error);
        // Handling error if the API request fails
        Swal.fire({
            icon: "error",
            title: "Terjadi kesalahan saat mengambil data pasien...!!!",
        });
    }
}
