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

function simpan1() {
    var notras = document.getElementById("notrans").value;
    var norm = document.getElementById("norm").value;
    var tglRo = document.getElementById("tglRo").value;
    var noreg = document.getElementById("noreg").value;
    var kdFoto = document.getElementById("kdFoto").value;
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

    var file = document.getElementById("fileRo").files[0];
    // Data yang akan dikirim
    var data = {
        notras: notras,
        norm: norm,
        tglRo: tglRo,
        pasienRawat: pasienRawat,
        noreg: noreg,
        kdFoto: kdFoto,
        ma: ma,
        kv: kv,
        s: s,
        jmlExpose: jmlExpose,
        jmlFilmDipakai: jmlFilmDipakai,
        jmlFilmRusak: jmlFilmRusak,
        kdMesin: kdMesin,
        kdProyeksi: kdProyeksi,
        layanan: layanan,
        // file: file,
        p_rontgen: p_rontgen,
        dokter: dokter,
    };

    // Konversi objek data menjadi format JSON
    var jsonData = JSON.stringify(data);
    console.log("🚀 ~ simpan ~ jsonData:", jsonData);

    // Kirim data menggunakan fetch API
    fetch("/api/addTrnasaksiRo", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: jsonData,
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then((data) => {
            console.log("Data berhasil disimpan:", data);
            // Lakukan sesuatu setelah data berhasil disimpan
        })
        .catch((error) => {
            console.error("Terjadi kesalahan saat menyimpan data:", error);
            // Lakukan sesuatu jika terjadi kesalahan
        });
}

async function simpan() {
    try {
        var notrans = document.getElementById("notrans").value;
        var norm = document.getElementById("norm").value;
        var tglRo = document.getElementById("tglRo").value;
        var noreg = document.getElementById("noreg").value;
        var pasienRawat = document.querySelector(
            'input[name="pasienRawat"]:checked'
        ).value;
        var kdFoto = document.getElementById("kdFoto").value;
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
        } else {
            console.log("File belum dipilih");
        }

        // Membuat objek FormData untuk mengirim data dengan file
        var formData = new FormData();
        formData.append("notrans", notrans);
        formData.append("norm", norm);
        formData.append("tglRo", tglRo);
        formData.append("noreg", noreg);
        formData.append("kdFoto", kdFoto);
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
        formData.append("gambar", gambar);

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
        // Lakukan sesuatu setelah data berhasil disimpan
    } catch (error) {
        console.error("Terjadi kesalahan saat menyimpan data:", error);
        // Lakukan sesuatu jika terjadi kesalahan
    }
}

function cariPasien() {
    formatNorm($("#norm"));
    searchRMObat($("#norm").val());
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
                // Construct the action button HTML
                item.aksi = `<a href="#" class="aksi-button btn-sm btn-primary px-2 icon-link icon-link-hover"
                        onclick="cariKominfo('${item.pasien_no_rm}')"><i class="fas fa-pen-to-square"></i></a>`;
            }
        });

        // Initialize DataTable with processed data
        $("#dataAntrian").DataTable({
            data: response.response.data,
            columns: [
                { data: "aksi", className: "text-center p-2 col-1" }, // Action column
                { data: "antrean_nomor", className: "text-center p-2" }, // No Antrean column
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
                item.aksi = `<a href="#" class="aksi-button btn-sm btn-primary px-2 icon-link icon-link-hover"
                        onclick="cariKominfo('${item.pasien_no_rm}')"><i class="fas fa-pen-to-square"></i></a>`;
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
    antrian();
    populateRadiograferOptions();
    populateDokterOptions();
    populateFoto();
    populateUkuranFilm();
    populateMesin();
    populateProyeksi();
    populateKv();
    populateMa();
    populateS();

    $("#norm").on("keyup", function (event) {
        if (event.key === "Enter") {
            event.preventDefault();
            cariKominfo();
        }
    });
});
