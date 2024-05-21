function showUkuran() {
    $("#periksa").hide();
    $("#kondisi").hide();
    $("#proyeksi").hide();
    $("#ukuran").show();
}

function showPeriksa() {
    $("#periksa").show();
    $("#kondisi").hide();
    $("#proyeksi").hide();
    $("#ukuran").hide();
}
function showKondisi() {
    $("#periksa").hide();
    $("#kondisi").show();
    $("#proyeksi").hide();
    $("#ukuran").hide();
}
function showProyeksi() {
    $("#periksa").hide();
    $("#kondisi").hide();
    $("#proyeksi").show();
    $("#ukuran").hide();
}

//fungsi simpan data master
function simpanFoto() {
    var kdFotoValue = document.getElementById("kdFoto").value;
    var nmFotoValue = document.getElementById("nmFoto").value;
    var tarifValue = document.getElementById("tarif").value;
    var url = kdFotoValue ? "/api/editfotoRo" : "/api/simpanFotoRo";

    var requestData = {
        kdFoto: kdFotoValue,
        nmFoto: nmFotoValue,
        tarif: tarifValue,
    };

    var method = kdFotoValue ? "PUT" : "POST"; // PUT for update, POST for create

    fetch(url, {
        method: method,
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(requestData),
    })
        .then((response) => {
            if (response.ok) {
                if (method === "PUT") {
                    console.log("Data berhasil diupdate.");
                } else if (method === "POST") {
                    console.log("Data berhasil disimpan.");
                }
                $("#editFotoModal").modal("hide");
                layananRo();
            } else {
                console.error("Gagal menyimpan data.");
            }
        })
        .catch((error) => {
            console.error("Terjadi kesalahan:", error);
        });
}
function simpanFilm() {
    var kdFilmValue = document.getElementById("kdFilm").value;
    var ukuranFilmValue = document.getElementById("ukuranFilm").value;
    var url = kdFilmValue ? "/api/editfilmRo" : "/api/simpanFilmRo";

    console.log("🚀 ~ simpanFilm ~ kdFilmValue:", kdFilmValue);
    console.log("🚀 ~ simpanFilm ~ ukuranFilmValue:", ukuranFilmValue);
    console.log("🚀 ~ simpanFilm ~ url:", url);

    var requestData = {
        kdFilm: kdFilmValue,
        ukuranFilm: ukuranFilmValue,
    };
    console.log("🚀 ~ simpanFilm ~ requestData:", requestData);

    var method = kdFilmValue ? "PUT" : "POST"; // PUT for update, POST for create
    console.log("🚀 ~ simpanFilm ~ method:", method);

    fetch(url, {
        method: method,
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(requestData),
    })
        .then((response) => {
            if (response.ok) {
                if (method === "PUT") {
                    console.log("Data berhasil diupdate.");
                } else if (method === "POST") {
                    console.log("Data berhasil disimpan.");
                }
                $("#editUkuranModal").modal("hide");
                ukuranRo();
            } else {
                console.error("Gagal menyimpan data.");
            }
        })
        .catch((error) => {
            console.error("Terjadi kesalahan:", error);
        });
}
function simpanProyeksi() {
    var kdProyeksiValue = document.getElementById("kdProyeksi").value;
    var nmProyeksiValue = document.getElementById("nmProyeksi").value;
    var url = kdProyeksiValue ? "/api/editProyeksiRo" : "/api/simpanProyeksiRo";

    console.log("🚀 ~ simpanProyeksi ~ kdProyeksiValue:", kdProyeksiValue);
    console.log("🚀 ~ simpanProyeksi ~ nmProyeksiValue:", nmProyeksiValue);
    console.log("🚀 ~ simpanProyeksi ~ url:", url);

    var requestData = {
        kdProyeksi: kdProyeksiValue,
        nmProyeksi: nmProyeksiValue,
    };
    console.log("🚀 ~ simpanProyeksi ~ requestData:", requestData);

    var method = kdProyeksiValue ? "PUT" : "POST"; // PUT for update, POST for create
    console.log("🚀 ~ simpanProyeksi ~ method:", method);

    fetch(url, {
        method: method,
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(requestData),
    })
        .then((response) => {
            if (response.ok) {
                if (method === "PUT") {
                    console.log("Data berhasil diupdate.");
                } else if (method === "POST") {
                    console.log("Data berhasil disimpan.");
                }
                $("#editProyeksiModal").modal("hide");
                proyeksiRo();
            } else {
                console.error("Gagal menyimpan data.");
            }
        })
        .catch((error) => {
            console.error("Terjadi kesalahan:", error);
        });
}
function simpanKondisi() {
    var kdKondisiValue = document.getElementById("kdKondisi").value;
    var nmKondisiValue = document.getElementById("nmKondisi").value;
    var grupValue = document.getElementById("grup").value;
    var statusValue = document.getElementById("status").value;
    var url = kdKondisiValue ? "/api/editKondisiRo" : "/api/simpanKondisiRo";

    console.log("🚀 ~ simpanKondisi ~ kdKondisiValue:", kdKondisiValue);
    console.log("🚀 ~ simpanKondisi ~ nmKondisiValue:", nmKondisiValue);
    console.log("🚀 ~ simpanKondisi ~ url:", url);

    var requestData = {
        grup: grupValue,
        status: statusValue,
        kdKondisi: kdKondisiValue,
        nmKondisi: nmKondisiValue,
    };
    console.log("🚀 ~ simpanKondisi ~ requestData:", requestData);

    var method = kdKondisiValue ? "PUT" : "POST"; // PUT for update, POST for create
    console.log("🚀 ~ simpanKondisi ~ method:", method);

    fetch(url, {
        method: method,
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(requestData),
    })
        .then((response) => {
            if (response.ok) {
                if (method === "PUT") {
                    console.log("Data berhasil diupdate.");
                } else if (method === "POST") {
                    console.log("Data berhasil disimpan.");
                }
                $("#editKondisiModal").modal("hide");
                proyeksiRo();
            } else {
                console.error("Gagal menyimpan data.");
            }
        })
        .catch((error) => {
            console.error("Terjadi kesalahan:", error);
        });
}

