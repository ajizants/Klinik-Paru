function showReagen() {
    $("#periksa").hide();
    $("#reagen").show();
}

function showPeriksa() {
    $("#reagen").hide();
    $("#periksa").show();
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
                item.actions = `<a href="#inputSection" class="edit"
                                    data-harga="${item.harga}"
                                    data-nmLayanan="${item.nmLayanan}"
                                    data-id="${item.idLayanan}"><i class="fas fa-pen-to-square pr-3"></i></a>`;
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
