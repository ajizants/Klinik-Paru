                <div class="card" id="divTrans">
                    <a href="#collapseCardAntrian" class="d-block card-header py-1 bg bg-info" data-toggle="collapse"
                        role="button" aria-expanded="true" aria-controls="collapseCardExample">
                        <h4 class="m-0 font-weight-bold text-dark text-center">Transaksi</h4>
                    </a>
                    <div class="card-body px-1">
                        <div class="container-fluid">
                            <div class="card card-lime">
                                <div class="card-header">
                                    <h4 class="card-title">Identitas</h4>
                                </div>
                                @csrf
                                <form class="form-horizontal" id="form_identitas">
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <label for="tgltrans"
                                                class="col-sm-1 col-form-label font-weight-bold mb-0">Tanggal
                                                :</label>
                                            <div class="col-sm-2">
                                                <input type="date" id="tgltrans" class="form-control bg-white"
                                                    placeholder="Tanggal Transaksi" />
                                            </div>
                                            <label for="layanan"
                                                class="col-sm-1 col-form-label font-weight-bold mb-0">Layanan
                                                :</label>
                                            <div class="col-sm-2">
                                                <input type="text" id="layanan" class="form-control bg-white"
                                                    placeholder="Layanan" readonly />
                                            </div>
                                            <label for="nama"
                                                class="col-sm-1 col-form-label font-weight-bold  mb-0">Nama
                                                :</label>
                                            <div class="col-sm-4">
                                                <input type="text" id="nama" class="form-control bg-white"
                                                    placeholder="Nama Pasien" readonly>
                                            </div>
                                            <div class="col-sm-1">
                                                <input type="text" id="umur" class="form-control bg-white"
                                                    placeholder="umur">
                                            </div>
                                        </div>
                                        <div class="form-group row mt-2">
                                            <label for="norm"
                                                class="col-sm-1 col-form-label font-weight-bold mb-0 ">No RM
                                                :</label>
                                            <div class="col-sm-2 input-group" style="overflow: hidden;">
                                                <input type="text" name="norm" id="norm" class="form-control"
                                                    placeholder="No RM" maxlength="6" pattern="[0-9]{6}" required
                                                    onkeyup="enterCariRM(event,'lab',this.value);" />
                                            </div>
                                            <label for="notrans"
                                                class="col-sm-1 col-form-label font-weight-bold mb-0">NoTran
                                                :</label>
                                            <div class="col-sm-2">
                                                <input type="text" id="notrans" class="form-control bg-white"
                                                    placeholder="Nomor Transaksi" readonly required />
                                            </div>
                                            <label for="alamat"
                                                class="col-sm-1 col-form-label font-weight-bold mb-0">Alamat
                                                :</label>
                                            <div class="col-sm-4">
                                                <input id="alamat" class="form-control bg-white"
                                                    placeholder="Alamat Pasien" readonly />
                                            </div>
                                            <div class="col-sm-1">
                                                <input type="text" id="no_sampel"
                                                    class="form-control bg-warning font-weight-bold"
                                                    placeholder="No Sampel">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="container-fluid" id="inputSection">
                            @csrf
                            <div class="col p-0">
                                <div class="card card-success">
                                    <div class="card-header">
                                        <h3 class="card-title">Input Hasil Pemeriksaan Laboratorium</h3>
                                    </div>
                                    <div class="card-body py-1">
                                        <div class="container-fluid">
                                            <h5 class="bg-yellow font-weight-bold ml-4 p-2">No Reg. laborat
                                                Selanjutnya adalah: <span id="no_reg_lab_next"
                                                    class="bg-yellow font-weight-bold mx-4"></span></h5>
                                        </div>
                                        <div class="table-responsive">
                                            <table id="inputHasil" class="table table-tight">
                                                <thead>
                                                    <tr>
                                                        <th>NoRM</th>
                                                        <th>Pemeriksaan</th>
                                                        <th>Petugas</th>
                                                        <th>Hasil</th>
                                                        <th>Ket</th>
                                                        <th>Tgl Hasil</th>
                                                        <th>NoTCM</th>
                                                        <th>NoReg TB04</th>
                                                        <th>Kode</th>
                                                        <th>No Sediaan</th>
                                                        <th>Alasan</th>
                                                        <th>Faskes</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="card-footer form-row d-flex justify-content-end aligment-items-center">
                                        <div class="col-3 pt-2 d-flex aligment-items-center">
                                            <div>
                                                <b class="mr-2">Waktu Selesai Hasil:</b> <span
                                                    id="waktuSelesai">-</span>
                                            </div>
                                        </div>
                                        <div class="col-auto" id="divSwitch">
                                            <label class="switch">
                                                <input type="checkbox" id="statusSwitch">
                                                <span class="slider round"></span>
                                                <span id="statusLabel" class="status-text text-dark">Belum</span>
                                            </label>
                                        </div>
                                        <script>
                                            // document.getElementById('statusSwitch').addEventListener('change', function() {
                                            //     var statusLabel = document.getElementById('statusLabel');
                                            //     if (this.checked) {
                                            //         statusLabel.textContent = 'Selesai';
                                            //     } else {
                                            //         statusLabel.textContent = 'Belum Selesai';
                                            //     }
                                            // });
                                            let isCompleted = false; // Initial state: "Belum Selesai"
                                            let status = "Belum";
                                            document.getElementById('statusSwitch').addEventListener('change', function() {
                                                var statusLabel = document.getElementById('statusLabel');

                                                if (this.checked) {
                                                    statusLabel.textContent = 'Selesai';
                                                    status = "Selesai";
                                                } else {
                                                    statusLabel.textContent = 'Belum';
                                                    status = "Belum";
                                                }
                                            });
                                        </script>
                                        <div class="col-auto">
                                            <a class="btn btn-success" id="tblSimpan" onclick="simpan();">Simpan</a>
                                        </div>
                                        <div class="col-auto">
                                            <a class="btn btn-danger" id="tblBatal"
                                                onclick="resetForm('Transaksi dibatalkan');">Batal</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>


                <script>
                    async function dataLab(pemeriksaan, notrans) {
                        const analisResponse = await fetch("/api/analis", {
                            method: "GET",
                            headers: {
                                "Content-Type": "application/json",
                            },
                        });

                        if (!analisResponse.ok) {
                            throw new Error(`HTTP error! Status: ${analisResponse.status}`);
                        }

                        const analisData = await analisResponse.json();

                        const tglSekarang = new Date().toISOString().split("T")[0];

                        const data = pemeriksaan.map((item, index) => ({
                            no: index + 1,
                            norm: item.norm,
                            nmLayanan: item.pemeriksaan?.nmLayanan ?? "",
                            petugas: item.petugas,
                            hasiLab: item.hasil || "",
                            ket: item.ket || "",
                            idLab: item.idLab,
                            idLayanan: item.pemeriksaan?.idLayanan ?? "",
                            kelas: item.pemeriksaan?.kelas ?? "",
                            kdTind: item.pemeriksaan?.kdTind ?? "",
                            no_reg_lab: item.no_reg_lab ?? "",
                            no_iden_sediaan: item.no_iden_sediaan ?? "",
                            tgl_hasil: item.tgl_hasil ?? tglSekarang,
                            alasan_periksa: item.alasan_periksa ?? "",
                            namaFaskes: item.namaFaskes ?? "KKPM",
                            kode_tcm: item.kode_tcm ?? "",
                            no_tcm: item.no_tcm ?? "",
                        }));

                        const table = $("#inputHasil").DataTable({
                            data: data,
                            destroy: true,
                            columns: [{
                                    data: "norm",
                                    render: (data) =>
                                        `<p type="text" class="form-control-sm col hasil">${data}</p>`,
                                },

                                {
                                    data: "nmLayanan",
                                    render: (data, type, row) =>
                                        `<p type="text" class="form-control-sm col hasil" id="layanan${row.idLayanan}" value="${row.idLayanan}" readonly>${data}</p>`,
                                },
                                {
                                    data: "petugas",
                                    width: "100px", // atur lebar kolom di sini
                                    render: (data, type, row) => {
                                        let inputId = "analis" + row.idLab;
                                        let inputField =
                                            `<select id="${inputId}" class="form-control-sm col analis ">`;
                                        inputField += `<option value="">--- Pilih Petugas ---</option>`;
                                        analisData.forEach((petugas) => {
                                            let selected = data === petugas.nip ? "selected" : "";
                                            inputField +=
                                                `<option value="${petugas.nip}" ${selected}>${petugas.gelar_d} ${petugas.nama} ${petugas.gelar_b}</option>`;
                                        });
                                        inputField += "</select>";
                                        return inputField;
                                    },
                                },
                                {
                                    data: "hasiLab",
                                    width: "100px", // atur lebar kolom di sini
                                    render: (data, type, row) => {
                                        let hasilLabHtml = "";
                                        // switch (row.kelas) {
                                        switch (row.kdTind) {
                                            case "94":
                                                hasilLabHtml =
                                                    `<select class="form-control-sm col hasil" id="hasil${row.idLab}">`;
                                                hasilLabHtml += `<option value="">--Pilih Hasil--</option>`;
                                                hasilLabHtml += `<option value="Hasil di SITB" ${data === "Hasil di SITB" ? "selected" : ""
                                                                    }>Hasil di SITB (TCM)</option>`;
                                                hasilLabHtml += `<option value="Rif Sen" ${data === "Rif Sen" ? "selected" : ""
                                                                    }>Rif Sen (TCM)</option>`;
                                                hasilLabHtml += `<option value="Rif Res" ${data === "Rif Res" ? "selected" : ""
                                                                    }>Rif Res (TCM)</option>`;
                                                hasilLabHtml += `<option value="Rif Indet" ${data === "Rif Indet" ? "selected" : ""
                                                                    }>Rif Indet (TCM)</option>`;
                                                hasilLabHtml += `<option value="INVALID" ${data === "INVALID" ? "selected" : ""
                                                                    }>INVALID (TCM)</option>`;
                                                hasilLabHtml += `<option value="ERROR" ${data === "ERROR" ? "selected" : ""
                                                                    }>ERROR (TCM)</option>`;
                                                hasilLabHtml += `<option value="No Result" ${data === "No Result" ? "selected" : ""
                                                                    }>No Result (TCM)</option>`;
                                                hasilLabHtml += `<option value="Negatif" ${data === "Negatif" ? "selected" : ""
                                                                    }>Negatif (BTA/TCM)</option>`;
                                                hasilLabHtml += `<option value="+1" ${data === "+1" ? "selected" : ""
                                                                    }>+ 1 (BTA)</option>`;
                                                hasilLabHtml += `<option value="+2" ${data === "+2" ? "selected" : ""
                                                                    }>+ 2 (BTA)</option>`;
                                                hasilLabHtml += `<option value="+3" ${data === "+3" ? "selected" : ""
                                                                    }>+ 3 (BTA)</option>`;
                                                hasilLabHtml += `<option value="+1-9" ${data === "+1-9" ? "selected" : ""
                                                                    }>+ 1-9 (BTA)</option>`;
                                                hasilLabHtml += `</select>`;
                                                break;
                                            case "93":
                                                hasilLabHtml =
                                                    `<select class="form-control-sm col hasil" id="hasil${row.idLab}">`;
                                                hasilLabHtml += `<option value="">--Pilih Hasil--</option>`;
                                                hasilLabHtml += `<option value="NR" ${data === "NR" ? "selected" : ""
                                                                    }>Non Reaktif (HIV)</option>`;
                                                hasilLabHtml += `<option value="Reaktif" ${data === "Reaktif" ? "selected" : ""
                                                                    }>Reaktif (HIV)</option>`;
                                                hasilLabHtml += `<option value="Negatif" ${data === "Negatif" || data === "NEGATIF"
                                                                    ? "selected": ""
                                                                    }>Negatif (Sifilis)</option>`;
                                                hasilLabHtml += `<option value="Positif" ${data === "Positif" ? "selected" : ""
                                                                    }>Positif (Sifilis)</option>`;
                                                hasilLabHtml += `</select>`;
                                                break;
                                            case "99":
                                                hasilLabHtml =
                                                    `<select class="form-control-sm col hasil" id="hasil${row.idLab}">`;
                                                hasilLabHtml += `<option value="">--Pilih Hasil--</option>`;
                                                hasilLabHtml += `<option value="A" ${data === "A" ? "selected" : ""
                                                                    }>A</option>`;
                                                hasilLabHtml += `<option value="B" ${data === "B" ? "selected" : ""
                                                                    }>B</option>`;
                                                hasilLabHtml += `<option value="AB" ${data === "AB" ? "selected" : ""
                                                                    }>AB</option>`;
                                                hasilLabHtml += `<option value="O" ${data === "O" ? "selected" : ""
                                                                    }>O</option>`;
                                                hasilLabHtml += `</select>`;
                                                break;
                                            case "97":
                                                hasilLabHtml =
                                                    `<select class="form-control-sm col hasil" id="hasil${row.idLab}">`;
                                                hasilLabHtml += `<option value="">--Pilih Hasil--</option>`;
                                                hasilLabHtml += `<option value="IGG NR, IGM NR" ${data === "IGG NR, IGM NR" ? "selected" : ""
                                                                    }>IGG dan IGM NR</option>`;
                                                hasilLabHtml += `<option value="IGG R, IGM R" ${data === "IGG R, IGM R" ? "selected" : ""
                                                                    }>IGG dan IGM R</option>`;
                                                hasilLabHtml += `<option value="IGG NR, IGM R" ${data === "IGG NR, IGM R" ? "selected" : ""
                                                                    }>IGG NR dan IGM R</option>`;
                                                hasilLabHtml += `<option value="IGG R, IGM NR" ${data === "IGG R, IGM NR" ? "selected" : ""
                                                                    }>IGG R dan IGM NR</option>`;

                                                hasilLabHtml += `</select>`;
                                                break;
                                            case "98":
                                                hasilLabHtml =
                                                    `<input type="text" class="form-control-sm col hasil" id="hasil${row.idLab}" value="Terlampir">`;
                                                break;
                                            default:
                                                hasilLabHtml =
                                                    `<input type="text" class="form-control-sm col hasil" id="hasil${row.idLab}" value="${data}">`;
                                        }
                                        return hasilLabHtml;
                                    },
                                },
                                {
                                    data: "ket",
                                    render: (data, type, row) =>
                                        `<input type="text" class="form-control-sm col hasil" id="ket${row.idLab}" value="${data}" placeholder="Keterangan">`,
                                },
                                {
                                    data: "tgl_hasil",
                                    width: "100px",
                                    render: (data, type, row) =>
                                        `<input type="date" class="form-control-sm col hasil" id="tgl_hasil${row.idLab}" value="${data}" placeholder="Tanggal Hasil">`,
                                },
                                {
                                    data: "no_tcm",
                                    width: "25px", // atur lebar kolom di sini
                                    render: (data, type, row) => {
                                        let noRegHtml = "";
                                        const arraykdTindakan = ["131"];
                                        if (arraykdTindakan.includes(String(row.idLayanan))) {
                                            noRegHtml =
                                                `<input type="text" class="form-control-sm col hasil" id="no_tcm${row.idLab}" value="${data}" placeholder="No.Reg">`;
                                        } else {
                                            noRegHtml =
                                                `<input type="text" class="form-control-sm col hasil bg-secondary" id="no_tcm${row.idLab}" value="${data}" readonly placeholder="No.Reg">`;
                                        }
                                        return noRegHtml;
                                    },
                                },
                                {
                                    data: "no_reg_lab",
                                    width: "30px", // atur lebar kolom di sini
                                    render: (data, type, row) => {
                                        let noRegHtml = "";
                                        const arraykdTindakan = ["130", "131", "214"];
                                        if (arraykdTindakan.includes(String(row.idLayanan))) {
                                            noRegHtml =
                                                `<input type="text" class="form-control-sm col hasil" id="no_reg_lab${row.idLab}" value="${data}" placeholder="No.Reg">`;
                                        } else {
                                            noRegHtml =
                                                `<input type="text" class="form-control-sm col hasil bg-secondary" id="no_reg_lab${row.idLab}" value="${data}" readonly placeholder="No.Reg">`;
                                        }
                                        return noRegHtml;
                                    },
                                },
                                {
                                    data: "kode_tcm",
                                    width: "20px", // lebar yang masuk akal
                                    render: (data, type, row) => {
                                        let kdTcm = "";
                                        const arraykdTindakan = ["130", "131", "214"];
                                        if (arraykdTindakan.includes(String(row.idLayanan))) {
                                            kdTcm = `<select class="form-control-sm col hasil" id="kode_tcm${row.idLab}">
                                                        <option value="">-KD-</option>
                                                        <option value="1" ${
                                                            data == "1" ? "selected" : ""
                                                        }>1</option>
                                                        <option value="2" ${
                                                            data == "2" ? "selected" : ""
                                                        }>2</option>
                                                    </select>`;
                                        } else {
                                            kdTcm =
                                                `<input type="text" class="form-control-sm col hasil bg-secondary" id="kode_tcm${row.idLab}" value="" readonly placeholder="Kode">`;
                                        }
                                        return kdTcm;
                                    },
                                },
                                {
                                    data: "no_iden_sediaan",
                                    width: "30px", // atur lebar kolom di sini
                                    render: (data, type, row) => {
                                        let noIdenHtml = "";
                                        const arraykdTindakan = ["130", "131", "214"];
                                        if (arraykdTindakan.includes(String(row.idLayanan))) {
                                            noIdenHtml =
                                                `<input type="text" class="form-control-sm col hasil" id="no_iden_sediaan${row.idLab}" value="${data}" placeholder="No.Iden">`;
                                        } else {
                                            noIdenHtml =
                                                `<input type="text" class="form-control-sm col hasil bg-secondary" id="no_iden_sediaan${row.idLab}" value="" readonly placeholder="No.Iden">`;
                                        }
                                        return noIdenHtml;
                                    },
                                },
                                {
                                    data: "alasan_periksa",
                                    render: (data, type, row) => {
                                        let input = "";
                                        const arraykdTindakan = ["130", "131", "214"];
                                        if (arraykdTindakan.includes(String(row.idLayanan))) {
                                            input =
                                                `<input type="text" class="form-control-sm col hasil" id="alasan_periksa${row.idLab}" value="${data}" placeholder="Alasan Periksa">`;
                                        } else {
                                            input =
                                                `<input type="text" class="form-control-sm col hasil bg-secondary" id="alasan_periksa${row.idLab}" value="" readonly placeholder="Alasan Periksa">`;
                                        }
                                        return input;
                                    },
                                },

                                {
                                    data: "namaFaskes",
                                    render: (data, type, row) => {
                                        let input = "";
                                        const arraykdTindakan = ["130", "131", "214"];
                                        if (arraykdTindakan.includes(String(row.idLayanan))) {
                                            input =
                                                `<input type="text" class="form-control-sm col hasil" id="namaFaskes${row.idLab}" value="${data}" placeholder="Nama Faskes">`;
                                        } else {
                                            input =
                                                `<input type="text" class="form-control-sm col hasil bg-secondary" id="namaFaskes${row.idLab}" value="" readonly    placeholder="Nama Faskes">`;
                                        }
                                        return input;
                                    },
                                },
                            ],
                            order: [
                                [2, "desc"]
                            ],
                            scrollY: "320px",
                            scrolX: true,
                            scrollCollapse: true,
                            paging: false,
                        });
                        scrollToInputSection();
                    }
                </script>
