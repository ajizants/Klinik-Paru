                    <div class="card shadow mb-4">
                        <!-- Card Header - Accordion -->
                        <a href="#collapseCardExample" class="d-block card-header py-1 bg-info" data-toggle="collapse"
                            role="button" aria-expanded="true" aria-controls="collapseCardExample">
                            <h4 id="inputSection" class="m-0 font-weight-bold text-dark text-center">Hasil Foto Thorax
                            </h4>
                        </a>
                        <!-- Card Content - Collapse -->
                        <div class="collapse show" id="collapseCardExample">
                            <div class="card-body p-2">
                                <div class="container-fluid py-2">
                                    <div class="form-group row">
                                        <label for="reservation" class="col-sm-auto col-form-label">No Rekam
                                            Medis:</label>
                                        <input type="text" class="form-control col-2" id="norm"
                                            onkeyup="if (event.keyCode === 13) { cari(); }">
                                        <button type="button" class="mx-2 btn btn-primary col-sm-auto"
                                            onclick="cari();">Cari</button>
                                    </div>
                                    <div class="form-group row" id="buttondiv"></div>
                                </div>
                                <div class="container-fluid">
                                    <div id="preview" class="p-3 row"></div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <div class="modal-content">
                                <div class="modal-body">
                                    <div class="f-panzoom" id="myPanzoom">
                                        <div class="f-custom-controls top-right">
                                            <button data-panzoom-action="toggleFS" class="toggleFullscreen">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <g>
                                                        <path
                                                            d="M14.5 9.5 21 3m0 0h-6m6 0v6M3 21l6.5-6.5M3 21v-6m0 6h6" />
                                                    </g>
                                                    <g>
                                                        <path d="m14 10 7-7m-7 7h6m-6 0V4M3 21l7-7m0 0v6m0-6H4" />
                                                    </g>
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="f-custom-controls bottom-right">
                                            <button data-panzoom-change='{"angle": 90}'>
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path
                                                        d="M9 4.55a8 8 0 0 1 6 14.9M15 15v5h5M5.63 7.16v.01M4.06 11v.01M4.63 15.1v.01M7.16 18.37v.01M11 19.94v.01" />
                                                </svg>
                                            </button>
                                            <button data-panzoom-action="zoomIn">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path d="M12 5v14M5 12h14" />
                                                </svg>
                                            </button>
                                            <button data-panzoom-action="zoomOut">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor" fill="none"
                                                    stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M5 12h14" />
                                                </svg>
                                            </button>
                                        </div>
                                        <img class="f-panzoom__content" id="zoomed-image" src="" />
                                        <div class="f-panzoom__caption" data-selectable id="caption">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
