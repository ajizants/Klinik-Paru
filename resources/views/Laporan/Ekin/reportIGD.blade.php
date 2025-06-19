<div class="card shadow mb-4">
    <!-- Card Header - Accordion -->
    <a href="#collapseCardPoinJaspel" class="d-block card-header py-1 bg bg-info" data-toggle="collapse" role="button"
        aria-expanded="true" aria-controls="collapseCardPoinJaspel">
        <h4 id="PoinJaspelSection" class="m-0 font-weight-bold text-dark text-center">LAPORAN POIN
        </h4>
    </a>
    <!-- Card Content - Collapse -->
    <div class="collapse show" id="collapseCardPoinJaspel">
        <div class="card-body">
            <div class="form-group">
                @csrf
                <form class="form-inline d-flex justify-content-start p-2">
                    @php
                        $tahun = date('Y');
                        $bulan = date('m');
                    @endphp
                    <label for="bulan"> <b>Bulan :</b></label>
                    <select name="bulan" id="bulan" class="form-control bg bg-warning m-2">
                        <option value="">-- Pilih Bulan --</option>
                        <option value="01" {{ $bulan == '01' ? 'selected' : '' }}>Januari</option>
                        <option value="02" {{ $bulan == '02' ? 'selected' : '' }}>Februari</option>
                        <option value="03" {{ $bulan == '03' ? 'selected' : '' }}>Maret</option>
                        <option value="04" {{ $bulan == '04' ? 'selected' : '' }}>April</option>
                        <option value="05" {{ $bulan == '05' ? 'selected' : '' }}>Mei</option>
                        <option value="06" {{ $bulan == '06' ? 'selected' : '' }}>Juni</option>
                        <option value="07" {{ $bulan == '07' ? 'selected' : '' }}>Juli</option>
                        <option value="08" {{ $bulan == '08' ? 'selected' : '' }}>Agustus</option>
                        <option value="09" {{ $bulan == '09' ? 'selected' : '' }}>September</option>
                        <option value="10" {{ $bulan == '10' ? 'selected' : '' }}>Oktober</option>
                        <option value="11" {{ $bulan == '11' ? 'selected' : '' }}>November</option>
                        <option value="12" {{ $bulan == '12' ? 'selected' : '' }}>Desember</option>
                    </select>
                    <label for="tahun" class="form-label"><b>Tahun :</b></label>
                    <select name="tahun" id="tahun" class="form-control bg bg-warning m-2">
                        <option value="">-- Pilih Tahun --</option>
                        @for ($i = $tahun - 5; $i <= $tahun + 5; $i++)
                            <option value="{{ $i }}" {{ $i == $tahun ? 'selected' : '' }}>
                                {{ $i }}</option>
                        @endfor
                    </select>
                    <a class="btn btn-success d-flex justify-content-center mx-2" onclick="CariPoinJaspel();">Cari</a>
                </form>

                <script>
                    function CariPoinJaspel() {
                        $('#divTablePoinJaspel').html("");
                        var bulan = document.getElementById('bulan').value;
                        var tahun = document.getElementById('tahun').value;
                        if (bulan == '' || tahun == '') {
                            tampilkanEror('Pilih Bulan dan Tahun Terlebih Dahulu');
                            return false;
                        }
                        var url = "/api/getRekapJumlahPoin/" + bulan + "/" + tahun;
                        $.ajax({
                            url: url,
                            type: "GET",
                            success: function(data) {
                                // masukan data ke div table
                                $('#divTablePoinJaspel').html(data);

                                // Inisialisasi DataTable
                                $('#tablePoinJaspel').DataTable({
                                        buttons: [{
                                                extend: "copyHtml5",
                                                text: "Salin",
                                            },
                                            {
                                                extend: "excelHtml5",
                                                text: "Excel",
                                                title: "Laporan Jaspel: \n" +
                                                    bulan +
                                                    "-" +
                                                    tahun,
                                                filename: "Laporan Jaspel: " +
                                                    bulan +
                                                    "-" +
                                                    tahun,
                                            },
                                            "colvis", // Tombol untuk menampilkan/menyembunyikan kolom
                                        ],
                                    })
                                    .buttons()
                                    .container()
                                    .appendTo("#tablePoinJaspel_wrapper .col-md-6:eq(0)");
                            }
                        })
                    }
                </script>
                <div class="table-responsive pt-2 px-2" id="divTablePoinJaspel">
                    <table id="tablePoinJaspel" name="tablePoinJaspel" class="table table-striped" style="width:100%"
                        cellspacing="0">
                        <thead>
                            <tr>
                                <th class="">No</th>
                                <th class="">Jenis Pemeriksaan</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                @include('Template.Table.loading')
            </div>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <!-- Card Header - Accordion -->
    <a href="#collapseCardDaftarTindakan" class="d-block card-header py-1 bg bg-info" data-toggle="collapse"
        role="button" aria-expanded="true" aria-controls="collapseCardDaftarTindakan">
        <h4 id="DaftarTindakanSection" class="m-0 font-weight-bold text-dark text-center">REPORT PETUGAS IGD
        </h4>
    </a>
    <!-- Card Content - Collapse -->
    <div class="collapse show" id="collapseCardDaftarTindakan">
        <div class="card-body">
            <div class="container">
                <div class="form-group">
                    @csrf
                    <form class="form-inline d-flex justify-content-start p-2">
                        <label for="mulaiTgl"> <b>Tanggal Awal :</b></label>
                        <input type="date" class="form-control bg bg-warning m-2" id="mulaiTgl"
                            value="{{ old('date') }}" required>
                        <label for="selesaiTgl" class="form-label"><b>Tanggal Akhir :</b></label>
                        <input type="date" class="form-control bg bg-warning m-2" id="selesaiTgl"
                            value="{{ old('date') }}" required>
                        <a id="cari" class="btn btn-success d-flex justify-content-center mx-2">Cari</a>
                    </form>
                    <div class="table-responsive pt-2 px-2">
                        <table id="report" name="report" class="table table-striped" style="width:100%"
                            cellspacing="0">
                            <thead>
                                <tr>
                                    <th class="">No</th>
                                    <th class="">Nip</th>
                                    <th class="">Nama</th>
                                    <th class="">Tindakan</th>
                                    <th class="col-3">Jumlah</th>
                                    <th class="">Status</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    @include('Template.Table.loading')
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <!-- Card Header - Accordion -->
    <a href="#collapseCardPoin" class="d-block card-header py-1 bg bg-info" data-toggle="collapse" role="button"
        aria-expanded="true" aria-controls="collapseCardPoin">
        <h4 id="PoinSection" class="m-0 font-weight-bold text-dark text-center">REPORT PETUGAS PELAYANAN
        </h4>
    </a>
    <!-- Card Content - Collapse -->
    <div class="collapse show" id="collapseCardPoin">
        <div class="card-body">
            <div class="container">
                <div class="form-group">
                    @csrf
                    <form class="form-inline d-flex justify-content-start p-2">
                        <label for="mulaiTglAll"> <b>Tanggal Awal :</b></label>
                        <input type="date" class="form-control bg bg-warning m-2" id="mulaiTglAll"
                            value="{{ old('date') }}" required>
                        <label for="selesaiTglAll" class="form-label"><b>Tanggal Akhir :</b></label>
                        <input type="date" class="form-control bg bg-warning m-2" id="selesaiTglAll"
                            value="{{ old('date') }}" required>
                        <a type="button" class="btn btn-success d-flex justify-content-center mx-2"
                            onclick="reportPoinPetugas();">Cari</a>
                    </form>
                    <div class="table-responsive pt-2 px-2">
                        <table id="poinAll" name="PoinAll" class="table table-striped" style="width:100%"
                            cellspacing="0">
                            <thead>
                                <tr>
                                    <th class="">No</th>
                                    <th class="">Tempat Tugas</th>
                                    <th class="">Nama</th>
                                    <th class="col-3">Jumlah</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    @include('Template.Table.loading')
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card shadow mb-4">
    <!-- Card Header - Accordion -->
    <a href="#collapseCardPoin" class="d-block card-header py-1 bg bg-info" data-toggle="collapse" role="button"
        aria-expanded="true" aria-controls="collapseCardPoin">
        <h4 id="PoinSection" class="m-0 font-weight-bold text-dark text-center">REPORT PETUGAS DOTS CENTER
        </h4>
    </a>
    <!-- Card Content - Collapse -->
    <div class="collapse show" id="collapseCardPoin">
        <div class="card-body">
            <div class="container">
                <div class="form-group">
                    @csrf
                    <form class="form-inline d-flex justify-content-start p-2">
                        <label for="mulaiTglDots"> <b>Tanggal Awal :</b></label>
                        <input type="date" class="form-control bg bg-warning m-2" id="mulaiTglDots"
                            value="{{ old('date') }}" required>
                        <label for="selesaiTglDots" class="form-label"><b>Tanggal Akhir :</b></label>
                        <input type="date" class="form-control bg bg-warning m-2" id="selesaiTglDots"
                            value="{{ old('date') }}" required>
                        <a type="button" class="btn btn-success d-flex justify-content-center mx-2"
                            onclick="reportPoinDots();">Cari</a>
                    </form>
                    <div class="table-responsive pt-2 px-2">
                        <table id="reportDots" name="reportDots" class="table table-striped" style="width:100%"
                            cellspacing="0">
                            <thead>
                                <tr>
                                    <th class="">No</th>
                                    <th class="">Nama</th>
                                    <th class="col-3">Input Data SIM RS</th>
                                    <th class="col-3">Input Pasien Lama</th>
                                    <th class="col-3">Input Pasien Baru</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    @include('Template.Table.loading')
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/report.js') }}"></script>
<script src="{{ asset('js/template.js') }}"></script>
