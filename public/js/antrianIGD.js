async function cariTsIgd(notrans, norm, tgl, ruang) {
    console.log("🚀 ~ cariTsIgd ~ cariTsIgd:", cariTsIgd);
    norm = norm || formatNorm($("#norm").val()); // Tambahkan kurung untuk memanggil val()
    tgl = tgl || $("#tanggal").val();
    notrans = notrans || $("#notrans").val();
    var requestData = { notrans: notrans, norm: norm, tgl: tgl };

    Swal.fire({
        icon: "info",
        title: "Sedang mencarikan data pasien...!!!",
        showConfirmButton: false,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });

    if ($.fn.DataTable.isDataTable("#dataTindakan")) {
        var tabletindakan = $("#dataTindakan").DataTable();
        tabletindakan.clear().destroy(); // Kosongkan tabel sebelum menghancurkannya
    }

    try {
        const response = await fetch("/api/cariDataTindakan", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(requestData),
        });

        if (!response.ok) {
            Swal.close();
            if (response.status == 404) {
                $("#dataTindakan").DataTable({
                    data: [], // Data kosong
                    columns: [
                        { title: "Aksi" },
                        { title: "Status" },
                        { title: "No RM" },
                        { title: "Tindakan" },
                        { title: "Petugas" },
                        { title: "dokter" },
                    ],
                    language: {
                        emptyTable: "Belum Ada Transaksi",
                    },
                    ordering: false,
                    initComplete: function () {
                        // Menambahkan CSS kustom untuk memberi background kuning
                        $("#dataTrans_wrapper .dataTables_empty").css({
                            "background-color": "yellow",
                            color: "black", // Mengubah warna teks agar tetap terlihat
                            "font-weight": "bold",
                            "text-align": "center",
                            padding: "20px",
                        });
                    },
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Terjadi kesalahan saat mengambil data pasien...!!!",
                });
                throw new Error(
                    `Network response was not ok. Status: ${response.status}`
                );
            }
        } else {
            const data = await response.json();
            console.log("🚀 ~ cariDataTindakan ~ data:", data);

            data.forEach(function (item, index) {
                var dokter = `${item.dokter.gelar_d} ${item.dokter.biodata.nama} ${item.dokter.gelar_b}`;
                var petugas = `${item.petugas.gelar_d} ${item.petugas.biodata.nama} ${item.petugas.gelar_b}`;
                var tindakan = item.tindakan.nmTindakan;
                item.actions = `<a type="button" class="edit btn-sm btn-primary icon-link icon-link-hover"
                                    data-id="${item.id}"
                                    data-kdtind="${item.kdTind}"
                                    data-tindakan="${tindakan}"
                                    data-norm="${item.norm}"
                                    data-petugas="${petugas}"
                                    data-dokter="${dokter}"><i class="fas fa-pen-to-square"></i></a>
                                <a type="button" class="delete btn-sm btn-danger icon-link icon-link-hover"
                                    data-id="${item.id}"
                                    data-kdTind="${item.kdTind}"
                                    data-tindakan="${tindakan}"
                                    data-norm="${item.norm}"
                                    data-petugas="${petugas}"
                                    data-dokter="${dokter}"><i class="fas fa-trash"></i></a>`;
                item.no = index + 1;
                item.status = item.transbmhp.length > 0 ? "sudah" : "belum";
            });

            $("#dataTindakan").DataTable({
                data: data,
                columns: [
                    { data: "actions", className: "text-center col-1 p-2" },
                    {
                        data: "status",
                        name: "kdTind",
                        render: function (data) {
                            var backgroundColor =
                                data === "belum" ? "danger" : "success";
                            return `<div class="badge badge-${backgroundColor}">${data}</div>`;
                        },
                        className: "p-2",
                    },
                    { data: "norm", className: "p-2" },
                    { data: "tindakan.nmTindakan", className: "p-2" },
                    { data: "petugas.biodata.nama", className: "p-2" },
                    { data: "dokter.biodata.nama", className: "p-2" },
                ],
                order: [2, "asc"],
            });

            scrollToInputSection();
            Swal.close();
        }
    } catch (error) {
        console.error("Terjadi kesalahan saat mencari data:", error);
        Swal.fire({
            icon: "error",
            title: `Terjadi kesalahan saat mencari data...!!!\n${error.message}`,
        });
    }
}

