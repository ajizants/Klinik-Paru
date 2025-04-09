<div class="card shadow mb-4">
    <a href="#cardTransLain" class="d-block card-header py-1 bg-info" data-toggle="collapse" role="button"
        aria-expanded="true" aria-controls="cardTransLain">
        <h4 class="m-0 font-weight-bold text-dark text-center">Transaksi Pekerjaan Lain</h4>
    </a>

    <div class="collapse show" id="cardTransLain">
        <div class="card-body">
            <div class="form-group row">
                <!-- Form Input -->
                <div class="col-sm-3 p-0 card card-orange">
                    <div class="card-header">
                        <h3 class="card-title">Form Input</h3>
                    </div>
                    <div class="card-body p-2">
                        <form class="form-group col" id="formKegiatanLain">
                            @csrf
                            <div class="form-group">
                                <label for="pegawai">Nama Petugas</label>
                                <select id="pegawai" class="form-control select2bs4 border border-primary">
                                    <option value="">--Pilih Petugas--</option>
                                    @foreach (collect($pegawai)->sortBy('nama') as $item)
                                        <option value="{{ $item['nip'] }}">
                                            {{ $item['gelar_d'] }} {{ $item['nama'] }} {{ $item['gelar_b'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="tglKegiatan">Tanggal :</label>
                                <input id="tglKegiatan" type="date" class="form-control-sm col border border-primary"
                                    value="{{ now()->format('Y-m-d') }}" />
                            </div>

                            <div class="form-group">
                                <label for="kegiatan">Kegiatan</label>
                                <select id="kegiatan" class="form-control select2bs4 border border-primary">
                                    <option value="">--Pilih Kegiatan--</option>
                                    <option value="Input TCM">Input TCM</option>
                                    <option value="Input SITB">Input SITB</option>
                                    <option value="Konseling VCT">Konseling VCT</option>
                                    <option value="Lainnya">Lainnya (tambahkan di keterangan)</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="jumlah">Jumlah</label>
                                <input id="jumlah" type="number" class="form-control border border-primary"
                                    placeholder="Jumlah" required>
                            </div>
                            <div class="form-group">
                                <label for="kegLain">Keterangan/Kegiatan Lain</label>
                                <textarea id="kegLain" class="form-control border border-primary" placeholder="Keterangan/Kegiatan Lain"></textarea>
                            </div>

                            <button type="button" class="btn btn-success d-flex justify-content-center mb-4"
                                onclick="validasiKegiatanLain();">
                                Simpan Kegiatan
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Tabel Data -->
                <div class="col-sm p-0 ml-2 card card-orange">
                    <div class="card-header">
                        <h3 class="card-title">Data Transaksi Pekerjaan Lain</h3>
                    </div>
                    <div class="card-body p-2">
                        <div class="table-responsive">
                            @include('Template.Table.loading')
                            <table id="tabelKegLain" class="table table-striped" style="width:100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th class="col-1">Aksi</th>
                                        <th class="col-1">NIP</th>
                                        <th class="col-1">NAMA</th>
                                        <th class="col-1">Kegiatan</th>
                                        <th class="col-1">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Validasi dan Simpan -->
    <script type="text/javascript">
        function validasiKegiatanLain() {
            var pegawai = $('#pegawai').val();
            var tglKegiatan = $('#tglKegiatan').val();
            var kegiatan = $('#kegiatan').val();
            var kegLain = $('#kegLain').val();
            var jumlah = $('#jumlah').val();

            if (!pegawai || !tglKegiatan || !kegiatan || !jumlah) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Data belum lengkap, silahkan lengkapi terlebih dahulu',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
                return;
            }

            simpanKegiatanLain(pegawai, tglKegiatan, kegiatan, kegLain, jumlah);
        }

        async function simpanKegiatanLain(pegawai, tglKegiatan, kegiatan, kegLain, jumlah) {
            tampilkanLoading();
            $.ajax({
                url: "{{ route('tambahPekerjaanPegawai') }}",
                type: "POST",
                data: {
                    pegawai: pegawai,
                    tglKegiatan: tglKegiatan,
                    kegiatan: kegiatan,
                    kegLain: kegLain,
                    jumlah: jumlah
                },
                dataType: "JSON",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if (data.error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Perhatian',
                            text: data.error,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        });
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Data berhasil disimpan',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        });

                        // reset form setelah simpan
                        document.getElementById("formKegiatanLain").reset();
                        $('#tglKegiatan').val(new Date().toISOString().split('T')[0]);

                        // reload datatable jika ada
                        if ($.fn.DataTable.isDataTable('#tabelKegLain')) {
                            $('#tabelKegLain').DataTable().ajax.reload();
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.log(error);
                    tampilkanEror(error);
                }
            });
        }
    </script>

</div>
