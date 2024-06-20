let tanggalFormat;
let kdtgl;
function setTglRo() {
    var inputTanggal = document.getElementById("tglRo");
    var tanggalHariIni = new Date();

    var tahun = tanggalHariIni.getFullYear();
    var bulan = String(tanggalHariIni.getMonth() + 1).padStart(2, "0");
    var tanggal = String(tanggalHariIni.getDate()).padStart(2, "0");

    tanggalFormat = tahun + "-" + bulan + "-" + tanggal;
    kdtglFormat = tahun + "-" + bulan + "-" + tanggal;

    inputTanggal.value = tanggalFormat;
    kdtgl = kdtglFormat.replace(/-/g, "");
}

async function simpan() {
    try {
        var notrans = document.getElementById("notrans").value;
        var norm = document.getElementById("norm").value;
        var nama = document.getElementById("nama").value;
        var tglRo = document.getElementById("tglRo").value;
        var noreg = document.getElementById("noreg").value;
        var pasienRawat = document.querySelector(
            'input[name="pasienRawat"]:checked'
        ).value;
        var kdFoto = document.getElementById("kdFoto").value;
        var kdFilm = document.getElementById("kdFilm").value;
        var ma = document.getElementById("ma").value;
        var kv = document.getElementById("kv").value;
        var s = document.getElementById("s").value;
        var jmlExpose = document.getElementById("jmlExpose").value;
        var jmlFilmDipakai = document.getElementById("jmlFilmDipakai").value;
        var jmlFilmRusak = document.getElementById("jmlFilmRusak").value;
        var kdMesin = document.getElementById("kdMesin").value;
        var kdProyeksi = document.getElementById("kdProyeksi").value;
        var layanan = document.getElementById("layanan").value;
        var p_rontgen = document.getElementById("p_rontgen").value;
        var dokter = document.getElementById("dokter").value;

        var gambar = gambarInput.files[0];

        if (gambar) {
            console.log("Nama file:", gambar.name);
            var foto = gambar;
        } else {
            console.log("File belum dipilih");
            var foto = "";
        }

        // Membuat objek FormData untuk mengirim data dengan file
        var formData = new FormData();
        formData.append("notrans", notrans);
        formData.append("norm", norm);
        formData.append("nama", nama);
        formData.append("tglRo", tglRo);
        formData.append("noreg", noreg);
        formData.append("kdFoto", kdFoto);
        formData.append("kdFilm", kdFilm);
        formData.append("pasienRawat", pasienRawat);
        formData.append("ma", ma);
        formData.append("kv", kv);
        formData.append("s", s);
        formData.append("jmlExpose", jmlExpose);
        formData.append("jmlFilmDipakai", jmlFilmDipakai);
        formData.append("jmlFilmRusak", jmlFilmRusak);
        formData.append("kdMesin", kdMesin);
        formData.append("kdProyeksi", kdProyeksi);
        formData.append("layanan", layanan);
        formData.append("p_rontgen", p_rontgen);
        formData.append("dokter", dokter);
        formData.append("gambar", foto);

        // console.log("🚀 ~ simpan ~ formData:", kdProyeksi);

        // Kirim data menggunakan fetch API dengan async/await
        const response = await fetch("/api/addTransaksiRo", {
            method: "POST",
            body: formData,
        });

        if (!response.ok) {
            throw new Error("Network response was not ok");
        }

        const responseData = await response.json();
        console.log("Data berhasil disimpan:", responseData);

        rstForm();
        antrian();
    } catch (error) {
        console.error("Terjadi kesalahan saat menyimpan data:", error);
        // Lakukan sesuatu jika terjadi kesalahan
    }
}

