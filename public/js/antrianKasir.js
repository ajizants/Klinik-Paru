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
    riwayat(notrans, norm, tgltrans, dokter);

    $("#norm").val(norm);
    $("#nama").val(nama);
    $("#jk").val(jk);
    $("#umur").val(umur);
    $("#dokter").trigger("change");
    $("#alamat").val(alamat);
    $("#layanan").val(layanan);
    $("#notrans").val(notrans);
    $("#tgltrans, #tgltind").val(tgltrans);

    const alamatPang = $(button).data("alamatpang");
    const umurPang = $(button).data("umurpang");
    var sebutan = "";
    if (umurPang <= 14) {
        sebutan = "Anak ";
    } else if (umurPang > 14 && umurPang <= 30) {
        if (jk == "L") {
            sebutan = "Saudara ";
        } else {
            sebutan = "Nona ";
        }
    } else if (umurPang > 30) {
        if (jk == "L") {
            sebutan = "Bapak ";
        } else {
            sebutan = "Ibu ";
        }
    }
    const text = `${sebutan} ${nama} dari ${alamatPang}, silahkan menuju ke Kasir`;
    console.log("🚀 ~ celuk ~ text:", text);

    $("#divPanggil").html(`
        <button type="button" class="btn btn-warning" onclick="panggil('${text}');">Panggil</button>
        `);

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

function tabelPemeriksaan(itemPemeriksaan, item, pilihSemuaId) {
    // Hapus dan destroy tabel jika sudah diinisialisasi
    if ($.fn.DataTable.isDataTable("#tabelPemeriksaan")) {
        $("#tabelPemeriksaan").DataTable().clear().destroy();
    }

    // Inisialisasi DataTable
    $("#tabelPemeriksaan").DataTable({
        data: itemPemeriksaan,
        columns: [
            {
                data: null,
                render: function (data, type, row) {
                    return `
                        <input
                            type="checkbox"
                            class="select-checkbox mt-2 data-checkbox ${item}"
                            kdFoto="${row.kdFoto}"
                            kdTInd="${row.kdTind}"
                            id="${row.idLayanan}">
                    `;
                },
            },
            {
                data: "nmLayanan",
                render: function (data, type, row) {
                    return `
                        <label
                            for="${row.idLayanan}"
                            class="form-check-label mt-1"
                            style="font-size: medium;">
                            ${data}
                        </label>
                    `;
                },
            },
            {
                data: null, // Tidak langsung mengambil data dari source
                render: function (data, type, row) {
                    return `
                        <input
                            type="number"
                            class="form-control mt-1 col-7 qty"
                            id="qty_${row.idLayanan}"
                            style="font-size: medium;"
                            onchange="hitungTotal('${row.idLayanan}')" value="1">
                    `;
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
                    return `
                        <label
                            for="${row.idLayanan}"
                            class="form-check-label mt-1"
                            style="font-size: medium;">
                            ${formattedTarif}
                        </label>
                        <input type="hidden" id="tarif_${row.idLayanan}" value="${data}">
                    `;
                },
            },
            {
                data: "tarif",
                render: function (data, type, row) {
                    return `
                        <input
                            type="number"
                            class="form-control mt-1"
                            id="harga_${row.idLayanan}"
                            style="font-size: medium;"
                            value="${data}">
                    `;
                },
            },
        ],
        scrollY: "400px",
        order: false,
        paging: false,
    });

    // Inisialisasi handler pilih semua
    handlePilihSemuaClick(pilihSemuaId, `data-checkbox ${item}`);
}

function handlePilihSemuaClick(pilihSemuaId, checkboxClass) {
    const pilihSemuaCheckbox = document.getElementById(pilihSemuaId);

    if (pilihSemuaCheckbox) {
        pilihSemuaCheckbox.addEventListener("change", function () {
            const isChecked = this.checked;
            const checkboxes = $("." + checkboxClass);

            // Centang atau hapus centang semua checkbox
            checkboxes.prop("checked", isChecked);

            // Hitung total untuk semua checkbox yang dicentang
            hitungTotalSemua(checkboxClass);
        });
    } else {
        console.warn(`Checkbox dengan ID "${pilihSemuaId}" tidak ditemukan.`);
    }
}

function hitungTotal(idLayanan) {
    const qtyInput = document.getElementById(`qty_${idLayanan}`);
    const tarifInput = document.getElementById(`tarif_${idLayanan}`);
    const hargaInput = document.getElementById(`harga_${idLayanan}`);

    if (qtyInput && tarifInput && hargaInput) {
        const qty = parseFloat(qtyInput.value) || 0;
        const tarif = parseFloat(tarifInput.value) || 0;
        const total = qty * tarif;
        hargaInput.value = total;
        // hargaInput.value = total.toLocaleString("id-ID", {
        //     style: "currency",
        //     currency: "IDR",
        //     minimumFractionDigits: 0,
        // });
    } else {
        console.warn(
            `Elemen input untuk idLayanan "${idLayanan}" tidak ditemukan.`
        );
    }
}

function hitungTotalSemua(checkboxClass) {
    // Ambil semua checkbox yang dicentang
    const checkboxes = $("." + checkboxClass + ":checked");

    checkboxes.each(function () {
        const idLayanan = $(this).attr("id"); // Ambil ID layanan dari checkbox
        hitungTotal(idLayanan); // Panggil hitungTotal untuk setiap checkbox yang dicentang
    });
}
