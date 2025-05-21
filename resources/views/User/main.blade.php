@extends('Template.lte')

@section('content')
    <div class="container-fluid">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a type="button" class="nav-link active bg-blue" onclick="toggleSections('#tab_1');"><b>Users</b></a>
            </li>
            <li class="nav-item">
                <a type="button" class="nav-link" onclick="toggleSections('#tab_2');"><b>Users Online</b></a>
            </li>
        </ul>
    </div>

    <div class="container-fluid mt-1" id="tab_1">
        @include('User.users')
    </div>
    <div class="container-fluid mt-1" id="tab_2" style="display: none;">
        @include('User.usersOnline')
    </div>

    <!-- my script -->
    <script src="{{ asset('js/template.js') }}"></script>
    <script src="{{ asset('js/populate.js') }}"></script>
    <script>
        function cariLogUser() {
            var url = "/user/online";
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success: function(response) {
                    $("#usersOnlineContainer").html(response.users);
                    dataTable("tableUserOnline");
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal Memuat Data",
                        text: "Terjadi kesalahan saat mengambil data: " + error,
                    });
                }

            });
        }
        $(document).on('click', '.toggle-password', function() {
            const targetId = $(this).data('target');
            const input = $('#' + targetId);
            const icon = $(this).find('i');

            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                input.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });

        function showEditUserModal(userId) {
            $.ajax({
                url: "users/" + userId + "/edit",
                type: 'GET',
                dataType: 'HTML',
                success: function(response) {
                    $('#editUserModalBody').html(response);
                    $('#editUserModal').modal('show');
                },
                error: function(xhr) {
                    tampilkanEror('Terjadi kesalahan saat mengambil data.' + xhr.responseJSON.message);
                }
            })
        }

        function updateUser() {
            let form = $('#editUserForm'); // pastikan form kamu punya ID ini
            let actionUrl = form.attr('action');
            console.log("🚀 ~ updateUser ~ actionUrl:", actionUrl)
            let formData = form.serialize();
            tampilkanLoading("Menyimpan data...");

            $.ajax({
                url: actionUrl,
                type: 'POST',
                dataType: 'JSON',
                data: formData,
                success: function(response) {
                    tampilkanSukses(response.messages);
                    $('#editUserModal').modal('hide');
                    $("#usersContainer").html(response.users);
                    dataTable("tableUser");
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let messages = Object.values(errors).map(e => e.join(', ')).join('<br>');
                        tampilkanEror(messages);
                    } else {
                        tampilkanEror('Terjadi kesalahan saat menyimpan.');
                    }
                }
            });
        }

        function dataTable(id) {
            var table = $('#' + id).DataTable({
                responsive: true,
                lengthChange: false,
                autoWidth: false,
                searching: true,
                paging: true,
                ordering: true,
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
                        previous: "←"
                    }
                },
            });
        }

        function cariLogUser() {
            var url = "/api/userOnline";
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    $("#tab_1").html(data);
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal Memuat Data",
                        text: "Terjadi kesalahan saat mengambil data: " + error,
                    });
                }

            });
        }
        $(document).ready(function() {
            dataTable('tableUserOnline');
            dataTable('tableUser');
        })
    </script>
@endsection
