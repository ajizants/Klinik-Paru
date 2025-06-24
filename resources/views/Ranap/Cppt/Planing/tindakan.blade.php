<div class="container-fluid form-row mx-0 px-0 py-1">

    <!-- Kartu Input Tindakan -->
    <div class="col-sm px-0 mx-1 card card-warning">
        <div class="card-header">
            <h3 class="card-title">Input Tindakan</h3>
        </div>
        <div class="card-body p-2">
            {{-- @csrf --}}
            <div id="formtind" class="form-group col">
                <div>
                    <input type="text" id="tindakan_norm" name="norm">
                    <input type="text" id="tindakan_notrans" name="notrans">
                    <input type="text" id="tindakan_form_id" name="form_id">
                </div>

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
                    <button onclick="orderTindakan()" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>

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


<script>
    // function orderTindakan() {
    //     const form = document.getElementById("formtind");
    //     const formData = new FormData(form);

    //     $.ajax({
    //         url: "/api/ranap/order_tindakan",
    //         method: "POST",
    //         data: formData,
    //         processData: false,
    //         contentType: false,
    //         headers: {
    //             "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    //         },
    //         success: function(res) {
    //             console.log("✅ Respon:", res);
    //             Swal.fire("Sukses", "Data berhasil disimpan", "success");
    //             // loadCpptTable(res.notrans);
    //         },
    //         error: function(xhr) {
    //             console.error("❌ Error:", xhr.responseText);
    //             Swal.fire("Gagal", "Terjadi kesalahan saat menyimpan", "error");
    //         },
    //     });
    // }

    function orderTindakan() {
        const data = {
            norm: $("#tindakan_norm").val(),
            notrans: $("#tindakan_notrans").val(),
            form_id: $("#tindakan_form_id").val(),
            tindakan_id: [],
            obat_id: [],
            signa_1: [],
            signa_2: [],
            keterangan: [],
        };

        // Loop semua baris obat
        $("#obatTable tbody tr").each(function() {
            data.obat_id.push($(this).find('select[name="obat_id[]"]').val());
            data.signa_1.push($(this).find('input[name="signa_1[]"]').val());
            data.signa_2.push($(this).find('input[name="signa_2[]"]').val());
            data.keterangan.push($(this).find('input[name="keterangan[]"]').val());
        });

        // Ambil semua select tindakan
        $('select[name="tindakan_id[]"]').each(function() {
            data.tindakan_id.push($(this).val());
        });

        $.ajax({
            url: "/api/ranap/order_tindakan",
            method: "POST",
            data: data,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function(res) {
                console.log("✅ Respon:", res);
                Swal.fire("Sukses", "Data berhasil disimpan", "success");
            },
            error: function(xhr) {
                console.error("❌ Error:", xhr.responseText);
                Swal.fire("Gagal", "Terjadi kesalahan saat menyimpan", "error");
            },
        });
    }


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
