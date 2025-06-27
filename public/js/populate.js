var Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
});

let jk = "";
function fetchDataAntrian(ruang, params, callback) {
    $.ajax({
        url: "/api/cpptKominfo",
        type: "POST",
        data: params,
        success: callback,
        error: function (xhr) {
            console.error("Error fetching data:", xhr);
            if (xhr.status === 404) {
                Toast.fire({
                    icon: "error",
                    title: "Data Tidak Ditemukan",
                });
                handleNoData(ruang);
            }
        },
    });
}

function handleNoData(ruang) {
    const dataArray = getNoDataMessage();
    drawDataTable(dataArray, ruang, "#dataAntrian");
    drawDataTable(dataArray, ruang, "#dataSelesai");
    if (ruang === "ro") {
        drawDataTable(dataArray, ruang, "#daftarUpload");
        drawDataTable(dataArray, ruang, "#dataKonsul");
    }
}

function initializeDataAntrian(response, ruang) {
    const data = response?.response?.data || [];
    updateTableData(data, ruang, "belum", "#dataAntrian");
    updateTableData(data, ruang, "sudah", "#dataSelesai");

    if (ruang === "ro") {
        // console.log("🚀 ~ initializeDataAntrian ~ ro:", data);
        updateTableData(
            data,
            ruang,
            "Belum Upload Foto Thorax",
            "#daftarUpload"
        );
        updateTableDataKonsul(data, ruang, true, "#dataKonsul");
    }
}
function updateTableData(data, ruang, status, tableId) {
    const filteredData = data.filter((item) => item.status === status);
    const dataArray = filteredData.length ? filteredData : getNoDataMessage();
    const nama = dataArray[0]?.pasien_nama;
    // console.log("🚀 ~ updateTableData ~ nama:", nama);
    if (nama !== "Belum ada data masuk") {
        processDataArray(dataArray, ruang, false);
    }
    drawDataTable(dataArray, ruang, tableId);
}
function updateTableDataKonsul(data, ruang, status, tableId) {
    const filteredData = data.filter(
        (item) => item.permintaan_konsul === status
    );
    const dataArray = filteredData.length ? filteredData : getNoDataMessage();
    // console.log("🚀 ~ updateTableDataKonsul ~ a:", dataArray);
    const nama = dataArray[0]?.pasien_nama;
    // console.log("🚀 ~ updateTableData ~ nama:", nama);
    if (nama !== "Belum ada data masuk") {
        processDataArray(dataArray, ruang, true);
    }
    drawDataTable(dataArray, ruang, tableId);
}

function antrian(ruang) {
    $("#loadingSpinner").show();
    const params = {
        tanggal_awal: $("#tanggal").val(),
        tanggal_akhir: $("#tanggal").val(),
        ruang: ruang,
    };
    // const params = {
    //     tanggal_awal: "2024-12-27",
    //     tanggal_akhir: "2024-12-27",
    //     ruang: ruang,
    // };

    fetchDataAntrian(ruang, params, function (response) {
        $("#loadingSpinner").hide();
        if ($.fn.DataTable.isDataTable("#dataAntrian")) {
            updateExistingTables(response, ruang);
        } else {
            initializeDataAntrian(response, ruang);
        }
    });
}

function updateExistingTables(response, ruang) {
    const data = response?.response?.data || [];
    updateTable("#dataAntrian", data, ruang, "belum");
    updateTable("#dataSelesai", data, ruang, "sudah");

    if (ruang === "ro") {
        updateTable("#daftarUpload", data, ruang, "Belum Upload Foto Thorax");
        updateTableKonsul("#dataKonsul", data, ruang, true);
    }
}

function updateTable(tableId, data, ruang, status) {
    const filteredData = data.filter((item) => item.status === status);
    // console.log("🚀 ~ updateTable ~ filteredData:", filteredData);
    const table = $(tableId).DataTable();
    const dataArray = filteredData.length ? filteredData : getNoDataMessage();
    const nama = dataArray[0]?.pasien_nama;
    // console.log("🚀 ~ updateTableData ~ nama:", nama);
    if (nama !== "Belum ada data masuk") {
        processDataArray(dataArray, ruang, false);
    }
    table.clear().rows.add(dataArray).draw();
}
function updateTableKonsul(tableId, data, ruang, status) {
    const filteredData = data.filter(
        (item) => item.permintaan_konsul === status
    );
    // console.log("🚀 ~ updateTableKonsul ~ filteredData:", filteredData);
    const table = $(tableId).DataTable();
    const dataArray = filteredData.length ? filteredData : getNoDataMessage();
    const nama = dataArray[0]?.pasien_nama;
    // console.log("🚀 ~ updateTableData ~ nama:", nama);
    if (nama !== "Belum ada data masuk") {
        processDataArray(dataArray, ruang, true);
    }
    table.clear().rows.add(dataArray).draw();
}

function processDataArray(dataArray, ruang, tableId) {
    dataArray.forEach((item, index) => {
        item.cekRo = item.radiologi.length > 0 ? true : false;
        item.cekLab = item.laboratorium.length > 0 ? true : false;
        item.cekIgd = item.tindakan.length > 0 ? true : false;
        item.index = index + 1;
        switch (ruang) {
            case "dots":
                item.nmDiagnosa = item.diagnosa[0]?.nama_diagnosa || "";
                break;
            case "ro":
                item.asktind = generateAsktindString(item.radiologi);
                break;
            case "igd":
                item.asktind = generateAsktindString(item.tindakan, true);
                break;
            case "lab":
                item.asktind = generateAsktindString(
                    item.laboratorium,
                    false,
                    true
                );
                break;
        }
        item.aksi = generateActionButton(item, ruang, tableId);
        let btn = item.status_konsul === "belum" ? "btn-danger" : "btn-success";
        const today = new Date(2024, 9, 3).setHours(0, 0, 0, 0);
        const date = new Date(item.tanggal).setHours(0, 0, 0, 0);
        const notrans = date <= today ? item.no_trans : item.no_reg;
        item.aksiKonsul = `
                                <a type="button"
                                    data-toggle="tooltip" data-placement="right" title="Transaksi Konsul ${
                                        item.status_konsul
                                    }"
                                    data-norm="${item.pasien_no_rm}"
                                    data-nama="${item.pasien_nama}"
                                    data-dokter="${item.dokter_nama}"
                                    data-asktind="${
                                        item.asktind?.trim() || "No data"
                                    }"
                                    data-kddokter="${item.nip_dokter}"
                                    data-alamat="${getFormattedAddress(item)}"
                                    data-layanan="${item.penjamin_nama}"
                                    data-notrans="${notrans}"
                                    data-tgltrans="${item.tanggal}"
                                    data-umur="${item.umur}"
                                    data-jk="${item.jenis_kelamin_nama}"
                                    class="aksi-button btn-sm ${btn} icon-link icon-link-hover"
                                    onclick="setKonsul(this)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
                                    <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
                                    </svg>
                                </a>
                            `;
    });
}

function setKonsul(button) {
    Swal.fire({
        icon: "info",
        title: "Sedang megnirim data konsul...!!!",
        showConfirmButton: true,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });
    $.ajax({
        url: "/api/ro/konsul",
        type: "post",
        data: {
            notrans: $(button).data("notrans"),
            nama: $(button).data("nama"),
            norm: $(button).data("norm"),
        },
        success: function (response) {
            // console.log("🚀 ~ setKonsul ~ response:", response);
            Swal.fire({
                icon: "success",
                title: response.metadata.message,
            });
            button.classList.remove("btn-danger");
            button.classList.add("btn-success");
        },
        error: function (xhr, status, error) {
            console.log(error);
            Swal.fire({
                icon: "error",
                title: "Terjadi Kesalahan",
                text: "Gagal mengirm konsul, data: " + error,
            });
        },
    });
}

function drawDataTable(dataArray, ruang, tableId) {
    const columns = getColumnsForRuang(ruang, tableId);

    $(tableId).DataTable({
        data: dataArray,
        columns: columns,
        order: [
            [1, "asc"],
            [2, "asc"],
        ],
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        destroy: true, // Allow table to reinitialize
    });
}

function generateActionButton(item, ruang, konsul) {
    const today = new Date(2024, 9, 3).setHours(0, 0, 0, 0);
    const date = new Date(item.tanggal).setHours(0, 0, 0, 0);
    const notrans = date <= today ? item.no_trans : item.no_reg;
    const tgl = ``;
    // const tgl = `<br><br><p>${item.tanggal}</p>`;

    const commonAttributes = `
        data-norm="${item.pasien_no_rm}"
        data-nama="${item.pasien_nama}"
        data-dokter="${item.dokter_nama}"
        data-asktind="${item.asktind?.trim() || "No data"}"
        data-kddokter="${item.nip_dokter}"
        data-alamat="${getFormattedAddress(item)}"
        data-layanan="${item.penjamin_nama}"
        data-notrans="${notrans}"
        data-tgltrans="${item.tanggal}"
        data-umur="${item.umur}"
        data-jk="${item.jenis_kelamin_nama}"
        data-tujuan="${getPenunjangText(item)}"
        data-ro="${item.cekRo}"
        data-igd="${item.cekIgd}"
        data-lab="${item.cekLab}"
    `;

    const actionMap = {
        dots: `cariPasienTb('${item.pasien_no_rm}','${item.tanggal}')`,
        ro: `setTransaksi(this,'${ruang}')`,
        igd: `setTransaksi(this,'${ruang}')`,
        lab: `setTransaksi(this,'${ruang}')`,
    };

    const editButton = `
        <a type="button" class="aksi-button btn-sm btn-primary icon-link icon-link-hover"
            ${commonAttributes} onclick="${actionMap[ruang]}">
            <i class="fas fa-pen-to-square"></i>
        </a>`;

    if (ruang === "igd") {
        const checkOutButton = `
            <a type="button" ${commonAttributes}
                class="aksi-button btn-sm btn-${item.igd_selesai} mt-md-0 mt-2 icon-link icon-link-hover"
                onclick="checkOut('${item.pasien_no_rm}', '${notrans}', this, '${ruang}')"
                placeholder="Selesai">
                <i class="fa-regular fa-square-check"></i>
            </a>`;
        return `${editButton} ${checkOutButton} ${tgl}`;
    }

    if (ruang === "ro") {
        const deleteButton = `
            <a type="button" ${commonAttributes}
                class="aksi-button btn-sm btn-danger icon-link icon-link-hover"
                onclick="deleteTransaksi(this);">
                <i class="fas fa-trash"></i>
            </a>`;

        if (
            item.status === "sudah" ||
            item.status === "Belum Upload Foto Thorax"
        ) {
            return `${editButton} ${deleteButton} ${tgl}`;
        }

        return editButton + tgl;
    }

    // Default case for other `ruang` values
    return editButton + tgl;
}

