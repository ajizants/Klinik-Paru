var Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
});

function formatTanggalIndonesia(tanggal) {
    var date = new Date(tanggal);
    var options = { day: "2-digit", month: "2-digit", year: "2-digit" };
    return date.toLocaleString("id-ID", options);
}
function addStokFarmasi() {
    var idGudang = $("#idGudang").val();
    var product_id = $("#productID").val();
    var idObat = $("#idObat").val();
    var nmObat = $("#nmObat").val();
    var stok = $("#stokBaruFarmasi").val();
    var pabrikan = $("#pabrikan").val();
    var beli = $("#hargaBeli").val();
    var jualin = $("#hargaJual").val();
    var jual = jualin.replace(/,/g, "");
    var jenis = $("#jenis").val();
    var sediaan = $("#sediaan").val();
    var sumber = $("#sumberObat").val();
    var supplier = $("#supplier").val();
    var tglEd = $("#tglED").val();
    var tglBeli = $("#tglBeli").val();
    var sisa = parseFloat($("#sisaStok").val());
    var jml = parseFloat($("#stokBaruFarmasi").val());

    // Membuat array untuk menyimpan field yang belum terisi
    var fieldsNotFilled = [];

    // Pengecekan data tidak boleh kosong
    if (!product_id) fieldsNotFilled.push("ID Barang");
    if (!idObat) fieldsNotFilled.push("ID Obat");
    if (!nmObat) fieldsNotFilled.push("Nama Obat");
    if (!stok) fieldsNotFilled.push("Stok");
    if (!pabrikan) fieldsNotFilled.push("Pabrikan");
    if (!beli) fieldsNotFilled.push("Harga Beli");
    if (!jual) fieldsNotFilled.push("Harga Jual");
    if (!jenis) fieldsNotFilled.push("Jenis");
    if (!sediaan) fieldsNotFilled.push("Sediaan");
    if (!sumber) fieldsNotFilled.push("Sumber Obat");
    if (!supplier) fieldsNotFilled.push("Supplier");
    if (!tglEd) fieldsNotFilled.push("Tanggal Expired");
    if (!tglBeli) fieldsNotFilled.push("Tanggal Pembelian");

    // Jika ada field yang belum terisi, tampilkan pesan kesalahan
    if (fieldsNotFilled.length > 0) {
        var errorMessage =
            "Harap lengkapi data berikut:\n" + fieldsNotFilled.join(", ");
        Swal.fire({
            icon: "error",
            title: errorMessage,
        });
        return; // Menghentikan eksekusi jika ada data yang belum terisi
    }

    if (sisa < jml) {
        var errorMessage = "Sisa stok " + nmObat + " tidak mencukupi...!";
        Swal.fire({
            icon: "error",
            title: errorMessage,
        });
        return;
    }

    // Jika semua data sudah lengkap, lanjutkan dengan permintaan AJAX
    $.ajax({
        url: "/api/addStokFarmasi",
        type: "POST",
        data: {
            idGudang: idGudang,
            product_id: product_id,
            idObat: idObat,
            nmObat: nmObat,
            stok: stok,
            beli: beli,
            pabrikan: pabrikan,
            jual: jual,
            jenis: jenis,
            sediaan: sediaan,
            sumber: sumber,
            supplier: supplier,
            tglEd: tglEd,
            tglBeli: tglBeli,
        },
        success: function (response) {
            Toast.fire({
                icon: "success",
                title: "Data Berhasil Disimpan, Maturnuwun...!!!",
            });
            loadFarmasi();
            loadGudangMasuk();
            loadStokGudang();
            populateNamaObatOptions();
            populateGudangObatOptions();
            $("#formInput").hide();
            $("#tabelData").show();

            // Mengosongkan semua formulir
            $(
                "#gObat,#idObat,#sisaStok,#productID,#nmObat,#stokBaru,#stokBaruIgd,#stokBaruFarmasi,#hargaBeli,#pabrikan,#supplier ,#jenis, #hargaJual, #jenis, #sediaan, #sumberObat, #tglED, #tglBeli"
            ).val("");
            $(
                "#gObat,#idObat,#jenis,#pabrikan,#sediaan,#sumberObat,#supplier"
            ).trigger("change");
        },
        error: function (xhr) {
            Swal.fire({
                icon: "error",
                title: "Gagal menyimpan data, Data Tidak Lengkap.....!!!!!",
            });
        },
    });
}
function loadFarmasi() {
    if ($.fn.DataTable.isDataTable("#farmasiObat")) {
        var tabelFarmasi = $("#farmasiObat").DataTable();
        tabelFarmasi.clear().destroy();
    }

    $.ajax({
        url: "/api/gudangFarmasi",
        type: "GET",
        data: {},
        success: function (response) {
            response.forEach(function (item, index) {
                item.no = index + 1; // Nomor urut dimulai dari 1, bukan 0
                item.actions = `<a href="#inputSection" class="edit"
                                    data-id="${item.id}"><i class="fas fa-pen-to-square pr-3"></i></a>
                                <a href="#inputSection" class="delete"
                                    data-id="${item.id}"><i class="fas fa-trash"></i></a>`;
                item.tBeli = formatTanggalIndonesia(item.tglPembelian);
                item.tED = formatTanggalIndonesia(item.tglPembelian);
            });

            $("#farmasiObat")
                .DataTable({
                    data: response,
                    columns: [
                        {
                            data: "actions",
                            className: "text-center px-0 col-1",
                        },
                        { data: "no" },
                        { data: "nmObat" },
                        { data: "nmjenis" },
                        { data: "pabrikan.nmPabrikan" },
                        { data: "sediaan" },
                        { data: "sumber" },
                        { data: "supplier.nmSupplier" },
                        { data: "tBeli" },
                        { data: "tED" },
                        { data: "hargaBeli" },
                        { data: "hargaJual" },
                        { data: "stokBaru" },
                        { data: "masuk" },
                        { data: "keluar" },
                        { data: "sisa" },
                    ],

                    order: [1, "dsc"],
                    paging: true,
                    lengthMenu: [
                        [5, 10, 25, 50, -1],
                        [5, 10, 25, 50, "All"],
                    ],
                    pageLength: 5,
                    responsive: true,
                    lengthChange: true,
                    autoWidth: false,
                    buttons: [
                        {
                            extend: "copyHtml5",
                            text: "Salin",
                        },
                        {
                            extend: "excelHtml5",
                            text: "Excel",
                            title: "Daftar Stok Obat Farmasi",
                            filename: "Daftar Stok Obat Farmasi",
                        },
                        "colvis", // Tombol untuk menampilkan/menyembunyikan kolom
                    ],
                    columnDefs: [
                        {
                            targets: [3],
                            visible: false,
                        },
                    ],
                })
                .buttons()
                .container()
                .appendTo("#farmasiObat_wrapper .col-md-6:eq(0)");
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        },
    });
}
function addStokGudang() {
    // Mengambil nilai input
    var idObat = $("#idObat").val();
    var nmObat = $("#nmObat").val();
    var stok = $("#stokBaru").val();
    var pabrikan = $("#pabrikan").val();
    var beli = $("#hargaBeli").val();
    var jualin = $("#hargaJual").val();
    var jual = jualin.replace(/,/g, "");
    var jenis = $("#jenis").val();
    var sediaan = $("#sediaan").val();
    var sumber = $("#sumberObat").val();
    var supplier = $("#supplier").val();
    var tglEd = $("#tglED").val();
    var tglBeli = $("#tglBeli").val();
    var sisa = parseFloat($("#sisaStok").val());
    var jml = parseFloat($("#stokBaru").val());

    // Membuat array untuk menyimpan field yang belum terisi
    var fieldsNotFilled = [];

    // Pengecekan data tidak boleh kosong
    if (!idObat) fieldsNotFilled.push("ID Obat");
    if (!nmObat) fieldsNotFilled.push("Nama Obat");
    if (!stok) fieldsNotFilled.push("Stok");
    if (!pabrikan) fieldsNotFilled.push("Pabrikan");
    if (!beli) fieldsNotFilled.push("Harga Beli");
    if (!jual) fieldsNotFilled.push("Harga Jual");
    if (!jenis) fieldsNotFilled.push("Jenis");
    if (!sediaan) fieldsNotFilled.push("Sediaan");
    if (!sumber) fieldsNotFilled.push("Sumber Obat");
    if (!supplier) fieldsNotFilled.push("Supplier");
    if (!tglEd) fieldsNotFilled.push("Tanggal Expired");
    if (!tglBeli) fieldsNotFilled.push("Tanggal Pembelian");

    // Jika ada field yang belum terisi, tampilkan pesan kesalahan
    if (fieldsNotFilled.length > 0) {
        var errorMessage =
            "Harap lengkapi data berikut:\n" + fieldsNotFilled.join(", ");
        Swal.fire({
            icon: "error",
            title: errorMessage,
        });
        return; // Menghentikan eksekusi jika ada data yang belum terisi
    }

    if (sisa < jml) {
        var errorMessage = "Sisa stok " + nmObat + " tidak mencukupi...!";
        Swal.fire({
            icon: "error",
            title: errorMessage,
        });
        return;
    }

    // Jika semua data sudah lengkap, lanjutkan dengan permintaan AJAX
    $.ajax({
        url: "/api/addStokGudang",
        type: "POST",
        data: {
            idObat: idObat,
            nmObat: nmObat,
            stok: stok,
            beli: beli,
            pabrikan: pabrikan,
            jual: jual,
            jenis: jenis,
            sediaan: sediaan,
            sumber: sumber,
            supplier: supplier,
            tglEd: tglEd,
            tglBeli: tglBeli,
        },
        success: function (response) {
            Toast.fire({
                icon: "success",
                title: "Data Berhasil Disimpan, Maturnuwun...!!!",
            });
            loadFarmasi();
            loadGudangMasuk();
            loadStokGudang();
            populateNamaObatOptions();
            populateGudangObatOptions();
            $("#formInput").hide();
            $("#tabelData").show();

            // Mengosongkan semua formulir
            $(
                "#gObat,#idObat,#productID,#sisaStok,#stokBaruIgd,#stokBaruFarmasi, #nmObat,#stokBaru,#hargaBeli,#pabrikan,#supplier ,#jenis, #hargaJual, #jenis, #sediaan, #sumberObat, #tglED, #tglBeli"
            ).val("");
            $(
                "#gObat,#idObat,#jenis,#pabrikan,#sediaan,#sumberObat,#supplier"
            ).trigger("change");
        },
        error: function (xhr) {
            Swal.fire({
                icon: "error",
                title: "Gagal menyimpan data, Data Tidak Lengkap.....!!!!!",
            });
        },
    });
}
function loadGudangMasuk() {
    if ($.fn.DataTable.isDataTable("#gudangObat")) {
        var tabletindakan = $("#gudangObat").DataTable();
        tabletindakan.clear().destroy();
    }

    $.ajax({
        url: "/api/gudangObatIN",
        type: "GET",
        data: {},
        success: function (response) {
            response.forEach(function (item, index) {
                item.no = index + 1; // Nomor urut dimulai dari 1, bukan 0
                item.actions = `<a href="#inputSection" class="edit"
                                    data-id="${item.id}"><i class="fas fa-pen-to-square pr-3"></i></a>
                                <a href="#inputSection" class="delete"
                                    data-id="${item.id}"><i class="fas fa-trash"></i></a>`;
                item.tBeli = formatTanggalIndonesia(item.tglPembelian);
                item.tED = formatTanggalIndonesia(item.tglPembelian);
            });

            $("#gudangObat")
                .DataTable({
                    data: response,
                    columns: [
                        {
                            data: "actions",
                            className: "text-center px-0 col-1",
                        },
                        { data: "no" },
                        { data: "nmObat" },
                        { data: "nmjenis" },
                        { data: "pabrikan.nmPabrikan" },
                        { data: "sediaan" },
                        { data: "sumber" },
                        { data: "supplier.nmSupplier" },
                        { data: "tBeli" },
                        { data: "tED" },
                        { data: "hargaBeli" },
                        { data: "hargaJual" },
                        { data: "stokBaru" },
                        { data: "keluar" },
                        { data: "sisa" },
                    ],

                    order: [1, "dsc"],
                    lengthMenu: [
                        [5, 10, 25, 50, -1],
                        [5, 10, 25, 50, "All"],
                    ],
                    paging: true,
                    pageLength: 5,
                    responsive: true,
                    lengthChange: true,
                    autoWidth: false,
                    buttons: [
                        {
                            extend: "copyHtml5",
                            text: "Salin",
                        },
                        {
                            extend: "excelHtml5",
                            text: "Excel",
                            title: "Daftar Pembelian Obat",
                            filename: "Daftar Pembelian Obat",
                        },
                        "colvis", // Tombol untuk menampilkan/menyembunyikan kolom
                    ],
                    columnDefs: [
                        {
                            targets: [3],
                            visible: false,
                        },
                    ],
                })
                .buttons()
                .container()
                .appendTo("#gudangObat_wrapper .col-md-6:eq(0)");
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        },
    });
}
function loadStokGudangLimit() {
    if ($.fn.DataTable.isDataTable("#limitStokGudang")) {
        var tabletindakan = $("#limitStokGudang").DataTable();
        tabletindakan.clear().destroy();
    }

    $.ajax({
        url: "/api/daftarGudangObatLimit",
        type: "GET",
        data: {},
        success: function (response) {
            response.forEach(function (item, index) {
                item.no = index + 1; // Nomor urut dimulai dari 1, bukan 0
            });

            $("#limitStokGudang")
                .DataTable({
                    data: response,
                    columns: [
                        { data: "no", className: "text-center" },
                        { data: "nmObat" },
                        { data: "pabrikan.nmPabrikan" },
                        { data: "sediaan" },
                        { data: "supplier.nmSupplier" },
                        { data: "ed" },
                        { data: "sisa" },
                    ],

                    order: [1, "dsc"],
                    paging: true,
                    lengthMenu: [
                        [5, 10, 25, 50, -1],
                        [5, 10, 25, 50, "All"],
                    ],
                    buttons: [
                        {
                            extend: "copyHtml5",
                            text: "Salin",
                        },
                        {
                            extend: "excelHtml5",
                            text: "Excel",
                            title: "Daftar Stok Gudang Obat",
                            filename: "Daftar Stok Gudang Obat",
                        },
                        "colvis", // Tombol untuk menampilkan/menyembunyikan kolom
                    ],
                })
                .buttons()
                .container()
                .appendTo("#limitStokGudang_wrapper .col-md-6:eq(0)");
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        },
    });
}
function loadStokGudang() {
    if ($.fn.DataTable.isDataTable("#dgudangObat")) {
        var tabletindakan = $("#dgudangObat").DataTable();
        tabletindakan.clear().destroy();
    }

    $.ajax({
        url: "/api/daftarGudangObat",
        type: "GET",
        data: {},
        success: function (response) {
            response.forEach(function (item, index) {
                item.no = index + 1; // Nomor urut dimulai dari 1, bukan 0
                item.actions = `<a href="#inputSection" class="edit"
                                    data-id="${item.id}"><i class="fas fa-pen-to-square pr-3"></i></a>
                                <a href="#inputSection" class="delete"
                                    data-id="${item.id}"><i class="fas fa-trash"></i></a>`;
                item.tBeli = formatTanggalIndonesia(item.tglPembelian);
                item.tED = formatTanggalIndonesia(item.tglPembelian);
            });

            $("#dgudangObat")
                .DataTable({
                    data: response,
                    columns: [
                        {
                            data: "actions",
                            className: "text-center px-0 col-1",
                        },
                        { data: "no" },
                        { data: "nmObat" },
                        { data: "nmjenis" },
                        { data: "pabrikan.nmPabrikan" },
                        { data: "sediaan" },
                        { data: "sumber" },
                        { data: "supplier.nmSupplier" },
                        { data: "tBeli" },
                        { data: "tED" },
                        { data: "hargaBeli" },
                        { data: "hargaJual" },
                        { data: "stokBaru" },
                        { data: "masuk" },
                        { data: "keluar" },
                        { data: "sisa" },
                    ],

                    order: [1, "dsc"],
                    paging: true,
                    lengthMenu: [
                        [5, 10, 25, 50, -1],
                        [5, 10, 25, 50, "All"],
                    ],
                    buttons: [
                        {
                            extend: "copyHtml5",
                            text: "Salin",
                        },
                        {
                            extend: "excelHtml5",
                            text: "Excel",
                            title: "Daftar Stok Gudang Obat",
                            filename: "Daftar Stok Gudang Obat",
                        },
                        "colvis", // Tombol untuk menampilkan/menyembunyikan kolom
                    ],
                    columnDefs: [
                        {
                            targets: [3],
                            visible: false,
                        },
                    ],
                })
                .buttons()
                .container()
                .appendTo("#dgudangObat_wrapper .col-md-6:eq(0)");
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        },
    });
}
function addStokIGD() {
    var idGudang = $("#idGudang").val();
    var product_id = $("#productID").val();
    var idObat = $("#idObat").val();
    var nmObat = $("#nmObat").val();
    var stok = $("#stokBaruIgd").val();
    var pabrikan = $("#pabrikan").val();
    var beliin = $("#hargaBeli").val();
    var beli = beliin.replace(/,/g, "");
    var jualin = $("#hargaJual").val();
    var jual = jualin.replace(/,/g, "");
    var jenis = $("#jenis").val();
    var sediaan = $("#sediaan").val();
    var sumber = $("#sumberObat").val();
    var supplier = $("#supplier").val();
    var tglEd = $("#tglED").val();
    var tglBeli = $("#tglBeli").val();
    var sisa = parseFloat($("#sisaStok").val());
    var jml = parseFloat($("#stokBaruIgd").val());

    // Membuat array untuk menyimpan field yang belum terisi
    var fieldsNotFilled = [];

    // Pengecekan data tidak boleh kosong
    if (!product_id) fieldsNotFilled.push("ID Barang");
    if (!idObat) fieldsNotFilled.push("ID Obat");
    if (!nmObat) fieldsNotFilled.push("Nama Obat");
    if (!stok) fieldsNotFilled.push("Stok");
    if (!pabrikan) fieldsNotFilled.push("Pabrikan");
    if (!beli) fieldsNotFilled.push("Harga Beli");
    if (!jual) fieldsNotFilled.push("Harga Jual");
    if (!jenis) fieldsNotFilled.push("Jenis");
    if (!sediaan) fieldsNotFilled.push("Sediaan");
    if (!sumber) fieldsNotFilled.push("Sumber Obat");
    if (!supplier) fieldsNotFilled.push("Supplier");
    if (!tglEd) fieldsNotFilled.push("Tanggal Expired");
    if (!tglBeli) fieldsNotFilled.push("Tanggal Pembelian");

    // Jika ada field yang belum terisi, tampilkan pesan kesalahan
    if (fieldsNotFilled.length > 0) {
        var errorMessage =
            "Harap lengkapi data berikut:\n" + fieldsNotFilled.join(", ");
        Swal.fire({
            icon: "error",
            title: errorMessage,
        });
        return; // Menghentikan eksekusi jika ada data yang belum terisi
    }

    if (sisa < jml) {
        var errorMessage = "Sisa stok " + nmObat + " tidak mencukupi...!";
        Swal.fire({
            icon: "error",
            title: errorMessage,
        });
        return;
    }

    // Jika semua data sudah lengkap, lanjutkan dengan permintaan AJAX
    $.ajax({
        url: "/api/addStokIGD",
        type: "POST",
        data: {
            idGudang: idGudang,
            product_id: product_id,
            idObat: idObat,
            nmObat: nmObat,
            stok: stok,
            beli: beli,
            pabrikan: pabrikan,
            jual: jual,
            jenis: jenis,
            sediaan: sediaan,
            sumber: sumber,
            supplier: supplier,
            tglEd: tglEd,
            tglBeli: tglBeli,
        },
        success: function (response) {
            Toast.fire({
                icon: "success",
                title: "Data Berhasil Disimpan, Maturnuwun...!!!",
            });
            loadFarmasi();
            loadGudangMasuk();
            loadStokGudang();
            loadIgdMasuk();
            populateNamaObatOptions();
            populateGudangObatOptions();
            $("#formInput").hide();
            $("#tabelData").show();

            // Mengosongkan semua formulir
            $(
                "#gObat,#idObat,#productID,#sisaStok,#stokBaruIgd,#stokBaruFarmasi, #nmObat,#stokBaru,#hargaBeli,#pabrikan,#supplier ,#jenis, #hargaJual, #jenis, #sediaan, #sumberObat, #tglED, #tglBeli"
            ).val("");
            $(
                "#gObat,#idObat,#jenis,#pabrikan,#sediaan,#sumberObat,#supplier"
            ).trigger("change");
        },
        error: function (xhr) {
            Swal.fire({
                icon: "error",
                title: "Gagal menyimpan data, Data Tidak Lengkap.....!!!!!",
            });
        },
    });
}
function loadIgdMasuk() {
    if ($.fn.DataTable.isDataTable("#igdObat")) {
        var tabletindakan = $("#igdObat").DataTable();
        tabletindakan.clear().destroy();
    }

    $.ajax({
        url: "/api/gudangIGD",
        type: "GET",
        data: {},
        success: function (response) {
            response.forEach(function (item, index) {
                item.no = index + 1; // Nomor urut dimulai dari 1, bukan 0
                item.actions = `<a href="#inputSection" class="edit"
                                    data-id="${item.product_id}"><i class="fas fa-pen-to-square pr-3"></i></a>
                                <a href="#inputSection" class="delete"
                                    data-id="${item.product_id}"><i class="fas fa-trash"></i></a>`;
                item.tBeli = formatTanggalIndonesia(item.tglPembelian);
                item.tED = formatTanggalIndonesia(item.tglPembelian);
            });

            $("#igdObat")
                .DataTable({
                    data: response,
                    columns: [
                        {
                            data: "actions",
                            className: "text-center px-0 col-1",
                        },
                        { data: "no" },
                        { data: "nmObat" },
                        { data: "nmjenis" },
                        { data: "pabrikan.nmPabrikan" },
                        { data: "sediaan" },
                        { data: "sumber" },
                        { data: "supplier.nmSupplier" },
                        { data: "tBeli" },
                        { data: "tED" },
                        { data: "hargaBeli" },
                        { data: "hargaJual" },
                        { data: "stokBaru" },
                        { data: "keluar" },
                        { data: "sisa" },
                    ],

                    order: [1, "dsc"],
                    lengthMenu: [
                        [5, 10, 25, 50, -1],
                        [5, 10, 25, 50, "All"],
                    ],
                    paging: true,
                    pageLength: 5,
                    responsive: true,
                    lengthChange: true,
                    autoWidth: false,
                    buttons: [
                        {
                            extend: "copyHtml5",
                            text: "Salin",
                        },
                        {
                            extend: "excelHtml5",
                            text: "Excel",
                            title: "Daftar Pembelian Obat",
                            filename: "Daftar Pembelian Obat",
                        },
                        "colvis", // Tombol untuk menampilkan/menyembunyikan kolom
                    ],
                    columnDefs: [
                        {
                            targets: [3],
                            visible: false,
                        },
                    ],
                })
                .buttons()
                .container()
                .appendTo("#igdObat_wrapper .col-md-6:eq(0)");
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        },
    });
}
function loadAwal() {
    $("#digd").hide();
    $("#dfarmasi").hide();
    $("#dgudang").hide();
    $("#igd").hide();
    $("#farmasi").hide();
    $("#adstok").hide();
    $("#adobat").show();
    $("#obatg").hide();
    $("#formInput").hide();
    $("#stokBaruIgd").hide();
    $("#stokBaruFarmasi").hide();
}

