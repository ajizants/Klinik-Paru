// Menjalankan fungsi pada saat dokumen sudah siap
$(document).ready(function () {
    $("body").toggleClass("sidebar-toggled");
    $(".sidebar").toggleClass("toggled");
    if ($(".sidebar").hasClass("toggled")) {
        $(".sidebar .collapse").collapse("hide");
    }
    // Menggulir ke elemen dengan ID "antrianSection"
    scrollToAntrianSection();

    // Mengatur nilai input tanggal dengan tanggal hari ini
    setTodayDate();

    // Inisialisasi DataTable
    $("#dataAntrian").DataTable();

    // Memuat data saat halaman pertama kali dimuat
    loadData();

    // Event listener untuk tombol "Muat Ulang"
    $("#refreshButton").on("click", function () {
        loadData();
    });

    // Event listener untuk perubahan pada elemen input tanggal
    $("#tanggal").on("change", function () {
        searchByDate(); // Panggil fungsi searchByDate saat nilai input tanggal berubah
    });

    // Event listener untuk tombol "CariRM"
    $("#norm").on("keyup", function (event) {
        if (event.key === "Enter") {
            event.preventDefault();
            searchByRM($("#norm").val());
        }
    });

    // Event listener untuk tombol aksi di dalam tabel
    $("#dataAntrian").on("click", ".aksi-button", function (e) {
        e.preventDefault(); // Hentikan perilaku default dari tautan

        // Ambil data dari atribut data pada tombol aksi
        var norm = $(this).data("norm");
        var nama = $(this).data("nama");
        var dokter = $(this).data("dokter");
        var alamat = $(this).data("alamat");
        var layanan = $(this).data("layanan");
        var notrans = $(this).data("notrans");

        // Gunakan data ini untuk mengisi formulir atau melakukan tindakan lainnya
        $("#norm").val(norm);
        $("#nama").val(nama);
        $("#dokter").val(dokter);
        $("#alamat").val(alamat);
        $("#layanan").val(layanan);
        $("#notrans").val(notrans);

        // Animasi scroll ke elemen formulir
        scrollToInputSection();
    });
});
