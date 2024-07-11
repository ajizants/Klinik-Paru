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

    $("#norm").on("keyup", function (event) {
        if (event.key === "Enter") {
            event.preventDefault();
            searchByRM($("#norm").val());
        }
    });
});

$(document).on("select2:open", () => {
    document.querySelector(".select2-search__field").focus();
});
