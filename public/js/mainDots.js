async function addPasienTB() {
    var norm = $("#modal-norm").val();
    var nik = $("#modal-nik").val();
    var nama = $("#modal-nama").val();
    var alamat = $("#modal-alamat").val();
    var hp = $("#modal-hp").val();
    var tcm = $("#modal-tcm").val();
    var dx = $("#modal-kdDx").val();
    var mulai = $("#modal-tglmulai").val();
    var bb = $("#modal-bb").val();
    var terapi = $("#modal-obtDots").val();
    var hiv = $("#modal-hiv").val();
    var dm = $("#modal-dm").val();
    var ket = $("#modal-ket").val();
    var blnKe = $("#modal-pasienTB #modal-blnKe").val();
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
        // console.log("siap tambah pasien tb");
        $.ajax({
            url: "/api/tambah/pasien/TB",
            type: "POST",
            data: {
                norm: norm,
                nama: nama,
                nik: nik,
                alamat: alamat,
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
                hasilBerobat: blnKe,
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

function validasiKunjungan() {
    // console.log("Validasi kunjungan dimulai");
    var inputsToValidate = [
        "notrans",
        "tglKunj",
        "nxKontrol",
        "norm",
        // "bta",
        "bb",
        "obatDots",
        "blnKe",
        "petugas",
        "dokter",
    ];

    var error = false;

    inputsToValidate.forEach(function (inputId) {
        var inputElement = document.getElementById(inputId);
        var inputValue = inputElement.value.trim();

        if (inputValue === "") {
            if ($(inputElement).hasClass("select2-hidden-accessible")) {
                // Select2 element
                $(inputElement)
                    .next(".select2-container")
                    .addClass("input-error");
            } else {
                // Regular input element
                inputElement.classList.add("input-error");
            }
            error = true;
        } else {
            if ($(inputElement).hasClass("select2-hidden-accessible")) {
                // Select2 element
                $(inputElement)
                    .next(".select2-container")
                    .removeClass("input-error");
            } else {
                // Regular input element
                inputElement.classList.remove("input-error");
            }
        }
    });
    if (error) {
        // Tampilkan pesan error menggunakan Swal jika ada input yang kosong
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Ada data yang masih kosong! Mohon lengkapi semua data.",
        });
    } else {
        // Lakukan pengiriman data atau proses selanjutnya jika semua data valid
        simpanKunjungan(); // Contoh: Panggil fungsi simpan() jika semua data valid
    }
}

async function simpanKunjungan() {
    try {
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
        // Membuat objek FormData untuk mengirim data dengan file
        var formData = new FormData();
        formData.append("notrans", notrans);
        formData.append("norm", norm);
        formData.append("tgltrans", tgltrans);
        formData.append("bta", bta);
        formData.append("bb", bb);
        formData.append("blnKe", blnKe);
        formData.append("nxKontrol", nxKontrol);
        formData.append("terapi", terapi);
        formData.append("petugas", petugas);
        formData.append("dokter", dokter);
        // console.log("🚀 ~ simpanKunjungan ~ formData:", formData);

        // Kirim data menggunakan fetch API dengan async/await
        const response = await fetch("/api/simpan/kunjungan/dots", {
            method: "POST",
            body: formData,
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || response.statusText);
        }

        const responseData = await response.json();
        console.log("Data berhasil disimpan:", responseData);

        Swal.fire({
            icon: "success",
            title: "Data berhasil disimpan,\n \n" + "Maturnuwun...!!",
        });

        resetFormTs();
        showRiwayatKunjungan(norm);
    } catch (error) {
        console.error("Terjadi kesalahan saat menyimpan data:", error.massage);

        // Display error message using SweetAlert
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Terjadi kesalahan saat menyimpan data: " + error.message,
        });
    }
}
async function simpanKunjungan2() {
    try {
        var norm = $("#modal-pasienTB #modal-norm").val();
        var notrans = $("#modal-pasienTB #modal-notrans").val();
        // var tgltrans = $("#modal-pasienTB #modal-tglmulai").val();
        // var tgltrans = $("#modal-pasienTB #modal-tglKunj").val();
        var tgltrans = $("#tglKunj").val();
        var nxKontrol = $("#modal-pasienTB #modal-nxKontrol").val();
        var bta = $("#modal-pasienTB #modal-bta").val();
        var bb = $("#modal-pasienTB #modal-bb").val();
        var terapi = $("#modal-pasienTB #modal-obtDots").val();
        var blnKe = $("#modal-pasienTB #modal-blnKe").val();
        var petugas = $("#modal-pasienTB #modal-petugas").val();
        var dokter = $("#modal-pasienTB #modal-dokter").val();
        // Membuat objek FormData untuk mengirim data dengan file
        var formData = new FormData();
        formData.append("notrans", notrans);
        formData.append("norm", norm);
        formData.append("tgltrans", tgltrans);
        formData.append("bta", bta);
        formData.append("bb", bb);
        formData.append("blnKe", blnKe);
        formData.append("nxKontrol", nxKontrol);
        formData.append("terapi", terapi);
        formData.append("petugas", petugas);
        formData.append("dokter", dokter);
        // console.log("🚀 ~ simpanKunjungan2 ~ formData:", formData);

        // Kirim data menggunakan fetch API dengan async/await
        const response = await fetch("/api/simpan/kunjungan/dots", {
            method: "POST",
            body: formData,
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || response.statusText);
        }

        const responseData = await response.json();
        console.log("Data berhasil disimpan:", responseData);

        Swal.fire({
            icon: "success",
            title: "Data berhasil disimpan,\n \n" + "Maturnuwun...!!",
        });

        // resetFormTs();
        // showRiwayatKunjungan(norm);
    } catch (error) {
        console.error("Terjadi kesalahan saat menyimpan data:", error.massage);

        // Display error message using SweetAlert
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Terjadi kesalahan saat menyimpan data: " + error.message,
        });
    }
}
function resetForm() {
    document.getElementById("formIdentitas").reset();
    document.getElementById("formKunjungan").reset();
    document.getElementById("formTBbaru").reset();

    document.getElementById("tglKunj").valueAsDate = new Date();
    $("#formKunjungan select").trigger("change");
    $("#formTBbaru select").trigger("change");

    if ($.fn.DataTable.isDataTable("#kunjDots")) {
        console.log("🚀 ~ resetForm ~ DataTable");
        var table = $("#kunjDots").DataTable();
        table.clear().draw();
    }
    scrollToTop();
}
function resetFormTs() {
    console.log("Mereset form");
    document.getElementById("formKunjungan").reset();
    document.getElementById("formTBbaru").reset();

    $("#formKunjungan select").trigger("change");
    $("#formTBbaru select").trigger("change");
}
function batal() {
    resetForm();
    Toast.fire({
        icon: "success",
        title: "Transaksi Dibatalkan, Maturnuwun...!!!",
    });
}
function selesai() {
    if (!norm || !notrans) {
        Toast.fire({
            icon: "error",
            title: "Belum Ada Data Transaksi...!!! ",
        });
    } else {
        resetForm();
        Toast.fire({
            icon: "success",
            title: "Transaksi Berhasil Disimpan, Maturnuwun...!!!",
        });
    }
}

function updateAntrian() {
    pasienTB();
    pasienTelat();
    antrianAll("dots");
    antrian("dots");
}

function populate() {
    populateObat();
    populateBlnKeOptions();
    populateDokterOptions();
    populatePetugasOptions();
    populateDxMedis();
}
function handleKeyUp(event) {
    if (event.key === "Enter") {
        cariPasienTb();
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
    scrollToTop();
    $(".select2bs4").select2();
    $("#modal-pasienTB .select2bs4").select2();

    // $("#modal-Ftb").on("click", function (e) {
    //     populateBlnKeOptions();
    //     populateDokterOptions();
    //     populateObat();
    //     populatePetugasOptions();
    //     populateDxMedis();
    // });

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

    setTodayDate();
    updateAntrian();
    populate();
});
