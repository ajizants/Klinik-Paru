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
            // alert("Terjadi kesalahan saat mengambil data. Silakan coba lagi.");
            callback([]);
        },
    });
}

function antrianNull() {
    $("#loadingSpinner").hide();
    $("#tableAntrian").html("");
}

function processResponseFar(response) {
    console.log("🚀 ~ processResponseFar ~ response:", response);
    response.forEach(function (item) {
        item.nip_dokter = getNipByDoctorName(item.dokter_nama);
        if (item.keterangan === "SEDANG DIPANGGIL") {
            console.log("Ada item yang sedang dipanggil.");
            sedangMemanggil = true;
        } else {
            console.log("Tidak ada item yang sedang dipanggil.");
            sedangMemanggil = false;
        }
        let warnaBtn =
            item.status_pulang === "Sudah Pulang" ? "danger" : "secondary";
        let warnaBtnObat =
            item.status_obat === "Sudah Selesai" ? "secondary" : "lime";
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
                    onclick="cariKominfo('${item.pasien_no_rm}', '${item.tanggal}', 'igd');"
                     data-toggle="tooltip" data-placement="top" title="Tambah Tindakan">
                    <i class="fas fa-pen-to-square"></i>
                </a>
                `;
        // const ctkRspBtn = `
        //     <a class="btn btn-secondary"
        //         data-notrans="${item.no_reg}"
        //         data-norm="${item.pasien_no_rm}"
        //         data-log_id="${item.log_id}"
        //         data-tgl="${item.tanggal}" onclick="cetakResep(norm, tgl)">
        //         <i class="fa-regular fa-folder-open"></i>
        //     </a>
        //     `;
        const ctkRspBtn = `
            <a class="btn btn-${warnaBtn}"
                onclick="cetakResep('${item.pasien_no_rm}', '${item.tanggal}',this)">
                <i class="fa-regular fa-folder-open"></i>
            </a>`;
        const plgBtn = `
            <a type="button" onclick="pulangkan('${item.pasien_no_rm}', '${item.log_id}', '${item.no_reg}')"
                class="btn btn-warning"
                data-toggle="tooltip" data-placement="top" title="Pulangkan">
                <i class="fa-solid fa-right-from-bracket"></i>
            </a>
            `;
        const bpjsBtn = `
            <a type="button" ${commonAttributes} onclick="isiObat(this)"
                class="btn bg-${warnaBtnObat}"
                data-toggle="tooltip" data-placement="top" title="Isi Obat">
                <i class="fa-solid fa-tablets"></i>
            </a>
            `;

        const panggilBtn = `<button class="panggil btn btn-success"
                onclick="panggil('${item.log_id}','${item.pasien_no_rm}', '${item.tanggal}')"
                data-toggle="tooltip" data-placement="top" title="Panggil">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-volume-up-fill" viewBox="0 0 16 16">
                    <path d="M11.536 14.01A8.47 8.47 0 0 0 14.026 8a8.47 8.47 0 0 0-2.49-6.01l-.708.707A7.48 7.48 0 0 1 13.025 8c0 2.071-.84 3.946-2.197 5.303z"/>
                    <path d="M10.121 12.596A6.48 6.48 0 0 0 12.025 8a6.48 6.48 0 0 0-1.904-4.596l-.707.707A5.48 5.48 0 0 1 11.025 8a5.48 5.48 0 0 1-1.61 3.89z"/>
                    <path d="M8.707 11.182A4.5 4.5 0 0 0 10.025 8a4.5 4.5 0 0 0-1.318-3.182L8 5.525A3.5 3.5 0 0 1 9.025 8 3.5 3.5 0 0 1 8 10.475zM6.717 3.55A.5.5 0 0 1 7 4v8a.5.5 0 0 1-.812.39L3.825 10.5H1.5A.5.5 0 0 1 1 10V6a.5.5 0 0 1 .5-.5h2.325l2.363-1.89a.5.5 0 0 1 .529-.06"/>
                </svg>
            </button>
            `;

        if (item.keterangan === "PULANG") {
            item.aksi = `
             ${inputBtn}
             ${ctkRspBtn}
             ${bpjsBtn}
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
            ${bpjsBtn}
            `;
        }
    });
}

