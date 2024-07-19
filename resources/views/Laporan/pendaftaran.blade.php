@extends('Template.lte')

@section('content')
    {{-- Data per pasien --}}
    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-start">
            <h6 class="m-0 font-weight-bold text-primary">Data Waktu Tunggu Layanan Pasien</h6>
        </div>
        <!-- Card Body -->
        <div class="card-body mb-2">
            {{-- <div class="row">
                    <label class="col-form-label">Rentang Tanggal :</label>
                    <div class="form-group col-5">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="far fa-calendar-alt"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control float-right" id="reservation">
                        </div>
                    </div>
                </div> --}}
            <div class="table-responsive">
                <table class="table table-bordered" id="waktuLayanan" width="100%" cellspacing="0">
                    <thead class="bg bg-teal">
                        <tr>
                            <th class="col-1">Antrean Nomor</th>
                            <th class="col-2">Tanggal</th>
                            <th class="col-1">Penjamin</th>
                            <th class="col-1">Daftar Lewat</th>
                            <th class="col-1">Pendaftaran</th>
                            <th class="col-1">No. Rekam Medis</th>
                            <th class="col-3">Nama Pasien</th>
                            <th class="col-3">JK</th>
                            <th class="col-3">Alamat</th>
                            <th class="col-1">Poli</th>
                            <th class="col-3">Dokter</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    @include('Template.footer')

    </div>
    @include('Template.script')


    <!-- my script -->
    <script src="{{ asset('js/template.js') }}"></script>
    <script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('vendor/chart.js/Chart.bundle.min.js') }}"></script>

    </body>

    </html>
@endsection
