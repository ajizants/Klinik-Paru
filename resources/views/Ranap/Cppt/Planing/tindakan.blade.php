<div class="container-fluid form-row mx-0 px-0 py-1">

    <!-- Kartu Input Tindakan -->
    <div class="col-sm px-0 mx-1 card card-warning">
        <div class="card-header">
            <h3 class="card-title">Input Tindakan</h3>
        </div>
        <div class="card-body p-2">
            @csrf
            <form id="formtind" class="form-group col">
                <input type="hidden" id="tindakan_norm" name="norm">
                <input type="hidden" id="tindakan_notrans" name="notrans">

                <!-- Tindakan -->
                <div class="form-group">
                    <label>Tindakan:</label>
                    <select name="tindakan_id[]" class="select2bs4 form-control border border-primary">
                        <option value="">--Pilih Tindakan--</option>
                        @foreach (collect($tindakan)->sortBy('nama') as $item)
                            <option value="{{ $item->kdTind ?? $item->idLayanan }}">
                                {{ $item->nmTindakan ?? $item->nmLayanan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tabel Obat -->
                <table class="table table-bordered" id="obatTable">
                    <thead class="thead-light">
                        <tr>
                            <th class="col-4">Obat</th>
                            <th class="col-3">Signa</th>
                            <th class="col-3">Keterangan</th>
                            <th class="col-1">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="obat-row">
                            <td>
                                <select name="obat_id[]" class="form-control-sm col-12 select2bs4">
                                    <option value="">-- Pilih Obat --</option>
                                    @foreach (collect($bmhp)->sortBy('nmObat') as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->nmObat }} (Stok: {{ $item->sisa }})
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <div class="form-row">
                                    <input type="text" name="signa_1[]" class="form-control form-control-sm col"
                                        placeholder="Signa">
                                    <label class="col-form-label col-2 text-center font-weight-bold">X</label>
                                    <input type="text" name="signa_2[]" class="form-control form-control-sm col"
                                        placeholder="Signa">
                                </div>
                            </td>
                            <td>
                                <input type="text" name="keterangan[]" class="form-control form-control-sm"
                                    placeholder="Keterangan">
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger remove-row">🗑</button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3"></td>
                            <td>
                                <button type="button" class="btn btn-success mb-2" id="addRow">➕</button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <div class="form-group form-row d-flex justify-conten-end">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Inisialisasi select2
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });

            // Tambah baris obat
            $('#addRow').click(function() {
                const newRow = `
                <tr class="obat-row">
                            <td>
                                <select name="obat_id[]" class="form-control col-12 select2bs4">
                                    <option value="">-- Pilih Obat --</option>
                                    @foreach (collect($bmhp)->sortBy('nmObat') as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->nmObat }} (Stok: {{ $item->sisa }})
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <div class="form-row">
                                    <input type="text" name="signa_1[]" class="form-control form-control-sm col"
                                        placeholder="Signa">
                                    <label class="col-form-label col-2 text-center font-weight-bold">X</label>
                                    <input type="text" name="signa_2[]" class="form-control form-control-sm col"
                                        placeholder="Signa">
                                </div>
                            </td>
                            <td>
                                <input type="text" name="keterangan[]" class="form-control form-control-sm"
                                    placeholder="Keterangan">
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger remove-row">🗑</button>
                            </td>
                        </tr>
            `;
                $('#obatTable tbody').append(newRow);
                $('.select2bs4').select2({
                    theme: 'bootstrap4'
                }); // reinit select2
            });

            // Hapus baris
            $('#obatTable').on('click', '.remove-row', function() {
                if ($('.obat-row').length > 1) {
                    $(this).closest('tr').remove();
                } else {
                    alert('Minimal 1 baris harus ada.');
                }
            });
        });
    </script>

    <!-- Kartu Rencana Tindakan -->
    <div class="col-sm px-0 mx-1 card card-warning">
        <div class="card-header">
            <h3 class="card-title">Data Rencana Tindakan</h3>
        </div>
        <div class="card-body p-2">
            <div class="table-responsive">
                <table id="dataTindakan" name="dataTindakan" class="table table-striped table-tight" style="width:100%"
                    cellspacing="0">
                    <thead>
                        <tr>
                            <th class="col-1">Aksi</th>
                            <th class="col-1">No RM</th>
                            <th class="col-3">Tindakan</th>
                            <th class="col-2">Signa</th>
                            <th class="col-2">Keterangan</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

</div>
