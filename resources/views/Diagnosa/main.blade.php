@extends('Template.lte')

@section('content')
    @include('Diagnosa.input')


    <script src="{{ asset('js/template.js') }}"></script>
    <script src="{{ asset('js/populate.js') }}"></script>
    <script type="text/javascript">
        let data = @json($data);
        console.log("🚀 ~ data:", data)

        $(document).ready(function() {
            drawTable(data);
        })

        function drawTable(data) {
            console.log("🚀 ~ drawTable ~ data:", data)
            if ($.fn.DataTable.isDataTable('#dataDx')) {
                $('#dataDx').DataTable().destroy();
            }

            data.forEach(function(item, index) {
                item.no = index + 1
                item.aksi = `
                                <a type="button" class="btn btn-warning my-1"
                                    data-kdDx="${item.kdDx}"
                                    data-diagnosa="${item.diagnosa}"
                                    data-mapping="${item.mapping}"
                                    onclick="editDX(this)"><i class="fas fa-pen-to-square"></i></a>
                                <a type="button" class="btn btn-danger my-1"
                                    data-kdDx="${item.kdDx}"
                                    data-diagnosa="${item.diagnosa}"
                                    data-mapping="${item.mapping}"
                                    onclick="deleteDX(this)"><i class="fas fa-trash"></i></a>
                            `;
            });

            $('#dataDx').DataTable({
                data: data,
                columns: [{
                        data: 'aksi',
                        clasName: 'text-center col-2'
                    },
                    {
                        data: 'no'
                    },
                    {
                        data: 'kdDx'
                    },
                    {
                        data: 'diagnosa'
                    },
                    {
                        data: 'mapping'
                    },
                ],
                order: [
                    [1, 'asc']
                ],
                autowidth: false
            });
        }

        function editDX(data) {
            let kdDx = $(data).data('kddx');
            let diagnosa = $(data).data('diagnosa');
            let mapping = $(data).data('mapping');

            console.log("🚀 ~ editDX ~ kdDx:", kdDx);

            // Tambahkan opsi manual ke select2
            let newOption = new Option(diagnosa, diagnosa, true, true);
            $('#icdx').append(newOption).trigger('change');

            // Isi input lainnya
            $('#kdDx').val(kdDx);
            $('#diagnosa').val(diagnosa);
            $('#mapping').val(mapping);

            // Sembunyikan tombol simpan dan tampilkan tombol update
            $('#btnSimpan').hide();
            $('#btnUpdate').show();
        }


        function deleteDX(data) {
            let kdDx = $(data).data('kddx');
            let diagnosa = $(data).data('diagnosa');
            let mapping = $(data).data('mapping');

            Swal.fire({
                title: 'Hapus Diagnosa Mapping?',
                text: 'Data Diagnosa ' + diagnosa + ' dengan mapping ' + mapping + ' akan dihapus!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Hapus',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/api/dxMapping/' + kdDx,
                        type: 'DELETE',
                        success: function(response) {
                            console.log("🚀 ~ deleteDX ~ response:", response)

                            if (response.success == true) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message,
                                    showConfirmButton: true,
                                    timer: 1500
                                });
                                drawTable(
                                    response.dxMaps
                                );
                                $('#btnSimpan').show();
                                $('#btnUpdate').hide();
                                resetForm();
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log(error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: 'Terjadi kesalahan saat menghapus data.',
                            });
                        }
                    });
                }
            });
        }

        function simpanDX(simpan) {
            let kdDx = $('#kdDx').val();
            let diagnosa = $('#diagnosa').val();
            let mapping = $('#mapping').val();
            if (kdDx == "" || diagnosa == "" || mapping == "") {
                tampilkanEror('Data belum lengkap');
                return
            }
            let url = simpan == true ? '/api/dxMapping/simpan' : '/api/dxMapping/update';
            tampilkanLoading();
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    kdDx: kdDx,
                    diagnosa: diagnosa,
                    mapping: mapping
                },
                success: function(response) {
                    console.log("🚀 ~ simpan ~ response:", response)
                    if (response.success == true) {
                        tampilkanSuccess(response.message);

                        data = response.dxMaps
                        drawTable(
                            data
                        );
                        resetForm();
                    } else {
                        tampilkanEror(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.log(error);
                    console.log("🚀 ~ simpanDX ~ xhr:", xhr)
                    tampilkanEror('Terjadi kesalahan saat menyimpan data. Silakan coba lagi.' + error + xhr
                        .responseJSON.message);
                }
            });
        }

        function resetForm() {
            document.getElementById("form_input").reset();
            // Hapus semua opsi yang ada di dropdown diagnosa
            $('#icdx').empty();
            $('#diagnosa').val("");
            $('#btnSimpan').show();
            $('#btnUpdate').hide();
        }

        async function getData() {

            // Tampilkan indikator loading
            tampilkanLoading();

            try {
                // Fetch data dari API
                const response = await fetch('/api/dxMapping');

                // Cek apakah respons berhasil
                if (!response.ok) {
                    const errorText = await response.text(); // Ambil detail error
                    console.error('Error detail:', errorText);
                    tampilkanEror('Terjadi kesalahan saat mengambil data. Silakan coba lagi.' + errorText);
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }

                // Ambil data dalam format JSON
                let data;
                try {
                    data = await response.json();
                } catch (parseError) {
                    console.error('Error parsing JSON:', parseError);
                    tampilkanEror('Error parsing JSON:', parseError);
                    throw new Error('Gagal mem-parsing respons JSON dari server');
                }

                // Panggil fungsi untuk menggambar tabel dengan data yang diterima
                drawTable(data);

            } catch (error) {
                // Tangani error jika ada
                console.error('Error fetching data:', error);
                tampilkanEror('Error fetching data:', error);

            } finally {
                // Sembunyikan indikator loading, baik berhasil maupun gagal
                swal.close()
            }
        }
    </script>
@endsection
