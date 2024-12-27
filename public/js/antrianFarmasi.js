function fetchDataAntrianFar(tanggal, callback) {
    $.ajax({
        url: "/api/antrianFarmasi",
        type: "POST",
        data: { tgl: tanggal },
        success: function (response) {
            if (typeof callback === "function") {
                callback(response);
            }
        },
        error: function (xhr) {
            console.error("Error fetching data:", xhr.status, xhr.statusText);
            $("#loadingSpinner").hide();
            alert("Terjadi kesalahan saat mengambil data. Silakan coba lagi.");
        },
    });
}

function processResponseFar(response) {
    response.forEach(function (item) {
        const commonAttributes = `
        data-norm="${item.pasien_no_rm}"
        data-nama="${item.pasien_nama}"
        data-kddokter="${item.nip_dokter}"
        data-alamat="${item.pasien_alamat}"
        data-tgltrans="${item.tanggal}"
        data-asktind="${item.asktind || ""}"
        data-umur="${item.pasien_umur}"
        data-layanan="${item.penjamin_nama}"
        data-notrans="${item.no_reg}"
        data-tujuan="${item.tujuan || ""}"
    `;

        const inputBtn = `
                <a type="button" ${commonAttributes} 
                    class="btn btn-primary "
                    onclick="setTransaksi(this,'igd');"
                     data-toggle="tooltip" data-placement="top" title="Tambah Tindakan">
                    <i class="fas fa-pen-to-square"></i>
                </a>
                `;
        const ctkRspBtn = `
            <a class="panggil btn btn-success"
                data-notrans="${item.no_reg}"
                data-norm="${item.pasien_no_rm}"
                data-log_id="${item.log_id}"
                data-tgl="${item.tanggal}" onclick="cariResepLocal(this)">
                <i class="fa-regular fa-folder-open"></i>
            </a>
            `;
        const plgBtn = `
            <a type="button" onclick="pulangkan('${item.pasien_no_rm}', '${item.log_id}', '${item.no_reg}')"
                class="btn btn-warning" 
                data-toggle="tooltip" data-placement="top" title="Pulangkan">
                <i class="fa-solid fa-right-from-bracket"></i>
            </a>
            `;

        const panggilBtn = `<a class="panggil btn btn-success" 
                onclick="panggil('${item.log_id}')"
                data-toggle="tooltip" data-placement="top" title="Panggil">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-volume-up-fill" viewBox="0 0 16 16">
                    <path d="M11.536 14.01A8.47 8.47 0 0 0 14.026 8a8.47 8.47 0 0 0-2.49-6.01l-.708.707A7.48 7.48 0 0 1 13.025 8c0 2.071-.84 3.946-2.197 5.303z"/>
                    <path d="M10.121 12.596A6.48 6.48 0 0 0 12.025 8a6.48 6.48 0 0 0-1.904-4.596l-.707.707A5.48 5.48 0 0 1 11.025 8a5.48 5.48 0 0 1-1.61 3.89z"/>
                    <path d="M8.707 11.182A4.5 4.5 0 0 0 10.025 8a4.5 4.5 0 0 0-1.318-3.182L8 5.525A3.5 3.5 0 0 1 9.025 8 3.5 3.5 0 0 1 8 10.475zM6.717 3.55A.5.5 0 0 1 7 4v8a.5.5 0 0 1-.812.39L3.825 10.5H1.5A.5.5 0 0 1 1 10V6a.5.5 0 0 1 .5-.5h2.325l2.363-1.89a.5.5 0 0 1 .529-.06"/>
                </svg>
            </a>
            `;

        if (item.keterangan === "PULANG") {
            item.aksi = `
             ${inputBtn}
             ${ctkRspBtn}
            `;
        } else if (item.keterangan === "SEDANG DIPANGGIL") {
            item.aksi = `
             ${inputBtn}
             ${plgBtn}
             ${ctkRspBtn}
            
            `;
        } else {
            item.aksi = `
            ${inputBtn}
            ${panggilBtn}
            ${ctkRspBtn}
            `;
        }
    });
}

function initializeDataAntrianFar(response) {
    // Proses data sebelum inisialisasi
    processResponseFar(response);

    const dataSelesai = response.filter((item) => item.keterangan === "PULANG");
    const daftarTunggu = response.filter(
        (item) => item.keterangan !== "PULANG" && item.keterangan !== "SKIP"
    );
    const dataSkip = response.filter((item) => item.keterangan === "SKIP");
    // Inisialisasi DataTable
    dwrawTableFar(dataSelesai, "#dataSelesai");
    dwrawTableFar(daftarTunggu, "#dataAntrian");
    dwrawTableFar(dataSkip, "#dataSkip");
}

