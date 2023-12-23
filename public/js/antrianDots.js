var Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
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

$(document).on("select2:open", () => {
    document.querySelector(".select2-search__field").focus();
});

document.addEventListener("DOMContentLoaded", function () {
    // var tgltindInput = document.getElementById("tgltind");
    var tglTransInput = document.getElementById("waktu");

    function updateDateTime() {
        var now = new Date();
        var options = {
            timeZone: "Asia/Jakarta",
            year: "numeric",
            month: "2-digit",
            day: "2-digit",
            hour: "2-digit",
            minute: "2-digit",
            second: "2-digit",
        };
        // var formattedDate = now.toLocaleString("id-ID", options);
        var formattedDate = now
            .toLocaleString("id-ID", options)
            .replace(
                /(\d{4})\D(\d{2})\D(\d{2})\D(\d{2})\D(\d{2})\D(\d{2})/,
                "$1-$2-$3 $4.$5.$6"
            );
        // tgltindInput.value = formattedDate;
        tglTransInput.value = formattedDate;
    }

    setInterval(updateDateTime, 1000);

    // Mendapatkan elemen input tanggal
    var tglKunjInput = document.getElementById("tglKunj");

    // Mendapatkan tanggal hari ini
    var today = new Date();

    // Mendapatkan nilai tanggal hari ini dalam format "yyyy-mm-dd"
    var formattedDate = today.toISOString().split("T")[0];

    // Menetapkan nilai ke input tglKunj
    tglKunjInput.value = formattedDate;
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

function searchByRM(norm) {
    // Check if the patient ID exists in "/api/Ptb"
    $.ajax({
        url: "/api/Ptb",
        type: "POST",
        data: {
            norm: norm,
        },
        success: function (response) {
            if (response.exists) {
                // If the patient ID exists, proceed with the original AJAX request to "/api/cariRM"
                performCariRM(norm);
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
                        performCariRMmodal(norm);
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
function performCariRM(norm) {
    // The original AJAX request to "/api/cariRM"
    $.ajax({
        url: "/api/cariRM",
        type: "POST",
        data: {
            norm: norm,
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
                $("#dokter").val(dokter);
                $("#dokter").trigger("change");

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
function performCariRMmodal(norm) {
    // The original AJAX request to "/api/cariRM"
    $.ajax({
        url: "/api/cariRM",
        type: "POST",
        data: {
            norm: norm,
        },
        success: function (response) {
            if (response.length > 0) {
                var noRM = response[0].norm;
                var nama = response[0].biodata.nama;
                var notrans = response[0].notrans;
                var layanan = response[0].kelompok.kelompok;
                var dokter = response[0].petugas.p_dokter_poli;
                var alamat = `${response[0].biodata.kelurahan}, ${response[0].biodata.rtrw}, ${response[0].biodata.kecamatan}, ${response[0].biodata.kabupaten}`;

                $("#modal-pasienTB #modal-norm").val(noRM);
                $("#modal-pasienTB #modal-nama").val(nama);
                $("#modal-pasienTB #modal-alamat").val(alamat);
                $("#modal-pasienTB #modal-notrans").val(notrans);
                $("#modal-pasienTB #modal-layanan").val(layanan);
                $("#modal-pasienTB #modal-dokter").val(dokter);
                $("#modal-pasienTB #modal-dokter").trigger("change");
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
                var alamat = `${item.biodata.kelurahan} ${item.biodata.rtrw} ${item.biodata.kecamatan}${item.biodata.kabupaten}`;

                item.pasien = `${item.biodata.nama}`;
                item.actions = `<a href="#" class="edit"
                                data-id="${item.id}"
                                data-norm="${item.norm}"
                    data-dokter="${item.biodata.nama}"
                    data-kddokter="${item.nip}"
                    data-alamat="${alamat}"
                    data-layanan="${item.kelompok}"
                    data-notrans="${item.notrans}"
                    data-tgltrans="${item.tgltrans}"
                                ><i class="fas fa-pen-to-square pr-3"></i></a>`;
                item.no = index + 1;
                item.dokter = `${item.petugas.pegawai.gelar_d} ${item.petugas.pegawai.nama} ${item.petugas.pegawai.gelar_b}`;
                if (item.dots !== null) {
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
                        { data: "status" },
                        { data: "no" },
                        { data: "norm" },
                        { data: "biodata.nohp" },
                        { data: "blnKe" },
                        { data: "pasien" },
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
                .appendTo("#Pkontrol_wrapper .col-md-6:eq(0)");

            // Menangani klik pada tombol edit
            $(".edit").on("click", function (e) {
                e.preventDefault();
                var norm = $(this).data("norm");
                searchPTB(norm);
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
    if ($.fn.DataTable.isDataTable("#Ptb")) {
        var table = $("#Ptb").DataTable();
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
                item.actions = `<a href="#" class="edit"
                                data-id="${item.id}"
                                data-norm="${item.norm}"
                                data-petugas="${item.petugas}"
                                data-dokter="${item.dokter}"
                                ><i class="fas fa-pen-to-square pr-3"></i></a>`;
                item.no = index + 1;
                item.dokter = `${item.dokter.gelar_d} ${item.dokter.nama} ${item.dokter.gelar_b}`;
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

            // Menangani klik pada tombol edit
            $(".edit").on("click", function (e) {
                e.preventDefault();
                var norm = $(this).data("norm");

                searchPTB(norm);
            });
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        },
    });
}

function searchPTB(norm) {
    // Show the modal
    $("#modal-pasienTB").modal("show");

    // Make an Ajax request to check patient ID existence
    $.ajax({
        url: "/api/Ptb",
        type: "POST",
        data: {
            norm: norm,
        },
        success: function (response) {
            console.log("Ajax response:", response);
            // Check if the response is an array and has at least one element
            if (Array.isArray(response) && response.length > 0) {
                // Extract data from the first element
                var data = response[0];

                // Check if necessary properties are present in the response
                if (data.norm && data.biodata && data.biodata.kelompok) {
                    // Populate modal fields with data
                    $("#modal-pasienTB #modal-norm").val(data.norm);
                    $("#modal-pasienTB #modal-layanan").val(
                        data.biodata.kelompok
                    );
                    $("#modal-pasienTB #modal-nama").val(data.biodata.nama);
                    $("#modal-pasienTB #modal-hp").val(data.noHP);
                    $("#modal-pasienTB #modal-status").val(data.hasilBerobat);

                    // Construct the address string
                    var alamat = `${data.biodata.kelurahan}, ${data.biodata.rtrw}, ${data.biodata.kecamatan}, ${data.biodata.kabupaten}`;
                    $("#modal-pasienTB #modal-alamat").val(alamat);

                    // Continue populating other fields...

                    // Trigger change events if needed
                    $("#modal-pasienTB #modal-dokter").trigger("change");
                    $("#modal-pasienTB #modal-petugas").trigger("change");
                } else {
                    // Handle case where essential data is missing
                    console.error("Incomplete or missing data in the response");
                }
            } else {
                // Handle case where no data is returned
                console.error("No data returned in the response");
            }
        },
        error: function (xhr) {
            // Handle error
            console.error("Error in Ajax request:", xhr.statusText);
        },
    });
}
