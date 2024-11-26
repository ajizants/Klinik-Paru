var Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
});

let keterangan; // Define status globally

document.getElementById("statusSwitch").addEventListener("change", function () {
    var statusLabel = document.getElementById("statusLabel");

    if (this.checked) {
        statusLabel.textContent = "Selesai";
        keterangan = "Selesai";
    } else {
        statusLabel.textContent = "Belum";
        keterangan = "Belum";
    }
    console.log("🚀 ~ status:", keterangan); // This will log the correct status
});

function formatWaktu(dateTimeString) {
    const [datePart, timePart] = dateTimeString.split(" ");
    const [year, month, day] = datePart.split("-");
    const formattedDate = `${day}-${month}-${year}`;
    return `${formattedDate} ${timePart}`;
}

async function cariTsLab(norm, tgl, task) {
    formatNorm($("#norm"));
    norm = norm || $("#norm").val();
    tgl = tgl || $("#tanggal").val();
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
        const response = await fetch("/api/cariTsLab", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(requestData),
        });

        if (!response.ok) {
            if (response.status == 404) {
                // searchRMObat(norm);
                // cariKominfo(norm, tgl);
                Swal.fire({
                    icon: "error",
                    title:
                        "Pasien dengan NO RM : " +
                        norm +
                        " tidak ditemukan di pendaftaran laboratorium...!!!",
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Terjadi kesalahan saat mengambil data pasien...!!!",
                });
                throw new Error("Network response was not ok");
            }
        } else {
            const data = await response.json();
            if (task == "tampil") {
                $("#norm").val(data.norm);
                $("#nama").val(data.nama);
                $("#nik").val(data.nik);
                $("#alamat").val(data.alamat);
                $("#notrans").val(data.notrans);
                $("#layanan").val(data.layanan);
                $("#dokter").val(data.dokter).trigger("change");
                $("#analis").val(data.petugas).trigger("change");
                var rawDateTime = data.waktu_selesai;
                if (rawDateTime !== null) {
                    const waktuSelesai = formatWaktu(rawDateTime);
                    console.log("🚀 ~ cariTsLab ~ waktuSelesai:", waktuSelesai);
                    $("#waktuSelesai").text(waktuSelesai);
                    $("#divSwitch").hide();
                }
                // var ket = data.ket;
                // if (ket == "Selesai") {
                //     document.getElementById("statusSwitch").checked = true;
                //     document.getElementById("statusLabel").textContent = "Selesai";
                //     keterangan = "Selesai";
                // } else {
                //     document.getElementById("statusSwitch").checked = false;
                //     document.getElementById("statusLabel").textContent = "Belum";
                //     keterangan = "Belum";
                // }

                const notrans = data.notrans;
                var pemeriksaan = data.pemeriksaan;
                dataLab(pemeriksaan);
            } else {
                cetak(data);
            }
            Swal.close();
        }
    } catch (error) {
        console.error("Terjadi kesalahan saat mencari data:", error);
        Swal.fire({
            icon: "error",
            title: "Terjadi kesalahan saat mencari data...!!! /n" + error,
        });
    }
}

