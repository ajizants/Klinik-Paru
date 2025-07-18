function reportPendapatanItem(tglAwal, tglAkhir) {
    if (
        $.fn.DataTable.isDataTable(
            "#tabelPerItemUMUM,#tabelPerItemUMUMBln,#tabelPerItemBPJS"
        )
    ) {
        var tabel = $(
            "#tabelPerItemUMUM,#tabelPerItemUMUMBln,#tabelPerItemBPJS"
        ).DataTable();
        tabel.clear().destroy();
    }
    $.ajax({
        url: "/api/pendapatan/item",
        type: "post",
        data: {
            tglAwal: tglAwal,
            tglAkhir: tglAkhir,
        },
        success: function (response) {
            Swal.close();
            const dataUmum = response.umum;
            const dataUmumBln = response.umumBulanan;
            const dataBpjs = response.bpjs;
            // console.log(
            //     "🚀 ~ reportPendapatanItem ~ dataUmumBln:",
            //     dataUmumBln
            // );
            // console.log("🚀 ~ reportPendapatanItem ~ dataUmum:", dataUmum);
            isiTabelPendapatanItem(dataUmum, "#tabelPerItemUMUM");
            isiTabelPendapatanItemBln(dataUmumBln, "#tabelPerItemUMUMBln");
            isiTabelPendapatanItem(dataBpjs, "#tabelPerItemBPJS");
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        },
    });
}
function isiTabelPendapatanItem(data, id) {
    data.forEach(function (item, index) {
        item.no = index + 1;
    });
    $(id)
        .DataTable({
            data: data,
            columns: [
                { data: "no" },
                { data: "nmLayanan" },
                {
                    data: "tanggal",
                    className: "col-2",
                    render: function (data) {
                        // Format the date using JavaScript
                        const formattedDate = new Date(data).toLocaleString(
                            "id-ID",
                            {
                                year: "numeric",
                                month: "numeric",
                                day: "numeric",
                            }
                        );
                        return formattedDate;
                    },
                },
                // {
                //     data: "jumlah",
                //     render: function (data, type, row) {
                //         var formattedTarif = parseInt(data).toLocaleString(
                //             "id-ID",
                //             {
                //                 style: "currency",
                //                 currency: "IDR",
                //                 minimumFractionDigits: 0,
                //             }
                //         );
                //         return `${formattedTarif}`;
                //     },
                // },
                { data: "jumlah" },
                { data: "totalItem" },
            ],
            order: [0, "asc"],
            lengthChange: true,
            autoWidth: true,
            buttons: [
                {
                    extend: "excelHtml5",
                    text: "Download",
                    title:
                        "Laporan Pendapatan Per Item Tanggal: " +
                        tglAwal +
                        " s.d. " +
                        tglAkhir,
                    filename:
                        "Laporan Pendapatan Per Item Tanggal: " +
                        tglAwal +
                        "  s.d. " +
                        tglAkhir,
                },
            ],
        })
        .buttons()
        .container()
        .appendTo(id + "_wrapper .col-md-6:eq(0)");
}
function isiTabelPendapatanItemBln(data, id) {
    data.forEach(function (item, index) {
        item.no = index + 1;
    });
    $(id)
        .DataTable({
            data: data,
            columns: [
                { data: "no" },
                { data: "nmLayanan" },
                // {
                //     data: "jumlah",
                //     render: function (data, type, row) {
                //         var formattedTarif = parseInt(data).toLocaleString(
                //             "id-ID",
                //             {
                //                 style: "currency",
                //                 currency: "IDR",
                //                 minimumFractionDigits: 0,
                //             }
                //         );
                //         return `${formattedTarif}`;
                //     },
                // },
                { data: "jumlah" },
                { data: "totalItem" },
            ],
            order: [0, "asc"],
            lengthChange: true,
            autoWidth: true,
            buttons: [
                {
                    extend: "excelHtml5",
                    text: "Download",
                    title:
                        "Laporan Pendapatan Per Item Tanggal: " +
                        tglAwal +
                        " s.d. " +
                        tglAkhir,
                    filename:
                        "Laporan Pendapatan Per Item Tanggal: " +
                        tglAwal +
                        "  s.d. " +
                        tglAkhir,
                },
            ],
        })
        .buttons()
        .container()
        .appendTo(id + "_wrapper .col-md-6:eq(0)");
}

function reportPendapatanRuang(tglAwal, tglAkhir) {
    if ($.fn.DataTable.isDataTable("#tabelPerRuangUMUM,#tabelPerRuangBPJS")) {
        var tabel = $("#tabelPerRuangUMUM,#tabelPerRuangBPJS").DataTable();
        tabel.clear().destroy();
    }
    // var tglAwal = document.getElementById("tglAwal").value;
    // var tglAkhir = document.getElementById("tglAkhir").value;

    $.ajax({
        url: "/api/pendapatan/ruang",
        type: "post",
        data: {
            tglAwal: tglAwal,
            tglAkhir: tglAkhir,
        },
        success: function (response) {
            Swal.close();
            const dataUmum = response.umum;
            const dataBpjs = response.bpjs;
            // console.log("🚀 ~ reportPendapatanRuang ~ dataUmum:", dataUmum);
            isiTabelPendapatanRuang(dataUmum, "#tabelPerRuangUMUM");
            isiTabelPendapatanRuang(dataBpjs, "#tabelPerRuangBPJS");
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        },
    });
}

