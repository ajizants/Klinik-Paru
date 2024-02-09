var Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
});

function searchByRM(norm) {
    $.ajax({
        url: "/api/cariRM",
        type: "post",
        data: {
            norm: norm,
        },
        success: function (response) {
            if (response && response.length > 0) {
                Swal.fire({
                    icon: "success",
                    title: "Data pasien ditemukan, lanjutkan transaksi...!!!",
                });
                // Mendapatkan data dari respons JSON
                var noRM = response[0].norm; // Menggunakan indeks 0 karena respons adalah array
                var nama = response[0].biodata.nama;
                var notrans = response[0].notrans;
                var layanan = response[0].kelompok.kelompok;
                var dokter = response[0].petugas.p_dokter_poli;
                var alamat = `${response[0].biodata.kelurahan}, ${response[0].biodata.rtrw}, ${response[0].biodata.kecamatan}, ${response[0].biodata.kabupaten}`;
                // Dapatkan data lainnya dari respons JSON sesuai kebutuhan

                // Mengisikan data ke dalam elemen-elemen HTML
                $("#norm").val(noRM);
                $("#nama").val(nama);
                $("#alamat").val(alamat);
                $("#notrans").val(notrans);
                $("#layanan").val(layanan);
                $("#dokter").val(dokter);
                $("#dokter").trigger("change");
                // Mengisi elemen-elemen lainnya sesuai kebutuhan
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Data pasien tidak ditemukan pada kunjungan hari ini...!!!",
                });
            }
        },
        error: function (xhr) {
            // Handle error
        },
    });
}
async function searchRMObat() {
    Swal.fire({
        icon: "success",
        title: "Sedang mencarikan data pasien...!!!",
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
            $("#layanan").val("UMUM");
            $("#dokter").val("198907252019022004").trigger("change");
            $("#apoteker").val("197609262011012003").trigger("change");

            // Additional function calls as needed
            dataLab();
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

var table; // Declare the DataTable variable outside the function

function layanan() {
    if ($.fn.DataTable.isDataTable("#tabelPemeriksaan")) {
        table.destroy();
    }
    const kelas = 9;
    table = $("#tabelPemeriksaan").DataTable({
        ajax: {
            url: "/api/layananlab",
            type: "POST",
            dataType: "json",
            dataSrc: "data", // Assuming your data is nested under a key called 'data'
            data: { kelas: kelas },
        },
        columns: [
            {
                data: null,
                render: function (data, type, row) {
                    return `<input type="checkbox" class="select-checkbox mt-1 data-checkbox" id="${row.idLayanan}">`;
                },
            },
            {
                data: "nmLayanan",
                render: function (data, type, row) {
                    return `<label type="text" class="form-check-label mt-1" for="${row.idLayanan}" style="font-size: medium;">${data}</label>`;
                },
            },
            {
                data: null,
                render: function (data, type, row) {
                    return `<input type="text" class="form-control-sm col" readonly id="ket${row.idLayanan}">`;
                },
            },
            {
                data: "tarif",
                render: function (data, type, row) {
                    // Ubah nilai ke dalam format mata uang Rupiah
                    var formattedTarif = parseInt(data).toLocaleString(
                        "id-ID",
                        {
                            style: "currency",
                            currency: "IDR",
                            minimumFractionDigits: 0,
                        }
                    );
                    return `<label type="text" class="form-check-label mt-1" for="${row.idLayanan}" style="font-size: medium;">${formattedTarif}</label>`;
                },
            },
        ],
        order: [1, "asc"],
        scrollY: "335px", // Atur tinggi scrol
        scrollCollapse: true, // Biarkan scrol jika kurang dari tinggi yang ditentukan
        paging: false, // Matikan paging
    });

    initializeTableEvents();
}

// Function to toggle readonly attribute on text inputs based on checkbox state
function toggleInputReadonly(isChecked, idLayanan) {
    var inputId = "#hasil" + idLayanan;
    $(inputId).prop("readonly", !isChecked);
}
function initializeTableEvents() {
    // Event listener for pilih-semua checkbox
    $("#pilih-semua").on("change", function () {
        var isChecked = $(this).prop("checked");
        $('input[type="checkbox"]', table.rows().nodes()).prop(
            "checked",
            isChecked
        );
        toggleInputReadonly(isChecked);
    });

    // Event listener for individual checkboxes
    $("#tabelPemeriksaan tbody").on(
        "change",
        'input[type="checkbox"]',
        function () {
            var isChecked = $(this).prop("checked");
            var rowData = table.row($(this).closest("tr")).data();

            toggleInputReadonly(isChecked, rowData.idLayanan);
        }
    );

    // Event listener for individual text inputs
    $("#tabelPemeriksaan tbody").on(
        "change",
        'input[type="text"]',
        function () {
            var inputValue = $(this).val();
            var rowData = table.row($(this).closest("tr")).data();
            console.log("Input ID:", rowData.idLayanan, "Value:", inputValue);
        }
    );

    // Function to toggle readonly attribute on text inputs based on checkbox state
    function toggleInputReadonly(isChecked, idLayanan) {
        var inputId = "#ket" + idLayanan;
        $(inputId).prop("readonly", !isChecked);
    }
}

function dataLab() {
    var notrans = $("#notrans").val();

    if ($.fn.DataTable.isDataTable("#dataTrans")) {
        var table = $("#dataTrans").DataTable();
        table.destroy();
    }

    $.ajax({
        url: "/api/cariLaboratorium",
        type: "POST",
        data: { notrans: notrans },
        success: function (response) {
            response.forEach(function (item, index) {
                item.actions = `<a href="" class="edit"
                                    data-id="${item.IdLab}"
                                    data-norm="${item.norm}"
                                    ><i class="fas fa-pen-to-square pr-3"></i></a>
                                <a href="" class="delete"
                                    data-id="${item.IdLab}"
                                    data-norm="${item.norm}"
                                    ><i class="fas fa-trash"></i></a>`;
                item.no = index + 1;
            });

            $("#dataTrans").DataTable({
                data: response,
                columns: [
                    { data: "actions", className: "px-0 col-1 text-center" },
                    { data: "no" },
                    { data: "NORM" },
                    { data: "NamaPemeriksaan" },
                    { data: "Ket" },
                ],
                order: [1, "asc"],
                scrollY: "250px", // Atur tinggi scrol
                scrollCollapse: true, // Biarkan scrol jika kurang dari tinggi yang ditentukan
                paging: false, // Matikan paging
            });
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        },
    });
}
function simpan() {
    var dataTerpilih = [];
    var norm = $("#norm").val();
    var notrans = $("#notrans").val();
    var petugas = $("#analis").val();
    var dokter = $("#dokter").val();

    // Validasi untuk memeriksa elemen yang tidak boleh kosong
    if (!norm || !notrans || !petugas || !dokter) {
        var dataKurang = [];
        if (!norm) dataKurang.push("No RM ");
        if (!notrans) dataKurang.push("Nomor Transaksi ");
        if (!petugas) dataKurang.push("Petugas ");
        if (!dokter) dataKurang.push("Dokter ");

        // Menampilkan notifikasi menggunakan Toast.fire
        Swal.fire({
            icon: "error",
            title:
                "Data Tidak Lengkap...!!! " +
                dataKurang.join(", ") +
                "Belum Diisi",
        });
    } else {
        var pemeriksaan = $(".data-checkbox:checked");

        // Validasi untuk memeriksa setidaknya satu checkbox telah dicentang
        if (pemeriksaan.length === 0) {
            Swal.fire({
                icon: "error",
                title: "Mohon pilih setidaknya satu layanan.",
            });
        } else {
            dataTerpilih = pemeriksaan
                .map(function () {
                    var id = $(this).attr("id");
                    var ket = $("#ket" + id).val();

                    return {
                        idLayanan: id,
                        norm: norm,
                        notrans: notrans,
                        ket: ket,
                        petugas: petugas,
                        dokter: dokter,
                    };
                })
                .get();

            // Filter elemen yang null (elemen yang kosong) dari array dataTerpilih
            dataTerpilih = dataTerpilih.filter(function (item) {
                return item !== null;
            });
        }

        fetch("/api/addTransaksiLab", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                // Tambahkan header lain jika diperlukan
            },
            body: JSON.stringify({ dataTerpilih: dataTerpilih }),
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then((data) => {
                console.log(data);
                Swal.fire({
                    icon: "success",
                    title: "Data berhasil tersimpan...!!!",
                });
                dataLab();
                $('input[type="checkbox"]', table.rows().nodes()).prop(
                    "checked",
                    false
                );
                toggleInputReadonly(false);
            })
            .catch((error) => {
                console.error(
                    "There has been a problem with your fetch operation:",
                    error
                );
            });
    }
}

$(document).ready(function () {
    $("#norm").on("keyup", function (event) {
        if (event.key === "Enter") {
            event.preventDefault();
            console.log("Enter pressed");
            formatNorm($("#norm"));
            searchByRM($("#norm").val());
        }
    });
    $("#tabelData,#dataTrans").DataTable({
        scrollY: "250px", // Atur tinggi scrol
    });
    populateDokterOptions();
    populateAnalisOptions();
    layanan();
});
