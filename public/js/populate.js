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
        item.aksi = `<a href="#" class="aksi-button px-2 icon-link icon-link-hover"
                data-norm="${item.norm}"
                data-nama="${item.namapasien}"
                ><i class="fas fa-pen-to-square"></i></a>`;
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
                item.aksi = `<a href="#" class="aksi-button px-2 icon-link icon-link-hover"
                data-norm="${item.norm}"
                data-nama="${item.namapasien}"
                ><i class="fas fa-pen-to-square"></i></a>`;
            });
            table.clear().rows.add(response).draw();
        } else {
            initializeAntrianAll(response);
        }
    });
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
    $.get("/api/dokter", function (data) {
        data.sort(function (a, b) {
            var namaLengkapA = a.gelar_d + " " + a.nama + " " + a.gelar_b;
            var namaLengkapB = b.gelar_d + " " + b.nama + " " + b.gelar_b;
            return namaLengkapA.localeCompare(namaLengkapB, undefined, {
                numeric: true,
                sensitivity: "base",
            });
        });
        data.forEach(function (dokter) {
            var namaLengkap =
                dokter.gelar_d + " " + dokter.nama + " " + dokter.gelar_b;
            var nip = dokter.nip.toString();
            var option = new Option(namaLengkap, nip, false, false);
            dokterSelectElement.append(option).trigger("change");
            dokterModals.append(option).trigger("change");
        });
    });
}
function populatePetugasOptions() {
    var petugasSelectElement = $("#petugas");
    var petugasSelectModals = $("#modal-petugas");
    var petugasSelectModals1 = $("#modal-pasienTB #modal-petugas");
    $.get("/api/perawat", function (data) {
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
            petugasSelectModals.append(option).trigger("change");
            petugasSelectModals1.append(option).trigger("change");
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

            // Append options to the respective select elements
            analisDarah.append(optionDarah).trigger("change");
            analisBakteri.append(optionBakteri).trigger("change");
            analisImuno.append(optionImuno).trigger("change");
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
    var tglKunjunganInput = $("#tglKunj");
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
                var tglKunjunganValue = new Date(tglKunjunganInput.val());

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
function populateObatDotsOptions() {
    var obatDots = $("#obatDots");
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
        data.forEach(function (obtDot) {
            var option = new Option(
                obtDot.nmPengobatan,
                obtDot.id,
                false,
                false
            );
            obatDots.append(option).trigger("change");
            obatDotsModal.append(option).trigger("change");
        });
    });
}
