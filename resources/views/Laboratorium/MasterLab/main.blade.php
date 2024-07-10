{{-- @extends('layouts.layout') --}}
@extends('Template.lte')

@section('content')
    @include('Laboratorium.MasterLab.input')






    </div>
    </section>
    <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    @include('Template.footer')

    </div>
    @include('Template.script')

    <div class="modal fade" id="modal-update">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Update Layanan Laboratorium</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body p-2">
                        <div class="container-fluid">
                            <div class="card card-black">
                                <!-- form start -->
                                @csrf
                                <form class="form-horizontal" id="updateLayLab">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="form-group col">
                                                <label for="status-idLayanan"
                                                    class="col-sm-3 col-form-label font-weight-bold">Nama Layanan</label>
                                                <div class="col-md row">
                                                    <input type="number" name="status-idLayanan" id="status-idLayanan"
                                                        class="form-control-sm col-md-2" placeholder="ID" readonly />
                                                    <input type="text" id="status-nmLayanan"
                                                        class="form-control-sm col-md bg-white border border-white"
                                                        placeholder="Nama Layanan" readonly>
                                                </div>
                                                <label for="status-tarif"
                                                    class="col-md col-form-label font-weight-bold">Tarif Layanan</label>
                                                <div class="col-md">
                                                    <input id="status-tarif"
                                                        class="form-control-sm col-md bg-white border border-white"
                                                        placeholder="Tarif" />
                                                </div>
                                            </div>

                                            <div class="form-group col">
                                                <label for="status-layanan"
                                                    class="col-sm-3 col-form-label font-weight-bold">Status</label>
                                                <div class="col">
                                                    <select id="status-layanan"
                                                        class="form-control select2bs4 border border-primary">
                                                        <option value="">--Status Layanan--</option>
                                                        <option value="1">Aktif</option>
                                                        <option value="0">Tidak Aktif</option>
                                                    </select>
                                                </div>
                                                <label for="status-kelas"
                                                    class="col-sm-3 col-form-label font-weight-bold">Grup</label>
                                                <div class="col">
                                                    <select id="status-kelas"
                                                        class="form-control select2bs4 border border-primary">
                                                        <option value="">--Pilih Kelas--</option>
                                                        <option value="9">LAYANAN LABORATORIUM</option>
                                                        <option value="91">HEMATOLOGI</option>
                                                        <option value="92">KIMIA DARAH</option>
                                                        <option value="93">IMUNO SEROLOGI</option>
                                                        <option value="94">BAKTERIOLOGI</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-primary" data-dismiss="modal"
                        onclick="updateLayanan();">Simpan</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- my script -->
    <script src="{{ asset('js/template.js') }}"></script>
    <script src="{{ asset('js/populate.js') }}"></script>
    <script src="{{ asset('js/masterLab.js') }}"></script>
@endsection