function dataTindakan(notrans, norm) {
    // console.log("🚀 ~ dataTindakan ~ notrans:", notrans);
    var notrans = notrans ? notrans : $("#notrans").val();
    // console.log("🚀 ~ dataTindakan ~ notrans:", notrans);

    if ($.fn.DataTable.isDataTable("#dataTindakan")) {
        var tabletindakan = $("#dataTindakan").DataTable();
        tabletindakan.destroy();
    }

    $.ajax({
        url: "/api/cariDataTindakan",
        type: "post",
        data: { notrans: notrans },
        success: function (response) {
            response.forEach(function (item, index) {
                var dokter = `${item.dokter.gelar_d} ${item.dokter.biodata.nama} ${item.dokter.gelar_b}`;
                var petugas = `${item.petugas.gelar_d} ${item.petugas.biodata.nama} ${item.petugas.gelar_b}`;
                var tindakan = `${item.tindakan.nmTindakan}`;
                item.actions = `<a type="button" class="mr-2 edit btn-sm btn-primary py-md-0 py-1 icon-link icon-link-hover"
                                    data-id="${item.id}"
                                    data-kdtind="${item.kdTind}"
                                    data-tindakan="${tindakan}"
                                    data-norm="${item.norm}"
                                    data-petugas="${petugas}"
                                    data-dokter="${dokter}"><i class="fas fa-pen-to-square"></i></a>
                                <a type="button" class="delete btn-sm btn-danger py-md-0 py-1 icon-link icon-link-hover"
                                    data-id="${item.id}"
                                    data-kdTind="${item.kdTind}"
                                    data-tindakan="${tindakan}"
                                    data-norm="${item.norm}"
                                    data-petugas="${petugas}"
                                    data-dokter="${dokter}"><i class="fas fa-trash"></i></a>`;
                item.no = index + 1;
                if (item.transbmhp.length > 0) {
                    item.status = "sudah";
                } else {
                    item.status = "belum";
                }
            });

            $("#dataTindakan").DataTable({
                data: response,
                columns: [
                    { data: "actions", className: "text-center col-1 p-2" },
                    {
                        data: "status",
                        name: "kdTind",
                        render: function (data, type, row) {
                            var backgroundColor =
                                data === "belum" ? "danger" : "success";
                            return `<div class="badge badge-${backgroundColor}">${data}</div>`;
                        },
                        className: "p-2",
                    },
                    { data: "norm", className: "p-2" },
                    { data: "tindakan.nmTindakan", className: "p-2" },
                    { data: "petugas.biodata.nama", className: "p-2" },
                    { data: "dokter.biodata.nama", className: "p-2" },
                ],
                order: [2, "asc"],
            });
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        },
    });
}
function dataBMHP() {
    var idTind = $("#modalidTind").val();
    if ($.fn.DataTable.isDataTable("#transaksiBMHP")) {
        var tabletindakan = $("#transaksiBMHP").DataTable();
        tabletindakan.clear().destroy();
    }

    $.ajax({
        url: "/api/cariTransaksiBmhp",
        type: "post",
        data: { idTind: idTind },
        success: function (response) {
            response.forEach(function (item, index) {
                item.actions = `<a href="" class="delete btn-sm btn-danger icon-link icon-link-hover"
                                    data-id="${item.id}"
                                    data-idTind="${item.idTind}"
                                    data-kdtind="${item.kdTind}"
                                    data-bmhp="${item.bmhp.nmObat}"
                                    data-kdBmhp="${item.kdBmhp}"
                                    data-jumlah="${item.jumlah}">
                                    <i class="fas fa-trash"></i></a>`;
                item.no = index + 1; // Nomor urut dimulai dari 1, bukan 0
            });

            $("#transaksiBMHP").DataTable({
                data: response,
                columns: [
                    { data: "actions", className: "text-center" },
                    { data: "no" },
                    { data: "bmhp.nmObat" },
                    { data: "jml" },
                    { data: "biaya" },
                ],
                order: [2, "asc"],
                paging: true,
                pageLength: 5,
            });
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        },
    });
}

