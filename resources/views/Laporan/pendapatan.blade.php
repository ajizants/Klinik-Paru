@extends('Template.lte')

@section('content')
    {{-- Data per pasien --}}
    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center bg-primary justify-content-start">
            <h6 class="m-0 font-weight-bold">Rekap Kunjungan Kasir</h6>
        </div>
        <div class="card-body mb-2">
            <div class="row">
                <label class="col-form-label">Rentang Tanggal :</label>
                <div class="form-group col-3">
                    <div class="input-group">
                        <Select class="form-control" id="tahun">
                            @foreach ($listYear as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </Select>
                    </div>
                </div>
                <div class="col">
                    <button type="button" class="btn btn-success" onclick="report();">
                        Cari
                        <span class="fa-solid fa-rotate" data-toggle="tooltip" data-placement="top" title="Update Data"
                            id="cariantrian"></span>
                    </button>
                </div>

            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover dataTable dtr-inline" id="report" cellspacing="0">
                    <thead class="bg bg-teal table-bordered border-warning">
                        <tr>
                            <th>Aksi</th>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nomor SBS</th>
                            <th>Kode Akun</th>
                            <th>Uraian Akun</th>
                            <th>Jumlah</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody class="table-bordered border-warning">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- my script -->
    <script src="{{ asset('js/template.js') }}"></script>
    <script src="{{ asset('vendor/plugins/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('vendor/plugins/chart.js/Chart.bundle.min.js') }}"></script>
    <script src="{{ asset('js/pendapatan.js') }}"></script>
@endsection
