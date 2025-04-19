const baseUrl = window.location.origin;
function cetak(norm) {
    // console.log("🚀 ~ cetak ~ norm:", norm);
    window.open("http://rsparu.kkpm.local/Cetak/Label3/norm/" + norm);

    // window.open(`${baseUrl}/api/pendaftaran/cetak/label/${norm}`);
    // window.open(`${baseUrl}/api/pendaftaran/cetak/rm/${norm}`);
}

function checkEnter(event) {
    if (event.key === "Enter" || event.keyCode === 13) {
        selesai(); // Call the selesai function when Enter key is pressed
    }
}

function daftarkan(button) {
    var norm = $(button).data("norm");
    var notrans = $(button).data("notrans");
    var no_urut = $(button).data("no_urut");
    var tgltrans = $(button).data("tgltrans");
    tampilkanLoading();
    $.ajax({
        url: "/api/pendaftaran/pasien/" + norm,
        type: "get",
        success: function (response) {
            const pekerjaan = response.pekerjaan || "";
            const ibu = response.ibuKandung || "";
            const pjwb = response.pjwb || "";
            const statusPasien = response.pasien === null ? "Baru" : "Lama";
            const judul = "Lengkapi Pendaftaran Pasien " + statusPasien;
            Swal.fire({
                title: judul,
                html: `
                    <label for="selectPekerjaan" class="swal2-label">Pekerjaan:</label>
                    <input id="pekerjaan" name="pekerjaan" type="text" class="swal2-input mb-2 col-10" placeholder="Nama Pekerjaan" value="${pekerjaan}">
                    <select id="selectPekerjaan" class="swal2-select select2 col-10">
                        <option value="">-- Pilih Pekerjaan --</option>
                        <option value="Belum/Tidak Bekerja">Belum/Tidak Bekerja</option>
                        <option value="Akuntan">Akuntan</option>
                        <option value="Analis">Analis</option>
                        <option value="Apoteker">Apoteker</option>
                        <option value="Arsitek">Arsitek</option>
                        <option value="Buruh">Buruh</option>
                        <option value="Desainer">Desainer</option>
                        <option value="Dokter">Dokter</option>
                        <option value="Dosen">Dosen</option>
                        <option value="Guru">Guru</option>
                        <option value="Ibu Rumah Tangga">Ibu Rumah Tangga</option>
                        <option value="Montir">Montir</option>
                        <option value="Nelayan">Nelayan</option>
                        <option value="Notaris">Notaris</option>
                        <option value="Ojek Online">Ojek Online</option>
                        <option value="PNS">PNS</option>
                        <option value="PPPK">PPPK</option>
                        <option value="Pedagang">Pedagang</option>
                        <option value="Pegawai Swasta">Pegawai Swasta</option>
                        <option value="Pelajar/Mahasiswa">Pelajar/Mahasiswa</option>
                        <option value="Pengacara">Pengacara</option>
                        <option value="Perawat">Perawat</option>
                        <option value="Petani">Petani</option>
                        <option value="Polri">Polri</option>
                        <option value="Programmer">Programmer</option>
                        <option value="Security">Security</option>
                        <option value="Seniman/Artis">Seniman/Artis</option>
                        <option value="Sopir">Sopir</option>
                        <option value="TNI">TNI</option>
                        <option value="Teknisi">Teknisi</option>
                        <option value="Wiraswasta">Wiraswasta</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                                        
                    <label for="ibu" class="swal2-label mt-4">Nama Ibu:</label>
                    <input id="ibu" type="text" class="swal2-input mt-0 col-10" placeholder="Nama Ibu Kandung" value="${ibu}">
                    <button class="btn btn-success mt-3" id="btnSama">Klik Jika Sama Dengan Penanggung Jawab: ${pjwb}</button>
                `,
                didOpen: () => {
                    // Inisialisasi select2
                    $("#selectPekerjaan").select2({
                        dropdownParent: $(".swal2-popup"),
                        width: "100%",
                        placeholder: "-- Pilih Pekerjaan --",
                    });

                    // Atur selected value jika ada
                    if (pekerjaan) {
                        $("#selectPekerjaan").val(pekerjaan).trigger("change");
                    }

                    // Ketika user memilih pekerjaan, isi ke input text
                    $("#selectPekerjaan").on("change", function () {
                        $("#pekerjaan").val($(this).val());
                    });

                    $("#btnSama").on("click", function () {
                        $("#ibu").val(pjwb);
                    });
                },
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Daftarkan",
                cancelButtonText: "Batal",
                preConfirm: () => {
                    const pekerjaan = $("#pekerjaan").val();
                    const ibu = $("#ibu").val();
                    return { pekerjaan, ibu };
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    const pekerjaan = result.value.pekerjaan;
                    const ibu = result.value.ibu;
                    tampilkanLoading("Sedang mendaftarkan pasien...");
                    $.ajax({
                        url: "/api/pendaftaran/pasien/daftar",
                        type: "post",
                        data: {
                            norm: norm,
                            notrans: notrans,
                            no_urut: no_urut,
                            tgltrans: tgltrans,
                            pekerjaan: pekerjaan,
                            ibu: ibu,
                            statusPasien: statusPasien,
                        },
                        success: function (response) {
                            Toast.fire({
                                icon: "success",
                                title: response.message,
                            });
                            reportPendaftaran(tglAwal, tglAkhir);
                            if (statusPasien === "Baru") {
                                window.open(
                                    `${baseUrl}/api/pendaftaran/cetak/rm/${norm}`
                                );
                            }
                        },
                        error: function (error) {
                            console.error(error);
                            Swal.fire(
                                "Gagal",
                                "Terjadi kesalahan saat menyimpan data" +
                                    error.responseJSON.message,
                                "error"
                            );
                        },
                    });
                }
            });
        },
        error: function () {
            Swal.fire("Error", "Gagal mengambil data pasien", "error");
        },
    });
}