async function dataLab(pemeriksaan, notrans) {
    const analisResponse = await fetch("/api/analis", {
        method: "GET",
        headers: {
            "Content-Type": "application/json",
        },
    });

    if (!analisResponse.ok) {
        throw new Error(`HTTP error! Status: ${analisResponse.status}`);
    }

    const analisData = await analisResponse.json();

    const data = pemeriksaan.map((item, index) => ({
        no: index + 1,
        norm: item.norm,
        nmLayanan: item.pemeriksaan.nmLayanan,
        petugas: item.petugas,
        hasiLab: item.hasil || "",
        ket: item.ket || "",
        idLab: item.idLab,
        idLayanan: item.pemeriksaan.idLayanan,
        kelas: item.pemeriksaan.kelas,
        kdTind: item.pemeriksaan.kdTind,
    }));

    const table = $("#inputHasil").DataTable({
        data: data,
        destroy: true,
        columns: [
            {
                data: "no",
                render: (data) =>
                    `<p type="text" class="form-control-sm col hasil">${data}</p>`,
            },
            {
                data: "norm",
                render: (data) =>
                    `<p type="text" class="form-control-sm col hasil">${data}</p>`,
            },
            {
                data: "nmLayanan",
                render: (data, type, row) =>
                    `<p type="text" class="form-control-sm col hasil" id="layanan${row.idLayanan}" value="${row.idLayanan}" readonly>${data}</p>`,
            },
            {
                data: "petugas",
                render: (data, type, row) => {
                    let inputId = "analis" + row.idLab;
                    let inputField = `<select id="${inputId}" class="form-control-sm col analis">`;
                    inputField += `<option value="">--- Pilih Petugas ---</option>`;
                    analisData.forEach((petugas) => {
                        let selected = data === petugas.nip ? "selected" : "";
                        inputField += `<option value="${petugas.nip}" ${selected}>${petugas.gelar_d} ${petugas.nama} ${petugas.gelar_b}</option>`;
                    });
                    inputField += "</select>";
                    return inputField;
                },
            },
            {
                data: "hasiLab",
                render: (data, type, row) => {
                    let hasilLabHtml = "";
                    // switch (row.kelas) {
                    switch (row.kdTind) {
                        case "94":
                            hasilLabHtml = `<select class="form-control-sm col hasil" id="hasil${row.idLab}">`;
                            hasilLabHtml += `<option value="">--Pilih Hasil--</option>`;
                            hasilLabHtml += `<option value="Hasil di SITB" ${
                                data === "Hasil di SITB" ? "selected" : ""
                            }>Hasil di SITB (TCM)</option>`;
                            hasilLabHtml += `<option value="Negatif" ${
                                data === "Negatif" ? "selected" : ""
                            }>Negatif (BTA/TCM)</option>`;
                            hasilLabHtml += `<option value="+1" ${
                                data === "+1" ? "selected" : ""
                            }>+ 1 (BTA)</option>`;
                            hasilLabHtml += `<option value="+2" ${
                                data === "+2" ? "selected" : ""
                            }>+ 2 (BTA)</option>`;
                            hasilLabHtml += `<option value="+3" ${
                                data === "+3" ? "selected" : ""
                            }>+ 3 (BTA)</option>`;
                            hasilLabHtml += `<option value="+1-9" ${
                                data === "+1-9" ? "selected" : ""
                            }>+ 1-9 (BTA)</option>`;
                            hasilLabHtml += `</select>`;
                            break;
                        case "93":
                            hasilLabHtml = `<select class="form-control-sm col hasil" id="hasil${row.idLab}">`;
                            hasilLabHtml += `<option value="">--Pilih Hasil--</option>`;
                            hasilLabHtml += `<option value="NR" ${
                                data === "NR" ? "selected" : ""
                            }>Non Reaktif (HIV)</option>`;
                            hasilLabHtml += `<option value="Reaktif" ${
                                data === "Reaktif" ? "selected" : ""
                            }>Reaktif (HIV)</option>`;
                            hasilLabHtml += `<option value="Negatif" ${
                                data === "Negatif" || data === "NEGATIF"
                                    ? "selected"
                                    : ""
                            }>Negatif (Sifilis)</option>`;
                            hasilLabHtml += `<option value="Positif" ${
                                data === "Positif" ? "selected" : ""
                            }>Positif (Sifilis)</option>`;
                            hasilLabHtml += `</select>`;
                            break;
                        case "99":
                            hasilLabHtml = `<select class="form-control-sm col hasil" id="hasil${row.idLab}">`;
                            hasilLabHtml += `<option value="">--Pilih Hasil--</option>`;
                            hasilLabHtml += `<option value="A" ${
                                data === "A" ? "selected" : ""
                            }>A</option>`;
                            hasilLabHtml += `<option value="B" ${
                                data === "B" ? "selected" : ""
                            }>B</option>`;
                            hasilLabHtml += `<option value="AB" ${
                                data === "AB" ? "selected" : ""
                            }>AB</option>`;
                            hasilLabHtml += `<option value="O" ${
                                data === "O" ? "selected" : ""
                            }>O</option>`;
                            hasilLabHtml += `</select>`;
                            break;
                        case "97":
                            hasilLabHtml = `<select class="form-control-sm col hasil" id="hasil${row.idLab}">`;
                            hasilLabHtml += `<option value="">--Pilih Hasil--</option>`;
                            hasilLabHtml += `<option value="IGG NR, IGM NR" ${
                                data === "IGG NR, IGM NR" ? "selected" : ""
                            }>IGG dan IGM NR</option>`;
                            hasilLabHtml += `<option value="IGG R, IGM R" ${
                                data === "IGG R, IGM R" ? "selected" : ""
                            }>IGG dan IGM R</option>`;
                            hasilLabHtml += `<option value="IGG NR, IGM R" ${
                                data === "IGG NR, IGM R" ? "selected" : ""
                            }>IGG NR dan IGM R</option>`;
                            hasilLabHtml += `<option value="IGG R, IGM NR" ${
                                data === "IGG R, IGM NR" ? "selected" : ""
                            }>IGG R dan IGM NR</option>`;

                            hasilLabHtml += `</select>`;
                            break;
                        case "98":
                            hasilLabHtml = `<input type="text" class="form-control-sm col hasil" id="hasil${row.idLab}" value="Terlampir">`;
                            break;
                        default:
                            hasilLabHtml = `<input type="text" class="form-control-sm col hasil" id="hasil${row.idLab}" value="${data}">`;
                    }
                    return hasilLabHtml;
                },
            },
            {
                data: "ket",
                render: (data, type, row) =>
                    `<input type="text" class="form-control-sm col hasil" id="ket${row.idLab}" value="${data}">`,
            },
        ],
        order: [[2, "desc"]],
        scrollY: "320px",
        scrollCollapse: true,
        paging: false,
    });
    scrollToInputSection();
}

