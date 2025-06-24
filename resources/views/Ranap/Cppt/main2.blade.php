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
                                <a class="nav-link active" id="nav-cppt-tab" data-toggle="tab" href="#nav-cppt"
                                    role="tab" aria-controls="nav-cppt" aria-selected="true"><b>CPPT</b></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="nav-asAwal-tab" data-toggle="tab" href="#nav-asAwal" role="tab"
                                    aria-controls="nav-asAwal" aria-selected="true"><b>Assesment Awal</b></a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body mx-0 p-0">
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                            <div class="tab-pane fade show active" id="nav-cppt" role="tabpanel"
                                aria-labelledby="nav-cppt-tab">
                                <!-- Konten Tab 1 -->
                                @if ($role == 'dokter' || $role == 'dpjp')
                                    @include('Ranap.Cppt.Dokter.main')
                                @elseif ($role == 'perawat')
                                    @include('Ranap.Cppt.Perawat.main')
                                @endif
                            </div>
                            <div class="tab-pane fade" id="nav-asAwal" role="tabpanel" aria-labelledby="nav-asAwal-tab">
                                <!-- Konten Tab 1 -->
                                @include('Ranap.Cppt.asesment')
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- my script -->
    <script src="{{ asset('js/template.js') }}"></script>
    <script src="{{ asset('js/mainGizi.js') }}"></script>
    <script src="{{ asset('js/cppt.js') }}"></script>
    <script>
        $(document).ready(function() {
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
        });
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
            height: 119,
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

        document.getElementById('pasien_no_rm').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                let val = this.value.trim();

                // Tambahkan 0 di depan hingga panjangnya 6 digit
                if (val.length < 6) {
                    this.value = val.padStart(6, '0');
                }

                // Optional: panggil ulang fungsi lihatIdentitas
                lihatIdentitas(this.value);

                // Cegah submit form jika perlu
                e.preventDefault();
            }
        });

        function lihatIdentitas(no_rm) {
            // Ambil nilai dari input form saat ini
            let current_rm = $("#pasien_no_rm").val();

            // Tambahkan 0 di depan hingga panjangnya 6 digit
            if (current_rm.length < 6) {
                this.value = current_rm.padStart(6, '0');
            }

            tampilkanLoading("Sedangan mengambil data pasien...");
            // Lakukan request ke API untuk ambil data baru
            $.ajax({
                url: "/api/pasienKominfo",
                method: "POST",
                data: {
                    no_rm: current_rm
                },
                success: function(response) {
                    if (response && response.error) {
                        tampilkanEror(response.error); // tampilkan pesan error
                        return;
                    }
                    Swal.close();
                    // Isi form dengan data dari response
                    for (let key in response) {
                        $(`#form_identitas [name="${key}"]`).val(response[key]);
                    }

                },
                error: function(xhr, error) {
                    console.log("🚀 ~ lihatIdentitas ~ xhr:", xhr)
                    tampilkanEror(xhr.responseJSON.error);
                    // tampilkanEror(error);
                }
            });
        }

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
