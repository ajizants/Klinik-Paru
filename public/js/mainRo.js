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

function validateAndSubmit() {
    var inputsToValidate = [
        "notrans",
        "norm",
        "nama",
        "alamat",
        "jk",
        "tglRo",
        "noreg",
        "kdFoto",
        "kdFilm",
        "ma",
        "kv",
        "s",
        "jmlExpose",
        "jmlFilmDipakai",
        "jmlFilmRusak",
        "kdMesin",
        "kdProyeksi",
        "layanan",
        "p_rontgen",
        "dokter",
    ];

    var error = false;

    inputsToValidate.forEach(function (inputId) {
        var inputElement = document.getElementById(inputId);
        var inputValue = inputElement.value.trim();

        if (inputValue === "") {
            if ($(inputElement).hasClass("select2-hidden-accessible")) {
                // Select2 element
                $(inputElement)
                    .next(".select2-container")
                    .addClass("input-error");
            } else {
                // Regular input element
                inputElement.classList.add("input-error");
            }
            error = true;
        } else {
            if ($(inputElement).hasClass("select2-hidden-accessible")) {
                // Select2 element
                $(inputElement)
                    .next(".select2-container")
                    .removeClass("input-error");
            } else {
                // Regular input element
                inputElement.classList.remove("input-error");
            }
        }
    });
    if (error) {
        // Tampilkan pesan error menggunakan Swal jika ada input yang kosong
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Ada data yang masih kosong! Mohon lengkapi semua data.",
        });
    } else {
        // Lakukan pengiriman data atau proses selanjutnya jika semua data valid
        simpan(); // Contoh: Panggil fungsi simpan() jika semua data valid
    }
}

var gambarInput = document.getElementById("fileRo");
var gambarInput2 = document.getElementById("fileRo2");
var gambarInput3 = document.getElementById("fileRo3");
async function simpan() {
    try {
        var notrans = document.getElementById("notrans").value;
        var norm = document.getElementById("norm").value;
        var nama = document.getElementById("nama").value;
        var alamat = document.getElementById("alamat").value;
        var tgltrans = document.getElementById("tglRo").value;
        var jk = document.getElementById("jk").value;
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
        var catatan = document.getElementById("catatan").value;
        var layanan = document.getElementById("layanan").value;
        var p_rontgen = document.getElementById("p_rontgen").value;
        var p_rontgen_evaluator = document.getElementById(
            "p_rontgen_evaluator"
        ).value;
        var dokter = document.getElementById("dokter").value;

        var ket_foto = document.getElementById("ket_foto").value;
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
        formData.append("alamat", alamat);
        formData.append("jk", jk);
        formData.append("tgltrans", tgltrans);
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
        formData.append("catatan", catatan);
        formData.append("layanan", layanan);
        formData.append("p_rontgen", p_rontgen);
        formData.append("p_rontgen_evaluator", p_rontgen_evaluator);
        formData.append("dokter", dokter);
        formData.append("ket_foto", ket_foto);
        formData.append("gambar", foto);

        // Kirim data menggunakan fetch API dengan async/await
        const response = await fetch("/api/addTransaksiRo", {
            method: "POST",
            body: formData,
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || response.statusText);
        }

        const responseData = await response.json();
        console.log("Data berhasil disimpan:", responseData);
        $msgThorax = responseData.data.foto_thorax;
        $msgTrans = responseData.data.transaksi;

        Swal.fire({
            icon: "success",
            title:
                "Data berhasil disimpan,\n\n" +
                "Maturnuwun...!!\n\nKeterangan: " +
                $msgTrans +
                ", " +
                $msgThorax,
            allowOutsideClick: false,
        }).then((result) => {
            if (result.isConfirmed && msgSelesai != undefined) {
                Swal.fire({
                    icon: "info",
                    title: msgSelesai,
                    allowOutsideClick: false,
                });
            }
        });

        console.log("🚀 ~ msgSelesai:", msgSelesai);

        // rstForm();
        updateHasilro(norm, tgltrans);
        updateAntrian();
    } catch (error) {
        console.error("Terjadi kesalahan saat menyimpan data:", error.massage);

        // Display error message using SweetAlert
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Terjadi kesalahan saat menyimpan data: " + error.message,
        });
    }
}

