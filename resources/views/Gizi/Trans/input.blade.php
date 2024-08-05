                <div class="container-fluid">

                </div>
                <div class="container-fluid">
                    <div class="card card-secondary">
                        @csrf
                        <div class="card-header p-2">
                            <h4 class="m-0 font-weight-bold text-center text-light">Asuhan Gizi</h4>
                        </div>
                        <div class="card-body p-2">
                            <div class="card card-lime">
                                <div class="card-header">
                                    <h4 class="card-title">Identitas</h4>
                                </div>
                                @csrf
                                <form class="form-horizontal" id="form_identitas">
                                    <div class="card-body" id="inputSection">
                                        <div class="form-grup row">
                                            <label for="norm"
                                                class="col-sm-1 col-form-label font-weight-bold mb-0 ">No
                                                RM
                                                :</label>
                                            <div class="col-sm-2 input-group input-group-sm" style="overflow: hidden;">
                                                <input type="text" name="norm" id="norm"
                                                    aria-describedby="inputGroup-sizing-sm" class="form-control"
                                                    placeholder="No RM" maxlength="6" pattern="[0-9]{6}" required
                                                    onkeyup="enterCariRM(event,'gizi');" />
                                            </div>
                                            <label for="layanan"
                                                class="col-sm-1 col-form-label font-weight-bold mb-0">Layanan
                                                :</label>
                                            <div class="col-sm-2 input-group input-group-sm">
                                                <input type="text" id="layanan" class="form-control bg-white"
                                                    aria-describedby="inputGroup-sizing-sm" placeholder="Layanan" />
                                            </div>
                                            <label for="nama"
                                                class="col-sm-1 col-form-label font-weight-bold  mb-0">Nama
                                                :</label>
                                            <div class="col-sm-5 input-group input-group-sm">
                                                <input type="text" id="nama" class="form-control"
                                                    aria-describedby="inputGroup-sizing-sm" placeholder="Nama Pasien">
                                            </div>
                                        </div>
                                        <div class="form-grup row mt-2">
                                            <label for="tglLahir"
                                                class="col-sm-1 col-form-label font-weight-bold mb-0">Tgl
                                                Lahir
                                                :</label>
                                            <div class="col-sm-2 input-group input-group-sm">
                                                <input type="date" id="tglLahir" class="form-control bg-white"
                                                    aria-describedby="inputGroup-sizing-sm"
                                                    placeholder="Tanggal Transaksi" />
                                            </div>
                                            <label for="notrans"
                                                class="col-sm-1 col-form-label font-weight-bold mb-0">NoTran
                                                :</label>
                                            <div class="col-sm-2 input-group input-group-sm">
                                                <input type="text" id="notrans" class="form-control bg-white"
                                                    aria-describedby="inputGroup-sizing-sm"
                                                    placeholder="Nomor Transaksi" required />
                                            </div>
                                            <label for="alamat"
                                                class="col-sm-1 col-form-label font-weight-bold mb-0">Alamat
                                                :</label>
                                            <div class="col-sm-5 input-group input-group-sm">
                                                <input id="alamat" class="form-control bg-white"
                                                    aria-describedby="inputGroup-sizing-sm"
                                                    placeholder="Alamat Pasien" />
                                            </div>
                                        </div>
                                        <div class="form-grup row mt-2">
                                            <label for="tgltrans"
                                                class="col-sm-1 col-form-label font-weight-bold mb-0">Tgl
                                                Input :</label>
                                            <div class="col-sm-2 input-group input-group-sm">
                                                <input type="date" id="tgltrans" class="form-control bg-white"
                                                    aria-describedby="inputGroup-sizing-sm"
                                                    placeholder="Tanggal Transaksi" />
                                            </div>
                                            <label for="ahli_gizi"
                                                class="col-sm-1 col-form-label font-weight-bold">Petugas
                                                :</label>
                                            <div class="col input-group input-group-sm">
                                                <select type="select" id="ahli_gizi"
                                                    class="form-control border border-primary"
                                                    aria-describedby="inputGroup-sizing-sm" required>
                                                    <option value="199902072022032008">REGINA DONA ZHAFIRA A.Md.Gz.
                                                    </option>
                                                </select>
                                            </div>
                                            <label for="dokter"
                                                class="col-sm-1 col-form-label font-weight-bold">Dokter
                                                :</label>
                                            <div class="col input-group input-group-sm">
                                                <select type="select"id="dokter"
                                                    aria-describedby="inputGroup-sizing-sm"
                                                    class="form-control mb-3 border border-primary" required>
                                                    <option value="">--Pilih Dokter--</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a type="button" class="nav-link active bg-blue"
                                        onclick="toggleSections('#kunjungan');"><b>Kunjungan</b></a>
                                </li>
                                <li class="nav-item">
                                    <a type="button" class="nav-link" onclick="toggleSections('#asesmen');"><b>Asesmen
                                            Awal</b></a>
                                </li>
                            </ul>
                            <div id="kunjungan">
                                @include('Gizi.Trans.kunjungan')
                            </div>

                            <div id="asesmen" style="display: none;">
                                @include('Gizi.Trans.asesment')
                            </div>
                        </div>
                    </div>