// function daftarkan(button) {
//     var norm = $(button).data("norm");
//     var notrans = $(button).data("notrans");
//     var no_urut = $(button).data("no_urut");
//     var tgltrans = $(button).data("tgltrans");
//     // alert(tgltrans);
//     // return;
//     tampilkanLoading("Sedang mendaftarakan ke aplikasi RSPARU...!!!");
//     try {
//         $.ajax({
//             url: "/api/pendaftaran/pasien/daftar",
//             type: "post",
//             data: {
//                 norm: norm,
//                 notrans: notrans,
//                 no_urut: no_urut,
//                 tgltrans: tgltrans,
//             },
//             success: function (response) {
//                 Toast.fire({
//                     icon: "success",
//                     title: response.message,
//                 });
//                 reportPendaftaran(tglAwal, tglAkhir);
//             },
//         });
//     } catch (error) {
//         console.error(error);
//     }
// }

function selesai(norm, notrans) {
    var norm = norm ? norm : $("#norm").val();
    var notrans = notrans ? notrans : $("#notrans").val();
    var nosep = $("#noSep").val();
    if (!norm) {
        Toast.fire({
            icon: "error",
            title: "Belum Ada Data Transaksi...!!! ",
        });
    } else {
        $.ajax({
            url: "/api/pendaftaran/selesai",
            type: "post",
            data: {
                norm: norm,
                notrans: notrans,
                nosep: nosep,
            },
            success: function (response) {
                Swal.fire({
                    icon: "info",
                    title: response.message + "Sedang memperbarui data...!!!",
                    showConfirmButton: true,
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    },
                });
                reportPendaftaran(tglAwal, tglAkhir);
                document.getElementById("formSep").reset();
                $("#modalSep").modal("hide");
            },
        });
    }
}

function isiForm(norm, nama, jaminan, notrans, nosep, btn) {
    $("#norm").val(norm);
    $("#nama").val(nama);
    $("#jaminan").val(jaminan);
    $("#notrans").val(notrans);
    $("#noSep").val(nosep);
    btn.classList.remove("btn-danger");
    btn.classList.add("btn-success");
}

function segarkan() {
    Swal.fire({
        icon: "info",
        title: "Sedang mencarikan data...!!!\n Proses lama jika mencari lebih dari 10 hari",
        showConfirmButton: true,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });
    reportPendaftaran(tglAwal, tglAkhir);
    rekapFaskesPerujuk();
}
function cariJumlah() {
    Swal.fire({
        icon: "info",
        title: "Sedang mencarikan data...!!!\n Proses lama jika mencari lebih dari 10 hari",
        showConfirmButton: true,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });
    reportJumlah(tglAwal, tglAkhir);
}

