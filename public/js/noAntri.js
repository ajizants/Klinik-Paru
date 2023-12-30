function getLastNoAntrian() {
    $.ajax({
        url: "api/lastNoAntri",
        type: "POST",
        dataType: "json",
        success: function (response) {
            console.log(response);
            if ($.isEmptyObject(response)) {
                let noAntri = 0;
                $("#noAntrian").val(noAntri);
            } else {
                let noAntri = parseInt(response.NoAntri);
                console.log(
                    "🚀 ~ file: noAntri.js:9 ~ getLastNoAntrian ~ noAntri:",
                    noAntri
                );
                $("#noAntrian").val(noAntri);
                $("#jenis").val(response.jenis);
                var tgl = response.created_at;
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
        addNo();
    } else {
        let noBaru = parseInt(noAntri) + 1;
        $("#noAntrian").val(noBaru);
        $("#jenis").val("UMUM");
        addNo();
    }
}
function noBpjs() {
    let noAntri = $("#noAntrian").val();
    if (noAntri == "") {
        getLastNoAntrian();
        $("#jenis").val("BPJS");
        addNo();
    } else {
        let noBaru = parseInt(noAntri) + 1;
        $("#noAntrian").val(noBaru);
        $("#jenis").val("BPJS");
        addNo();
    }
}

function addNo() {
    var noAntri = $("#noAntrian").val();
    if (noAntri.length < 3) {
        noAntri = "0".repeat(3 - noAntri.length) + noAntri;
    }
    var jenis = $("#jenis").val();
    console.log(
        "🚀 ~ file: noAntri.js:55 ~ addNo ~ noAntri:",
        noAntri,
        " Jenis :",
        jenis
    );
    $.ajax({
        url: "api/ambilNo",
        type: "POST",
        data: {
            noAntri: noAntri,
            jenis: jenis,
        },
        success: function (response) {
            console.log(response);
            // let noBaru = parseInt(noAntri) + 1;
            // $("#noAntrian").val(noBaru);
            cetakNoAntrian();
        },
        error: function (response) {
            console.log(response);
        },
    });
}

function cetakNoAntrian() {
    var noAntri = $("#noAntrian").val();
    if (noAntri.length < 3) {
        noAntri = "0".repeat(3 - noAntri.length) + noAntri;
    }
    var jenis = $("#jenis").val();
    var date = new Date();
    var options = {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
    };
    var options2 = {
        hour: "2-digit",
        minute: "numeric",
        second: "numeric",
        timeZoneName: "short",
        timeZone: "Asia/Jakarta",
    };

    var tgl = date.toLocaleString("id-ID", options);
    var jam = date.toLocaleString("id-ID", options2);

    // Buat elemen HTML baru untuk mencetak teks
    var printWindow = window.open("", "_blank");
    printWindow.document.write(
        "<html><head><title>Cetak No Antrian</title>" +
            "<style>" +
            "body { text-align: center; }" +
            "h1 { font-size: 60px; font-family: sans-serif; font-weight: bold; margin-top: 6px;margin-bottom: 6px;}" +
            ".judul { font-size: 20px; font-family: sans-serif; font-weight: bold; margin-top: 0px;margin-bottom: 0px;}" +
            ".jenis { font-size: 20px; font-family: sans-serif; font-weight: bold; }" +
            ".time { font-size: 18px; font-family: sans-serif; margin-top: 0px;margin-bottom: 0px;}" +
            "</style></head><body>"
    );
    printWindow.document.write("<p class='judul'>Klinik Utama Kesehatan</p>");
    printWindow.document.write("<p class='judul'>Paru Masyarakat</p>");
    printWindow.document.write("<h1>" + noAntri + "</h1>");
    printWindow.document.write("<p class='jenis'>" + jenis + "</p>");
    printWindow.document.write("<p class='time'>" + tgl + "</p>");
    printWindow.document.write("<p class='time'>" + jam + "</p>");
    printWindow.document.write("</body></html>");

    // Panggil fungsi cetak pada jendela baru
    printWindow.print();
    printWindow.document.close();
}
document.addEventListener("DOMContentLoaded", function () {
    getLastNoAntrian();
    let no = document.getElementById(noAntrian);
    console.log("🚀 ~ file: noAntri.js:80 ~ no:", no);
});
