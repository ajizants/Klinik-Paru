var Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
});

let jk = "";
function fetchDataAntrian(params, callback) {
    $.ajax({
        url: "/api/cpptKominfo",
        type: "POST",
        data: params,
        success: callback,
        error: function (xhr) {
            console.error("Error fetching data:", xhr);
            alert("Failed to fetch data. Please try again later.");
        },
    });
}

function initializeDataAntrian(response, ruang) {
    const data = response?.response?.data || [];
    const filteredData = data.filter((item) => item.status === "belum");
    // const dataArray = filteredData.length ? filteredData : getNoDataMessage();

    // processDataArray(dataArray, ruang);
    // drawDataTable(dataArray, ruang);

    if (filteredData.length) {
        const dataArray = filteredData;
        processDataArray(filteredData, ruang);
        drawDataTable(dataArray, ruang);
    } else {
        const dataArray = getNoDataMessage();
        drawDataTable(dataArray, ruang);
    }
}

function antrian(ruang) {
    $("#loadingSpinner").show();

    const params = {
        tanggal_awal: $("#tanggal").val(),
        tanggal_akhir: $("#tanggal").val(),
        ruang: ruang,
    };

    fetchDataAntrian(params, function (response) {
        $("#loadingSpinner").hide();

        if ($.fn.DataTable.isDataTable("#dataAntrian")) {
            const table = $("#dataAntrian").DataTable();
            const data = response?.response?.data || [];
            const filteredData = data.filter((item) => item.status === "belum");
            processDataArray(filteredData, ruang);
            table
                .clear()
                .rows.add(
                    filteredData.length ? filteredData : getNoDataMessage()
                )
                .draw();
        } else {
            initializeDataAntrian(response, ruang);
        }
    });
}

function processDataArray(dataArray, ruang) {
    dataArray.forEach((item, index) => {
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
        item.aksi = generateActionButton(item, ruang);
    });
}

function drawDataTable(dataArray, ruang) {
    const columns = getColumnsForRuang(ruang);
    $("#dataAntrian").DataTable({
        data: dataArray,
        columns: columns,
        order: [
            [1, "asc"],
            [2, "asc"],
        ],
        pageLength: ruang === "lab" ? 5 : 10,
    });
}

function generateActionButton(item, ruang) {
    // console.log("🚀 ~ generateActionButton ~ item:", item);

    const today = new Date(2024, 9, 3); // October 3, 2024
    today.setHours(0, 0, 0, 0); // Set time to 00:00:00.000

    let notrans;

    const date = new Date(item.tanggal);
    date.setHours(0, 0, 0, 0); // Set time to 00:00:00.000
    if (date <= today) {
        notrans = item.no_trans;
    } else {
        notrans = item.no_reg;
    }

    // Ensure asktind is properly trimmed and handled
    const asktind =
        item.asktind && item.asktind.trim() !== ""
            ? item.asktind.trim()
            : "No data";

    const commonAttributes = `
        data-norm="${item.pasien_no_rm}"
        data-nama="${item.pasien_nama}"
        data-dokter="${item.dokter_nama}"
        data-asktind="${asktind}"   // Correct access to asktind
        data-kddokter="${item.nip_dokter}"
        data-alamat="${getFormattedAddress(item)}"
        data-layanan="${item.penjamin_nama}"
        data-notrans="${notrans}"
        data-tgltrans="${item.tanggal}"
        data-umur="${item.umur}"
    `;

    switch (ruang) {
        case "dots":
            return `<a type="button" class="aksi-button btn-sm btn-primary py-0 icon-link icon-link-hover"
                onclick="cariPasienTb('${item.pasien_no_rm}','${item.tanggal}');"><i class="fas fa-pen-to-square"></i></a>`;
        case "ro":
            return `<a type="button" class="aksi-button btn-sm btn-primary py-0 icon-link icon-link-hover"
                ${commonAttributes} onclick="askRo(this);"><i class="fas fa-pen-to-square"></i></a>`;
        case "igd":
            return `<a class="aksi-button btn-sm btn-primary py-0 icon-link icon-link-hover"
        ${commonAttributes} onclick="setTransaksi(this);"><i class="fas fa-pen-to-square"></i></a>`;
        case "lab":
            return `<a class="aksi-button btn-sm btn-primary py-0 icon-link icon-link-hover"
                ${commonAttributes} onclick="askLab(this);"><i class="fas fa-pen-to-square"></i></a>`;
    }
}

