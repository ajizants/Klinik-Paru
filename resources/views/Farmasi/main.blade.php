{{-- @extends('layouts.layout') --}}
@extends('tamplate.lte')

@section('content')
    @include('farmasi.antrian')
    {{-- @include('farmasi.input') --}}
    @include('farmasi.input2')






    </div>
    </section>
    <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    @include('Tamplate.footer')

    </div>
    @include('Tamplate.script')

    <!-- my script -->
    <script src="{{ asset('js/tamplate.js') }}"></script>
    <script src="{{ asset('js/antrianFarmasi.js') }}"></script>
    <script src="{{ asset('js/populate.js') }}"></script>
    <script src="{{ asset('js/mainFarmasi.js') }}"></script>
    <script>
        function checkSelectedOption(selectElement) {
            var otherQtyDiv = document.getElementById("otherQty");
            var otherQtyInput = document.getElementById("otherQtyInput");

            if (selectElement.value === "other") {
                otherQtyDiv.style.display = "block";
                otherQtyInput.required = true; // Jika ingin memaksa pengguna mengisi
            } else {
                otherQtyDiv.style.display = "none";
                otherQtyInput.required = false;
            }
        }
    </script>


    <div class="modal fade" id="modal-xl">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Stok Obat Farmasi Kurang Dari 200</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="card-body">
                            <div class=" border border-black">
                                <div class="card-body card-body-hidden p-2">
                                    <table id="farmasiObat" class="table table-striped fs-6" style="width:100%"
                                        cellspacing="0">
                                        <thead class="table-secondary table-sm">
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Barang</th>
                                                <th>Pabrikan</th>
                                                <th>Sediaan</th>
                                                <th>Suplier</th>
                                                <th>Tgl ED</th>
                                                <th>Stok Akhir</th>
                                            </tr>
                                        </thead>
                                    </table>
                                    <div id="loadingSpinner" style="display: none;" class="text-center">
                                        <i class="fa fa-spinner fa-spin"></i> Sedang mencari data...
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
@endsection
