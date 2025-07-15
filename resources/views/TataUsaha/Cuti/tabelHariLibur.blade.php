<table class="table table-bordered table-hover dataTable dtr-inline" id="TabelDaftarHariLibur" cellspacing="0">
    <thead class="bg bg-warning table-bordered">
        <tr>
            <th>Aksi</th>
            <th>Tanggal</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody class="table-bordered">
        @foreach ($hariLiburs as $item)
            <tr>
                <td>
                    <a type="button" class="btn btn-info"
                        onclick="editHariLibur({{ $item->id }}, '{{ $item->tanggal }}', '{{ $item->keterangan }}')">
                        edit
                    </a>
                    <a type="button" class="btn btn-danger" onclick="hapusHariLibur({{ $item->id }})">
                        hapus
                    </a>
                </td>
                <td>{{ $item->tanggal }}</td>
                <td>{{ $item->keterangan }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="modal fade" id="modalHariLibur" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Form Hari Libur</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formHariLibur">
                    <input type="" id="idHariLibur" name="id">
                    <div class="form-group">
                        <label for="tanggalHariLibur">Tanggal</label>
                        <input type="date" class="form-control" id="tanggalHariLibur" name="tanggal" required>
                    </div>
                    <div class="form-group">
                        <label for="keteranganHariLibur">Keterangan</label>
                        <input type="text" class="form-control" id="keteranganHariLibur" name="keterangan" required>
                    </div>
                </form>
                <button type="button" class="btn btn-primary" onclick="simpanHariLibur()">Simpan</button>
                <hr>

                {{-- Upload Excel --}}
                <h5>Formulir Tambah Hari Libur Kolektif</h5>

                <div class="form-group">
                    <label for="file_tambahan_hari_libur">Upload Tambahan Hari Libur Multiple</label>
                    <input type="file" name="file_tambahan_hari_libur" id="file_tambahan_hari_libur"
                        accept=".xls,.xlsx" class="form-control">
                </div>

                <div class="form-group d-flex justify-content-between">
                    <button type="button" class="btn btn-primary" onclick="uploadTambahanHariLibur(event)"
                        id="tblUploadHariLibur">
                        Upload
                    </button>
                    <a href="https://docs.google.com/spreadsheets/d/1-mxBJrTWcQcZEPD2xlnBqRht6T9BYEn4/edit?usp=sharing&ouid=109021928107189827550&rtpof=true&sd=true"
                        target="_blank" class="btn btn-warning">
                        Download Template
                    </a>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#TabelDaftarHariLibur').DataTable();
    });

    function editHariLibur(id, tanggal, keterangan) {
        $('#idHariLibur').val(id);
        $('#tanggalHariLibur').val(tanggal);
        $('#keteranganHariLibur').val(keterangan);
        $('#modalHariLibur').modal('show');
    }

    function simpanHariLibur() {
        const id = $('#idHariLibur').val();
        console.log("🚀 ~ simpanHariLibur ~ id:", id)
        let url = "/tu/hariLibur/tambah  "
        if (id !== "") {
            url = "/tu/hariLibur/update/" + id
        }
        $.ajax({
            url: url,
            type: "POST",
            data: $('#formHariLibur').serialize(),
            success: function(data) {
                generateTabelHariLibur(data.html);
                $('#modalHariLibur').modal('hide');
            },
            error: function(xhr) {
                tampilkanEror(xhr.responseJSON.message)
            }
        })
    }

    function hapusHariLibur(id) {
        $.ajax({
            url: "/tu/hariLibur/hapus/" + id,
            type: "POST",
            success: function(data) {
                generateTabelHariLibur(data.html);
            },
            error: function(xhr) {
                tampilkanEror(xhr.responseJSON.message)
            }
        })
    }

    function cariDataHariLibur($tahun) {
        $.ajax({
            url: "/tu/hariLibur/get/" + $tahun,
            type: "GET",
            success: function(data) {
                generateTabelHariLibur(data.html);
            },
            error: function(xhr) {
                tampilkanEror(xhr.responseJSON.message)
            }
        })
    }

    function uploadTambahanHariLibur(event) {
        event.preventDefault();
        const file = $('#file_tambahan_hari_libur')[0].files[0];
        const formData = new FormData();
        formData.append('file_tambahan_hari_libur', file);
        $.ajax({
            url: "/tu/hariLibur/upload",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                generateTabelHariLibur(data.html);
                $('#modalHariLibur').modal('hide');
            },
            error: function(xhr) {
                tampilkanEror(xhr.responseJSON.message)
            }
        })
    }
</script>
