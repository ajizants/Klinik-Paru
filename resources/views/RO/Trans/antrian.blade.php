<div class="card shadow mb-4">
    <!-- Card Header - Accordion -->
    <a href="#collapseCardAntrian" class="d-block card-header py-1 bg bg-info" data-toggle="collapse" role="button"
        aria-expanded="true" aria-controls="collapseCardExample">
        <h4 class="m-0 font-weight-bold text-dark text-center">Antrian</h4>
    </a>
    <!-- Card Content - Collapse -->
    <div class="collapse show card-body p-0" id="collapseCardAntrian">
        <div class="d-flex justify-content-center position-absolute" style="z-index: 500;">
            @include('Template.Table.loading')
        </div>
        <div class="mt-3">
            {{-- <div id="loadingSpinner" style="display: none; scale: 2;"
                class="badge bg-primary text-wrap text-center z-3 position-absolute mt-5">
                <i class="fa fa-spinner fa-spin"> </i>Sedang Mencari data...
            </div> --}}
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a type="button" class="nav-link active bg-blue"
                        onclick="toggleSections('#dTunggu');"><b>Tunggu</b></a>
                </li>
                <li class="nav-item">
                    <a type="button" class="nav-link" onclick="toggleSections('#dKonsul');"><b>Konsul Sp.Rad</b></a>
                </li>
                <li class="nav-item">
                    <a type="button" class="nav-link" onclick="toggleSections('#dBelum');"><b>Belum Upoload</b></a>
                </li>
                <li class="nav-item">
                    <a type="button" class="nav-link" onclick="toggleSections('#dSelesai');"><b>Selesai</b></a>
                </li>
                <li class="nav-item">
                    <a type="button" class="nav-link " onclick="toggleSections('#dAntrian');"><b>Antrian
                            All</b></a>
                </li>
                <div class="input-group col d-flex justify-content-end mr-5">
                    <input type="date" class="form-control col-sm-2 bg bg-warning" id="tanggal"
                        value="{{ old('date') }}" required onchange="updateAntrian();">
                    <div class="input-group-addon btn btn-danger">
                        <span class="fa-solid fa-rotate" data-toggle="tooltip" data-placement="top"
                            title="Update Pasien Hari ini" id="cariantrian" onclick="updateAntrian();"></span>
                    </div>
                </div>
            </ul>

            @include('Template.Table.tunggu')
            @include('Template.Table.konsul')
            @include('Template.Table.belumUpload')
            @include('Template.Table.selesai')
            @include('Template.Table.all')

        </div>
    </div>

</div>