function isiTabelPendapatanRuang(data, id) {
    data.forEach(function (item, index) {
        item.no = index + 1;
    });
    $(id)
        .DataTable({
            data: data,
            columns: [
                { data: "no" },
                { data: "nmKelas" },
                {
                    data: "tanggal",
                    render: function (data) {
                        // Format the date using JavaScript
                        const formattedDate = new Date(data).toLocaleString(
                            "id-ID",
                            {
                                year: "numeric",
                                month: "numeric",
                                day: "numeric",
                            }
                        );
                        return formattedDate;
                    },
                },
                {
                    data: "jumlah",
                    render: function (data, type, row) {
                        var formattedTarif = parseInt(data).toLocaleString(
                            "id-ID",
                            {
                                style: "currency",
                                currency: "IDR",
                                minimumFractionDigits: 0,
                            }
                        );
                        return `${formattedTarif}`;
                    },
                },
                { data: "totalItem" },
            ],
            order: [0, "asc"],
            lengthChange: true,
            autoWidth: true,
            buttons: [
                {
                    extend: "excelHtml5",
                    text: "Download",
                    title:
                        "Laporan Pendapatan Per Ruang Tanggal: " +
                        tglAwal +
                        " s.d. " +
                        tglAkhir,
                    filename:
                        "Laporan Pendapatan Per Ruang Tanggal: " +
                        tglAwal +
                        "  s.d. " +
                        tglAkhir,
                },
            ],
        })
        .buttons()
        .container()
        .appendTo(id + "_wrapper .col-md-6:eq(0)");
}

function reportPendapatanTotalPerHari(tahun) {
    tahun === undefined ? $("#tahun").val() : tahun;
    // Destroy existing DataTables if they exist
    const tableIds = [
        "#tabelPendapatanTotalPerHariUMUM",
        "#tabelPendapatanTotalPerHariBPJS",
    ];

    tableIds.forEach((tableId) => {
        if ($.fn.DataTable.isDataTable(tableId)) {
            $(tableId).DataTable().clear().destroy();
        }
    });

    // console.log("🚀 ~ reportPendapatanTotalPerHari ~ tahun:", tahun);

    // Fetch data via AJAX
    $.ajax({
        url: `/api/pendapatan/${tahun}`,
        type: "GET",
        success: function (response) {
            Swal.close();

            // Initialize DataTables for each dataset
            isiTabelPendapatanTotalPerHari(
                response.umum,
                "#tabelPendapatanTotalPerHariUMUM",
                tahun,
                "umum"
            );
            isiTabelPendapatanTotalPerHari(
                response.bpjs,
                "#tabelPendapatanTotalPerHariBPJS",
                tahun,
                "bpjs"
            );
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
            Swal.fire({
                icon: "error",
                title: "Terjadi kesalahan!",
                text: `Gagal mengambil data: ${error}`,
            });
        },
    });
}

function isiTabelPendapatanTotalPerHari(data, tableId, tahun, selector) {
    data.forEach((item, index) => {
        item.no = index + 1;
        const today = new Date().toISOString().split("T")[0];
        const atrb = item.tanggal === today ? "" : "";
        if (item.btnColor === "btn btn-danger") {
            item.aksi = ` 
                <button class="btn btn-danger btn-sm mb-2 col-sm-9"
                    data-tgl="${item.tanggal}"
                    data-jumlah="${item.jumlah}"
                    data-asal_pendapatan="3.003.25581.5"
                    data-noSbs="${item.nomor}"
                    onclick="setorkan(this)" ${atrb}>Setorkan
                </button>
                <div class="row d-flex justify-content-center" id="divStpbSts" style="display: none !important;">
                    <a class="btn btn-sm btn-warning mr-2 mb-2"
                       onclick="cetakBaph('${item.nomor}','${item.tanggal}')"
                        target="_blank">
                        BAPH
                    </a>
                    <a class="btn btn-sm btn-success mr-2 mb-2 px-3"
                        onclick="cetakSbs('${item.nomor}','${item.tanggal}')"
                        target="_blank">
                        SBS
                    </a>
                </div>
            `;
        } else {
            item.aksi = ` 
                <button class="btn btn-danger btn-sm mb-2 col-sm-9"
                    data-tgl="${item.tanggal}"
                    data-jumlah="${item.jumlah}"
                    data-asal_pendapatan="3.003.25581.5"
                    data-noSbs="${item.nomor}"
                    onclick="setorkan(this)" ${atrb} style="display: none;">Setorkan
                </button>
                <div class="row d-flex justify-content-center" id="divStpbSts">
                    <a class="btn btn-sm btn-warning mr-2 mb-2"
                       onclick="cetakBaph('${item.nomor}','${item.tanggal}')"
                        target="_blank">
                        BAPH
                    </a>
                    <a class="btn btn-sm btn-success mr-2 mb-2 px-3"
                        onclick="cetakSbs('${item.nomor}','${item.tanggal}')"
                        target="_blank">
                        SBS
                    </a>
                </div>
            `;
        }
    });

    $(tableId)
        .DataTable({
            data: data,
            columns: [
                { data: "no", className: "text-center" },
                { data: "aksi", className: "text-center col-2" },
                { data: "tanggal", className: "text-center" },
                { data: "nomor", className: "text-center" },
                { data: "kode_akun" },
                { data: "uraian" },
                { data: "jumlah", className: "text-right" },
                { data: "pendapatan", className: "col-2" },
            ],
            autoWidth: false,
            order: [[0, "dsc"]],
            buttons: [
                {
                    extend: "excelHtml5",
                    text: "Excel",
                    title: `Laporan Pendapatan Tahun: ${tahun}`,
                    filename: `Laporan Pendapatan Tahun ${tahun}`,
                },
                {
                    extend: "colvis",
                    text: "Tampilkan Kolom",
                },
                {
                    text: "Update Data Total Pendapatan", // Teks tombol
                    action: function (e, dt, node, config) {
                        reportPendapatanTotalPerHari($("#tahun").val()); // Panggil fungsi custom
                    },
                },
            ],
        })
        .buttons()
        .container()
        .appendTo(`${tableId}_wrapper .col-md-6:eq(0)`);
}

