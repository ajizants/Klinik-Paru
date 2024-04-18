async function addPasienTB() {
    var norm = $("#modal-norm").val();
    var hp = $("#modal-hp").val();
    var tcm = $("#modal-tcm").val();
    var dx = $("#modal-kdDx").val();
    var mulai = $("#modal-tglmulai").val();
    var bb = $("#modal-bb").val();
    var terapi = $("#modal-obtDots").val();
    var hiv = $("#modal-hiv").val();
    var dm = $("#modal-dm").val();
    var ket = $("#modal-ket").val();
    // var status = $("#modal-").val();
    var petugas = $("#modal-petugas").val();
    var dokter = $("#modal-dokter").val();
    if (
        !norm ||
        !hp ||
        !tcm ||
        !dx ||
        !mulai ||
        !bb ||
        !terapi ||
        !petugas ||
        !dokter
    ) {
        var dataKurang = [];
        if (!hp) dataKurang.push("No HP Belum Diisi");
        if (!petugas) dataKurang.push("No petugas Belum Diisi");

        Swal.fire({
            icon: "error",
            title: "Data Tidak Lengkap...!!! " + dataKurang.join(", "),
        });
    } else {
        $.ajax({
            url: "/api/addPTB",
            type: "POST",
            data: {
                norm: norm,
                hp: hp,
                tcm: tcm,
                dx: dx,
                mulai: mulai,
                bb: bb,
                terapi: terapi,
                hiv: hiv,
                dm: dm,
                ket: ket,
                petugas: petugas,
                dokter: dokter,
            },

            success: function (response) {
                Toast.fire({
                    icon: "success",
                    title: "Data Berhasil Disimpan, Maturnuwun...!!!",
                });
                pasienTB();
                resetForm();
            },
            error: function (xhr) {
                Toast.fire({
                    icon: "error",
                    title: "Data Tidak Lengkap...!!!",
                });
            },
        });
    }
}

