<div class="container-fluid form-row mx-0 px-0 py-1">

    <!-- Kartu Input Tindakan -->
    <div class="col-sm px-0 mx-1 card card-warning">
        <div class="card-header">
            <h3 class="card-title">Input Tindakan</h3>
        </div>
        <div class="card-body p-2">
            {{-- @csrf --}}
            <div id="formtind" class="form-group col">
                <div hidden>
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
                            <option value="{{ $item->idLayanan }}">
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
                                <select name="obat_id[]" id="obat_id"
                                    class="form-control-sm col-12 select2bs4 select2bs4Obat">
                                    <option value="">-- Pilih Obat --</option>
                                </select>
                                <input type="" id="obat_nama" name="obat_nama[]">
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
                    <a type="button" onclick="orderTindakan()" class="btn btn-primary">Simpan</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Kartu Rencana Tindakan -->
    <div class="col-sm px-0 mx-1 card card-success">
        <div class="card-header">
            <h3 class="card-title">Data Rencana Tindakan</h3>
        </div>
        <div class="card-body p-2">
            {{-- <div class="table-responsive"> --}}
            <table id="dataTindakan" name="dataTindakan" class="table table-striped table-tight" style="width:100%"
                cellspacing="0">
                <thead>
                    <tr>
                        <th class="col-1">Aksi</th>
                        <th class="col-3">Tindakan</th>
                        <th class="col-3">Obat</th>
                        <th class="col-2">Signa</th>
                        <th class="col-2">Keterangan</th>
                    </tr>
                </thead>
            </table>
            {{-- </div> --}}
        </div>
    </div>

</div>