function getFormattedAddress(item) {
    return `${item.kelurahan_nama}, ${item.pasien_rt}/${item.pasien_rw}, ${item.kecamatan_nama}, ${item.kabupaten_nama}`;
}

function getColumnsForRuang(ruang, tableId) {
    // Tentukan kolom aksi berdasarkan tableId
    const aksiColumns =
        tableId === "#dataKonsul"
            ? [
                  {
                      data: "aksiKonsul",
                      className: "col-1 text-center p-2",
                      title: "Aksi",
                  },
              ]
            : [
                  {
                      data: "aksi",
                      className: "col-1 text-center p-2",
                      title: "Aksi",
                  },
              ];

    // Kolom umum untuk semua ruang
    const commonColumns = [
        {
            data: "status",
            title: "Status",
            className: "text-center p-2",
            render: function (data) {
                const statusClasses = {
                    belum: "danger",
                    sudah: "success",
                    "Belum Upload Foto Thorax": "warning",
                    default: "secondary",
                };
                return `<div class="badge badge-${
                    statusClasses[data] || statusClasses.default
                }">${data}</div>`;
            },
        },
        {
            data: "status_obat",
            title: "Obat",
            className: "text-center p-2",
            render: (data) =>
                `<div class="badge badge-${
                    data === "Obat Belum" ? "danger" : "success"
                }">${data}</div>`,
        },
        {
            data: "tanggal",
            title: "Tanggal",
            className: "text-center p-2 col-1",
        },
        {
            data: "antrean_nomor",
            title: "Urut",
            className: "font-weight-bold text-center p-2",
        },
        {
            data: "penjamin_nama",
            title: "Penjamin",
            className: "text-center p-2",
        },
        { data: "pasien_no_rm", title: "NoRM", className: "text-center p-2" },
        { data: "pasien_nama", title: "Pasien", className: "p-2 col-2" },
        { data: "dokter_nama", title: "Dokter", className: "p-2 col-3" },
    ];

    // Kolom tambahan berdasarkan ruang
    const extraColumns = {
        dots: [
            {
                data: "nmDiagnosa",
                title: "Diagnosa",
                className: "p-2 col-4",
                title: "Diagnosa",
            },
        ],
        ro: [{ data: "asktind", title: "Permintaan", className: "p-2 col-3" }],
        igd: [{ data: "asktind", title: "Permintaan", className: "p-2 col-4" }],
        lab: [{ data: "asktind", title: "Permintaan", className: "p-2 col-4" }],
    };

    // Gabungkan semua kolom dan kembalikan hasilnya
    return [...aksiColumns, ...commonColumns, ...(extraColumns[ruang] || [])];
}

function getNoDataMessage() {
    return [
        {
            aksi: "",
            aksiKonsul: "",
            status: "",
            status_obat: "",
            tanggal: "",
            antrean_nomor: "",
            pasien_no_rm: "",
            penjamin_nama: "",
            pasien_nama: "Belum ada data masuk",
            dokter_nama: "",
            nmDiagnosa: "",
            asktind: "",
            laboratorium: [],
            tindakan: [],
            radiologi: [],
        },
    ];
}

function getPenunjangText(item) {
    // console.log("🚀 ~ getPenunjangText ~ item:", item);

    return `
        <div>
            <h6>Penunjang Hari ini:</h6>
            ${item.laboratorium.length > 0 ? "Laboratorium, " : ""}
            ${item.tindakan.length > 0 ? "IGD, " : ""}
            ${item.radiologi.length > 0 ? "Radiologi, " : ""}
        </div>`;
}

//antrianall
function fetchDataAntrianAll(tanggal, ruang, callback) {
    $.ajax({
        url: "/api/antrian/kominfo",
        type: "post",
        data: { tanggal, ruang },
        success: callback,
        error: function (xhr) {
            console.error("Error fetching data:", xhr);
        },
    });
}

function initializeDataTable(selector, data, columns, ruang) {
    let order;
    if (ruang === "surat") {
        order = [[0, "dsc"]];
    } else if (ruang === "farmasi") {
        order = [[1, "asc"]];
    } else {
        order = [[2, "asc"]];
    }
    $(selector).DataTable({
        data,
        columns,
        order,
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        destroy: true,
        autoWidth: true,
    });
}

function getColumnDefinitions(statusType = "status_pulang", ruang) {
    // console.log("🚀 ~ getColumnDefinitions ~ ruang:", ruang);
    const baseColumns = [
        {
            data: "antrean_nomor",
            className: "font-weight-bold text-center p-2 col-1",
            title: "Urut",
        },
        {
            data: statusType,
            className: "text-center p-2",
            title: "Status Pulang",
            render: function (data) {
                const statusClasses = {
                    "Belum Pulang": "danger",
                    "Sudah Pulang": "success",
                    "Tidak Ada Permintaan": "danger",
                    "Belum Ada Ts RO": "danger",
                    "Belum Upload Foto Thorax": "warning",
                    "Sudah Selesai": "success",
                    default: "secondary",
                };
                return `<div class="badge badge-${
                    statusClasses[data] || statusClasses.default
                }">${data}</div>`;
            },
        },
        { data: "tanggal", className: "col-1 p-2", title: "Tanggal" },
        {
            data: "penjamin_nama",
            className: "text-center p-2",
            title: "Penjamin",
        },
        { data: "pasien_no_rm", className: "text-center p-2", title: "NoRM" },
    ];

    const extraColumns = [
        { data: "pasien_nik", className: "p-2 col-1", title: "NIK" },
    ];

    const commonColumns = [
        { data: "pasien_nama", className: "p-2 col-3", title: "Nama Pasien" },
        { data: "dokter_nama", className: "p-2 col-3", title: "Dokter" },
        { data: "poli_nama", className: "p-2", title: "Poli" },
    ];
    const suratColumns = [
        {
            data: "antrean_nomor",
            className: "font-weight-bold text-center p-2 col-1",
            title: "Urut",
        },
        {
            data: statusType,
            className: "text-center p-2",
            title: "Status",
            render: function (data, type, row) {
                const statusClasses = {
                    "Belum Pulang": "danger",
                    "Sudah Pulang": "success",
                    "Tidak Ada Permintaan": "danger",
                    "Belum Ada Ts RO": "danger",
                    "Belum Upload Foto Thorax": "warning",
                    "Sudah Selesai": "success",
                    default: "secondary",
                };
                const tgl = row.tanggal;
                return `<div class="badge badge-${
                    statusClasses[data] || statusClasses.default
                }">${data}</div>
                <div class="badge badge-info">${tgl}</div>
                `;
            },
        },
        // { data: "tanggal", className: "col-1 p-2", title: "Tanggal" },
        // { data: "pasien_no_rm", className: "text-center p-2", title: "NoRM" },
        // { data: "pasien_nama", className: "p-2 col-2", title: "Nama Pasien" },
        {
            data: null,
            className: "p-2 col-3",
            title: "Pasien",
            render: function (data, type, row) {
                const nama = row.pasien_nama || "-";
                const norm = row.pasien_no_rm || "-";
                return `
                ${nama}<br>
                ( ${norm} )<br>
            `;
            },
        },
        {
            data: null,
            className: "p-2 col-3",
            title: "Dokter & Poli",
            render: function (data, type, row) {
                const dokter = row.dokter_nama || "-";
                const poli = row.poli_nama || "-";
                const penjamin = row.penjamin_nama || "-";
                return `
                ${dokter}<br>
                 <small><strong>Poli:</strong> ${poli}</small><br>
                <small><strong>Jaminan:</strong> ${penjamin}</small>
            `;
            },
        },
    ];

    let aksiColumns;
    if (ruang === "surat") {
        aksiColumns = [
            {
                data: "aksi",
                className: "p-2 col-2 text-center",
                title: "Aksi",
            },
        ];
    } else {
        aksiColumns = [
            { data: "aksi", className: "p-2 text-center col-1", title: "Aksi" },
        ];
    }
    const ketColumns = [
        {
            data: "status",
            className: "text-center p-2",
            title: "Status Tindakan",
            render: function (data) {
                const statusClasses = {
                    "Belum Pulang": "danger",
                    "Sudah Pulang": "success",
                    "Tidak Ada Permintaan": "danger",
                    "Belum Ada Ts RO": "danger",
                    "Belum Upload Foto Thorax": "warning",
                    "Sudah Selesai": "success",
                    default: "secondary",
                };
                return `<div class="badge badge-${
                    statusClasses[data] || statusClasses.default
                }">${data}</div>`;
            },
        },
    ];
    // Konfigurasi kolom untuk setiap ruang
    const columnConfig = {
        surat: [
            ...aksiColumns,
            // ...baseColumns,
            // ...extraColumns,
            ...suratColumns,
        ],
        lab: [
            ...aksiColumns,
            ...baseColumns,
            ...extraColumns,
            ...commonColumns,
        ],
        dots: [
            ...aksiColumns,
            ...baseColumns,
            ...extraColumns,
            ...commonColumns,
        ],
        igd: [...aksiColumns, ...baseColumns, ...commonColumns, ...ketColumns],
        default: [...aksiColumns, ...baseColumns, ...commonColumns],
    };

    // Return kolom sesuai ruang, atau gunakan default
    return columnConfig[ruang] || columnConfig.default;
}

