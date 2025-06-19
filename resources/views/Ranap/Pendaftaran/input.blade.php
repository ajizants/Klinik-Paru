  {{-- Data per pasien --}}
  <div class="card shadow mb-4">
      <!-- Card Header - Dropdown -->
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-start">
          <h6 class="m-0 font-weight-bold text-primary">Form Pendaftaran Pasien Rawat Inap</h6>
      </div>
      <div class="card-body mb-2">
          @csrf
          <form class="form-horizontal" id="form_identitas">
              <div class="card-body" id="inputSection">
                  <div class="form-group row">
                      <label for="norm" class="col-sm-1 col-form-label font-weight-bold mb-0 ">No RM
                          :</label>
                      <div class="col-sm-2 input-group" style="overflow: hidden;">
                          <input type="text" name="norm" id="norm" class="form-control" placeholder="No RM"
                              maxlength="6" pattern="[0-9]{6}" required onkeyup="enterCariRM(event,'lab');" />
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
                          <input type="text" id="notrans" class="form-control bg-white"
                              placeholder="Nomor Transaksi" readonly required />
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
                              <option value="">--Pilih Petugas--</option>
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
                              <option value="">--Pilih DPJP--</option>
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
      </div>
  </div>
