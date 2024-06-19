var Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
});

function fetchAntrianAll(tanggal, ruang, callback) {
    $.ajax({
        url: "/api/antrianAll",
        type: "POST",
        data: {
            date: tanggal,
            ruang: ruang,
        },
        success: function (response) {
            callback(response);
        },
        error: function (xhr) {},
    });
}
function initializeAntrianAll(response) {
    response.forEach(function (item) {
        item.aksi = `<a href="#" class="aksi-button px-2 btn btn-sm btn-danger"
        data-norm="${item.norm}"
        data-nama="${item.namapasien}"
        data-dokter="${item.dokterpoli}"
        data-kddokter="${item.nip}"
        data-alamat="${alamat}"
        data-kelompok="${item.kelompok}"
        data-notrans="${item.notrans}"
        data-nik="${item.noktp}"
        data-tgltrans="${item.tgltrans}"><i class="fas fa-pen-to-square"></i></a>`;
    });

    $("#antrianall").DataTable({
        data: response,
        columns: [
            { data: "aksi", className: "text-center p-2" },
            { data: "lokasi", className: "col-1 p-2" },
            { data: "nourut", className: "text-center p-2" },
            { data: "norm", className: "col-1 text-center p-2" },
            { data: "layanan", className: "col-1 p-2" },
            { data: "kunjungan", className: "col-1 p-2" },
            { data: "namapasien", className: "p-2" },
            { data: "dokterpoli", className: "p-2" },
        ],
        order: [2, "asc"],
        paging: true,
        lengthMenu: [
            [5, 10, 25, 50, -1],
            [5, 10, 25, 50, "All"],
        ],
        pageLength: 10,
        responsive: true,
        lengthChange: true,
        autoWidth: false,
        buttons: ["copyHtml5", "excelHtml5", "pdfHtml5", "colvis"],
    });
    // .buttons()
    // .container()
    // .appendTo("#antrianall_wrapper .col-md-6:eq(0)");
}
function antrianAll(ruang) {
    $("#loadingSpinner").show();
    var tanggal = $("#tanggal").val();

    fetchAntrianAll(tanggal, ruang, function (response) {
        $("#loadingSpinner").hide();
        if ($.fn.DataTable.isDataTable("#antrianall")) {
            var table = $("#antrianall").DataTable();
            response.forEach(function (item) {
                item.aksi = `<a href="#" class="aksi-button px-2 btn btn-sm btn-danger"
                data-norm="${item.norm}"
                data-nama="${item.namapasien}"
                data-dokter="${item.dokterpoli}"
                data-kddokter="${item.nip}"
                data-alamat="${alamat}"
                data-kelompok="${item.kelompok}"
                data-notrans="${item.notrans}"
                data-nik="${item.noktp}"
                data-tgltrans="${item.tgltrans}"><i class="fas fa-pen-to-square"></i></a>`;
            });
            table.clear().rows.add(response).draw();
        } else {
            initializeAntrianAll(response);
        }
    });
}

