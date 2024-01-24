$(document).on("select2:open", () => {
    document.querySelector(".select2-search__field").focus();
});

document.addEventListener("DOMContentLoaded", function () {
    // var tgltindInput = document.getElementById("tgltind");
    var tglTransInput = document.getElementById("waktu");

    function updateDateTime() {
        var now = new Date();
        var options = {
            timeZone: "Asia/Jakarta",
            year: "numeric",
            month: "2-digit",
            day: "2-digit",
            hour: "2-digit",
            minute: "2-digit",
            second: "2-digit",
        };
        // var formattedDate = now.toLocaleString("id-ID", options);
        var formattedDate = now
            .toLocaleString("id-ID", options)
            .replace(
                /(\d{4})\D(\d{2})\D(\d{2})\D(\d{2})\D(\d{2})\D(\d{2})/,
                "$1-$2-$3 $4.$5.$6"
            );

        tglTransInput.value = formattedDate;
    }
    setInterval(updateDateTime, 1000);
});

$(".select2bs4").select2();

$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"), // Mengirim token CSRF untuk perlindungan keamanan
    },
});

$(".nav-link").on("click", function () {
    // Menghapus class 'active' dari semua elemen dengan class 'nav-link'
    $(".nav-link").removeClass("active");
    $(".nav-link").removeClass("bg-white");
    // Menambah class 'active' ke elemen yang diklik
    $(this).addClass("active");
    if ($(this).hasClass("active")) {
        // Jika iya, ubah warna latar belakang menjadi putih
        $(this).addClass("bg-white");
    }
});

$("#itunggu").on("click", function () {
    $("#dselesai").hide();
    $("#dtunggu").show();
});
$("#iselesai").on("click", function () {
    $("#dselesai").show();
    $("#dtunggu").hide();
});

function formatNorm(inputElement) {
    console.log(inputElement);
    // Pastikan inputElement adalah objek jQuery yang valid
    if (inputElement && inputElement.val) {
        // Hapus karakter selain digit
        let inputValue = inputElement.val().replace(/\D/g, "");

        // Tambahkan 0 di depan jika kurang dari 6 digit
        while (inputValue.length < 6) {
            inputValue = "0" + inputValue;
        }

        // Ambil 6 digit pertama
        inputElement.val(inputValue.slice(0, 6));
    }
}
function scrollToAntrianSection() {
    $("html, body").animate({ scrollTop: $("#topSection").offset().top }, 500);
}
function panggilPasien(text) {
    // let synth = window.speechSynthesis;
    let synth = speechSynthesis;
    let utterance = new SpeechSynthesisUtterance(text);

    // Set bahasa ke bahasa Indonesia (id-ID)
    utterance.lang = "id-ID";

    // Jalankan Text-to-Speech
    synth.speak(utterance);
}

function setTodayDate() {
    var today = new Date().toISOString().split("T")[0];
    $("#tanggal").val(today);
}

function scrollToInputSection() {
    $("html, body").animate(
        { scrollTop: $("#inputSection").offset().top },
        500
    );
}
function scrollToTop() {
    $("html, body").animate({ scrollTop: $("#top").offset().top }, 500);
}
