{{-- @extends('layouts.layout') --}}
@extends('Template.lte')

@section('content')
    @include('Laboratorium.Pendaftaran.antrian')

    @include('Laboratorium.Pendaftaran.input')


    <!-- my script -->
    <script src="{{ asset('js/template.js') }}"></script>
    <script src="{{ asset('js/populate.js') }}"></script>
    <script src="{{ asset('js/mainLab.js') }}"></script>
    <script>
        const itemPemeriksaan = @json($layananLab);
        // console.log("🚀 ~ layanan:", itemPemeriksaan)

        socketIO.on("reload", (msg) => {
            if (msg == "paru_loket_laboratorium") {
                antrian("lab");

            }
            if (msg == "paru_notifikasi_ruang_laboratorium") {
                const notif = new Audio("/audio/dingdong.mp3");
                notif.load();
                notif.play();
                antrian("lab");
            }
            if (msg == "paru_notifikasi_ruang_tensi_1") {
                const notif = new Audio("/audio/dingdong.mp3");
                notif.load();
                notif.play();
                antrianAll("lab");
            }
        });
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
                                    @include('Template.Table.loading')
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
    <div class="modal fade" id="riwayatModal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Riwayat Transaksi Farmasi</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="card-body">
                            <div class=" border border-black">
                                <div class="card-body card-body-hidden p-2">
                                    <table id="riwayat" class="table table-striped fs-6" style="width:100%"
                                        cellspacing="0">
                                        <thead class="table-secondary table-sm">
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal</th>
                                                <th>Data FArmasi</th>
                                                <th>Data Tindakan</th>
                                            </tr>
                                        </thead>
                                    </table>
                                    @include('Template.Table.loading')
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
