document.addEventListener("DOMContentLoaded", function () {
    var tgltindInput = document.getElementById("tgltind");

    function updateDateTime() {
        var now = new Date();
        var options = { timeZone: "Asia/Jakarta" };
        var formattedDate = now.toLocaleString("id-ID", options);
        tgltindInput.value = formattedDate;
    }

    setInterval(updateDateTime, 1000);
});

function scrollToAntrianSection() {
    $("html, body").animate(
        { scrollTop: $("#antrianSection").offset().top },
        500
    );
}

function setTodayDate() {
    var today = new Date().toISOString().split("T")[0];
    $("#tanggal").val(today);
}

function scrollToInputSection() {
    $("html, body").animate(
        { scrollTop: $("#inputSection").offset().top },
        500
    );
}

$(document).ready(function () {
    scrollToAntrianSection();

    var Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
    });

    $(".select2bs4").select2({ theme: "bootstrap4" });

    $("#tanggal").on("change", antrian);
    $("#kelas").on("change", function () {
        var kelas = $(this).val(); // Mendapatkan nilai terpilih dari elemen dengan ID "kelas"
        console.log(kelas);
        populateLayananOptions(kelas); // Memanggil fungsi populateLayananOptions() dengan nilai kelas terpilih
    });

    $("#cariantrian").on("click", antrian);

    $("#jual").on("input", function () {
        // Mengambil nilai dari input
        var inputText = $(this).val();

        // Menghapus pemisah ribuan dari nilai input
        var inputValueWithoutCommas = inputText.replace(/,/g, "");

        // Mengubah nilai menjadi angka dan menambahkan pemisah ribuan
        var formattedValue = parseFloat(
            inputValueWithoutCommas
        ).toLocaleString();

        // Mengisi nilai input dengan pemisah ribuan
        $(this).val(formattedValue);
    });

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"), // Mengirim token CSRF untuk perlindungan keamanan
        },
    });

    setTodayDate();
    populateDokterOptions();
    populateLayananOptions();
    antrian();
    // setInterval(function () {
    //     antrian();
    // }, 60000);

    $("#qty").on("input", function (e) {
        hitungTotalHarga();
    });

    $("#tblBatal").on("click", function (e) {
        $(
            "#norm, #nama, #alamat, #layanan, #notrans, #dokter, #petugas, #tindakan, #asktind, #bmhp, #qty, #modalidTind, #modalkdTind, #modalnorm, #modaltindakan, #modaldokter, #modalpetugas"
        ).val("");
        $("#dokter, #petugas, #tindakan, #bmhp, #qty").trigger("change");

        var tabletindakan = $("#dataTindakan").DataTable();
        tabletindakan.clear().destroy();
        var tablebmhp = $("#transaksiBMHP").DataTable();
        tablebmhp.clear().destroy();

        antrian();
        scrollToAntrianSection();
        $("#formbmhp").hide();
    });

    $("#tblSimpan").on("click", function (e) {
        e.preventDefault();
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
            scrollToAntrianSection();
        } else {
            $(
                "#norm, #nama, #alamat, #layanan, #notrans, #dokter, #apoteker, #obat, #qty"
            ).val("");
            $("#dokter, #apoteker, #obat").trigger("change");

            var tabletindakan = $("#dataTindakan").DataTable();
            tabletindakan.clear().destroy();
            var tablebmhp = $("#transaksiBMHP").DataTable();
            tablebmhp.clear().destroy();

            antrian();
            scrollToAntrianSection();
            $("#formbmhp").hide();
            Toast.fire({
                icon: "success",
                title: "Transaksi Berhasil Disimpan, Maturnuwun...!!!",
            });
        }
    });

    $("#addFarmasi").on("click", function (e) {
        e.preventDefault();

        var norm = $("#norm").val();
        var notrans = $("#notrans").val();
        var kdTind = $("#tindakan").val();
        var petugas = $("#petugas").val();
        var dokter = $("#dokter").val();
        // Memeriksa apakah ada nilai yang kosong
        if (!norm || !notrans || !kdTind || !petugas || !dokter) {
            // Menampilkan notifikasi jika ada nilai yang kosong
            var dataKurang = [];
            if (!norm) dataKurang.push("Nomor Rekam Medis Belum Diisi");
            if (!notrans) dataKurang.push("Nomor Transaksi Belum Diisi");
            if (!kdTind) dataKurang.push("Tindakan Belum Diisi");
            if (!petugas) dataKurang.push("Petugas Belum Diisi");
            if (!dokter) dataKurang.push("Dokter Belum Diisi");

            // Menampilkan notifikasi menggunakan Toast.fire
            Toast.fire({
                icon: "error",
                title: "Data Tidak Lengkap...!!! " + dataKurang.join(", "),
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
                },

                success: function (response) {
                    Toast.fire({
                        icon: "success",
                        title: "Data Berhasil Disimpan, Maturnuwun...!!!",
                    });
                    dataTindakan();
                    $("#tindakan").val("");
                    $("#tindakan").trigger("change");
                },
                error: function (xhr) {
                    Toast.fire({
                        icon: "error",
                        title: "Data Tidak Lengkap...!!!",
                    });
                },
            });
        }
    });

    $("#norm").on("keyup", function (event) {
        if (event.key === "Enter") {
            event.preventDefault();
            searchByRM($("#norm").val());
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
        var asktind = $(this).data("asktind");

        $("#norm").val(norm);
        $("#nama").val(nama);
        $("#dokter").val(dokter);
        $("#dokter").trigger("change");
        $("#alamat").val(alamat);
        $("#layanan").val(layanan);
        $("#notrans").val(notrans);
        $("#asktind").val(asktind);

        scrollToInputSection();
        dataFarmasi();
    });

    $("#dataFarmasi").on("click", ".delete", function (e) {
        e.preventDefault();

        var id = $(this).data("id");
        var obat = $(this).data("obat");
        if (
            confirm("Apakah Anda yakin ingin menghapus tindakan " + obat + " ?")
        ) {
            $.ajax({
                url: "/api/deleteObat",
                type: "POST",
                data: { id: id },
                success: function (response) {
                    Toast.fire({
                        icon: "success",
                        title: "Data transaksi obat berhasil dihapus...!!!",
                    });

                    dataFarmasi();
                },
                error: function (xhr, status, error) {
                    Toast.fire({
                        icon: "success",
                        title: error + "...!!!",
                    });
                    console.error("Error:", error);
                },
            });
        }
    });
});

$(document).on("select2:open", () => {
    document.querySelector(".select2-search__field").focus();
});
