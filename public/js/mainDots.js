$(document).ready(function () {
    $("#ddo").hide();
    $("#dtb").hide();
    $("#dtelat").hide();
    $("#dselesai").hide();

    $("#ikontrol").on("click", function () {
        $("#ddo").hide();
        $("#dtelat").hide();
        $("#dtb").hide();
        $("#dselesai").hide();
        $("#dkontrol").show();
    });
    $("#itelat").on("click", function () {
        $("#ddo").hide();
        $("#dtb").hide();
        $("#dkontrol").hide();
        $("#dselesai").hide();
        $("#dtelat").show();
    });
    $("#ido").on("click", function () {
        $("#dtelat").hide();
        $("#dtb").hide();
        $("#dkontrol").hide();
        $("#dselesai").hide();
        $("#ddo").show();
    });
    $("#itb").on("click", function () {
        $("#ddo").hide();
        $("#dtelat").hide();
        $("#dkontrol").hide();
        $("#dselesai").hide();
        $("#dtb").show();
    });
    $("#iall").on("click", function () {
        $("#ddo").hide();
        $("#dtelat").hide();
        $("#dkontrol").hide();
        $("#dtb").hide();
        $("#dselesai").show();
        // $(".aksi-button").hide();
    });
    scrollToAntrianSection();

    $("#tanggal").on("change", pasienKontrol);

    $("#cariantrian").on("click", pasienKontrol);

    $(".select2bs4").select2();
    $("#modal-pasienTB .select2bs4").select2();

    $("#modal-Ftb").on("click", function (e) {
        populateBlnKeOptions();
        populateDokterOptions();
        populateObatDotsOptions();
        populatePetugasOptions();
        populateDxMedis();
    });

    // $("#antrianall").on("click", ".aksi-button", function (e) {
    //     e.preventDefault();
    //     var norm = $(this).data("norm");

    //     searchByRM(norm);
    // });

    $("#norm").on("keyup", function (event) {
        if (event.key === "Enter") {
            event.preventDefault();

            formatNorm($("#norm"));
            searchByRM($("#norm").val());
        }
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

    $(".nav-link").on("click", function () {
        // Menghapus class 'active' dari semua elemen dengan class 'nav-link'
        $(".nav-link").removeClass("active");
        // Menambah class 'active' ke elemen yang diklik
        $(this).addClass("active");
    });

    $("#tblBatal").on("click", function (e) {
        $(
            "#norm, #nama, #alamat, #layanan, #notrans, #dokter, #apoteker, #obat, #qty"
        ).val("");
        $("#dokter, #apoteker, #obat").trigger("change");

        var table = $("#dataFarmasi").DataTable();
        table.destroy();
        scrollToAntrianSection();
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

            var table = $("#dataFarmasi").DataTable();
            table.clear().destroy();
            scrollToAntrianSection();
            Toast.fire({
                icon: "success",
                title: "Transaksi Berhasil Disimpan, Maturnuwun...!!!",
            });
        }
    });

    $("#addDataKunj").on("click", function (e) {
        e.preventDefault();

        var notrans = $("#notrans").val();
        var tgltrans = $("#tglKunj").val();
        var nxKontrol = $("#nxKontrol").val();
        var norm = $("#norm").val();
        var bta = $("#bta").val();
        var bb = $("#bb").val();
        var terapi = $("#obatDots").val();
        var blnKe = $("#blnKe").val();
        var petugas = $("#apoteker").val();
        var dokter = $("#dokter").val();
        // Memeriksa apakah ada nilai yang kosong
        if (!norm || !notrans || !bta || !petugas || !dokter) {
            // Menampilkan notifikasi jika ada nilai yang kosong
            var dataKurang = [];
            if (!norm) dataKurang.push("Nomor Rekam Medis Belum Diisi");
            if (!notrans) dataKurang.push("Nomor Transaksi Belum Diisi");
            if (!bta) dataKurang.push("Tindakan Belum Diisi");
            if (!petugas) dataKurang.push("Petugas Belum Diisi");
            if (!dokter) dataKurang.push("Dokter Belum Diisi");

            // Menampilkan notifikasi menggunakan Toast.fire
            Toast.fire({
                icon: "error",
                title: "Data Tidak Lengkap...!!! " + dataKurang.join(", "),
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
                    dataFarmasi();
                    $("#obat,#qty,#total").val("");
                    $("#obat").trigger("change");
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

    $("#modal-pasienTB #addPTB").on("click", function (e) {
        e.preventDefault();
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
            !hiv ||
            !dm ||
            !ket ||
            !petugas ||
            !dokter
        ) {
            Toast.fire({
                icon: "error",
                title: "Data Tidak Lengkap...!!!",
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
                    // status: status,
                    petugas: petugas,
                    dokter: dokter,
                },

                success: function (response) {
                    Toast.fire({
                        icon: "success",
                        title: "Data Berhasil Disimpan, Maturnuwun...!!!",
                    });
                    pasienTB();
                    $(
                        "#modal-norm ,#modal-hp ,#modal-tcm,#modal-kdDx,#modal-tglmulai,#modal-bb ,#modal-obtDots ,#modal-hiv,#modal-dm,#modal-ket ,#modal-petugas, #modal-dokter"
                    ).val("");
                    $("#obat").trigger("change");
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

    setTodayDate();
    pasienKontrol();
    pasienDo();
    pasienTB();
    pasienTelat();
    antrianAll();

    populateBlnKeOptions();
    populateDokterOptions();
    populateObatDotsOptions();
    populatePetugasOptions();
    populateDxMedis();
});
