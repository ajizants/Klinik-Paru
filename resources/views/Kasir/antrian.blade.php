<div class="card shadow mb-4">
    <!-- Card Header - Accordion -->
    <a href="#collapseCardAntrian" class="d-block card-header py-1 bg bg-info" data-toggle="collapse" role="button"
        aria-expanded="true" aria-controls="collapseCardExample">
        <h4 class="m-0 font-weight-bold text-dark text-center">Antrian</h4>
    </a>
    <!-- Card Content - Collapse -->
    <div class="collapse show card-body p-0" id="collapseCardAntrian">
        @include('Template.Table.loading')
        <div class="mt-3">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a type="button" class="nav-link active bg-blue" onclick=" toggleSections('#dTunggu')"><b>Tunggu
                            UMUM</b></a>
                </li>
                <li class="nav-item">
                    <a type="button" class="nav-link" onclick=" toggleSections('#tab_1')"><b>Tunggu
                            BPJS</b></a>
                </li>
                <li class="nav-item">
                    <a type="button" class="nav-link" onclick=" toggleSections('#dSelesai')"><b>Selesai</b></a>
                </li>
                <li class="nav-item">
                    <a type="button" class="nav-link" onclick=" toggleSections('#dAntrian')"><b>Antrian
                            All</b></a>
                </li>
                <div class="input-group col d-flex justify-content-start ml-5">
                    <input type="date" class="form-control col-sm-2 bg bg-warning" id="tanggal"
                        value="{{ old('date') }}" required onchange=" antrianAll('kasir');">
                    <div class="input-group-addon btn btn-danger">
                        <span class="fa-solid fa-rotate" data-toggle="tooltip" data-placement="top"
                            title="Update Pasien Hari ini" id="cariantrian" onclick=" antrianAll('kasir');"></span>
                    </div>
                </div>
            </ul>
            @include('Template.Table.tunggu')
            @include('Template.Table.tungguBpjs')
            @include('Template.Table.selesai')
            @include('Template.Table.all')
        </div>
    </div>
</div>
