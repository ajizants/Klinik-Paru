@extends('Template.lte')

@section('content')
    @php
        $email = Auth::user()->email;
        $email = explode('@', $email);
        $nip = $email[0];

    @endphp
    <div class="container-fluid">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a type="button" class="nav-link active bg-blue" onclick="toggleSections('#tab_1');"><b>Daftar Pengajuan
                        Cuti</b></a>
            </li>
            <li class="nav-item">
                <a type="button" class="nav-link " onclick="toggleSections('#tab_2');"><b>Laporan WA</b></a>
            </li>
            <li class="nav-item">
                <a type="button" class="nav-link " onclick="toggleSections('#tab_3'); "><b>Sisa Cuti</b></a>
            </li>
        </ul>
    </div>
    @include('TataUsaha.Cuti.input')
    @include('TataUsaha.Cuti.modal')

    <!-- my script -->
    <script src="{{ asset('js/template.js') }}"></script>
    <script>
        var tglAwal;
        var tglAkhir;

        let htmlPermohonan = @json($html);
        let cutiHariIni = @json($cutiHariIni);
        let sisaCutiUser = @json($sisaCutiUser);
        let sisaCutiAll = @json($sisaCutiAll);

        window.addEventListener("load", function() {
            setTodayDate();
            var today = new Date().toISOString().split("T")[0];
            $("#tanggal").val(today);

            // Inisialisasi tglAwal dan tglAkhir sebagai objek Moment.js
            // tglAwal = moment().subtract(30, "days").format("YYYY-MM-DD");
            tglAwal = moment().subtract(0, "days").format("YYYY-MM-DD");
            tglAkhir = moment().subtract(0, "days").format("YYYY-MM-DD");


            // Menetapkan nilai ke input tanggal
            $("#tglCuti, #tglJumlah").val(tglAwal + " to " + tglAkhir);

            // Date range picker
            $("#tglCuti, #tglJumlah").daterangepicker({
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

            generateTabelPermohonanCuti(htmlPermohonan);
            generateTabelSisaCuti(sisaCutiAll);
            $('#dataCutiWa').html(cutiHariIni);
            tampilkanInfoCuti(sisaCutiUser);

        });

        function cariDataCuti(bulan) {
            tampilkanLoading('Sedangan Mencari Data Cuti...');
            $.ajax({
                url: "/tu/cuti/bulan/" + bulan,
                type: "GET",
                success: function(data) {
                    generateTabelPermohonanCuti(data.html);
                    Swal.close();
                }
            })
        }

        function cariDataSisaCuti(tahun_cuti) {
            tampilkanLoading('Sedang Mencari Data Sisa Cuti Pegawai...');
            $.ajax({
                url: "/tu/cuti/sisa/get",
                type: "GET",
                data: {
                    tahun_cuti: tahun_cuti
                }, // tambahkan parameter di sini
                success: function(data) {
                    generateTabelPermohonanCuti(data.html);
                    Swal.close();
                },
                error: function(xhr) {
                    console.log("🚀 ~ cariDataSisaCuti ~ xhr:", xhr)
                    tampilkanEror(xhr.responseJSON.message)
                }
            });
        }


        function cariDataCutiHari() {
            const tgl = $('#tgl_Cuti').val();
            tampilkanLoading('Sedangan Mencari Data Cuti...');
            $.ajax({
                url: "/tu/cuti/hari/" + tgl,
                type: "GET",
                success: function(data) {
                    console.log("🚀 ~ cariDataCutiHari ~ data:", data.html)
                    $('#dataCutiWa').html(data.html);
                    Swal.close();
                },
                error: function(xhr) {
                    tampilkanEror(xhr.response)
                }
            })
        }

        function persetujuanCuti(id, persetujuan) {
            tampilkanLoading('Sedangan Menyetujui Cuti...');
            $.ajax({
                url: "/tu/cuti/persetujuan/" + id + "/" + persetujuan,
                type: "GET",
                success: function(data) {
                    tampilkanSukses(data.message);
                    generateTabelPermohonanCuti(data.html);
                    Swal.close();
                }
            })
        }

        function generateTabelPermohonanCuti(data) {
            //jikadata tabel exsiskosongkan

            $('#divTabelDaftarCuti').html(data);
            $('#tabelDaftarPermohonanCuti').DataTable({
                "responsive": true,
                "lengthChange": true,
                "pageLength": 5,
                "lengthMenu": [5, 10, 25, 50],
                "info": true,
                "autoWidth": false,
                "searching": true,
                "paging": true,
                "order": [
                    // [1, "asc"],
                    [6, "desc"]
                ],

            });
        }

        function generateTabelSisaCuti(data) {
            $('#divTabelDaftarSisaCuti').html(data);
            $('#tabelDaftarSisaCuti').DataTable({
                "responsive": true,
                "lengthChange": true,
                "pageLength": 5,
                "lengthMenu": [5, 10, 25, 50],
                "info": true,
                "autoWidth": false,
                "searching": true,
                "paging": true,
                "order": [
                    [1, "asc"]
                ],

            });
        }

        function tampilkanInfoCuti(dataArray) {
            const container = document.getElementById('dataCutiPegawai');
            container.innerHTML = ''; // Kosongkan dulu

            dataArray.forEach((item) => {
                const card = `
                    <div class="card shadow-sm border">
                        <div class="card-body">
                            <div class="form-row">
                                    <p class="mb-1 col">Nama:<strong> ${item.nama}</strong></p>
                                    <p class="mb-1 col">Jatah Cuti:<strong> ${item.jatah_cuti}</strong></p>
                                    <p class="mb-1 col">Diambil:<strong> ${item.jumalhCutiDiambil}</strong></p>
                                </div>
                                <div class="form-row">
                                    <p class="mb-1 col">NIP/NIK:<strong> ${item.nip}</strong></p>
                                    <p class="mb-1 col">Tambahan Cuti:<strong> ${item.tambahan_cuti}</strong></p>
                                    <p class="mb-0 col">Sisa Cuti:<strong> ${item.jumlahSisaCuti}</strong></p>
                            </div>
                        </div>
                    </div>
        `;
                container.insertAdjacentHTML('beforeend', card);
            });
        }

        // function tampilkanInfoCuti(data) {
        //     let html = '';
        //     data.forEach(item => {
        //         html += `
    //                 <tr style="background-color:rgba(0,0,0,.05);">
    //                     <th class="col-2">Nama</th>
    //                     <td class="">: ${item.nama}</td>
    //                     <th class="col-2">Jatah Cuti</th>
    //                     <td class="">: ${item.jatah_cuti}</td>
    //                     <th class="col-2">Tambahan Cuti</th>
    //                     <td class="">: ${item.tambahan_cuti}</td>
    //                 </tr>
    //                 <tr style="background-color:rgba(0,0,0,.05);">
    //                     <th class="col-2">NIP</th>
    //                     <td class="">: ${item.nip}</td>
    //                     <th class="col-2">Diambil</th>
    //                     <td class="">: ${item.jumalhCutiDiambil}</td>
    //                     <th class="col-2">Sisa Cuti</th>
    //                     <td class="">: ${item.jumlahSisaCuti}</td>
    //                 </tr>
    //             `;
        //     });
        //     document.getElementById('tabelInfoCuti').innerHTML = html;
        // }
    </script>
@endsection