function isiObat(btn) {
    const norm = btn.getAttribute("data-norm");
    const layanan = btn.getAttribute("data-layanan");
    const nama = btn.getAttribute("data-nama");
    const tgltrans = btn.getAttribute("data-tgltrans");
    const notrans = btn.getAttribute("data-notrans");

    // Isi input form terlebih dahulu
    $("#norm_bpjs").val(norm);
    $("#layanan_bpjs").val(layanan);
    $("#nama_bpjs").val(nama);
    $("#tgltrans_bpjs").val(tgltrans);
    $("#notrans_bpjs").val(notrans);

    // Tampilkan modal lebih awal agar user tidak menunggu diam
    $("#modalInputObat").modal("show");

    const requestData = { notrans, tgltrans };

    $.ajax({
        url: "/api/kasir/kunjungan/item",
        type: "POST",
        data: requestData,
        success: function (response) {
            const data = response || [];

            let totalObat = "";
            let totalBMHP = "";
            let totalObatKronis = "";

            data.forEach((item) => {
                const id = parseInt(item.idLayanan);
                if (id === 2) totalObat = item.totalHarga;
                else if (id === 229) totalBMHP = item.totalHarga;
                else if (id === 228) totalObatKronis = item.totalHarga;
            });

            $("#obat_bpjs").val(totalObat);
            $("#bmhp_bpjs").val(totalBMHP);
            $("#obatKronis_bpjs").val(totalObatKronis);
        },
        error: function (xhr, status, error) {
            console.error("Gagal mengambil data kunjungan:", error);
            handleErrorKasir();
        },
    });
}

function handleErrorKasir() {
    $("#obat_bpjs").val("");
    $("#bmhp_bpjs").val("");
    $("#obatKronis_bpjs").val("");
}

