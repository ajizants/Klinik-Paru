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
    if ($.fn.DataTable.isDataTable("#inputHasil")) {
        var table = $("#inputHasil").DataTable();
        table.destroy();
    }

    try {
        var data = pemeriksaan;
        // console.log("🚀 ~ dataLab ~ data:", data);
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

        data.forEach((item, index) => {
            item.no = index + 1;
            // Jika hasil null maka kembalikan ""
            item.hasiLab = item.hasil == null ? "" : item.hasil;
            item.ket = item.ket == null ? "" : item.ket;
            var kelas = item.pemeriksaan.kelas;
            // console.log("🚀 ~ data.forEach ~ kelas:", kelas);
        });

        $("#inputHasil").DataTable({
            data: data,
            columns: [
                {
                    data: "no",
                    render: function (data, type, row) {
                        return `<p type="text" class="form-control-sm col hasil" >${data}</p>`;
                    },
                },
                {
                    data: "norm",
                    render: function (data, type, row) {
                        return `<p type="text" class="form-control-sm col hasil" >${data}</p>`;
                    },
                },
                {
                    data: "pemeriksaan.nmLayanan",
                    render: function (data, type, row) {
                        return `<p type="text" class="form-control-sm col hasil" id="layanan${row.idLayanan}" value="${row.idLayanan}" readonly>${data}</p>`;
                    },
                },
                {
                    data: "petugas",
                    render: function (data, type, row) {
                        var inputId = "analis" + row.idLab;
                        var inputField = `<select id="${inputId}" class="form-control-sm col analis">`;
                        inputField += `<option value="">--- Pilih Petugas ---</option>`;
                        analisData.forEach(function (petugas) {
                            var selected =
                                data === petugas.nip ? "selected" : "";
                            inputField += `<option value="${petugas.nip}" ${selected}>${petugas.gelar_d} ${petugas.nama} ${petugas.gelar_b}</option>`;
                        });
                        inputField += "</select>";
                        return inputField;
                    },
                },
                {
                    data: "hasiLab",
                    render: function (data, type, row) {
                        var kelas = row.pemeriksaan.kelas;
                        var hasilLabHtml = "";

                        if (kelas === "94") {
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
                        } else if (kelas === "93") {
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
                        } else if (kelas === "99") {
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
                        } else if (kelas === "97") {
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
                        } else if (kelas === "98") {
                            hasilLabHtml = `<input type="text" class="form-control-sm col hasil" id="hasil${row.idLab}" value="Terlampir">`;
                        } else {
                            hasilLabHtml = `<input type="text" class="form-control-sm col hasil" id="hasil${row.idLab}" value="${data}">`;
                        }

                        return hasilLabHtml;
                    },
                },
                {
                    data: "ket",
                    render: function (data, type, row) {
                        var ketHasilLabHtml = "";
                        ketHasilLabHtml = `<input type="text" class="form-control-sm col hasil" id="ket${row.idLab}" value="${data}">`;
                        return ketHasilLabHtml;
                    },
                },
            ],
            order: [2, "dsc"],
            scrollY: "320px",
            scrollCollapse: true,
            paging: false,
        });
        scrollToInputSection();
    } catch (error) {
        console.error("Error:", error.message);
    }
}

function cetak(data) {
    console.log("🚀 ~ cetak ~ data:", data);
    const pemeriksaan = data.pemeriksaan;

    Swal.fire({
        icon: "success",
        title: "Verifikasi Berhasil...!!",
        timer: 3000,
    });

    let printWindow = window.open("", "_blank");
    printWindow.document.write(`<html><head><title>Cetak Hasil Lab</title>`);

    printWindow.document.write(`<table width="100%" style="color: black;">
                                <tbody><tr>
                                    <td width="20%" style="text-align: center; padding-top: 10px; padding-bottom: 10px">
                                        <img src="https://kkpm.banyumaskab.go.id/assets/img/banyumas.png" style="width: 30%;">
                                    </td>
                                    <td width="60%">
                                        <p style="font-size: 20px; margin-bottom: -5px; text-align: center; margin-top: 0px;">
                                            PEMERINTAH KABUPATEN BANYUMAS
                                        </p>

                                        <p style="font-size: 20px; margin-bottom: -5px; text-align: center; margin-top: 0px;">
                                            DINAS KESEHATAN
                                        </p>

                                        <p style="font-size: 20px; margin-bottom: -5px; text-align: center; margin-top: 0px; font-weight: bold;">
                                            KLINIK UTAMA KESEHATAN PARU MASYARAKAT KELAS A
                                        </p>

                                        <p style="margin-bottom: -5px; text-align: center; margin-top: 0px;">
                                            Jalan A. Yani Nomor 33 Purwokerto Timur, Banyumas, Jawa Tengah
                                        </p>

                                        <p style="margin-bottom: -5px; text-align: center; margin-top: 0px;">
                                            Kode Pos 53111, Telepon (0281) 635658, Pos-el bkpm_purwokerto@yahoo.com
                                        </p>
                                    </td>
                                    <td width="20%" style="text-align: center; padding-top: 10px; padding-bottom: 10px">
                                        <img src="https://kkpm.banyumaskab.go.id/assets/img/logo.png" style="width: 40%;">
                                    </td>
                                </tr>
                            </tbody></table>`);
    printWindow.document.write(`</body></html>`);

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
                        item.status === "Input Hasil Belum Lengkap" ||
                        item.status === "Belum Input Hasil"
                    );
                });

                var sudahTransakasi = data.filter(function (item) {
                    return item.status === "Input Hasil Lengkap";
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
        item.no = index + 1;
        item.tgl = tgl;
        item.tanggal = moment(item.created_at).format("DD-MM-YYYY");
        item.alamat = item.alamat.replace(/, [^,]*$/, "");
        item.aksi = `<button class="btn btn-danger bg-danger"
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
                        data === "Input Hasil Belum Lengkap"
                            ? "warning"
                            : "danger";
                    return `<div class="badge badge-${backgroundColor}">${data}</div>`;
                },
            },
            { data: "tanggal" },
            { data: "layanan" },
            { data: "norm", className: "col-1" },
            { data: "nama", className: "col-2" },
            { data: "alamat", className: "col-4" },
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
        item.no = index + 1;
        item.tgl = tgl;
        item.tanggal = moment(item.created_at).format("DD-MM-YYYY");
        item.alamat = item.alamat.replace(/, [^,]*$/, "");
        item.aksi = `<button class="btn btn-danger"
                            data-norm="${item.norm}"
                            data-nama="${item.nama}"
                            data-alamat="${item.alamat}"
                            onclick="cariTsLab('${item.norm}', '${item.tgl}','tampil');"><i class="fa-solid fa-file-pen"></i></button>
                            <button class="btn btn-success"
                            data-norm="${item.norm}"
                            data-nama="${item.nama}"
                            data-alamat="${item.alamat}"
                            onclick="cariTsLab('${item.norm}', '${item.tgl}','cetak');"><i class="fa-solid fa-print"></i></button>`;
    });

    $("#antrianSudah").DataTable({
        data: sudahTransakasi,
        columns: [
            { data: "aksi", className: "col-2" },
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
            { data: "norm", className: "col-1" },
            { data: "nama", className: "col-2" },
            { data: "alamat", className: "col-3" },
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