function processResponse(response, ruang, statusFilter) {
    if (!response || !response.response || !response.response.data) {
        console.error("Invalid response format:", response);
        return;
    }

    const data = response.response.data.map((item) => ({
        ...item,
        tgl: $("#tanggal").val(),
        aksi: generateActionLink(item, ruang, statusFilter),
    }));

    const dataSelesai = data.filter((item) => item.status === statusFilter);
    const daftarTunggu = data.filter(
        (item) =>
            item.status === "Tidak Ada Transaksi" &&
            item.status_pulang === "Sudah Pulang"
    );
    const daftarTungguUmum = data.filter(
        (item) =>
            item.status === "Tidak Ada Transaksi" &&
            item.status_pulang === "Sudah Pulang" &&
            item.penjamin_nama === "UMUM"
    );
    const daftarTungguBpjs = data.filter(
        (item) =>
            item.status === "Tidak Ada Transaksi" &&
            item.status_pulang === "Sudah Pulang" &&
            item.penjamin_nama === "BPJS"
    );

    return {
        data,
        dataSelesai,
        daftarTunggu,
        daftarTungguUmum,
        daftarTungguBpjs,
    };
}

function generateActionLink(item, ruang, statusFilter) {
    const today = new Date(2024, 9, 3);
    today.setHours(0, 0, 0, 0);

    let notrans;
    let actionLink;

    // Compare item.tanggal with today
    const date = new Date(item.tanggal);
    date.setHours(0, 0, 0, 0); // Set time to 00:00:00.000
    // console.log("🚀 ~ generateActionLink ~ date:", date);
    if (date <= today) {
        notrans = item.no_trans; // Use no_trans if date is less than or equal to today
    } else {
        notrans = item.no_reg; // Use no_reg if date is after today
    }
    // console.log("🚀 ~ generateActionLink ~ notrans antrian cppt:", notrans);
    const commonAttributes = `
        data-tgltrans="${item.tanggal}"
        data-norm="${item.pasien_no_rm}"
        data-nama="${item.pasien_nama}"
        data-tglLahir="${item.pasien_tgl_lahir}"
        data-nik="${item.pasien_nik || "-"}"
        data-alamat="${item.pasien_alamat}"
        data-asktind="${item.asktind || ""}"
        data-jk="${item.jenis_kelamin_nama}"
        data-umur="${item.pasien_umur}"
        data-kddokter="${item.nip_dokter}"
        data-layanan="${item.penjamin_nama}"
        data-notrans="${notrans}"
        data-noreg="${item.no_reg}"
        data-dokter="${item.dokter_nama}"
        data-alamatPang="${item.pasien_alamat_pang}"
        data-umurPang="${item.pasien_umur_tahun}"
    `;
    const createLink = (
        iconClass,
        action,
        ruang,
        extraClass = "",
        extraAttributes = "",
        text = ""
    ) => `
            <a type="button" ${commonAttributes}
            class="aksi-button btn-sm btn-primary py-md-0 py-1 icon-link icon-link-hover ${extraClass}"
            onclick="${action}(this,'${ruang}');" ${extraAttributes}>
            ${text}    <i class="${iconClass}"></i>
            </a>
        `;

    const linkLog = `<a type="button" class="aksi-button btn-sm btn-warning py-md-0 py-1 m-1 col icon-link icon-link-hover"
                    onclick="cariLog('${item.id}','${item.pasien_no_rm}','${item.tanggal}');">
                    <strong>Posisi</strong>
                </a>`;
    const linkCppt = `<a type="button" class="aksi-button btn-sm btn-success py-md-0 py-1 m-1 col icon-link icon-link-hover"
                    onclick="riwayatKunjungan('${item.pasien_no_rm}','${item.pasien_nama}');">
                    <strong>CPPT</strong>                   
                </a>`;
    const buttonColor = item.button;
    let panggilan;
    if (buttonColor === "warning") {
        let txPanggilan = "Belum";
        panggilan = `<div class="badge badge-${buttonColor}">${txPanggilan}</div>`;
    } else {
        let txPanggilan = "Sudah";
        panggilan = `<div class="badge badge-${buttonColor}">${txPanggilan}</div>`;
    }
    const links = {
        dots:
            createLink("fas fa-pen-to-square", "setTransaksi", ruang) + linkLog,
        surat: `<div class="row">
        ${createLink(
            "",
            "setTransaksi",
            ruang,
            "col m-1",
            "",
            "<strong>Surat</strong>"
        )}<br>
            ${linkLog}<br>
            ${linkCppt}<br>            
                 <a type="button"
                      data-toggle="tooltip" data-placement="right" title="Transaksi Konsul 
                      ${item.konsul_ro === "danger" ? "Belum" : "Sudah"}"
                      ${commonAttributes}
                     class="aksi-button btn-sm btn-${item.konsul_ro} 
                     py-md-0 py-1 m-1 col icon-link icon-link-hover"
                     onclick="setKonsul(this)">Konsul RO
                </a>
                           
            </div>`,
        kasir:
            // item.status === "Sudah Selesai"
            //     ?
            createLink("fas fa-pen-to-square", "setTransaksi", ruang) +
            createLink(
                "fa-solid fa-volume-high",
                "celuk",
                ruang,
                "ml-3 btn-warning"
            ),
        // : item.status === "Tidak Ada Transaksi"
        // ? createLink("fas fa-pen-to-square", "setTransaksi", ruang)
        // : `<a></a>`
        farmasi:
            item.status === "Sudah Selesai"
                ? createLink(
                      "fa-solid fa-volume-high",
                      "celuk",
                      ruang,
                      "ml-3 btn-" + buttonColor,
                      "",
                      panggilan
                  )
                : item.status === "Tidak Ada Transaksi"
                ? createLink("fas fa-pen-to-square", "setTransaksi", ruang)
                : `<a></a>`,
        default: linkLog,
    };

    // Mengembalikan tautan yang sesuai berdasarkan ruang dan statusFilter
    return links[ruang] || links.default;
}