async function updateHasilro(norm, tgltrans) {
    const requestData = {
        norm: norm,
        tgltrans: tgltrans,
    };
    try {
        const response = await fetch("/api/hasilRo", {
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
                throw new Error("Network response was not ok");
            }
        } else {
            const data = await response.json();
            const foto = data.data;
            console.log("🚀 ~ updateHasilro ~ foto:", foto);
            showFoto(foto);
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
async function update() {
    try {
        var idFoto = document.getElementById("idFoto").value;
        var norm = document.getElementById("norm").value;
        var nama = document.getElementById("nama").value;
        var tglRo = document.getElementById("tglRo").value;

        var ket_foto = document.getElementById("ket_foto_new").value;
        var gambar = gambarInput2.files[0];

        // Membuat objek FormData untuk mengirim data dengan file
        var formData = new FormData();
        formData.append("idFoto", idFoto);
        formData.append("norm", norm);
        formData.append("nama", nama);
        formData.append("tgltrans", tglRo);

        if (gambar) {
            formData.append("gambar", gambar);
            formData.append("ket_foto", ket_foto);
        }

        // Kirim data menggunakan fetch API dengan async/await
        const response = await fetch("/api/updateRo", {
            method: "POST",
            body: formData,
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || response.statusText);
        }

        const responseData = await response.json();
        console.log("Data berhasil disimpan:", responseData);
        var $msg = responseData.message;
        console.log("🚀 ~ update ~ $msg:", $msg);
        Swal.fire({
            icon: "success",
            title:
                "Data berhasil disimpan,\n \n" +
                "Maturnuwun...!!" +
                "\n\nKeterangan: " +
                $msg,
        });
    } catch (error) {
        console.error("Terjadi kesalahan saat menyimpan data:", error.message);

        // Tampilkan pesan error menggunakan SweetAlert
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Terjadi kesalahan saat menyimpan data: " + error.message,
        });
    }
}

function deleteFoto(id) {
    console.log("🚀 ~ deleteFoto ~ id:", id);

    Swal.fire({
        title: "Konfirmasi",
        text: "Apakah Anda yakin ingin menghapus?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, Hapus!",
    }).then(async (result) => {
        // Menambahkan async di sini
        if (result.isConfirmed) {
            try {
                const response = await fetch("/api/deleteFotoPasien", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({ id: id }),
                });

                console.log("🚀 ~ deleteFoto ~ response:", response);
                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || response.statusText);
                } else {
                    Swal.fire({
                        icon: "success",
                        title: "Data Berhasil",
                        text: "Data berhasil dihapus.",
                    });
                    removeRow(id);
                }
            } catch (error) {
                console.error(
                    "Terjadi kesalahan saat menghapus data:",
                    error.message
                );
                Swal.fire({
                    icon: "error",
                    title: "Terjadi Kesalahan",
                    text: "Gagal menghapus data: " + error.message,
                });
            }
        }
    });
}

function deleteTransaksi(button) {
    console.log("🚀 ~ setTransaksi ~ setTransaksi:", setTransaksi);
    const notrans = $(button).data("notrans");
    const nama = $(button).data("nama");
    const tgltrans = $(button).data("tgltrans");

    console.log("🚀 ~ deleteTransaksi ~ notrans:", notrans);
    console.log("🚀 ~ deleteTransaksi ~ tgltrans:", tgltrans);
    Swal.fire({
        title: "Konfirmasi",
        text:
            "Apakah Anda yakin ingin menghapus transaksi atas nama: " +
            nama +
            "?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, Hapus!",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/api/deleteTransaksiRo",
                type: "POST",
                data: { notrans: notrans, tanggal: tgltrans },
                success: function (response) {
                    if (response.status === "success") {
                        Swal.fire({
                            icon: "success",
                            title: "Data Berhasil",
                            text: "Data berhasil dihapus.",
                        });
                        removeRow(notrans);
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Terjadi Kesalahan",
                            text: "Gagal menghapus data: " + response.message,
                        });
                    }
                },
                error: function (xhr, status, error) {
                    console.log(error);
                    Swal.fire({
                        icon: "error",
                        title: "Terjadi Kesalahan",
                        text: "Gagal menghapus data: " + error,
                    });
                },
            });
        }
    });
}

