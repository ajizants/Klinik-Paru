function showReagen() {
    $("#periksa").hide();
    $("#reagen").show();
}

function showPeriksa() {
    $("#reagen").hide();
    $("#periksa").show();
}

function editLayanan(button) {
    console.log("🚀 ~ editLayanan ~ button:", button);
    var id = button.getAttribute("data-id");
    var nmLayanan = button.getAttribute("data-nmLayanan");
    var tarif = button.getAttribute("data-harga");
    var kelas = button.getAttribute("data-kelas");
    var status = button.getAttribute("data-status");
    var estimasi = button.getAttribute("data-estimasi");
    var normal = button.getAttribute("data-normal");
    var satuan = button.getAttribute("data-satuan");

    document.getElementById("status-idLayanan").value = id;
    document.getElementById("status-nmLayanan").value = nmLayanan;
    document.getElementById("status-tarif").value = tarif;
    document.getElementById("status-estimasi").value = estimasi;
    document.getElementById("status-normal").value = normal;
    document.getElementById("status-satuan").value = satuan;
    $("#status-kelas").val(kelas).trigger("change");
    $("#status-layanan").val(status).trigger("change");
}

function updateLayanan() {
    var id = document.getElementById("status-idLayanan").value;
    var nmLayanan = document.getElementById("status-nmLayanan").value;
    var tarif = document.getElementById("status-tarif").value;
    var kelas = document.getElementById("status-kelas").value;
    var estimasi = document.getElementById("status-estimasi").value;
    var normal = document.getElementById("status-normal").value;
    var satuan = document.getElementById("status-satuan").value;
    var status = document.getElementById("status-layanan").value;
    $.ajax({
        url: "/api/layanan/update",
        type: "POST",
        data: {
            id: id,
            nmLayanan: nmLayanan,
            tarif: tarif,
            kelas: kelas,
            status: status,
            estimasi: estimasi,
            normal: normal,
            satuan: satuan,
        },
        success: function (response) {
            console.log("🚀 ~ updateLayanan ~ response:", response);
            Swal.fire({
                icon: "success",
                title: response.message,
            });
            layananLab();
        },
        error: function (xhr) {
            // Handle error
        },
    });
}
function addLayanan() {
    var nmLayanan = document.getElementById("nmLayanan").value;
    var tarif = document.getElementById("tarif").value;
    var kelas = document.getElementById("kelas").value;
    var status = document.getElementById("layanan").value;
    $.ajax({
        url: "/api/layanan/add",
        type: "POST",
        data: {
            nmLayanan: nmLayanan,
            tarif: tarif,
            kelas: kelas,
            status: status,
        },
        success: function (response) {
            console.log("🚀 ~ updateLayanan ~ response:", response);
            Swal.fire({
                icon: "success",
                title: response.message,
            });
            layananLab();
        },
        error: function (xhr) {
            // Handle error
        },
    });
}
function layananLab() {
    if ($.fn.DataTable.isDataTable("#dataPeriksa")) {
        $("#dataPeriksa").DataTable().clear().destroy();
    }

    $.ajax({
        url: "/api/layananLabAll",
        type: "GET",
        data: {},
        success: function (response) {
            response.forEach(function (item, index) {
                item.no = index + 1; // Nomor urut dimulai dari 1, bukan 0
                item.actions = `<a href="" class="edit"
                                    data-harga="${item.tarif}"
                                    data-nmLayanan="${item.nmLayanan}"
                                    data-id="${item.idLayanan}"
                                    data-kelas="${item.kelas}"
                                    data-status="${item.status}"
                                    data-estimasi="${item.estimasi}"
                                    data-normal="${item.normal}"
                                    data-satuan="${item.satuan}"
                                    data-toggle="modal"
                                    data-target="#modal-update"
                                    onclick="editLayanan(this);"><i class="fas fa-pen-to-square pr-3"></i></a>
                                <a href="" class="delete"
                                    data-id="${item.idLayanan}"
                                    onclick="deleteLayanan(${item.idLayanan}, '${item.nmLayanan}'); return false;">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                                `;

                if (item.status == 1) {
                    item.status = "Tersedia";
                } else {
                    item.status = "Tidak Tersedia";
                }
            });

            $("#dataPeriksa").DataTable({
                data: response,
                columns: [
                    { data: "actions", className: "text-center col-1" },
                    { data: "no" },
                    { data: "nmLayanan" },
                    { data: "tarif" },
                    { data: "estimasi" },
                    { data: "satuan" },
                    { data: "normal" },
                    { data: "status" },
                    { data: "kelas" },
                ],
                order: [1, "dsc"],
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

function deleteLayanan(id, nmLayanan) {
    Swal.fire({
        title: "Apakah anda yakin ingin menghapus layanan " + nmLayanan + "?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "YA",
        cancelButtonText: "TIDAK",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/api/layanan/delete",
                type: "POST",
                data: {
                    id: id,
                },
                success: function (response) {
                    console.log("🚀 ~ deleteLayanan ~ response:", response);
                    Swal.fire({
                        icon: "success",
                        title: response.message,
                    });
                    layananLab();
                },
                error: function (xhr) {
                    // Handle error
                },
            });
        }
    });
}

$(document).ready(function () {
    showPeriksa();
    layananLab();
});