//fungsi edit data master
function editFoto(id, nmFoto, tarif) {
    $("#editFotoModal").modal("show");
    document.getElementById("kdFoto").value = id;
    document.getElementById("nmFoto").value = nmFoto;
    document.getElementById("tarif").value = tarif;
}
function editProyeksi(id, proyeksi) {
    $("#editProyeksiModal").modal("show");
    document.getElementById("kdProyeksi").value = id;
    document.getElementById("nmProyeksi").value = proyeksi;
}
function editUkuran(id, film) {
    $("#editUkuranModal").modal("show");
    document.getElementById("kdFilm").value = id;
    document.getElementById("ukuranFilm").value = film;
}
function editKondisi(id, nmKondisi, grup, status) {
    $("#editKondisiModal").modal("show");
    document.getElementById("kdKondisi").value = id;
    document.getElementById("nmKondisi").value = nmKondisi;
    document.getElementById("grup").value = grup;
    document.getElementById("status").value = status;
    document.getElementById("grup").dispatchEvent(new Event("change"));
    document.getElementById("status").dispatchEvent(new Event("change"));
}

//fungsi delete
function deleteFoto(id, nmFoto) {
    console.log("🚀 ~ editFoto ~ id:", id);
    console.log("🚀 ~ editFoto ~ nmFoto:", nmFoto);
    Swal.fire({
        title: "Konfirmasi",
        text: "Apakah Anda yakin ingin menghapus " + nmFoto + " ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, Hapus!",
    }).then((result) => {
        // if (result.isConfirmed) {
        //     $.ajax({
        //         url: "/api/deleteFoto",
        //         type: "POST",
        //         data: { kdFoto: id },
        //         success: function (response) {
        Toast.fire({
            icon: "success",
            title:
                "Data item Pemeriksaan " + nmFoto + " berhasil dihapus...!!!",
        });

        //             dataFarmasi();
        //         },
        //         error: function (xhr, status, error) {
        // Toast.fire({
        //     icon: "success",
        //     title: error + "...!!!",
        // });
        //         },
        //     });
        // } else {
        //     // Logika jika pembatalan (cancel)
        //     console.log("Penghapusan dibatalkan.");
        // }
    });
}

