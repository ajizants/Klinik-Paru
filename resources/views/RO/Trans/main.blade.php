{{-- @extends('layouts.layout') --}}
@extends('Template.lte')

@section('content')
    @include('RO.Trans.antrian')
    @include('RO.Trans.input')


    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#hasilBacaan">
        Launch demo modal
    </button>
    <div class="modal fade" id="hasilBacaan">
        <div class="modal-dialog modal-dialog modal-dialog-scrollable modal-xl-custom">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-outline card-info">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        Form Bacaan Hasil Radiologi
                                    </h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <textarea id="bacaanRO" name="Hasil Bacaan RO" placeholder="Tuliskan Hasil Bacaan Radiologi"></textarea>
                                </div>
                            </div>
                        </div>
                        <!-- /.col-->
                    </div>
                    <div class="modal-footer justify-content-end">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Simpan</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </div>

    <!-- my script -->
    <script src="{{ asset('js/template.js') }}"></script>
    <script src="{{ asset('js/mainRo.js') }}"></script>
    <script src="{{ asset('js/populate.js') }}"></script>
    <script type="text/javascript">
        var appUrlRo = @json($appUrlRo);

        $(function() {
            $('#bacaanRO').summernote({
                height: 200,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        })
    </script>
@endsection