function cetakSbs(noSBS, tgl) {
    const url = `/api/cetakSBS?noSBS=${encodeURIComponent(
        noSBS
    )}&tgl=${encodeURIComponent(tgl)}`;
    window.open(url, "_blank");
}
function cetakBaph(noSBS, tgl) {
    const url = `/api/cetakBAPH?noSBS=${encodeURIComponent(
        noSBS
    )}&tgl=${encodeURIComponent(tgl)}`;
    window.open(url, "_blank");
}

function setorkan(button) {
    // console.log("🚀 ~ setorkan ~ button:", button);
    const tgl = $(button).data("tgl"); // Format input: "08-01-2025"
    const [day, month, year] = tgl.split("-"); // Asumsikan format DD-MM-YYYY
    const tgl_setor = new Date(`${year}-${month}-${day}`)
        .toISOString()
        .split("T")[0];
    // console.log("🚀 ~ setorkan ~ tgl_setor:", tgl_setor);

    const pendapatan = $(button).data("jumlah");
    const asalPendapatan = $(button).data("asal_pendapatan");
    const penyetor = "Nasirin";
    const noSbs = $(button).data("nosbs");
    const rupiah = pendapatan.toLocaleString("id-ID", {
        style: "currency",
        currency: "IDR",
    });

    Swal.fire({
        title: "Setorkan Pendapatan",
        html: `
            <p>Jumlah pendapatan tanggal ${tgl}:</p>
            <p><strong>${rupiah}</strong></p>
            <label for="setoran" class="swal2-label">Masukkan nilai setoran:</label>
            <input id="setoran" type="number" class="swal2-input" placeholder="Contoh: 50000" value="${pendapatan}">
            <label for="tanggal_setor" class="swal2-label">Masukkan tanggal setoran:</label>
            <input id="tanggal_setor" type="date" class="swal2-input" value="${tgl_setor}">
        `,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya",
        cancelButtonText: "Tidak",
        preConfirm: () => {
            const setoran = document.getElementById("setoran").value;
            const tanggalSetor = document.getElementById("tanggal_setor").value;

            if (!setoran || !tanggalSetor) {
                Swal.showValidationMessage(
                    "Nilai setoran dan tanggal wajib diisi!"
                );
            }

            return { setoran, tanggalSetor };
        },
    }).then((result) => {
        if (result.isConfirmed) {
            // console.log("Setoran:", result.value.setoran);
            // console.log("Tanggal Setor:", result.value.tanggalSetor);

            const setoran = result.value.setoran;
            const tanggalSetor = result.value.tanggalSetor;

            // Kirim data melalui AJAX
            $.ajax({
                url: "/api/kasir/setorkan",
                type: "post",
                data: {
                    noSbs: noSbs,
                    tanggalPendapatan: tgl,
                    tanggalSetor: tanggalSetor,
                    pendapatan: pendapatan,
                    setoran: setoran,
                    asal_pendapatan: asalPendapatan,
                    penyetor: penyetor,
                },
                success: function (response) {
                    // console.log("🚀 ~ setorkan ~ response:", response);
                    if (response.status == "success") {
                        Swal.fire({
                            icon: "success",
                            title: response.message,
                        });

                        $(button).remove();

                        // Mencari div terkait dari button yang ditekan
                        const parent = button.parentElement;
                        const divStpbSts = parent.querySelector("#divStpbSts");

                        if (divStpbSts) {
                            divStpbSts.style.display = "flex";
                        }

                        //remove onclick
                        $(button).attr("onclick", "");
                    } else {
                        tampilkanError(response.message);
                    }
                },
                error: function (xhr) {
                    console.log("🚀 ~ setorkan ~ xhr:", xhr);
                    tampilkanError(xhr);
                },
            });
        }
    });
}