//tampil data master dari database
function layananRo() {
    if ($.fn.DataTable.isDataTable("#dataPeriksa")) {
        $("#dataPeriksa").DataTable().clear().destroy();
    }

    $.ajax({
        url: "/api/fotoRo",
        type: "GET",
        data: {},
        success: function (response) {
            response.forEach(function (item, index) {
                item.no = index + 1; // Nomor urut dimulai dari 1, bukan 0
                item.actions = `<a href="#inputSection" class="edit"
                                    data-nmFoto="${item.nmFoto}"
                                    data-id="${item.kdFoto}" onclick="editFoto(${item.kdFoto}, '${item.nmFoto}', '${item.tarif}');"><i class="fas fa-pen-to-square pr-3"></i></a>
                                    <a href="#inputSection" class="delete"
                                    data-nmFoto="${item.nmFoto}"
                                    data-id="${item.kdFoto}"onclick="deleteFoto(${item.kdFoto}, '${item.nmFoto}', '${item.tarif}');"><i class="fas fa-trash pr-3"></i></a>`;
            });

            $("#dataPeriksa").DataTable({
                data: response,
                columns: [
                    { data: "actions" },
                    { data: "no" },
                    { data: "nmFoto" },
                    { data: "tarif" },
                ],
                order: [1, "asc"],
                paging: true,
                lengthMenu: [
                    [5, 10, 25, 50, -1],
                    [5, 10, 25, 50, "All"],
                ],
                pageLength: 5,
            });
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        },
    });
}
function proyeksiRo() {
    if ($.fn.DataTable.isDataTable("#dataProyeksi")) {
        $("#dataProyeksi").DataTable().clear().destroy();
    }

    $.ajax({
        url: "/api/proyeksiRo",
        type: "GET",
        data: {},
        success: function (response) {
            response.forEach(function (item, index) {
                item.no = index + 1; // Nomor urut dimulai dari 1, bukan 0
                item.actions = `<a href="#inputSection" class="edit"
                                    data-proyeksi="${item.proyeksi}"
                                    data-id="${item.kdProyeksi}" onclick="editProyeksi(${item.kdProyeksi}, '${item.proyeksi}');"><i class="fas fa-pen-to-square pr-3"></i></a>
                                    <a href="#inputSection" class="delete"
                                    data-proyeksi="${item.proyeksi}"
                                    data-id="${item.kdProyeksi}" onclick="deleteProyeksi(${item.kdProyeksi}, '${item.proyeksi}');"><i class="fas fa-trash pr-3"></i></a>`;
            });

            $("#dataProyeksi").DataTable({
                data: response,
                columns: [
                    { data: "actions" },
                    { data: "no" },
                    { data: "proyeksi" },
                ],
                order: [1, "asc"],
                paging: true,
                lengthMenu: [
                    [5, 10, 25, 50, -1],
                    [5, 10, 25, 50, "All"],
                ],
                pageLength: 5,
            });
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        },
    });
}
function kondisiRo() {
    if ($.fn.DataTable.isDataTable("#dataKondisi")) {
        $("#dataKondisi").DataTable().clear().destroy();
    }

    $.ajax({
        url: "/api/kondisiRo",
        type: "post",
        data: {},
        success: function (response) {
            response.forEach(function (item, index) {
                item.no = index + 1; // Nomor urut dimulai dari 1, bukan 0
                item.actions = `<a href="#inputSection" class="edit"
                                    data-nmKondisi="${item.nmKondisi}"
                                    data-id="${item.kdKondisiRo}" onclick="editKondisi(${item.kdKondisiRo}, '${item.nmKondisi}', '${item.grup}', '${item.status}');"><i class="fas fa-pen-to-square pr-3"></i></a>
                                    <a href="#inputSection" class="delete"
                                    data-nmKondisi="${item.nmKondisi}"
                                    data-id="${item.kdKondisiRo}" onclick="editKondisi(${item.kdKondisiRo}, '${item.nmKondisi}', '${item.grup}', '${item.status}');"><i class="fas fa-trash pr-3"></i></a>`;
            });

            $("#dataKondisi").DataTable({
                data: response,
                columns: [
                    { data: "actions" },
                    { data: "no" },
                    { data: "nmKondisi" },
                    { data: "grup" },
                    { data: "status" },
                ],
                order: [1, "asc"],
                paging: true,
                lengthMenu: [
                    [5, 10, 25, 50, -1],
                    [5, 10, 25, 50, "All"],
                ],
                pageLength: 5,
            });
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        },
    });
}
function ukuranRo() {
    if ($.fn.DataTable.isDataTable("#dataUkuran")) {
        $("#dataUkuran").DataTable().clear().destroy();
    }

    $.ajax({
        url: "/api/filmRo",
        type: "GET",
        data: {},
        success: function (response) {
            response.forEach(function (item, index) {
                item.no = index + 1; // Nomor urut dimulai dari 1, bukan 0
                item.actions = `<a href="#inputSection" class="edit"
                                    data-ukuranFilm="${item.ukuranFilm}"
                                    data-id="${item.kdFilm}" onclick="editUkuran(${item.kdFilm}, '${item.ukuranFilm}');"><i class="fas fa-pen-to-square pr-3"></i></a>
                                    <a href="#inputSection" class="delete"
                                    data-ukuranFilm="${item.ukuranFilm}"
                                    data-id="${item.kdFilm}" onclick="editUkuran(${item.kdFilm}, '${item.ukuranFilm}');"><i class="fas fa-trash pr-3"></i></a>`;
            });

            $("#dataUkuran").DataTable({
                data: response,
                columns: [
                    { data: "actions" },
                    { data: "no" },
                    { data: "ukuranFilm" },
                ],
                order: [1, "asc"],
                paging: true,
                lengthMenu: [
                    [5, 10, 25, 50, -1],
                    [5, 10, 25, 50, "All"],
                ],
                pageLength: 5,
            });
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
        },
    });
}

$(document).ready(function () {
    showPeriksa();
    layananRo();
    kondisiRo();
    ukuranRo();
    proyeksiRo();
});