function checkOut(norm, notrans, btn) {
    if (!norm) {
        Toast.fire({
            icon: "error",
            title: "Belum Ada Data Transaksi...!!! ",
        });
    } else {
        $.ajax({
            url: "/api/igd/selesai",
            type: "post",
            data: {
                norm: norm,
                notrans: notrans,
            },
            success: function (response) {
                Toast.fire({
                    icon: "success",
                    title: response.message,
                });
                btn.classList.remove("btn-danger");
                btn.classList.add("btn-success");
            },
        });
    }
}
function searchByRM(norm) {
    $.ajax({
        url: "/api/cariRM",
        type: "post",
        data: {
            norm: norm,
        },
        success: function (response) {
            // Mendapatkan data dari respons JSON
            var noRM = response[0].norm; // Menggunakan indeks 0 karena respons adalah array
            var nama = response[0].biodata.nama;
            var notrans = response[0].notrans;
            var layanan = response[0].kelompok.kelompok;
            var dokter = response[0].petugas.p_dokter_poli;
            var tgltrans = response[0].tgltrans;
            // var dokter = `${response[0].petugas.pegawai.gelar_d} ${response[0].petugas.pegawai.nama} ${response[0].petugas.pegawai.gelar_b}`;
            var alamat = `${response[0].biodata.kelurahan}, ${response[0].biodata.rtrw}, ${response[0].biodata.kecamatan}, ${response[0].biodata.kabupaten}`;
            var asktind = "";

            if (response[0].poli) {
                if (response[0].poli.nebulizer)
                    asktind += "Nebu: " + response[0].poli.nebulizer + "\n";
                if (response[0].poli.oksigenasi)
                    asktind += "O2: " + response[0].poli.oksigenasi + "\n";
                if (response[0].poli.injeksi)
                    asktind += "Injeksi: " + response[0].poli.injeksi + "\n";
                if (response[0].poli.infus)
                    asktind += "Infus: " + response[0].poli.infus + "\n";
                if (response[0].poli.mantoux) asktind += "Mantoux" + ", ";
                if (response[0].poli.ekg) asktind += "EKG" + ", ";
                if (response[0].poli.spirometri) asktind += "Spirometri";
            }

            // The rest of your code goes here, if any.

            // Dapatkan data lainnya dari respons JSON sesuai kebutuhan

            // Mengisikan data ke dalam elemen-elemen HTML
            $("#asktind").val(asktind);
            $("#norm").val(noRM);
            $("#nama").val(nama);
            $("#alamat").val(alamat);
            $("#notrans").val(notrans);
            $("#tgltrans").val(tgltrans);
            $("#layanan").val(layanan);
            $("#dokter").val(dokter);
            $("#dokter").trigger("change");

            dataTindakan();
            // Mengisi elemen-elemen lainnya sesuai kebutuhan
        },
        error: function (xhr) {
            // Handle error
        },
    });
}

function antrianSpiro(tgl) {
    $.ajax({
        url: "/api/spiro/antrian",
        type: "get",
        data: { tgl: tgl },
        dataType: "json",
        success: function (response) {
            $("#divAntrianSpiro").html(response);
            $("#tabelAntrianSpiro").DataTable();
        },
        error: function (xhr) {
            // Handle error
        },
    });
}