// function reportKunjungan(tglAwal, tglAkhir) {
//     if ($.fn.DataTable.isDataTable("#reportKunjungan")) {
//         var tabel = $("#reportKunjungan").DataTable();
//         tabel.clear().destroy();
//         $("#reportKunjungan thead").remove();
//         $("#reportKunjungan tbody").remove();
//     }
//     $.ajax({
//         url: "/api/kasir/rekap",
//         type: "post",
//         data: {
//             tglAwal: tglAwal,
//             tglAkhir: tglAkhir,
//         },
//         success: function (response) {
//             // Map response data to a structure suitable for DataTable
//             var dataTableData = response.map(function (item) {
//                 // Clone the item to avoid modifying the original object
//                 var clonedItem = Object.assign({}, item);

//                 // Transform pemeriksaan into an object with key-value pairs
//                 var pemeriksaanObj = {};
//                 item.pemeriksaan.forEach(function (pemeriksaan) {
//                     // Assign hasil to the respective pemeriksaan name
//                     pemeriksaanObj[pemeriksaan.nmLayanan] =
//                         pemeriksaan.totalHarga;
//                 });

//                 // Add the transformed pemeriksaan object to clonedItem
//                 clonedItem.pemeriksaan = pemeriksaanObj;

//                 return clonedItem;
//             });

//             // Extract all unique pemeriksaan types from the response
//             var uniquePemeriksaan = new Set();
//             response.forEach(function (item) {
//                 item.pemeriksaan.forEach(function (pemeriksaan) {
//                     uniquePemeriksaan.add(pemeriksaan.nmLayanan);
//                 });
//             });
//             // Create DataTable columns dynamically
//             var columns = [
//                 {
//                     data: null,
//                     title: "No",
//                     render: function (data, type, row, meta) {
//                         return meta.row + meta.settings._iDisplayStart + 1;
//                     },
//                 },
//                 { data: "tgl", title: "Tanggal" },
//                 { data: "norm", title: "NoRM" },
//                 { data: "jaminan", title: "Jaminan" },
//                 {
//                     data: "nama",
//                     title: "Nama",
//                     className: "col-2", // Set custom class for width
//                     render: function (data, type, row) {
//                         return data.toUpperCase();
//                     },
//                 },
//                 { data: "alamat", title: "Alamat", className: "col-3" }, // Set custom class for width
//                 {
//                     data: "tagihan",
//                     title: "Tagihan",

//                     render: function (data, type, row) {
//                         var formattedTarif = parseInt(data).toLocaleString(
//                             "id-ID",
//                             {
//                                 style: "currency",
//                                 currency: "IDR",
//                                 minimumFractionDigits: 0,
//                             }
//                         );
//                         return `${formattedTarif}`;
//                     },
//                 },
//                 {
//                     data: "bayar",
//                     title: "Bayar",

//                     render: function (data, type, row) {
//                         var formattedTarif = parseInt(data).toLocaleString(
//                             "id-ID",
//                             {
//                                 style: "currency",
//                                 currency: "IDR",
//                                 minimumFractionDigits: 0,
//                             }
//                         );
//                         return `${formattedTarif}`;
//                     },
//                 },
//                 {
//                     data: "kembalian",
//                     title: "Kembalian",

//                     render: function (data, type, row) {
//                         var formattedTarif = parseInt(data).toLocaleString(
//                             "id-ID",
//                             {
//                                 style: "currency",
//                                 currency: "IDR",
//                                 minimumFractionDigits: 0,
//                             }
//                         );
//                         return `${formattedTarif}`;
//                     },
//                 },
//             ];

//             // Add each unique pemeriksaan as a column with its name as title
//             uniquePemeriksaan.forEach(function (pemeriksaan) {
//                 columns.push({
//                     data: "pemeriksaan." + pemeriksaan,
//                     title: pemeriksaan, // Use pemeriksaan name as column title
//                     defaultContent: "-",
//                 });
//             });

//             // Initialize DataTable with dynamic columns
//             $("#reportKunjungan")
//                 .DataTable({
//                     data: dataTableData,
//                     columns: columns,
//                     order: [0, "dsc"],
//                     paging: false,
//                     autoWidth: false,
//                     buttons: [
//                         {
//                             extend: "excel",
//                             text: "Download",
//                             title:
//                                 "Laporan Kunjungan Kasir " +
//                                 tglAwal +
//                                 " s.d. " +
//                                 tglAkhir,
//                             filename:
//                                 "Daftar Kunjungan Kasir " +
//                                 tglAwal +
//                                 " s.d. " +
//                                 tglAkhir,
//                         },
//                     ],
//                     initComplete: function () {
//                         this.api()
//                             .table()
//                             .node()
//                             .classList.add("table", "table-bordered-custom");
//                     },
//                 })
//                 .buttons()
//                 .container()
//                 .appendTo("#reportKunjungan_wrapper .col-md-6:eq(0)");
//         },
//         error: function (xhr, status, error) {
//             console.error("Error:", error);
//         },
//     });
// }

