<!-- Tombol -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalAddUser">
    Tambah User
</button>

<!-- Modal -->
<div class="modal fade" id="modalAddUser" tabindex="-1" role="dialog" aria-labelledby="modalAddUserLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form id="formAddUser">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddUserLabel">Tambah User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formAddUser">
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="text" class="form-control" id="name" name="name" required="">
                        </div>

                        <div class="form-group">
                            <label for="role">Role</label>
                            <select class="form-control" id="role" name="role" required="">
                                <option value="">--Pilih Role--</option>
                                <option value="igd">IGD</option>
                                <option value="farmasi">Farmasi</option>
                                <option value="kasir">Kasir</option>
                                <option value="lab">Lab</option>
                                <option value="ro">Radiologi</option>
                                <option value="dots">DOTS</option>
                                <option value="loket">Loket</option>
                                <option value="gizi">Gizi</option>
                                <option value="dokter">Dokter</option>
                                <option value="dpjp">DPJP</option>
                                <option value="perawat">Perawat</option>
                                <option value="analitik">Data Analis</option>
                                <option value="promkes">Promkes</option>
                                <option value="atk">Pengelola Barang</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required="">
                        </div>

                        <div class="form-group">
                            <label for="password">Password <small>(kosongkan jika tidak ingin diubah)</small></label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password">
                                <div class="input-group-append">
                                    <button class="btn btn-secondary toggle-password" type="button"
                                        data-target="password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Konfirmasi Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation">
                                <div class="input-group-append">
                                    <button class="btn btn-secondary toggle-password" type="button"
                                        data-target="password_confirmation">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('#formAddUser').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: "{{ route('user.store') }}",
            method: "POST",
            data: $(this).serialize(),
            success: function(response) {
                $('#modalAddUser').modal('hide');
                tampilkanSukses(response.message);
                $('#formAddUser')[0].reset();
                // refresh tabel kalau ada
                $("#usersContainer").html(response.users);
                dataTable('tableUser');
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors;
                let message = "Terjadi kesalahan:\n";
                for (let field in errors) {
                    message += `- ${errors[field][0]}\n`;
                }
                tampilkanEror(message);
            }
        });
    });
</script>
