@extends('Template.lte')
{{-- @extends('layouts.layout') --}}

@section('content')
    <div class="card shadow mb-4">
        <!-- Card Header - Accordion -->
        <a href="#collapseCardDaftarTindakan" class="d-block card-header py-1 bg bg-info" data-toggle="collapse" role="button"
            aria-expanded="true" aria-controls="collapseCardDaftarTindakan">
            <h4 id="DaftarTindakanSection" class="m-0 font-weight-bold text-dark text-center">REPORT PETUGAS IGD
            </h4>
        </a>
        <!-- Card Content - Collapse -->
        <div class="collapse show" id="collapseCardDaftarTindakan">
            <div class="container">
                <fieldset>
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
                        <div class="col  border border-3 border-info mt-2 p-4">
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
                </fieldset>

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
            <div class="container">
                <fieldset>
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
                        <div class="col  border border-3 border-info mt-2 p-4">
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
                </fieldset>

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
            <div class="container">
                <fieldset>
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
                        <div class="col  border border-3 border-info mt-2 p-4">
                            <div class="table-responsive pt-2 px-2">
                                <table id="reportDots" name="reportDots" class="table table-striped" style="width:100%"
                                    cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th class="">No</th>
                                            {{-- <th class="">NIP</th> --}}
                                            <th class="">Nama</th>
                                            <th class="col-3">Input Kunjungan</th>
                                            <th class="col-3">Input Pasien Baru</th>
                                        </tr>
                                        {{-- <tr>
                                            <th class="">No</th>
                                            <th class="">Nama</th>
                                            <th class="col-3">Input Data SIM RS</th>
                                            <th class="col-3">Input Pasien Lama</th>
                                            <th class="col-3">Input Pasien Baru</th>
                                        </tr> --}}
                                    </thead>
                                </table>
                            </div>
                            @include('Template.Table.loading')
                        </div>
                    </div>
                </fieldset>

            </div>
        </div>
    </div>

    <script src="{{ asset('js/report.js') }}"></script>
@endsection