// function olahDataRupiah(response) {
//     if ($.fn.DataTable.isDataTable("#reportKunjunganRp")) {
//         var tabel = $("#reportKunjunganRp").DataTable();
//         tabel.clear().destroy();
//         $("#reportKunjunganRp thead").remove();
//         $("#reportKunjunganRp tbody").remove();
//     }

//     var dataTableData = response.map(function (item) {
//         // Clone the item to avoid modifying the original object
//         var clonedItem = Object.assign({}, item);

//         // Transform pemeriksaan into an object with key-value pairs
//         var pemeriksaanObj = {};
//         item.pemeriksaan.forEach(function (pemeriksaan) {
//             // Assign hasil to the respective pemeriksaan name
//             pemeriksaanObj[pemeriksaan.nmLayanan] = pemeriksaan.totalHarga;
//         });

//         // Add the transformed pemeriksaan object to clonedItem
//         clonedItem.pemeriksaan = pemeriksaanObj;

//         return clonedItem;
//     });

//     // Extract all unique pemeriksaan types from the response
//     var uniquePemeriksaan = new Set();
//     response.forEach(function (item) {
//         item.pemeriksaan.forEach(function (pemeriksaan) {
//             uniquePemeriksaan.add(pemeriksaan.nmLayanan);
//         });
//     });
//     // Create DataTable columns dynamically
//     var columns = [
//         {
//             data: null,
//             title: "No",
//             render: function (data, type, row, meta) {
//                 return meta.row + meta.settings._iDisplayStart + 1;
//             },
//         },
//         { data: "tgl", title: "Tanggal" },
//         { data: "norm", title: "NoRM" },
//         { data: "jaminan", title: "Jaminan" },
//         {
//             data: "nama",
//             title: "Nama",
//             className: "col-2", // Set custom class for width
//             render: function (data, type, row) {
//                 return data.toUpperCase();
//             },
//         },
//         { data: "alamat", title: "Alamat", className: "col-3" }, // Set custom class for width
//         {
//             data: "tagihan",
//             title: "Tagihan",

//             render: function (data, type, row) {
//                 var formattedTarif = parseInt(data).toLocaleString("id-ID", {
//                     style: "currency",
//                     currency: "IDR",
//                     minimumFractionDigits: 0,
//                 });
//                 return `${formattedTarif}`;
//             },
//         },
//         {
//             data: "bayar",
//             title: "Bayar",

//             render: function (data, type, row) {
//                 var formattedTarif = parseInt(data).toLocaleString("id-ID", {
//                     style: "currency",
//                     currency: "IDR",
//                     minimumFractionDigits: 0,
//                 });
//                 return `${formattedTarif}`;
//             },
//         },
//         {
//             data: "kembalian",
//             title: "Kembalian",

//             render: function (data, type, row) {
//                 var formattedTarif = parseInt(data).toLocaleString("id-ID", {
//                     style: "currency",
//                     currency: "IDR",
//                     minimumFractionDigits: 0,
//                 });
//                 return `${formattedTarif}`;
//             },
//         },
//     ];

//     // Add each unique pemeriksaan as a column with its name as title
//     uniquePemeriksaan.forEach(function (pemeriksaan) {
//         columns.push({
//             data: "pemeriksaan." + pemeriksaan,
//             title: pemeriksaan, // Use pemeriksaan name as column title
//             defaultContent: "-",
//         });
//     });

//     drawTableReport(dataTableData, columns, "#reportKunjunganRp");
// }
function drawTableReport(data, columns, idTable) {
    if ($.fn.DataTable.isDataTable(idTable)) {
        $(idTable).DataTable().clear().destroy();
    }
    const judul =
        idTable == "#reportKunjungan"
            ? "Laporan Kunjungan Kasir"
            : "Laporan Kunjungan Kasir Report";
    // Initialize DataTable with dynamic columns
    $(idTable)
        .DataTable({
            columns: columns,
            data: data,
            // paging: false, // Matikan paging jika tidak diperlukan
            ordering: false, // Aktifkan atau matikan pengurutan sesuai kebutuhan
            scrollX: true, // Aktifkan scroll horizontal jika kolom banyak
            autoWidth: false,
            buttons: [
                {
                    extend: "colvis", // Tombol untuk mengatur visibilitas kolom
                    text: "Pilih Kolom",
                },
                {
                    extend: "excel", // Tombol ekspor ke Excel
                    text: "Download",
                    title: judul + tglAwal + " s.d. " + tglAkhir,
                    filename: judul + tglAwal + " s.d. " + tglAkhir,
                    exportOptions: {
                        columns: ":visible",
                    },
                },
            ],
            initComplete: function () {
                // Sembunyikan kolom berdasarkan indeks
                var table = $(idTable).DataTable();
                table.column(5).visible(false);
                table.column(7).visible(false);
                table.column(8).visible(false);
            },
        })
        .buttons()
        .container()
        .appendTo(idTable + "_wrapper .col-md-6:eq(0)");
}

