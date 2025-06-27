            <!-- /.footer -->
            <footer class="main-footer">
                <strong>Copyright &copy; {{ date('Y') }} <a href="#">Klinik Utama Kesehatan Paru Masyarakat
                        Kelas
                        A</a>.</strong>
                All rights reserved.
                <div class="float-right d-none d-sm-inline-block pr-4 mr-4">
                    <b>Versi</b> {{ env('APP_LARAVEL_VERSION') }}
                </div>
            </footer>


            <!-- Logout Modal-->
            <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ModalLabel">Ready to Leave?</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">Select "Logout" below if you are ready to end your current session.
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                            <a class="btn btn-primary" href="{{ route('actionlogout') }}">Logout</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Ubah Password -->
            <div class="modal fade" id="modal-password" tabindex="-1" role="dialog"
                aria-labelledby="modal-passwordLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal-passwordLabel">Form Ubah Password</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            @csrf
                            <form id="form-password">
                                <input type="text" class="form-control" id="id_user" name="id_user"
                                    value="{{ Auth::user()->id }}" readonly>
                                <div class="form-group">
                                    <label for="password">Password Baru</label>
                                    <input type="password" class="form-control" id="password" name="password">
                                </div>
                                <div class="form-group">
                                    <label for="password_confirmation">Konfirmasi Password Baru</label>
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation">
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                            <a class="btn btn-primary" onclick="updatePassword();">Simpan</a>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                function updatePassword() {
                    var id_user = $('#id_user').val();
                    var password = $('#password').val();
                    var password_confirmation = $('#password_confirmation').val();

                    $.ajax({
                        url: '/users/' + id_user,
                        type: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        },
                        data: {
                            id: id_user,
                            password: password,
                            password_confirmation: password_confirmation,
                        },
                        success: function(response) {
                            Toast.fire({
                                icon: 'success',
                                title: 'Password berhasil diubah'
                            });
                            $('#modal-password').modal('hide');
                        },
                        error: function(xhr) {
                            Toast.fire({
                                icon: 'error',
                                title: 'Terjadi kesalahan saat mengubah password'
                            });
                        }
                    });
                }
            </script>
