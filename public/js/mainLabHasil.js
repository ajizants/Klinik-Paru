var Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
});

async function dataLab(notrans) {
    if ($.fn.DataTable.isDataTable("#inputHasil")) {
        var table = $("#inputHasil").DataTable();
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

        // Fetch data from /api/analis separately
        const analisResponse = await fetch("/api/analis", {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
            },
        });

        if (!analisResponse.ok) {
            throw new Error(`HTTP error! Status: ${analisResponse.status}`);
        }

        const analisData = await analisResponse.json();

        data.forEach((item, index) => {
            item.actions = `<a class="delete"
                                data-id="${item.IdLab}"
                                data-layanan="${item.NamaPemeriksaan}"
                                onclick="deletLab();"><i class="fas fa-trash"></i></a>`;
            item.no = index + 1;
        });

        $("#inputHasil").DataTable({
            data: data,
            columns: [
                { data: "actions", className: "px-0 col-1 text-center" },
                { data: "no" },
                { data: "NORM" },
                {
                    data: "NamaPemeriksaan",
                    render: function (data, type, row) {
                        return `<input type="text" class="form-control-sm col-6 hasil" id="hasil${data.idLayanan}" value="${data.idLayanan}" readonly>`;
                    },
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        return `<input type="text" class="form-control-sm col-6 hasil" id="hasil${row.idLayanan}">`;
                    },
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        // Use row.idLayanan to create a unique identifier for each input field
                        var inputId = "analis" + row.idLayanan;

                        var inputField = `<select id="${inputId}" class="form-control-sm col-6 analis">`;
                        analisData.forEach(function (petugas) {
                            inputField += `<option value="${petugas.nip}">${petugas.gelar_d} ${petugas.nama} ${petugas.gelar_b}</option>`;
                        });
                        inputField += "</select>";

                        return inputField;
                    },
                },
            ],
            order: [1, "asc"],
            scrollY: "320px",
            scrollCollapse: true,
            paging: false,
        });
    } catch (error) {
        console.error("Error:", error.message);
    }
}

function simpan() {
    var dataTerpilih = [];
    var norm = $("#norm").val();
    var notrans = $("#notrans").val();

    if (!norm || !notrans) {
        var dataKurang = [];
        if (!norm) dataKurang.push("No RM ");
        if (!notrans) dataKurang.push("Nomor Transaksi ");

        Swal.fire({
            icon: "error",
            title:
                "Data Tidak Lengkap...!!! " +
                dataKurang.join(", ") +
                "Belum Diisi",
        });
    } else {
        var hasil = $(".hasil");

        if (hasil.length === 0) {
            Swal.fire({
                icon: "error",
                title: "Mohon pilih setidaknya satu layanan.",
            });
        } else {
            dataTerpilih = hasil
                .map(function () {
                    var id = $(this).attr("id");
                    var hasil = $("#hasil" + id).val();
                    var petugas = $("#analis" + id).val();

                    return {
                        idLayanan: id,
                        norm: norm,
                        notrans: notrans,
                        hasil: hasil,
                        petugas: petugas,
                    };
                })
                .get();

            dataTerpilih = dataTerpilih.filter(function (item) {
                return item !== null;
            });
        }

        console.log(dataTerpilih);
        // fetch("/api/addHasilLab", {
        //     method: "POST",
        //     headers: {
        //         "Content-Type": "application/json",
        //     },
        //     body: JSON.stringify({ dataTerpilih: dataTerpilih }),
        // })
        //     .then((response) => {
        //         if (!response.ok) {
        //             throw new Error("Network response was not ok");
        //         }
        //         return response.json();
        //     })
        //     .then((data) => {
        //         console.log(data);
        //         Swal.fire({
        //             icon: "success",
        //             title: "Data berhasil tersimpan...!!!",
        //         });
        //         dataLab();
        //         antrian();

        //         // toggleInputReadonly(false);
        //     })
        //     .catch((error) => {
        //         console.error(
        //             "There has been a problem with your fetch operation:",
        //             error
        //         );
        //         Swal.fire({
        //             icon: "error",
        //             title:
        //                 "There has been a problem with your fetch operation:" +
        //                 error,
        //         });
        //     });
    }
}

