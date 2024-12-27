@extends('Template.lte')

@section('content')
    @include('Farmasi.Transaksi.antrian')
    {{-- @include('Farmasi.Transaksi.input2') --}}




    <!-- my script -->
    <script src="{{ asset('js/template.js') }}"></script>
    <script src="{{ asset('js/antrianFarmasi.js') }}"></script>
    <script src="{{ asset('js/populate.js') }}"></script>
    {{-- <script src="{{ asset('js/mainFarmasi.js') }}"></script> --}}
    <script>
        $(document).ready(function() {
            var today = new Date().toISOString().split("T")[0];
            $("#tanggal").val(today);
            antrianAll();
            antrianFar();
            setInterval(function() {
                antrianAll();
                antrianFar();
            }, 60000);
        });

        function panggilFarmasi(button) {
            var tgl = button.getAttribute("data-tgl");
            console.log("🚀 ~ panggilFarmasi ~ tgl:", tgl)
            var norm = button.getAttribute("data-norm");
            console.log("🚀 ~ panggilFarmasi ~ norm:", norm)
            var log_id = button.getAttribute("data-log_id");
            console.log("🚀 ~ panggilFarmasi ~ log_id:", log_id)
            var notrans = button.getAttribute("data-notrans");
            console.log("🚀 ~ panggilFarmasi ~ notrans:", notrans)
            cariResepLocal(tgl, norm, log_id, notrans);
        }

        function pulangkan(norm, log_id, notrans) {
            console.log("🚀 ~ panggil ~ log_id:", log_id)
            console.log("🚀 ~ panggil ~ norm:", norm)
            console.log("🚀 ~ panggil ~ notrans:", notrans)

            const apiUrl = "/api/farmasi/panggil";

            // Data yang akan dikirim melalui POST
            const postData = {
                norm: norm,
                log_id: log_id,
                notrans: notrans
            };
            Swal.fire({
                icon: "info",
                title: "Sedang mencarikan data pasien...!!!",
                didOpen: () => {
                    Swal.showLoading();
                },
            })

            fetch(apiUrl, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json", // Pastikan tipe konten adalah JSON
                    },
                    body: JSON.stringify(postData), // Data dikonversi ke JSON
                })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error("Network response was not ok " + response.statusText);
                    }
                    return response.json();
                })
                .then((data) => {
                    console.log("🚀 ~ .then ~ data:", data)
                    Swal.fire({
                        icon: "success",
                        title: "Pasien Pulang",
                    })
                    antrianFar();
                })
                .catch((error) => console.error("Error fetching data:", error));
        }

        // function cariResepLocal(tgl, norm, log_id) {
        function cariResepLocal(button) {
            var tgl = button.getAttribute("data-tgl");
            console.log("🚀 ~ panggilFarmasi ~ tgl:", tgl)
            var norm = button.getAttribute("data-norm");
            console.log("🚀 ~ panggilFarmasi ~ norm:", norm)
            var log_id = button.getAttribute("data-log_id");
            console.log("🚀 ~ panggilFarmasi ~ log_id:", log_id)
            var notrans = button.getAttribute("data-notrans");
            console.log("🚀 ~ panggilFarmasi ~ notrans:", notrans)


            const apiUrl = "/api/lists/obat";

            // Data yang akan dikirim melalui POST
            const postData = {
                tgl: tgl,
                norm: norm,
            };
            Swal.fire({
                icon: "info",
                title: "Sedang mencarikan data pasien...!!!",
                didOpen: () => {
                    Swal.showLoading();
                },
            })

            fetch(apiUrl, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json", // Pastikan tipe konten adalah JSON
                    },
                    body: JSON.stringify(postData), // Data dikonversi ke JSON
                })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error("Network response was not ok " + response.statusText);
                    }
                    return response.json();
                })
                .then((data) => {
                    console.log("🚀 ~ .then ~ data:", data)
                    Swal.close();
                    $("#modal-resep").modal("show");
                    $("#divBtnCetak").html(`
                        <a href="/api/resep/${norm}/${tgl}" target="_blank" class="btn btn-success" >Cetak Resep</a>
                    `);
                    $("#divBtnPulang").html(`
                        <a type="button" onclick="pulangkan('${norm}', '${log_id}', '${notrans}')" target="_blank" class="btn btn-warning" >Pulangkan</a>
                    `);
                    // Hapus konten sebelumnya di tabel untuk menghindari duplikasi
                    const tindakanBody = document.getElementById("tindakan-body");
                    const obatBody = document.getElementById("obat-body");
                    tindakanBody.innerHTML = "";
                    obatBody.innerHTML = "";

                    // Muat data tindakan
                    data.tindakan.forEach((tindakan) => {
                        let rowspan = tindakan.bmhps.length;
                        tindakan.bmhps.forEach((bmhp, index) => {
                            const row = document.createElement("tr");

                            if (index === 0) {
                                row.innerHTML = `
                            <td rowspan="${rowspan}">${tindakan.id}</td>
                            <td rowspan="${rowspan}">${tindakan.notrans}</td>
                            <td rowspan="${rowspan}">${tindakan.norm}</td>
                            <td rowspan="${rowspan}">${tindakan.kdTind}</td>
                            <td rowspan="${rowspan}">${tindakan.tindakan}</td>
                            <td>${bmhp.bmhp} (${bmhp.qty})</td>
                        `;
                            } else {
                                row.innerHTML = `
                            <td>${bmhp.bmhp} (${bmhp.qty})</td>
                        `;
                            }

                            tindakanBody.appendChild(row);
                        });
                    });

                    // Muat data obats
                    data.obats.forEach((obat) => {
                        const row = document.createElement("tr");
                        row.innerHTML = `
                    <td>${obat.no_resep}</td>
                    <td>${obat.nmObat}</td>
                    <td>${obat.jumlah}</td>
                    <td>${obat.signa}</td>
                `;
                        obatBody.appendChild(row);
                    });

                })
                .catch((error) => console.error("Error fetching data:", error));
        }
    </script>



    <div class="modal fade" id="modal-resep">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="container mt-4">
                        <div class="row">
                            <div class="col-md-6">
                                <h3>Daftar Tindakan</h3>
                            </div>
                            <div class="col-md-3" id="divBtnCetak">

                            </div>
                            <div class="col-md-3" id="divBtnPulang">

                            </div>
                        </div>
                        <table class="table table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>No Trans</th>
                                    <th>No RM</th>
                                    <th>Kode Tindakan</th>
                                    <th>Nama Tindakan</th>
                                    <th>BMHP</th>
                                </tr>
                            </thead>
                            <tbody id="tindakan-body">
                                <!-- Data akan dimuat di sini -->
                            </tbody>
                        </table>

                        <h3>Daftar Obat</h3>
                        <table class="table table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>No Resep</th>
                                    <th>Nama Obat</th>
                                    <th>Jumlah</th>
                                    <th>Signa</th>
                                </tr>
                            </thead>
                            <tbody id="obat-body">
                                <!-- Data akan dimuat di sini -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer justify-content-end">

                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    <div class="modal fade" id="riwayatModal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Riwayat Transaksi Farmasi</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="card-body">
                            <div class=" border border-black">
                                <div class="card-body card-body-hidden p-2">
                                    <table id="riwayat" class="table table-striped fs-6" style="width:100%"
                                        cellspacing="0">
                                        <thead class="table-secondary table-sm">
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal</th>
                                                <th>Data FArmasi</th>
                                                <th>Data Tindakan</th>
                                            </tr>
                                        </thead>
                                    </table>
                                    <div id="loadingSpinner" style="display: none;" class="text-center">
                                        <i class="fa fa-spinner fa-spin"></i> Sedang mencari data...
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
@endsection