async function cetak(norm, tgl) {
    const requestData = { norm: norm, tgl: tgl };
    try {
        const response = await fetch("/api/hasil/lab/cetak", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(requestData),
        });

        if (!response.ok) {
            if (response.status == 404) {
                console.error("No data found");
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Terjadi kesalahan saat mengambil data pasien...!!!",
                });
                throw new Error("Network response was not ok");
            }
        } else {
            const data = await response.json();
            let printWindow = window.open(data, "_blank");

            Swal.close();
        }
    } catch (error) {
        console.error("Terjadi kesalahan saat mencari data:", error);
        Swal.fire({
            icon: "error",
            title: "Terjadi kesalahan saat mencari data...!!! /n" + error,
        });
    }

    printWindow.open();
    // printWindow.print();
    // printWindow.close();
}

function simpan() {
    const norm = $("#norm").val();
    const notrans = $("#notrans").val();
    const tglTrans = $("#tgltrans").val();

    if (!norm || !notrans) {
        const dataKurang = [];
        if (!norm) dataKurang.push("No RM");
        if (!notrans) dataKurang.push("Nomor Transaksi");

        Swal.fire({
            icon: "error",
            title: `Data Tidak Lengkap...!!! ${dataKurang.join(
                ", "
            )} Belum Diisi`,
        });
        return;
    }

    const table = $("#inputHasil").DataTable();
    const dataRows = table.rows().data();

    const dataTerpilih = dataRows
        .map((row) => ({
            idLab: row.idLab,
            idLayanan: row.idLayanan,
            norm: norm,
            notrans: notrans,
            hasil: $("#hasil" + row.idLab).val(),
            petugas: $("#analis" + row.idLab).val(),
            ket: $("#ket" + row.idLab).val(),
        }))
        .toArray();

    fetch("/api/addHasilLab", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            dataTerpilih: dataTerpilih,
            keterangan: keterangan,
            tglTrans: tglTrans,
        }),
    })
        .then((response) => {
            if (!response.ok) throw new Error("Network response was not ok");
            return response.json();
        })
        .then((data) => {
            Swal.fire({
                icon: "success",
                title: "Data berhasil tersimpan...!!!",
            });
            resetForm("Data berhasil tersimpan...!!!");
        })
        .catch((error) => {
            console.error(
                "There has been a problem with your fetch operation:",
                error
            );
            Swal.fire({
                icon: "error",
                title: `There has been a problem with your fetch operation: ${error}`,
            });
        });
}

function resetForm(message) {
    document.getElementById("form_identitas").reset();

    document.getElementById("statusSwitch").checked = false; // Uncheck the switch
    document.getElementById("statusLabel").textContent = "Belum"; // Update the text
    keterangan = "Belum";

    if ($.fn.DataTable.isDataTable("#inputHasil")) {
        let tableTrans = $("#inputHasil").DataTable();
        tableTrans.clear().destroy();
    }
    Swal.fire({
        icon: "info",
        title: message + "\n Maturnuwun...!!!",
    });

    document.getElementById("tgltrans").value = new Date()
        .toISOString()
        .split("T")[0];
    antrian();

    $("#waktuSelesai").text("-");
    $("#divSwitch").show();
}

