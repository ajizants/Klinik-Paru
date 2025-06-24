        <div class="container-fluid px-2">
            <h5 class="font-weight-bold text-center">Input Data Perkembangan Pasien Rawat Inap</h5>
            @csrf
            <form id="form_cppt">
                @include('Ranap.Cppt.dsdo')

                <label>Assesment & Planing</label>
                <div class="card card-primary card-tabs">
                    <div class="card-header p-0 pt-1">
                        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="nav-1-tab" data-toggle="tab" href="#nav-1" role="tab"
                                    aria-controls="nav-1" aria-selected="true">Dokter</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="nav-tindakan-tab" data-toggle="tab" href="#nav-tindakan"
                                    role="tab" aria-controls="nav-tindakan" aria-selected="true">Tindakan</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="nav-2-tab" data-toggle="tab" href="#nav-2" role="tab"
                                    aria-controls="nav-2" aria-selected="false">RO & Lab</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="nav-3-tab" data-toggle="tab" href="#nav-3" role="tab"
                                    aria-controls="nav-3" aria-selected="false">Nutritionis</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="nav-4-tab" data-toggle="tab" href="#nav-4" role="tab"
                                    aria-controls="nav-4" aria-selected="false">Terapis</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="nav-5-tab" data-toggle="tab" href="#nav-5" role="tab"
                                    aria-controls="nav-5" aria-selected="false">Apoteker</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body mx-0 px-0 py-1">
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                            <div class="tab-pane fade show active" id="nav-1" role="tabpanel"
                                aria-labelledby="nav-1-tab">
                                <!-- Konten Tab 1 -->
                                @include('Ranap.Cppt.assDokter')
                            </div>
                            <div class="tab-pane fade" id="nav-tindakan" role="tabpanel"
                                aria-labelledby="nav-tindakan-tab">
                                <!-- Konten Tab 1 -->
                                @include('Ranap.Cppt.Planing.tindakan')
                            </div>
                            <div class="tab-pane fade" id="nav-2" role="tabpanel" aria-labelledby="nav-2-tab">
                                <!-- Konten Tab 2 -->
                                @include('Ranap.Cppt.roLab')
                            </div>
                            <div class="tab-pane fade" id="nav-3" role="tabpanel" aria-labelledby="nav-3-tab">
                                <!-- Konten Tab 3 -->
                                @include('Ranap.Cppt.assGizi')
                            </div>
                            <div class="tab-pane fade" id="nav-4" role="tabpanel" aria-labelledby="nav-4-tab">
                                <!-- Konten Tab 4 -->
                                @include('Ranap.Cppt.assTerapis')
                            </div>
                            <div class="tab-pane fade" id="nav-5" role="tabpanel" aria-labelledby="nav-5-tab">
                                <!-- Konten Tab 4 -->
                                @include('Ranap.Cppt.assApoteker')
                            </div>
                        </div>

                    </div>
                </div>

                @include('Ranap.Cppt.fotter')
            </form>
        </div>
