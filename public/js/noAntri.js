function getLastNoAntrian() {
    $.ajax({
        url: "api/lastNoAntri",
        type: "POST",
        dataType: "json",
        success: function (response) {
            console.log(response);
            if ($.isEmptyObject(response)) {
                $("#noAntrian").val(0);
            } else {
                let noAntri = parseInt(response.NoAntri);
                $("#noAntrian").val(noAntri);
                $("#jenis").val(response.jenis);
            }
        },
        error: function (response) {
            console.log(response);
        },
    });
}

function noUmum() {
    let noAntri = $("#noAntrian").val();
    if (noAntri == "") {
        getLastNoAntrian();
        $("#jenis").val("UMUM");
        incrementNoAntrian();
    } else {
        let noBaru = parseInt(noAntri) + 1;
        $("#noAntrian").val(noBaru);
        $("#jenis").val("UMUM");
        incrementNoAntrian();
    }
}
function noBpjs() {
    let noAntri = $("#noAntrian").val();
    if (noAntri == "") {
        getLastNoAntrian();
        $("#jenis").val("BPJS");
        incrementNoAntrian();
    } else {
        let noBaru = parseInt(noAntri) + 1;
        $("#noAntrian").val(noBaru);
        $("#jenis").val("BPJS");
        incrementNoAntrian();
    }
}

function incrementNoAntrian() {
    let noAntri = $("#noAntrian").val() || 0;
    noAntri = ("000" + noAntri).slice(-3); // Padding dengan nol di depan
    let jenis = $("#jenis").val();

    $.ajax({
        url: "api/ambilNo",
        type: "POST",
        data: { noAntri, jenis },
        success: function (response) {
            console.log(response);
            cetakNoAntrian();
        },
        error: function (response) {
            console.log(response);
        },
    });
}

function cetakNoAntrian() {
    let noAntri = ("000" + $("#noAntrian").val()).slice(-3); // Padding dengan nol di depan
    let jenis = $("#jenis").val();
    let date = new Date();

    let options = {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
    };
    let options2 = {
        hour: "2-digit",
        minute: "numeric",
        second: "numeric",
        timeZoneName: "short",
        timeZone: "Asia/Jakarta",
    };

    let tgl = date.toLocaleString("id-ID", options);
    let jam = date.toLocaleString("id-ID", options2);

    let printWindow = window.open("", "_blank");
    printWindow.document.write(
        `<html><head><title>Cetak No Antrian</title>` +
            `<style>body{text-align:center;}h1{font-size:60px;font-family:sans-serif;font-weight:bold;margin-top:6px;margin-bottom:6px;}` +
            `.judul{font-size:20px;font-family:sans-serif;font-weight:bold;margin-top:0px;margin-bottom:0px;}` +
            `.jenis{font-size:20px;font-family:sans-serif;font-weight:bold;}.time{font-size:18px;font-family:sans-serif;margin-top:0px;margin-bottom:0px;}</style></head><body>`
    );

    printWindow.document.write(`<p class='judul'>Klinik Utama Kesehatan</p>`);
    printWindow.document.write(`<p class='judul'>Paru Masyarakat</p>`);
    printWindow.document.write(`<h1>${noAntri}</h1>`);
    printWindow.document.write(`<p class='jenis'>${jenis}</p>`);
    printWindow.document.write(`<p class='time'>${tgl}</p>`);
    printWindow.document.write(`<p class='time'>${jam}</p>`);
    printWindow.document.write(`</body></html>`);

    printWindow.print();
    printWindow.document.close();
}

document.addEventListener("DOMContentLoaded", function () {
    getLastNoAntrian();
    console.log("🚀 ~ file: noAntri.js:80 ~ no:", $("#noAntrian").val());
});

// Fungsi untuk memulai antrian
function startAntrian(jenis) {
    getLastNoAntrian();
    $("#jenis").val(jenis);
    incrementNoAntrian();
}

// Fungsi untuk mengonversi zona waktu menjadi format yang sesuai
function konversiZonaWaktu(tanggal) {
    const opsi = {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
        timeZone: "Asia/Jakarta",
    };
    return tanggal.toLocaleString("id-ID", opsi);
}

// Dapatkan waktu lokal saat ini
const waktuSaatIni = new Date();

// Tampilkan waktu lokal di dalam elemen HTML
const waktuLokalElemen = document.getElementById("waktuLokal");
waktuLokalElemen.textContent = "Purwokerto, " + konversiZonaWaktu(waktuSaatIni);

setInterval(function () {
    const ketElemen = document.getElementById("ket");
    ketElemen.style.visibility =
        ketElemen.style.visibility === "visible" ? "hidden" : "visible";
}, 1000);
