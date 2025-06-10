$(document).on("select2:open", () => {
    document.querySelector(".select2-search__field").focus();
});

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
            // second: "2-digit",
        };
        let tglnow = now
            .toLocaleString("id-ID", options)
            .replace(
                /(\d{4})\D(\d{2})\D(\d{2})\D(\d{2})\D(\d{2})\D(\d{2})/,
                "$1-$2-$3 $4.$5.$6"
            );
        // document.getElementById("waktu").innerHTML = tglnow;
        document.getElementById("waktu").textContent = tglnow;
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

function panggil(pesan) {
    //     console.log("🚀 ~ panggil ~ pesan:", pesan);

    // Cek daftar suara yang tersedia
    const voices = speechSynthesis.getVoices();

    // Cari suara VE Damayanti (atau yang mendukung id-ID)
    const damayantiVoice = voices.find(
        (voice) => voice.name.includes("Damayanti") || voice.lang === "id-ID"
    );

    const utterance = new SpeechSynthesisUtterance(pesan);
    utterance.lang = "id-ID"; // Bahasa Indonesia

    // Gunakan VE Damayanti jika ditemukan
    if (damayantiVoice) {
        utterance.voice = damayantiVoice;
    } else {
        console.warn(
            "VE Damayanti tidak ditemukan, menggunakan suara default."
        );
    }

    // Setel kecepatan dan nada suara jika diperlukan
    utterance.rate = 0.6; // Turunkan sedikit kecepatannya
    utterance.pitch = 1.0; // Nada normal

    // Tambahkan dingdong sebelum panggilan
    const dingdong = new Audio("/audio/dingdong.mp3");
    dingdong
        .play()
        .then(() => {
            setTimeout(() => {
                speechSynthesis.speak(utterance);
            }, 1000);
        })
        .catch((error) => {
            console.error("Gagal memutar audio:", error);
            speechSynthesis.speak(utterance); // Tetap lanjutkan ucapan
        });
}

function setTodayDate() {
    var today = new Date().toISOString().split("T")[0];
    $("#tanggal").val(today);
    $("#tglKunj").val(today);
    $("#tglRO").val(today);
    $("#tgltrans").val(today);
    $("#tanggal_bpjs").val(today);
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

function toggleSections(sectionToShow, idTable = null) {
    console.log("🚀 ~ toggleSections ~ sectionToShow:", sectionToShow);
    var sections = [
        "#dAntrian",
        "#dSelesai",
        "#dBelum",
        "#dTunggu",
        "#dKontrol",
        "#dTelat",
        "#dKonsul",
        "#dDo",
        "#dTb",
        "#dSkip",
        "#key_pad",

        "#hasilPemeriksaan",
        "#poin",
        "#jmlhPeriksa",
        "#waktuLayanan",

        "#SubKelas",
        "#Kelas",
        "#Domain",

        "#kunjungan",
        "#asesmen",

        "#tab_1",
        "#tab_2",
        "#tab_3",
        "#tab_4",
        "#tab_5",
        "#tab_6",
        "#tab_7",
        "#tab_8",
        "#tab_9",
        "#tab_10",
        "#tab_11",
        "#tab_12",
        "#tab_13",
        "#tab_14",
        "#tab_15",
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
    if (idTable !== null) {
        $(idTable).DataTable().columns.adjust().draw();
    }
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
            cariTsLab(norm, tgl, "tampil");
        } else if (ruang == "dots") {
            cariPasienTb(norm, tgl, ruang);
        } else if (ruang == "gizi") {
            cariPasienGizi(norm, tgl, ruang);
        }
    }
}
$(function () {
    $('[data-toggle="tooltip"]').tooltip();
});

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

$(".select2bs4").select2({ theme: "bootstrap4" });

function showModalUniversal(data) {
    // Buat modal dengan data yang diberikan
    const modal = `
      <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              ${data}
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary">Save changes</button>
            </div>
          </div>
        </div>
      </div>`;

    // Tambahkan modal ke body
    document.body.insertAdjacentHTML("beforeend", modal);

    // Tampilkan modal
    $("#modalUniversal").modal("show");

    // Hapus modal dari DOM setelah ditutup
    $("#modalUniversal").on("hidden.bs.modal", function () {
        $(this).remove();
    });
}

var socketIO = io.connect("wss://kkpm.banyumaskab.go.id:3131/", {
    // path: '/socket.io',
    transports: ["websocket", "polling", "flashsocket"],
});

socketIO.on("connectParu", () => {
    const sessionID = socketIO.id;
    $("#socket-id").html(sessionID);
    console.log("Socket ID : " + sessionID);
});

function tampilkanLoading(msg = "Sedang memproses data...!!!") {
    Swal.fire({
        icon: "info",
        title: msg,
        showConfirmButton: false,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });
}

function tampilkanEror(msg = "") {
    Swal.fire({
        icon: "error",
        title: "Oops...",
        text: msg,
        showConfirmButton: true,
        allowOutsideClick: false,
    });
}

function tampilkanSukses(msg = "") {
    Swal.fire({
        icon: "success",
        title: msg,
        showConfirmButton: true,
        allowOutsideClick: false,
    });
}

function generateAsktindString(data, addNewLine = false, isLab = false) {
    if (!Array.isArray(data)) return "";
    return data
        .map((item, index) => {
            const separator = isLab ? (index % 2 === 1 ? ",<br>" : ", ") : ", ";
            const hasil = " - Hasil: " + item.hasil || "";
            const ket =
                item.keterangan || item.nama_obat
                    ? ` - ${item.keterangan || item.nama_obat}`
                    : "";
            const hasilLab = isLab ? hasil : "";
            return `${
                item.layanan || item.nama_tindakan || item.pemeriksaan
            } ${ket} ${hasilLab} ${addNewLine ? "<br>" : separator}`;
        })
        .join("")
        .replace(/(,\s*<br>|,\s)$/, "");
}

$.extend(true, $.fn.dataTable.defaults, {
    language: {
        search: "Cari:",
        lengthMenu: "Lihat _MENU_ data",
        zeroRecords: "Tidak ada data yang cocok",
        info: "Menampilkan _START_ s.d. _END_ dari _TOTAL_ data",
        infoEmpty: "Menampilkan 0",
        emptyTable: "Tidak ada data yang tersedia",
        infoFiltered: "(difilter dari _MAX_ total data)",
        paginate: {
            first: "<",
            last: ">",
            next: ">>",
            previous: "<<",
        },
    },
});