function antrian() {
    $("#loadingSpinner").show();
    var tgl = $("#tanggal").val();

    if ($.fn.DataTable.isDataTable("#antrianBelum, #antrianSudah")) {
        $("#antrianBelum, #antrianSudah").DataTable().destroy();
    }

    $.ajax({
        url: "/api/hasil/antrian",
        type: "POST",
        data: { tgl: tgl },
        success: function (response) {
            $("#loadingSpinner").hide();
            var data = response;

            if (Array.isArray(data)) {
                var belumTransaksi = data.filter(function (item) {
                    return (
                        item.status === "Belum Lengkap" ||
                        item.status === "Belum"
                    );
                });

                var sudahTransakasi = data.filter(function (item) {
                    return item.status === "Lengkap";
                });

                antrianBelum(belumTransaksi, tgl);
                antrianSudah(sudahTransakasi, tgl);
            } else {
                console.error("Invalid data format:", data);
            }
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        },
    });
}

function antrianBelum(belumTransaksi, tgl) {
    belumTransaksi.forEach(function (item, index) {
        // Mengambil nmLayanan dari pemeriksaan
        const nmLayananArray = item.pemeriksaan.map(
            (pem) => pem.pemeriksaan.nmLayanan
        );

        // Menggabungkan nmLayanan menjadi satu string
        item.pemeriksaan = nmLayananArray.join(", ");
        item.no = index + 1;
        item.tgl = tgl;
        item.tanggal = moment(item.created_at).format("DD-MM-YYYY");
        item.alamat = item.alamat.replace(/, [^,]*$/, "");
        item.aksi = `<button class="btn btn-danger bg-danger"
                            data-toggle="tooltip"
                            data-placement="right"
                            title="Input Hasil Lab"
                            data-norm="${item.norm}"
                            data-nama="${item.nama}"
                            data-alamat="${item.alamat}"
                            onclick="cariTsLab('${item.norm}', '${item.tgl}','tampil');"><i class="fa-solid fa-file-pen"></i></button>

                            `;
    });

    $("#antrianBelum").DataTable({
        data: belumTransaksi,
        columns: [
            { data: "aksi" },
            {
                data: "status",
                className: "text-center",
                render: function (data) {
                    var backgroundColor =
                        data === "Belum Lengkap" ? "warning" : "danger";
                    return `<div class="badge badge-${backgroundColor}">${data}</div>`;
                },
            },
            { data: "tanggal" },
            { data: "layanan" },
            { data: "norm" },
            { data: "nama", className: "col-1" },
            { data: "pemeriksaan" },
            { data: "alamat", className: "col-2" },
            { data: "nama_dokter", className: "col-3" },
        ],
        paging: true,
        lengthMenu: [
            [5, 10, 25, 50, -1],
            [5, 10, 25, 50, "All"],
        ],
        pageLength: 5,
        responsive: true,
    });
}

function antrianSudah(sudahTransakasi, tgl) {
    sudahTransakasi.forEach(function (item, index) {
        const nmLayananArray = item.pemeriksaan.map(
            (pem) => pem.pemeriksaan.nmLayanan
        );
        var sebutan = "";
        let umur = item.umur.split("th")[0];
        if (umur <= 14) {
            sebutan = "Anak ";
        } else if (umur > 14 && umur <= 30) {
            if (item.jk == "L") {
                sebutan = "Saudara ";
            } else {
                sebutan = "Nona ";
            }
        } else if (umur > 30) {
            if (item.jk == "L") {
                sebutan = "Bapak ";
            } else {
                sebutan = "Ibu ";
            }
        }
        item.pemeriksaan = nmLayananArray.join(", ");
        item.no = index + 1;
        item.tgl = tgl;
        item.tanggal = moment(item.created_at).format("DD-MM-YYYY");
        item.alamat = item.alamat.replace(/, [^,]*$/, "");
        let desa = item.alamat.split(",")[0];

        item.aksi = `<div>
                        <button class="btn btn-danger"
                                data-toggle="tooltip"
                                data-placement="right"
                                title="Edit Hasil Lab"
                                data-norm="${item.norm}"
                                data-nama="${item.nama}"
                                data-alamat="${item.alamat}"
                                onclick="cariTsLab('${item.norm}', '${item.tgl}','tampil');"><i class="fa-solid fa-file-pen"></i></button>
                        <a href="/api/hasil/lab/cetak/${item.notrans}/${item.tgl}" method="get" target="_blank" class="btn btn-success"
                                data-toggle="tooltip" data-placement="right" title="Cetak Hasil Lab"><i class="fa-solid fa-print"></i></a>
                    </div>
                    <div class="mt-2">
                        <a class="panggil px-2 btn btn-sm btn-warning"
                            onclick="panggil('${sebutan} ${item.nama} dari ${desa}, silahkan menuju ke loket laboratorium')">Panggil <i class="fa-solid fa-volume-high"></i></a>
                    </div>`;
    });

    $("#antrianSudah").DataTable({
        data: sudahTransakasi,
        columns: [
            { data: "aksi", className: "col-1" },
            {
                data: "status",
                className: "text-center",
                render: function (data) {
                    var backgroundColor = "success";
                    return `<div class="badge badge-${backgroundColor}">${data}</div>`;
                },
            },
            { data: "tanggal" },
            { data: "layanan" },
            { data: "norm" },
            { data: "nama", className: "col-1" },
            { data: "pemeriksaan" },
            { data: "alamat", className: "col-2" },
            { data: "nama_dokter", className: "col-3" },
        ],
        paging: true,
        lengthMenu: [
            [5, 10, 25, 50, -1],
            [5, 10, 25, 50, "All"],
        ],
        pageLength: 3,
        responsive: true,
    });
}

