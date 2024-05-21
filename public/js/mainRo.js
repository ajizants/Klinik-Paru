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
var pasienRawat;
var radioPasienRawat0 = document.getElementById("pasienRawat0");
var radioPasienRawat1 = document.getElementById("pasienRawat1");

// Tambahkan event listener untuk mengubah nilai pasienRawat ketika radio button diubah
radioPasienRawat0.addEventListener("change", function () {
    pasienRawat = 0;
});

radioPasienRawat1.addEventListener("change", function () {
    pasienRawat = 1;
});

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

        var gambar = document.getElementById("fileRo").files[0];

        // Membuat objek FormData untuk mengirim data dengan file
        var formData = new FormData();
        formData.append("notrans", notrans);
        formData.append("norm", norm);
        formData.append("tglRo", tglRo);
        formData.append("noreg", noreg);
        formData.append("kdFoto", kdFoto);
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

window.addEventListener("load", function () {
    setTglRo();
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