async function reportKunjungan(tglAwal, tglAkhir) {
    // Hapus tabel DataTable jika sudah ada
    if ($.fn.DataTable.isDataTable("#reportKunjungan,#reportKunjunganRp")) {
        $("#reportKunjungan,#reportKunjunganRp").DataTable().clear().destroy();
        $("#headerRow").empty(); // Bersihkan header
        $("#footerRow").empty(); // Bersihkan footer
        $("#headerRowRp").empty(); // Bersihkan header
        $("#footerRowRp").empty(); // Bersihkan footer
    }

    // Fetch data dari API
    await fetch("/api/kasir/rekap", {
        method: "POST",
        credentials: "include",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        body: JSON.stringify({
            tglAwal: tglAwal,
            tglAkhir: tglAkhir,
            layanan: "UMUM",
        }),
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error("Gagal mengambil data");
            }
            return response.json();
        })
        .then((data) => {
            console.log("🚀 ~ Data API:", data);

            if (
                !data ||
                !Array.isArray(data.columns) ||
                data.columns.length === 0
            ) {
                throw new Error("Data kolom tidak valid atau kosong");
            }

            const dataRupiah = data.dataRupiah;
            const columns = data.columns.map((column) => ({
                title: column,
                data: column,
            }));

            // Ambil elemen header/footer
            const headerRow = document.getElementById("headerRow");
            const footerRow = document.getElementById("footerRow");
            const headerRowRp = document.getElementById("headerRowRp");
            const footerRowRp = document.getElementById("footerRowRp");

            // Pastikan elemen ada sebelum memprosesnya
            if (!headerRowRp || !footerRowRp) {
                console.error(
                    "Elemen headerRowRp atau footerRowRp tidak ditemukan."
                );
                return;
            }

            // Kosongkan sebelum mengisi ulang
            headerRowRp.innerHTML = "";
            footerRowRp.innerHTML = "";

            // Buat header/footer
            data.columns.forEach((column) => {
                const th = document.createElement("th");
                th.textContent = column;
                headerRowRp.appendChild(th);

                const td = document.createElement("td");
                td.textContent = ""; // Kosong, akan diisi nanti
                footerRowRp.appendChild(td);
            });
            data.columns.forEach((column) => {
                const th = document.createElement("th");
                th.textContent = column;
                headerRow.appendChild(th);

                const td = document.createElement("td");
                td.textContent = ""; // Kosong, akan diisi nanti
                footerRow.appendChild(td);
            });

            // Inisialisasi DataTables
            drawTableReport(data.data, columns, "#reportKunjungan");
            drawTableReport(data.dataRupiah, columns, "#reportKunjunganRp");
        })

        .catch((error) => {
            console.error("Terjadi kesalahan:", error.message);
        });
}

// async function reportPendaftaran(tglAwal, tglAkhir) {
//     const formattedTglAwal = formatDate(new Date(tglAwal));
//     const formattedTglAkhir = formatDate(new Date(tglAkhir));

//     // Destroy existing DataTable if initialized
//     if ($.fn.DataTable.isDataTable("#report")) {
//         $("#report").DataTable().destroy();
//     }
//     if ($.fn.DataTable.isDataTable("#total")) {
//         $("#total").DataTable().destroy();
//     }

//     try {
//         const response = await $.ajax({
//             url: "/api/kominfo/pendaftaran/report",
//             type: "POST",
//             data: {
//                 tanggal_awal: tglAwal,
//                 tanggal_akhir: tglAkhir,
//                 no_rm: "",
//             },
//         });

//         const { data: pendaftaran, total } = response;

//         // Process pendaftaran data
//         pendaftaran.forEach((item) => {
//             const nama_pasien = item.pasien_nama.replace(/'/g, "\\'");
//             const isBPJS = item.penjamin_nama === "BPJS";

//             item.aksi = `
//                 <button type="button" class="btn btn-sm btn-primary mr-2 mb-2"
//                         onclick="cetak('${item.pasien_no_rm}')" placeholder="Cetak">Label</button>
//                 <button type="button" class="btn btn-sm btn-${item.check_in} mr-2 mb-2" id="checkin" placeholder="Selesai"
//                         data-toggle="modal" data-target="#modalSep"
//                         onclick="isiForm('${item.pasien_no_rm}', '${nama_pasien}', '${item.penjamin_nama}', '${item.no_trans}', '${item.no_sep}', this)">
//                         <i class="fa-regular fa-square-check"></i></button>
//                 <a type="button" class="btn btn-sm btn-warning mr-2 mb-2" placeholder="Resume"
//                    href="/api/resume/${item.pasien_no_rm}/${item.tanggal}" target="_blank">Resume</a>
//             `;

//             item.status = item.check_in === "danger" ? "Belum" : "Selesai";
//         });