function reportPendaftaran(tglAwal, tglAkhir) {
    var tglA = formatDate(new Date(tglAwal));
    var tglB = formatDate(new Date(tglAkhir));

    if ($.fn.DataTable.isDataTable("#report, #total")) {
        var tabletindakan = $("#report, #total").DataTable();
        tabletindakan.destroy();
    }

    $.ajax({
        url: "/api/kominfo/pendaftaran/report",
        type: "post",
        data: {
            tanggal_awal: tglAwal,
            tanggal_akhir: tglAkhir,
            no_rm: "",
        },
        success: function (response) {
            var pendaftaran = response["data"];
            var total = response["total"];
            // console.log("🚀 ~ reportPendaftaran ~ total:", total);
            // console.log("🚀 ~ reportPendaftaran ~ $data:", pendaftaran);

            pendaftaran.forEach(function (item, index) {
                var nama_pasien = item.pasien_nama.replace(/'/g, "\\'");
                let resume;
                if (item.penjamin_nama == "BPJS") {
                    resume = "";
                } else {
                    resume = "hidden";
                }
                item.aksi = `

                            <button type="button" class="btn btn-sm btn-primary mr-2 mb-2"
                                    onclick="cetak('${item.pasien_no_rm}')" placeholder="Cetak">Label</button>
                            <button type="button" class="btn btn-sm btn-${item.check_in} mr-2 mb-2" id="checkin" placeholder="Selesai" data-toggle="modal"
                                    data-target="#modalSep" onclick="isiForm('${item.pasien_no_rm}', '${nama_pasien}','${item.penjamin_nama}','${item.no_reg}','${item.no_sep}',this)">
                                    <i class="fa-regular fa-square-check"></i></button>
                            <a type="button" class="btn btn-sm btn-warning mr-2 mb-2" placeholder="Resume"
                                    href="/api/resume/${item.pasien_no_rm}/${item.tanggal}" target="_blank">Resume</a>
                            <a type="button" class="btn btn-sm btn-${item.statusDaftar} mr-2 mb-2" placeholder="Resume"
                            data-norm="${item.pasien_no_rm}"
                            data-notrans="${item.no_reg}"
                            data-no_urut="${item.antrean_nomor}"
                            data-tgltrans="${item.waktu_verifikasi}"
                                    onclick="daftarkan(this)">Daftarkan</a>
                            `;
                if (item.check_in == "danger") {
                    item.status = "Belum";
                } else {
                    item.status = "Selesai";
                }
            });

            $("#report")
                .DataTable({
                    data: pendaftaran,
                    columns: [
                        { data: "aksi", className: "col-3" },
                        { data: "antrean_nomor" },
                        { data: "tanggal" },
                        { data: "no_sep" },
                        { data: "penjamin_nama" },
                        { data: "daftar_by" },
                        { data: "pasien_lama_baru" },
                        { data: "pasien_no_rm" },
                        { data: "pasien_nama", className: "col-2" },
                        { data: "jenis_kelamin_nama" },
                        { data: "pasien_umur" },
                        { data: "pasien_alamat", className: "col-2" },
                        { data: "poli_nama" },
                        { data: "dokter_nama", className: "col-2" },
                        {
                            data: "status",
                            render: function (data) {
                                const statusClasses = {
                                    Belum: "danger",
                                    Selesai: "success",
                                    default: "secondary",
                                };
                                return `<div class="badge badge-${
                                    statusClasses[data] || statusClasses.default
                                }">${data}</div>`;
                            },
                        },
                    ],
                    autoWidth: false,
                    lengthChange: false,
                    order: [
                        [14, "asc"],
                        [1, "asc"],
                    ],
                    buttons: [
                        {
                            extend: "excelHtml5",
                            text: "Excel",
                            title:
                                "Laporan Pendaftaran Tanggal: " +
                                tglA +
                                " s.d. " +
                                tglB,
                            filename:
                                "Laporan Pendaftaran Tanggal: " +
                                tglA +
                                "  s.d. " +
                                tglB,
                        },
                        {
                            extend: "colvis",
                            text: "Tampilkan Kolom",
                        },
                        // "colvis", // Tombol untuk menampilkan/menyembunyikan kolom
                    ],
                })
                .buttons()
                .container()
                .appendTo("#report_wrapper .col-md-6:eq(0)");
            $("#total")
                .DataTable({
                    data: [total],
                    columns: [
                        { data: "jumlah_no_antrian", className: "text-center" },
                        { data: "jumlah_pasien", className: "text-center" },
                        {
                            data: "jumlah_pasien_batal",
                            className: "text-center",
                        },
                        { data: "jumlah_nomor_skip", className: "text-center" },
                        { data: "jumlah_BPJS", className: "text-center" },
                        { data: "jumlah_BPJS_2", className: "text-center" },
                        { data: "jumlah_UMUM", className: "text-center" },
                        {
                            data: "jumlah_pasien_LAMA",
                            className: "text-center",
                        },
                        {
                            data: "jumlah_pasien_BARU",
                            className: "text-center",
                        },
                        { data: "jumlah_daftar_OTS", className: "text-center" },
                        { data: "jumlah_daftar_JKN", className: "text-center" },
                    ],
                    autoWidth: false,
                    ordering: false,
                    paging: true,
                    searching: false,
                    lengthChange: false,
                    buttons: [
                        {
                            extend: "excelHtml5",
                            text: "Excel",
                            title:
                                "Laporan Pendaftaran Tanggal: " +
                                tglA +
                                " s.d. " +
                                tglB,
                            filename:
                                "Laporan Pendaftaran Tanggal: " +
                                tglA +
                                "  s.d. " +
                                tglB,
                        },
                        {
                            extend: "colvis",
                            text: "Tampilkan Kolom",
                        },
                        // "colvis", // Tombol untuk menampilkan/menyembunyikan kolom
                    ],
                })
                .buttons()
                .container()
                .appendTo("#total_wrapper .col-md-6:eq(0)");
            Swal.close();
        },

        error: function (xhr, status, error) {
            console.error("Error:", error);
            Swal.fire({
                icon: "error",
                title:
                    "Terjadi kesalahan saat mengambil data pasien...!!!\n" +
                    error,
            });
        },
    });
}

async function rekapFaskesPerujuk() {
    var tglA = formatDate(new Date(tglAwal));
    // console.log("🚀 ~ rekapFaskesPerujuk ~ tglA:", tglA);
    var tglB = formatDate(new Date(tglAkhir));
    // console.log("🚀 ~ rekapFaskesPerujuk ~ tglB:", tglB);

    // Hapus DataTables jika sudah ada
    if ($.fn.DataTable.isDataTable("#rekapFaskesPerujuk")) {
        $("#rekapFaskesPerujuk").DataTable().clear().destroy();
    }

    try {
        // Fetch data dari API
        const response = await fetch(
            "/api/kominfo/pendaftaran/faskes_perujuk",
            {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    tanggal_awal: tglAwal,
                    tanggal_akhir: tglAkhir,
                }),
            }
        );

        // Cek jika response tidak OK
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        // Konversi response ke JSON
        const result = await response.json();
        // console.log("🚀 ~ response data:", result);

        // Pastikan `result.data` adalah array
        let data = result.data || result;
        if (!Array.isArray(data)) {
            console.error("Data bukan array:", data);
            return;
        }

        // Tambahkan nomor urut ke setiap item
        data.forEach((item, index) => {
            item.no = index + 1;
        });

        // Debug data sebelum masuk ke DataTable
        // console.log("🚀 ~ Data yang dikirim ke DataTable:", data);

        // Jika data kosong, beri peringatan
        if (data.length === 0) {
            console.warn("⚠ Data kosong, tidak ada yang ditampilkan di tabel.");
            return;
        }

        // Inisialisasi DataTable
        $("#rekapFaskesPerujuk")
            .DataTable({
                data: data,
                columns: [
                    { data: "no" },
                    { data: "ppk_rujukan_nama" },
                    { data: "jumlah_rujukan" },
                ],
                autoWidth: false,
                lengthChange: false,
                paging: true,
                order: [[2, "dsc"]],
                buttons: [
                    {
                        extend: "excelHtml5",
                        text: "Excel",
                        title: `Laporan Rekap Faskes Perujuk Tanggal: ${tglA} s.d. ${tglB}`,
                        filename: `Laporan_Rekap_Faskes_Perujuk_${tglA}_sd_${tglB}`,
                    },
                    {
                        extend: "colvis",
                        text: "Tampilkan Kolom",
                    },
                ],
            })
            .buttons()
            .container()
            .appendTo("#rekapFaskesPerujuk_wrapper .col-md-6:eq(0)");

        Swal.close();
    } catch (error) {
        console.error("🚨 Error:", error);
        Swal.fire({
            icon: "error",
            title: `Terjadi kesalahan saat mengambil data...!!!\n${error.message}`,
        });
    }
}
function rencanaKontrolPasien() {
    var tglA = formatDate(new Date(tglAwal));
    var tglB = formatDate(new Date(tglAkhir));

    // console.log("🚀 ~ rencanaKontrolPasien ~ tglA:", tglA);
    // console.log("🚀 ~ rencanaKontrolPasien ~ tglB:", tglB);

    $.ajax({
        url: "/api/kominfo/data_rencana_kontrol",
        type: "POST",
        dataType: "json",
        contentType: "application/json",
        data: JSON.stringify({
            tanggal_awal: tglAwal,
            tanggal_akhir: tglAkhir,
        }),
        beforeSend: function () {
            Swal.fire({
                title: "Mengambil data...",
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            });
        },
        success: function (result) {
            // console.log("🚀 ~ response data:", result);

            // Pastikan data tidak kosong
            if (!result.html || result.html.trim() === "") {
                Swal.fire({
                    icon: "warning",
                    title: "Data kosong atau tidak valid!",
                });
                return;
            }

            // Masukkan data ke dalam div
            $("#divRencanaKontrolTable").html(result.html);

            // Pastikan elemen tabel ada sebelum inisialisasi DataTables
            if ($("#rencanaKontrolTable").length) {
                // Hapus DataTables lama jika sudah ada
                if ($.fn.DataTable.isDataTable("#rencanaKontrolTable")) {
                    $("#rencanaKontrolTable").DataTable().destroy();
                }

                // Inisialisasi ulang DataTables
                var table = $("#rencanaKontrolTable").DataTable({
                    responsive: true,
                    lengthChange: false,
                    autoWidth: true,
                    searching: true,
                    paging: true,
                    order: [[1, "asc"]],
                    info: true,
                    language: {
                        search: "Cari:",
                        lengthMenu: "Tampilkan _MENU_ data",
                        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                        infoEmpty: "Tidak ada data tersedia",
                        zeroRecords: "Tidak ada data yang cocok",
                        paginate: {
                            first: "Awal",
                            last: "Akhir",
                            next: "→",
                            previous: "←",
                        },
                    },
                    buttons: [
                        {
                            extend: "copyHtml5",
                            text: "Salin",
                        },
                        {
                            extend: "excel",
                            text: "Download",
                            title: `Data Pasien Baru & Kunjungan Ulang ${tglAwal} s.d. ${tglAkhir}`,
                            filename: `Data_Analisis_Biaya_Pasien_${tglAwal}_${tglAkhir}`,
                            exportOptions: { columns: ":visible" },
                        },
                    ],
                });

                // Tambahkan tombol ekspor ke dalam wrapper DataTables
                table
                    .buttons()
                    .container()
                    .appendTo("#rencanaKontrolTable_wrapper .col-md-6:eq(0)");
            }

            Swal.close();
        },
        error: function (xhr, status, error) {
            console.error("🚨 Error:", error);
            Swal.fire({
                icon: "error",
                title: `Terjadi kesalahan saat mengambil data...!!!\n${xhr.status} - ${xhr.statusText}`,
            });
        },
    });
}