function getFormattedAddress(item) {
    return `${item.kelurahan_nama}, ${item.pasien_rt}/${item.pasien_rw}, ${item.kecamatan_nama}, ${item.kabupaten_nama}`;
}

function generateAsktindString(data, addNewLine = false, isLab = false) {
    if (!Array.isArray(data)) return ""; // Ensure data is an array

    return data
        .map((item, index) => {
            const separator = isLab ? (index % 2 === 1 ? ",<br>" : ", ") : ", ";
            return `${item.layanan || item.nama_tindakan} (${
                item.keterangan || item.nama_obat || ""
            })${addNewLine ? "<br>" : separator}`;
        })
        .join("")
        .replace(/(,\s*<br>|,\s)$/, ""); // Remove trailing separator
}

function getColumnsForRuang(ruang) {
    const commonColumns = [
        { data: "aksi", className: "text-center p-2" },
        {
            data: "status",
            className: "text-center p-2",
            render: (data) =>
                `<div class="badge badge-${
                    data === "belum" ? "danger" : "success"
                }">${data}</div>`,
        },
        { data: "tanggal", className: "text-center p-2 col-1" },
        { data: "antrean_nomor", className: "text-center p-2" },
        { data: "penjamin_nama", className: "text-center p-2" },
        { data: "pasien_no_rm", className: "text-center p-2" },
        { data: "pasien_nama", className: "p-2 col-2" },
        { data: "dokter_nama", className: "p-2 col-3" },
    ];

    const extraColumns = {
        dots: [{ data: "nmDiagnosa", className: "p-2 col-4" }],
        ro: [{ data: "asktind", className: "p-2 col-3" }],
        igd: [{ data: "asktind", className: "p-2 col-4" }],
        lab: [{ data: "asktind", className: "p-2 col-4" }],
    };

    return commonColumns.concat(extraColumns[ruang] || []);
}

function getNoDataMessage() {
    return [
        {
            aksi: "",
            status: "",
            tanggal: "",
            antrean_nomor: "",
            pasien_no_rm: "",
            penjamin_nama: "",
            pasien_nama: "Belum ada data masuk",
            dokter_nama: "",
            nmDiagnosa: "",
            asktind: "",
        },
    ];
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
            // Uncomment below to retry on error
            // fetchDataAntrianAll(tanggal, ruang, callback);
        },
    });
}

function initializeDataTable(selector, data, columns, order = [1, "asc"]) {
    $(selector).DataTable({
        data,
        columns,
        order,
        destroy: true,
    });
}

