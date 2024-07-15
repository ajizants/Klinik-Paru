var Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
});
function fetchDataAntrian(params, callback) {
    // console.log("🚀 ~ fetchDataAntrian ~ params:", params);
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

function initializeDataAntrian(response, ruang) {
    // console.log("🚀 ~ initializeDataAntrian ~ ruang:", ruang);
    // Pastikan response.data adalah objek yang berisi data pasien
    if (response && response.response && response.response.data) {
        var dataArray = Object.values(response.response.data); // Mengubah objek ke dalam array nilai-nilai
        processDataArray(dataArray, ruang);
        drawDataTable(dataArray, ruang);
    } else {
        var noDataMsg = [
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
        drawDataTable(noDataMsg, ruang);
    }
}

function antrian(ruang) {
    // console.log("🚀 ~ antrian ~ ruang:", ruang);
    $("#loadingSpinner").show();
    var tanggal_awal = $("#tanggal").val(); // Ganti id input tanggal_awal
    var tanggal_akhir = $("#tanggal").val(); // Ganti id input tanggal_akhir
    // var no_rm = $("#norm").val(); // Ganti id input no_rm

    var param = {
        tanggal_awal: tanggal_awal,
        tanggal_akhir: tanggal_akhir,
        ruang: ruang,
    };

    fetchDataAntrian(param, function (response) {
        // console.log("🚀 ~ response:", response);
        $("#loadingSpinner").hide();
        // var ruang = ruang;
        // console.log("🚀 ~ antrian - fetchDataAntrian~ ruang:", ruang);
        if ($.fn.DataTable.isDataTable("#dataAntrian")) {
            var table = $("#dataAntrian").DataTable();

            if (response && response.response && response.response.data) {
                var dataArray = Object.values(response.response.data); // Mengubah objek ke dalam array nilai-nilai
                processDataArray(dataArray, ruang);
            } else {
                var noDataMsg = [
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
                dataArray = noDataMsg;
            }
            table.clear().rows.add(dataArray).draw();
        } else {
            initializeDataAntrian(response, ruang);
        }
    });
}

function processDataArray(dataArray, ruang) {
    // console.log("🚀 ~ processDataArray ~ ruang:", ruang);
    if (ruang === "dots") {
        dataArray.forEach(function (item) {
            item.index = dataArray.indexOf(item) + 1;
            item.nmDiagnosa = item.diagnosa[0].nama_diagnosa;
            var alamat = `${item.kelurahan_nama}, ${item.pasien_rt}/${item.pasien_rw}, ${item.kecamatan_nama}, ${item.kabupaten_nama}`;
            item.aksi = `<a type="button" class="aksi-button btn-sm btn-primary py-0 icon-link icon-link-hover"
                                    onclick="cariPasienTb('${item.pasien_no_rm}','${item.tanggal}');"><i class="fas fa-pen-to-square"></i></a>`;
        });
    } else if (ruang === "ro") {
        dataArray.forEach(function (item) {
            var asktind = "";
            if (item.radiologi && Array.isArray(item.radiologi)) {
                item.radiologi.forEach(function (radiologi) {
                    asktind += `${radiologi.layanan} ( ${radiologi.keterangan} ), `;
                });
            }
            item.asktind = asktind;
            item.index = dataArray.indexOf(item) + 1;

            var alamat = `${item.kelurahan_nama}, ${item.pasien_rt}/${item.pasien_rw}, ${item.kecamatan_nama}, ${item.kabupaten_nama}`;
            item.aksi = `<a type="button" class="aksi-button btn-sm btn-primary py-0 icon-link icon-link-hover"
                                                data-norm="${item.pasien_no_rm}"
                                                data-nama="${item.pasien_nama}"
                                                data-dokter="${item.dokter_nama}"
                                                data-asktind="${asktind}"
                                                data-kddokter="${item.nip_dokter}"
                                                data-alamat="${alamat}"
                                                data-layanan="${item.penjamin_nama}"
                                                data-notrans="${item.no_trans}"
                                                data-tgltrans="${item.tanggal}"
                                                onclick="askRo(this);"><i class="fas fa-pen-to-square"></i></a>`;
        });
    } else if (ruang === "igd") {
        console.log("🚀 ~ dataArray igd:", dataArray);
        dataArray.forEach(function (item) {
            var asktind = "";
            // Pastikan item.tindakan adalah array sebelum mengaksesnya
            if (item.tindakan && Array.isArray(item.tindakan)) {
                item.tindakan.forEach(function (tindakan) {
                    asktind += `${tindakan.nama_tindakan} : ${tindakan.nama_obat},<br>`;
                });
            }
            item.asktind = asktind;
            item.index = dataArray.indexOf(item) + 1;

            var alamat = `${item.kelurahan_nama}, ${item.pasien_rt}/${item.pasien_rw}, ${item.kecamatan_nama}, ${item.kabupaten_nama}`;
            item.aksi = `<a href="" class="aksi-button btn-sm btn-primary py-0 icon-link icon-link-hover"
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
    } else if (ruang === "lab") {
        dataArray.forEach(function (item) {
            let asktind = "";
            if (item.laboratorium && Array.isArray(item.laboratorium)) {
                item.laboratorium.forEach(function (lab, index) {
                    // Add data in pairs
                    asktind += `${lab.layanan} (${lab.keterangan})`;
                    // Add comma if it's an odd index, otherwise add a new line
                    if ((index + 1) % 2 === 0) {
                        asktind += ",<br>";
                    } else {
                        asktind += ",  ";
                    }
                });

                // Remove the last comma and space or newline for a clean ending
                if (asktind.endsWith(", ")) {
                    asktind = asktind.slice(0, -2);
                } else if (asktind.endsWith(",<br>")) {
                    asktind = asktind.slice(0, -1);
                }
            }
            item.asktind = asktind;
            item.index = dataArray.indexOf(item) + 1;

            const alamat = `${item.kelurahan_nama}, ${item.pasien_rt}/${item.pasien_rw}, ${item.kecamatan_nama}, ${item.kabupaten_nama}`;
            item.aksi = `<a href="#" class="aksi-button btn-sm btn-primary py-0 icon-link icon-link-hover"
                            data-norm="${item.pasien_no_rm}"
                            data-nama="${item.pasien_nama}"
                            data-dokter="${item.dokter_nama}"
                            data-asktind="${asktind}"
                            data-kddokter="${item.nip_dokter}"
                            data-alamat="${alamat}"
                            data-layanan="${item.penjamin_nama}"
                            data-notrans="${item.no_trans}"
                            data-tgltrans="${item.tanggal}"
                            onclick="askLab(this);"><i class="fas fa-pen-to-square"></i></a>`;
        });
    }
}

function drawDataTable(dataArray, ruang) {
    if (ruang === "dots") {
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
                { data: "tanggal", className: "text-center p-2 col-1" },
                { data: "antrean_nomor", className: "text-center p-2" },
                { data: "pasien_no_rm", className: "text-center p-2" },
                { data: "penjamin_nama", className: "text-center p-2" },
                { data: "pasien_nama", className: "p-2 col-2" },
                { data: "dokter_nama", className: "p-2 col-3" },
                { data: "nmDiagnosa", className: "p-2 col-4" },
            ],
            order: [
                [1, "asc"],
                [2, "asc"],
            ],
        });
    } else if (ruang === "ro") {
        $("#dataAntrian").DataTable({
            data: dataArray,
            columns: [
                { data: "aksi", className: "text-center p-2" },
                {
                    data: "status",
                    className: "text-center p-2",
                    render: function (data) {
                        var backgroundColor =
                            data === "belum" ? "danger" : "success";
                        return `<div class="badge badge-${backgroundColor}">${data}</div>`;
                    },
                },
                { data: "tanggal", className: "text-center p-2 col-1" },
                { data: "antrean_nomor", className: "text-center p-2" },
                { data: "penjamin_nama", className: "text-center p-2" },
                { data: "pasien_no_rm", className: "text-center p-2" },
                { data: "pasien_nama", className: "p-2 col-2" },
                { data: "asktind", className: "p-2 col-3" },
                { data: "dokter_nama", className: "p-2 col-3" },
            ],
            order: [
                [1, "asc"],
                [2, "asc"],
            ],
        });
    } else if (ruang === "igd") {
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
                { data: "tanggal", className: "text-center p-2 col-1" },
                { data: "antrean_nomor", className: "text-center p-2" },
                { data: "pasien_no_rm", className: "text-center p-2" },
                { data: "penjamin_nama", className: "text-center p-2" },
                { data: "pasien_nama", className: "p-2 col-2" },
                { data: "asktind", className: "p-2 col-4" },
                { data: "dokter_nama", className: "p-2 col-3" },
            ],
            order: [
                [1, "asc"],
                [2, "asc"],
            ],
        });
    } else if (ruang == "lab") {
        $("#dataAntrian").DataTable({
            data: dataArray,
            columns: [
                { data: "aksi", className: "text-center p-2" },
                {
                    data: "status",
                    className: "text-center p-2",
                    render: function (data) {
                        var backgroundColor =
                            data === "belum" ? "danger" : "success";
                        return `<div class="badge badge-${backgroundColor}">${data}</div>`;
                    },
                },
                { data: "antrean_nomor", className: "text-center p-2" },
                { data: "tanggal", className: "text-center p-2 col-1" },
                { data: "penjamin_nama", className: "text-center p-2" },
                { data: "pasien_no_rm", className: "text-center p-2" },
                { data: "pasien_nama", className: "p-2 col-2" },
                { data: "asktind", className: "p-2 col-4" },
                { data: "dokter_nama", className: "p-2 col-3" },
            ],
            order: [
                [1, "asc"],
                [2, "asc"],
            ],
            pageLength: 5,
        });
    }
}

function fetchDataAntrianAll(tanggal, ruang, callback) {
    $.ajax({
        url: "/api/antrian/kominfo",
        type: "post",
        data: { tanggal: tanggal, ruang: ruang },
        success: callback,
        error: function (xhr) {
            console.error("Error fetching data:", xhr);
            fetchDataAntrianAll(tanggal, ruang, callback);
        },
    });
}

function initializeDataTable(selector, data, columns, order = [1, "asc"]) {
    $(selector).DataTable({
        data: data,
        columns: columns,
        order: order,
        destroy: true,
    });
}

function getColumnDefinitions(statusType = "status_pulang") {
    return [
        { data: "aksi", className: "text-center p-2 col-1" },
        {
            data: statusType,
            className: "text-center p-2 col-1",
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
        { data: "tanggal", className: "p-2" },
        { data: "antrean_nomor", className: "text-center p-2" },
        { data: "penjamin_nama", className: "text-center p-2" },
        { data: "pasien_no_rm", className: "text-center p-2" },
        { data: "pasien_nama", className: "p-2 col-3" },
        { data: "poli_nama", className: "p-2" },
        { data: "dokter_nama", className: "p-2 col-3" },
    ];
}

function processResponse(response, ruang, statusFilter) {
    if (!response || !response.response || !response.response.data) {
        console.error("Invalid response format:", response);
        return;
    }

    const data = response.response.data;
    const filteredData = data.filter((item) => item.status === statusFilter);
    const tanggal = $("#tanggal").val();

    data.forEach((item) => {
        item.tgl = tanggal;
        let link;
        if (ruang === "dots") {
            link = `<a type="button" class="aksi-button btn-sm btn-primary py-0 icon-link icon-link-hover" onclick="cariPasienTb('${item.pasien_no_rm}', '${item.tgl}', '${ruang}');"><i class="fas fa-pen-to-square"></i></a>`;
        } else if (ruang === "lab") {
            link = `<a type="button" class="aksi-button btn-sm btn-primary py-0 icon-link icon-link-hover" onclick="cariTsLab('${item.pasien_no_rm}', '${item.tgl}', '${ruang}');"><i class="fas fa-pen-to-square"></i></a>`;
        } else if (ruang === "igd") {
            link = `<a type="button" class="aksi-button btn-sm btn-primary py-0 icon-link icon-link-hover" onclick="cariKominfo('${item.pasien_no_rm}', '${item.tgl}', '${ruang}');"><i class="fas fa-pen-to-square"></i></a>`;
        } else {
            link = `<a type="button" class="aksi-button btn-sm btn-primary py-0 icon-link icon-link-hover" onclick="cariKominfo('${item.pasien_no_rm}', '${item.tgl}', '${ruang}');"><i class="fas fa-pen-to-square"></i></a>`;
        }

        item.aksi = link;
    });

    return { data, filteredData };
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

        initializeDataTable(
            "#antrianall",
            data,
            getColumnDefinitions("status_pulang")
        );
        if (ruang === "igd" || ruang === "lab") {
            initializeDataTable(
                "#dataSelesai",
                filteredData,
                getColumnDefinitions("status")
            );
        }
    });
}

// function cariKominfo(norm, tgl, ruang) {
//     var normValue = norm ? norm : $("#norm").val();
//     if (ruang == "ro") {
//         var tgl = tgl ? tgl : $("#tglRO").val();
//     } else {
//         var tgl = tgl ? tgl : $("#tanggal").val();
//     }
//     // console.log(normValue)
//     // Add leading zeros if the value has less than 6 digits
//     while (normValue.length < 6) {
//         normValue = "0" + normValue;
//     }
//     if (
//         isNaN(normValue) ||
//         normValue === null ||
//         normValue === undefined ||
//         normValue == 0
//     ) {
//         Swal.fire({
//             icon: "error",
//             title: "No Rm Tidak Valid...!!! ",
//         });
//     } else {
//         Swal.fire({
//             icon: "info",
//             title: "Sedang Mencari Data Pasien di Aplikasi KOMINFO\n Mohon Ditunggu ...!!!",
//             // allowOutsideClick: false, // Mencegah interaksi di luar dialog
//             didOpen: () => {
//                 Swal.showLoading(); // Menampilkan loading spinner
//             },
//         });

//         $.ajax({
//             url: "/api/dataPasien",
//             method: "POST",
//             data: {
//                 no_rm: normValue,
//                 tanggal: tgl,
//             },
//             dataType: "json",
//             success: function (response) {
//                 if (response.error) {
//                     console.error("Error: " + response.error);
//                     Swal.fire({
//                         icon: "error",
//                         title:
//                             "Unexpected metadata code: " +
//                             code +
//                             "\n Silahkan Coba Lagi",
//                     });
//                 } else if (response.metadata) {
//                     var code = response.metadata.code;

//                     if (code === 404) {
//                         Swal.fire({
//                             icon: "info",
//                             title: response.metadata.message,
//                         });
//                     } else if (code === 204) {
//                         Swal.fire({
//                             icon: "info",
//                             title: response.metadata.message,
//                         });
//                         if (ruang != "lab") {
//                             rstForm();
//                         }
//                     } else if (code === 200) {
//                         Swal.fire({
//                             icon: "info",
//                             title: response.metadata.message,
//                             showConfirmButton: false,
//                             allowOutsideClick: false,
//                         });
//                         var pasien = response.response.pasien;
//                         // var cppt = response.response.cppt;
//                         // if (cppt == null) {
//                         //     cppt = [];
//                         // } else {
//                         //     cppt = response.response.cppt[0];
//                         // }
//                         var cppt = response.response.cppt
//                             ? response.response.cppt[0]
//                             : [];

//                         var pendaftaran = response.response.pendaftaran[0];
//                         var notrans = pendaftaran.no_trans;

//                         if (ruang == "igd") {
//                             let permintaan = "";
//                             var item = cppt; // Ambil data tindakan dari cppt

//                             if (item.tindakan && Array.isArray(item.tindakan)) {
//                                 item.tindakan.forEach(function (tindakan) {
//                                     // Add data in pairs
//                                     permintaan += `${tindakan.nama_tindakan} : ${tindakan.nama_obat},<br>`;
//                                 });
//                             }
//                             item.permintaan = permintaan;
//                             console.log(
//                                 "🚀 ~ cariKominfo ~ permintaan:",
//                                 permintaan
//                             );
//                             isiIdentitas(pasien, pendaftaran, permintaan);
//                             // isiIdentitas(pasien, pendaftaran, cppt);
//                             dataTindakan(notrans);
//                         } else if (ruang == "farmasi") {
//                             dataFarmasi();
//                             isiIdentitas(pasien, pendaftaran);
//                         } else if (ruang == "ro") {
//                             let permintaan = "";
//                             var item = cppt; // Ambil data tindakan dari cppt

//                             if (
//                                 item.radiologi &&
//                                 Array.isArray(item.radiologi)
//                             ) {
//                                 item.radiologi.forEach(function (radiologi) {
//                                     // Add data in pairs
//                                     permintaan += `${radiologi.layanan} (${radiologi.keterangan}),<br>`;
//                                 });
//                             }
//                             item.permintaan = permintaan;
//                             console.log(
//                                 "🚀 ~ cariKominfo ~ permintaan:",
//                                 permintaan
//                             );
//                             isiIdentitas(pasien, pendaftaran, permintaan);
//                         } else if (ruang == "dots") {
//                             isiBiodataModal(norm, tgl, pasien, pendaftaran);
//                         } else if (ruang == "lab") {
//                             let permintaan = "";
//                             var item = cppt; // Ambil data laboratorium dari cppt

//                             if (
//                                 item.laboratorium &&
//                                 Array.isArray(item.laboratorium)
//                             ) {
//                                 item.laboratorium.forEach(function (
//                                     lab,
//                                     index
//                                 ) {
//                                     // Add data in pairs
//                                     permintaan += `${lab.layanan} (${lab.keterangan})`;
//                                     // Add comma if it's an odd index, otherwise add a new line
//                                     if ((index + 1) % 2 === 0) {
//                                         permintaan += ",<br>";
//                                     } else {
//                                         permintaan += ", ";
//                                     }
//                                 });

//                                 // Remove the last comma and space or <br> for a clean ending
//                                 if (permintaan.endsWith(", ")) {
//                                     permintaan = permintaan.slice(0, -2);
//                                 } else if (permintaan.endsWith(",<br>")) {
//                                     permintaan = permintaan.slice(0, -5);
//                                 }
//                             }
//                             item.permintaan = permintaan;
//                             console.log(
//                                 "🚀 ~ cariKominfo ~ permintaan:",
//                                 permintaan
//                             );
//                             isiIdentitas(pasien, pendaftaran, permintaan);
//                         }
//                     } else {
//                         // Handle other potential status codes
//                         Swal.fire({
//                             icon: "error",
//                             title: "Unexpected metadata code: " + code,
//                         });
//                     }
//                 }
//             },

//             error: function (error) {
//                 console.error("Error fetching data:", error);
//             },
//         });
//     }
// }

function cariKominfo(norm, tgl, ruang) {
    var normValue = norm || $("#norm").val();
    tgl =
        ruang === "ro" ? tgl || $("#tglRO").val() : tgl || $("#tanggal").val();

    // Tambahkan nol di depan jika panjang nilai kurang dari 6 digit
    normValue = normValue.padStart(6, "0");

    if (isNaN(normValue) || !normValue) {
        Swal.fire({
            icon: "error",
            title: "No Rm Tidak Valid...!!!",
        });
    } else {
        Swal.fire({
            icon: "info",
            title: "Sedang Mencari Data Pasien di Aplikasi KOMINFO\n Mohon Ditunggu ...!!!",
            didOpen: () => Swal.showLoading(),
        });

        $.ajax({
            url: "/api/dataPasien",
            method: "POST",
            data: {
                no_rm: normValue,
                tanggal: tgl,
            },
            dataType: "json",
            success: function (response) {
                if (response.error) {
                    console.error("Error: " + response.error);
                    Swal.fire({
                        icon: "error",
                        title:
                            "Unexpected metadata code: " +
                            response.error +
                            "\n Silahkan Coba Lagi",
                    });
                } else if (response.metadata) {
                    handleMetadata(response, ruang);
                }
            },
            error: function (error) {
                console.error("Error fetching data:", error);
            },
        });
    }
}

function handleMetadata(response, ruang) {
    var code = response.metadata.code;
    var message = response.metadata.message;

    if (code === 404 || code === 204) {
        Swal.fire({
            icon: "info",
            title: message,
        });
        if (code === 204 && ruang !== "lab") {
            rstForm();
        }
    } else if (code === 200) {
        Swal.fire({
            icon: "info",
            title: message,
            showConfirmButton: false,
            allowOutsideClick: false,
        });
        var pasien = response.response.pasien;
        var cppt = response.response.cppt ? response.response.cppt[0] : [];
        var pendaftaran = response.response.pendaftaran[0];
        var notrans = pendaftaran.no_trans;

        switch (ruang) {
            case "igd":
                handleIgd(cppt, pasien, pendaftaran);
                dataTindakan(notrans);
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
    let permintaan = "";
    if (cppt.tindakan && Array.isArray(cppt.tindakan)) {
        cppt.tindakan.forEach(function (tindakan) {
            permintaan += `${tindakan.nama_tindakan} : ${tindakan.nama_obat},<br>`;
        });
    }
    isiIdentitas(pasien, pendaftaran, permintaan);
}

function handleRo(cppt, pasien, pendaftaran) {
    let permintaan = "";
    if (cppt.radiologi && Array.isArray(cppt.radiologi)) {
        cppt.radiologi.forEach(function (radiologi) {
            permintaan += `${radiologi.layanan} (${radiologi.keterangan}),<br>`;
        });
    }
    isiIdentitas(pasien, pendaftaran, permintaan);
}

function handleLab(cppt, pasien, pendaftaran) {
    let permintaan = "";
    if (cppt.laboratorium && Array.isArray(cppt.laboratorium)) {
        cppt.laboratorium.forEach(function (lab, index) {
            permintaan += `${lab.layanan} (${lab.keterangan})`;
            permintaan += (index + 1) % 2 === 0 ? ",<br>" : ", ";
        });
        permintaan = permintaan.replace(/, $|,<br>$/, "");
    }
    isiIdentitas(pasien, pendaftaran, permintaan);
}

function isiIdentitas(pasien, pendaftaran, permintaan) {
    console.log("🚀 ~ isiIdentitas ~ pendaftaran:", pendaftaran);
    console.log("🚀 ~ isiIdentitas ~ pasien:", pasien);
    $("#layanan").val(pendaftaran.penjamin_nama); // Trigger change event jika diperlukan
    $("#norm").val(pasien.pasien_no_rm);
    $("#nama").val(pasien.pasien_nama);
    $("#alamat").val(pasien.pasien_alamat);
    $("#notrans").val(pendaftaran.no_trans);
    $("#dokter").val(pendaftaran.nip_dokter).trigger("change");
    //cari jika ada element jk maka isi jk
    if ($("#jk").length) {
        $("#jk").val(pasien.jenis_kelamin_nama);
    }
    jk = pasien.jenis_kelamin_nama;
    var tanggalHariIni = new Date().toLocaleDateString("en-CA");

    var tglDaftar = pendaftaran.tanggal.split("-").reverse().join("-");

    // Memperbarui konten asktindContent
    $("#permintaan").html(`<b>${permintaan}</b>`);

    if (pendaftaran.tanggal !== tanggalHariIni) {
        Swal.fire({
            icon: "warning",
            title:
                "Pasien atas nama " +
                pasien.pasien_nama +
                " adalah pasien tanggal " +
                tglDaftar +
                "\n Jangan Lupa Mengganti Tanggal Transaksi...!!",
        });
        scrollToInputSection();
    } else {
        setTimeout(function () {
            Swal.close();
            scrollToInputSection();
        }, 1000);
    }
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
//IGD
function populateTindakanOptions() {
    var tindakanSelectElement = $("#tindakan");
    $.get("/api/jenistindakan", function (data) {
        data.sort(function (a, b) {
            var namaA = a.nmTindakan.toUpperCase();
            var namaB = b.nmTindakan.toUpperCase();
            if (namaA < namaB) {
                return -1;
            }
            if (namaA > namaB) {
                return 1;
            }
            return 0;
        });
        data.forEach(function (tindakan) {
            var option = new Option(
                tindakan.nmTindakan,
                tindakan.kdTindakan,
                false,
                false
            );
            tindakanSelectElement.append(option).trigger("change");
        });
    });
}
function populateBmhpOptions() {
    var obatSelectElement = $("#bmhp");
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
    $.get("/api/bmhp", function (data) {
        // data.sort(function (a, b) {
        //     var namaA = a.nmObat.toUpperCase();
        //     var namaB = b.nmObat.toUpperCase();
        //     if (namaA < namaB) {
        //         return -1;
        //     }
        //     if (namaA > namaB) {
        //         return 1;
        //     }
        //     return 0;
        // });

        data.forEach(function (obat) {
            var obatStok =
                obat.nmObat +
                " \u00A0\u00A0\u00A0\u00A0\u00A0\u00A0----(Stok: " +
                obat.sisa +
                ")----";
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
                document.querySelector("#qty").focus();
            } else {
                // Mengosongkan nilai harga beli dan harga jual jika obat tidak ditemukan
                hargaBeliElement.val("");
                hargaJualElement.val("");
            }
            document.querySelector("#qty").focus();
        });
    });
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

//SDM
function populateDokterOptions() {
    var dokterSelectElement = $("#dokter");
    var dokterModals = $("#modal-pasienTB #modal-dokter");

    // if (!dokterSelectElement.length || !dokterModals.length) {
    //     console.error(
    //         "One or both of the elements (dokterSelectElement, dokterModals) not found."
    //     );
    //     return;
    // }

    $.get("/api/dokter", function (data) {
        if (!Array.isArray(data)) {
            console.error("Invalid data format received from server.");
            return;
        }

        data.sort((a, b) =>
            (a.gelar_d + " " + a.nama + " " + a.gelar_b).localeCompare(
                b.gelar_d + " " + b.nama + " " + b.gelar_b,
                undefined,
                { numeric: true, sensitivity: "base" }
            )
        );

        data.forEach((dokter) => {
            var namaLengkap =
                dokter.gelar_d + " " + dokter.nama + " " + dokter.gelar_b;
            var nip = dokter.nip.toString();

            if (!dokterSelectElement.find(`option[value="${nip}"]`).length) {
                dokterSelectElement
                    .append(new Option(namaLengkap, nip))
                    .trigger("change");
            }

            if (!dokterModals.find(`option[value="${nip}"]`).length) {
                dokterModals
                    .append(new Option(namaLengkap, nip))
                    .trigger("change");
            }
        });
    }).fail((xhr, status, error) => {
        console.error("Error fetching data:", error);
        // Tindakan yang sesuai untuk menangani kesalahan
    });
}

function populatePetugasOptions() {
    var petugasSelectElement = $("#petugas");
    var petugasSelectModals = $("#modal-pasienTB #modal-petugas");

    // if (!petugasSelectElement.length || !petugasSelectModals.length) {
    //     console.error(
    //         "One or both of the elements (petugasSelectElement, petugasSelectModals) not found."
    //     );
    //     return;
    // }

    $.get("/api/perawat", function (data) {
        if (!Array.isArray(data)) {
            console.error("Invalid data format received from server.");
            return;
        }

        data.sort((a, b) =>
            (a.gelar_d + " " + a.nama + " " + a.gelar_b).localeCompare(
                b.gelar_d + " " + b.nama + " " + b.gelar_b,
                undefined,
                { numeric: true, sensitivity: "base" }
            )
        );

        data.forEach((petugas) => {
            var namaLengkap =
                petugas.gelar_d + " " + petugas.nama + " " + petugas.gelar_b;

            if (
                !petugasSelectElement.find(`option[value="${petugas.nip}"]`)
                    .length
            ) {
                petugasSelectElement
                    .append(new Option(namaLengkap, petugas.nip))
                    .trigger("change");
            }

            if (
                !petugasSelectModals.find(`option[value="${petugas.nip}"]`)
                    .length
            ) {
                petugasSelectModals
                    .append(new Option(namaLengkap, petugas.nip))
                    .trigger("change");
            }
        });
    }).fail((xhr, status, error) => {
        console.error("Error fetching data:", error);
        // Tindakan yang sesuai untuk menangani kesalahan
    });
}

function populateRadiograferOptions() {
    var petugasSelectElement = $("#p_rontgen");
    $.get("/api/radiografer", function (data) {
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
            var option = new Option(namaLengkap, petugas.nip, false, false);
            petugasSelectElement.append(option).trigger("change");
        });
    });
}
function populateApotekerOptions() {
    var petugasSelectElement = $("#apoteker");
    $.get("/api/apoteker", function (data) {
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
            var option = new Option(namaLengkap, petugas.nip, false, false);
            petugasSelectElement.append(option).trigger("change");
        });
    });
}

function populateAnalisOptions() {
    var petugasSelectElement = $("#analis");
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
            var option = new Option(namaLengkap, petugas.nip, false, false);
            petugasSelectElement.append(option).trigger("change");
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

//DOTS CENTER
function populateBlnKeOptions() {
    var blnKeSelectElement = $("#blnKe");
    var tglKunjunganInput = $("#tglKunj"); // format 2024-06-06
    var tglKontrolInput = $("#nxKontrol");

    var modalUpdate = $("#modal-update #statusPengobatan");
    var modalblnKeSelectElement = $("#modal-blnKe");
    var modaltglKunjunganInput = $("#tglKunj");
    var modaltglKontrolInput = $("#modal-nxKontrol");

    $.ajax({
        url: "/api/blnKeDots",
        method: "GET",
        success: function (data) {
            // Urutkan data berdasarkan id
            data.sort(function (a, b) {
                return a.id - b.id;
            });

            blnKeSelectElement.empty();
            modalblnKeSelectElement.empty();
            modalUpdate.empty();

            data.forEach(function (blnKe) {
                var option = new Option(blnKe.nmBlnKe, blnKe.id);
                blnKeSelectElement.append(option);
                var modalOption = new Option(blnKe.nmBlnKe, blnKe.id);
                modalblnKeSelectElement.append(modalOption);
                var modalUpdateOp = new Option(blnKe.nmBlnKe, blnKe.id);
                modalUpdate.append(modalUpdateOp);
            });

            blnKeSelectElement.on("change", function () {
                var selectedId = $(this).val();
                var selectedBln = data.find(function (bulanKe) {
                    return bulanKe.id == selectedId;
                });

                var selectedValue = selectedBln
                    ? parseFloat(selectedBln.nilai)
                    : 0;
                if (isNaN(selectedValue)) {
                    selectedValue = 0;
                }

                var tglKunjunganValue = new Date(tglKunjunganInput.val());
                if (isNaN(tglKunjunganValue.getTime())) {
                    // console.error(
                    //     "Invalid date value: ",
                    //     tglKunjunganInput.val()
                    // );
                    return;
                }

                tglKunjunganValue.setDate(
                    tglKunjunganValue.getDate() + selectedValue
                );
                var formattedDate = tglKunjunganValue
                    .toISOString()
                    .split("T")[0];

                tglKontrolInput.val(formattedDate);
            });

            modalblnKeSelectElement.on("change", function () {
                var selectedId = $(this).val();
                var selectedBln = data.find(function (bulanKe) {
                    return bulanKe.id == selectedId;
                });

                var selectedValue = selectedBln
                    ? parseFloat(selectedBln.nilai)
                    : 0;
                if (isNaN(selectedValue)) {
                    selectedValue = 0;
                }

                var tglKunjunganValue = new Date(modaltglKunjunganInput.val());
                if (isNaN(tglKunjunganValue.getTime())) {
                    // console.error(
                    //     "Invalid date value: ",
                    //     modaltglKunjunganInput.val()
                    // );
                    return;
                }

                tglKunjunganValue.setDate(
                    tglKunjunganValue.getDate() + selectedValue
                );
                var formattedDate = tglKunjunganValue
                    .toISOString()
                    .split("T")[0];

                modaltglKontrolInput.val(formattedDate);
            });
        },
        error: function (error) {
            console.error("Error fetching data: ", error);
        },
    });
}

function populateDxMedis() {
    var dxDotsModal1 = $("#modal-pasienTB #modal-kdDx");
    var dxDotsModal = $("#modal-kdDx");
    $.get("/api/dxMedis", function (data) {
        data.sort(function (a, b) {
            var namaA = a.diagnosa.toUpperCase();
            var namaB = b.diagnosa.toUpperCase();
            if (namaA < namaB) {
                return -1;
            }
            if (namaA > namaB) {
                return 1;
            }
            return 0;
        });
        data.forEach(function (dxDot) {
            var option = new Option(dxDot.diagnosa, dxDot.kdDiag, false, false);
            dxDotsModal1.append(option).trigger("change");
            dxDotsModal.append(option).trigger("change");
        });
    });
}

function populateObat() {
    var obatDots = $("#obatD #obatDots");
    var obatDotsModal = $("#modal-pasienTB #modal-obtDots");
    $.get("/api/obatDots", function (data) {
        data.sort(function (a, b) {
            var namaA = a.nmPengobatan.toUpperCase();
            var namaB = b.nmPengobatan.toUpperCase();
            if (namaA < namaB) {
                return -1;
            }
            if (namaA > namaB) {
                return 1;
            }
            return 0;
        });

        data.forEach(function (obat) {
            var option = new Option(obat.nmPengobatan, obat.kd, false, false);
            obatDots.append(option).trigger("change");
        });
        data.forEach(function (obat) {
            var option = new Option(obat.nmPengobatan, obat.kd, false, false);
            obatDotsModal.append(option).trigger("change");
        });
    });
}

//Radiologi
function populateFoto() {
    var fotoRo = $("#kdFoto");
    $.get("/api/fotoRo", function (data) {
        data.forEach(function (foto) {
            var option = new Option(foto.nmFoto, foto.kdFoto, false, false);
            fotoRo.append(option).trigger("change");
        });
    });
}
function populateUkuranFilm() {
    var film = $("#kdFilm");
    $.get("/api/filmRo", function (data) {
        data.forEach(function (foto) {
            var option = new Option(foto.ukuranFilm, foto.kdFilm, false, false);
            film.append(option).trigger("change");
        });
    });
}
function populateProyeksi() {
    var film = $("#kdProyeksi");
    $.get("/api/proyeksiRo", function (data) {
        data.forEach(function (foto) {
            var option = new Option(
                foto.proyeksi,
                foto.kdProyeksi,
                false,
                false
            );
            film.append(option).trigger("change");
        });
    });
}
function populateMesin() {
    var film = $("#kdMesin");
    $.get("/api/mesinRo", function (data) {
        data.forEach(function (foto) {
            var option = new Option(foto.nmMesin, foto.kdMesin, false, false);
            film.append(option).trigger("change");
        });
    });
}

function populateKondisi(data) {
    var kvSelect = $("#kv");
    var maSelect = $("#ma");
    var sSelect = $("#s");

    data.forEach(function (foto) {
        var option = new Option(foto.nmKondisi, foto.kdKondisiRo, false, false);

        switch (foto.grup) {
            case "KV":
                kvSelect.append(option);
                break;
            case "mA":
                maSelect.append(option);
                break;
            case "s":
                sSelect.append(option);
                break;
        }
    });

    kvSelect.trigger("change");
    maSelect.trigger("change");
    sSelect.trigger("change");
}

function kondisiRo() {
    var status = "1";
    var grups = ["KV", "mA", "S"];

    $.post("/api/kondisiRo", { grups: grups, status: status }, function (data) {
        populateKondisi(data);
    });
}
