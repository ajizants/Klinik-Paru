@extends('Template.lte')

@section('content')
    @include('Gizi.Master.input')

    {{-- @include('Template.script') --}}

    <!-- my script -->
    <script src="{{ asset('js/template.js') }}"></script>
    {{-- <script src="{{ asset('js/populate.js') }}"></script> --}}
    <script>
        function showSubKelas() {
            if ($.fn.DataTable.isDataTable("#dataSubKelas")) {
                $("#dataSubKelas").DataTable().clear().destroy();
            }

            $.ajax({
                url: "/api/gizi/dx/subKelas",
                type: "GET",
                data: {},
                success: function(response) {
                    response.forEach(function(item, index) {
                        item.no = index + 1;
                        item.actions = `<a href="" class="edit"
                                    data-id="${item.id}"
                                    data-domain="${item.domain}"
                                    data-kelas="${item.kelas}"
                                    data-kode="${item.kode}"
                                    data-deskripsi="${item.sub_kelas}"
                                    data-toggle="modal"
                                    data-target="#modal-form"
                                    onclick="edit(this, 'form_subKelas','Tambah Sub Kelas Diagnosa');"><i class="fas fa-pen-to-square pr-3"></i></a>
                                <a href="" class="delete"
                                    onclick="deleteItem(${item.id}, '${item.sub_kelas}','Sub Kelas'); return false;">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                                `;

                    });

                    $("#dataSubKelas").DataTable({
                        data: response,
                        columns: [{
                                data: "no"
                            },
                            {
                                data: "domain.domain"
                            },
                            {
                                data: "kelas.kelas"
                            },
                            {
                                data: "kode"
                            },
                            {
                                data: "sub_kelas"
                            },
                            {
                                data: "actions",
                                className: "col-2"
                            },
                        ],
                        order: [0, "dsc"],
                        paging: true,
                        lengthMenu: [
                            [5, 10, 25, 50, -1],
                            [5, 10, 25, 50, "All"],
                        ],
                        pageLength: 5,
                    });
                },
                error: function(xhr, domain, error) {
                    console.error("Error:", error);
                },
            });
        }



        function showKelas() {
            if ($.fn.DataTable.isDataTable("#dataKelas")) {
                $("#dataKelas").DataTable().clear().destroy();
            }

            $.ajax({
                url: "/api/gizi/dx/kelas",
                type: "GET",
                data: {},
                success: function(response) {
                    response.forEach(function(item, index) {
                        item.no = index + 1;
                        item.actions = `<a href="" class="edit"
                                    data-id="${item.id}"
                                    data-domain="${item.domain}"
                                    data-kode="${item.kode}"
                                    data-deskripsi="${item.kelas}"
                                    data-toggle="modal"
                                    data-target="#modal-form"
                                    onclick="edit(this, 'form_kelas','Edit Kelas Diagnosa');"><i class="fas fa-pen-to-square pr-3"></i></a>
                                <a href="" class="delete"
                                    onclick="deleteItem(${item.id}, '${item.kelas}','Kelas'); return false;">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                                `;
                    });

                    $("#dataKelas").DataTable({
                        data: response,
                        columns: [{
                                data: "no"
                            },
                            {
                                data: "domain.domain"
                            },
                            {
                                data: "kode"
                            },
                            {
                                data: "kelas"
                            },
                            {
                                data: "actions",
                                className: "col-2"
                            },
                        ],
                        order: [0, "dsc"],
                        paging: true,
                        lengthMenu: [
                            [5, 10, 25, 50, -1],
                            [5, 10, 25, 50, "All"],
                        ],
                        pageLength: 5,
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                },
            });
        }

        function showDomain() {
            if ($.fn.DataTable.isDataTable("#dataDomain")) {
                $("#dataDomain").DataTable().clear().destroy();
            }

            $.ajax({
                url: "/api/gizi/dx/domain",
                type: "GET",
                data: {},
                success: function(response) {
                    response.forEach(function(item, index) {
                        item.no = index + 1;
                        item.actions = `<a href="" class="edit"
                                    data-id="${item.id}"
                                    data-kode="${item.kode}"
                                    data-deskripsi="${item.domain}"
                                    data-toggle="modal"
                                    data-target="#modal-form"
                                    onclick="edit(this, 'form_domain','Form Edit Domain Diagnosa');"><i class="fas fa-pen-to-square pr-3"></i></a>
                                <a href="" class="delete"
                                    onclick="deleteItem(${item.id}, '${item.domain}','Domain'); return false;">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                                `;

                    });

                    $("#dataDomain").DataTable({
                        data: response,
                        columns: [{
                                data: "no"
                            },
                            {
                                data: "kode"
                            },
                            {
                                data: "domain"
                            },
                            {
                                data: "actions",
                                className: "col-2"
                            },
                        ],
                        order: [0, "dsc"],
                        paging: true,
                        lengthMenu: [
                            [5, 10, 25, 50, -1],
                            [5, 10, 25, 50, "All"],
                        ],
                        pageLength: 5,
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                },
            });
        }

        function validasi(what) {
            // Define inputs to validate based on the form type
            var inputsToValidate;
            switch (what) {
                case "Domain":
                    inputsToValidate = ["kode", "deskripsi"];
                    break;
                case "Kelas":
                    inputsToValidate = ["kode", "deskripsi", "domain"];
                    break;
                case "Sub Kelas":
                    inputsToValidate = ["kode", "deskripsi", "domain", "kelas"];
                    break;
                default:
                    console.error("Unknown form type:", what);
                    return;
            }

            var error = false;

            // Validate each input
            inputsToValidate.forEach(function(inputId) {
                var inputElement = document.getElementById(inputId);
                var inputValue = inputElement.value.trim();

                if (inputValue === "") {
                    if ($(inputElement).hasClass("select2-hidden-accessible")) {
                        // Handle Select2 element
                        $(inputElement).next(".select2-container").addClass("input-error");
                    } else {
                        // Handle regular input element
                        inputElement.classList.add("input-error");
                    }
                    error = true;
                } else {
                    if ($(inputElement).hasClass("select2-hidden-accessible")) {
                        // Remove error class from Select2 element
                        $(inputElement).next(".select2-container").removeClass("input-error");
                    } else {
                        // Remove error class from regular input element
                        inputElement.classList.remove("input-error");
                    }
                }
            });

            // Show error message if there are validation errors
            if (error) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Ada data yang masih kosong! Mohon lengkapi semua data.",
                });
            } else {
                // Proceed with adding item if no errors
                addItem(what);
            }
        }


        function addItem(what) {
            console.log("🚀 ~ addItem ~ what:", what);

            // Collect values from the input fields
            var id = document.getElementById("id").value;
            var kode = document.getElementById("kode").value;
            var domain = document.getElementById("domain") ? document.getElementById("domain").value : '';
            var kelas = document.getElementById("kelas") ? document.getElementById("kelas").value : '';
            var deskripsi = document.getElementById("deskripsi").value;

            var urlAdd;
            var dataPost;

            // Set the URL and data to post based on the form type
            if (what === "Domain") {
                urlAdd = "api/gizi/dx/domain";
                dataPost = {
                    id: id,
                    kode: kode,
                    deskripsi: deskripsi,
                };

            } else if (what === "Kelas") {
                urlAdd = "api/gizi/dx/kelas";
                dataPost = {
                    id: id,
                    kode: kode,
                    deskripsi: deskripsi,
                    domain: domain,
                };

            } else if (what === "Sub Kelas") {
                urlAdd = "api/gizi/dx/subKelas";
                dataPost = {
                    id: id,
                    kode: kode,
                    deskripsi: deskripsi,
                    kelas: kelas,
                    domain: domain,
                };

            } else {
                console.error("Unknown form type:", what);
                return;
            }

            console.log("🚀 ~ addItem ~ urlAdd:", urlAdd);

            // Perform the AJAX request
            $.ajax({
                url: urlAdd,
                type: "POST",
                data: dataPost,
                success: function(response) {
                    console.log("🚀 ~ addItem ~ response:", response);
                    Swal.fire({
                        icon: "success",
                        title: response.message,
                    });

                    // Call appropriate function based on form type
                    if (what === "Domain") {
                        showDomain();
                    } else if (what === "Kelas") {
                        showKelas();
                    } else if (what === "Sub Kelas") {
                        showSubKelas();
                    } else {
                        console.error("Unknown form type:", what);
                    }

                    // Reset the form
                    resetForm("form_subKelas");
                },
                error: function(xhr) {
                    // Handle error
                    console.error("AJAX Error:", xhr.responseText);
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Terjadi kesalahan saat menambahkan data.",
                    });
                },
            });
        }


        function deleteItem(id, deskripsi, what) {
            var urlDel;

            // Determine the URL based on the 'what' parameter
            switch (what) {
                case "Domain":
                    urlDel = "api/gizi/dx/domain/delete";
                    break;
                case "Kelas":
                    urlDel = "api/gizi/dx/kelas/delete";
                    break;
                case "Sub Kelas":
                    urlDel = "api/gizi/dx/subKelas/delete";
                    break;
                default:
                    console.error("Unknown form type:", what);
                    return;
            }

            // Show confirmation dialog
            Swal.fire({
                title: `Apakah anda yakin ingin menghapus ${what}: ${deskripsi}?`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "YA",
                cancelButtonText: "TIDAK",
            }).then((result) => {
                if (result.isConfirmed) {
                    // Perform the delete operation
                    $.ajax({
                        url: urlDel,
                        type: "POST",
                        data: {
                            id: id
                        },
                        success: function(response) {
                            console.log("🚀 ~ deleteItem ~ response:", response);
                            Swal.fire({
                                icon: "success",
                                title: response.message,
                            });
                            // Call the appropriate function based on 'what'
                            switch (what) {
                                case "Domain":
                                    showDomain();
                                    break;
                                case "Kelas":
                                    showKelas();
                                    break;
                                case "Sub Kelas":
                                    showSubKelas();
                                    break;
                                default:
                                    console.error("Unknown form type:", what);
                                    break;
                            }
                        },
                        error: function(xhr) {
                            console.error("AJAX Error:", xhr.responseText);
                            Swal.fire({
                                icon: "error",
                                title: "Terjadi kesalahan",
                                text: xhr.responseJSON?.message || "Gagal menghapus data.",
                            });
                        },
                    });
                }
            });
        }

        function edit(button, idForm, judul) {
            document.getElementById("modal-title").innerHTML = `${judul}`;

            if (button !== null) {
                var id = button.getAttribute("data-id");
                console.log("🚀 ~ edit ~ id:", id)
                var kode = button.getAttribute("data-kode");
                var deskripsi = button.getAttribute("data-deskripsi");
                var domain = button.getAttribute("data-domain");
                var kelas = button.getAttribute("data-kelas");
                document.getElementById("deskripsi").value = deskripsi;
                document.getElementById("kode").value = kode;
                document.getElementById("id").value = id;
            }


            if (idForm === "form_subKelas") {
                $("#kelas").val(kelas).trigger("change");
                $("#domain").val(domain).trigger("change");
                $('#domainLabel, #kelasLabel, #domainDiv, #kelasDiv, #simpanSubKelas').show();
                $('#simpanKelas, #simpanDomain').hide();

            } else if (idForm === "form_kelas") {
                $('#domain').val(domain).trigger("change");
                $('#kelasLabel, #kelasDiv, #simpanDomain, #simpanSubKelas').hide();
                $('#simpanKelas, #domainDiv, #domainLabel').show();

            } else if (idForm === "form_domain") {
                $('#domainLabel, #kelasLabel, #domainDiv, #kelasDiv, #simpanSubKelas, #simpanKelas').hide();
                $('#simpanDomain').show();
            }
        }

        function resetForm(idForm) {
            document.getElementById(idForm).reset();
            $("#" + idForm + " select").trigger("change");
            $(" select").trigger("change");
            $("#modal-form").modal("hide");

            var inputsToValidate = [
                "kode",
                "deskripsi",
                "domain",
                "kelas",
            ];
            inputsToValidate.forEach(function(inputId) {
                var inputElement = document.getElementById(inputId);
                if ($(inputElement).hasClass("select2-hidden-accessible")) {
                    // Select2 element
                    $(inputElement)
                        .next(".select2-container")
                        .removeClass("input-error");
                } else {
                    // Regular input element
                    inputElement.classList.remove("input-error");
                }
            });
        }

        $(document).ready(function() {
            showSubKelas();
            showDomain();
            showKelas();
        });
    </script>
@endsection
