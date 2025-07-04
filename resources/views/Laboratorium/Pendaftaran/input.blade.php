<div class="container-fluid">
    <div class="card card-lime">
        <div class="card-header">
            <h4 class="card-title">Identitas</h4>
        </div>
        @csrf
        <form class="form-horizontal" id="form_identitas">
            <div class="card-body" id="inputSection">
                <div class="form-group row">
                    <label for="norm" class="col-sm-1 col-form-label font-weight-bold mb-0 ">No RM
                        :</label>
                    <div class="col-sm-2 input-group" style="overflow: hidden;">
                        <input type="text" name="norm" id="norm" class="form-control" placeholder="No RM"
                            maxlength="6" pattern="[0-9]{6}" required />
                        <button class="btn btn-warning" onclick="obatSajaIdentitas()">TCM</button>
                    </div>
                    <label for="layanan" class="col-sm-1 col-form-label font-weight-bold mb-0">Layanan
                        :</label>
                    <div class="col-sm-2">
                        <input type="text" id="layanan" class="form-control bg-white" placeholder="Layanan"
                            readonly />
                    </div>
                    <label for="nama" class="col-sm-1 col-form-label font-weight-bold  mb-0">Nama
                        :</label>
                    <div class="col-sm-3">
                        <input type="text" id="nama" class="form-control bg-white" placeholder="Nama Pasien"
                            readonly>
                    </div>
                    <div class="col-sm-1">
                        {{-- <input type="text" id="jk" class="form-control bg-white" placeholder="JK"> --}}
                        <Select type="text" id="jk" class="form-control bg-white" placeholder="JK">
                            <option value="">--JK--</option>
                            <option value="L">Laki-Laki</option>
                            <option value="P">Perempuan</option>
                        </Select>
                    </div>
                    <div class="col-sm-1">
                        <input type="text" id="umur" class="form-control bg-white" placeholder="Umur">
                    </div>
                </div>
                <div class="form-group row mt-2">
                    <label for="tgltrans" class="col-sm-1 col-form-label font-weight-bold mb-0">Tanggal
                        :</label>
                    <div class="col-sm-2">
                        <input type="date" id="tgltrans" class="form-control bg-white"
                            placeholder="Tanggal Transaksi" />
                    </div>
                    <label for="notrans" class="col-sm-1 col-form-label font-weight-bold mb-0">NoTran
                        :</label>
                    <div class="col-sm-2">
                        <input type="text" id="notrans" class="form-control bg-white" placeholder="Nomor Transaksi"
                            readonly required />
                    </div>
                    <label for="alamat" class="col-sm-1 col-form-label font-weight-bold mb-0">Alamat
                        :</label>
                    <div class="col-sm-4">
                        <input id="alamat" class="form-control bg-white" placeholder="Alamat Pasien" readonly />
                    </div>
                    <div class="col-sm-1">
                        <input type="text" id="no_sampel" class="form-control bg-warning font-weight-bold"
                            placeholder="No Sampel">
                    </div>
                </div>
                <div class="form-group row mt-2">
                    <label for="analis" class="col-sm-1 col-form-label font-weight-bold">Admin
                        :</label>
                    <div class="col">
                        <select id="analis" class="form-control border border-primary" required>
                            <option value="">--Pilih Analis--</option>
                            @foreach ($analis as $item)
                                <option value="{{ $item->nip }}">{{ $item->gelar_d }}
                                    {{ $item->nama }} {{ $item->gelar_b }}</option>
                            @endforeach
                        </select>
                    </div>
                    <label for="dokter" class="col-sm-1 col-form-label font-weight-bold">Dokter
                        :</label>
                    <div class="col">
                        <select id="dokter" class="form-control mb-3 border border-primary" required>
                            <option value="">--Pilih Dokter--</option>
                            @foreach ($dokter as $item)
                                <option value="{{ $item->nip }}">{{ $item->gelar_d }}
                                    {{ $item->nama }} {{ $item->gelar_b }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>


                <div class=" form-group d-flex justify-content-center" hidden>
                    <button type="button" class="btn btn-primary col" data-toggle="modal" data-target="#riwayatModal"
                        onclick="showRiwayat()" hidden>Lihat
                        Riwayat
                        Transaksi</button>
                </div>
            </div>
        </form>

        <script>
            var number = 0;
            var noTransObat;

            async function obatSajaIdentitas() {
                console.log("🚀 ~ obatSajaIdentitas ~ obatSajaIdentitas:", obatSajaIdentitas)
                var today = new Date().toISOString().split("T")[0];
                //hilangkan - atau / di today
                let idTrans = today.replace(/-/g, "").replace(/\//g, "");
                number++;
                noTransObat = idTrans + number + '-' + 'TCM';

                let norm = $("#norm").val();

                //jika no tidak 6 digit, tambahkna 0000 sampai 6 digit
                if (norm.length < 6) {
                    norm = norm.padStart(6, "0");
                }
                tampilkanLoading("Sedang mencarikan data pasien...!!!");
                try {
                    const response = await $.ajax({
                        url: "/api/pasienKominfo",
                        type: "post",
                        data: {
                            no_rm: norm,
                        },
                    });
                    console.log("🚀 ~ obatSajaIdentitas ~ response:", response)
                    if (response === "Data tidak ditemukan!") {
                        Swal.fire({
                            icon: "error",
                            title: "Data pasien tidak ditemukan",
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            timer: 1500,
                        });
                        return;
                    }
                    Swal.fire({
                        icon: "success",
                        title: "Data pasien ditemukan, lanjutkan transaksi...!!!",
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        timer: 1500,
                    });

                    $("#norm").val(response.pasien_no_rm);
                    $("#nama").val(response.pasien_nama);
                    $("#alamat").val(response.pasien_alamat);
                    $("#notrans").val(noTransObat);
                    $("#layanan").val("UMUM");
                    $("#umur").val(response.umur);
                    $("#jk").val(response.jenis_kelamin_nama).trigger("change");
                } catch (error) {
                    console.error("Error:", error);
                    // Handling error if the API request fails
                    tampilkanEror(`Terjadi kesalahan saat mengambil data pasien: ${error}`);
                }

            }
        </script>
    </div>
</div>
<div class="container-fluid">
    <div class="card card-secondary">
        @csrf
        <div class="card-body p-2">
            <div class="row px-2">
                <div class="LayLab col-2">
                    <div class="card card-warning">
                        <div class="card-header">
                            <h4 class="card-title">Permintaan Pemeriksaan</h4>
                        </div>
                        <div class="card-body p-1 card-body table-responsive" id="permintaan" style="height: 390px">
                        </div>
                    </div>
                </div>
                <div class="LayLab col-5">
                    <div class="card card-danger">
                        <div class="card-header">
                            <h4 class="card-title">Pilih Pemeriksaan</h4>
                        </div>
                        <div class="card-body p-1 card-body table-responsive">
                            {{-- <table id="bakteriologi" class="table table-tight table-hover">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="pilih-bakteriologi"></th>
                                        <th>Item Pemeriksaan</th>
                                        <th>Harga</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table> --}}
                            <table id="tabelPemeriksaan" class="table table-tight table-hover">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="pilih-semua"></th>
                                        <th>Item Pemeriksaan</th>
                                        <th>Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-center mt-3">
                                <a class="btn btn-success col" id="tblSimpan" onclick="validateAndSubmit();">Simpan
                                    {{-- onclick="simpan();">Simpan --}}
                                    Pemeriksaan</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="LayLab col-5">
                    <div class="card card-success">
                        <div class="card-header">
                            <h4 class="card-title">Pemeriksaan yang dilakukan</h4>
                        </div>
                        <div class="card-body p-1" style="height: 390px">
                            <table id="dataTrans" class="table table-tight table-hover">
                                <thead>
                                    <tr>
                                        <th class="no-total" width="35px">Aksi</th>
                                        <th>NO</th>
                                        <th>NO RM</th>
                                        <th>Item Pemeriksaan</th>
                                        <th>TGL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- <div class="row px-2">
                <div class="col p-0">
                    <div class="card card-success">
                        <div class="card-header">
                            <h4 class="card-title">Pemeriksaan yang dilakukan</h4>
                        </div>
                        <div class="card-body py-1">
                            <table id="dataTrans" class="table">
                                <thead>
                                    <tr>
                                        <th class="no-total" width="35px">Aksi</th>
                                        <th>NO</th>
                                        <th>NO RM</th>
                                        <th>Item Pemeriksaan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div> --}}
            <div class="card-footer p-0">
                <form id="form_Petugas">
                    <div class="form-group row d-flex justify-content-end">
                        <div class="col-auto">
                            <a class="btn btn-primary" id="tombol_selesai"
                                onclick="resetForm('Transaksi Selesai...!!!');">Selesai</a>
                        </div>
                        <div class="col-auto">
                            <a class="btn btn-warning" id="tblBatal" onclick="batal();">Batal</a>
                        </div>
                        <div class="col-auto">
                            <a class="btn btn-danger" id="delete_ts" onclick="delete_ts();"
                                style="display: none;">Hapus</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