async function cariTsRo(norm, tgl) {
    // Format the norm input field
    var appUrlRo = "http://172.16.10.88/ro/file/";
    formatNorm($("#norm"));
    var norm = norm ? norm : $("#norm").val();
    var tgl = tgl ? tgl : $("#tglRO").val();
    var requestData = { norm: norm, tgl: tgl };

    Swal.fire({
        icon: "info",
        title: "Sedang mencarikan data pasien...!!!",
        showConfirmButton: false,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });
    try {
        const response = await fetch("/api/cariTsRO", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(requestData),
        });

        if (!response.ok) {
            if (response.status == 404) {
                // searchRMObat(norm);
                cariKominfo(norm, tgl);
            } else {
                throw new Error("Network response was not ok");
                Swal.fire({
                    icon: "error",
                    title: "Terjadi kesalahan saat mengambil data pasien...!!!",
                });
            }
        } else {
            const data = await response.json();

            // Ensure data and the 'transaksi_ro' object exist before setting values
            if (data && data.data && data.data.transaksi_ro) {
                const transaksi = data.data.transaksi_ro;
                const petugas = data.data.petugas;
                const foto = data.data.foto_thorax;
                var idFoto = foto.id;
                console.log("🚀 ~ cariTsRo ~ idFoto:", idFoto);
                var alamat = `${transaksi.pasien.kelurahan}, ${transaksi.pasien.rtrw}, ${transaksi.pasien.kecamatan}, ${transaksi.pasien.kabupaten}`;
                $("#norm").val(transaksi.pasien.norm || "");
                $("#nama").val(transaksi.pasien.nama || "");
                $("#alamat").val(alamat || "");
                $("#notrans").val(transaksi.notrans || "");
                $(
                    "input[name=pasienRawat][value=" +
                        transaksi.pasienRawat +
                        "]"
                ).prop("checked", true);
                $("#noreg").val(transaksi.noreg || "");
                $("#layanan")
                    .val(transaksi.layanan || "")
                    .trigger("change");
                $("#kdFoto")
                    .val(transaksi.kdFoto || "")
                    .trigger("change");
                $("#kdFilm")
                    .val(transaksi.kdFilm || "")
                    .trigger("change");
                $("#kv")
                    .val(transaksi.kv || "")
                    .trigger("change");
                $("#ma")
                    .val(transaksi.ma || "")
                    .trigger("change");
                $("#s")
                    .val(transaksi.s || "")
                    .trigger("change");
                $("#kdMesin")
                    .val(transaksi.kdMesin || "")
                    .trigger("change");
                $("#jmlExpose").val(transaksi.jmlExpose || "1");
                $("#jmlFilmDipakai").val(transaksi.jmlFilmDipakai || "1");
                $("#jmlFilmRusak").val(transaksi.jmlFilmRusak || "0");
                $("#kdProyeksi")
                    .val(transaksi.kdProyeksi || "")
                    .trigger("change");
                $("#catatan").val(transaksi.catatan || "");
                $("#dokter")
                    .val(petugas.p_dokter_poli || "")
                    .trigger("change");
                $("#p_rontgen")
                    .val(petugas.p_rontgen || "")
                    .trigger("change");
                $("#idFoto").text(
                    "ID Foto " + foto.id + " - " + foto.nama || ""
                );
                if (foto && foto.foto) {
                    $("#preview").show();
                    $("#idFoto").text(
                        "ID Foto " + foto.id + " - " + foto.nama || ""
                    );
                    $("#displayRo")
                        .attr("src", appUrlRo + foto.foto)
                        .css({ width: "110px", height: "110px" })
                        .show();
                } else {
                    $("#displayRo").attr("src", "").hide();
                }

                Swal.close();
                scrollToInputSection();
            } else {
                console.error("No data received from API");
                Swal.fire({
                    icon: "error",
                    title: "Terjadi kesalahan saat mengambil data pasien...!!!",
                });
            }
        }
    } catch (error) {
        console.error("Terjadi kesalahan saat mencari data:", error);
        Swal.fire({
            icon: "error",
            title: "Terjadi kesalahan saat mencari data...!!! /n" + error,
        });
        // Optionally, handle the error by informing the user or retrying
    }
}

