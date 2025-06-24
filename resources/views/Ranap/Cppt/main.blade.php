@extends('Template.lte')

@section('content')
    <div class="container-fluid">
        <div class="card card-secondary">
            @csrf
            <div class="card-header p-2">
                <h4 class="m-0 font-weight-bold text-center text-light">Catatan Perawatan Pasien Terintegrasi
                </h4>
            </div>
            <div class="card-body p-2">
                <div class="card shadow">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-start">
                        <h6 class="m-0 font-weight-bold text-primary">Pasien Rawat Inap</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive" id="divTablePasienRanap">

                        </div>
                    </div>
                </div>

                @include('Ranap.Cppt.Template.identitas')

                <div class="card card-primary card-tabs">
                    <div class="card-header p-0 pt-1">
                        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link" type="button" data-toggle="modal"
                                    data-target="#modalAssesmentAwal"><b>Assesment Awal</b></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" type="button" data-toggle="modal"
                                    data-target="#modalRiwayatCppt"><b>Riwayat CPPT</b></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" id="nav-dsdo-tab" data-toggle="tab" href="#nav-dsdo"
                                    role="tab" aria-controls="nav-dsdo" aria-selected="true"><b>DS & DO</b></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="nav-dx-tab" data-toggle="tab" href="#nav-dx" role="tab"
                                    aria-controls="nav-dx" aria-selected="true"><b>DA & DP</b></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="nav-tindakan-tab" data-toggle="tab" href="#nav-tindakan"
                                    role="tab" aria-controls="nav-tindakan"
                                    aria-selected="true"onclick="refreshTable('nav-tindakan-tab')"><b>Tindakan</b></a>
                            </li>
                            {{-- <li class="nav-item">
                                <a class="nav-link" id="nav-ro-tab" data-toggle="tab" href="#nav-ro" role="tab"
                                    aria-controls="nav-ro" aria-selected="false"><b>Penunjang</b></a>
                            </li> --}}
                            <li class="nav-item">
                                <a class="nav-link" id="nav-lab-tab" data-toggle="tab" href="#nav-lab" role="tab"
                                    aria-controls="nav-lab" aria-selected="false"
                                    onclick="refreshTable('nav-lab-tab')"><b>Penunjang</b></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="nav-terapi-tab" data-toggle="tab" href="#nav-terapi" role="tab"
                                    aria-controls="nav-terapi" aria-selected="false"><b>Terapi</b></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="nav-resep-tab" data-toggle="tab" href="#nav-resep" role="tab"
                                    aria-controls="nav-resep" aria-selected="false"><b>Resep Pulang</b></a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body mx-0 px-2" style="min-height: 450px">
                        <form id="form_cppt">
                            <div class="tab-content" id="custom-tabs-one-tabContent">
                                <div>
                                    <input type="text" id="norm" name="norm">
                                    <input type="text" id="notrans" name="notrans">
                                    <input type="text" id="form_id" name="form_id">
                                </div>
                                @if ($role == 'dokter' || $role == 'dpjp' || $role == 'admin')
                                    @include('Ranap.Cppt.Dokter.main')
                                @elseif ($role == 'perawat')
                                    @include('Ranap.Cppt.Perawat.main')
                                @elseif ($role == 'terapis')
                                    @include('Ranap.Cppt.Terapis.main')
                                @elseif ($role == 'gizi')
                                    @include('Ranap.Cppt.Gizi.main')
                                @endif
                                @include('Ranap.Cppt.Template.footer')
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- modal --}}
    <div class="modal fade" id="modalAssesmentAwal" tabindex="-1" aria-labelledby="modalAssesmentAwalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xxl modal-dialog-scrollable ">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="modalAssesmentAwalLabel">Assesment Awal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body modal-body bg-antiquewhite">
                    @include('Ranap.Cppt.asesment')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalRiwayatCppt" tabindex="-1" aria-labelledby="modalRiwayatCpptLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xxl">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title" id="modalRiwayatCpptLabel">Catatan Perawatan Pasien Terintegritas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body modal-body bg-antiquewhite">
                    <table id="tabel_riwayat_cppt" class="table table-bordered table-striped" width="100%"
                        cellspacing="0">
                        <thead>
                            <tr>
                                <th style="width: 10px">Aksi</th>
                                <th style="width: 15px">Tanggal</th>
                                <th style="width: 30px">Professional Pemberi Ashuan</th>
                                <th class="col-4">Hasil Assessment Pasien & Pemberian Pelayanan</th>
                                <th class="col-3">Intruksi PPA</th>
                                <th style="width: 30px">Review & Verifikasi DPJP</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    Tombol untuk membuka form atau modal pengisian CPPT/assesment awal pasien.
                                </td>
                                <td>
                                    Tanggal pencatatan assesment atau pelayanan pasien dilakukan.
                                </td>
                                <td>
                                    Nama dan gelar lengkap tenaga kesehatan profesional yang memberikan asuhan.
                                </td>
                                <td>
                                    Uraian hasil pengkajian kondisi pasien dan pelayanan yang diberikan secara detail.
                                </td>
                                <td>
                                    Instruksi medis atau keperawatan lanjutan dari PPA kepada tim lainnya.
                                </td>
                                <td>
                                    Nama DPJP yang melakukan review dan verifikasi terhadap tindakan/assesment.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- my script -->
    <script src="{{ asset('js/template.js') }}"></script>
    <script src="{{ asset('js/cpptGizi.js') }}"></script>
    <script src="{{ asset('js/cppt.js') }}"></script>
    <script>
        let itemPemeriksaan = @json($itemPemeriksaan)


        $(document).ready(function() {

            $("#modalAssesmentAwal .select2bs4").select2();
            $("#modalRiwayatCppt").on("shown.bs.modal", function() {
                let notrans = $("#pasien_notrans").val(); // atau dari data lain
                console.log("🚀 ~ $ ~ notrans:", notrans)
                loadCpptTable(notrans);
            });

            $('#dx1, #dx2, #dx3, #dx4').select2({
                placeholder: 'Cari Diagnosa...',
                ajax: {
                    url: '/api/diagnosa_icd_x',
                    dataType: 'json',
                    delay: 50,
                    data: function(params) {
                        return {
                            search: params.term,
                            limit: 10
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.map(function(item) {
                                return {
                                    id: item.kdDx,
                                    text: item.kdDx + ' - ' + item.diagnosa
                                };
                            })
                        };
                    },
                    cache: true
                }
            });

            // Masukkan diagnosa ke input keterangan setelah dipilih
            $('#dx1, #dx2, #dx3, #dx4').on('select2:select', function(e) {
                const selectedId = $(this).attr('id'); // contoh: dx2
                const num = selectedId.replace('dx', ''); // hasil: 2
                const data = e.params.data;
                // $('#ket_dx' + num).val(data.text); // masukkan ke input keterangan
            });

            handlePilihSemuaClick("pilih-semua", "item");

            tabelPemeriksaan(itemPemeriksaan, "item", "pilih-semua");
        });

        function refreshTable(id) {
            $('#' + id).on('shown.bs.tab', function() {
                setTimeout(() => {
                    $('#daftarOrderPenunjang').DataTable().columns.adjust().draw();
                    $('#dataTindakan').DataTable().columns.adjust().draw();
                    $('#tablePenunjang').DataTable().columns.adjust().draw();
                }, 10); // delay kecil agar render sempat selesai
            });
        }
    </script>
    <script>
        $(function() {
            // Cek apakah event bekerja
            $('#custom-tabs-one-tab a[data-toggle="pill"]').on(
                "shown.bs.tab",
                function(e) {
                    console.log("Berpindah ke tab:", $(e.target).attr("href"));
                }
            );

        });
        $(function() {
            $('#custom-tabs-one-tab a[data-toggle="pill"]').on('click', function(e) {
                e.preventDefault();
                $(this).tab('show'); // paksa show, jika event default tidak bekerja
            });
        });

        $('#objektif, #subjektif').summernote({
            height: 100,
            callbacks: {
                onInit: function() {
                    $('.note-btn').css({
                        'padding': '1px 2px',
                        'font-size': '10px',
                        'line-height': '1'
                    });
                    $('.note-editable p').css({
                        'margin-top': '1px',
                        'margin-bottom': '1px'
                    });
                }
            }
        });
        $('#assesment, #planing').summernote({
            height: 59,
            callbacks: {
                onInit: function() {
                    $('.note-btn').css({
                        'padding': '1px 2px',
                        'font-size': '10px',
                        'line-height': '1'
                    });
                    $('.note-editable p').css({
                        'margin-top': '1px',
                        'margin-bottom': '1px'
                    });
                }
            }
        });

        // document.getElementById('pasien_no_rm').addEventListener('keydown', function(e) {
        //     if (e.key === 'Enter') {
        //         let val = this.value.trim();

        //         // Tambahkan 0 di depan hingga panjangnya 6 digit
        //         if (val.length < 6) {
        //             this.value = val.padStart(6, '0');
        //         }

        //         // Optional: panggil ulang fungsi lihatIdentitas
        //         lihatIdentitas(this.value);

        //         // Cegah submit form jika perlu
        //         e.preventDefault();
        //     }
        // });

        // function lihatIdentitas(no_rm) {
        //     // Ambil nilai dari input form saat ini
        //     let current_rm = $("#pasien_no_rm").val();

        //     // Tambahkan 0 di depan hingga panjangnya 6 digit
        //     if (current_rm.length < 6) {
        //         this.value = current_rm.padStart(6, '0');
        //     }

        //     tampilkanLoading("Sedangan mengambil data pasien...");
        //     // Lakukan request ke API untuk ambil data baru
        //     $.ajax({
        //         url: "/api/pasienKominfo",
        //         method: "POST",
        //         data: {
        //             no_rm: current_rm
        //         },
        //         success: function(response) {
        //             if (response && response.error) {
        //                 tampilkanEror(response.error); // tampilkan pesan error
        //                 return;
        //             }
        //             Swal.close();
        //             // Isi form dengan data dari response
        //             for (let key in response) {
        //                 $(`#form_identitas [name="${key}"]`).val(response[key]);
        //             }

        //         },
        //         error: function(xhr, error) {
        //             console.log("🚀 ~ lihatIdentitas ~ xhr:", xhr)
        //             tampilkanEror(xhr.responseJSON.error);
        //             // tampilkanEror(error);
        //         }
        //     });
        // }

        function simpanPendaftaran() {
            let form = document.getElementById('formPendaftaran');
            let formData = new FormData(form);

            tampilkanLoading("Sedangan menyimpan data...");
            $.ajax({
                url: "/api/ranap/pendaftaran",
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    console.log("🚀 ~ simpanPendaftaran ~ response:", response)
                    Swal.close();
                    if (response.success == true) {
                        tampilkanSukses(response.message);
                        form.reset();
                    } else {
                        tampilkanEror(response.message);
                    }
                },
                error: function(xhr) {
                    console.log("🚀 ~ simpanPendaftaran ~ xhr:", xhr.responseJSON)
                    tampilkanEror(xhr.responseJSON.message);
                }
            });
        }

        function drawTablePasienRanap(data) {
            //    masikan data ke div
            $('#divTablePasienRanap').html(data);
            $('#tablePasienRanap').DataTable({
                responsive: true,
                lengthChange: false,
                autoWidth: false,
                searching: true,
                paging: true,
                order: [
                    [1, 'asc']
                ],
                pageLength: 10,
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            let tablePasienRanap = @json($dataPasien);
            drawTablePasienRanap(tablePasienRanap);
        })
    </script>
@endsection
