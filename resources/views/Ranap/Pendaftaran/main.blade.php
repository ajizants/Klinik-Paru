@extends('Template.lte')

@section('content')
    @include('Ranap.Pendaftaran.input')

    <!-- my script -->
    <script src="{{ asset('js/template.js') }}"></script>
    <script src="{{ asset('js/populate.js') }}"></script>
    <script>
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
                        $(`#formPendaftaran [name="${key}"]`).val(response[key]);
                    }

                },
                error: function(xhr, error) {
                    console.log("🚀 ~ lihatIdentitas ~ xhr:", xhr)
                    tampilkanEror(xhr.responseJSON.error);
                    // tampilkanEror(error);
                }
            });
        }

        // function simpanPendaftaran() {
        //     let form = document.getElementById('formPendaftaran');
        //     let formData = new FormData(form);

        //     tampilkanLoading("Sedangan menyimpan data...");
        //     $.ajax({
        //         url: "/api/ranap/pendaftaran",
        //         method: "POST",
        //         data: formData,
        //         contentType: false,
        //         processData: false,
        //         success: function(response) {
        //             console.log("🚀 ~ simpanPendaftaran ~ response:", response)
        //             Swal.close();
        //             if (response.success == true) {
        //                 tampilkanSukses(response.message);
        //                 form.reset();
        //                 $('#pasien_no_rm').focus();
        //                 $('#jaminan').triger('change');
        //                 //kosongkan option ruang dan isi dengan data ruangan dari respon
        //                 $('#ruang').empty();
        //                 $('#ruang').append('<option value="">--Pilih Ruang--</option>');
        //                 response.ruangan.forEach(element => {
        //                     $('#ruang').append('<option value="' + element.id + '">' + element
        //                         .nama_ruangan + '</option>');
        //                 });
        //             } else {
        //                 tampilkanEror(response.message);
        //             }
        //         },
        //         error: function(xhr) {
        //             console.log("🚀 ~ simpanPendaftaran ~ xhr:", xhr.responseJSON)
        //             tampilkanEror(xhr.responseJSON.message);
        //         }
        //     });
        // }

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


        function simpanPendaftaran() {
            let form = document.getElementById('formPendaftaran');
            let formData = new FormData(form);

            tampilkanLoading("Sedang menyimpan data...");
            $.ajax({
                url: "/api/ranap/pendaftaran",
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    console.log("🚀 ~ simpanPendaftaran ~ response:", response);
                    Swal.close();

                    if (response.success === true) {
                        tampilkanSukses(response.message);
                        form.reset();
                        $('#pasien_no_rm').focus();
                        $('#jaminan').trigger('change');
                        drawTablePasienRanap(response.table);

                        generateOptionRuang(response.ruangan);
                    } else {
                        tampilkanEror(response.message);

                        // Tetap isi ulang ruang jika tersedia di response
                        if (response.ruangan) {
                            $('#ruang').empty().append('<option value="">--Pilih Ruang--</option>');
                            response.ruangan.forEach(element => {
                                $('#ruang').append('<option value="' + element.id + '">' + element
                                    .nama_ruangan + '</option>');
                            });
                        }
                    }
                },
                error: function(xhr) {
                    console.log("🚀 ~ simpanPendaftaran ~ xhr:", xhr.responseJSON);
                    Swal.close();
                    tampilkanEror(xhr.responseJSON?.message || 'Terjadi kesalahan saat menyimpan.');
                }
            });
        }


        function deletePasienRanap(id) {
            Swal.fire({
                title: "Apakah anda yakin ingin menghapus data ini?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "YA",
                cancelButtonText: "TIDAK",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/api/ranap/pendaftaran/${id}`,
                        method: "DELETE",
                        success: function(response) {
                            if (response.success === true) {
                                tampilkanSukses(response.message);
                                // Hapus baris dari tabel
                                $(`#row-${id}`).remove();
                                generateOptionRuang(response.ruangan);
                            } else {
                                tampilkanEror(response.message);
                            }
                        },
                        error: function(xhr) {
                            console.log("🚀 ~ deletePasienRanap ~ xhr:", xhr.responseJSON);
                            tampilkanEror(xhr.responseJSON.message);
                        }
                    });
                }
            });
        }

        function generateOptionRuang(data) {
            // Isi ulang opsi ruang
            $('#ruang').empty().append('<option value="">--Pilih Ruang--</option>');
            data.forEach(element => {
                $('#ruang').append('<option value="' + element.id + '">' + element
                    .nama_ruangan + '</option>');
            });
        }


        function editPasienRanap(id) {
            tampilkanLoading("Sedang Mencari data...");
            $.ajax({
                url: `/api/ranap/pendaftaran/${id}`,
                method: "GET",
                success: function(response) {
                    console.log("🚀 ~ editePasienRanap ~ response:", response)
                    for (let key in response) {
                        $(`#formPendaftaran [name="${key}"]`).val(response[key]);
                    }
                    $('#jaminan').trigger('change');
                    // tambahkan opton di #ruang                  
                    $('#ruang').append('<option value="' + response.ruang + '" selected>' + response
                        .nama_ruangan + '</option>');

                    Swal.close();
                },
                error: function(xhr) {
                    console.log("🚀 ~ editePasienRanap ~ xhr:", xhr.responseJSON)
                    tampilkanEror(xhr.responseJSON.message);
                }
            });
        }

        function pulangkanPasien(id, notrans) {
            console.log("🚀 ~ pulangkanPasien ~ notrans:", notrans)
            const tgl_sekarang = new Date();
            let tgl_pulang = tgl_sekarang.toISOString().split('T')[0];
            Swal.fire({
                title: "Apakah anda yakin ingin memulangkan pasien?",
                html: `
                        <label for="tgl_pulang" class="swal2-label">Masukkan tanggal pulang:</label>
                        <input id="tgl_pulang" type="date" class="swal2-input" value="${tgl_pulang || ''}">
                    `,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya",
                cancelButtonText: "Tidak",
                preConfirm: () => {
                    const tgl_pulang = document.getElementById("tgl_pulang").value;

                    if (!tgl_pulang) {
                        Swal.showValidationMessage("Pilih tanggal pulang!");
                    }

                    return {
                        tgl_pulang
                    }; // objek dikembalikan dengan key yang benar
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    const tgl_pulang = result.value.tgl_pulang; // bukan result.value.tanggalSetor!

                    $.ajax({
                        url: `/api/ranap/pendaftaran/pulangkanPasien`,
                        method: "POST",
                        data: {
                            notrans: notrans,
                            tgl_pulang: tgl_pulang
                        },
                        success: function(response) {
                            console.log("🚀 ~ pulangkanPasien ~ response:", response);
                            tampilkanSukses(response.message);
                            $('#row-' + id).remove();
                            Swal.close();
                        },
                        error: function(xhr) {
                            console.log("🚀 ~ pulangkanPasien ~ xhr:", xhr.responseJSON);
                            tampilkanEror(xhr.responseJSON.message);
                        }
                    });
                }
            }).catch((error) => {
                console.log("🚀 ~ pulangkanPasien ~ error:", error);
                tampilkanEror(error.message);
            });
        }

        document.addEventListener("DOMContentLoaded", function() {
            let tablePasienRanap = @json($dataPasien);
            console.log("🚀 ~ tablePasienRanap:", tablePasienRanap)
            drawTablePasienRanap(tablePasienRanap);
        })
    </script>
@endsection