//         // Initialize DataTable for #total
//         $("#total")
//             .DataTable({
//                 data: [total],
//                 columns: [
//                     { data: "jumlah_no_antrian", className: "text-center" },
//                     { data: "jumlah_pasien", className: "text-center" },
//                     { data: "jumlah_pasien_batal", className: "text-center" },
//                     { data: "jumlah_nomor_skip", className: "text-center" },
//                     { data: "jumlah_BPJS", className: "text-center" },
//                     { data: "jumlah_UMUM", className: "text-center" },
//                     { data: "jumlah_pasien_LAMA", className: "text-center" },
//                     { data: "jumlah_pasien_BARU", className: "text-center" },
//                     { data: "jumlah_daftar_OTS", className: "text-center" },
//                     { data: "jumlah_daftar_JKN", className: "text-center" },
//                 ],
//                 autoWidth: false,
//                 ordering: false,
//                 paging: true,
//                 searching: false,
//                 lengthChange: false,
//                 buttons: [
//                     {
//                         extend: "excelHtml5",
//                         text: "Excel",
//                         title: `Laporan Pendaftaran Tanggal: ${formattedTglAwal} s.d. ${formattedTglAkhir}`,
//                         filename: `Laporan_Pendaftaran_${formattedTglAwal}_sd_${formattedTglAkhir}`,
//                     },
//                     {
//                         extend: "colvis",
//                         text: "Tampilkan Kolom",
//                     },
//                 ],
//             })
//             .buttons()
//             .container()
//             .appendTo("#total_wrapper .col-md-6:eq(0)");

//         Swal.close();
//     } catch (error) {
//         console.error("Error:", error);
//         Swal.fire({
//             icon: "error",
//             title: "Terjadi kesalahan saat mengambil data",
//             text: error.responseJSON?.message || error.statusText,
//         });
//     }
// }

