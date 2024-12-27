{{-- @extends('layouts.layout') --}}
@extends('Template.lte')

@section('content')
    @include('Kasir.Master.input')


    <!-- my script -->
    <script src="{{ asset('js/template.js') }}"></script>
    <script src="{{ asset('js/populate.js') }}"></script>
    <script>
        const layanan = @json($layanan);

        function validasi() {
            var id = $("#idLayanan").val();

            if (id == "") {
                addLayanan();
                console.log("🚀 ~ validasi ~ id:", id)
            } else {
                updateLayanan();

                console.log("🚀 ~ validasi ~ id:", id)
            }
        }

        function editLayanan(button) {
            console.log("🚀 ~ editLayanan ~ button:", button);
            var id = button.getAttribute("data-id");
            var nmLayanan = button.getAttribute("data-nmLayanan");
            var tarif = button.getAttribute("data-harga");
            var kelas = button.getAttribute("data-kelas");
            console.log("🚀 ~ editLayanan ~ kelas:", kelas)
            var status = button.getAttribute("data-status");
            var normal = button.getAttribute("data-normal");
            var satuan = button.getAttribute("data-satuan");

            $("#idLayanan").val(id);
            $("#nmLayanan").val(nmLayanan);
            $("#tarif").val(tarif);
            $("#normal").val(normal);
            $("#satuan").val(satuan);
            $("#kelas").val(kelas).trigger("change");
            $("#layanan").val(status).trigger("change");
        }

        function updateLayanan() {
            var id = document.getElementById("idLayanan").value;
            var nmLayanan = document.getElementById("nmLayanan").value;
            var tarif = document.getElementById("tarif").value;
            var kelas = document.getElementById("kelas").value;
            var normal = document.getElementById("normal").value;
            var satuan = document.getElementById("satuan").value;
            var status = document.getElementById("layanan").value;
            $.ajax({
                url: "/api/layanan/update",
                type: "POST",
                data: {
                    id: id,
                    nmLayanan: nmLayanan,
                    tarif: tarif,
                    kelas: kelas,
                    status: status,
                    normal: normal,
                    satuan: satuan,
                },
                success: function(response) {
                    console.log("🚀 ~ updateLayanan ~ response:", response);
                    Swal.fire({
                        icon: "success",
                        title: response.message,
                    });
                    const data = response.data;
                    console.log("🚀 ~ updateLayanan ~ data:", data)
                    isiTabelLayanan(data)
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                    })
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
                    normal: normal,
                    satuan: satuan,
                },
                success: function(response) {
                    console.log("🚀 ~ updateLayanan ~ response:", response);
                    Swal.fire({
                        icon: "success",
                        title: response.message,
                    });
                    // isiTabelLayanan(data)
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                    })
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
                success: function(response) {
                    isiTabelLayanan(response);
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                },
            });
        }

        function isiTabelLayanan(data) {
            if ($.fn.DataTable.isDataTable("#dataLayanan")) {
                $("#dataLayanan").DataTable().clear().destroy();
            }
            data.forEach(function(item, index) {
                let normal = item.normal === null ? "-" : item.normal
                item.no = index + 1; // Nomor urut dimulai dari 1, bukan 0
                item.actions = `<a type="button" class="btn-sm  btn-info edit mr-3"
                                    data-harga="${item.tarif}"
                                    data-nmLayanan="${item.nmLayanan}"
                                    data-id="${item.idLayanan}"
                                    data-kelas="${item.kelas}"
                                    data-status="${item.status}"
                                    data-normal="${normal}"
                                    data-satuan="${item.satuan}"
                                    data-toggle="modal"
                                    data-target="#modal-layanan"
                                    onclick="editLayanan(this);"><i class="fas fa-pen-to-square px-1"></i></a>
                                <a type="button" class="btn-sm  btn-danger delete"
                                    data-id="${item.idLayanan}"
                                    onclick="deleteLayanan(${item.idLayanan}, '${item.nmLayanan}'); return false;">
                                    <i class="fas fa-trash-alt px-1"></i>
                                </a>
                                `;

                if (item.status == 1) {
                    item.status = "Tersedia";
                } else {
                    item.status = "Tidak Tersedia";
                }
            });

            $("#dataLayanan").DataTable({
                data: data,
                columns: [{
                        data: "actions",
                        className: "text-center col-1"
                    },
                    {
                        data: "no"
                    },
                    {
                        data: "nmLayanan",
                        className: "col-3"
                    },
                    {
                        data: "tarif",
                        render: function(data, type, row) {
                            var formattedTarif = parseInt(data).toLocaleString(
                                "id-ID", {
                                    style: "currency",
                                    currency: "IDR",
                                    minimumFractionDigits: 0,
                                }
                            );
                            return `${formattedTarif}`;
                        },
                    },
                    {
                        data: "status",
                        className: "col-2"
                    },
                    {
                        data: "grup.nmKelas",
                        className: "col-2"
                    },
                    {
                        data: "satuan"
                    },
                    {
                        data: "normal"
                    },
                ],
                order: [1, "asc"],
                paging: true,
                lengthMenu: [
                    [5, 10, 25, 50, -1],
                    [5, 10, 25, 50, "All"],
                ],
                pageLength: 10,
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
                        success: function(response) {
                            console.log("🚀 ~ deleteLayanan ~ response:", response);
                            Swal.fire({
                                icon: "success",
                                title: response.message,
                            });
                            layananLab();
                        },
                        error: function(xhr) {
                            // Handle error
                        },
                    });
                }
            });
        }

        $(document).ready(function() {
            isiTabelLayanan(layanan);
        });
    </script>
@endsection