function removeRow(id) {
    const table = $("#tableRo").DataTable();
    const row = $(`#tableRo .btn-danger[data-id='${id}']`).closest("tr");

    if (row.length) {
        table.row(row).remove().draw();
    } else {
        console.error("🚀 ~ Row not found with id:", id);
    }
}

async function cariTsRo(norm, tgl) {
    console.log("🚀 ~ cariTsRo ~ tgl:", tgl);
    console.log("🚀 ~ cariTsRo ~ norm:", norm);
    // rstForm();
    // formatNorm($("#norm"));
    norm = norm ? norm : formatNorm($("#norm"));
    tgl = tgl ? tgl : $("#tglRo").val();
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
                // cariKominfo(norm, tgl, "ro");
                $("#tableRo").DataTable({
                    data: [
                        {
                            ket: "Belum Ada Transaksi",
                        },
                    ],
                    columns: [
                        {
                            data: "ket",
                            createdCell: function (
                                td,
                                cellData,
                                rowData,
                                row,
                                col
                            ) {
                                $(td)
                                    .attr("colspan", 5)
                                    .addClass("bg-warning text-center");
                            },
                        },
                    ],
                    paging: false, // Disable pagination if not needed
                    searching: false, // Disable searching if not needed
                    ordering: false, // Disable ordering if not needed
                    info: false, // Disable table information display
                });
                $("#noreg").val("");
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Terjadi kesalahan saat mengambil data pasien...!!!",
                });
                throw new Error("Network response was not ok");
            }
            Swal.close();
        } else {
            const data = await response.json();

            // Ensure data and the 'transaksi_ro' object exist before setting values
            if (data && data.data && data.data.transaksi_ro) {
                const transaksi = data.data.transaksi_ro;
                const petugas = data.data.petugas;
                const foto = data.data.foto_thorax;
                if (foto && foto.length > 0) {
                    showFoto(foto);
                }

                //ternary check transaksi.jk, if null get from transaksi.pasien.jkel
                let noreg = transaksi.noreg;
                console.log("🚀 ~ cariTsRo ~ noreg:", noreg);
                jk = transaksi.jk || transaksi.pasien.jkel;
                // var alamat = `${transaksi.pasien.kelurahan}, ${transaksi.pasien.rtrw}, ${transaksi.pasien.kecamatan}, ${transaksi.pasien.kabupaten}`;
                $("#norm").val(transaksi.norm || "");
                $("#nama").val(transaksi.nama || "");
                $("#alamat").val(transaksi.alamat || "");
                // jk = transaksi.pasien.jkel || "";
                $("#jk").val(transaksi.jk || transaksi.pasien.jkel);
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
                $("#p_rontgen_evaluator")
                    .val(petugas.p_rontgen_evaluator || "")
                    .trigger("change");

                closeSwalAfterDelay();
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

async function hasilRo(norm, tgl) {
    var norm = norm || $("#norm").val();
    var tgl = tgl || $("#tglRo").val();
    try {
        const response = await fetch("/api/ro", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ norm, tgl }),
        });
        const data = await response.json();
        console.log(data);
    } catch (error) {
        console.error("Terjadi kesalahan saat mencari data:", error);
    }
}