async function addKunjunganDots() {
    var notrans = $("#notrans").val();
    var tgltrans = $("#tglKunj").val();
    var nxKontrol = $("#nxKontrol").val();
    var norm = $("#norm").val();
    var bta = $("#bta").val();
    var bb = $("#bb").val();
    var terapi = $("#obatDots").val();
    var blnKe = $("#blnKe").val();
    var petugas = $("#petugas").val();
    var dokter = $("#dokter").val();
    // Memeriksa apakah ada nilai yang kosong
    if (!norm || !notrans || !blnKe || !petugas || !dokter) {
        // Menampilkan notifikasi jika ada nilai yang kosong
        var dataKurang = [];
        if (!norm) dataKurang.push("Nomor Rekam Medis");
        if (!notrans) dataKurang.push("Nomor Transaksi");
        if (!blnKe) dataKurang.push("Status Pengobatan Bulan Keberapa?");
        if (!petugas) dataKurang.push("Petugas");
        if (!dokter) dataKurang.push("Dokter");

        // Menampilkan notifikasi menggunakan Toast.fire
        Toast.fire({
            icon: "error",
            title:
                "Data Tidak Lengkap...!!! " +
                dataKurang.join(", ") +
                "Belum Diisi..!!",
        });
    } else {
        $.ajax({
            url: "/api/simpanDots",
            type: "POST",
            data: {
                norm: norm,
                notrans: notrans,
                bta: bta,
                bb: bb,
                blnKe: blnKe,
                nxKontrol: nxKontrol,
                terapi: terapi,
                petugas: petugas,
                dokter: dokter,
            },

            success: function (response) {
                Toast.fire({
                    icon: "success",
                    title: "Data Berhasil Disimpan, Maturnuwun...!!!",
                });
                document.getElementById("formKunjungan").reset();
                document.getElementById("formTBbaru").reset();

                document.getElementById("tglKunj").valueAsDate = new Date();
                $("#formKunjungan select").trigger("change");
                $("#kunjDots").DataTable().destroy();
                showKunjungan();
            },
            error: function (xhr) {
                Toast.fire({
                    icon: "error",
                    title: "Data Tidak Lengkap...!!!",
                });
            },
        });
    }
}
async function showKunjungan() {
    $("#loadingSpinner").show();
    if ($.fn.DataTable.isDataTable("#kunjDots")) {
        var table = $("#kunjDots").DataTable();
        table.destroy();
    }

    $.ajax({
        url: "/api/kunjunganDots",
        type: "POST",
        data: {
            norm: norm,
        },
        success: function (response) {
            $("#loadingSpinner").hide();
            var dataArray = response.data || [];
            dataArray.forEach(function (item, index) {
                item.pasien = `${item.biodata.nama}`;
                item.actions = `<a class="editTB"
                                data-id="${item.id}"
                                data-norm="${item.norm}"
                                ><i class="fas fa-pen-to-square pr-3"></i></a>`;
                item.petugas = `${item.petugas.gelar_d} ${item.petugas.biodata.nama} ${item.petugas.gelar_b}`;
                item.dokter = `${item.dokter.gelar_d} ${item.dokter.biodata.nama} ${item.dokter.gelar_b}`;
            });

            $("#kunjDots")
                .DataTable({
                    data: dataArray,
                    columns: [
                        { data: "actions", className: "col-1 text-center" },
                        { data: "norm" },
                        {
                            data: "created_at",
                            render: function (data) {
                                // Format the date using JavaScript
                                const formattedDate = new Date(
                                    data
                                ).toLocaleString("id-ID", {
                                    year: "numeric",
                                    month: "numeric",
                                    day: "numeric",
                                });
                                return formattedDate;
                            },
                        },
                        { data: "blnKe" },
                        { data: "bta" },
                        { data: "terapi" },
                        { data: "petugas" },
                        { data: "dokter" },
                    ],
                    order: [2, "dsc"],
                    paging: true,
                    lengthMenu: [
                        [5, 10, 25, 50, -1],
                        [5, 10, 25, 50, "All"],
                    ],
                    pageLength: 5,
                    responsive: true,
                    lengthChange: false,
                    autoWidth: false,
                    buttons: ["copyHtml5", "excelHtml5", "pdfHtml5", "colvis"],
                })
                .buttons()
                .container()
                .appendTo("#kunjDots_wrapper .col-md-6:eq(0)");

            console.log("🚀 ~ table generate");
            // Menangani klik pada tombol edit
            $(".editTB").on("click", function (e) {
                e.preventDefault();
                var norm = $(this).data("norm");
                var date = $("#tanggal").val();
                console.log("🚀 ~ date:", date);
                console.log("🚀 ~ norm:", norm);
                performCariRM(norm);
                // searchPTB(norm);
            });
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        },
    });
}
function resetForm() {
    document.getElementById("formIdentitas").reset();
    document.getElementById("formKunjungan").reset();
    document.getElementById("formTBbaru").reset();

    document.getElementById("tglKunj").valueAsDate = new Date();
    $("#formKunjungan select").trigger("change");
    $("#formTBbaru select").trigger("change");
    $("#dataFarmasi").DataTable().destroy();
    scrollToAntrianSection();
}
function transaksiBatal() {
    resetForm();
    Toast.fire({
        icon: "success",
        title: "Transaksi Dibatalkan, Maturnuwun...!!!",
    });
}
function transaksiSelesai() {
    if (!norm || !notrans) {
        Toast.fire({
            icon: "error",
            title: "Belum Ada Data Transaksi...!!! ",
        });
        scrollToAntrianSection();
    } else {
        resetForm();
        Toast.fire({
            icon: "success",
            title: "Transaksi Berhasil Disimpan, Maturnuwun...!!!",
        });
    }
}

function antrianKontrol() {
    $("#ddo").hide();
    $("#dtelat").hide();
    $("#dtb").hide();
    $("#dselesai").hide();
    $("#dkontrol").show();
}
function antrianTelat() {
    $("#ddo").hide();
    $("#dtb").hide();
    $("#dkontrol").hide();
    $("#dselesai").hide();
    $("#dtelat").show();
}
function antrianDo() {
    $("#dtelat").hide();
    $("#dtb").hide();
    $("#dkontrol").hide();
    $("#dselesai").hide();
    $("#ddo").show();
}
function antrianTb() {
    $("#ddo").hide();
    $("#dtelat").hide();
    $("#dkontrol").hide();
    $("#dselesai").hide();
    $("#dtb").show();
}
function antrianToday() {
    $("#ddo").hide();
    $("#dtelat").hide();
    $("#dkontrol").hide();
    $("#dtb").hide();
    $("#dselesai").show();
}

