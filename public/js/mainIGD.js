function scrollToInputBMHPSection() {
    $("html, body").animate({ scrollTop: $("#formbmhp").offset().top }, 500);
}
var Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
});
function hitungTotalHarga() {
    var nilaiJual = $("#jual").val();
    nilaiJual = nilaiJual.replace(/[.,]/g, "");
    var hargaJual = parseFloat(nilaiJual) || 0;
    var qty = parseFloat($("#qty").val()) || 0;
    var totalharga = hargaJual * qty;
    $("#total").val(totalharga); // Menampilkan total harga dengan pemisah ribuan
}

async function searchRMObat() {
    let currentDate = new Date();

    // Mendapatkan nilai tahun, bulan, hari, jam, menit, dan detik
    let year = currentDate.getFullYear();
    let month = String(currentDate.getMonth() + 1).padStart(2, "0"); // Perhatikan bahwa getMonth() mengembalikan nilai mulai dari 0
    let day = String(currentDate.getDate()).padStart(2, "0");
    let hours = String(currentDate.getHours()).padStart(2, "0");
    let minutes = String(currentDate.getMinutes()).padStart(2, "0");
    let seconds = String(currentDate.getSeconds()).padStart(2, "0");

    // Menggabungkan nilai-nilai tersebut menjadi format yang diinginkan
    let formattedDate = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;

    console.log(formattedDate);

    Swal.fire({
        icon: "info",
        title: "Sedang mencari data...!!!",
        allowOutsideClick: false,
        showConfirmButton: false,
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
                allowOutsideClick: false,
                showConfirmButton: false,
                timer: 1500,
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
            $("#tgltrans").val(formattedDate);
            $("#layanan").val("UMUM");
            $("#dokter").val("198907252019022004").trigger("change");
            $("#apoteker").val("197609262011012003").trigger("change");

            // Additional function calls as needed
            dataTindakan();
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
function addBmhp() {
    // Get data from input fields
    var idTind = $("#modalidTind").val();
    var kdTind = $("#modalkdTind").val();
    var kdBmhp = $("#bmhp").val();
    var jumlah = $("#qty").val();
    var total = $("#total").val();
    var notrans = $("#notrans").val();
    var productID = $("#productID").val();

    // Memeriksa apakah ada nilai yang kosong
    if (!kdBmhp || !jumlah) {
        // Menampilkan notifikasi jika ada nilai yang kosong
        var dataKurang = [];
        if (!kdBmhp) dataKurang.push("BMHP Belum Diisi");
        if (!jumlah) dataKurang.push("jumlah Belum Diisi");

        // Menampilkan notifikasi menggunakan Toast.fire
        Swal.fire({
            icon: "error",
            title: "Data Tidak Lengkap...!!! " + dataKurang.join(", "),
        });
    } else {
        // Send AJAX POST request to Laravel route
        $.ajax({
            type: "POST",
            url: "/api/addTransaksiBmhp", // Replace with your Laravel route
            data: {
                idTind: idTind,
                kdTind: kdTind,
                kdBmhp: kdBmhp,
                jml: jumlah,
                total: total,
                notrans: notrans,
                productID: productID,
            },
            success: function (response) {
                Toast.fire({
                    icon: "success",
                    title: "Data Berhasil Disimpan, Maturnuwun...!!!",
                });
                dataBMHP();
                $("#bmhp, #qty").val("");
                $("#bmhp").trigger("change");
                quantity = 0;
                console.log(quantity);
                dataTindakan();
            },
            error: function (error) {
                // Handle error response here
                console.log(error);
            },
        });
    }
}

let tanggalFormat;
let kdtgl;
function setTglRo() {
    var inputTanggal = document.getElementById("tgltrans");
    var tanggalHariIni = new Date();

    var tahun = tanggalHariIni.getFullYear();
    var bulan = String(tanggalHariIni.getMonth() + 1).padStart(2, "0");
    var tanggal = String(tanggalHariIni.getDate()).padStart(2, "0");

    tanggalFormat = tahun + "-" + bulan + "-" + tanggal;
    kdtglFormat = tahun + "-" + bulan + "-" + tanggal;

    inputTanggal.value = tanggalFormat;
    kdtgl = kdtglFormat.replace(/-/g, "");
}

function batal() {
    $(
        "#norm, #nama, #alamat, #layanan,#tglTind,#tglTrans, #notrans, #dokter, #petugas, #tindakan, #asktind, #bmhp, #qty, #modalidTind, #modalkdTind, #modalnorm, #modaltindakan, #modaldokter, #modalpetugas"
    ).val("");

    $("#dokter, #petugas, #tindakan, #bmhp, #qty").trigger("change");

    var tabletindakan = $("#dataTindakan").DataTable();
    tabletindakan.clear().destroy();
    var tablebmhp = $("#transaksiBMHP").DataTable();
    tablebmhp.clear().destroy();
    antrian();
    quantity = 0;
    scrollToTop();
    $("#formbmhp").hide();
    $("#formtind").show();
}

function selesai() {
    var norm = $("#norm").val();
    var notrans = $("#notrans").val();
    // Memeriksa apakah ada nilai yang kosong
    if (!norm || !notrans) {
        // Menampilkan notifikasi jika ada nilai yang kosong
        var dataKurang = [];
        if (!norm || !notrans) dataKurang.push("Belum Ada Data Transaksi");

        Toast.fire({
            icon: "error",
            title: "Data Tidak Lengkap...!!! " + dataKurang.join(", "),
        });
        scrollToTop();
    } else {
        $(
            "#norm, #nama, #alamat, #layanan, #notrans, #dokter, #petugas, #tindakan, #asktind, #bmhp, #qty, #modalidTind, #modalkdTind, #modalnorm, #modaltindakan, #modaldokter, #modalpetugas"
        ).val("");
        $("#dokter, #petugas, #tindakan, #bmhp").trigger("change");

        var tabletindakan = $("#dataTindakan").DataTable();
        tabletindakan.clear().destroy();
        var tablebmhp = $("#transaksiBMHP").DataTable();
        tablebmhp.clear().destroy();
        quantity = 0;
        antrian();
        scrollToTop();
        $("#formbmhp").hide();
        $("#formtind").show();
        Toast.fire({
            icon: "success",
            title: "Transaksi Berhasil Disimpan, Maturnuwun...!!!",
        });
    }
}

function updateAntrian() {
    antrian();
    antrianAll("igd");
}

function addTindakan() {
    var norm = $("#norm").val();
    var notrans = $("#notrans").val();
    var kdTind = $("#tindakan").val();
    var petugas = $("#petugas").val();
    var dokter = $("#dokter").val();
    var tgltrans = $("#tgltrans").val();
    // Memeriksa apakah ada nilai yang kosong
    if (!norm || !notrans || !kdTind || !petugas || !dokter || !tgltrans) {
        // Menampilkan notifikasi jika ada nilai yang kosong
        var dataKurang = [];
        if (!norm) dataKurang.push("Nomor Rekam Medis");
        if (!notrans) dataKurang.push("Nomor Transaksi");
        if (!kdTind) dataKurang.push("Tindakan");
        if (!petugas) dataKurang.push("Petugas");
        if (!dokter) dataKurang.push("Dokter");
        if (!tgltrans) dataKurang.push("Tanggal Transaksi");

        // Menampilkan notifikasi menggunakan Toast.fire
        swal.fire({
            icon: "error",
            title:
                "Data Tidak Lengkap...!!! " +
                dataKurang.join(", ") +
                " Belum Diisi, diisi disit mbok..!!!",
        });
    } else {
        $.ajax({
            url: "/api/simpanTindakan",
            type: "POST",
            data: {
                notrans: notrans,
                kdTind: kdTind,
                petugas: petugas,
                dokter: dokter,
                norm: norm,
                tgltrans: tgltrans,
            },

            success: function (response) {
                Toast.fire({
                    icon: "success",
                    title: "Data Berhasil Disimpan, Maturnuwun...!!!",
                });
                dataTindakan();
                $("#tindakan,#petugas").val("");
                $("#tindakan,#petugas").trigger("change");
            },
            error: function (xhr) {
                swal.fire({
                    icon: "error",
                    title: "Data Tidak Lengkap...!!!",
                });
            },
        });
    }
}
$(document).ready(function () {
    setTglRo();
    $(".select2bs4").select2({ theme: "bootstrap4" });
    $(".bmhp").select2({ theme: "bootstrap4" });
    // $("#tanggal").on("change", function () {
    //     antrian();
    //     antrianAll();
    // });

    // $("#cariantrian").on("click", function () {
    //     antrian();
    //     antrianAll();
    // });

    // $("#formbmhp").hide();

    let quantity = 0;
    const qtyInput = document.getElementById("qty");
    const increaseBtn = document.getElementById("increaseBtn");
    const decreaseBtn = document.getElementById("decreaseBtn");
    // Menambahkan event listener untuk penambahan qty
    increaseBtn.addEventListener("click", function () {
        quantity++;
        qtyInput.value = quantity;
        hitungTotalHarga();
    });

    // Menambahkan event listener untuk pengurangan qty
    decreaseBtn.addEventListener("click", function () {
        if (quantity > 1) {
            quantity--;
            qtyInput.value = quantity;
        } else {
            $("#qty").val("");
        }
        hitungTotalHarga();
    });

    setTodayDate();
    populateTindakanOptions();
    populateDokterOptions();
    populatePetugasOptions();
    populateBmhpOptions();
    updateAntrian();
    setInterval(function () {
        updateAntrian();
    }, 150000);

    $("#norm").on("keyup", function (event) {
        if (event.key === "Enter") {
            event.preventDefault();
            // searchByRM($("#norm").val());
            var tgl = $("#tgltrans").val();
            var norm = $("#norm").val();
            cariKominfo(norm, tgl);
        }
    });
    $("#qty").on("keyup", function (event) {
        if (event.key === "Enter") {
            event.preventDefault();
            addBmhp();
        }
    });

    $("#dataAntrian").on("click", ".aksi-button", function (e) {
        e.preventDefault();

        var norm = $(this).data("norm");
        var nama = $(this).data("nama");
        var dokter = $(this).data("kddokter");
        var alamat = $(this).data("alamat");
        var layanan = $(this).data("layanan");
        var notrans = $(this).data("notrans");
        var tgltrans = $(this).data("tgltrans");
        var tgl = $(this).data("tgl");
        var asktind = $(this).data("asktind");

        $("#norm").val(norm);
        $("#nama").val(nama);
        $("#dokter").val(dokter);
        $("#dokter").trigger("change");
        $("#alamat").val(alamat);
        $("#layanan").val(layanan);
        $("#notrans").val(notrans);
        $("#tgltrans").val(tgltrans);
        $("#tgltind").val(tgl);
        $("#asktind").val(asktind);

        scrollToInputSection();
        dataTindakan();
    });

    $("#dataTindakan").on("click", ".edit", function (e) {
        e.preventDefault();
        var id = $(this).data("id");
        var kdtind = $(this).data("kdtind");
        var norm = $(this).data("norm");
        var tindakan = $(this).data("tindakan");
        var petugas = $(this).data("petugas");
        var dokter = $(this).data("dokter");

        $("#modalidTind").val(id);
        $("#modalkdTind").val(kdtind);
        $("#modalnorm").val(norm);
        $("#modaltindakan").val(tindakan);
        $("#modaldokter").val(dokter);
        $("#modalpetugas").val(petugas);
        $("#formbmhp").show();
        $("#formtind").hide();
        dataBMHP();
        scrollToInputBMHPSection();
    });

    $("#dataTindakan").on("click", ".delete", function (e) {
        e.preventDefault();

        var id = $(this).data("id");
        var tindakan = $(this).data("tindakan");
        if (
            confirm(
                "Apakah Anda yakin ingin menghapus tindakan " + tindakan + " ?"
            )
        ) {
            $.ajax({
                url: "/api/deleteTindakan",
                type: "POST",
                data: { id: id },
                success: function (response) {
                    Toast.fire({
                        icon: "success",
                        title: "Data tindakan berhasil dihapus, Maturnuwun...!!!",
                    });

                    dataTindakan();
                    dataBMHP();
                },
                error: function (xhr, status, error) {
                    Toast.fire({
                        icon: "success",
                        title: error + ", Maturnuwun...!!!",
                    });
                    console.error("Error:", error);
                },
            });
        }
    });

    $("#addBMHP").on("click", function () {
        // Get data from input fields
        var idTind = $("#modalidTind").val();
        var kdTind = $("#modalkdTind").val();
        var kdBmhp = $("#bmhp").val();
        var jumlah = $("#qty").val();
        var total = $("#total").val();
        var notrans = $("#notrans").val();
        var productID = $("#productID").val();

        // Memeriksa apakah ada nilai yang kosong
        if (!kdBmhp || !jumlah) {
            // Menampilkan notifikasi jika ada nilai yang kosong
            var dataKurang = [];
            if (!kdBmhp) dataKurang.push("BMHP Belum Diisi");
            if (!jumlah) dataKurang.push("jumlah Belum Diisi");

            // Menampilkan notifikasi menggunakan Toast.fire
            Swal.fire({
                icon: "error",
                title: "Data Tidak Lengkap...!!! " + dataKurang.join(", "),
            });
        } else {
            // Send AJAX POST request to Laravel route
            $.ajax({
                type: "POST",
                url: "/api/addTransaksiBmhp", // Replace with your Laravel route
                data: {
                    idTind: idTind,
                    kdTind: kdTind,
                    kdBmhp: kdBmhp,
                    jml: jumlah,
                    total: total,
                    notrans: notrans,
                    productID: productID,
                },
                success: function (response) {
                    Toast.fire({
                        icon: "success",
                        title: "Data Berhasil Disimpan, Maturnuwun...!!!",
                    });
                    dataBMHP();
                    $("#bmhp, #qty").val("");
                    $("#bmhp").trigger("change");
                    quantity = 0;
                    dataTindakan();
                },
                error: function (error) {
                    // Handle error response here
                    console.log(error);
                },
            });
        }
    });

    $("#addBMHPSelesai").on("click", function (e) {
        e.preventDefault();

        $(
            "#bmhp, #qty, #modalidTind, #modalkdTind, #modalnorm, #modaltindakan, #modaldokter, #modalpetugas"
        ).val("");
        $("#bmhp, #qty").trigger("change");

        var tablebmhp = $("#transaksiBMHP").DataTable();
        tablebmhp.clear().destroy();

        dataTindakan();
        scrollToInputSection();
        $("#formbmhp").hide();
        $("#formtind").show();
        Toast.fire({
            icon: "success",
            title: "Data BMHP Berhasil Disimpan, Maturnuwun...!!!",
        });
    });

    $("#transaksiBMHP").on("click", ".delete", function (e) {
        e.preventDefault();

        var id = $(this).data("id");
        var bmhp = $(this).data("bmhp");
        console.log(id);
        console.log(bmhp);
        if (confirm("Apakah Anda yakin ingin menghapus " + bmhp + " ?")) {
            $.ajax({
                url: "/api/deleteTransaksiBmhp",
                type: "POST",
                data: { id: id },
                success: function (response) {
                    Toast.fire({
                        icon: "success",
                        title: "Data BMHP berhasil dihapus, Maturnuwun...!!!",
                    });
                    dataBMHP();
                },
                error: function (xhr, status, error) {
                    swal.fire({
                        icon: "error",
                        title: error + "...!!!",
                    });
                    console.error("Error:", error);
                },
            });
        }
    });

    selamatBertugas();
});

$(document).on("select2:open", () => {
    document.querySelector(".select2-search__field").focus();
});
