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

let table; // Declare the DataTable variable outside the function

function layanan(kelas, tb) {
    // console.log("🚀 ~ layanan ~ tb:", tb);
    // console.log("🚀 ~ layanan ~ kelas:", kelas);
    if ($.fn.DataTable.isDataTable("#" + tb)) {
        table.destroy();
    }

    // let kelas = kelas;
    table = $("#" + tb).DataTable({
        ajax: {
            url: "/api/layananlab",
            type: "POST",
            dataType: "json",
            dataSrc: "data",
            data: { kelas: kelas },
        },
        columns: [
            {
                data: null,
                render: function (data, type, row) {
                    return `<input type="checkbox" class="select-checkbox mt-1 data-checkbox ${tb}" id="${row.idLayanan}">`;
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
                    return `<input type="text" class="form-control-sm col-6" readonly id="ket${row.idLayanan}">`;
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
        scrollY: "300px", // Atur tinggi scrol
        scrollCollapse: false, // Biarkan scrol jika kurang dari tinggi yang ditentukan
        paging: false, // Matikan paging
        responsive: true,
    });

    initializeTableEvents(tb);
}

function toggleInputReadonly(isChecked, idLayanan) {
    var inputId = "#ket" + idLayanan;
    $(inputId).prop("readonly", !isChecked);
}
function initializeTableEvents(tb, idTabel) {
    $("#" + tb + "tbody").on("change", 'input[type="checkbox"]', function () {
        var isChecked = $(this).prop("checked");
        var rowData = table.row($(this).closest("tr")).data();
        toggleInputReadonly(isChecked, rowData.idLayanan);
    });

    // Event listener for individual text inputs
    $("#" + tb + "tbody").on("change", 'input[type="text"]', function () {
        var inputValue = $(this).val();
        var rowData = table.row($(this).closest("tr")).data();
    });
    toggleInputReadonly();
}

async function dataLab() {
    var notrans = $("#notrans").val();

    if ($.fn.DataTable.isDataTable("#dataTrans")) {
        var table = $("#dataTrans").DataTable();
        table.destroy();
    }

    try {
        const response = await fetch("/api/cariLaboratorium", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ notrans: notrans }),
        });

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const data = await response.json();

        data.forEach((item, index) => {
            item.actions = `<a href="" class="edit"
                                data-id="${item.idLab}"
                                data-norm="${item.norm}"
                                ><i class="fas fa-pen-to-square pr-3"></i></a>
                            <a href="" class="delete"
                                data-id="${item.idLab}"
                                data-norm="${item.norm}"
                                ><i class="fas fa-trash"></i></a>`;
            item.no = index + 1;
        });

        $("#dataTrans").DataTable({
            data: data,
            columns: [
                { data: "actions", className: "px-0 col-1 text-center" },
                { data: "no" },
                { data: "norm" },
                { data: "layanan.nmLayanan" },
                { data: "ket" },
            ],
            order: [1, "asc"],
            scrollY: "320px", // Atur tinggi scroll
            scrollCollapse: true, // Biarkan scroll jika kurang dari tinggi yang ditentukan
            paging: false, // Matikan paging
        });
    } catch (error) {
        console.error("Error:", error.message);
    }
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

        console.log(dataTerpilih);
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
                Swal.fire({
                    icon: "error",
                    title:
                        "There has been a problem with your fetch operation:" +
                        error,
                });
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
        scrollY: "300px", // Atur tinggi scrol
    });
    populateDokterOptions();
    populateAnalisOptions();
    layanan(91, "hematologi");
    layanan(92, "kimia");
    layanan(93, "imuno");
    layanan(94, "bakteriologi");
});

document.addEventListener("DOMContentLoaded", function () {
    // Fungsi untuk menangani klik pada checkbox "pilih-semua" untuk setiap tabel
    function handlePilihSemuaClick(pilihSemuaId, checkboxClass) {
        const pilihSemuaCheckbox = document.getElementById(pilihSemuaId);

        pilihSemuaCheckbox.addEventListener("click", function () {
            const checkboxes = document.querySelectorAll(`.${checkboxClass}`);

            checkboxes.forEach(function (checkbox) {
                checkbox.checked = pilihSemuaCheckbox.checked;
            });
        });
    }

    // Panggil fungsi untuk setiap tabel
    handlePilihSemuaClick("pilih-hematologi", "hematologi");
    handlePilihSemuaClick("pilih-bakteriologi", "bakteriologi");
    handlePilihSemuaClick("pilih-imuno", "imuno");
    handlePilihSemuaClick("pilih-kimia", "kimia");
    // ... tambahkan sesuai kebutuhan
});
