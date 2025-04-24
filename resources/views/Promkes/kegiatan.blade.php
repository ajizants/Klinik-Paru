<div class="card shadow mb-4">
    <a href="#cardTransLain" class="d-block card-header py-1 bg-info" data-toggle="collapse" role="button"
        aria-expanded="true" aria-controls="cardTransLain">
        <h4 class="m-0 font-weight-bold text-dark text-center">Transaksi Kegiatan Promkes</h4>
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
                            <div class="form-group" hidden>
                                <input id="id" class="form-control border border-primary" placeholder="id"
                                    readonly>
                            </div>

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
                                <label for="pasien">Nama Pasien :</label>
                                <input id="pasien" type="text"
                                    class="form-control-sm col border border-primary" />
                            </div>
                            <div class="form-group">
                                <label for="noHp">No HP :</label>
                                <input id="noHp" type="text"
                                    class="form-control-sm col border border-primary" />
                            </div>

                            <div class="form-group form-row">
                                <div class="form-group col">
                                    <label for="td">TD :</label>
                                    <input id="td" type="text"
                                        class="form-control-sm col border border-primary" />
                                </div>
                                <div class="form-group col">
                                    <label for="nadi">Nadi :</label>
                                    <input id="nadi" type="text"
                                        class="form-control-sm col border border-primary" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="konsultasi">Konsultasi :</label>
                                <textarea id="konsultasi" class="form-control border border-primary" rows="4"
                                    placeholder="Tuliskan Data Hasil Konsultasi"></textarea>
                            </div>

                            <button type="button" class="btn btn-success d-flex justify-content-center mb-2 col"
                                onclick="validasiPromkes();">
                                Simpan Kegiatan
                            </button>

                        </form>
                    </div>
                </div>

                <!-- Tabel Data -->
                <div class="col-sm p-0 ml-sm-2 card card-lime">
                    <div class="card-header">
                        <h3 class="card-title">Data Transaksi Promkes</h3>
                    </div>
                    <div class="card-body p-2">
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
                        <div class="form-group form-row">
                            <label class="col-form-label col-sm-2">Tanggal Kegiatan:</label>
                            <div class="input-group col-sm-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control float-right" id="tglEkin">
                            </div>
                        </div>
                        <button type="button" class="btn btn-info d-flex justify-content-center mb-2 col"
                            onclick="cariDataKegiatanPerOrang();">
                            Cari Data Kegiatan
                        </button>
                        <div class="table-responsive">
                            @include('Template.Table.loading')
                            <table id="tabelKegLain" class="table table-striped" style="width:100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th class="col-1">Aksi</th>
                                        <th class="col-1">Tgl</th>
                                        <th class="col-1">No Hp</th>
                                        <th class="col-1">Nama</th>
                                        <th class="col-1">Konsultasi</th>
                                        <th class="col-1">Petugas</th>
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
        function validasiPromkes() {
            var pegawai = $('#pegawai').val();
            var pasien = $('#pasien').val();
            var konsultasi = $('#konsultasi').val();
            var td = $('#td').val();
            var nadi = $('#nadi').val();
            var noHp = $('#noHp').val();
            var id = $('#id').val();

            if (!pegawai || !pasien || !konsultasi) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Data pegawai/pasien belum lengkap, silahkan lengkapi terlebih dahulu',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
                return;
            }

            const dataPut = {
                pegawai: pegawai,
                pasien: pasien,
                konsultasi: konsultasi,
                td: td,
                nadi: nadi,
                noHp: noHp,
                id: id
            }
            const dataStore = {
                pegawai: pegawai,
                pasien: pasien,
                konsultasi: konsultasi,
                td: td,
                nadi: nadi,
                noHp: noHp,
            }

            simpan(dataPut, dataStore, id);
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

        async function simpan(dataPut, data, id) {
            console.log("🚀 ~ simpan ~ data:", data)
            // return
            tampilkanLoading();
            let url;
            let type;
            if (id != '') {
                url = "/api/ekin/";
                type = "PUT";
                data = dataPut
            } else {
                url = "/api/ekin/"
                type = "POST";
                data = dataStore
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
                        url: "api/ekin/poin/" + id,
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
                url: "api/ekin/poin/cari",
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
