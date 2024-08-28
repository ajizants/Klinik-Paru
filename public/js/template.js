$(document).on("select2:open", () => {
    document.querySelector(".select2-search__field").focus();
});

var tglTransInput = document.getElementById("waktu");
let tglnow = "";
document.addEventListener("DOMContentLoaded", function () {
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
        let tglnow = now
            .toLocaleString("id-ID", options)
            .replace(
                /(\d{4})\D(\d{2})\D(\d{2})\D(\d{2})\D(\d{2})\D(\d{2})/,
                "$1-$2-$3 $4.$5.$6"
            );

        tglTransInput.value = tglnow;
    }
    setInterval(updateDateTime, 1000);

    $(".select2bs4").select2();

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"), // Mengirim token CSRF untuk perlindungan keamanan
        },
    });

    $(".nav-link").on("click", function () {
        // Menghapus class 'active' dari semua elemen dengan class 'nav-link'
        $(".nav-link").removeClass("active");
        $(".nav-link").removeClass("bg-blue");
        // Menambah class 'active' ke elemen yang diklik
        $(this).addClass("active");
        if ($(this).hasClass("active")) {
            // Jika iya, ubah warna latar belakang menjadi putih
            $(this).addClass("bg-blue");
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
});
function formatNorm(inputElement) {
    // console.log("🚀 ~ formatNorm ~ inputElement:", inputElement);
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
    $("#tglKunj").val(today);
    $("#tglRO").val(today);
    $("#tgltrans").val(today);
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

var Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
});

function toggleSections(sectionToShow) {
    var sections = [
        "#dAntrian",
        "#dSelesai",
        "#dBelum",
        "#dTunggu",
        "#dKontrol",
        "#dTelat",
        "#dDo",
        "#dTb",
        "#key_pad",

        "#hasilPemeriksaan",
        "#poin",
        "#jmlhPeriksa",

        "#SubKelas",
        "#Kelas",
        "#Domain",

        "#kunjungan",
        "#asesmen",
    ];
    sections.forEach(function (section) {
        if (section === sectionToShow) {
            // console.log("🚀 ~ toggleSections ~ sama:", section);
            $(section).show();
        } else {
            // console.log("🚀 ~ toggleSections ~ beda:", section);
            $(section).hide();
        }
    });
}

function enterCariRM(event, ruang) {
    // console.log("🚀 ~ enterCariRM ~ ruang:", ruang);
    if (event.key === "Enter") {
        var tgl; // Declare the variable

        // Check if the element with ID tgltrans exists
        if ($("#tgltrans").length) {
            tgl = $("#tgltrans").val();
        } else {
            tgl = $("#tanggal").val();
        }
        var formatNorm = $("#norm").val().replace(/\D/g, "");

        while (formatNorm.length < 6) {
            formatNorm = "0" + formatNorm;
        }

        $("#norm").val(formatNorm.slice(0, 6));
        var norm = formatNorm.slice(0, 6);

        if (ruang == "lab") {
            cariTsLab(norm, tgl, ruang);
        } else if (ruang == "dots") {
            cariPasienTb(norm, tgl, ruang);
        } else if (ruang == "gizi") {
            cariPasienGizi(norm, tgl, ruang);
        }
    }
}

function selamatBertugas() {
    let synth = speechSynthesis;

    // Memeriksa dukungan Text-to-Speech API
    if (synth === undefined) {
        console.error("Text-to-Speech API is not supported");
        return;
    }

    let utterance = new SpeechSynthesisUtterance(
        "selamat bertugas teman teman, aja kelalen madang, lan aja kelalen gosip, haha haha wkwk wkwk"
    );

    // Set bahasa ke bahasa Indonesia (id-ID)
    utterance.lang = "id-ID";

    // Jalankan Text-to-Speech
    try {
        synth.speak(utterance);
    } catch (error) {
        console.error("Error while attempting to use Text-to-Speech:", error);
    }

    const jsConfetti = new JSConfetti();

    jsConfetti
        .addConfetti({})
        .then(() => jsConfetti.addConfetti())
        .then(() => {
            // Create a new element for the greeting message
            const greetingMessage = document.createElement("div");
            greetingMessage.textContent = "Selamat Ngodey...!"; // Your greeting message here
            greetingMessage.style.fontSize = "24px";
            greetingMessage.style.fontWeight = "bold";
            greetingMessage.style.color = "indigo";
            greetingMessage.style.position = "absolute";
            greetingMessage.style.top = "50%";
            greetingMessage.style.left = "40%";
            greetingMessage.style.transform = "translate(-50%, -50%)";
            greetingMessage.style.animation = "zoomIn 3s"; // Add zoom-in animation

            // Append the greeting message to the document body
            document.body.appendChild(greetingMessage);

            // Remove the greeting message after 3 seconds
            setTimeout(() => {
                document.body.removeChild(greetingMessage);
            }, 3000);
        });
}