function riwayatKunjungan(norm, nama) {
    $("#nama_pasien").text(nama);
    $("#no_rm").text(norm);
    Swal.fire({
        icon: "info",
        title: "Sedang mencarikan data...!!! \n Pencarian dapat membutuhkan waktu lama, \n Mohon ditunggu...!!!",
        showConfirmButton: false,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });
    // console.log("🚀 ~ riwayatKunjungan ~ norm:", norm);
    $.ajax({
        url: "/api/kominfo/kunjungan/riwayat",
        type: "POST",
        data: { no_rm: norm },
        success: function (response) {
            Swal.close();
            // console.log("🚀 ~ riwayatKunjungan ~ response:", response);
            tabelRiwayatKunjungan(response); // Menampilkan tabel
            $("#historiKunjungan").modal("show"); // Menampilkan modal
            // $("#historiKunjungan").on("shown.bs.tab", function () {
            setTimeout(() => {
                $("#riwayatKunjungan").DataTable().columns.adjust().draw();
            }, 50); // delay kecil agar render sempat selesai
            // });
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
    data.forEach((item, index) => {
        item.no = index + 1; // Nomor urut dimulai dari 1

        item.antrean = `
            <div>
                <p>${item.antrean_nomor} </p>                                    
                <p>${item.penjamin_nama}</p>                                    
                <p>${item.dokter_nama}</p>
            </div>`;

        item.diagnosa = `
            <div>
                <p><strong>DX 1 :</strong> ${item.dx1 || "-"}</p>
                <p><strong>DX 2 :</strong> ${item.dx2 || "-"}</p>
                <p><strong>DX 3 :</strong> ${item.dx3 || "-"}</p>
            </div>`;

        item.anamnesa = `
            <div>
                <p><strong>DS :</strong> ${item.ds || "-"}</p>
                <p><strong>DO :</strong> ${item.do || "-"}</p>
                <table>
                    <tr>
                        <td><strong>TD :</strong> ${item.td || "-"} mmHg</td>
                        <td><strong>Nadi :</strong> ${
                            item.nadi || "-"
                        } X/mnt</td>
                    </tr>
                    <tr>
                        <td><strong>BB :</strong> ${item.bb || "-"} Kg</td>
                        <td><strong>Suhu :</strong> ${item.suhu || "-"} °C</td>
                    </tr>
                    <tr>
                        <td><strong>RR :</strong> ${item.rr || "-"} X/mnt</td>
                    </tr>
                </table>
            </div>`;

        let identitas = `
            <div class="row">
                <div class="col-md-4 col-sm-6 col-12 mb-2">
                    <p><strong>NO RM:</strong> ${item.pasien_no_rm}</p>
                    <p><strong>Nama:</strong> ${item.pasien_nama}</p>
                </div>
                <div class="col-md-4 col-sm-6 col-12 mb-2">
                    <p><strong>Tgl Lahir:</strong> ${item.pasien_tgl_lahir}</p>
                    <p><strong>Umur:</strong> ${item.umur}</p>
                </div>
                <div class="col-md-4 col-sm-6 col-12 mb-2">
                    <p><strong>Kelamin:</strong> ${item.jenis_kelamin_nama}</p>
                    <p><strong>Alamat:</strong> ${item.alamat}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-6 col-12 mb-2">
                    <a type="button" class="btn btn-warning font-weight-bold mx-2" href="/RO/Hasil/${item.pasien_no_rm}" target="_blank">Lihat Hasil Penunjang</a>
                </div>
                <div class="col-md-4 col-sm-6 col-12 mb-2">
                    <a type="button" class="btn btn-danger font-weight-bold mx-2"  onclick="lihatIdentitas('${item.pasien_no_rm}')">Lihat Identitas</a>
                </div>                
            </div>`;

        $("#identitas").html(identitas);

        item.ro = generateAsktindString(item.radiologi);
        item.igd = generateAsktindString(item.tindakan, true);
        item.lab = generateAsktindString(item.laboratorium, false, true);
        // item.hasilLab = generateAsktindString(item.hasilLab, false, true);

        let obatHtml = `
            <div>
                <table border="1" style="width:100%; border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th>Nama Obat</th>
                            <th>Aturan</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>`;

        item.obat.forEach((obat) => {
            obat.resep_obat_detail.forEach((detail) => {
                let aturan = obat.aturan_pakai || "";
                obatHtml += `
                    <tr>
                        <td>${detail.nama_obat}</td>
                        <td>${obat.signa_1} X ${obat.signa_2} ${aturan}</td>
                        <td>${detail.jumlah_obat}</td>
                    </tr>`;
            });
        });

        obatHtml += `</tbody></table></div>`;
        item.dataObats = obatHtml;

        item.rincian = `
                <div class="mb-2">
                    <p><strong>DS :</strong> ${item.ds || "-"}</p>
                    <p><strong>DO :</strong> ${item.do || "-"}</p>
                    <p><span><strong>TD:</strong> ${
                        item.td || "-"
                    } mmHg, </span>
                    <span><strong>Nadi:</strong> ${
                        item.nadi || "-"
                    } X/mnt, </span>
                    <span><strong>BB:</strong> ${item.bb || "-"} Kg, </span>
                    <span><strong>Suhu:</strong> ${item.suhu || "-"} °C, </span>
                    <span><strong>RR:</strong> ${
                        item.rr || "-"
                    } X/mnt </span></p>
                </div>
                <div class="mb-2" >
                    <p><strong>DX 1 :</strong> ${item.dx1 || "-"}</p>
                    <p><strong>DX 2 :</strong> ${item.dx2 || "-"}</p>
                    <p><strong>DX 3 :</strong> ${item.dx3 || "-"}</p>
                </div>
                <p class="mb-2"><strong>Radiologi :</strong> ${
                    item.ro || "Tidak Ada Pemeriksaan RO"
                }</p>
                <p class="mb-2"><strong>Tindakan :</strong> ${
                    item.igd || "Tidak Ada Tidankan"
                }</p>
                <p class="mb-2"><strong>Laboratorium :</strong> ${
                    item.hasilLab || ""
                }</p>
                <p class="mb-2"><strong>Resep Obat :</strong> ${
                    item.dataObats || "Tidak Ada Resep Obat"
                }</p>
                <p class="mb-2"> <strong> Status Pulang: </strong> ${
                    item.status_pasien_pulang + ", " || ""
                }  ${item.ket_status_pasien_pulang || "-"}</p > `;
    });

    // Hancurkan DataTable sebelumnya jika ada
    if ($.fn.DataTable.isDataTable("#riwayatKunjungan")) {
        $("#riwayatKunjungan").DataTable().destroy();
    }

    // Inisialisasi DataTable baru
    $("#riwayatKunjungan").DataTable({
        data: data,
        columns: [
            {
                data: "antrean",
                className: "text-wrap",
                title: "Pendaftaran",
                width: "25%",
            },
            {
                data: "tanggal",
                className: "text-center",
                title: "Tanggal",
                width: "10%",
            },
            {
                data: "rincian",
                className: "text-wrap",
                title: "SOAP",
            },
        ],
        paging: true,
        order: [[1, "desc"]], // Mengurutkan berdasarkan tanggal
        lengthMenu: [
            [5, 10, 25, 50, -1],
            [5, 10, 25, 50, "All"],
        ],
        pageLength: 3,
        responsive: true,
        autoWidth: false,
        scrollX: true,
    });
}

function cariLog(id, norm, tgl) {
    // console.log("🚀 ~ cariLog ~ id:", id);
    Swal.fire({
        title: "Loading...",
        didOpen: () => {
            Swal.showLoading();
        },
    });
    $.ajax({
        url: "/api/kominfo/antrian/log",
        method: "POST",
        data: { id: id },
        success: function (response) {
            // console.log("🚀 ~ cariLog ~ response:", response);
            //tangani jika 404 kirim swal
            if (response.status === 404) {
                Swal.fire({
                    icon: "error",
                    title: "Data Tidak Ditemukan",
                });
                return;
            }

            // Format data ke HTML
            let formattedResponse = `
                                        <div>
                                            <p><strong>Ruang:</strong> ${
                                                response.ruang_nama
                                            }</p>
                                            <p><strong>Keterangan:</strong> ${
                                                response.keterangan
                                            }</p>
                                            <p><strong>Waktu:</strong> ${
                                                response.created_at
                                            }</p>
                                            <p><strong>Pengirim:</strong> ${
                                                response.created_by ?? "-"
                                            }</p>
                                            <a href="https://kkpm.banyumaskab.go.id/administrator/loket_farmasi/cetak_resep?pendaftaran_id=${id}"
                                            target="_blank" class="btn btn-success" style="margin-top: 10px;">
                                                Cetak Resep
                                            </a>
                                            <a href="/api/resep2/${norm}/${tgl}"
                                            target="_blank" class="btn btn-primary" style="margin-top: 10px;">
                                                Cetak Resep BPJS
                                            </a>
                                        </div>
                                    `;

            // Tampilkan di SweetAlert
            Swal.fire({
                icon: "info",
                title: "Posisi Antrian",
                html: formattedResponse,
            });
        },
    });
}
function celuk(button, ruang) {
    const nama = $(button).data("nama");
    const alamat = $(button).data("alamatpang");
    const umur = $(button).data("umurpang");
    const jk = $(button).data("jk");
    var sebutan = "";
    if (umur <= 14) {
        sebutan = "Anak ";
    } else if (umur > 14 && umur <= 30) {
        if (jk == "L") {
            sebutan = "Saudara ";
        } else {
            sebutan = "Nona ";
        }
    } else if (umur > 30) {
        if (jk == "L") {
            sebutan = "Bapak ";
        } else {
            sebutan = "Ibu ";
        }
    }
    const text = `${sebutan} ${nama} dari ${alamat}, silahkan menuju ke loket ${ruang}`;
    // console.log("🚀 ~ celuk ~ text:", text);
    if (ruang === "farmasi") {
        pasienPulang(button, text);
    } else {
        panggil(text);
    }
}

function pasienPulang(button, text) {
    // console.log("🚀 ~ pasienPulang ~ pasienPulang:", pasienPulang);
    var norm = $(button).data("norm");
    var notrans = $(button).data("notrans");
    var tgltrans = $(button).data("tgltrans");
    try {
        $.ajax({
            url: "api/pendaftaran/pulang",
            method: "POST",
            data: {
                norm: norm,
                notrans: notrans,
                tgltrans: tgltrans,
            },
            success: function (response) {
                // console.log("🚀 ~ pasienPulang ~ response:", response);
                //jika respon 500 swal fire eror
                Swal.fire({
                    icon: "success",
                    title: response.message,
                    confirmButtonText: "OK",
                }).then((result) => {
                    if (result.isConfirmed) {
                        antrianAll("farmasi");
                        panggil(text);
                    }
                });
            },
            error: function (xhr) {
                // console.log("🚀 ~ pasienPulang ~ xhr:", xhr);
                let response = xhr.responseJSON || {
                    message: "Terjadi kesalahan.",
                    status: 500,
                };
                Swal.fire({
                    icon: "error",
                    title: response.message,
                });
            },
        });
    } catch (error) {
        // console.log("🚀 ~ pasienPulang ~ error:", error);
        Swal.fire({
            icon: "error",
            title: error,
        });
    }
}

function setTransaksi(button, ruang) {
    // console.log("🚀 ~ setTransaksi ~ setTransaksi:", setTransaksi);
    var norm = $(button).data("norm");
    var nama = $(button).data("nama");
    var dokter = $(button).data("kddokter");
    // console.log("🚀 ~ setTransaksi ~ dokter:", dokter);
    var alamat = $(button).data("alamat");
    var layanan = $(button).data("layanan");
    var notrans = $(button).data("notrans");
    var tgltrans = $(button).data("tgltrans");
    var tgl = $(button).data("tgltrans");
    var asktind = $(button).data("asktind");
    var tujuan = $(button).data("tujuan");
    var umur = $(button).data("umur");
    var tglLahir = $(button).data("tgllahir");
    var nik = $(button).data("nik");
    var cekRo = $(button).data("ro");
    var cekIgd = $(button).data("igd");
    var cekLab = $(button).data("lab");
    jk = $(button).data("jk");
    // console.log("🚀 ~ setTransaksi ~ jk:", jk);
    switch (ruang) {
        case "igd":
            cariTsIgd(notrans, norm, tgl, ruang);
            break;
        case "lab":
            cariTsLab(norm, tgl, ruang);
            $("#umur").val(umur);
            getNoSampel();
            // if (cekIgd == true) {
            //     cekTransLain(notrans);
            // }

            break;
        case "ro":
            if ($.fn.DataTable.isDataTable("#tableRo")) {
                var tabel = $("#tableRo").DataTable();
                tabel.clear().destroy();
            }
            $("#tglRo").val(tgltrans);
            cariTsRo(norm, tgl, ruang);
            if (cekIgd == true) {
                cekTransLain(notrans);
            }
            break;
        case "dots":
            notrans = $(button).data("no_reg");
            cariPasienTb(norm, tgl, ruang);
            break;
        case "surat":
            $("#umur").val(umur);
            $("#tglLahir").val(tglLahir);
            $("#alamat").val(alamat);
            $("#nik").val(nik);
            $("#tglTrans").val(tgltrans);
            $("#modalCreateSurat").modal("show");
            break;
        default:
            $("#igd").hide();
            $("#lab").hide();
            $("#ro").hide();
    }

    $("#norm").val(norm);
    $("#nama").val(nama);
    $("#jk").val(jk);
    $("#dokter").val(dokter);
    $("#dokter").trigger("change");
    $("#alamat").val(alamat);
    $("#layanan").val(layanan);
    $("#notrans").val(notrans);
    $("#tgltrans").val(tgltrans);
    $("#tgltind").val(tgl);
    $("#asktind").val(asktind);
    // $("#permintaan").html(`<b>${asktind}</b>`);
    if (ruang == "ro") {
        $("#permintaan").html(`<b>${asktind}</b>`);
        $("#tujuanLain").html(
            `<div class="font-weight-bold bg-warning rounded">${tujuan}</div>`
        );
    } else {
        $("#permintaan").html(`<b>${asktind}</b>
        <br>
        <br>
        <br>
        <br>
        <br>
        <div class="font-weight-bold bg-warning rounded">${tujuan}</div>`);
    }
    scrollToInputSection();
}

let msgSelesai;
async function cekTransLain(notrans) {
    try {
        await fetch(`/api/cariDataTindakan`, {
            method: "POST",
            body: JSON.stringify({ notrans: notrans }),
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        }).then((response) => {
            response.json(console.log("🚀 ~ response:", response));
            if (response.status == 404) {
                msgSelesai =
                    "Belum ada transaksi IGD, silahkan di arahkan ke IGD";
            } else {
                msgSelesai =
                    "Transaksi IGD sudah, silahkan di arahkan menunggu hasil";
            }
            // console.log("🚀 ~ msgSelesai:", msgSelesai);
        });
    } catch (error) {
        // console.log("error++", error);
    }
}

function antrianAll(ruang) {
    $("#loadingSpinner").show();
    const tanggal =
        $("#tanggal").val() || new Date().toISOString().slice(0, 10);

    fetchDataAntrianAll(tanggal, ruang, function (response) {
        $("#loadingSpinner").hide();
        const {
            data,
            dataSelesai,
            daftarTunggu,
            daftarTungguUmum,
            daftarTungguBpjs,
        } = processResponse(response, ruang, "Sudah Selesai");
        // console.log("🚀 ~ daftarTunggu:", daftarTunggu);
        // console.log("🚀 ~ dataSelesai:", dataSelesai);
        // console.log("🚀 ~ data:", data);

        if (ruang === "kasir") {
            initializeDataTable(
                "#dataSelesai",
                dataSelesai,
                getColumnDefinitions("status", ruang),
                ruang
            );
            initializeDataTable(
                "#antrianall",
                data,
                getColumnDefinitions("status_pulang")
            );
            initializeDataTable(
                "#dataAntrian",
                daftarTungguUmum,
                getColumnDefinitions("status", ruang),
                ruang
            );
            initializeDataTable(
                "#dataAntrianBpjs",
                daftarTungguBpjs,
                getColumnDefinitions("status", ruang),
                ruang
            );
            // initializeDataTable(
            //     "#dataTunggu",
            //     daftarTunggu,
            //     getColumnDefinitions("status", ruang),
            //     ruang
            // );
        } else if (ruang === "farmasi") {
            initializeDataTable(
                "#antrianall",
                dataSelesai,
                getColumnDefinitions("status", ruang),
                ruang
            );
        } else if (ruang === "surat") {
            initializeDataTable(
                "#antrianall",
                dataSelesai,
                getColumnDefinitions("status", ruang),
                ruang
            );
        } else {
            initializeDataTable(
                "#antrianall",
                data,
                getColumnDefinitions("status_pulang", ruang),
                ruang
            );
        }
    });
}

function cariKominfo(norm, tgl, ruang) {
    const normValue = (norm || $("#norm").val()).padStart(6, "0");
    tgl =
        ruang === "ro" ? tgl || $("#tglRO").val() : tgl || $("#tanggal").val();

    if (isNaN(normValue) || !normValue) {
        Swal.fire({ icon: "error", title: "No Rm Tidak Valid...!!!" });
    } else {
        Swal.fire({
            icon: "info",
            title: "Sedang Mencari Data Pasien di Aplikasi KOMINFO\n Mohon Ditunggu ...!!!",
            didOpen: () => Swal.showLoading(),
        });

        $.ajax({
            url: "/api/dataPasien",
            method: "POST",
            data: { no_rm: normValue, tanggal: tgl },
            dataType: "json",
            success: function (response) {
                if (response.error) {
                    Swal.fire({
                        icon: "error",
                        title: `Unexpected metadata code: ${response.error}\n Silahkan Coba Lagi`,
                    });
                } else if (response.metadata) {
                    handleMetadata(response, ruang);
                }
            },
            error: function (error) {
                Swal.fire({
                    icon: "error",
                    title: `Error fetching data: ${error}`,
                });
            },
        });
    }
}

function handleMetadata(response, ruang) {
    console.log("🚀 ~ handleMetadata ~ response:", response);
    const code = response.metadata.code;
    const message = response.metadata.message;

    if (code === 404 || code === 204) {
        Swal.fire({ icon: "info", title: message });
        if (code === 204 && ruang !== "lab") rstForm();
    } else if (code === 200) {
        Swal.fire({
            icon: "info",
            title: message,
            showConfirmButton: false,
            allowOutsideClick: false,
        });
        const pasien = response.response.pasien;
        const cppt = response.response.cppt ? response.response.cppt[0] : [];
        // console.log("🚀 ~ handleMetadata ~ cppt:", cppt);
        const pendaftaran = response.response.pendaftaran[0];
        console.log("🚀 ~ handleMetadata ~ pendaftaran:", pendaftaran.no_reg);
        const dxMed = cppt.diagnosa[0];
        // console.log("🚀 ~ handleMetadata ~ dxMed:", dxMed);

        switch (ruang) {
            case "igd":
                handleIgd(cppt, pasien, pendaftaran);
                dataTindakan(pendaftaran.no_reg);
                break;
            case "farmasi":
                dataFarmasi();
                dataTindakan(pendaftaran.no_reg);
                isiIdentitas(pasien, pendaftaran);
                break;
            case "ro":
                handleRo(cppt, pasien, pendaftaran);
                break;
            case "dots":
                isiBiodataModal(norm, tgl, pasien, pendaftaran);
                break;
            case "lab":
                handleLab(cppt, pasien, pendaftaran);
                break;
            case "gizi":
                isiIdentitas(pasien, pendaftaran, dxMed);
                break;
            default:
                Swal.fire({
                    icon: "error",
                    title: "Unexpected ruang: " + ruang,
                });
        }
    } else {
        Swal.fire({
            icon: "error",
            title: "Unexpected metadata code: " + code,
        });
    }
}

function handleIgd(cppt, pasien, pendaftaran) {
    const pendaftaran_id = cppt.pendaftaran_id;
    console.log("🚀 ~ handleIgd ~ pendaftaran_id:", pendaftaran_id);
    let permintaan =
        cppt.tindakan
            ?.map(
                (tindakan) =>
                    `${tindakan.nama_tindakan} : ${tindakan.nama_obat}`
            )
            .join(",<br>") || "kosong";
    if (permintaan === "kosong") {
        getTindakanKominfo(pendaftaran_id, function (permintaan) {
            console.log("🚀 ~ handleIgd ~ permintaan:", permintaan);
            isiIdentitas(pasien, pendaftaran, permintaan);
        });
    } else {
        isiIdentitas(pasien, pendaftaran, permintaan);
    }
}

function getTindakanKominfo(pendaftaran_id, callback) {
    $.ajax({
        url: "/api/kominfo/get_data_tindakan/" + pendaftaran_id,
        method: "GET",
        dataType: "json",
        success: function (response) {
            const permintaan =
                response
                    ?.map(
                        (item) =>
                            `${item.nama_tindakan} : ${item.nama_obat || "-"}`
                    )
                    .join(",<br>") || "-";

            callback(permintaan);
        },
        error: function (error) {
            console.log("🚀 ~ getPermintaanKominfo ~ error:", error);
            callback("-");
        },
    });
}

function handleRo(cppt, pasien, pendaftaran) {
    const permintaan =
        cppt.radiologi
            ?.map(
                (radiologi) => `${radiologi.layanan} (${radiologi.keterangan})`
            )
            .join(",<br>") || "";
    isiIdentitas(pasien, pendaftaran, permintaan);
}

function handleLab(cppt, pasien, pendaftaran) {
    const permintaan =
        cppt.laboratorium
            ?.map(
                (lab, index) =>
                    `${lab.layanan} (${lab.keterangan})${
                        (index + 1) % 2 === 0 ? ",<br>" : ", "
                    }`
            )
            .join("")
            .replace(/, $|,<br>$/, "") || "";
    isiIdentitas(pasien, pendaftaran, permintaan);
}

function isiIdentitas(pasien, pendaftaran, permintaan) {
    // console.log("🚀 ~ isiIdentitas ~ pendaftaran:", pendaftaran);
    // console.log("🚀 ~ isiIdentitas ~ pasien:", pasien);
    // console.log("🚀 ~ isiIdentitas ~ permintaan:", permintaan);

    // Set values for input fields
    $("#layanan").val(pendaftaran.penjamin_nama); // Trigger change event if needed
    $("#norm").val(pasien.pasien_no_rm);
    $("#nama").val(pasien.pasien_nama);
    $("#alamat").val(pasien.pasien_alamat);
    $("#notrans").val(pendaftaran.no_reg);
    $("#dokter").val(pendaftaran.nip_dokter).trigger("change");

    // Handle specific fields for different sections
    setOptionalFields(pasien, pendaftaran, permintaan);

    // Display permintaan
    $("#permintaan").html(`<b>${permintaan}</b>`);

    // Convert today's date to "en-CA" format
    const tanggalHariIni = new Date().toLocaleDateString("en-CA");

    // Format the registration date
    const tglDaftar = formatDate(pendaftaran.tanggal);

    // Show warning if registration date is not today's date
    if (pendaftaran.tanggal !== tanggalHariIni) {
        showWarning(pasien.pasien_nama, tglDaftar);
    } else {
        closeSwalAfterDelay();
    }
}

function setOptionalFields(pasien, pendaftaran, permintaan) {
    // For RO
    if ($("#jk").length) {
        $("#jk").val(pasien.jenis_kelamin_nama);
    }

    // For Gizi
    if ($("#tglLahir").length) {
        $("#tglLahir").val(pasien.pasien_tgl_lahir);
    }

    const gender =
        pasien.jenis_kelamin_nama === "L"
            ? "male"
            : pasien.jenis_kelamin_nama === "P"
            ? "female"
            : null;

    if ($("#gender").length) {
        $("#gender").val(gender);
    }

    if ($("#age").length) {
        $("#age").val(pendaftaran.pasien_umur_tahun);
        if (permintaan.nama_diagnosa) {
            $("#nama_diagnosa").html(
                `<b>Diagnosa Medis: ${permintaan.nama_diagnosa}</b>`
            );
        }
    }
}

function formatDate(dateString) {
    return dateString.split("-").reverse().join("-");
}

function showWarning(pasienNama, tglDaftar) {
    Swal.fire({
        icon: "warning",
        title: `Pasien atas nama ${pasienNama} adalah pasien tanggal ${tglDaftar}\n Jangan Lupa Mengganti Tanggal Transaksi...!!`,
    });
    scrollToInputSection();
}

function closeSwalAfterDelay() {
    setTimeout(() => {
        Swal.close();
        scrollToInputSection();
    }, 1000);
}

async function searchRMObat(norm) {
    Swal.fire({
        icon: "success",
        title: "Sedang mencarikan data pasien...!!!",
        showConfirmButton: false,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });
    // var norm = "000001";
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
                showConfirmButton: false,
                allowOutsideClick: false,
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
            setTimeout(Swal.close, 1000);
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

//KASIR
function populateLayananOptions(kelas) {
    var LayananSelectElement = $("#jenislayanan");
    LayananSelectElement.empty();
    $.get("/api/layanan", { kelas: kelas }, function (data) {
        // console.log(data);
        data.sort(function (a, b) {
            var namaA = a.nmLayanan.toUpperCase();
            var namaB = b.nmLayanan.toUpperCase();
            if (namaA < namaB) {
                return -1;
            }
            if (namaA > namaB) {
                return 1;
            }
            return 0;
        });
        data.forEach(function (layanan) {
            var option = new Option(
                layanan.nmLayanan,
                layanan.idLayanan,
                false,
                false
            );
            LayananSelectElement.append(option).trigger("change");
            // console.log(
            //     "Options added to select element:",
            //     LayananSelectElement.html()
            // );
        });
    });
}

function populateJaminan() {
    var jaminan = $("#jaminan");

    $.get("/api/jaminan", function (data) {
        data.sort(function (a, b) {
            // Add your sorting logic here if needed
            // Example: return a.name.localeCompare(b.name);
        });

        data.forEach(function (jaminanData) {
            var kelompok = jaminanData.kelompok;
            var kode = jaminanData.kkelompok;

            // Creating option elements
            var option = new Option(kelompok, kode, false, false);

            // Appending options to both jaminan and dokterModals
            jaminan.append(option).trigger("change");
        });
    });
}
function populateTujuan() {
    var tujuanSelectElement = $("#tujuan");
    $.get("/api/tujuan", function (data) {
        data.sort(function (a, b) {
            var namaLengkapA = a.tujuan;
            var namaLengkapB = b.tujuan;
            return namaLengkapA.localeCompare(namaLengkapB, undefined, {
                numeric: true,
                sensitivity: "base",
            });
        });
        data.forEach(function (tujuan) {
            var nmTujuan = tujuan.tujuan;
            var kode = tujuan.kd_tujuan.toString();
            var option = new Option(nmTujuan, kode, false, false);
            tujuanSelectElement.append(option).trigger("change");
        });
    });
}

function populateAnalisHasil() {
    var analisDarah = $("#darah");
    var analisBakteri = $("#bakteri");
    var analisImuno = $("#imuno");
    var analisSampling = $("#sampling");
    var analisAdmin = $("#admin");

    $.get("/api/analis", function (data) {
        data.sort(function (a, b) {
            var namaLengkapA = a.gelar_d + " " + a.nama + " " + a.gelar_b;
            var namaLengkapB = b.gelar_d + " " + b.nama + " " + b.gelar_b;
            return namaLengkapA.localeCompare(namaLengkapB, undefined, {
                numeric: true,
                sensitivity: "base",
            });
        });

        data.forEach(function (petugas) {
            var namaLengkap =
                petugas.gelar_d + " " + petugas.nama + " " + petugas.gelar_b;

            // Create separate Option instances for each select element
            var optionDarah = new Option(
                namaLengkap,
                petugas.nip,
                false,
                false
            );
            var optionBakteri = new Option(
                namaLengkap,
                petugas.nip,
                false,
                false
            );
            var optionImuno = new Option(
                namaLengkap,
                petugas.nip,
                false,
                false
            );
            var optionSampling = new Option(
                namaLengkap,
                petugas.nip,
                false,
                false
            );
            var optionAdmin = new Option(
                namaLengkap,
                petugas.nip,
                false,
                false
            );

            // Append options to the respective select elements
            analisDarah.append(optionDarah).trigger("change");
            analisBakteri.append(optionBakteri).trigger("change");
            analisImuno.append(optionImuno).trigger("change");
            analisAdmin.append(optionAdmin).trigger("change");
            analisSampling.append(optionSampling).trigger("change");
        });
    });
}

//format numbering
function formatNumberWithCommas(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
function formatNumbernoCommas(number) {
    return number.toString().replace(/,/g, ""); // Menghapus pemisah ribuan
}

//FARMASI
function populateObatOptions() {
    var obatSelectElement = $("#obat");
    var hargaBeliElement = $("#beli");
    var hargaJualElement = $("#jual");
    var productID = $("#productID");
    obatSelectElement.empty();
    var placeholderOption = new Option(
        "--- Pilih Nama Obat ---",
        "",
        true,
        true
    );
    obatSelectElement.append(placeholderOption).trigger("change");
    $.get("/api/obat", function (data) {
        data.sort(function (a, b) {
            var namaA = a.nmObat.toUpperCase();
            var namaB = b.nmObat.toUpperCase();
            if (namaA < namaB) {
                return -1;
            }
            if (namaA > namaB) {
                return 1;
            }
            return 0;
        });

        data.forEach(function (obat) {
            var obatStok =
                obat.nmObat +
                " (Supplier: " +
                obat.supplier.nmSupplier +
                ")" +
                " (Tgl ED: " +
                obat.ed +
                ")" +
                " (Sisa Stok: " +
                obat.sisa +
                ")";
            var option = new Option(obatStok, obat.id, false, false);
            // var option = new Option(obat.nmObat, obat.product_id, false, false);
            obatSelectElement.append(option).trigger("change");
        });

        // Event listener untuk mengisi harga beli dan harga jual saat obat dipilih
        obatSelectElement.on("change", function () {
            var selectedObatId = $(this).val();
            var selectedObat = data.find(function (obat) {
                return obat.id == selectedObatId;
            });

            if (selectedObat) {
                // Mengisi nilai harga beli dan harga jual jika obat ditemukan
                hargaBeliElement.val(
                    selectedObat.hargaBeli
                        ? selectedObat.hargaBeli.toLocaleString(undefined)
                        : ""
                );
                hargaJualElement.val(
                    selectedObat.hargaJual
                        ? selectedObat.hargaJual.toLocaleString(undefined)
                        : ""
                );
                productID.val(
                    selectedObat.product_id
                        ? selectedObat.product_id.toLocaleString()
                        : ""
                );
                $("#qty").focus();
            } else {
                // Mengosongkan nilai harga beli dan harga jual jika obat tidak ditemukan
                hargaBeliElement.val("");
                hargaJualElement.val("");
            }
        });
    });
}
function populateGudangObatOptions() {
    var obatSelectElement = $("#gObat");
    var idGudang = $("#idGudang");
    var productID = $("#productID");
    var idObat = $("#idObat");
    var nmObat = $("#nmObat");
    var stok = $("#stokBaru");
    var beli = $("#hargaBeli");
    var jual = $("#hargaJual");
    var jenis = $("#jenis");
    var sediaan = $("#sediaan");
    var sumber = $("#sumberObat");
    var pabrikan = $("#pabrikan");
    var suplayer = $("#supplier");
    var tglEd = $("#tglED");
    var tglBeli = $("#tglBeli");
    var sisaStok = $("#sisaStok");
    obatSelectElement.empty();
    var placeholderOption = new Option(
        "--- Pilih Nama Obat ---",
        "",
        true,
        true
    );
    obatSelectElement.append(placeholderOption).trigger("change");
    $.get("/api/daftarInObatGudang", function (data) {
        data.sort(function (a, b) {
            var namaA = a.nmObat.toUpperCase();
            var namaB = b.nmObat.toUpperCase();
            if (namaA < namaB) {
                return -1;
            }
            if (namaA > namaB) {
                return 1;
            }
            return 0;
        });

        data.forEach(function (obat) {
            var obatStok =
                obat.nmObat +
                " (Supplier: " +
                obat.supplier.nmSupplier +
                ")" +
                " (Tgl ED: " +
                obat.ed +
                ")" +
                " (Sisa Stok: " +
                obat.sisa +
                ")";
            var option = new Option(obatStok, obat.id, false, false);
            // var option = new Option(obat.nmObat, obat.product_id, false, false);
            obatSelectElement.append(option).trigger("change");
        });

        // Event listener untuk mengisi harga beli dan harga jual saat obat dipilih
        obatSelectElement.on("change", function () {
            stok.focus();
            var selectedObatId = $(this).val();
            var selectedObat = data.find(function (obat) {
                return obat.id == selectedObatId;
            });

            if (selectedObat) {
                // Mengisi nilai harga beli dan harga jual jika obat ditemukan
                idGudang.val(
                    selectedObat.id ? selectedObat.id.toLocaleString() : ""
                );
                productID.val(
                    selectedObat.product_id
                        ? selectedObat.product_id.toLocaleString()
                        : ""
                );
                idObat.val(
                    selectedObat.idObat
                        ? selectedObat.idObat.toLocaleString()
                        : ""
                );
                nmObat.val(
                    selectedObat.nmObat
                        ? selectedObat.nmObat.toLocaleString()
                        : ""
                );
                beli.val(
                    selectedObat.hargaBeli
                        ? selectedObat.hargaBeli.toLocaleString()
                        : ""
                );
                jual.val(
                    selectedObat.hargaJual
                        ? selectedObat.hargaJual.toLocaleString()
                        : ""
                );
                sumber.val(
                    selectedObat.sumber
                        ? selectedObat.sumber.toLocaleString()
                        : ""
                );
                suplayer.val(
                    selectedObat.supplier.id
                        ? selectedObat.supplier.id.toLocaleString()
                        : ""
                );
                pabrikan.val(
                    selectedObat.pabrikan.pabrikan
                        ? selectedObat.pabrikan.pabrikan.toLocaleString()
                        : ""
                );
                jenis.val(
                    selectedObat.jenis
                        ? selectedObat.jenis.toLocaleString()
                        : ""
                );
                sediaan.val(selectedObat.sediaan || "");
                tglEd.val(
                    selectedObat.ed ? selectedObat.ed.toLocaleString() : ""
                );
                tglBeli.val(
                    selectedObat.tglPembelian
                        ? selectedObat.tglPembelian.toLocaleString()
                        : ""
                );
                sisaStok.val(selectedObat.sisa || "");

                suplayer.change();
                pabrikan.change();
                idObat.change();
                jenis.trigger("change");
                sediaan.trigger("change");
                sumber.trigger("change");
            } else {
                // console.log("id tidak ada");
            }
        });
    });
}
function populateNamaObatOptions() {
    var namaObatEl = $("#idObat");
    var nmObat = $("#nmObat");
    namaObatEl.empty();
    var placeholderOption = new Option(
        "--- Pilih Nama Obat ---",
        "",
        true,
        true
    );
    namaObatEl.append(placeholderOption).trigger("change");

    $.get("/api/namaObat", function (data) {
        data.sort(function (a, b) {
            var namaA = a.nmObat.toUpperCase();
            var namaB = b.nmObat.toUpperCase();
            if (namaA < namaB) {
                return -1;
            }
            if (namaA > namaB) {
                return 1;
            }
            return 0;
        });
        data.forEach(function (obat) {
            var option = new Option(obat.nmObat, obat.idObat, false, false);
            namaObatEl.append(option).trigger("change");
        });

        namaObatEl.on("change", function () {
            var selectedObatId = $(this).val();
            var selectedObat = data.find(function (obat) {
                return obat.idObat == selectedObatId;
            });

            $("#StokBaru").focus();
            if (selectedObat) {
                // Mengisi nilai harga beli dan harga jual jika obat ditemukan
                nmObat.val(
                    selectedObat.nmObat
                        ? selectedObat.nmObat.toLocaleString(undefined)
                        : ""
                );
            } else {
                namaObatEl.val("");
            }
        });
    });
}
function populatePabrikanOptions() {
    var pabrikanElement = $("#pabrikan");
    $.get("/api/pabrikan", function (data) {
        data.sort(function (a, b) {
            var namaA = a.nmPabrikan.toUpperCase();
            var namaB = b.nmPabrikan.toUpperCase();
            if (namaA < namaB) {
                return -1;
            }
            if (namaA > namaB) {
                return 1;
            }
            return 0;
        });

        data.forEach(function (pabrikan) {
            var option = new Option(
                pabrikan.nmPabrikan,
                pabrikan.pabrikan,
                false,
                false
            );
            pabrikanElement.append(option).trigger("change");
        });
    });
}
function populateSupplierOptions() {
    var supplierElement = $("#supplier");
    $.get("/api/supplier", function (data) {
        data.sort(function (a, b) {
            var namaA = a.nmSupplier.toUpperCase();
            var namaB = b.nmSupplier.toUpperCase();
            if (namaA < namaB) {
                return -1;
            }
            if (namaA > namaB) {
                return 1;
            }
            return 0;
        });
        data.forEach(function (sup) {
            var option = new Option(sup.nmSupplier, sup.id, false, false);
            supplierElement.append(option).trigger("change");
        });
    });
}
//IGD
// function populateTindakanOptions() {
//     var tindakanSelectElement = $("#tindakan");
//     $.get("/api/jenistindakan", function (data) {
//         data.sort(function (a, b) {
//             var namaA = a.nmTindakan.toUpperCase();
//             var namaB = b.nmTindakan.toUpperCase();
//             if (namaA < namaB) {
//                 return -1;
//             }
//             if (namaA > namaB) {
//                 return 1;
//             }
//             return 0;
//         });
//         data.forEach(function (tindakan) {
//             var option = new Option(
//                 tindakan.nmTindakan,
//                 tindakan.kdTindakan,
//                 false,
//                 false
//             );
//             tindakanSelectElement.append(option).trigger("change");
//         });
//     });
// }
// function populateBmhpOptions() {
//     var obatSelectElement = $("#bmhp");
//     var hargaBeliElement = $("#beli");
//     var hargaJualElement = $("#jual");
//     var productID = $("#productID");
//     obatSelectElement.empty();
//     var placeholderOption = new Option(
//         "--- Pilih Nama Obat ---",
//         "",
//         true,
//         true
//     );
//     obatSelectElement.append(placeholderOption).trigger("change");
//     $.get("/api/bmhp", function (data) {
//         // data.sort(function (a, b) {
//         //     var namaA = a.nmObat.toUpperCase();
//         //     var namaB = b.nmObat.toUpperCase();
//         //     if (namaA < namaB) {
//         //         return -1;
//         //     }
//         //     if (namaA > namaB) {
//         //         return 1;
//         //     }
//         //     return 0;
//         // });

//         data.forEach(function (obat) {
//             var obatStok =
//                 obat.nmObat +
//                 " \u00A0\u00A0\u00A0\u00A0\u00A0\u00A0----(Stok: " +
//                 obat.sisa +
//                 ")----";
//             var option = new Option(obatStok, obat.id, false, false);
//             // var option = new Option(obat.nmObat, obat.product_id, false, false);
//             obatSelectElement.append(option).trigger("change");
//         });

//         // Event listener untuk mengisi harga beli dan harga jual saat obat dipilih
//         obatSelectElement.on("change", function () {
//             var selectedObatId = $(this).val();
//             var selectedObat = data.find(function (obat) {
//                 return obat.id == selectedObatId;
//             });

//             if (selectedObat) {
//                 // Mengisi nilai harga beli dan harga jual jika obat ditemukan
//                 hargaBeliElement.val(
//                     selectedObat.hargaBeli
//                         ? selectedObat.hargaBeli.toLocaleString(undefined)
//                         : ""
//                 );
//                 hargaJualElement.val(
//                     selectedObat.hargaJual
//                         ? selectedObat.hargaJual.toLocaleString(undefined)
//                         : ""
//                 );
//                 productID.val(
//                     selectedObat.product_id
//                         ? selectedObat.product_id.toLocaleString()
//                         : ""
//                 );
//                 document.querySelector("#qty").focus();
//             } else {
//                 // Mengosongkan nilai harga beli dan harga jual jika obat tidak ditemukan
//                 hargaBeliElement.val("");
//                 hargaJualElement.val("");
//             }
//             document.querySelector("#qty").focus();
//         });
//     });
// }

//SDM
// function populateDokterOptions() {
//     var dokterSelectElement = $("#dokter");
//     var dokterModals = $("#modal-pasienTB #modal-dokter");

//     // if (!dokterSelectElement.length || !dokterModals.length) {
//     //     console.error(
//     //         "One or both of the elements (dokterSelectElement, dokterModals) not found."
//     //     );
//     //     return;
//     // }

//     $.get("/api/dokter", function (data) {
//         if (!Array.isArray(data)) {
//             console.error("Invalid data format received from server.");
//             return;
//         }

//         data.sort((a, b) =>
//             (a.gelar_d + " " + a.nama + " " + a.gelar_b).localeCompare(
//                 b.gelar_d + " " + b.nama + " " + b.gelar_b,
//                 undefined,
//                 { numeric: true, sensitivity: "base" }
//             )
//         );

//         data.forEach((dokter) => {
//             var namaLengkap =
//                 dokter.gelar_d + " " + dokter.nama + " " + dokter.gelar_b;
//             var nip = dokter.nip.toString();

//             if (!dokterSelectElement.find(`option[value="${nip}"]`).length) {
//                 dokterSelectElement
//                     .append(new Option(namaLengkap, nip))
//                     .trigger("change");
//             }

//             if (!dokterModals.find(`option[value="${nip}"]`).length) {
//                 dokterModals
//                     .append(new Option(namaLengkap, nip))
//                     .trigger("change");
//             }
//         });
//     }).fail((xhr, status, error) => {
//         console.error("Error fetching data:", error);
//         // Tindakan yang sesuai untuk menangani kesalahan
//     });
// }

// function populatePetugasOptions() {
//     var petugasSelectElement = $("#petugas");
//     var petugasSelectModals = $("#modal-pasienTB #modal-petugas");

//     // if (!petugasSelectElement.length || !petugasSelectModals.length) {
//     //     console.error(
//     //         "One or both of the elements (petugasSelectElement, petugasSelectModals) not found."
//     //     );
//     //     return;
//     // }

//     $.get("/api/perawat", function (data) {
//         if (!Array.isArray(data)) {
//             console.error("Invalid data format received from server.");
//             return;
//         }

//         data.sort((a, b) =>
//             (a.gelar_d + " " + a.nama + " " + a.gelar_b).localeCompare(
//                 b.gelar_d + " " + b.nama + " " + b.gelar_b,
//                 undefined,
//                 { numeric: true, sensitivity: "base" }
//             )
//         );

//         data.forEach((petugas) => {
//             var namaLengkap =
//                 petugas.gelar_d + " " + petugas.nama + " " + petugas.gelar_b;

//             if (
//                 !petugasSelectElement.find(`option[value="${petugas.nip}"]`)
//                     .length
//             ) {
//                 petugasSelectElement
//                     .append(new Option(namaLengkap, petugas.nip))
//                     .trigger("change");
//             }

//             if (
//                 !petugasSelectModals.find(`option[value="${petugas.nip}"]`)
//                     .length
//             ) {
//                 petugasSelectModals
//                     .append(new Option(namaLengkap, petugas.nip))
//                     .trigger("change");
//             }
//         });
//     }).fail((xhr, status, error) => {
//         console.error("Error fetching data:", error);
//         // Tindakan yang sesuai untuk menangani kesalahan
//     });
// }

// function populateRadiograferOptions() {
//     var petugasSelectElement = $("#p_rontgen");
//     $.get("/api/radiografer", function (data) {
//         data.sort(function (a, b) {
//             var namaLengkapA = a.gelar_d + " " + a.nama + " " + a.gelar_b;
//             var namaLengkapB = b.gelar_d + " " + b.nama + " " + b.gelar_b;
//             return namaLengkapA.localeCompare(namaLengkapB, undefined, {
//                 numeric: true,
//                 sensitivity: "base",
//             });
//         });
//         data.forEach(function (petugas) {
//             var namaLengkap =
//                 petugas.gelar_d + " " + petugas.nama + " " + petugas.gelar_b;
//             var option = new Option(namaLengkap, petugas.nip, false, false);
//             petugasSelectElement.append(option).trigger("change");
//         });
//     });
// }
// function populateApotekerOptions() {
//     var petugasSelectElement = $("#apoteker");
//     $.get("/api/apoteker", function (data) {
//         data.sort(function (a, b) {
//             var namaLengkapA = a.gelar_d + " " + a.nama + " " + a.gelar_b;
//             var namaLengkapB = b.gelar_d + " " + b.nama + " " + b.gelar_b;
//             return namaLengkapA.localeCompare(namaLengkapB, undefined, {
//                 numeric: true,
//                 sensitivity: "base",
//             });
//         });
//         data.forEach(function (petugas) {
//             var namaLengkap =
//                 petugas.gelar_d + " " + petugas.nama + " " + petugas.gelar_b;
//             var option = new Option(namaLengkap, petugas.nip, false, false);
//             petugasSelectElement.append(option).trigger("change");
//         });
//     });
// }

// function populateAnalisOptions() {
//     var petugasSelectElement = $("#analis");
//     $.get("/api/analis", function (data) {
//         data.sort(function (a, b) {
//             var namaLengkapA = a.gelar_d + " " + a.nama + " " + a.gelar_b;
//             var namaLengkapB = b.gelar_d + " " + b.nama + " " + b.gelar_b;
//             return namaLengkapA.localeCompare(namaLengkapB, undefined, {
//                 numeric: true,
//                 sensitivity: "base",
//             });
//         });
//         data.forEach(function (petugas) {
//             var namaLengkap =
//                 petugas.gelar_d + " " + petugas.nama + " " + petugas.gelar_b;
//             var option = new Option(namaLengkap, petugas.nip, false, false);
//             petugasSelectElement.append(option).trigger("change");
//         });
//     });
// }

//DOTS CENTER
// function populateBlnKeOptions() {
//     var blnKeSelectElement = $("#blnKe");
//     var tglKunjunganInput = $("#tglKunj"); // format 2024-06-06
//     var tglKontrolInput = $("#nxKontrol");

//     var modalUpdate = $("#modal-update #statusPengobatan");
//     var modalblnKeSelectElement = $("#modal-blnKe");
//     var modaltglKunjunganInput = $("#tglKunj");
//     var modaltglKontrolInput = $("#modal-nxKontrol");

//     $.ajax({
//         url: "/api/blnKeDots",
//         method: "GET",
//         success: function (data) {
//             // Urutkan data berdasarkan id
//             data.sort(function (a, b) {
//                 return a.id - b.id;
//             });

//             blnKeSelectElement.empty();
//             modalblnKeSelectElement.empty();
//             modalUpdate.empty();

//             data.forEach(function (blnKe) {
//                 var option = new Option(blnKe.nmBlnKe, blnKe.id);
//                 blnKeSelectElement.append(option);
//                 var modalOption = new Option(blnKe.nmBlnKe, blnKe.id);
//                 modalblnKeSelectElement.append(modalOption);
//                 var modalUpdateOp = new Option(blnKe.nmBlnKe, blnKe.id);
//                 modalUpdate.append(modalUpdateOp);
//             });

//             blnKeSelectElement.on("change", function () {
//                 var selectedId = $(this).val();
//                 var selectedBln = data.find(function (bulanKe) {
//                     return bulanKe.id == selectedId;
//                 });

//                 var selectedValue = selectedBln
//                     ? parseFloat(selectedBln.nilai)
//                     : 0;
//                 if (isNaN(selectedValue)) {
//                     selectedValue = 0;
//                 }

//                 var tglKunjunganValue = new Date(tglKunjunganInput.val());
//                 if (isNaN(tglKunjunganValue.getTime())) {
//                     // console.error(
//                     //     "Invalid date value: ",
//                     //     tglKunjunganInput.val()
//                     // );
//                     return;
//                 }

//                 tglKunjunganValue.setDate(
//                     tglKunjunganValue.getDate() + selectedValue
//                 );
//                 var formattedDate = tglKunjunganValue
//                     .toISOString()
//                     .split("T")[0];

//                 tglKontrolInput.val(formattedDate);
//             });

//             modalblnKeSelectElement.on("change", function () {
//                 var selectedId = $(this).val();
//                 var selectedBln = data.find(function (bulanKe) {
//                     return bulanKe.id == selectedId;
//                 });

//                 var selectedValue = selectedBln
//                     ? parseFloat(selectedBln.nilai)
//                     : 0;
//                 if (isNaN(selectedValue)) {
//                     selectedValue = 0;
//                 }

//                 var tglKunjunganValue = new Date(modaltglKunjunganInput.val());
//                 if (isNaN(tglKunjunganValue.getTime())) {
//                     // console.error(
//                     //     "Invalid date value: ",
//                     //     modaltglKunjunganInput.val()
//                     // );
//                     return;
//                 }

//                 tglKunjunganValue.setDate(
//                     tglKunjunganValue.getDate() + selectedValue
//                 );
//                 var formattedDate = tglKunjunganValue
//                     .toISOString()
//                     .split("T")[0];

//                 modaltglKontrolInput.val(formattedDate);
//             });
//         },
//         error: function (error) {
//             console.error("Error fetching data: ", error);
//         },
//     });
// }

// function populateDxMedis() {
//     var dxDotsModal1 = $("#modal-pasienTB #modal-kdDx");
//     var dxDotsModal = $("#modal-kdDx");
//     $.get("/api/dxMedis", function (data) {
//         data.sort(function (a, b) {
//             var namaA = a.diagnosa.toUpperCase();
//             var namaB = b.diagnosa.toUpperCase();
//             if (namaA < namaB) {
//                 return -1;
//             }
//             if (namaA > namaB) {
//                 return 1;
//             }
//             return 0;
//         });
//         data.forEach(function (dxDot) {
//             var option = new Option(dxDot.diagnosa, dxDot.kdDiag, false, false);
//             dxDotsModal1.append(option).trigger("change");
//             dxDotsModal.append(option).trigger("change");
//         });
//     });
// }

// function populateObat() {
//     var obatDots = $("#obatD #obatDots");
//     var obatDotsModal = $("#modal-pasienTB #modal-obtDots");
//     $.get("/api/obatDots", function (data) {
//         data.sort(function (a, b) {
//             var namaA = a.nmPengobatan.toUpperCase();
//             var namaB = b.nmPengobatan.toUpperCase();
//             if (namaA < namaB) {
//                 return -1;
//             }
//             if (namaA > namaB) {
//                 return 1;
//             }
//             return 0;
//         });

//         data.forEach(function (obat) {
//             var option = new Option(obat.nmPengobatan, obat.kd, false, false);
//             obatDots.append(option).trigger("change");
//         });
//         data.forEach(function (obat) {
//             var option = new Option(obat.nmPengobatan, obat.kd, false, false);
//             obatDotsModal.append(option).trigger("change");
//         });
//     });
// }

//Radiologi
// function populateFoto() {
//     var fotoRo = $("#kdFoto");
//     $.get("/api/fotoRo", function (data) {
//         data.forEach(function (foto) {
//             var option = new Option(foto.nmFoto, foto.kdFoto, false, false);
//             fotoRo.append(option).trigger("change");
//         });
//     });
// }
// function populateUkuranFilm() {
//     var film = $("#kdFilm");
//     $.get("/api/filmRo", function (data) {
//         data.forEach(function (foto) {
//             var option = new Option(foto.ukuranFilm, foto.kdFilm, false, false);
//             film.append(option).trigger("change");
//         });
//     });
// }
// function populateProyeksi() {
//     var film = $("#kdProyeksi");
//     $.get("/api/proyeksiRo", function (data) {
//         data.forEach(function (foto) {
//             var option = new Option(
//                 foto.proyeksi,
//                 foto.kdProyeksi,
//                 false,
//                 false
//             );
//             film.append(option).trigger("change");
//         });
//     });
// }
// function populateMesin() {
//     var film = $("#kdMesin");
//     $.get("/api/mesinRo", function (data) {
//         data.forEach(function (foto) {
//             var option = new Option(foto.nmMesin, foto.kdMesin, false, false);
//             film.append(option).trigger("change");
//         });
//     });
// }

// function populateKondisi(data) {
//     var kvSelect = $("#kv");
//     var maSelect = $("#ma");
//     var sSelect = $("#s");

//     data.forEach(function (foto) {
//         var option = new Option(foto.nmKondisi, foto.kdKondisiRo, false, false);

//         switch (foto.grup) {
//             case "KV":
//                 kvSelect.append(option);
//                 break;
//             case "mA":
//                 maSelect.append(option);
//                 break;
//             case "s":
//                 sSelect.append(option);
//                 break;
//         }
//     });

//     kvSelect.trigger("change");
//     maSelect.trigger("change");
//     sSelect.trigger("change");
// }

// function kondisiRo() {
//     var status = "1";
//     var grups = ["KV", "mA", "S"];

//     $.post("/api/kondisiRo", { grups: grups, status: status }, function (data) {
//         populateKondisi(data);
//     });
// }