function getColumnDefinitions(statusType = "status_pulang", ruang) {
    const baseColumns = [
        { data: "aksi", className: "text-center p-2 col-1" },
        {
            data: statusType,
            className: "text-center p-2",
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
        { data: "tanggal", className: "col-1 p-2" },
        { data: "antrean_nomor", className: "text-center p-2" },
        { data: "penjamin_nama", className: "text-center p-2" },
        { data: "pasien_no_rm", className: "text-center p-2" },
    ];

    const extraColumns =
        ruang === "lab" || ruang === "dots"
            ? [{ data: "pasien_nik", className: "p-2 col-1" }]
            : [];

    const commonColumns = [
        { data: "pasien_nama", className: "p-2 col-3" },
        { data: "dokter_nama", className: "p-2 col-3" },
        { data: "poli_nama", className: "p-2" },
    ];

    return [...baseColumns, ...extraColumns, ...commonColumns];
}

function processResponse(response, ruang, statusFilter) {
    if (!response || !response.response || !response.response.data) {
        console.error("Invalid response format:", response);
        return;
    }

    const data = response.response.data.map((item) => ({
        ...item,
        tgl: $("#tanggal").val(),
        aksi: generateActionLink(item, ruang),
    }));

    const filteredData = data.filter((item) => item.status === statusFilter);

    return { data, filteredData };
}

function generateActionLink(item, ruang) {
    const today = new Date(2024, 9, 3); // October 3, 2024
    today.setHours(0, 0, 0, 0); // Set time to 00:00:00.000
    // console.log("🚀 ~ generateActionLink ~ today:", today);

    let notrans; // Declare notrans outside of the if-else block

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
        data-norm="${item.pasien_no_rm}"
        data-nama="${item.pasien_nama}"
        data-dokter="${item.dokter_nama}"
        data-asktind="${item.asktind || ""}"
        data-jk="${item.jenis_kelamin_nama}"
        data-kddokter="${item.nip_dokter}"
        data-alamat="${item.pasien_alamat}"
        data-layanan="${item.penjamin_nama}"
        data-notrans="${notrans}"
        data-tgltrans="${item.tanggal}"
        data
    `;
    const links = {
        dots: `<a type="button" ${commonAttributes} class="aksi-button btn-sm btn-primary py-0 icon-link icon-link-hover" onclick="setTransaksi(this,'${ruang}');"><i class="fas fa-pen-to-square"></i></a>`,
        lab: `<a type="button" ${commonAttributes} class="aksi-button btn-sm btn-primary py-0 icon-link icon-link-hover" onclick="setTransaksi(this,'${ruang}');"><i class="fas fa-pen-to-square"></i></a>`,
        ro: `<a type="button" ${commonAttributes} class="aksi-button btn-sm btn-primary py-0 icon-link icon-link-hover" onclick="setTransaksi(this,'${ruang}');"><i class="fas fa-pen-to-square"></i></a>`,
        igd: `<a type="button" ${commonAttributes} class="aksi-button btn-sm btn-primary py-0 icon-link icon-link-hover" onclick="setTransaksi(this,'${ruang}');"><i class="fas fa-pen-to-square"></i></a>
              <button type="button" ${commonAttributes} class="aksi-button btn-sm btn-${item.igd_selesai} py-0 icon-link icon-link-hover" onclick="checkOut('${item.pasien_no_rm}','${item.no_trans}', this,'${ruang}')" placeholder="Selesai"><i class="fa-regular fa-square-check"></i></button>`,
        default: `<a type="button" ${commonAttributes} class="aksi-button btn-sm btn-primary py-0 icon-link icon-link-hover" onclick="setTransaksi(this,'${ruang}');"><i class="fas fa-pen-to-square"></i></a>`,
    };
    // const links = {
    //     dots: `<a type="button" ${commonAttributes} class="aksi-button btn-sm btn-primary py-0 icon-link icon-link-hover" onclick="cariPasienTb('${item.pasien_no_rm}', '${item.tanggal}', '${ruang}');"><i class="fas fa-pen-to-square"></i></a>`,
    //     lab: `<a type="button" ${commonAttributes} class="aksi-button btn-sm btn-primary py-0 icon-link icon-link-hover" onclick="cariTsLab('${item.pasien_no_rm}', '${item.tanggal}', '${ruang}');"><i class="fas fa-pen-to-square"></i></a>`,
    //     ro: `<a type="button" ${commonAttributes} class="aksi-button btn-sm btn-primary py-0 icon-link icon-link-hover" onclick="cariTsRo('${item.pasien_no_rm}', '${item.tanggal}', '${ruang}');"><i class="fas fa-pen-to-square"></i></a>`,
    //     igd: `<a type="button" ${commonAttributes} class="aksi-button btn-sm btn-primary py-0 icon-link icon-link-hover" onclick="cariTsIgd('${item.pasien_no_rm}', '${item.tanggal}', '${ruang}');"><i class="fas fa-pen-to-square"></i></a>
    //           <button type="button" ${commonAttributes} class="aksi-button btn-sm btn-${item.igd_selesai} py-0 icon-link icon-link-hover" onclick="checkOut('${item.pasien_no_rm}','${item.no_trans}', this)" placeholder="Selesai"><i class="fa-regular fa-square-check"></i></button>`,
    //     default: `<a type="button" ${commonAttributes} class="aksi-button btn-sm btn-primary py-0 icon-link icon-link-hover" onclick="cariKominfo('${item.pasien_no_rm}', '${item.tanggal}', '${ruang}');"><i class="fas fa-pen-to-square"></i></a>`,
    // };
    return links[ruang] || links.default;
}

function setTransaksi(button, ruang) {
    var norm = $(button).data("norm");
    var nama = $(button).data("nama");
    var dokter = $(button).data("kddokter");
    var alamat = $(button).data("alamat");
    var layanan = $(button).data("layanan");
    var notrans = $(button).data("notrans");
    var tgltrans = $(button).data("tgltrans");
    var tgl = $(button).data("tgltrans");
    var asktind = $(button).data("asktind");
    jk = $(button).data("jk");
    console.log("🚀 ~ setTransaksi ~ jk:", jk);
    switch (ruang) {
        case "igd":
            cariTsIgd(notrans, norm, tgl, ruang);
            break;
        case "lab":
            cariTsLab(norm, tgl, ruang);
            break;
        case "ro":
            if ($.fn.DataTable.isDataTable("#tableRo")) {
                var tabel = $("#tableRo").DataTable();
                tabel.clear().destroy();
            }
            cariTsRo(norm, tgl, ruang);
            break;
        case "dots":
            cariPasienTb(norm, tgl, ruang);
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
    $("#permintaan").html(`<b>${asktind}</b>`);

    scrollToInputSection();
}

function antrianAll(ruang) {
    $("#loadingSpinner").show();
    const tanggal = $("#tanggal").val();

    fetchDataAntrianAll(tanggal, ruang, function (response) {
        $("#loadingSpinner").hide();
        const { data, filteredData } = processResponse(
            response,
            ruang,
            "Sudah Selesai"
        );
        const blmUpload = processResponse(
            response,
            ruang,
            "Belum Upload Foto Thorax"
        );
        const belumUpload = blmUpload.filteredData;

        initializeDataTable(
            "#antrianall",
            data,
            getColumnDefinitions("status_pulang", ruang)
        );

        if (ruang === "igd" || ruang === "lab" || ruang === "dots") {
            initializeDataTable(
                "#dataSelesai",
                filteredData,
                getColumnDefinitions("status")
            );
        }
        if (ruang === "ro") {
            initializeDataTable(
                "#dataSelesai",
                filteredData,
                getColumnDefinitions("status")
            );
            initializeDataTable(
                "#daftarUpload",
                belumUpload,
                getColumnDefinitions("status")
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
        console.log("🚀 ~ handleMetadata ~ cppt:", cppt);
        const pendaftaran = response.response.pendaftaran[0];
        const dxMed = cppt.diagnosa[0];
        // console.log("🚀 ~ handleMetadata ~ dxMed:", dxMed);

        switch (ruang) {
            case "igd":
                handleIgd(cppt, pasien, pendaftaran);
                dataTindakan(pendaftaran.no_trans);
                break;
            case "farmasi":
                dataFarmasi();
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
    const permintaan =
        cppt.tindakan
            ?.map(
                (tindakan) =>
                    `${tindakan.nama_tindakan} : ${tindakan.nama_obat}`
            )
            .join(",<br>") || "";
    isiIdentitas(pasien, pendaftaran, permintaan);
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
    console.log("🚀 ~ isiIdentitas ~ permintaan:", permintaan);

    // Set values for input fields
    $("#layanan").val(pendaftaran.penjamin_nama); // Trigger change event if needed
    $("#norm").val(pasien.pasien_no_rm);
    $("#nama").val(pasien.pasien_nama);
    $("#alamat").val(pasien.pasien_alamat);
    $("#notrans").val(pendaftaran.no_trans);
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
