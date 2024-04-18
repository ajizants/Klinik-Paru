var Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
});

$(document).on("select2:open", () => {
    document.querySelector(".select2-search__field").focus();
});

function formatNorm(inputElement) {
    // Pastikan inputElement adalah objek jQuery yang valid
    if (inputElement && inputElement.val) {
        // Hapus karakter selain digit
        let inputValue = inputElement.val().replace(/\D/g, "");

        // Tambahkan 0 di depan jika kurang dari 6 digit
        while (inputValue.length < 6) {
            inputValue = "0" + inputValue;
        }

        // Ambil 6 digit pertama
        inputElement.val(inputValue.slice(0, 6));
    }
}

function searchByRM(norm, date) {
    // Check if the patient ID exists in "/api/Ptb"
    $.ajax({
        url: "/api/Ptb",
        type: "POST",
        data: {
            norm: norm,
        },
        success: function (response) {
            if (response.exists) {
                performCariRM(norm, date);
            } else {
                // If the patient ID does not exist, show an error toast
                Swal.fire({
                    icon: "question",
                    title:
                        "Data dengan norm " +
                        norm +
                        " belum terdaftar sebagai pasien TB",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "YA",
                    cancelButtonText: "TIDAK",
                }).then((result) => {
                    // Display a confirmation dialog
                    if (result.isConfirmed) {
                        $("#modal-pasienTB").modal("show");
                        performCariRMmodal(norm, date);
                    } else {
                    }
                });
            }
        },
        error: function (xhr) {
            // Handle error
        },
    });
}

