        <div class="container-fluid px-2">
            <h5 class="font-weight-bold text-center">Input Data Perkembangan Pasien Rawat Inap</h5>
            @csrf
            <form id="form_cppt">
                @include('Ranap.Cppt.Template.dsdo')

                <label>Assesment & Planing</label>
                <div class="card card-primary card-tabs">
                    <div class="card-header p-0 pt-1">
                        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="nav-dx-tab" data-toggle="tab" href="#nav-dx"
                                    role="tab" aria-controls="nav-dx" aria-selected="true">Diagnosa</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="nav-tindakan-tab" data-toggle="tab" href="#nav-tindakan"
                                    role="tab" aria-controls="nav-tindakan" aria-selected="true">Tindakan</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="nav-ro-tab" data-toggle="tab" href="#nav-ro" role="tab"
                                    aria-controls="nav-ro" aria-selected="false">Radiologi</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="nav-lab-tab" data-toggle="tab" href="#nav-lab" role="tab"
                                    aria-controls="nav-lab" aria-selected="false">Laboratorium</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="nav-terapi-tab" data-toggle="tab" href="#nav-terapi"
                                    role="tab" aria-controls="nav-terapi" aria-selected="false">Resep Obat Oral</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body mx-0 px-0 py-1" style="min-height: 300px;">
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                            <div class="tab-pane fade show active" id="nav-dx" role="tabpanel"
                                aria-labelledby="nav-dx-tab">
                                <!-- Konten Tab 1 -->
                                @include('Ranap.Cppt.Dokter.diagnosa')
                            </div>
                            <div class="tab-pane fade" id="nav-tindakan" role="tabpanel"
                                aria-labelledby="nav-tindakan-tab">
                                <!-- Konten Tab 1 -->
                                @include('Ranap.Cppt.Planing.tindakan')
                            </div>
                            <div class="tab-pane fade" id="nav-ro" role="tabpanel" aria-labelledby="nav-ro-tab">
                                <!-- Konten Tab 2 -->
                                @include('Ranap.Cppt.Planing.ro')
                            </div>
                            <div class="tab-pane fade" id="nav-lab" role="tabpanel" aria-labelledby="nav-lab-tab">
                                <!-- Konten Tab 3 -->
                                @include('Ranap.Cppt.Planing.lab')
                            </div>
                            <div class="tab-pane fade" id="nav-terapi" role="tabpanel" aria-labelledby="nav-terapi-tab">
                                <!-- Konten Tab 4 -->
                                @include('Ranap.Cppt.Planing.obat')
                            </div>
                        </div>

                    </div>
                </div>

                @include('Ranap.Cppt.Template.fotter')
            </form>
        </div>
