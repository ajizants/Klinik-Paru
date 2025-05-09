@extends('Template.lte')

@section('content')
    <div class="card shadow">
        <div class="card-body">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a type="button" class="nav-link border border-primary active bg-blue" onclick=" toggleSections('#tab_1')"
                        id="link_tab_1"><b>Rekap Poin</b></a>
                </li>
                <li class="nav-item">
                    <a type="button" class="nav-link border border-primary" onclick=" toggleSections('#tab_2')"
                        id="link_tab_1"><b>Kepegawaian</b></a>
                </li>
                <li class="nav-item">
                    <a type="button" class="nav-link border border-primary" onclick=" toggleSections('#tab_3')"><b>Kegiatan
                            Lain</b></a>
                </li>
            </ul>
            <div id="tab_1">
                @include('Laporan.Ekin.reportIGD')
            </div>
            <div id="tab_2"style="display: none">
                @include('Laporan.Ekin.daftarPegawai')
            </div>
            <div id="tab_3" style="display: none">
                @include('Laporan.Ekin.kegiatan')
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalTglEkin" style="display: none; padding-right: 15px;" aria-modal="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Pilih Rentang Tanggal sebelum mencetak data kinerja pegawai</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-grup">
                        <label class="col-form-label">NIP:</label>
                        <div class="form-group">
                            <input type="text" class="form-control" id="nip" readonly>
                        </div>
                    </div>
                    <div class="form-grup">
                        <label class="col-form-label">Nama:</label>
                        <div class="form-group">
                            <input type="text" class="form-control" id="nama" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Tanggal:</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="far fa-calendar-alt"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control float-right" id="tglEkin">
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-primary" onclick="okeCetak();">Cetak</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>




        <!-- my script -->
        <script src="{{ asset('js/template.js') }}"></script>
        <script>
            function cetak(nip, nama) {
                $('#modalTglEkin').modal('show');
                $('#nama').val(nama);
                $('#nip').val(nip);
            }

            function okeCetak() {
                const nama = $('#nama').val();
                const nip = $('#nip').val();
                const url =
                    `api/ekin/poin?tanggal_awal=${tglAwal}&tanggal_akhir=${tglAkhir}&nip=${nip}&nama=${encodeURIComponent(nama)}`;
                console.log("🚀 ~ cetak ~ url:", url)


                window.open(url, "_blank");
                $('#modalTglEkin').modal('hide');
            }

            function edit(nip, nama) {
                const url = `api/pegawai/${nip}`;

                // Tampilkan SweetAlert loading
                Swal.fire({
                    title: 'Memuat data...',
                    text: 'Silakan tunggu',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.get(url)
                    .done(function(data) {
                        // Tampilkan data di divFormEdit
                        $("#divFormEdit").html(data);

                        // Inisialisasi Select2
                        $("#kd_jab").select2();

                        // Tutup alert loading dan tampilkan sukses
                        Swal.fire({
                            icon: 'success',
                            title: 'Data Ditemukan',
                            text: `Form untuk ${nama} berhasil dimuat!`
                        });

                        // Scroll ke form
                        // $('html, body').animate({
                        //     scrollTop: $("#divFormEdit").offset().top
                        // }, 500);
                    })
                    .fail(function(jqXHR, textStatus, errorThrown) {
                        // Tutup alert loading dan tampilkan error
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Memuat Data',
                            text: `Terjadi kesalahan: ${jqXHR.status} - ${errorThrown}`
                        });
                    });
            }

            function updatePegawai() {
                var formData = new FormData(document.getElementById("pegawaiForm"));
                var nip = document.getElementById("nip").value;
                $.ajax({
                    url: "/api/pegawai/update/" + nip,
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log(response);
                        Swal.fire({
                            icon: "success",
                            title: "Data Berhasil Diubah",
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $('#divTablePegawai').html('');
                        $('#divTablePegawai').html(response.data);
                        setTablePedawaiDataTable();

                        $('#divFormEdit').html('');
                    },
                    error: function(xhr) {
                        console.error("Error:", xhr.responseText);
                        Swal.fire({
                            icon: "error",
                            title: "Gagal Mengubah Data",
                            text: "Terjadi kesalahan, silakan coba lagi." + xhr.responseText,
                        });
                    },
                });

            }

            function batal() {
                $('#divFormEdit').html('');
            }

            var tglAwal;
            var tglAkhir;

            function setTablePedawaiDataTable() {
                $('#pegawaiTable').DataTable({
                    paging: true,
                    order: [
                        [1, "asc"]
                    ],
                    lengthMenu: [
                        [5, 10, 25, 50, -1],
                        [5, 10, 25, 50, "All"]
                    ],
                    pageLength: 5,
                    responsive: true,
                    autoWidth: false,
                    scrollX: true
                });
            }

            window.addEventListener("load", function() {
                setTodayDate();
                var today = new Date().toISOString().split("T")[0];
                $("#tanggal").val(today);
                setTablePedawaiDataTable();


                // Inisialisasi tglAwal dan tglAkhir sebagai objek Moment.js
                // tglAwal = moment().subtract(30, "days").format("YYYY-MM-DD");
                tglAwal = moment().subtract(0, "days").format("YYYY-MM-DD");
                tglAkhir = moment().subtract(0, "days").format("YYYY-MM-DD");
                console.log("🚀 ~ tglAwal:", tglAwal)
                console.log("🚀 ~ tglAkhir:", tglAkhir)
                // Menetapkan nilai ke input tanggal
                $("#tglEkin").val(tglAwal + " to " + tglAkhir);

                // Date range picker
                $("#tglEkin").daterangepicker({
                    startDate: tglAwal,
                    endDate: tglAkhir,
                    autoApply: true,
                    locale: {
                        format: "YYYY-MM-DD",
                        separator: " to ",
                        applyLabel: "Apply",
                        cancelLabel: "Cancel",
                        customRangeLabel: "Custom Range",
                    },
                });

                $("#tglEkin").on("apply.daterangepicker", function(ev, picker) {
                    tglAwal = picker.startDate.format("YYYY-MM-DD");
                    console.log("🚀 ~ $ ~ tglAwal:", tglAwal)
                    tglAkhir = picker.endDate.format("YYYY-MM-DD");
                    console.log("🚀 ~ $ ~ tglAkhir:", tglAkhir)

                    // Update nilai input dengan tanggal yang baru dipilih
                    $(this).val(tglAwal + " to " + tglAkhir);
                });

            });
        </script>
    @endsection