async function cariPasien() {
    // var norm = "000001";
    // var tgl = "2022-01-01";
    try {
        const response = cariTsRo(norm, tgl);

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
    // searchRMObat($("#norm").val());
}

function rstForm() {
    document.getElementById("formtrans").reset();
    document.getElementById("form_identitas").reset();
    $("#preview").hide();
    $("#formtrans select").trigger("change");
    $("#form_identitas select").trigger("change");
    scrollToTop();
    setTglRo();
    setTodayDate();
}
var gambarInput = document.getElementById("fileRo");

gambarInput.addEventListener("change", function () {
    var gambar = this.files[0];
    if (gambar) {
        console.log("Nama file:", gambar.name);
    } else {
        console.log("File belum dipilih");
    }
});

function fetchDataAntrian(tanggal, callback) {
    $.ajax({
        url: "/api/noAntrianKominfo",
        type: "post",
        data: {
            tanggal: tanggal,
        },
        success: function (response) {
            callback(response);
        },
        error: function (xhr) {},
    });
}
function initializeDataAntrian(response) {
    // Check if response has data array
    if (response && response.response && response.response.data) {
        // Iterate over each item in the data array
        response.response.data.forEach(function (item) {
            // Check if pasien_no_rm is not empty
            if (item.pasien_no_rm) {
                var tgl = $("#tanggal").val();
                item.tgl = tgl;
                item.aksi = `<a type="button" class="aksi-button btn-sm btn-primary px-2 icon-link icon-link-hover"
                onclick="cariTsRo('${item.pasien_no_rm}','${item.tgl}');rstForm();"><i class="fas fa-pen-to-square"></i></a>`;
            }
        });

        // Initialize DataTable with processed data
        $("#dataAntrian").DataTable({
            data: response.response.data,
            columns: [
                { data: "aksi", className: "text-center p-2 col-1" }, // Action column
                // { data: "status", className: "text-center p-2 col-1" }, // Action column
                {
                    data: "status", // Assuming 'status' is the key in your row data for the status
                    className: "text-center p-2 col-1",
                    render: function (data, type, row) {
                        var backgroundColor = "";
                        switch (data) {
                            case "Belum Ada Transaksi":
                                backgroundColor = "danger";
                                break;
                            case "Belum Upload Foto Thorax":
                                backgroundColor = "warning";
                                break;
                            case "Sudah Selesai":
                                backgroundColor = "success";
                                break;
                            default:
                                backgroundColor = "secondary";
                                break;
                        }
                        return `<div class="badge badge-${backgroundColor}">${data}</div>`;
                    },
                },
                { data: "antrean_nomor", className: "text-center p-2" }, // No Antrean column
                { data: "penjamin_nama", className: "text-center p-2" }, // No Antrean column
                { data: "pasien_no_rm", className: "text-center p-2" }, // Pasien No. RM column
                { data: "pasien_nama", className: "p-2" }, // Pasien Nama column
                { data: "poli_nama", className: "p-2" }, // Poli column
                { data: "tanggal", className: "p-2" }, // Tanggal column
                { data: "dokter_nama", className: "p-2" }, // Dokter column
            ],
            order: [[1, "asc"]], // Order by Antrean Nomor ascending
        });
    } else {
        console.error(
            "Response format is incorrect or data array is missing:",
            response
        );
        // Handle the case where the response format is incorrect or data array is missing
    }
}

function antrian() {
    $("#loadingSpinner").show();
    var tanggal = $("#tanggal").val();
    console.log("🚀 ~ antrian ~ tanggal:", tanggal);

    fetchDataAntrian(tanggal, function (response) {
        $("#loadingSpinner").hide();

        // Check if DataTable already initialized
        if ($.fn.DataTable.isDataTable("#dataAntrian")) {
            var table = $("#dataAntrian").DataTable();

            // Modify response data to add action column
            response.response.data.forEach(function (item) {
                var tgl = $("#tanggal").val();
                item.tgl = tgl;
                item.aksi = `<a type="button" class="aksi-button btn-sm btn-primary px-2 icon-link icon-link-hover"
                onclick="cariTsRo('${item.pasien_no_rm}','${item.tgl}');rstForm();"><i class="fas fa-pen-to-square"></i></a>`;
            });

            // Clear existing data, add new data, and redraw table
            table.clear().rows.add(response.response.data).draw();
        } else {
            // Initialize DataTable with the response data
            initializeDataAntrian(response);
        }
    });
}

window.addEventListener("load", function () {
    setTglRo();
    setTodayDate();
    populateRadiograferOptions();
    populateDokterOptions();
    populateFoto();
    populateUkuranFilm();
    populateMesin();
    populateProyeksi();
    populateKv();
    populateMa();
    populateS();
    antrian();

    $("#norm").on("keyup", function (event) {
        if (event.key === "Enter") {
            event.preventDefault();
            // cariKominfo();
            cariTsRo();
        }
    });
});