function formatDate(date) {
    // Convert the input to a Date object if it isn't already
    if (!(date instanceof Date)) {
        date = new Date(date);
    }

    // Check if the date is valid
    if (isNaN(date)) {
        throw new Error("Invalid date");
    }

    let day = String(date.getDate()).padStart(2, "0");
    let month = String(date.getMonth() + 1).padStart(2, "0"); // getMonth() returns month from 0-11
    let year = date.getFullYear();
    return `${day}-${month}-${year}`;
}

var tglAwal;
var tglAkhir;

window.addEventListener("load", function () {
    setTodayDate();
    var today = new Date().toISOString().split("T")[0];
    $("#tanggal").val(today);

    // Inisialisasi tglAwal dan tglAkhir sebagai objek Moment.js
    // tglAwal = moment().subtract(30, "days").format("YYYY-MM-DD");
    tglAwal = moment().subtract(0, "days").format("YYYY-MM-DD");
    tglAkhir = moment().subtract(0, "days").format("YYYY-MM-DD");

    // Menetapkan nilai ke input tanggal
    $("#reservation, #tglJumlah").val(tglAwal + " to " + tglAkhir);

    // Date range picker
    $("#reservation, #tglJumlah").daterangepicker({
        startDate: tglAwal,
        endDate: tglAkhir,
        autoApply: true,
        locale: {
            format: "YYYY-MM-DD",
            separator: " to ",
            applyLabel: "Apply",
            cancelLabel: "Cancel",
            customRangeLabel: "Custom Range",
        },
    });

    $("#reservation, #tglJumlah").on(
        "apply.daterangepicker",
        function (ev, picker) {
            tglAwal = picker.startDate.format("YYYY-MM-DD");
            tglAkhir = picker.endDate.format("YYYY-MM-DD");
        }
    );
    segarkan();

    // setInterval(function () {
    //     reportPendaftaran(tglAwal, tglAkhir);
    // }, 60000);
    $("#modalSep").on("shown.bs.modal", function () {
        $("#noSep").focus();
    });
});