//pasien Kominfo
function cariKominfo(norm, tgl) {
    var normValue = norm ? norm : $("#norm").val();
    var tgl = tgl ? tgl : $("#tglRO").val();
    // console.log(normValue)
    // Add leading zeros if the value has less than 6 digits
    while (normValue.length < 6) {
        normValue = "0" + normValue;
    }
    if (
        isNaN(normValue) ||
        normValue === null ||
        normValue === undefined ||
        normValue == 0
    ) {
        Swal.fire({
            icon: "error",
            title: "No Rm Tidak Valid...!!! ",
        });
    } else {
        Swal.fire({
            icon: "info",
            title: "Sedang Mencari Data Pasien di Aplikasi KOMINFO\n Mohon Ditunggu ...!!!",
            // showConfirmButton: false, // Menyembunyikan tombol OK
            // allowOutsideClick: false, // Mencegah interaksi di luar dialog
            didOpen: () => {
                Swal.showLoading(); // Menampilkan loading spinner
            },
        });

        $.ajax({
            // url: "http://kkpm.local/api/pasienKominfo",
            url: "/api/antrianKominfo",
            method: "POST",
            data: {
                no_rm: normValue,
                tanggal: tgl,
            },
            dataType: "json",
            success: function (response) {
                if (response.error) {
                    console.error("Error: " + response.error);
                } else if (
                    response.metadata &&
                    response.metadata.code === 404
                ) {
                    //tidak mendaftar
                    Swal.fire({
                        icon: "info",
                        title: response.metadata.message,
                    });
                    // Swal.close();
                } else {
                    Swal.fire({
                        icon: "info",
                        title: response.metadata.message,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                    });
                    console.log("🚀 ~ cariKominfo ~ data:", response);
                    var pasien = response.response.pasien[0];
                    console.log("🚀 ~ cariKominfo ~ pasien:", pasien);

                    var pendaftaran = response.response.pendaftaran[0];
                    console.log("🚀 ~ cariKominfo ~ pendaftaran:", pendaftaran);

                    // Mengatur nilai untuk form fields
                    $("#layanan")
                        .val(pendaftaran.penjamin_nama)
                        .trigger("change"); // Trigger change event jika diperlukan
                    $("#norm").val(pasien.pasien_no_rm);
                    $("#nama").val(pasien.pasien_nama);
                    $("#alamat").val(pasien.pasien_alamat);
                    $("#notrans").val(pendaftaran.no_trans);
                    $("#dokter").val(pendaftaran.nip_dokter).trigger("change");
                    setTimeout(function () {
                        Swal.close();
                        scrollToInputSection();
                    }, 1000);
                }
            },

            error: function (error) {
                console.error("Error fetching data:", error);
            },
        });
    }
}

