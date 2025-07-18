<!-- Accordion -->

<div class="accordion" id="divFormAddObat">
    <div class="card card-success">
        <a class="btn btn-link text-left w-100" type="button" data-toggle="collapse" id="headingOne"
            data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
            <strong>Klik Untuk Menambahkan Obat/BMHP</strong>
        </a>
        <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#divFormAddObat">
            <div class="card-body">
                <form class="form-horizontal" id="formAddObat">
                    <div class="row">
                        <div class="form-group col-4">
                            <label for="gObat" class="form-label pt-2"> Cari
                                Obat </label>
                            <div name="" id="obats">
                                <select type="text" name="idObat" id="idObat"
                                    class="form-control select2bs4 foem-control" placeholder="ID Obat">
                                    <option value="">--- Pilih obat ---</option>
                                </select>
                            </div>
                            <label for="stokBaru" class="form-label pt-2"> Jumlah* </label>
                            <div class="col p-0">
                                <input type="text" name="stokBaru" id="stokBaru"
                                    class="form-control form-control-sm col" placeholder=" Jumlah Stock Barang Baru"
                                    required>
                            </div>
                            <label for="hargaBeli" class="form-label pt-2"> Harga
                                Beli* </label>
                            <div class="col p-0">
                                <input type="text" name="hargaBeli" id="hargaBeli"
                                    class="form-control form-control-sm col" placeholder="Harga Beli" required
                                    value="5000">
                            </div>
                            <label for="hargaJual" class="form-label pt-2"> Harga
                                Jual* </label>
                            <div class="col p-0">
                                <input type="text" name="hargaJual" id="hargaJual"
                                    class="form-control form-control-sm col" placeholder="Harga Jual" required
                                    value="10000">
                            </div>
                        </div>
                        <div class="form-group col-4">
                            <label for="nmObat" class="form-label pt-2">
                                Nama Obat </label>
                            <div class="">
                                <input type="text" name="nmObat" id="nmObat"
                                    class="form-control form-control-sm col" placeholder="Nama Obat" readonly>
                            </div>
                            <label for="jenis" class="form-label pt-2"> Jenis*
                            </label>
                            <div class="">
                                <select name="jenis" id="jenis" class="form-control select2bs4 21 foem-control">
                                    <option value="">--- Pilih Jenis ---</option>
                                    <option value="1">Obat</option>
                                    <option value="2">Bahan Medis Habis Pakai/BMHP
                                    </option>
                                </select>
                            </div>
                            <label for="supplier" class="form-label pt-2">
                                Supplier* </label>
                            <div class="">
                                <input name="supplier" id="supplier" class="form-control form-control-sm col" required
                                    value="1">
                            </div>
                            <label for="pabrikan" class="form-label pt-2">
                                Pabrikan Obat* </label>
                            <div class="">
                                <input name="pabrikan" id="pabrikan" class="form-control form-control-sm col"
                                    value="10" required readonly>
                            </div>
                        </div>
                        <div class="form-group col-4">
                            <label for="sediaan" class="form-label pt-2">
                                Sediaan*
                            </label>
                            <div class="row">
                                <input name="sediaan" id="sediaan" class="form-control form-control-sm col"
                                    required />
                                <input name="product_id" id="product_id" class="form-control form-control-sm col-3"
                                    required />
                            </div>
                            <label for="sumberObat" class="form-label pt-2">
                                Sumber Obat* </label>
                            <div class="">
                                <input name="sumber" id="sumberObat" class="form-control form-control-sm col"
                                    required value="Gudang Farmasi" />
                            </div>
                            <label for="tglBeli" class="form-label pt-2">
                                Tanggal Pembelian* </label>
                            <div class="col p-0">
                                <input type="date" name="tglPembelian" id="tglBeli"
                                    class="form-control form-control-sm col" required
                                    value="{{ now()->format('Y-m-d') }}">
                            </div>
                            <label for="tglED" class="form-label pt-2">
                                Tanggal Kedaluwarsa* </label>
                            <div class="col p-0">
                                <input type="date" name="ed" id="tglED"
                                    class="form-control form-control-sm col" required>
                            </div>
                        </div>
                    </div>
                </form>
                <button type="button" class="btn btn-primary" id="simpanObat" onclick="simpanObat()">Simpan
                    Obat/BMHP</button>
            </div>
        </div>
    </div>
