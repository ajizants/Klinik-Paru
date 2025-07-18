function loadTindakan() {
    if ($.fn.DataTable.isDataTable("#tindakan")) {
        var tabletindakan = $("#tindakan").DataTable();
        tabletindakan.clear().destroy();
    }

    $.ajax({
        url: "/api/jenistindakan",
        type: "GET",
        data: {},
        success: function (response) {
            response.forEach(function (item, index) {
                item.no = index + 1; // Nomor urut dimulai dari 1, bukan 0
                item.actions = `<a href="#inputSection" class="edit"
                                    data-harga="${item.harga}"
                                    data-nmTindakan="${item.nmTindakan}"
                                    data-id="${item.kdTindakan}"><i class="fas fa-pen-to-square pr-3"></i></a>
                                <a href="#inputSection" class="delete"
                                    data-harga="${item.harga}"
                                    data-nmTindakan="${item.nmTindakan}"
                                    data-id="${item.kdTindakan}"><i class="fas fa-trash"></i></a>`;
            });

            $("#tindakan").DataTable({
                data: response,
                columns: [
                    { data: "actions" },
                    { data: "no" },
                    { data: "nmTindakan" },
                    { data: "harga" },
                ],
                order: [1, "desc"],
                paging: true,
                lengthMenu: [
                    [5, 10, 25, 50, -1],
                    [5, 10, 25, 50, "All"],
                ],
                pageLength: 5,
            });
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        },
    });
}
function loadBMHP() {
    if ($.fn.DataTable.isDataTable("#BMHP")) {
        var tabletbmhp = $("#BMHP").DataTable();
        tabletbmhp.clear().destroy();
    }

    $.ajax({
        url: "/api/bmhp",
        type: "GET",
        data: {},
        success: function (response) {
            response.forEach(function (item, index) {
                item.no = index + 1; // Nomor urut dimulai dari 1, bukan 0
                item.actions = `<a href="#inputSection" class="edit"
                                    data-id="${item.kdBmhp}"><i class="fas fa-pen-to-square pr-3"></i></a>
                                <a href="#inputSection" class="delete"
                                    data-id="${item.kdBmhp}"><i class="fas fa-trash"></i></a>`;
            });

            $("#BMHP")
                .DataTable({
                    data: response,
                    columns: [
                        { data: "actions" },
                        { data: "no", className: "text-center" },
                        { data: "nmObat" },
                        { data: "nmjenis" },
                        { data: "pabrikan.nmPabrikan" },
                        { data: "sediaan" },
                        { data: "sumber" },
                        { data: "supplier.nmSupplier" },
                        { data: "tglPembelian" },
                        { data: "ed" },
                        { data: "hargaBeli" },
                        { data: "hargaJual" },
                        { data: "stokBaru" },
                        { data: "masuk" },
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
                            title: "Daftar Stok Obat dan BMHP IGD KKPM",
                            filename: "Daftar Stok Obat dan BMHP IGD KKPM",
                        },
                        "colvis", // Tombol untuk menampilkan/menyembunyikan kolom
                    ],
                })
                .buttons()
                .container()
                .appendTo("#BMHP_wrapper .col-md-6:eq(0)");
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        },
    });
}

var Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
});

$(document).ready(function () {
    loadTindakan();
    loadBMHP();

    $("#addJenisTindakan").on("click", function (e) {
        e.preventDefault();
        var nmTindakan = $("#nmTindakan").val();
        var harga = $("#harga").val();

        $.ajax({
            url: "/api/addJenisTindakan",
            type: "POST",
            data: {
                nmTindakan: nmTindakan,
                harga: harga,
            },

            success: function (response) {
                $("#nmTindakan").val("");
                $("#harga").val("");
                Toast.fire({
                    icon: "success",
                    title: "Jos... Data Berhasil Disimpan, Maturnuwun...!!!",
                });
                loadTindakan();
            },
            error: function (xhr) {
                Toast.fire({
                    icon: "error",
                    title: "Gagal menyimpan data, Data Tidak Lengkap.....!!!!!",
                });
            },
        });
    });

    $("#addJenisBmhp").on("click", function (e) {
        console.log("Tombol Add Jenis BMHP diklik.");
        e.preventDefault();
        var nmBmhp = $("#nmBmhp").val();
        var harga = $("#hargaBmhp").val();

        $.ajax({
            url: "/api/addJenisBmhp",
            type: "POST",
            data: {
                nmBmhp: nmBmhp,
                hargaBmhp: harga,
            },

            success: function (response) {
                $("#nmBmhp").val("");
                $("#hargaBmhp").val("");
                Toast.fire({
                    icon: "success",
                    title: "Data Berhasil Disimpan, Maturnuwun...!!!",
                });
                loadBMHP();
            },
            error: function (xhr) {
                Toast.fire({
                    icon: "error",
                    title: "Gagal menyimpan data, Data Tidak Lengkap.....!!!!!",
                });
            },
        });
    });

    $("#tindakan").on("click", ".delete", function (e) {
        e.preventDefault();

        var id = $(this).data("id");
        if (confirm("Apakah Anda yakin ingin menghapus tindakan ini?")) {
            $.ajax({
                url: "/api/deleteJenisTindakan",
                type: "POST",
                data: { kdTindakan: id },
                success: function (response) {
                    Toast.fire({
                        icon: "success",
                        title: "Data Tindakan berhasil dihapus...!!!",
                    });
                    loadTindakan();
                },
                error: function (xhr, status, error) {
                    console.error("Error:", error);
                },
            });
        }
    });

    $("#BMHP").on("click", ".delete", function (e) {
        e.preventDefault();

        var id = $(this).data("id");
        if (confirm("Apakah Anda yakin ingin menghapus tindakan ini?")) {
            $.ajax({
                url: "/api/deleteJenisBmhp",
                type: "POST",
                data: { kdBmhp: id },
                success: function (response) {
                    Toast.fire({
                        icon: "success",
                        title: "Data BMHP berhasil dihapus...!!!",
                    });
                    loadBMHP();
                },
                error: function (xhr, status, error) {
                    console.error("Error:", error);
                },
            });
        }
    });
});