function showFoto(foto) {
    console.log("🚀 ~ showFoto ~ foto:", foto);

    $("#preview").show();
    if ($.fn.DataTable.isDataTable("#tableRo")) {
        var tabel = $("#tableRo").DataTable();
        tabel.clear().destroy();
    }
    foto.forEach(function (item, index) {
        let ket = ""; // Default value if no valid 'ket' part is found
        // let ketFoto = item.norm + "-" + item.nama ;
        // item.ketFoto = ketFoto;
        // Check if the file name includes an underscore and has at least three parts
        if (item.foto.includes("_")) {
            const parts = item.foto.split("_");

            if (parts.length > 2) {
                // Safely access the third part and remove the extension
                ket = parts[2].split(".")[0];
            }
        }

        // If 'ket' is empty or undefined, you can provide a fallback or leave it as an empty string
        if (!ket) {
            ket = ""; // Or some other fallback value
        }

        item.actions = `<a type="button"  class="btn-sm btn-danger mx-2 mt-md-0 mt-2"
                            data-id="${item.id}"
                            data-foto="${item.foto}"
                            data-ketFoto="${item.ketFoto}"
                            data-norm="${item.norm}"
                            data-nama="${item.nama}"
                            onclick="deleteFoto('${item.id}')"
                            ><i class="fas fa-trash"></i></a>
                        <a type="button"  class="btn-sm btn-warning mx-2 mt-md-0 mt-2"
                            data-id="${item.id}"
                            data-foto="${item.foto}"
                            data-ketFoto="${item.ketFoto}"
                            data-norm="${item.norm}"
                            data-nama="${item.nama}"
                            data-toggle="modal" data-target="#staticBackdrop"
                            onclick="document.getElementById('idFoto').value = '${item.id}'; document.getElementById('nmFoto').value = '${item.foto}';document.getElementById('ket_foto_new').value = '${ket}';"
                            ><i class="fas fa-pen-to-square"></i></a> `;
        item.buttonShow = `<a type="button" class="btn-sm btn-primary px-5" data-toggle="modal" data-target="#modalFoto" onclick="modalFotoShow('${item.foto}','${item.norm} - ${item.nama}','${item.tanggal}')">
                            <i class="fa-solid fa-eye"></i></a>`;
        item.no = index + 1;
    });

    $("#tableRo").DataTable({
        data: foto,
        paging: false,
        searching: false,
        ordering: false,
        columns: [
            { data: "actions", className: "text-center col-2" },
            { data: "id" },
            {
                data: "buttonShow",
            },
            { data: "tanggal" },
            { data: "foto" },
        ],
    });
}

function modalFotoShow(foto, ketFoto, tgl) {
    const fullImageUrl = appUrlRo + foto;
    // document.getElementById("modalFotoImage").src = fullImageUrl;
    document.getElementById("zoomed-image").src = fullImageUrl;
    $("#keteranganFoto").html(`<b>${ketFoto}</b>`);
    $("#keteranganFoto2").html(`<b>${tgl}</b>`);
    const container = document.getElementById("myPanzoom");
    const options = {
        click: "toggleCover",
        Toolbar: {
            display: ["zoomIn", "zoomOut"],
        },
    };

    new Panzoom(container, options);
    $("#modalFoto").modal("show");
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
    document.getElementById("form_identitas").reset();
    document.getElementById("formtrans").reset();
    $("#preview").hide();
    $("#formtrans select").trigger("change");
    $("#form_identitas select").trigger("change");
    $("#permintaan").html("");
    $("#tujuanLain").html("Penunjang Hari ini:");
    scrollToTop();
    setTglRo();
    setTodayDate();
    console.log("🚀 ~ msgSelesai:", msgSelesai);
    if (msgSelesai != undefined)
        Swal.fire({
            icon: "info",
            title: msgSelesai,
            allowOutsideClick: false,
        });
    msgSelesai = undefined;
}

function askRo(button) {
    var norm = $(button).data("norm");
    var nama = $(button).data("nama");
    var dokter = $(button).data("kddokter");
    var alamat = $(button).data("alamat");
    var layanan = $(button).data("layanan");
    var notrans = $(button).data("notrans");
    var tgltrans = $(button).data("tgltrans");
    var asktind = $(button).data("asktind");
    var tujuan = $(button).data("tujuan");
    jk = $(button).data("jk");

    $("#norm").val(norm);
    $("#nama").val(nama);
    $("#dokter").val(dokter);
    $("#dokter").trigger("change");
    $("#alamat").val(alamat);
    $("#layanan").val(layanan).trigger("change");
    $("#notrans").val(notrans);
    $("#tgltrans").val(tgltrans);
    $("#jk").val(jk);

    // Memperbarui konten asktindContent
    $("#permintaan").html(`<b>${asktind}</b>`);
    $("#tujuanLain").html(
        `<div class="font-weight-bold bg-warning rounded">${tujuan}</div>`
    );

    scrollToInputSection();
}

