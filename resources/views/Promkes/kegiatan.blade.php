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
                                {{-- <label for="pegawai">Nama Petugas</label> --}}
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
                                {{-- <label for="pasien">Nama Pasien :</label> --}}
                                <input id="pasien" type="text" placeholder="Nama Pasien"
                                    class="form-control-sm col border border-primary" />
                            </div>
                            <div class="form-group">
                                {{-- <label for="noHp">No HP :</label> --}}
                                <input id="noHp" type="text" placeholder="No HP"
                                    class="form-control-sm col border border-primary" />
                            </div>

                            <div class="form-group form-row">
                                <div class="form-group col">
                                    <div class="input-group input-group-sm">
                                        <input type="text" inputmode="numeric" id="td"
                                            class="form-control col border border-primary"
                                            aria-describedby="inputGroup-sizing-sm" placeholder="TD" step="1" />
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                mmHg
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col">
                                    <div class="input-group input-group-sm">
                                        <input type="text" inputmode="numeric" id="nadi"
                                            class="form-control col border border-primary"
                                            aria-describedby="inputGroup-sizing-sm" placeholder="Nadi" step="1" />
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                X/mnt
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                {{-- <label for="konsultasi">Konsultasi :</label> --}}
                                <textarea id="konsultasi" class="form-control border border-primary" rows="4"
                                    placeholder="Tuliskan hasil konsultasi atau pemeriksaan lain di sini"></textarea>
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
                            onclick="cariDataKegiatan(tglAwal, tglAkhir);">
                            Cari Data Kegiatan
                        </button>
                        <div class="table-responsive">
                            @include('Template.Table.loading')
                            <table id="tabelKegLain" class="table table-striped" style="width:100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th class="col-1">Aksi</th>
                                        <th class="col-1">Tgl</th>
                                        <th class="col-1">Nama</th>
                                        <th class="col-1">No Hp</th>
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
        function drawTabelKegiatanLain(dataTabel) {
            if ($.fn.DataTable.isDataTable('#tabelKegLain')) {
                $('#tabelKegLain').DataTable().clear().destroy();
            }

            $('#tabelKegLain').DataTable({
                data: dataTabel,
                columns: [{
                        data: 'aksi'
                    },
                    {
                        data: 'tanggal'
                    },
                    {
                        data: 'pasien'
                    },
                    {
                        data: 'noHp'
                    },
                    {
                        data: 'hasilPromkes'
                    },
                    {
                        data: 'petugas.biodata.nama'
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
                _method: 'PUT',
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

        async function simpan(dataPut, dataStore, id) {
            // return
            let url;
            let data;
            if (id != '') {
                url = "/api/promkes/" + id;
                data = dataPut
                tampilkanLoading('Mengubah data...');
            } else {
                url = "/api/promkes"
                data = dataStore
                tampilkanLoading('Menyimpan data...');
            }
            $.ajax({
                url: url,
                type: "POST",
                data: data,
                dataType: "JSON",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    console.log("🚀 ~ simpanKegiatanLain ~ data:", data)
                    if (data.error) {
                        tampilkanEror(data.error);
                    } else {
                        tampilkanSuccess(data.message);

                        // reset form setelah simpan
                        document.getElementById("formKegiatanLain").reset();
                        $('#pegawai').trigger('change');


                        const dataTabel = data.table || [];
                        drawTabelKegiatanLain(dataTabel);
                    }
                },
                error: function(xhr, status, error) {
                    console.log(error);
                    tampilkanEror(xhr.responseJSON);
                }
            });
        }

        function editPromkes(button) {
            console.log("🚀 ~ editPromkes ~ button:", button)
            var id = $(button).attr('data-id');
            var pegawai = $(button).attr('data-pegawai');
            var pasien = $(button).attr('data-pasien');
            var noHp = $(button).attr('data-noHp');
            var td = $(button).attr('data-td');
            var nadi = $(button).attr('data-nadi');
            var konsultasi = $(button).attr('data-konsultasi');

            $('#id').val(id);
            $('#pegawai').val(pegawai).trigger('change');
            $('#pasien').val(pasien);
            $('#noHp').val(noHp);
            $('#td').val(td);
            $('#nadi').val(nadi);
            $('#konsultasi').val(konsultasi);
            scrollToTop();
        }

        function deletePromkes(id) {

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
                        url: "api/promkes/" + id,
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
                                tampilkanSuccess(data.delete);
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
                                drawTabelKegiatanLain([]);
                            } else if (xhr.status === 500) {
                                errorMessage = 'Kesalahan server. Silakan coba lagi nanti.';
                            }

                            tampilkanEror(errorMessage);
                        }
                    });
                }
            })
        }

        function cariDataKegiatan(tglAwal, tglAkhir) {
            console.log("🚀 ~ cariDataKegiatan ~ tglAkhir:", tglAkhir)
            console.log("🚀 ~ cariDataKegiatan ~ tglAwal:", tglAwal)


            $.ajax({
                url: "api/promkes/cari",
                type: "POST",
                dataType: "JSON",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    tglAwal: tglAwal,
                    tglAkhir: tglAkhir
                },
                success: function(data) {
                    console.log("🚀 ~ cariDataKegiatanPerOrang ~ data:", data);
                    const dataTabel = data.data;
                    drawTabelKegiatanLain(dataTabel);
                },
                error: function(xhr, status, error) {
                    console.error("❌ ~ Error Hapus:", error);
                    // Coba ambil pesan dari respon jika tersedia
                    let errorMessage = 'Terjadi kesalahan saat mencari data.' + error + xhr.responseJSON;
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
        console.log("🚀 ~ dataKegiatan:", dataKegiatan)
        document.addEventListener('DOMContentLoaded',
            function() {
                drawTabelKegiatanLain(dataKegiatan);
            })
    </script>

</div>