// Separate function to perform the original AJAX request to "/api/cariRM"
function performCariRM(norm, date) {
    // The original AJAX request to "/api/cariRM"
    $.ajax({
        url: "/api/cariRM",
        type: "POST",
        data: {
            norm: norm,
            date: date,
        },
        success: function (response) {
            if (response.length > 0) {
                var noRM = response[0].norm;
                var nama = response[0].biodata.nama;
                var notrans = response[0].notrans;
                var layanan = response[0].kelompok.kelompok;
                var dokter = response[0].petugas.p_dokter_poli;
                var alamat = `${response[0].biodata.kelurahan}, ${response[0].biodata.rtrw}, ${response[0].biodata.kecamatan}, ${response[0].biodata.kabupaten}`;

                $("#norm").val(noRM);
                $("#nama").val(nama);
                $("#alamat").val(alamat);
                $("#notrans").val(notrans);
                $("#layanan").val(layanan);
                $("#dokter").val(dokter).trigger("change");
                showKunjungan();
                showRiwayatKunjungan(noRM);
                scrollToInputSection();
            } else {
                Toast.fire({
                    icon: "error",
                    title: "Data tidak ditemukan pada kunjungan hari ini...!!!",
                });
            }
        },
        error: function (xhr) {
            // Handle error
        },
    });
}
function performCariRMmodal(norm, date) {
    // The original AJAX request to "/api/cariRM"
    $.ajax({
        url: "/api/cariRM",
        type: "POST",
        data: {
            norm: norm,
            date: date,
        },
        success: function (response) {
            if (response.length > 0) {
                let dx1 = "";
                var noRM = response[0].norm;
                var nama = response[0].biodata.nama;
                var notrans = response[0].notrans;
                var layanan = response[0].kelompok.kelompok;
                var nik = response[0].biodata.noktp;
                var dokter = response[0].petugas.p_dokter_poli;
                var alamat = `${response[0].biodata.kelurahan}, ${response[0].biodata.rtrw}, ${response[0].biodata.kecamatan}, ${response[0].biodata.kabupaten}`;
                if (response.poli !== null) {
                    dx1 = "";
                    console.log("true");
                } else {
                    dx1 = response[0].poli.diagnosa1;
                    console.log("else");
                }
                console.log("🚀 ~ performCariRMmodal ~ dx1:", dx1);
                $("#modal-pasienTB #modal-norm").val(noRM);
                $("#modal-pasienTB #modal-nama").val(nama);
                $("#modal-pasienTB #modal-alamat").val(alamat);
                $("#modal-pasienTB #modal-notrans").val(notrans);
                $("#modal-pasienTB #modal-layanan").val(layanan);
                $("#modal-pasienTB #modal-nik").val(nik);
                $("#modal-pasienTB #modal-dokter").val(dokter);
                $("#modal-pasienTB #modal-dokter").trigger("change");
                $("#modal-pasienTB #modal-kdDx").val(dx1).trigger("change");
            } else {
                Toast.fire({
                    icon: "error",
                    title: "Data tidak ditemukan pada kunjungan hari ini...!!!",
                });
            }
        },
        error: function (xhr) {
            // Handle error
        },
    });
}
function pasienKontrol() {
    var tanggal = $("#tanggal").val();
    $("#loadingSpinner").show();
    if ($.fn.DataTable.isDataTable("#Pkontrol")) {
        var table = $("#Pkontrol").DataTable();
        table.destroy();
    }

    $.ajax({
        url: "/api/kontrolDots",
        type: "GET",
        data: { date: tanggal },
        success: function (response) {
            $("#loadingSpinner").hide();
            response.forEach(function (item, index) {
                var alamat = `${item.kelurahan} ${item.rtrw} ${item.kecamatan}${item.kabupaten}`;
                item.diagnosa = `${item.diagnosa1}, ${item.diagnosa2}, ${item.diagnosa3}`;

                item.pasien = `${item.namapasien}`;
                item.actions = `<a href="#" class="edit"
                                data-id="${item.id}"
                                data-norm="${item.norm}"
                    data-nama="${item.namapasien}"
                    data-alamat="${alamat}"
                    data-layanan="${item.kelompok}"
                    data-notrans="${item.notrans}"
                    data-tgltrans="${item.tgltrans}"
                                ><i class="fas fa-pen-to-square pr-3"></i></a>`;
                item.no = index + 1;
                if (item.idKunjunganDots !== null) {
                    item.status = "sudah";
                } else {
                    item.status = "belum";
                }
            });

            $("#Pkontrol")
                .DataTable({
                    data: response,
                    columns: [
                        { data: "actions", className: "col-1 text-center" },
                        {
                            data: "status",
                            name: "idKunjunganDots",
                            render: function (data, type, row) {
                                var backgroundColor =
                                    data === "belum" ? "danger" : "success";
                                return `<div class="badge badge-${backgroundColor}">${data}</div>`;
                            },
                            className: "p-2",
                        },
                        { data: "nourut" },
                        { data: "norm" },
                        { data: "noktp" },
                        { data: "namapasien" },
                        { data: "dokterpoli" },
                        { data: "diagnosa" },
                    ],
                    order: [2, "asc"],
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
                .appendTo("#Pkontrol_wrapper .col-md-6:eq(0)");

            // Menangani klik pada tombol edit
            $(".edit").on("click", function (e) {
                e.preventDefault();
                var norm = $(this).data("norm");
                var date = $("#tanggal").val();
                searchByRM(norm, date);
            });
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        },
    });
}
function pasienTelat() {
    var tanggal = $("#tanggal").val();
    $("#loadingSpinner").show();
    if ($.fn.DataTable.isDataTable("#Ptelat")) {
        var table = $("#Ptelat").DataTable();
        table.destroy();
    }

    $.ajax({
        url: "/api/telatDots",
        type: "GET",
        data: { date: tanggal },
        success: function (response) {
            $("#loadingSpinner").hide();
            var today = new Date();

            response.forEach(function (item, index) {
                item.pasien = `${item.biodata.nama}`;
                item.actions = `<a href="#" class="edit"
                                data-id="${item.id}"
                                data-norm="${item.norm}"
                                data-petugas="${item.petugas}"
                                data-dokter="${item.dokter}"
                                ><i class="fas fa-pen-to-square pr-3"></i></a>`;
                item.no = index + 1;
                item.alamat = `${item.biodata.kelurahan} ${item.biodata.rtrw} ${item.biodata.kecamatan}${item.biodata.kabupaten}`;
                // Menghitung jarak antara nxKontrol dan hari ini dalam hitungan hari
                var nxKontrolDate = new Date(item.nxKontrol);
                var timeDiff = today.getTime() - nxKontrolDate.getTime();
                var daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));

                item.telat = daysDiff + " hari";
                item.dokter = `${item.dokter.gelar_d} ${item.dokter.nama} ${item.dokter.gelar_b}`;
            });

            $("#Ptelat")
                .DataTable({
                    data: response,
                    columns: [
                        { data: "actions", className: "col-1 text-center" },
                        { data: "telat" },
                        { data: "nxKontrol" },
                        { data: "no" },
                        { data: "norm" },
                        { data: "biodata.nohp" },
                        { data: "pasien" },
                        { data: "alamat" },
                        { data: "dokter" },
                    ],
                    order: [2, "asc"],
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
                .appendTo("#Ptelat_wrapper .col-md-6:eq(0)");

            // Menangani klik pada tombol edit
            $(".edit").on("click", function (e) {
                e.preventDefault();
                var id = $(this).data("id");
                var norm = $(this).data("norm");
                var petugas = $(this).data("petugas");
                var dokter = $(this).data("dokter");
                // Lakukan sesuatu dengan data yang diperoleh
                // ...
            });

            // Menangani klik pada tombol delete
            $(".delete").on("click", function (e) {
                e.preventDefault();
                var id = $(this).data("id");
                var norm = $(this).data("norm");
                var petugas = $(this).data("petugas");
                var dokter = $(this).data("dokter");
                // Lakukan sesuatu dengan data yang diperoleh
                // ...
            });
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        },
    });
}
function pasienDo() {
    var tanggal = $("#tanggal").val();
    $("#loadingSpinner").show();
    if ($.fn.DataTable.isDataTable("#Pdo")) {
        var table = $("#Pdo").DataTable();
        table.destroy();
    }

    $.ajax({
        url: "/api/doDots",
        type: "GET",
        data: { date: tanggal },
        success: function (response) {
            $("#loadingSpinner").hide();
            var today = new Date();
            response.forEach(function (item, index) {
                item.pasien = `${item.biodata.nama}`;
                item.actions = `<a href="#" class="edit"
                                data-id="${item.id}"
                                data-norm="${item.norm}"
                                data-petugas="${item.petugas}"
                                data-dokter="${item.dokter}"
                                ><i class="fas fa-pen-to-square pr-3"></i></a>`;
                item.no = index + 1;
                item.alamat = `${item.biodata.kelurahan} ${item.biodata.rtrw} ${item.biodata.kecamatan}${item.biodata.kabupaten}`;
                // Menghitung jarak antara nxKontrol dan hari ini dalam hitungan hari
                var nxKontrolDate = new Date(item.nxKontrol);
                var timeDiff = today.getTime() - nxKontrolDate.getTime();
                var daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));

                item.telat = daysDiff + " hari";
                item.dokter = `${item.dokter.gelar_d} ${item.dokter.nama} ${item.dokter.gelar_b}`;
            });

            $("#Pdo")
                .DataTable({
                    data: response,
                    columns: [
                        { data: "actions", className: "col-1 text-center" },
                        { data: "telat" },
                        { data: "nxKontrol" },
                        { data: "no" },
                        { data: "norm" },
                        { data: "biodata.nohp" },
                        { data: "pasien" },
                        { data: "alamat" },
                        { data: "dokter" },
                    ],
                    order: [2, "asc"],
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
                .appendTo("#Pdo_wrapper .col-md-6:eq(0)");

            // Menangani klik pada tombol edit
            $(".edit").on("click", function (e) {
                e.preventDefault();
                var id = $(this).data("id");
                var norm = $(this).data("norm");
                var petugas = $(this).data("petugas");
                var dokter = $(this).data("dokter");
                // Lakukan sesuatu dengan data yang diperoleh
                // ...
            });

            // Menangani klik pada tombol delete
            $(".delete").on("click", function (e) {
                e.preventDefault();
                var id = $(this).data("id");
                var norm = $(this).data("norm");
                var petugas = $(this).data("petugas");
                var dokter = $(this).data("dokter");
                // Lakukan sesuatu dengan data yang diperoleh
                // ...
            });
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        },
    });
}
function pasienTB() {
    $("#loadingSpinner").show();
    if ($.fn.DataTable.isDataTable("#Ptb,#modal-Ptb")) {
        var table = $("#Ptb,#modal-Ptb").DataTable();
        table.destroy();
    }

    $.ajax({
        url: "/api/Ptb",
        type: "POST",
        success: function (response) {
            $("#loadingSpinner").hide();
            var dataArray = response.data || [];
            dataArray.forEach(function (item, index) {
                item.pasien = `${item.biodata.nama}`;
                item.actions = `<button class="editTB bg-danger"
                                data-id="${item.id}"
                                data-norm="${item.norm}"
                                data-petugas="${item.petugas}"
                                data-dokter="${item.dokter}"
                                ><i class="fas fa-pen-to-square"></i></button>
                                <button class="riwayat bg-green"
                                data-id="${item.id}"
                                data-norm="${item.norm}"
                                data-petugas="${item.petugas}"
                                data-dokter="${item.dokter}"
                                onclick="showRiwayatKunjungan();" data-toggle="modal" data-target="#modal-RiwayatKunjungan"><i class="fa-regular fa-folder-open"></i></button>`;
                item.no = index + 1;
                item.dokter = `${item.dokter.gelar_d} ${item.dokter.biodata.nama} ${item.dokter.gelar_b}`;
                item.alamat = `${item.biodata.kelurahan} ${item.biodata.rtrw} ${item.biodata.kecamatan}${item.biodata.kabupaten}`;
            });

            $("#Ptb, #modal-Ptb")
                .DataTable({
                    data: dataArray,
                    columns: [
                        { data: "actions", className: "col-1 text-center" },
                        { data: "tglMulai", className: "col-1 text-center" },
                        { data: "no" },
                        { data: "norm" },
                        { data: "noHP" },
                        { data: "hasilBerobat" },
                        { data: "biodata.nama" },
                        { data: "alamat" },
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
                .appendTo("#Ptb_wrapper .col-md-6:eq(0)");

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

function showRiwayatKunjungan(norm) {
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
                item.actions = `<button class="editTB bg-danger"
                                data-id="${item.id}"
                                data-norm="${item.norm}"
                                ><i class="fas fa-pen-to-square"></i></button>`;
                item.no = index + 1;
                item.dokter = `${item.dokter.gelar_d} ${item.dokter.biodata.nama} ${item.dokter.gelar_b}`;
                item.petugas = `${item.petugas.gelar_d} ${item.petugas.biodata.nama} ${item.petugas.gelar_b}`;
                item.alamat = `${item.biodata.kelurahan} ${item.biodata.rtrw} ${item.biodata.kecamatan}${item.biodata.kabupaten}`;
            });

            $("#kunjDots")
                .DataTable({
                    data: dataArray,
                    columns: [
                        { data: "actions", className: "col-1 text-center" },
                        { data: "norm" },
                        { data: "created_at", className: "col-1 text-center" },
                        { data: "bb" },
                        { data: "blnKe" },
                        { data: "bta" },
                        { data: "obat.nmPengobatan" },
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
// function searchPTB(norm) {
//     // Show the modal

//     // Make an Ajax request to check patient ID existence
//     $.ajax({
//         url: "/api/Ptb",
//         type: "POST",
//         data: {
//             norm: norm,
//         },
//         success: function (response) {
//             console.log("Ajax response:", response);
//             // Check if the response is an array and has at least one element
//             if (Array.isArray(response) && response.length > 0) {
//                 // Extract data from the first element
//                 console.log("data Ditemukan");
//             } else {
//                 // Handle case where no data is returned
//                 console.error("No data returned in the response");
//                 $("#modal-pasienTB").modal("show");
//                 console.log(norm);

//             }
//         },
//         error: function (xhr) {
//             // Handle error
//             console.error("Error in Ajax request:", xhr.statusText);
//         },
//     });
// }