</div>

<div class="card card-info" id="dBmhp">
    <div class="card-header">
        <h3 class="card-title">Stok Obat IGD</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <form class="form-horizontal">
        <div class="card-body">
            <div class=" border border-black">
                <div class="card-body card-body-hidden p-2">
                    <table id="BMHP" class="table table-striped fs-6" style="width:100%" cellspacing="0">
                        <thead class="table-secondary table-sm">
                            <tr>
                                <th>Aksi</th>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Jenis</th>
                                <th>Pabrikan</th>
                                <th>Sediaan</th>
                                <th>Sumber</th>
                                <th>Suplier</th>
                                <th>Tgl Beli</th>
                                <th>Tgl ED</th>
                                <th>Harga Beli</th>
                                <th>Harga Jual</th>
                                <th>Stok Awal</th>
                                <th>Masuk</th>
                                <th>Keluar</th>
                                <th>Stok Akhir</th>
                            </tr>
                        </thead>
                    </table>
                    @include('Template.Table.loading')
                </div>
            </div>
        </div>
    </form>
</div>


<script>
    $(document).ready(function() {
        $('#idObat').select2({
            theme: 'bootstrap4',
            placeholder: '--- Pilih obat ---',
            allowClear: true,
            ajax: {
                url: 'http://kkpm.local/api/kominfo/get_master_obat',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        namaObat: params.term
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.map(function(item) {
                            return {
                                id: item.id,
                                product_id: item.kode_obat,
                                text: item.nama_obat, // Yang ditampilkan
                                sediaan: item.nama_bentuk, // Data tambahan
                                nama_obat: item.nama_obat,
                                jenis_obat_nama: item.jenis_obat_nama
                            };
                        })
                    };
                },
                cache: true
            },
            minimumInputLength: 2
        });

        $('#idObat').on('select2:select', function(e) {
            const data = e.params.data;
            let jenisItem = 1 //1 = obat, 2 = bmhp
            if (data.jenis_obat_nama == 'BMHP') {
                jenisItem = 2
            }
            // Isi input lainnya
            $('#nmObat').val(data.nama_obat || '');
            $('#sediaan').val(data.sediaan || '');
            $('#product_id').val(data.product_id || '');
            $('#jenis').val(jenisItem || '');
            $('#jenis').trigger('change');
        });

        // Clear field saat user clear select2
        $('#idObat').on('select2:clear', function() {
            $('#nmObat').val('');
            $('#sediaan').val('');
            $('#product_id').val('');
            $('#jenis').val('');
            $('#jenis').trigger('change');
        });
    });

    function simpanObat() {
        const form = document.getElementById("formAddObat");
        const formData = new FormData(form);
        console.log("🚀 ~ simpanObat ~ formData:", formData);
        tampilkanLoading();
        $.ajax({
            url: '/api/addJenisBmhp',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                console.log("🚀 ~ simpanObat ~ response:", response);
                if (response.success == true) {
                    tampilkanSukses(response.message);
                    loadTindakan();
                    loadBMHP();
                } else {
                    tampilkanEror(response.message);
                }
            },
            error: function(xhr, status, error) {
                const response = xhr.responseJSON;

                if (response && response.errors) {
                    let pesan = "Terjadi kesalahan validasi:\n";

                    Object.keys(response.errors).forEach(function(key) {
                        response.errors[key].forEach(function(msg) {
                            pesan += "- " + msg + "\n";
                        });
                    });

                    tampilkanEror(
                        pesan); // custom function kamu untuk tampilkan error, misal pakai Swal atau alert
                } else {
                    tampilkanEror("Terjadi kesalahan saat menyimpan data. Silakan coba lagi.");
                }

                console.error("Detail error:", response);
            }

        });

    }
</script>
