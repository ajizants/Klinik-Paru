@extends('Template.lte')

@section('content')
    @include('GudangATK.input')

    <!-- Modal ATK Baru -->
    <div class="modal fade" id="modal_atk_baru" tabindex="-1" aria-labelledby="modal_atk_baruLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div id="form_atk_baru">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah ATK Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="idBarang" class="form-label">ID Barang</label>
                            <input type="text" class="form-control" name="idBarang" required>
                        </div>
                        <div class="mb-3">
                            <label for="namaBarang" class="form-label">Nama Barang</label>
                            <input type="text" class="form-control" name="namaBarang" required>
                        </div>
                        <div class="mb-3">
                            <label for="jumlah" class="form-label">Jumlah</label>
                            <input type="number" class="form-control" name="jumlah" required>
                        </div>
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <input type="text" class="form-control" name="keterangan">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal_atk_baru">Batal</button>
                        <button type="submit" class="btn btn-primary" onclick="simpanAtkBaru();">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal Tambah ATK -->
    <div class="modal fade" id="modalTambahAtk" tabindex="-1" aria-labelledby="modalTambahAtkLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div id="formTambahAtk">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahAtkLabel">Tambah ATK</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="tambahid">
                        <input type="hidden" name="idBarang" id="tambahIdBarang">
                        <div class="mb-3">
                            <label for="namaBarang" class="form-label">Nama Barang</label>
                            <input type="text" class="form-control" id="tambahNamaBarang" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="jumlahTambah" class="form-label">Jumlah</label>
                            <input type="number" class="form-control" name="jumlah" required>
                        </div>
                        <div class="mb-3">
                            <label for="keteranganTambah" class="form-label">Keterangan</label>
                            <input type="text" class="form-control" name="keterangan">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" onclick="atkMasuk();" id="btnTambah">Tambah</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Keluarkan ATK -->
    <div class="modal fade" id="modalKeluarAtk" tabindex="-1" aria-labelledby="modalKeluarAtkLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div id="formKeluarAtk">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalKeluarAtkLabel">Keluarkan ATK</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="keluarid">
                        <input type="hidden" name="idBarang" id="keluarIdBarang">
                        <div class="mb-3">
                            <label for="namaBarang" class="form-label">Nama Barang</label>
                            <input type="text" class="form-control" id="keluarNamaBarang" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="jumlahKeluar" class="form-label">Jumlah</label>
                            <input type="number" class="form-control" name="jumlah" required>
                        </div>
                        <div class="mb-3">
                            <label for="keteranganKeluar" class="form-label">Keterangan</label>
                            <input type="text" class="form-control" name="keterangan">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger" onclick="atkKeluar();"
                            id="btnKeluar">Keluarkan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- my script -->
    <script src="{{ asset('js/template.js') }}"></script>
    <script src="{{ asset('js/populate.js') }}"></script>
    <script>
        function tambahAtk(button, title, btn) {
            const idBarang = button.dataset.id;
            const namaBarang = button.dataset.nama;
            console.log("Tambah ATK:", idBarang, namaBarang);
            $('#tambahIdBarang').val(idBarang);
            $('#tambahNamaBarang').val(namaBarang);
            $('#modalTambahAtkLabel').text(title);
            $('#btnTambah').text(btn);
            $('#modalTambahAtk').modal('show');
        }

        function keluarAtk(button, title, btn) {
            const idBarang = button.dataset.id;
            const namaBarang = button.dataset.nama;
            console.log("Keluar ATK:", idBarang, namaBarang);
            $('#keluarIdBarang').val(idBarang);
            $('#keluarNamaBarang').val(namaBarang);
            $('#modalKeluarAtkLabel').text(title);
            $('#btnKeluar').text(btn);
            $('#modalKeluarAtk').modal('show');
        }

        function editAtk(button, ket, title, btn) {
            const idBarang = button.dataset.id;
            const namaBarang = button.dataset.nama;
            const jumlah = button.dataset.jumlah;
            const id = button.dataset.id;
            $('#tambahid').val(id);
            $('#tambahIdBarang').val(idBarang);
            $('#tambahNamaBarang').val(namaBarang);
            $('#tambahJumlah').val(jumlah);
            $('#modalTambahAtkLabel').text(title);
            $('#btnTambah').text(btn);
            $('#modalTambahAtk').modal('show');
            console.log("Edit ATK:", idBarang, namaBarang, jumlah, id);

        }

        function tambahAtkBaru() {

        }

        function atkKeluar() {
            const idBarang = $('#keluarIdBarang').val();
            const jumlah = $('#jumlahKeluar').val();
            const keterangan = $('#keteranganKeluar').val();
            console.log("Keluar ATK:", idBarang, jumlah);
            //ajax
            $.ajax({
                type: "POST",
                url: "/api/keluarAtk",
                data: {
                    idBarang: idBarang,
                    jumlah: jumlah,
                    keterangan: keterangan
                },
                success: function(response) {
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            })
        }

        function atkMasuk() {
            const idBarang = $('#tambahIdBarang').val();
            const jumlah = $('#jumlahTambah').val();
            const keterangan = $('#keteranganTambah').val();
            console.log("Tambah ATK:", idBarang, jumlah);
            //ajax
            $.ajax({
                type: "POST",
                url: "/api/addAtk",
                data: {
                    idBarang: idBarang,
                    jumlah: jumlah,
                    keterangan: keterangan
                },
                success: function(response) {
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            })
        }

        window.addEventListener("load", function() {
            // Tanggal awal bulan dan akhir bulan (Moment.js)
            let tglAwal = moment().startOf("month").format("YYYY-MM-DD");
            let tglAkhir = moment().endOf("month").format("YYYY-MM-DD");
            let tglAwalE = moment().startOf("month").format("DD/MM/YYYY");
            let tglAkhirE = moment().endOf("month").format("DD/MM/YYYY");

            // Set nilai awal datepicker
            $("#reservation").val(tglAwalE + " s.d. " + tglAkhirE);

            // Inisialisasi Date Range Picker
            $("#reservation").daterangepicker({
                startDate: tglAwalE,
                endDate: tglAkhirE,
                autoApply: true,
                locale: {
                    format: "DD/MM/YYYY",
                    separator: " s.d. ",
                    applyLabel: "Apply",
                    cancelLabel: "Cancel",
                    customRangeLabel: "Custom Range",
                },
            });

            // Saat pengguna memilih tanggal baru
            $("#reservation").on("apply.daterangepicker", function(ev, picker) {
                tglAwal = picker.startDate.format("YYYY-MM-DD");
                tglAkhir = picker.endDate.format("YYYY-MM-DD");
                tglAwalE = picker.startDate.format("DD/MM/YYYY");
                tglAkhirE = picker.endDate.format("DD/MM/YYYY");

                $(this).val(tglAwalE + " s.d. " + tglAkhirE);
            });

            // Inisialisasi DataTable untuk semua tabel
            const initTable = (tableId, titlePrefix) => {
                $('#' + tableId).DataTable({
                    autoWidth: false,
                    lengthChange: false,
                    order: [],
                    buttons: [{
                            extend: "excelHtml5",
                            text: "Excel",
                            title: `${titlePrefix} (${tglAwalE} s.d. ${tglAkhirE})`,
                            filename: `${titlePrefix.replace(/\s+/g, "_")}_${tglAwalE}_sd_${tglAkhirE}`,
                        },
                        {
                            extend: "colvis",
                            text: "Tampilkan Kolom",
                        }
                    ],
                }).buttons().container().appendTo(`#${tableId}_wrapper .col-md-6:eq(0)`);
            };

            initTable('tableAtkMasuk', 'Laporan ATK Masuk');
            initTable('tableAtkKeluar', 'Laporan ATK Keluar');
            initTable('tableStokAtk', 'Laporan Stok ATK');
        });
    </script>
@endsection
