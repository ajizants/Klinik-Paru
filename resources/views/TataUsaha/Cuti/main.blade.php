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
                <a type="button" class="nav-link active bg-blue" onclick="toggleSections('#tab_1');"><b>Pengajuan
                        Cuti</b></a>
            </li>
            <li class="nav-item">
                <a type="button" class="nav-link " onclick="toggleSections('#tab_2');"><b>Laporan WA</b></a>
            </li>
            <li class="nav-item">
                <a type="button" class="nav-link " onclick="toggleSections('#tab_3'); "><b>Sisa Cuti</b></a>
            </li>
            <li class="nav-item">
                <a type="button" class="nav-link " onclick="toggleSections('#tab_4'); "><b>Hari Libur Tahunan</b></a>
            </li>
        </ul>
    </div>
    @include('TataUsaha.Cuti.input')
    <div id="divModalPermohonanCuti">
        @include('TataUsaha.Cuti.modal')
    </div>

    <!-- my script -->
    <script src="{{ asset('js/template.js') }}"></script>
    <script>
        var tglAwal;
        var tglAkhir;

        let htmlPermohonan = @json($dataPengajuanCuti);
        let cutiHariIni = @json($cutiHariIni);
        let sisaCutiUser = @json($sisaCutiUser);
        let sisaCutiAll = @json($sisaCutiAll);
        let cutiTambahan = @json($cutiTambahan);
        let hariLibur = @json($hariLibur);
        // console.log("🚀 ~ cutiTambahan:", cutiTambahan)

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
            generateTabelTambahanCuti(cutiTambahan);
            generateTabelSisaCuti(sisaCutiAll);
            $('#dataCutiWa').html(cutiHariIni);
            tampilkanInfoCuti(sisaCutiUser);
            generateTabelHariLibur(hariLibur);
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
                    generateTabelSisaCuti(data.html);
                    Swal.close();
                },
                error: function(xhr) {
                    // console.log("🚀 ~ cariDataSisaCuti ~ xhr:", xhr)
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
                    // console.log("🚀 ~ cariDataCutiHari ~ data:", data.html)
                    $('#dataCutiWa').html(data.html);
                    Swal.close();
                },
                error: function(xhr) {
                    tampilkanEror(xhr.response)
                }
            })
        }

        function showFormCuti() {
            $.ajax({
                url: "/tu/cuti/form",
                type: "GET",
                success: function(data) {
                    console.log("🚀 ~ showFormCuti ~ data:", data)
                    $('#divModalPermohonanCuti').html(data);
                    $('#modal-pengajuanCuti').modal('show');
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
                    generateTabelSisaCuti(sisaCutiAll);
                    tampilkanInfoCuti(data.sisaCuti);
                    // Swal.close();
                }
            })
        }

        function hapusPermohonanCuti(id) {
            Swal.fire({
                title: 'Apakah anda yakin ingin menghapus data ini?',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
                showLoaderOnConfirm: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "/tu/cuti/hapus/" + id,
                        type: "GET",
                        success: function(data) {
                            tampilkanSukses(data.message);
                            generateTabelPermohonanCuti(data.html);
                            generateTabelSisaCuti(sisaCutiAll);
                            tampilkanInfoCuti(data.sisaCuti);
                            // Swal.close();
                        },
                        error: function(xhr) {
                            tampilkanEror(xhr.response)
                        }
                    })
                }
            })
        }

        function editPermohonanCuti(id) {
            Swal.fire({
                title: 'Apakah anda yakin ingin mengupdate data ini?',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
                showLoaderOnConfirm: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "/tu/cuti/edit/" + id,
                        type: "GET",
                        success: function(res) {
                            Swal.close();
                            Toast.fire({
                                icon: 'success',
                                title: 'Data berhasil diambil'
                            })
                            $('#divModalPermohonanCuti').html(res.data);
                            $('#modal-pengajuanCuti').modal('show');

                        },
                        error: function(xhr) {
                            tampilkanEror(xhr.response)
                        }
                    })
                }
            })
        }

        function updatePermohonan(id) {
            const formData = new FormData(document.getElementById('formCuti'));
            formData.append('id', id);
            fetch('/tu/cuti/update/' + id, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: formData
                })
                .then(async res => {
                    const data = await res.json();

                    if (!res.ok) {
                        throw data; // lempar pesan dari server (misalnya 422)
                    }

                    tampilkanSukses(data.message || 'Pengajuan cuti berhasil dikirim.');
                    // formData.reset();
                    $('#modal-pengajuanCuti').modal('hide');
                    generateTabelPermohonanCuti(data.html);
                    generateTabelSisaCuti(sisaCutiAll);
                    tampilkanInfoCuti(sisaCutiUser);
                })
                .catch(err => {
                    console.error(err);
                    tampilkanEror(err.message || "Terjadi kesalahan saat mengirim pengajuan cuti.");
                });
        }

        function generateTabelPermohonanCuti(data) {
            //jikadata tabel exsiskosongkan

            $('#divTabelDaftarCuti').html(data);
            $('#tabelDaftarPermohonanCuti').DataTable({
                language: {
                    emptyTable: "Tidak ada data cuti untuk bulan ini."
                },
                "destroy": true,
                "lengthChange": true,
                "pageLength": 5,
                "lengthMenu": [5, 10, 25, 50],
                "info": true,
                "autoWidth": false,
                "searching": true,
                "paging": true,
                "order": [
                    [0, "dsc"],
                    // [1, "desc"],
                    [7, "desc"],
                ],
            });
        }

        function generateTabelTambahanCuti(data) {
            $('#divTabelDaftarCutiTambahan').html(data);
            $('#tabelDaftarTambahanCuti').DataTable({
                language: {
                    emptyTable: "Tidak ada data tambahan cuti."
                },
                "destroy": true,
                "lengthChange": true,
                "pageLength": 5,
                "lengthMenu": [5, 10, 25, 50],
                "info": true,
                "autoWidth": false,
                "searching": true,
                "paging": true,
                "order": [
                    [1, "asc"],
                ],
            });
        }

        function generateTabelSisaCuti(data) {
            // console.log("🚀 ~ generateTabelSisaCuti ~ generateTabelSisaCuti:", generateTabelSisaCuti)
            $('#divTabelDaftarSisaCuti').html(data);
            $('#tabelDaftarSisaCuti').DataTable({
                "destroy": true,
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

        function generateTabelHariLibur(data) {
            // console.log("🚀 ~ generateTabelSisaCuti ~ generateTabelSisaCuti:", generateTabelSisaCuti)
            $('#divTabelDaftarHariLibur').html(data);
            $('#TabelDaftarHariLibur').DataTable({
                "destroy": true,
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
            console.log("🚀 ~ tampilkanInfoCuti ~ dataArray:", dataArray)
            const container = document.getElementById('dataCutiPegawai');
            container.innerHTML = ''; // Kosongkan dulu

            dataArray.forEach((item) => {
                const card = `
                                <div class="card shadow-sm border">
                                    <div class="card-header p-2">
                                        <h6 class="card-title font-weight-bold">Informasi Cuti Pegawai</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-row">
                                            <p class="mb-1 col-md">Nama:<br><strong> ${item.nama}</strong>
                                            <p class="mb-1 col-md">NIP/NIK:<br><strong> ${item.nip}</strong>
                                        </div>
                                        <div class="form-row">
                                            <p class="mb-1 col">Jatah Cuti:<br><strong> ${item.totalJatahCuti} hari</strong>
                                                <p class="mb-1 col">Tambahan Cuti:<br><strong> ${item.jumlahCutiTambahan} hari</strong>
                                            </div>
                                        <div class="form-row">
                                            <p class="mb-1 col">Menunggu:<br><strong> ${item.jumlahCutiDiambil - item.jumlahCutiDisetujui} hari</strong>
                                            <p class="mb-1 col">Disetujui:<br><strong> ${item.jumlahCutiDisetujui} hari</strong>
                                        </div>
                                        <div class="form-row">
                                            <p class="mb-0 col">Ditolak:<br><strong> ${item.jumlahCutiDitolak} hari</strong>
                                            <p class="mb-0 col">Sisa Cuti:<br><strong> ${item.jumlahSisaCutiTahunan} hari</strong>
                                        </div>
                                    </div>
                                </div>
        `;
                container.insertAdjacentHTML('beforeend', card);
            });
        }


        function editPegawai(nip, nama) {
            const url = `/api/pegawai/${nip}`;

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
                    $("#kd_jenjang").select2();

                    Toast.fire({
                        icon: 'success',
                        title: 'Data Ditemukan',
                        text: `Form untuk ${nama} berhasil dimuat!`
                    })

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
                    // console.log(response);
                    Swal.fire({
                        icon: "success",
                        title: "Data Berhasil Diubah",
                        showConfirmButton: false,
                        timer: 1500
                    });
                    const tgl = new Date();
                    const tahun_cuti = tgl.getFullYear();
                    cariDataSisaCuti(tahun_cuti)

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
    </script>
@endsection
