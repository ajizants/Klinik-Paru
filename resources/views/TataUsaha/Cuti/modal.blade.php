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
                            @if (Auth::user()->role == 'pegawai')
                                <input type="text" class="form-control form-control-sm" id="nip" name="nip"
                                    value="{{ $nip ?? $cutiPegawai->nip }}" readonly required>
                            @else
                                <select name="nip" class="form-control select2bs4" id="nip">
                                    <option value="">-- Pilih Pegawai --</option>
                                    @foreach ($pegawai as $user)
                                        <option value="{{ $user->nip }}" {{-- pilih nip yang sama --}}
                                            @php $nip=$cutiPegawai->nip??$nip; @endphp
                                            @if ($user->nip == $nip) selected @endif
                                            data-nama="{{ $user->gelar_d }} {{ $user->nama }} {{ $user->gelar_b }}">
                                            {{ $user->gelar_d }} {{ $user->nama }}
                                            {{ $user->gelar_b }} - {{ $user->nip }}</option>
                                    @endforeach
                            @endif
                            </select>
                        </div>

                        <script>
                            $(document).ready(function() {
                                $('.select2bs4').select2({
                                    theme: 'bootstrap4'
                                });
                                $('#nip').on('change', function() {
                                    const selectedOption = $(this).find('option:selected');
                                    const namaLengkap = selectedOption.data('nama') || '';
                                    $('#nama').val(namaLengkap);
                                });
                            });
                        </script>

                        <div class="form-group col">
                            <label for="nama">Nama Petugas</label>
                            @if (isset($cutiPegawai->pegawai))
                                <input type="text" class="form-control form-control-sm" id="nama" name="nama"
                                    value="{{ $cutiPegawai->pegawai->gelar_d ?? '' }} {{ $cutiPegawai->pegawai->nama ?? '' }} {{ $cutiPegawai->pegawai->gelar_b ?? '' }} "
                                    readonly>
                            @else
                                <input type="text" class="form-control form-control-sm" id="nama" name="nama"
                                    value="{{ Auth::user()->name }}" readonly>
                            @endif
                            <input type="text" name="id" id="idCuti" value="{{ $cutiPegawai->id ?? '' }}"
                                readonly hidden>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col">
                            <label for="tgl_mulai">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="tgl_mulai" name="tgl_mulai"
                                value="{{ $cutiPegawai->tgl_mulai ?? date('Y-m-d') }}" required>
                        </div>

                        <div class="form-group col">
                            <label for="tgl_selesai">Tanggal Selesai</label>
                            <input type="date" class="form-control" id="tgl_selesai" name="tgl_selesai"
                                value="{{ $cutiPegawai->tgl_selesai ?? date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col">
                            <label for="alasan">Alasan</label>
                            @php
                                $listAlasan = [
                                    'Cuti Tahunan',
                                    'Cuti Besar',
                                    'Cuti Alasan Penting',
                                    'Cuti Melahirkan',
                                    'Cuti Sakit',
                                    'Cuti Bersama',
                                    'Dinas Luar',
                                ];
                            @endphp

                            <select class="form-control" id="alasan" name="alasan" required>
                                <option value="">-- Pilih Alasan --</option>
                                @foreach ($listAlasan as $alasan)
                                    <option value="{{ $alasan }}"
                                        {{ old('alasan', $cutiPegawai->alasan ?? '') == $alasan ? 'selected' : '' }}>
                                        {{ $alasan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col">
                            <label for="keterangan">Keterangan</label>
                            <input type="text" class="form-control" id="keterangan" name="keterangan"
                                value="{{ $cutiPegawai->keterangan ?? '' }}" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                @if (isset($cutiPegawai->pegawai))
                    <button type="button" class="btn btn-primary"
                        onclick="updatePermohonan({{ $cutiPegawai->id }})">Update</button>
                @else
                    <button type="button" class="btn btn-primary" onclick="ajukanCuti()">Simpan</button>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambahanCuti">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Formulir Penambahan Cuti Pegawai</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                {{-- Form Manual Input --}}
                <h5>Formulir Penambahan Cuti per Pegawai</h5>
                <form id="formTambahanCuti" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="nip_manual">Pilih Pegawai</label>
                        <select name="nip_manual" class="form-control select2bs4">
                            @foreach ($pegawai as $user)
                                <option value="{{ $user->nip }}">{{ $user->nama }} - {{ $user->nip }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="cuti_manual">Tambahan Cuti</label>
                        <input type="number" name="cuti_manual" class="form-control"
                            placeholder="Jumlah hari tambahan cuti">
                    </div>

                    <div class="form-group">
                        <button type="submit" id="tblSimpan" onclick="tambahkanCuti(event)"
                            class="btn btn-primary">
                            Simpan
                        </button>
                    </div>
                </form>

                <hr>

                {{-- Upload Excel --}}
                <h5>Formulir Penambahan Cuti Kolektif</h5>

                <div class="form-group">
                    <label for="file_tambahan_cuti">Upload Tambahan Cuti Multiple</label>
                    <input type="file" name="file_tambahan_cuti" id="file_tambahan_cuti" accept=".xls,.xlsx"
                        class="form-control">
                </div>

                <div class="form-group d-flex justify-content-between">
                    <button type="button" class="btn btn-primary" onclick="uploadTambahan(event)" id="tblUpload">
                        Upload
                    </button>
                    <a href="https://docs.google.com/spreadsheets/d/1-mxBJrTWcQcZEPD2xlnBqRht6T9BYEn4/edit?usp=sharing&ouid=109021928107189827550&rtpof=true&sd=true"
                        target="_blank" class="btn btn-warning">
                        Download Template
                    </a>
                </div>
            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>



<script>
    function uploadTambahan(event) {
        event.preventDefault();

        Swal.fire({
            title: 'Mohon Tunggu Beberapa Saat',
            text: 'Sedang memproses upload tambahan cuti...',
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading()
            }
        });

        $('#tblUpload').prop('disabled', true);

        const fileInput = document.getElementById("file_tambahan_cuti");
        const file = fileInput.files[0];

        if (!file) {
            Swal.close();
            tampilkanEror("File belum dipilih.");
            $('#tblUpload').prop('disabled', false);
            return;
        }

        const formData = new FormData();
        formData.append("file", file);

        $.ajax({
            url: "/tu/cuti/tambahan/kolektif",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                Swal.close();
                if (response.success) {
                    tampilkanSukses(response.message);
                } else {
                    tampilkanEror(response.message);
                }
            },
            error: function(xhr, status, error) {
                Swal.close();
                tampilkanEror(
                    `${error}, \n Terjadi kesalahan saat mengupload file: ${xhr.responseJSON?.message || "Unknown error"}`
                );
            },
            complete: function() {
                $('#tblUpload').prop('disabled', false);
            }
        });
    }

    function tambahkanCuti(event) {
        event.preventDefault();

        Swal.fire({
            title: 'Mohon Tunggu Beberapa Saat',
            text: 'Sedang menyimpan data cuti...',
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading()
            }
        });

        $('#tblSimpan').prop('disabled', true);

        const form = document.getElementById("formTambahanCuti");
        const formData = new FormData(form);
        const nip = form.querySelector('[name="nip_manual"]').value;

        $.ajax({
            url: "/tu/cuti/tambahan",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                Swal.close();
                if (response.success) {
                    tampilkanSukses(response.message);
                    // Misalnya kamu mau reload data tertentu:
                    // drawTable(response.data, "pegawai", "table_pegawai");
                } else {
                    tampilkanEror(response.message);
                }
            },
            error: function(xhr, status, error) {
                Swal.close();
                tampilkanEror(
                    `${error}, \n Terjadi kesalahan saat menyimpan data: ${xhr.responseJSON?.message || "Unknown error"}`
                );
            },
            complete: function() {
                $('#tblSimpan').prop('disabled', false);
            }
        });
    }



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
                generateTabelSisaCuti(sisaCutiAll);
                tampilkanInfoCuti(sisaCutiUser);
            })
            .catch(err => {
                console.error(err);
                tampilkanEror(err.message || "Terjadi kesalahan saat mengirim pengajuan cuti.");

            });

    }
</script>