<script>
    function orderTindakan() {
        tampilkanLoading('Sedang menyimpan data...');
        const data = {
            norm: $("#tindakan_norm").val(),
            notrans: $("#tindakan_notrans").val(),
            form_id: $("#tindakan_form_id").val(),
            petugas: $("#petugas").val(),
            tindakan_id: [],
            obat_id: [],
            obat_nama: [],
            signa_1: [],
            signa_2: [],
            keterangan: [],
        };

        // Loop semua baris obat
        $("#obatTable tbody tr").each(function() {
            data.obat_id.push($(this).find('select[name="obat_id[]"]').val());
            data.obat_nama.push($(this).find('input[name="obat_nama[]"]').val());
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
                isiTabelTindakan(res.data);
                Swal.fire("Sukses", "Data berhasil disimpan", "success");
            },
            error: function(xhr) {
                console.error("❌ Error:", xhr.responseText);
                Swal.fire("Gagal", "Terjadi kesalahan saat menyimpan", "error", xhr.responseText);
            },
        });
    }

    function isiTabelTindakan(data) {
        console.log(data);
        $("#dataTindakan").DataTable({
            destroy: true,
            data: data,
            columns: [{
                    data: "id",
                    render: function(data) {
                        return `
                        <a type="button" class="btn btn-sm btn-danger" onclick="deleteTindakan('${data}')">
                            <i class="fa fa-trash"></i>
                        </a>`;
                    }
                },
                {
                    data: "nmLayanan",
                    defaultContent: "-"
                },
                {
                    data: "nmObat",
                    defaultContent: "-",
                    render: function(data) {
                        return data ?? "-";
                    }
                },
                {
                    data: "signa",
                    defaultContent: "-",
                    render: function(data) {
                        return data ?? "-";
                    }
                },
                {
                    data: "ket",
                    defaultContent: "-",
                    render: function(data) {
                        return data ?? "-";
                    }
                }
            ],
            createdRow: function(row, data) {
                $(row).attr("id", "row_" + data.id);
            },
            order: [1, "asc"],
            scrollY: "300px",
            paging: false,
        });
    }

    // Inisialisasi Select2 dengan AJAX pada elemen baru
    function initSelect2(el) {
        el.select2({
            theme: 'bootstrap4',
            placeholder: 'Cari Obat...',
            ajax: {
                url: '/api/kominfo/data_obat/get_data',
                dataType: 'json',
                delay: 50,
                data: function(params) {
                    return {
                        search: params.term
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.map(function(item) {
                            return {
                                id: item.id,
                                text: item.jenis_obat_nama ?
                                    item.nama_obat + ' - ' + item.jenis_obat_nama : item
                                    .nama_obat, // ← kalau kosong, cukup tampilkan nama_obat saja
                                nama_obat: item.nama_obat
                            };
                        })
                    };
                },
                cache: true
            }
        });

        // Tangkap perubahan dan simpan nama_obat ke input hidden dalam baris yang sama
        el.on('select2:select', function(e) {
            const selected = e.params.data;
            const row = $(this).closest('tr');
            if (selected.id == "0") {
                row.find('input[name="obat_nama[]"]').val("");
            } else {
                row.find('input[name="obat_nama[]"]').val(selected.nama_obat);
            }
        });
    }

    function deleteTindakan(id) {
        $.ajax({
            url: "/api/ranap/order_tindakan/" + id,
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function(response) {
                console.log('Berhasil:', response);
                tampilkanSukses(response.message);
                isiTabelPenunjang(response.data);
            },
            error: function(xhr) {
                console.error('Gagal:', xhr.responseText);
                tampilkanEror(xhr.responseText);
            }
        });
    }




    $(document).ready(function() {
        // Inisialisasi select2
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        });

        // Tambah baris obat
        // $('#addRow').click(function() {
        //     const newRow = `
        //             <tr class="obat-row">
        //                 <td>
        //                     <select name="obat_id[]" id="obat_id" class="form-control-sm col-12 select2bs4">
        //                         <option value="">-- Pilih Obat --</option>
        //                     </select>
        //                     <input type="" id="obat_nama" name="obat_nama[]">
        //                 </td>
        //                 <td>
        //                     <div class="form-row">
        //                         <input type="text" name="signa_1[]" class="form-control form-control-sm col"
        //                             placeholder="Signa">
        //                         <label class="col-form-label col-2 text-center font-weight-bold">X</label>
        //                         <input type="text" name="signa_2[]" class="form-control form-control-sm col"
        //                             placeholder="Signa">
        //                     </div>
        //                 </td>
        //                 <td>
        //                     <input type="text" name="keterangan[]" class="form-control form-control-sm"
        //                         placeholder="Keterangan">
        //                 </td>
        //                 <td>
        //                     <button type="button" class="btn btn-danger remove-row">🗑</button>
        //                 </td>
        //             </tr>
        //             `;
        //     $('#obatTable tbody').append(newRow);
        //     $('.select2bs4').select2({
        //         theme: 'bootstrap4'
        //     }); // reinit select2
        // });

        // Tambah baris obat
        $('#addRow').click(function() {
            const newRow = `
                                    <tr class="obat-row">
                                        <td>
                                            <select name="obat_id[]" class="form-control-sm col-12 select2bs4">
                                                <option value="">-- Pilih Obat --</option>
                                            </select>
                                            <input type="" name="obat_nama[]">
                                        </td>
                                        <td>
                                            <div class="form-row">
                                                <input type="text" name="signa_1[]" class="form-control form-control-sm col" placeholder="Signa">
                                                <label class="col-form-label col-2 text-center font-weight-bold">X</label>
                                                <input type="text" name="signa_2[]" class="form-control form-control-sm col" placeholder="Signa">
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" name="keterangan[]" class="form-control form-control-sm" placeholder="Keterangan">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger remove-row">🗑</button>
                                        </td>
                                    </tr>`;
            const $row = $(newRow).appendTo('#obatTable tbody');
            initSelect2($row.find('select'));
        });



        // Hapus baris
        $('#obatTable').on('click', '.remove-row', function() {
            if ($('.obat-row').length > 1) {
                $(this).closest('tr').remove();
            } else {
                alert('Minimal 1 baris harus ada.');
            }
        });

        // $('#obat_id').select2({
        //     placeholder: 'Cari Obat...',
        //     ajax: {
        //         url: '/api/kominfo/data_obat/get_data',
        //         dataType: 'json',
        //         delay: 50,
        //         data: function(params) {
        //             return {
        //                 search: params.term,
        //             };
        //         },
        //         processResults: function(data) {
        //             return {
        //                 results: data.map(function(item) {
        //                     return {
        //                         id: item.id,
        //                         text: item.nama_obat + ' - ' + item.jenis_obat_nama,
        //                         nama_obat: item.nama_obat
        //                     };
        //                 })
        //             };
        //         },
        //         cache: true
        //     }
        // });
        // // Tangkap perubahan dan simpan nama_obat ke input hidden
        // $('#obat_id').on('select2:select', function(e) {
        //     const selected = e.params.data;
        //     $('#obat_nama').val(selected.nama_obat);
        // });



        // Inisialisasi Select2 pada semua elemen dengan kelas .select2bs4
        $('.select2bs4Obat').each(function() {
            initSelect2($(this));
        });

    });
</script>
