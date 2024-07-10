function showReagen() {
    $("#periksa").hide();
    $("#reagen").show();
}

function showPeriksa() {
    $("#reagen").hide();
    $("#periksa").show();
}

function editLayanan(button) {
    var id = button.getAttribute("data-id");
    var nmLayanan = button.getAttribute("data-nmLayanan");
    var tarif = button.getAttribute("data-harga");
    var kelas = button.getAttribute("data-kelas");
    var status = button.getAttribute("data-status");
    document.getElementById("status-idLayanan").value = id;
    document.getElementById("status-nmLayanan").value = nmLayanan;
    document.getElementById("status-tarif").value = tarif;
    $("#status-kelas").val(kelas).trigger("change");
    $("#status-layanan").val(status).trigger("change");
}

function updateLayanan() {
    var id = document.getElementById("status-idLayanan").value;
    var nmLayanan = document.getElementById("status-nmLayanan").value;
    var tarif = document.getElementById("status-tarif").value;
    var kelas = document.getElementById("status-kelas").value;
    var status = document.getElementById("status-layanan").value;
    $.ajax({
        url: "/api/update/layanan",
        type: "POST",
        data: {
            id: id,
            nmLayanan: nmLayanan,
            tarif: tarif,
            kelas: kelas,
            status: status,
        },
        success: function (response) {
            console.log("🚀 ~ updateLayanan ~ response:", response);
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
                                    data-toggle="modal"
                                    data-target="#modal-update"
                                    onclick="editLayanan(this);"><i class="fas fa-pen-to-square pr-3"></i></a>`;

                if (item.status == 1) {
                    item.status = "Tersedia";
                } else {
                    item.status = "Tidak Tersedia";
                }
            });

            $("#dataPeriksa").DataTable({
                data: response,
                columns: [
                    { data: "actions" },
                    { data: "no" },
                    { data: "nmLayanan" },
                    { data: "tarif" },
                    { data: "status" },
                    { data: "kelas" },
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
    layananLab();
});