async function searchRMObat(norm) {
    Swal.fire({
        icon: "success",
        title: "Sedang mencarikan data pasien...!!!",
        showConfirmButton: false,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });
    // var norm = "000001";
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
                showConfirmButton: false,
                allowOutsideClick: false,
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
            setTimeout(Swal.close, 1000);
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
//IGD
function populateTindakanOptions() {
    var tindakanSelectElement = $("#tindakan");
    $.get("/api/jenistindakan", function (data) {
        data.sort(function (a, b) {
            var namaA = a.nmTindakan.toUpperCase();
            var namaB = b.nmTindakan.toUpperCase();
            if (namaA < namaB) {
                return -1;
            }
            if (namaA > namaB) {
                return 1;
            }
            return 0;
        });
        data.forEach(function (tindakan) {
            var option = new Option(
                tindakan.nmTindakan,
                tindakan.kdTindakan,
                false,
                false
            );
            tindakanSelectElement.append(option).trigger("change");
        });
    });
}
function populateBmhpOptions() {
    var obatSelectElement = $("#bmhp");
    var hargaBeliElement = $("#beli");
    var hargaJualElement = $("#jual");
    var productID = $("#productID");
    obatSelectElement.empty();
    var placeholderOption = new Option(
        "--- Pilih Nama Obat ---",
        "",
        true,
        true
    );
    obatSelectElement.append(placeholderOption).trigger("change");
    $.get("/api/bmhp", function (data) {
        // data.sort(function (a, b) {
        //     var namaA = a.nmObat.toUpperCase();
        //     var namaB = b.nmObat.toUpperCase();
        //     if (namaA < namaB) {
        //         return -1;
        //     }
        //     if (namaA > namaB) {
        //         return 1;
        //     }
        //     return 0;
        // });

        data.forEach(function (obat) {
            var obatStok =
                obat.nmObat +
                " \u00A0\u00A0\u00A0\u00A0\u00A0\u00A0----(Stok: " +
                obat.sisa +
                ")----";
            var option = new Option(obatStok, obat.id, false, false);
            // var option = new Option(obat.nmObat, obat.product_id, false, false);
            obatSelectElement.append(option).trigger("change");
        });

        // Event listener untuk mengisi harga beli dan harga jual saat obat dipilih
        obatSelectElement.on("change", function () {
            var selectedObatId = $(this).val();
            var selectedObat = data.find(function (obat) {
                return obat.id == selectedObatId;
            });

            if (selectedObat) {
                // Mengisi nilai harga beli dan harga jual jika obat ditemukan
                hargaBeliElement.val(
                    selectedObat.hargaBeli
                        ? selectedObat.hargaBeli.toLocaleString(undefined)
                        : ""
                );
                hargaJualElement.val(
                    selectedObat.hargaJual
                        ? selectedObat.hargaJual.toLocaleString(undefined)
                        : ""
                );
                productID.val(
                    selectedObat.product_id
                        ? selectedObat.product_id.toLocaleString()
                        : ""
                );
                document.querySelector("#qty").focus();
            } else {
                // Mengosongkan nilai harga beli dan harga jual jika obat tidak ditemukan
                hargaBeliElement.val("");
                hargaJualElement.val("");
            }
            document.querySelector("#qty").focus();
        });
    });
}

//KASIR
function populateLayananOptions(kelas) {
    var LayananSelectElement = $("#jenislayanan");
    LayananSelectElement.empty();
    $.get("/api/layanan", { kelas: kelas }, function (data) {
        console.log(data);
        data.sort(function (a, b) {
            var namaA = a.nmLayanan.toUpperCase();
            var namaB = b.nmLayanan.toUpperCase();
            if (namaA < namaB) {
                return -1;
            }
            if (namaA > namaB) {
                return 1;
            }
            return 0;
        });
        data.forEach(function (layanan) {
            var option = new Option(
                layanan.nmLayanan,
                layanan.idLayanan,
                false,
                false
            );
            LayananSelectElement.append(option).trigger("change");
            console.log(
                "Options added to select element:",
                LayananSelectElement.html()
            );
        });
    });
}

function populateJaminan() {
    var jaminan = $("#jaminan");

    $.get("/api/jaminan", function (data) {
        data.sort(function (a, b) {
            // Add your sorting logic here if needed
            // Example: return a.name.localeCompare(b.name);
        });

        data.forEach(function (jaminanData) {
            var kelompok = jaminanData.kelompok;
            var kode = jaminanData.kkelompok;

            // Creating option elements
            var option = new Option(kelompok, kode, false, false);

            // Appending options to both jaminan and dokterModals
            jaminan.append(option).trigger("change");
        });
    });
}
function populateTujuan() {
    var tujuanSelectElement = $("#tujuan");
    $.get("/api/tujuan", function (data) {
        data.sort(function (a, b) {
            var namaLengkapA = a.tujuan;
            var namaLengkapB = b.tujuan;
            return namaLengkapA.localeCompare(namaLengkapB, undefined, {
                numeric: true,
                sensitivity: "base",
            });
        });
        data.forEach(function (tujuan) {
            var nmTujuan = tujuan.tujuan;
            var kode = tujuan.kd_tujuan.toString();
            var option = new Option(nmTujuan, kode, false, false);
            tujuanSelectElement.append(option).trigger("change");
        });
    });
}

//SDM
function populateDokterOptions() {
    var dokterSelectElement = $("#dokter");
    var dokterModals = $("#modal-pasienTB #modal-dokter");

    // if (!dokterSelectElement.length || !dokterModals.length) {
    //     console.error(
    //         "One or both of the elements (dokterSelectElement, dokterModals) not found."
    //     );
    //     return;
    // }

    $.get("/api/dokter", function (data) {
        if (!Array.isArray(data)) {
            console.error("Invalid data format received from server.");
            return;
        }

        data.sort((a, b) =>
            (a.gelar_d + " " + a.nama + " " + a.gelar_b).localeCompare(
                b.gelar_d + " " + b.nama + " " + b.gelar_b,
                undefined,
                { numeric: true, sensitivity: "base" }
            )
        );

        data.forEach((dokter) => {
            var namaLengkap =
                dokter.gelar_d + " " + dokter.nama + " " + dokter.gelar_b;
            var nip = dokter.nip.toString();

            if (!dokterSelectElement.find(`option[value="${nip}"]`).length) {
                dokterSelectElement
                    .append(new Option(namaLengkap, nip))
                    .trigger("change");
            }

            if (!dokterModals.find(`option[value="${nip}"]`).length) {
                dokterModals
                    .append(new Option(namaLengkap, nip))
                    .trigger("change");
            }
        });
    }).fail((xhr, status, error) => {
        console.error("Error fetching data:", error);
        // Tindakan yang sesuai untuk menangani kesalahan
    });
}

function populatePetugasOptions() {
    var petugasSelectElement = $("#petugas");
    var petugasSelectModals = $("#modal-pasienTB #modal-petugas");

    // if (!petugasSelectElement.length || !petugasSelectModals.length) {
    //     console.error(
    //         "One or both of the elements (petugasSelectElement, petugasSelectModals) not found."
    //     );
    //     return;
    // }

    $.get("/api/perawat", function (data) {
        if (!Array.isArray(data)) {
            console.error("Invalid data format received from server.");
            return;
        }

        data.sort((a, b) =>
            (a.gelar_d + " " + a.nama + " " + a.gelar_b).localeCompare(
                b.gelar_d + " " + b.nama + " " + b.gelar_b,
                undefined,
                { numeric: true, sensitivity: "base" }
            )
        );

        data.forEach((petugas) => {
            var namaLengkap =
                petugas.gelar_d + " " + petugas.nama + " " + petugas.gelar_b;

            if (
                !petugasSelectElement.find(`option[value="${petugas.nip}"]`)
                    .length
            ) {
                petugasSelectElement
                    .append(new Option(namaLengkap, petugas.nip))
                    .trigger("change");
            }

            if (
                !petugasSelectModals.find(`option[value="${petugas.nip}"]`)
                    .length
            ) {
                petugasSelectModals
                    .append(new Option(namaLengkap, petugas.nip))
                    .trigger("change");
            }
        });
    }).fail((xhr, status, error) => {
        console.error("Error fetching data:", error);
        // Tindakan yang sesuai untuk menangani kesalahan
    });
}

function populateRadiograferOptions() {
    var petugasSelectElement = $("#p_rontgen");
    $.get("/api/radiografer", function (data) {
        data.sort(function (a, b) {
            var namaLengkapA = a.gelar_d + " " + a.nama + " " + a.gelar_b;
            var namaLengkapB = b.gelar_d + " " + b.nama + " " + b.gelar_b;
            return namaLengkapA.localeCompare(namaLengkapB, undefined, {
                numeric: true,
                sensitivity: "base",
            });
        });
        data.forEach(function (petugas) {
            var namaLengkap =
                petugas.gelar_d + " " + petugas.nama + " " + petugas.gelar_b;
            var option = new Option(namaLengkap, petugas.nip, false, false);
            petugasSelectElement.append(option).trigger("change");
        });
    });
}
function populateApotekerOptions() {
    var petugasSelectElement = $("#apoteker");
    $.get("/api/apoteker", function (data) {
        data.sort(function (a, b) {
            var namaLengkapA = a.gelar_d + " " + a.nama + " " + a.gelar_b;
            var namaLengkapB = b.gelar_d + " " + b.nama + " " + b.gelar_b;
            return namaLengkapA.localeCompare(namaLengkapB, undefined, {
                numeric: true,
                sensitivity: "base",
            });
        });
        data.forEach(function (petugas) {
            var namaLengkap =
                petugas.gelar_d + " " + petugas.nama + " " + petugas.gelar_b;
            var option = new Option(namaLengkap, petugas.nip, false, false);
            petugasSelectElement.append(option).trigger("change");
        });
    });
}

function populateAnalisOptions() {
    var petugasSelectElement = $("#analis");
    $.get("/api/analis", function (data) {
        data.sort(function (a, b) {
            var namaLengkapA = a.gelar_d + " " + a.nama + " " + a.gelar_b;
            var namaLengkapB = b.gelar_d + " " + b.nama + " " + b.gelar_b;
            return namaLengkapA.localeCompare(namaLengkapB, undefined, {
                numeric: true,
                sensitivity: "base",
            });
        });
        data.forEach(function (petugas) {
            var namaLengkap =
                petugas.gelar_d + " " + petugas.nama + " " + petugas.gelar_b;
            var option = new Option(namaLengkap, petugas.nip, false, false);
            petugasSelectElement.append(option).trigger("change");
        });
    });
}
function populateAnalisHasil() {
    var analisDarah = $("#darah");
    var analisBakteri = $("#bakteri");
    var analisImuno = $("#imuno");
    var analisSampling = $("#sampling");
    var analisAdmin = $("#admin");

    $.get("/api/analis", function (data) {
        data.sort(function (a, b) {
            var namaLengkapA = a.gelar_d + " " + a.nama + " " + a.gelar_b;
            var namaLengkapB = b.gelar_d + " " + b.nama + " " + b.gelar_b;
            return namaLengkapA.localeCompare(namaLengkapB, undefined, {
                numeric: true,
                sensitivity: "base",
            });
        });

        data.forEach(function (petugas) {
            var namaLengkap =
                petugas.gelar_d + " " + petugas.nama + " " + petugas.gelar_b;

            // Create separate Option instances for each select element
            var optionDarah = new Option(
                namaLengkap,
                petugas.nip,
                false,
                false
            );
            var optionBakteri = new Option(
                namaLengkap,
                petugas.nip,
                false,
                false
            );
            var optionImuno = new Option(
                namaLengkap,
                petugas.nip,
                false,
                false
            );
            var optionSampling = new Option(
                namaLengkap,
                petugas.nip,
                false,
                false
            );
            var optionAdmin = new Option(
                namaLengkap,
                petugas.nip,
                false,
                false
            );

            // Append options to the respective select elements
            analisDarah.append(optionDarah).trigger("change");
            analisBakteri.append(optionBakteri).trigger("change");
            analisImuno.append(optionImuno).trigger("change");
            analisAdmin.append(optionAdmin).trigger("change");
            analisSampling.append(optionSampling).trigger("change");
        });
    });
}

//format numbering
function formatNumberWithCommas(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
function formatNumbernoCommas(number) {
    return number.toString().replace(/,/g, ""); // Menghapus pemisah ribuan
}

//FARMASI
function populateObatOptions() {
    var obatSelectElement = $("#obat");
    var hargaBeliElement = $("#beli");
    var hargaJualElement = $("#jual");
    var productID = $("#productID");
    obatSelectElement.empty();
    var placeholderOption = new Option(
        "--- Pilih Nama Obat ---",
        "",
        true,
        true
    );
    obatSelectElement.append(placeholderOption).trigger("change");
    $.get("/api/obat", function (data) {
        data.sort(function (a, b) {
            var namaA = a.nmObat.toUpperCase();
            var namaB = b.nmObat.toUpperCase();
            if (namaA < namaB) {
                return -1;
            }
            if (namaA > namaB) {
                return 1;
            }
            return 0;
        });

        data.forEach(function (obat) {
            var obatStok =
                obat.nmObat +
                " (Supplier: " +
                obat.supplier.nmSupplier +
                ")" +
                " (Tgl ED: " +
                obat.ed +
                ")" +
                " (Sisa Stok: " +
                obat.sisa +
                ")";
            var option = new Option(obatStok, obat.id, false, false);
            // var option = new Option(obat.nmObat, obat.product_id, false, false);
            obatSelectElement.append(option).trigger("change");
        });

        // Event listener untuk mengisi harga beli dan harga jual saat obat dipilih
        obatSelectElement.on("change", function () {
            var selectedObatId = $(this).val();
            var selectedObat = data.find(function (obat) {
                return obat.id == selectedObatId;
            });

            if (selectedObat) {
                // Mengisi nilai harga beli dan harga jual jika obat ditemukan
                hargaBeliElement.val(
                    selectedObat.hargaBeli
                        ? selectedObat.hargaBeli.toLocaleString(undefined)
                        : ""
                );
                hargaJualElement.val(
                    selectedObat.hargaJual
                        ? selectedObat.hargaJual.toLocaleString(undefined)
                        : ""
                );
                productID.val(
                    selectedObat.product_id
                        ? selectedObat.product_id.toLocaleString()
                        : ""
                );
                $("#qty").focus();
            } else {
                // Mengosongkan nilai harga beli dan harga jual jika obat tidak ditemukan
                hargaBeliElement.val("");
                hargaJualElement.val("");
            }
        });
    });
}
function populateGudangObatOptions() {
    var obatSelectElement = $("#gObat");
    var idGudang = $("#idGudang");
    var productID = $("#productID");
    var idObat = $("#idObat");
    var nmObat = $("#nmObat");
    var stok = $("#stokBaru");
    var beli = $("#hargaBeli");
    var jual = $("#hargaJual");
    var jenis = $("#jenis");
    var sediaan = $("#sediaan");
    var sumber = $("#sumberObat");
    var pabrikan = $("#pabrikan");
    var suplayer = $("#supplier");
    var tglEd = $("#tglED");
    var tglBeli = $("#tglBeli");
    var sisaStok = $("#sisaStok");
    obatSelectElement.empty();
    var placeholderOption = new Option(
        "--- Pilih Nama Obat ---",
        "",
        true,
        true
    );
    obatSelectElement.append(placeholderOption).trigger("change");
    $.get("/api/daftarInObatGudang", function (data) {
        data.sort(function (a, b) {
            var namaA = a.nmObat.toUpperCase();
            var namaB = b.nmObat.toUpperCase();
            if (namaA < namaB) {
                return -1;
            }
            if (namaA > namaB) {
                return 1;
            }
            return 0;
        });

        data.forEach(function (obat) {
            var obatStok =
                obat.nmObat +
                " (Supplier: " +
                obat.supplier.nmSupplier +
                ")" +
                " (Tgl ED: " +
                obat.ed +
                ")" +
                " (Sisa Stok: " +
                obat.sisa +
                ")";
            var option = new Option(obatStok, obat.id, false, false);
            // var option = new Option(obat.nmObat, obat.product_id, false, false);
            obatSelectElement.append(option).trigger("change");
        });

        // Event listener untuk mengisi harga beli dan harga jual saat obat dipilih
        obatSelectElement.on("change", function () {
            stok.focus();
            var selectedObatId = $(this).val();
            var selectedObat = data.find(function (obat) {
                return obat.id == selectedObatId;
            });

            if (selectedObat) {
                // Mengisi nilai harga beli dan harga jual jika obat ditemukan
                idGudang.val(
                    selectedObat.id ? selectedObat.id.toLocaleString() : ""
                );
                productID.val(
                    selectedObat.product_id
                        ? selectedObat.product_id.toLocaleString()
                        : ""
                );
                idObat.val(
                    selectedObat.idObat
                        ? selectedObat.idObat.toLocaleString()
                        : ""
                );
                nmObat.val(
                    selectedObat.nmObat
                        ? selectedObat.nmObat.toLocaleString()
                        : ""
                );
                beli.val(
                    selectedObat.hargaBeli
                        ? selectedObat.hargaBeli.toLocaleString()
                        : ""
                );
                jual.val(
                    selectedObat.hargaJual
                        ? selectedObat.hargaJual.toLocaleString()
                        : ""
                );
                sumber.val(
                    selectedObat.sumber
                        ? selectedObat.sumber.toLocaleString()
                        : ""
                );
                suplayer.val(
                    selectedObat.supplier.id
                        ? selectedObat.supplier.id.toLocaleString()
                        : ""
                );
                pabrikan.val(
                    selectedObat.pabrikan.pabrikan
                        ? selectedObat.pabrikan.pabrikan.toLocaleString()
                        : ""
                );
                jenis.val(
                    selectedObat.jenis
                        ? selectedObat.jenis.toLocaleString()
                        : ""
                );
                sediaan.val(selectedObat.sediaan || "");
                tglEd.val(
                    selectedObat.ed ? selectedObat.ed.toLocaleString() : ""
                );
                tglBeli.val(
                    selectedObat.tglPembelian
                        ? selectedObat.tglPembelian.toLocaleString()
                        : ""
                );
                sisaStok.val(selectedObat.sisa || "");

                suplayer.change();
                pabrikan.change();
                idObat.change();
                jenis.trigger("change");
                sediaan.trigger("change");
                sumber.trigger("change");
            } else {
                // console.log("id tidak ada");
            }
        });
    });
}
function populateNamaObatOptions() {
    var namaObatEl = $("#idObat");
    var nmObat = $("#nmObat");
    namaObatEl.empty();
    var placeholderOption = new Option(
        "--- Pilih Nama Obat ---",
        "",
        true,
        true
    );
    namaObatEl.append(placeholderOption).trigger("change");

    $.get("/api/namaObat", function (data) {
        data.sort(function (a, b) {
            var namaA = a.nmObat.toUpperCase();
            var namaB = b.nmObat.toUpperCase();
            if (namaA < namaB) {
                return -1;
            }
            if (namaA > namaB) {
                return 1;
            }
            return 0;
        });
        data.forEach(function (obat) {
            var option = new Option(obat.nmObat, obat.idObat, false, false);
            namaObatEl.append(option).trigger("change");
        });

        namaObatEl.on("change", function () {
            var selectedObatId = $(this).val();
            var selectedObat = data.find(function (obat) {
                return obat.idObat == selectedObatId;
            });

            $("#StokBaru").focus();
            if (selectedObat) {
                // Mengisi nilai harga beli dan harga jual jika obat ditemukan
                nmObat.val(
                    selectedObat.nmObat
                        ? selectedObat.nmObat.toLocaleString(undefined)
                        : ""
                );
            } else {
                namaObatEl.val("");
            }
        });
    });
}
function populatePabrikanOptions() {
    var pabrikanElement = $("#pabrikan");
    $.get("/api/pabrikan", function (data) {
        data.sort(function (a, b) {
            var namaA = a.nmPabrikan.toUpperCase();
            var namaB = b.nmPabrikan.toUpperCase();
            if (namaA < namaB) {
                return -1;
            }
            if (namaA > namaB) {
                return 1;
            }
            return 0;
        });

        data.forEach(function (pabrikan) {
            var option = new Option(
                pabrikan.nmPabrikan,
                pabrikan.pabrikan,
                false,
                false
            );
            pabrikanElement.append(option).trigger("change");
        });
    });
}
function populateSupplierOptions() {
    var supplierElement = $("#supplier");
    $.get("/api/supplier", function (data) {
        data.sort(function (a, b) {
            var namaA = a.nmSupplier.toUpperCase();
            var namaB = b.nmSupplier.toUpperCase();
            if (namaA < namaB) {
                return -1;
            }
            if (namaA > namaB) {
                return 1;
            }
            return 0;
        });
        data.forEach(function (sup) {
            var option = new Option(sup.nmSupplier, sup.id, false, false);
            supplierElement.append(option).trigger("change");
        });
    });
}

//DOTS CENTER
function populateBlnKeOptions() {
    var blnKeSelectElement = $("#blnKe");
    var tglKunjunganInput = $("#waktu");
    var tglKontrolInput = $("#nxKontrol");

    $.ajax({
        url: "/api/blnKeDots",
        method: "GET",
        success: function (data) {
            blnKeSelectElement.empty();

            data.forEach(function (blnKe) {
                var option = new Option(blnKe.nmBlnKe, blnKe.id);
                blnKeSelectElement.append(option);
            });

            blnKeSelectElement.on("change", function () {
                var selectedId = $(this).val();
                var selectedBln = data.find(function (bulanKe) {
                    return bulanKe.id == selectedId;
                });

                var selectedValue = selectedBln
                    ? parseFloat(selectedBln.nilai)
                    : 0;

                if (isNaN(selectedValue)) {
                    selectedValue = 0;
                }

                // Get the current value of tglKunjunganInput and convert it to a Date object
                var tglKunjunganValue = new Date();

                // Add the selectedValue to the date
                tglKunjunganValue.setDate(
                    tglKunjunganValue.getDate() + selectedValue
                );

                // Format the date to "yyyy-MM-dd"
                var formattedDate = tglKunjunganValue
                    .toISOString()
                    .split("T")[0];

                // Set the value of tglKontrolInput
                tglKontrolInput.val(formattedDate);
            });
        },
        error: function (error) {
            console.error("Error fetching data: ", error);
        },
    });
}
function populateDxMedis() {
    var dxDotsModal1 = $("#modal-pasienTB #modal-kdDx");
    var dxDotsModal = $("#modal-kdDx");
    $.get("/api/dxMedis", function (data) {
        data.sort(function (a, b) {
            var namaA = a.diagnosa.toUpperCase();
            var namaB = b.diagnosa.toUpperCase();
            if (namaA < namaB) {
                return -1;
            }
            if (namaA > namaB) {
                return 1;
            }
            return 0;
        });
        data.forEach(function (dxDot) {
            var option = new Option(dxDot.diagnosa, dxDot.kdDiag, false, false);
            dxDotsModal1.append(option).trigger("change");
            dxDotsModal.append(option).trigger("change");
        });
    });
}
// function populateObatDotsOptions() {
//     var obatDots = $("#obatD #obatDots");
//     var obatDotsModal = $("#modal-pasienTB #modal-obtDots");
//     // console.log("🚀 ~ populateObatDotsOptions ~ obatDotsModal:", obatDotsModal);
//     // console.log($("#obatDots"));

//     if (!obatDots.length || !obatDotsModal.length) {
//         console.error(
//             "One or both of the elements (obatDots, obatDotsModal) not found."
//         );
//         return;
//     }

//     $.get("/api/obatDots", function (data) {
//         if (!Array.isArray(data)) {
//             console.error("Invalid data format received from server.");
//             return;
//         }

//         data.sort((a, b) =>
//             a.nmPengobatan.localeCompare(b.nmPengobatan, undefined, {
//                 sensitivity: "base",
//             })
//         );

//         data.forEach((obtDot) => {
//             var option = new Option(obtDot.nmPengobatan, obtDot.id);

//             if (!obatDots.find(`option[value="${obtDot.id}"]`).length) {
//                 obatDots.append(option).trigger("change");
//             }
//         });
//         data.forEach((obtDot) => {
//             if (!obatDotsModal.find(`option[value="${obtDot.id}"]`).length) {
//             }
//         });
//     }).fail((xhr, status, error) => {
//         console.error("Error fetching data:", error);
//         // Tindakan yang sesuai untuk menangani kesalahan
//     });
// }

function populateObat() {
    var obatDots = $("#obatD #obatDots");
    var obatDotsModal = $("#modal-pasienTB #modal-obtDots");
    $.get("/api/obatDots", function (data) {
        data.sort(function (a, b) {
            var namaA = a.nmPengobatan.toUpperCase();
            var namaB = b.nmPengobatan.toUpperCase();
            if (namaA < namaB) {
                return -1;
            }
            if (namaA > namaB) {
                return 1;
            }
            return 0;
        });

        data.forEach(function (obat) {
            var option = new Option(obat.nmPengobatan, obat.id, false, false);
            obatDots.append(option).trigger("change");
        });
        data.forEach(function (obat) {
            var option = new Option(obat.nmPengobatan, obat.id, false, false);
            obatDotsModal.append(option).trigger("change");
        });
    });
}

//Radiologi
function populateFoto() {
    var fotoRo = $("#kdFoto");
    $.get("/api/fotoRo", function (data) {
        data.forEach(function (foto) {
            var option = new Option(foto.nmFoto, foto.kdFoto, false, false);
            fotoRo.append(option).trigger("change");
        });
    });
}
function populateUkuranFilm() {
    var film = $("#kdFilm");
    $.get("/api/filmRo", function (data) {
        data.forEach(function (foto) {
            var option = new Option(foto.ukuranFilm, foto.kdFilm, false, false);
            film.append(option).trigger("change");
        });
    });
}
function populateProyeksi() {
    var film = $("#kdProyeksi");
    $.get("/api/proyeksiRo", function (data) {
        data.forEach(function (foto) {
            var option = new Option(
                foto.proyeksi,
                foto.kdProyeksi,
                false,
                false
            );
            film.append(option).trigger("change");
        });
    });
}
function populateMesin() {
    var film = $("#kdMesin");
    $.get("/api/mesinRo", function (data) {
        data.forEach(function (foto) {
            var option = new Option(foto.nmMesin, foto.kdMesin, false, false);
            film.append(option).trigger("change");
        });
    });
}
function populateKv() {
    var film = $("#kv");
    var grup = "KV";
    var status = "1";
    $.post("/api/kondisiRo", { grup: grup, status: status }, function (data) {
        data.forEach(function (foto) {
            var option = new Option(
                foto.nmKondisi,
                foto.kdKondisiRo,
                false,
                false
            );
            film.append(option).trigger("change");
        });
    });
}
function populateMa() {
    var film = $("#ma");
    var grup = "mA";
    var status = "1";
    $.post("/api/kondisiRo", { grup: grup, status: status }, function (data) {
        data.forEach(function (foto) {
            var option = new Option(
                foto.nmKondisi,
                foto.kdKondisiRo,
                false,
                false
            );
            film.append(option).trigger("change");
        });
    });
}
function populateS() {
    var film = $("#s");
    var grup = "S";
    var status = "1";
    $.post("/api/kondisiRo", { grup: grup, status: status }, function (data) {
        data.forEach(function (foto) {
            var option = new Option(
                foto.nmKondisi,
                foto.kdKondisiRo,
                false,
                false
            );
            film.append(option).trigger("change");
        });
    });
}
