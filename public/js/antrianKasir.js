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

    // const filteredData = data.filter((item) => item.status === statusFilter);
    const filteredData = data.filter((item) => item.status === statusFilter);
    const filteredPulang = data.filter(
        (item) =>
            item.status_pulang === statusFilter && item.status === "Belum Input"
    );

    return { data, filteredData, filteredPulang };
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
        data-umur="${item.pasien_umur}"
    `;
    const links = {
        dots: `<a type="button" ${commonAttributes} class="aksi-button btn-sm btn-primary py-md-0 py-1 icon-link icon-link-hover" onclick="setTransaksi(this,'${ruang}');"><i class="fas fa-pen-to-square"></i></a>`,
        lab: `<a type="button" ${commonAttributes} class="aksi-button btn-sm btn-primary py-md-0 py-1 icon-link icon-link-hover" onclick="setTransaksi(this,'${ruang}');"><i class="fas fa-pen-to-square"></i></a>`,
        ro: `<a type="button" ${commonAttributes} class="aksi-button btn-sm btn-primary py-md-0 py-1 icon-link icon-link-hover" onclick="setTransaksi(this,'${ruang}');"><i class="fas fa-pen-to-square"></i></a>`,
        igd: `<a type="button" ${commonAttributes} class="aksi-button btn-sm btn-primary py-md-0 py-1 icon-link icon-link-hover" onclick="setTransaksi(this,'${ruang}');"><i class="fas fa-pen-to-square"></i></a>
              <button type="button" ${commonAttributes} class="aksi-button btn-sm btn-${item.igd_selesai} py-md-0 py-1 mt-md-0 mt-2 icon-link icon-link-hover" onclick="checkOut('${item.pasien_no_rm}','${item.no_trans}', this,'${ruang}')" placeholder="Selesai"><i class="fa-regular fa-square-check"></i></button>`,
        default: `<a type="button" ${commonAttributes} class="aksi-button btn-sm btn-primary py-md-0 py-1  icon-link icon-link-hover" onclick="setTransaksi(this,'${ruang}');"><i class="fas fa-pen-to-square"></i></a>`,
    };
    return links[ruang] || links.default;
}

function setTransaksi(button, ruang) {
    console.log("🚀 ~ setTransaksi ~ setTransaksi:", setTransaksi);
    var norm = $(button).data("norm");
    var nama = $(button).data("nama");
    var dokter = $(button).data("dokter");
    var alamat = $(button).data("alamat");
    var layanan = $(button).data("layanan");
    var notrans = $(button).data("notrans");
    var tgltrans = $(button).data("tgltrans");
    var umur = $(button).data("umur");
    jk = $(button).data("jk");
    tampilkanOrder(notrans, norm, tgltrans, dokter);

    $("#norm").val(norm);
    $("#nama").val(nama);
    $("#jk").val(jk);
    $("#umur").val(umur);
    $("#dokter").trigger("change");
    $("#alamat").val(alamat);
    $("#layanan").val(layanan);
    $("#notrans").val(notrans);
    $("#tgltrans, #tgltind").val(tgltrans);

    scrollToInputSection();
}
function checkedPemeriksaan(id) {
    console.log("🚀 ~ checkedPemeriksaan ~ id:", id);
    const element = document.getElementById(id);
    element.checked = true;
}

function cariTagihan(norm, tgl) {
    $("#loadingSpinner").show();
    try {
        $.ajax({
            url: "/api/tagihan",
            method: "POST",
            data: {
                norm: norm,
                tgl: tgl,
            },
            success: function (response) {
                console.log(response);
                $("#loadingSpinner").hide();
                const tindakan = response.tindakan;
                const ro = response.ro;
                const lab = response.lab;
                pilihPemeriksaan(tindakan, "igd");
                pilihPemeriksaan(ro, "ro");
                pilihPemeriksaan(lab, "lab");
            },
            error: function (xhr, status, error) {
                console.log(error);
                $("#loadingSpinner").hide();
            },
        });
    } catch (error) {
        console.log(error);
        $("#loadingSpinner").hide();
    }
}
function pilihPemeriksaan(data, ruang) {
    //checked pemeriksaan sesuai forech data
    if (data && Array.isArray(data)) {
        data.forEach((item) => {
            let checkbox;
            let id;
            switch (ruang) {
                case "lab":
                    id = item.kdPemeriksaan;
                    checkbox = document.getElementById(id);
                    break;
                case "ro":
                    id = item.kdFoto;
                    checkbox = document.querySelector(`input[kdFoto="${id}"]`);
                    break;
                case "igd":
                    id = item.kdTind;
                    checkbox = document.querySelector(`input[kdtind="${id}"]`);
                    break;
                default:
                    break;
            }
            if (checkbox) {
                checkbox.checked = true;
            }
        });
    } else {
        console.error("Data pemeriksaan tidak ditemukan atau bukan array.");
    }
}

function antrianKasir(ruang) {
    $("#loadingSpinner").show();
    const tanggal = $("#tanggal").val();

    fetchDataAntrianAll(tanggal, ruang, function (response) {
        $("#loadingSpinner").hide();
        const { data, filteredData } = processResponse(
            response,
            ruang,
            "Sudah Selesai"
        );
        const pulang = processResponse(response, ruang, "Sudah Pulang");
        const belumInput = pulang.filteredPulang;

        initializeDataTable(
            "#antrianall",
            data,
            getColumnDefinitions("status_pulang", ruang)
        );
        initializeDataTable(
            "#dataSelesai",
            filteredData,
            getColumnDefinitions("status")
        );
        initializeDataTable(
            "#dataAntrian",
            belumInput,
            getColumnDefinitions("status")
        );
    });
}

function tabelPemeriksaan(itemPemeriksaan, item, pilihSemuaId) {
    if ($.fn.DataTable.isDataTable("#tabelPemeriksaan")) {
        table.clear().destroy();
    }

    table = $("#tabelPemeriksaan").DataTable({
        data: itemPemeriksaan,
        columns: [
            {
                data: null,
                render: function (data, type, row) {
                    return `<input type="checkbox" class="select-checkbox mt-2 data-checkbox ${item}" kdFoto="${row.kdFoto}" kdTInd="${row.kdTind}" id="${row.idLayanan}">`;
                },
            },
            {
                data: "nmLayanan",
                render: function (data, type, row) {
                    return `<label type="text" class="form-check-label mt-1" for="${row.idLayanan}" style="font-size: medium;">${data}</label>`;
                },
            },
            {
                data: "tarif",
                render: function (data, type, row) {
                    var formattedTarif = parseInt(data).toLocaleString(
                        "id-ID",
                        {
                            style: "currency",
                            currency: "IDR",
                            minimumFractionDigits: 0,
                        }
                    );
                    return `<label type="text" class="form-check-label mt-1" for="${row.idLayanan}" style="font-size: medium;">${formattedTarif}</label>`;
                },
            },
        ],
        // order: [1, "asc"],
        scrollY: "400px",
        paging: false,
        // responsive: true,
    });
}

function handlePilihSemuaClick(pilihSemuaId, checkboxClass) {
    const pilihSemuaCheckbox = document.getElementById(pilihSemuaId);

    pilihSemuaCheckbox.addEventListener("change", function () {
        const isChecked = this.checked;
        const checkboxes = $("." + checkboxClass);

        checkboxes.prop("checked", isChecked);
    });
}