function resetForm(message) {
    $('table thead input[type="checkbox"]').prop("checked", false);
    $('table tbody input[type="checkbox"]').prop("checked", false);
    document.getElementById("frmident").reset();
    document.getElementById("frmPetugas").reset();
    $("#analis,#dokter,#tujuan").trigger("change");

    if ($.fn.DataTable.isDataTable("#dataTrans")) {
        let tableTrans = $("#dataTrans").DataTable();
        tableTrans.clear().destroy();
    }
    Swal.fire({
        icon: "info",
        title: "Transaksi " + message + " maturnuwun...!!!",
    });
    scrollToTop();
}

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
                var nik = response[0].biodata.noktp;
                var notrans = response[0].notrans;
                var tgltrans = response[0].tgltrans;
                var layanan = response[0].kelompok.kelompok;
                var dokter = response[0].petugas.p_dokter_poli;
                var alamat = `${response[0].biodata.kelurahan}, ${response[0].biodata.rtrw}, ${response[0].biodata.kecamatan}, ${response[0].biodata.kabupaten}`;
                // Dapatkan data lainnya dari respons JSON sesuai kebutuhan

                // Mengisikan data ke dalam elemen-elemen HTML
                $("#norm").val(noRM);
                $("#nama").val(nama);
                $("#nik").val(nik);
                $("#alamat").val(alamat);
                $("#notrans").val(notrans);
                $("#tgltrans").val(tgltrans);
                $("#layanan").val(layanan);
                $("#dokter").val(dokter);
                $("#dokter").trigger("change");
                // Mengisi elemen-elemen lainnya sesuai kebutuhan
                dataLab(notrans);
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
            var nik = response[0].noktp;
            var notrans = response[0].notrans;
            var alamat = `${response[0].kelurahan}, ${response[0].rtrw}, ${response[0].kecamatan}, ${response[0].kabupaten}`;

            // Updating HTML elements with the extracted data
            $("#norm").val(noRM);
            $("#nama").val(nama);
            $("#nik").val(nik);
            $("#alamat").val(alamat);
            $("#notrans").val(notrans);
            $("#layanan").val("UMUM");
            $("#dokter").val("198907252019022004").trigger("change");
            $("#apoteker").val("197609262011012003").trigger("change");

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
$(document).ready(function () {
    setTodayDate();
    $("#norm").on("keyup", function (event) {
        if (event.key === "Enter") {
            event.preventDefault();
            formatNorm($("#norm"));
            searchByRM($("#norm").val());
        }
    });
    $("#tabelData,#dataTrans").DataTable({
        scrollY: "200px",
    });
    populateDokterOptions();
    populateAnalisHasil();

    $("#dataAntrian").on("click", ".aksi-button", function (e) {
        e.preventDefault();
        $("#add").show();
        $("#edit").hide();
        var norm = $(this).data("norm");
        var nama = $(this).data("nama");
        var nik = $(this).data("nik");
        var dokter = $(this).data("kddokter");
        var alamat = $(this).data("alamat");
        var layanan = $(this).data("layanan");
        var notrans = $(this).data("notrans");
        var tgltrans = $(this).data("tgltrans");

        $("#norm").val(norm);
        $("#nik").val(nik);
        $("#nama").val(nama);
        $("#dokter").val(dokter).trigger("change");
        $("#apoteker").val("197609262011012003").trigger("change");
        $("#alamat").val(alamat);
        $("#layanan").val(layanan);
        $("#notrans").val(notrans);
        $("#tgltrans").val(tgltrans);

        scrollToInputSection();
        dataLab();
    });

    $("#dataAntrian").on("click", ".panggil", function (e) {
        e.preventDefault();

        let panggilData = $(this).data("panggil");
        console.log(
            "🚀 ~ file: mainFarmasi.js:478 ~ panggilData:",
            panggilData
        );

        panggilPasien(panggilData);
    });
    $("#dataTrans").on("click", ".delete", function (e) {
        e.preventDefault();
        let idLab = $(this).data("id");
        let layanan = $(this).data("layanan");
        deletLab(idLab, layanan);
    });
});
