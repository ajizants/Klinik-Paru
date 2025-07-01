<style>
    .is-invalid {
        border-color: #dc3545;
    }
</style>

<div class="modal fade" id="modal-pengajuanCuti">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Formulir Pengajuan Cuti Pegawai</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formCuti">
                    <div class="form-row">
                        <div class="form-group col">

                            <label for="nip">NIP</label>
                            <input type="text" class="form-control" id="nip" name="nip"
                                value="{{ $nip }}" readonly required>
                        </div>

                        <div class="form-group col">
                            <label for="nama">Nama Petugas</label>
                            <input type="text" class="form-control" id="nama" name="nama"
                                value="{{ Auth::user()->name }}" readonly>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col">
                            <label for="tgl_mulai">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="tgl_mulai" name="tgl_mulai"
                                value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="form-group col">
                            <label for="tgl_selesai">Tanggal Selesai</label>
                            <input type="date" class="form-control" id="tgl_selesai" name="tgl_selesai"
                                value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col">
                            <label for="alasan">Alasan</label>
                            <select class="form-control" id="alasan" name="alasan" required>
                                <option value="">-- Pilih Alasan --</option>
                                <option value="Cuti Tahunan">Cuti Tahunan</option>
                                <option value="Cuti Sakit">Cuti Sakit</option>
                                <option value="Cuti Melahirkan">Cuti Melahirkan</option>
                                <option value="Cuti Besar">Cuti Besar</option>
                                <option value="Cuti Alasan Penting">Cuti Alasan Penting</option>
                            </select>
                        </div>

                        <div class="form-group col">
                            <label for="keterangan">Keterangan</label>
                            <input type="text" class="form-control" id="keterangan" name="keterangan" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="ajukanCuti()">Ajukan Cuti</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<script>
    function ajukanCuti() {
        const form = document.getElementById('formCuti');
        const formData = new FormData(form);
        let isValid = true;
        let firstInvalid = null;

        // Bersihkan border merah sebelumnya
        form.querySelectorAll('input, select, textarea').forEach(input => {
            input.classList.remove('is-invalid');
        });

        // Validasi: Semua input tidak boleh kosong
        form.querySelectorAll('[required]').forEach(input => {
            if (!input.value.trim()) {
                input.classList.add('is-invalid');
                if (!firstInvalid) firstInvalid = input;
                isValid = false;
            }
        });

        if (!isValid) {
            Swal.fire({
                icon: 'error',
                title: 'Form Belum Lengkap',
                text: 'Harap isi semua kolom yang wajib diisi.',
            });
            if (firstInvalid) firstInvalid.focus();
            return;
        }
        tampilkanLoading('Mengirim Pengajuan Cuti...');

        // Kirim jika valid
        fetch('/tu/cuti/pengajuan', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: formData
            })
            .then(async res => {
                const data = await res.json();

                if (!res.ok) {
                    throw data; // lempar pesan dari server (misalnya 422)
                }

                // jika berhasil
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: data.message || 'Pengajuan cuti berhasil dikirim.',
                });
                form.reset();
                $('#modal-pengajuanCuti').modal('hide');
                generateTabelPermohonanCuti(data.html);
            })
            .catch(err => {
                console.error(err);

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: err.message || 'Terjadi kesalahan saat mengirim data.',
                });

                // opsional: tetap update tabel walaupun gagal
                if (err.html) {
                    generateTabelPermohonanCuti(err.html);
                }
            });

    }
</script>