function simpanDataFarmasi() {
    const notrans = $("#notrans_bpjs").val();
    const norm = $("#norm_bpjs").val();
    const jaminan = $("#layanan_bpjs").val();
    const nama = $("#nama_bpjs").val();
    const tgltrans = $("#tgltrans_bpjs").val();
    const obat = $("#obat_bpjs").val();
    const bmhp = $("#bmhp_bpjs").val();
    const obatKronis = $("#obatKronis_bpjs").val();

    const dataTerpilih = [];

    if (obat !== "") {
        dataTerpilih.push({
            idLayanan: 2,
            norm: norm,
            notrans: notrans,
            qty: 1,
            harga: obat,
            jaminan: jaminan,
        });
    }

    if (bmhp !== "") {
        dataTerpilih.push({
            idLayanan: 229,
            norm: norm,
            notrans: notrans,
            qty: 1,
            harga: bmhp,
            jaminan: jaminan,
        });
    }

    if (obatKronis !== "") {
        dataTerpilih.push({
            idLayanan: 228,
            norm: norm,
            notrans: notrans,
            qty: 1,
            harga: obatKronis,
            jaminan: jaminan,
        });
    }

    // Kirim data ke server
    fetch("/api/farmasi/item/add", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            notrans: notrans,
            norm: norm,
            nama: nama,
            umur: "",
            jk: "",
            alamat: "",
            jaminan: jaminan,
            tgltrans: tgltrans,
            dataTerpilih: dataTerpilih,
        }),
    })
        .then((response) => {
            if (!response.ok) {
                console.log("Response status:", response.status);
                console.log("Response status text:", response.statusText);
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then((data) => {
            console.log(data);
            Swal.fire({
                icon: "success",
                title: data.message,
            });
        })
        .catch((error) => {
            console.error(
                "There has been a problem with your fetch operation:",
                error
            );
            Swal.fire({
                icon: "error",
                title: "Terjadi masalah: " + error.message,
            });
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
            {
                data: "status_obat",
                render: function (data) {
                    const backgroundColor =
                        data === "Tidak Ada Transaksi" ? "danger" : "success";
                    return `<div class="badge badge-${backgroundColor}">${data}</div>`;
                },
                className: "p-2",
                title: "Status Obat",
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
        if (Swal.isVisible()) {
            Swal.close();
        }

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

        btnPanggil = document.querySelectorAll(".panggil");
        if (sedangMemanggil === true) {
            btnPanggil.forEach((btn, index) => {
                btn.disabled = true;
            });
        }
    });
}

function getNipByDoctorName(doctorName) {
    return doctorNipMap[doctorName] || null; // Mengembalikan null jika nama dokter tidak ditemukan
}
const doctorNipMap = {
    "dr. Cempaka Nova Intani, Sp.P, FISR., MM.": "198311142011012002",
    "dr. Agil Dananjaya, Sp.P": "9",
    "dr. Filly Ulfa Kusumawardani": "198907252019022004",
    "dr. Sigit Dwiyanto": "198903142022031005",
};

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
        if ($.fn.DataTable.isDataTable("#transaksiBMHP")) {
            var tabletindakan = $("#transaksiBMHP").DataTable();
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

                $("#transaksiBMHP").DataTable({
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
            },
            error: function (xhr, status, error) {
                console.error("Error:", error);
            },
        });
    }
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
                item.actions = `<a href="" class="delete btn-sm btn-danger icon-link icon-link-hover"
                                    data-id="${item.id}"
                                    data-idTind="${item.idTind}"
                                    data-kdtind="${item.kdTind}"
                                    data-bmhp="${item.bmhp.nmObat}"
                                    data-kdBmhp="${item.kdBmhp}"
                                    data-jumlah="${item.jumlah}">
                                    <i class="fas fa-trash"></i></a>`;
                item.no = index + 1;
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

// function simpanDataFarmasi() {
//     var dataTerpilih = [];
//     var norm = $("#norm_bpjs").val();
//     var nama = $("#nama_bpjs").val();
//     var alamat = "";
//     var jaminan = "";
//     var notrans = $("#notrans_bpjs").val();
//     var umur = "";
//     var jk = "";
//     var tgltrans = $("#tgltrans_bpjs").val();
//     var totalObat = $("#obat_bpjs").val();
//     var totalBMHP = $("#bmhp_bpjs").val();
//     var totalObatKronis = $("#obatKronis_bpjs").val();
//     const idObat = 2;
//     const idBMHP = 229;
//     const idObatKronis = 228;

//     // Validasi data input
//     if (!norm || !notrans || !tgltrans) {
//         var dataKurang = [];
//         if (!norm) dataKurang.push("No RM ");
//         if (!notrans) dataKurang.push("Nomor Transaksi ");
//         if (!tgltrans) dataKurang.push("Tanggal Transaksi ");

//         Swal.fire({
//             icon: "error",
//             title: "Data Tidak Lengkap!",
//             text: dataKurang.join(", ") + " Belum Diisi.",
//         });

//         if (!norm) $("#norm_bpjs").focus();
//         else if (!notrans) $("#notrans_bpjs").focus();
//         else if (!tgltrans) $("#tgltrans_bpjs").focus();
//         return; // Hentikan fungsi
//     }

//     dataTerpilih = [
//         {
//             idLayanan: idObat,
//             norm: norm,
//             notrans: notrans,
//             qty: 1,
//             harga: totalObat,
//             jaminan: jaminan,
//         },
//         {
//             idLayanan: idBMHP,
//             norm: norm,
//             notrans: notrans,
//             qty: 1,
//             harga: totalBMHP,
//             jaminan: jaminan,
//         },
//         {
//             idLayanan: idObatKronis,
//             norm: norm,
//             notrans: notrans,
//             qty: 1,
//             harga: totalObatKronis,
//             jaminan: jaminan,
//         },
//     ];

//     console.log(dataTerpilih);
//     return;

//     // return {
//     //     idLayanan: id,
//     //     norm: norm,
//     //     notrans: notrans,
//     //     qty: qty,
//     //     harga: harga,
//     //     jaminan: jaminan,
//     // };

//     // Kirim data ke server
//     fetch("/api/kasir/item/add", {
//         method: "POST",
//         headers: {
//             "Content-Type": "application/json",
//         },
//         body: JSON.stringify({
//             notrans: notrans,
//             norm: norm,
//             nama: nama,
//             umur: umur,
//             jk: jk,
//             alamat: alamat,
//             jaminan: jaminan,
//             tgltrans: tgltrans,
//             dataTerpilih: dataTerpilih,
//         }),
//     })
//         .then((response) => {
//             if (!response.ok) {
//                 console.log("Response status:", response.status);
//                 console.log("Response status text:", response.message);
//                 throw new Error("Network response was not ok");
//             }
//             return response.json();
//         })
//         .then((data) => {
//             console.log(data);
//             Swal.fire({
//                 icon: "success",
//                 title: data.message,
//             });
//             var notrans = $("#notrans").val();
//             riwayat(notrans);
//             $('table thead input[type="checkbox"]').prop("checked", false);
//             $('table tbody input[type="checkbox"]').prop("checked", false);
//         })
//         .catch((error) => {
//             console.error(
//                 "There has been a problem with your fetch operation:",
//                 error
//             );
//             Swal.fire({
//                 icon: "error",
//                 title: "Terjadi masalah: " + error.message,
//             });
//         });
// }