$(document).ready(function () {
    $("#hargaBeli").on("input", function (e) {
        var beli = parseFloat($("#hargaBeli").val()) || 0; // Mengambil harga jual, jika tidak valid, dianggap 0
        var jual = beli * 1.25;
        var hargaJual = Math.ceil(jual);
        $("#hargaJual").val(hargaJual.toLocaleString());
    });
    $("#input").on("click", function () {
        $("#tabelData").hide();
        $("#formInput").show();
        $(
            "#gObat,#idObat,#sisaStok,#productID,#stokBaruIgd,#stokBaruFarmasi, #nmObat,#stokBaru,#hargaBeli,#pabrikan,#supplier ,#jenis, #hargaJual, #jenis, #sediaan, #sumberObat, #tglED, #tglBeli"
        ).val("");
        $(
            "#gObat,#idObat,#jenis,#pabrikan,#sediaan,#sumberObat,#supplier"
        ).trigger("change");
    });
    loadAwal();

    $("#iigd").on("click", function () {
        $("#farmasi").hide();
        $("#gudang").hide();
        $("#dfarmasi").hide();
        $("#dingudang").hide();
        $("#dgudang").hide();
        $("#digd").show();
        $("#igd").show();
        $("#adstok").show();
        $("#adobat").hide();
        $("#obatg").show();
        $("#obatNew").hide();
        $("#adstok").show();
        $("#adobat").hide();
        $("#obats").hide();
        $("#formInput").hide();
        $("#tabelData").show();
        $("#stokBaru").hide();
        $("#stokBaruIgd").show();
        $("#stokBaruFarmasi").hide();
    });
    $("#ifarmasi").on("click", function () {
        $("#farmasi").show();
        $("#dfarmasi").show();
        $("#igdfar").show();
        $("#adstok").show();
        $("#obatg").show();
        $("#dgudang").hide();
        $("#dingudang").hide();
        $("#digd").hide();
        $("#igd").hide();
        $("#gudang").hide();
        $("#adobat").hide();
        $("#obatNew").hide();
        $("#adstok").show();
        $("#adobat").hide();
        $("#obats").hide();
        $("#formInput").hide();
        $("#tabelData").show();
        $("#stokBaru").hide();
        $("#stokBaruIgd").hide();
        $("#stokBaruFarmasi").show();
    });
    $("#igudang").on("click", function () {
        $("#farmasi").hide();
        $("#dfarmasi").hide();
        $("#igd").hide();
        $("#digd").hide();
        $("#obatg").hide();
        $("#dgudang").show();
        $("#gudang").show();
        $("#dingudang").hide();
        $("#obatNew").show();
        $("#adstok").show();
        $("#adobat").hide();
        $("#obats").show();
        $("#obatg").hide();
        $("#formInput").hide();
        $("#tabelData").show();
        $("#stokBaru").show();
        $("#stokBaruIgd").hide();
        $("#stokBaruFarmasi").hide();
    });
    $("#ingudang").on("click", function () {
        $("#farmasi").hide();
        $("#dfarmasi").hide();
        $("#igd").hide();
        $("#digd").hide();
        $("#obatg").hide();
        $("#dingudang").show();
        $("#gudang").show();
        $("#dgudang").hide();
        $("#obatNew").show();
        $("#adstok").show();
        $("#adobat").hide();
        $("#obats").show();
        $("#obatg").hide();
        $("#formInput").hide();
        $("#tabelData").show();
        $("#stokBaru").show();
        $("#stokBaruIgd").hide();
        $("#stokBaruFarmasi").hide();
    });

    $(".select2bs4").select2();
    $(".21").select2();

    loadStokGudangLimit();
    loadFarmasi();
    loadGudangMasuk();
    loadStokGudang();
    loadIgdMasuk();
    populateGudangObatOptions();
    populateNamaObatOptions();
    populateSupplierOptions();
    populatePabrikanOptions();
    $("#modal-xl").modal("show");
    $("#stokBaruIgd").on("keyup", function (event) {
        if (event.key === "Enter") {
            event.preventDefault();
            Swal.fire({
                title: "Konfirmasi",
                text: "Apakah Anda yakin ingin menyimpan transaksi?",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, simpan!",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    addStokIGD();
                }
            });
        }
    });
    $("#stokBaruFarmasi").on("keyup", function (event) {
        if (event.key === "Enter") {
            event.preventDefault();
            Swal.fire({
                title: "Konfirmasi",
                text: "Apakah Anda yakin ingin menyimpan transaksi?",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, simpan!",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    addStokFarmasi();
                }
            });
        }
    });
    $("#addStokFarmasi").on("click", function (e) {
        e.preventDefault();
        Swal.fire({
            title: "Konfirmasi",
            text: "Apakah Anda yakin ingin menyimpan transaksi?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, simpan!",
            cancelButtonText: "Batal",
        }).then((result) => {
            if (result.isConfirmed) {
                addStokFarmasi();
            }
        });
    });

    $("#addStokIGD").on("click", function (e) {
        e.preventDefault();
        Swal.fire({
            title: "Konfirmasi",
            text: "Apakah Anda yakin ingin menyimpan transaksi?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, simpan!",
            cancelButtonText: "Batal",
        }).then((result) => {
            if (result.isConfirmed) {
                addStokIGD();
            }
        });
    });
    $("#addStokGudang").on("click", function (e) {
        e.preventDefault();
        Swal.fire({
            title: "Konfirmasi",
            text: "Apakah Anda yakin ingin menyimpan transaksi?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, simpan!",
            cancelButtonText: "Batal",
        }).then((result) => {
            if (result.isConfirmed) {
                addStokGudang();
            }
        });
    });

    $("#simpanBasicObat").on("click", function (e) {
        e.preventDefault();

        // Mengambil nilai input
        var nmObat = $("#namaObatBasic").val();
        // Membuat array untuk menyimpan field yang belum terisi
        var fieldsNotFilled = [];

        if (!nmObat) fieldsNotFilled.push("Nama Obat");
        if (fieldsNotFilled.length > 0) {
            var errorMessage =
                "Harap lengkapi data :\n" + fieldsNotFilled.join(", ");
            Swal.fire({
                icon: "error",
                title: errorMessage,
            });
            return; // Menghentikan eksekusi jika ada data yang belum terisi
        }

        // Jika semua data sudah lengkap, lanjutkan dengan permintaan AJAX
        $.ajax({
            url: "/api/addBasicObat",
            type: "POST",
            data: {
                nmObat: nmObat,
            },
            success: function (response) {
                Toast.fire({
                    icon: "success",
                    title: "Data Berhasil Disimpan, Maturnuwun...!!!",
                });
                populateNamaObatOptions();
                $("#namaObatBasic").val("");
            },
            error: function (xhr) {
                Swal.fire({
                    icon: "error",
                    title: "Gagal menyimpan data, Data Tidak Lengkap.....!!!!!",
                });
            },
        });
    });
});