async function updateAntrian() {
    var tbBlmUpload = $("#daftarUpload");
    var tbSelesai = $("#daftarSelesai");
    $("#daftarTunggu").DataTable();

    // var ruang = "ro";
    // fetchAntrianData(ruang, function (data) {
    //     processAntrianData(data, "Belum Upload Foto Thorax", tbBlmUpload);
    //     processAntrianData(data, "Sudah Selesai", tbSelesai);
    // });

    antrianAll("ro");
    antrian("ro");
}

function tbAntrianBelumUpload(tabel, antrian) {
    tabel.DataTable({
        data: antrian,
        columns: [
            { data: "aksi", className: "text-center p-2 col-1" },
            {
                data: "status",
                className: "text-center p-2 col-1",
                render: function (data) {
                    var badgeClass =
                        {
                            "Belum Ada Transaksi": "danger",
                            "Belum Upload Foto Thorax": "warning",
                            "Sudah Selesai": "success",
                        }[data] || "secondary";
                    return `<div class="badge badge-${badgeClass}">${data}</div>`;
                },
            },
            { data: "tanggal", className: "col-1 p-2" },
            { data: "antrean_nomor", className: "text-center p-2" },
            { data: "penjamin_nama", className: "text-center p-2" },
            { data: "pasien_no_rm", className: "text-center p-2" },
            { data: "pasien_nama", className: "p-2 col-3" },
            { data: "poli_nama", className: "p-2" },
            { data: "dokter_nama", className: "p-2 col-3" },
        ],
        order: [[3, "asc"]],
    });
}

function fetchAntrianData(ruang, callback) {
    var tanggal = $("#tanggal").val();
    $.ajax({
        url: "/api/antrian/kominfo",
        type: "post",
        data: { tanggal, ruang },
        success: function (response) {
            callback(response.response.data);
        },
        error: function (xhr) {
            console.error("Error fetching antrian data:", xhr);
        },
    });
}

function initializeAntrian(tabel, antrian) {
    tbAntrianBelumUpload(tabel, antrian);
}

function processAntrianData(data, filter, tabel) {
    $("#loadingSpinner").show();
    var filteredData = data.filter(function (item) {
        return item.status === filter;
    });

    filteredData.forEach(function (item) {
        item.aksi = `<a type="button" class="aksi-button btn-sm btn-primary py-0 icon-link icon-link-hover"
                      onclick="cariTsRo('${item.pasien_no_rm}','${$(
            "#tanggal"
        ).val()}');rstForm();"><i class="fas fa-pen-to-square"></i></a>`;
    });

    if ($.fn.DataTable.isDataTable(tabel)) {
        var table = tabel.DataTable();
        table.clear().rows.add(filteredData).draw();
    } else {
        initializeAntrian(tabel, filteredData);
    }
    $("#loadingSpinner").hide();
}

window.addEventListener("load", function () {
    setTglRo();
    setTodayDate();
    updateAntrian();
    scrollToTop();
    $("#norm").on("keyup", function (event) {
        if (event.key === "Enter") {
            event.preventDefault();
            var tgl = $("#tglRo").val();
            var norm = $("#norm").val();

            // Mengecek apakah norm dan tgl sudah diisi
            if (!norm || !tgl) {
                Swal.fire({
                    icon: "error",
                    title: "No RM dan Tanggal Belum di isi...!!!",
                });
            } else {
                // Memanggil fungsi cariTsRo jika norm dan tgl tidak kosong
                cariTsRo(norm, tgl);
            }
        }
    });
});