function updateAntrian() {
    pasienKontrol();
    pasienDo();
    pasienTB();
    pasienTelat();
    antrianAll();
}
function handleKeyUp(event) {
    if (event.key === "Enter") {
        cariPasien();
    }
}
function cariPasien() {
    // Corrected the usage of getElementById and value()
    let norm = document.getElementById("norm").value;
    let date = document.getElementById("tanggal").value;
    console.log("🚀 ~ cariPasien ~ norm:", norm);
    console.log("🚀 ~ date:", date);

    // Assuming formatNorm and searchByRM are defined elsewhere in your code
    formatNorm(norm);
    searchByRM($("#norm").val(), date);
}

$(document).ready(function () {
    scrollToAntrianSection();
    antrianKontrol();

    $("#tanggal").on("change", updateAntrian);

    $("#cariantrian").on("click", updateAntrian);

    $(".select2bs4").select2();
    $("#modal-pasienTB .select2bs4").select2();

    $("#modal-Ftb").on("click", function (e) {
        populateBlnKeOptions();
        populateDokterOptions();
        populateObat();
        populatePetugasOptions();
        populateDxMedis();
    });

    $("#modal-pasienTB #modal-norm").on("keyup", function (event) {
        if (event.key === "Enter") {
            event.preventDefault();

            formatNorm($("#modal-pasienTB #modal-norm"));
            performCariRMmodal($("#modal-pasienTB #modal-norm").val());
        }
    });

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"), // Mengirim token CSRF untuk perlindungan keamanan
        },
    });

    $("#antrianall").on("click", ".aksi-button", function (e) {
        e.preventDefault();
        var norm = $(this).data("norm");
        var date = $("#tanggal").val();
        console.log("🚀 ~ date:", date);
        console.log("🚀 ~ norm:", norm);
        searchByRM(norm, date);
        scrollToInputSection();
    });

    // $("#modal-pasienTB #addPTB").on("click", function (e) {
    //     e.preventDefault();
    //     var norm = $("#modal-norm").val();
    //     var hp = $("#modal-hp").val();
    //     var tcm = $("#modal-tcm").val();
    //     var dx = $("#modal-kdDx").val();
    //     var mulai = $("#modal-tglmulai").val();
    //     var bb = $("#modal-bb").val();
    //     var terapi = $("#modal-obtDots").val();
    //     var hiv = $("#modal-hiv").val();
    //     var dm = $("#modal-dm").val();
    //     var ket = $("#modal-ket").val();
    //     // var status = $("#modal-").val();
    //     var petugas = $("#modal-petugas").val();
    //     var dokter = $("#modal-dokter").val();
    //     if (
    //         !norm ||
    //         !hp ||
    //         !tcm ||
    //         !dx ||
    //         !mulai ||
    //         !bb ||
    //         !terapi ||
    //         !petugas ||
    //         !dokter
    //     ) {
    //         var dataKurang = [];
    //         if (!hp) dataKurang.push("No HP Belum Diisi");
    //         if (!petugas) dataKurang.push("No petugas Belum Diisi");

    //         Swal.fire({
    //             icon: "error",
    //             title: "Data Tidak Lengkap...!!! " + dataKurang.join(", "),
    //         });
    //     } else {
    //         $.ajax({
    //             url: "/api/addPTB",
    //             type: "POST",
    //             data: {
    //                 norm: norm,
    //                 hp: hp,
    //                 tcm: tcm,
    //                 dx: dx,
    //                 mulai: mulai,
    //                 bb: bb,
    //                 terapi: terapi,
    //                 hiv: hiv,
    //                 dm: dm,
    //                 ket: ket,
    //                 // status: status,
    //                 petugas: petugas,
    //                 dokter: dokter,
    //             },

    //             success: function (response) {
    //                 Toast.fire({
    //                     icon: "success",
    //                     title: "Data Berhasil Disimpan, Maturnuwun...!!!",
    //                 });
    //                 pasienTB();
    //                 $(
    //                     "#modal-norm ,#modal-hp ,#modal-tcm,#modal-kdDx,#modal-tglmulai,#modal-bb ,#modal-obtDots ,#modal-hiv,#modal-dm,#modal-ket ,#modal-petugas, #modal-dokter"
    //                 ).val("");
    //                 $("#obat").trigger("change");
    //             },
    //             error: function (xhr) {
    //                 Toast.fire({
    //                     icon: "error",
    //                     title: "Data Tidak Lengkap...!!!",
    //                 });
    //             },
    //         });
    //     }
    // });

    setTodayDate();
    pasienKontrol();
    pasienDo();
    pasienTB();
    pasienTelat();
    antrianAll();

    populateObat();
    populateBlnKeOptions();
    populateDokterOptions();
    populatePetugasOptions();
    populateDxMedis();
});