function dwrawTableFar(data, idTable) {
    if ($.fn.DataTable.isDataTable(idTable)) {
        $(idTable).DataTable().destroy();
    }
    $(idTable).DataTable({
        data: data,
        destroy: true,
        columns: [
            { data: "aksi", className: "text-center p-2 col-2", title: "Aksi" },
            {
                data: "keterangan",
                className: "p-2",
                title: "Status Pulang",
                render: function (data) {
                    const statusClasses = {
                        "MENUNGGU DIPANGGIL": "danger",
                        "SEDANG DIPANGGIL": "success",
                        PULANG: "success",
                        SKIP: "warning",
                        default: "secondary",
                    };
                    return `<div class="badge badge-${
                        statusClasses[data] || statusClasses.default
                    }">${data}</div>`;
                },
            },
            // { data: "keterangan", className: "p-2", title: "Status Kominfo" },
            { data: "antrean_nomor", className: "p-2", title: "Urut" },
            {
                data: "created_at_log",
                className: "p-2 col-2",
                title: "Waktu Masuk",
            },
            { data: "pasien_no_rm", className: "p-2", title: "NoRM" },
            { data: "penjamin_nama", className: "p-2", title: "Penjamin" },
            { data: "pasien_nama", className: "p-2 col-2", title: "Pasien" },
            { data: "dokter_nama", className: "p-2 col-3", title: "Dokter" },
            {
                data: "status_kasir",
                render: function (data) {
                    const backgroundColor =
                        data === "Tidak Ada Transaksi" ? "danger" : "success";
                    return `<div class="badge badge-${backgroundColor}">${data}</div>`;
                },
                className: "p-2",
                title: "Status Kasir",
            },
        ],
        order: [
            [1, "dsc"],
            [3, "asc"],
        ],
    });
}

function updateTableFar(idTable, data) {
    const table = $(idTable).DataTable();
    processResponseFar(data);
    table.clear().rows.add(data).draw();
}

function antrianFar() {
    $("#loadingSpinner").show();
    const tanggal = $("#tanggal").val();

    fetchDataAntrianFar(tanggal, function (response) {
        $("#loadingSpinner").hide();

        // Jika DataTable sudah ada, hanya update datanya
        if ($.fn.DataTable.isDataTable("#dataAntrian")) {
            // const table = $("#dataAntrian").DataTable();
            // processResponseFar(response);
            // table.clear().rows.add(response).draw();
            const dataSelesai = response.filter(
                (item) => item.keterangan === "PULANG"
            );
            const daftarTunggu = response.filter(
                (item) =>
                    item.keterangan === "SEDANG DIPANGGIL" ||
                    item.keterangan === "MENUNGGU DIPANGGIL"
            );
            const dataSkip = response.filter(
                (item) => item.keterangan === "SKIP"
            );
            updateTableFar("#dataAntrian", daftarTunggu);
            updateTableFar("#dataSelesai", dataSelesai);
            updateTableFar("#dataSkip", dataSkip);
        } else {
            initializeDataAntrianFar(response);
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
            var notrans = response[0].notrans;
            var alamat = `${response[0].kelurahan}, ${response[0].rtrw}, ${response[0].kecamatan}, ${response[0].kabupaten}`;

            // Updating HTML elements with the extracted data
            $("#norm").val(noRM);
            $("#nama").val(nama);
            $("#alamat").val(alamat);
            $("#notrans").val(notrans);
            $("#layanan").val("UMUM");
            $("#dokter").val("198907252019022004").trigger("change");
            $("#apoteker").val("197609262011012003").trigger("change");

            // Additional function calls as needed
            dataFarmasi();
            dataBMHP();
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
function dataFarmasi() {
    var notrans = $("#notrans").val();
    //joka no transaksi tidak kosong
    if (notrans !== "") {
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
                        {
                            data: "actions",
                            className: "px-0 col-1 text-center",
                        },
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
}

function dataBMHP() {
    var notrans = $("#notrans").val();
    if (notrans !== "") {
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
                    item.no = index + 1;
                    item.biaya = `${item.biaya.toLocaleString()}`;
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
}