// Fungsi untuk memproses dan menampilkan teks panggilan
// function panggil(pesan) {
//     console.log("🚀 ~ panggil ~ pesan:", pesan);
//     // Jika Anda ingin menambahkan audio panggilan, contoh penggunaan SpeechSynthesis API:
//     const utterance = new SpeechSynthesisUtterance(pesan);
//     utterance.lang = "id-ID"; // Bahasa Indonesia
//     speechSynthesis.speak(utterance);
// }

// function panggil(pesan) {
//     console.log("🚀 ~ panggil ~ pesan:", pesan);

//     // Membuat objek SpeechSynthesisUtterance dengan pesan
//     const utterance = new SpeechSynthesisUtterance(pesan);
//     utterance.lang = "id-ID"; // Bahasa Indonesia
//     utterance.rate = 1; // Menurunkan kecepatan (0.1 - 2, default: 1)

//     // Mendapatkan daftar suara yang tersedia
//     const voices = speechSynthesis.getVoices();

//     // Memilih suara wanita (jika tersedia) dalam bahasa Indonesia
//     const femaleVoice = voices.find(
//         (voice) => voice.lang === "id-ID" && voice.name.includes("Female")
//     );
//     console.log("🚀 ~ panggil ~ femaleVoice:", femaleVoice);

//     // Jika ditemukan, gunakan suara wanita; jika tidak, tetap gunakan default
//     if (femaleVoice) {
//         utterance.voice = femaleVoice;
//     }

//     // Memutar suara
//     speechSynthesis.speak(utterance);
// }
// function panggil(pesan) {
//     console.log("🚀 ~ panggil ~ pesan:", pesan);

//     // 1. Putar suara dingdong sebelum memulai ucapan
//     const dingdong = new Audio("/audio/dingdong.mp3"); // Ganti path sesuai file Anda
//     dingdong.play();

//     // 2. Setelah suara dingdong selesai, ucapkan pesan
//     dingdong.onended = () => {
//         const utterance = new SpeechSynthesisUtterance(pesan);
//         utterance.lang = "id-ID"; // Bahasa Indonesia
//         utterance.rate = 0.6; // Kecepatan bicara
//         utterance.pitch = 1; // Nada suara

//         // Pilih suara wanita Bahasa Indonesia
//         const voices = speechSynthesis.getVoices();
//         const femaleVoice = voices.find(
//             (voice) => voice.lang === "id-ID" && voice.name.includes("Female")
//         );
//         if (femaleVoice) {
//             utterance.voice = femaleVoice;
//         }

//         speechSynthesis.speak(utterance);
//     };
// }

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

// Pastikan daftar suara sudah dimuat sebelum fungsi dipanggil
speechSynthesis.onvoiceschanged = () => {
    console.log("Voices loaded");
};

$(document).ready(function () {
    setTodayDate();
    antrian();
    $("#dataTrans").on("click", ".delete", function (e) {
        e.preventDefault();
        let idLab = $(this).data("id");
        let layanan = $(this).data("layanan");
        deletLab(idLab, layanan);
    });
});