function reportPendaftaran(tglAwal, tglAkhir) {
    prosesCariDataLaporan = true;
    console.log(
        "🚀 ~ reportPendaftaran ~ prosesCariDataLaporan:",
        prosesCariDataLaporan
    );
    var tglA = formatDate(new Date(tglAwal));
    var tglB = formatDate(new Date(tglAkhir));

    if ($.fn.DataTable.isDataTable("#rekapTotal")) {
        var tabletindakan = $("#rekapTotal").DataTable();
        tabletindakan.destroy();
    }

    $.ajax({
        url: "/api/kominfo/pendaftaran/report",
        type: "post",
        data: {
            tanggal_awal: tglAwal,
            tanggal_akhir: tglAkhir,
            no_rm: "",
        },
        success: function (response) {
            var pendaftaran = response["data"];
            var total = response["total"];
            var html = response["html"];
            // console.log("🚀 ~ reportPendaftaran ~ total:", total);
            // console.log("🚀 ~ reportPendaftaran ~ $data:", pendaftaran);

            pendaftaran.forEach(function (item, index) {
                var nama_pasien = item.pasien_nama.replace(/'/g, "\\'");
                let resume;
                if (item.penjamin_nama == "BPJS") {
                    resume = "";
                } else {
                    resume = "hidden";
                }
                item.aksi = `

                            <button type="button" class="btn btn-sm btn-primary mr-2 mb-2"
                                    onclick="cetak('${item.pasien_no_rm}')" placeholder="Cetak">Label</button>
                            <button type="button" class="btn btn-sm btn-${item.check_in} mr-2 mb-2" id="checkin" placeholder="Selesai" data-toggle="modal"
                                    data-target="#modalSep" onclick="isiForm('${item.pasien_no_rm}', '${nama_pasien}','${item.penjamin_nama}','${item.no_reg}','${item.no_sep}',this)">
                                    <i class="fa-regular fa-square-check"></i></button>
                            <a type="button" class="btn btn-sm btn-warning mr-2 mb-2" placeholder="Resume"
                                    href="/api/resume/${item.pasien_no_rm}/${item.tanggal}" target="_blank">Resume</a>
                            <a type="button" class="btn btn-sm btn-${item.statusDaftar} mr-2 mb-2" placeholder="Resume"
                            data-norm="${item.pasien_no_rm}"
                            data-notrans="${item.no_reg}"
                            data-no_urut="${item.antrean_nomor}"
                            data-tgltrans="${item.waktu_verifikasi}"
                                    onclick="daftarkan(this)">Daftarkan</a>
                            `;
                if (item.check_in == "danger") {
                    item.status = "Belum";
                } else {
                    item.status = "Selesai";
                }
            });
            // Inisialisasi DataTable

            $("#tabelJumlah").html(html);
            $("#rekapTotal")
                .DataTable({
                    autoWidth: false,
                    ordering: false,
                    paging: false,
                    searching: false,
                    info: false,
                    lengthChange: false,
                    buttons: [
                        {
                            extend: "excelHtml5",
                            text: "Excel",
                            title:
                                "Laporan Jumlah Pendaftaran Tanggal: " +
                                tglA +
                                " s.d. " +
                                tglB,
                            filename:
                                "Laporan Jumlah Pendaftaran Tanggal " +
                                tglA +
                                " s.d. " +
                                tglB,
                        },
                        {
                            extend: "colvis",
                            text: "Tampilkan Kolom",
                        },
                    ],
                })
                .buttons()
                .container()
                .appendTo("#rekapTotal_wrapper .col-md-6:eq(0)");

            Swal.close();
            setTimeout(function () {
                prosesCariDataLaporan = false;
            }, 3000);
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
            Swal.fire({
                icon: "error",
                title:
                    "Terjadi kesalahan saat mengambil data pasien...!!!\n" +
                    error,
            });
        },
    });
}
function formatDate(date) {
    // Convert the input to a Date object if it isn't already
    if (!(date instanceof Date)) {
        date = new Date(date);
    }

    // Check if the date is valid
    if (isNaN(date)) {
        throw new Error("Invalid date");
    }

    let day = String(date.getDate()).padStart(2, "0");
    let month = String(date.getMonth() + 1).padStart(2, "0"); // getMonth() returns month from 0-11
    let year = date.getFullYear();
    return `${day}-${month}-${year}`;
}

let tglAwal;
let tglAkhir;

let tahun = $("#tahun").val();
// console.log("🚀 ~ tahun:", tahun);
function updateData() {
    // reportPendaftaran(tglAwal, tglAkhir);
    reportPendapatanItem(tglAwal, tglAkhir);
    reportKunjungan(tglAwal, tglAkhir);
    reportPendapatanRuang(tglAwal, tglAkhir);
    reportPendapatanTotalPerHari(tahun);
}

function initializeDataTable(selector, titlePrefix, tglAwal, tglAkhir) {
    if ($.fn.DataTable.isDataTable(selector)) {
        $(selector).DataTable().destroy();
    }

    $(selector)
        .DataTable({
            destroy: true,
            dom: "Bfrtip",
            buttons: [
                {
                    extend: "excelHtml5",
                    text: "Download",
                    title: `${titlePrefix} Tanggal: ${tglAwal} s.d. ${tglAkhir}`,
                    filename: `${titlePrefix} Tanggal: ${tglAwal} s.d. ${tglAkhir}`,
                },
            ],
        })
        .buttons()
        .container()
        .appendTo(`${selector}_wrapper .col-md-6:eq(0)`);
}

window.addEventListener("load", function () {
    setTodayDate();
    var today = new Date().toISOString().split("T")[0];
    $("#tanggal").val(today);

    tglAwal = moment().subtract(0, "days").format("YYYY-MM-DD");
    tglAkhir = moment().subtract(0, "days").format("YYYY-MM-DD");

    // Menetapkan nilai ke input tanggal
    $("#reservation, #tglJumlah").val(tglAwal + " to " + tglAkhir);

    // Date range picker
    $("#reservation, #tglJumlah").daterangepicker({
        startDate: tglAwal,
        endDate: tglAkhir,
        autoApply: true,
        locale: {
            format: "YYYY-MM-DD",
            separator: " to ",
            applyLabel: "Apply",
            cancelLabel: "Cancel",
            customRangeLabel: "Custom Range",
        },
    });

    $("#reservation, #tglJumlah").on(
        "apply.daterangepicker",
        function (ev, picker) {
            tglAwal = picker.startDate.format("YYYY-MM-DD");
            tglAkhir = picker.endDate.format("YYYY-MM-DD");

            // Lakukan sesuatu dengan startDate dan endDate
            Swal.fire({
                icon: "info",
                title: "Sedang mencarikan data...!!!",
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            });
            updateData();
        }
    );
    Swal.fire({
        icon: "info",
        title: "Sedang mencarikan data...!!!",
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });
    reportPendaftaran(tglAwal, tglAkhir);
    reportKunjungan(tglAwal, tglAkhir);

    initializeDataTable(
        "#tabelPerItemUMUM",
        "Laporan Pendapatan Per Item UMUM",
        tglAwal,
        tglAkhir
    );
    initializeDataTable(
        "#tabelPerItemUMUMBln",
        "Laporan Pendapatan Per Item UMUM Bulanan",
        tglAwal,
        tglAkhir
    );
    initializeDataTable(
        "#tabelPerItemBPJS",
        "Laporan Pendapatan Per Item BPJS",
        tglAwal,
        tglAkhir
    );

    initializeDataTable(
        "#tabelPerRuangBPJS",
        "Laporan Pendapatan Per Ruang BPJS",
        tglAwal,
        tglAkhir
    );
    initializeDataTable(
        "#tabelPerRuangUMUM",
        "Laporan Pendapatan Per Ruang UMUM",
        tglAwal,
        tglAkhir
    );

    initializeDataTable(
        "#tabelPendapatanTotalPerHariUMUM",
        "Laporan Pendapatan Total UMUM",
        tglAwal,
        tglAkhir
    );
    initializeDataTable(
        "#tabelPendapatanTotalPerHariBPJS",
        "Laporan Pendapatan Total BPJS",
        tglAwal,
        tglAkhir
    );
    const tableIds = [
        "#tabelPendapatanTotalPerHariUMUM",
        "#tabelPendapatanTotalPerHariBPJS",
    ];

    tableIds.forEach((tableId) => {
        if ($.fn.DataTable.isDataTable(tableId)) {
            $(tableId).DataTable().clear().destroy();
        }
    });

    isiTabelPendapatanTotalPerHari(
        dataSBSU,
        "#tabelPendapatanTotalPerHariUMUM",
        tahun,
        "umum"
    );
    isiTabelPendapatanTotalPerHari(
        dataSBSB,
        "#tabelPendapatanTotalPerHariBPJS",
        tahun,
        "bpjs"
    );
});
