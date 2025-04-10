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
                                    <option value="Penyuluhan">Penyuluhan</option>
                                    <option value="Penanganan Pasien Hemaptoe">Penanganan Pasien Hemaptoe</option>
                                    <option value="Input TCM">Input TCM</option>
                                    <option value="Input SITB">Input SITB</option>
                                    <option value="Konseling VCT">Konseling VCT</option>
                                    <option value="Konsultasi Pasien">Konsultasi Pasien</option>
                                    <option value="Lainnya">Lainnya (tambahkan di keterangan)</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="jumlah">Jumlah</label>
                                <div class="row">
                                    <input id="idKegiatan" class="col-2 form-control border border-primary"
                                        placeholder="idKegiatan" readonly hidden>
                                    <input id="jumlah" type="number" class="col form-control border border-primary"
                                        placeholder="Jumlah" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="kegLain">Keterangan/Kegiatan Lain</label>
                                <textarea id="kegLain" class="form-control border border-primary" placeholder="Keterangan/Kegiatan Lain"></textarea>
                            </div>

                            <button type="button" class="btn btn-success d-flex justify-content-center mb-2 col"
                                onclick="validasiKegiatanLain();">
                                Simpan Kegiatan
                            </button>
                            <div class="accordion" id="accordionExample">
                                <div class="card">
                                    <a class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                        id="headingOne" data-target="#caraCariDataKegiatan" aria-expanded="true"
                                        aria-controls="caraCariDataKegiatan">
                                        <strong>Klik Untuk Melihat Cara Pencarian Data Kegiatan</strong>
                                    </a>
                                    <div id="caraCariDataKegiatan" class="collapse" aria-labelledby="headingOne"
                                        data-parent="#accordionExample">
                                        <div class="card-body p-1">
                                            <ul class="list-decimal list-outside space-y-2 ml-6">
                                                <li>Pertama pilih Nama Petugas</li>
                                                <li>Lalu, silahkan pilih tanggal bebas, patokannya adalah bulannya.
                                                </li>
                                                <li><strong>Contoh:</strong> Jika ingin mencari data Kegiatan Bulan
                                                    Maret 2025 maka
                                                    bisa memilih tanggal 10 Maret 2025 atau 25 Maret 2025</li>
                                                <li>Lalu Klik Tombol "Cari Data Kegiatan"</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-info d-flex justify-content-center mb-2 col"
                                onclick="cariDataKegiatanPerOrang();">
                                Cari Data Kegiatan
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
                                        <th class="col-1">Tgl</th>
                                        <th class="col-1">NIP</th>
                                        <th class="col-1">NAMA</th>
                                        <th class="col-1">KEGIATAN</th>
                                        <th class="col-1">QTY</th>
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
            var idKegiatan = $('#idKegiatan').val();

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

            simpanKegiatanLain(pegawai, tglKegiatan, kegiatan, kegLain, jumlah, idKegiatan);
        }

        function drawTabelKegiatanLain(dataTabel) {
            if ($.fn.DataTable.isDataTable('#tabelKegLain')) {
                $('#tabelKegLain').DataTable().clear().destroy();
            }
            const role = $("#roleUser").val();
            dataTabel.forEach(item => {
                if (role !== 'admin') {
                    item.aksi = "Hub admin";
                }
            })
            $('#tabelKegLain').DataTable({
                data: dataTabel,
                columns: [{
                        data: 'aksi'
                    },
                    {
                        data: 'tanggal'
                    },
                    {
                        data: 'nip'
                    },
                    {
                        data: 'nama'
                    },
                    {
                        data: 'kegiatan'
                    },
                    {
                        data: 'jumlah'
                    },
                ],
                paging: true,
                order: [
                    [1, "asc"]
                ],
                lengthMenu: [
                    [5, 10, 25, 50, -1],
                    [5, 10, 25, 50, "All"]
                ],
                pageLength: 5,
                responsive: true,
                autoWidth: false,
                scrollX: true
            });
        }

        async function simpanKegiatanLain(pegawai, tglKegiatan, kegiatan, kegLain, jumlah, idKegiatan) {
            tampilkanLoading();
            let url;
            let type;
            let data = [];
            if (idKegiatan != '') {
                url = "{{ route('updatePekerjaanPegawai') }}";
                type = "PUT";
                data = {
                    pegawai: pegawai,
                    tglKegiatan: tglKegiatan,
                    kegiatan: kegiatan,
                    kegLain: kegLain,
                    jumlah: jumlah,
                    id: idKegiatan
                }
            } else {
                url = "{{ route('tambahPekerjaanPegawai') }}";
                type = "POST";
                data = {
                    pegawai: pegawai,
                    tglKegiatan: tglKegiatan,
                    kegiatan: kegiatan,
                    kegLain: kegLain,
                    jumlah: jumlah
                }
            }
            $.ajax({
                url: url,
                type: type,
                data: data,
                dataType: "JSON",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    console.log("🚀 ~ simpanKegiatanLain ~ data:", data)
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
                        $('#pegawai').trigger('change');
                        $('#kegiatan').trigger('change');


                        const dataTabel = data.table;
                        drawTabelKegiatanLain(dataTabel);
                    }
                },
                error: function(xhr, status, error) {
                    console.log(error);
                    tampilkanEror(error);
                }
            });
        }

        function editKegiatan(button) {
            console.log("🚀 ~ editKegiatan ~ button:", button)
            var id = $(button).attr('data-id');
            var pegawai = $(button).attr('data-nip');
            var tglKegiatan = $(button).attr('data-tanggal');
            var kegiatan = $(button).attr('data-kegiatan');
            var kegLain = $(button).attr('data-keterangan');
            var jumlah = $(button).attr('data-jumlah');

            $('#idKegiatan').val(id);
            $('#pegawai').val(pegawai).trigger('change');
            $('#tglKegiatan').val(tglKegiatan);
            $('#kegiatan').val(kegiatan).trigger('change');
            $('#kegLain').val(kegLain);
            $('#jumlah').val(jumlah);

        }

        function deleteKegiatan(id) {

            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Data akan dihapus secara permanen",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('hapusPekerjaanPegawai', '') }}/" + id,
                        type: "DELETE",
                        dataType: "JSON",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            console.log("🚀 ~ hapusKegiatan ~ data:", data);
                            if (data.error) {
                                tampilkanEror(data.error);
                            } else {
                                tampilkanSuccess('Data berhasil dihapus');
                                const dataTabel = data.table;
                                drawTabelKegiatanLain(dataTabel);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("❌ ~ Error Hapus:", error);
                            // Coba ambil pesan dari respon jika tersedia
                            let errorMessage = 'Terjadi kesalahan saat menghapus data.' + error;
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            } else if (xhr.status === 404) {
                                errorMessage = 'Data tidak ditemukan.';
                            } else if (xhr.status === 500) {
                                errorMessage = 'Kesalahan server. Silakan coba lagi nanti.';
                            }

                            tampilkanEror(errorMessage);
                        }
                    });
                }
            })
        }

        function cariDataKegiatanPerOrang() {
            let pegawai = $('#pegawai').val();
            let tglKegiatan = $('#tglKegiatan').val();
            let roleUser = $('#roleUser').val();
            console.log("🚀 ~ cariDataKegiatanPerOrang ~ pegawai:", pegawai);
            console.log("🚀 ~ cariDataKegiatanPerOrang ~ tglKegiatan:", tglKegiatan);

            $.ajax({
                url: "{{ route('cariPekerjaanPegawai') }}",
                type: "POST",
                dataType: "JSON",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    pegawai: pegawai,
                    tglKegiatan: tglKegiatan,
                    roleUser: roleUser
                },
                success: function(data) {
                    console.log("🚀 ~ cariDataKegiatanPerOrang ~ data:", data);
                    const dataTabel = data.table;
                    drawTabelKegiatanLain(dataTabel);
                },
                error: function(xhr, status, error) {
                    console.error("❌ ~ Error Hapus:", error);
                    // Coba ambil pesan dari respon jika tersedia
                    let errorMessage = 'Terjadi kesalahan saat menghapus data.' + error;
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.status === 404) {
                        errorMessage = 'Data tidak ditemukan.';
                    } else if (xhr.status === 500) {
                        errorMessage = 'Kesalahan server. Silakan coba lagi nanti.';
                    }

                    tampilkanEror(errorMessage);
                }
            });
        }

        let dataKegiatan = @json($hasilKegiatan);
        document.addEventListener('DOMContentLoaded',
            function() {
                drawTabelKegiatanLain(dataKegiatan);
            })
    </script>

</div>
