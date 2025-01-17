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
                                <a type="button" class="btn btn-warning my-1 edit"
                                    data-kdDx="${item.kdDx}"
                                    data-diagnosa="${item.diagnosa}"
                                    data-mapping="${item.mapping}"
                                    onclick="editDX(this)"><i class="fas fa-pen-to-square"></i></a>
                                <a type="button" class="btn btn-danger my-1 delete"
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
                        clasName: 'text-center col-1'
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
                ]
            });
        }

        function editDX(data) {
            let kdDx = $(data).data('kddx');
            console.log("🚀 ~ editDX ~ kdDx:", kdDx)
            let diagnosa = $(data).data('diagnosa');
            let mapping = $(data).data('mapping');

            $('#kdDx').val(kdDx);
            $('#diagnosa').val(diagnosa);
            $('#mapping').val(mapping);
        }

        function deleteDX(data) {
            let id = $(data).data('id');
            let tanggal = $(data).data('tanggal');
            let jumlah = $(data).data('jumlah');
            let asal_pendapatan = $(data).data('asal_pendapatan');
            let penyetor = $(data).data('penyetor');

            Swal.fire({
                title: 'Hapus Pendapatan Lain?',
                text: 'Data pendapatan lain "' + asal_pendapatan + '" dengan jumlah ' + jumlah + ' pada tanggal ' +
                    tanggal + ' akan dihapus!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Hapus',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/api/pendapatanLain/delete',
                        type: 'POST',
                        data: {
                            id: id,
                        },
                        success: function(response) {
                            if (response.status == 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message,
                                    showConfirmButton: true,
                                    timer: 1500
                                });
                                drawTable(
                                    data
                                );
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

        function simpanDX() {
            let id = $('#id').val();
            let tanggal = $('#tanggal').val();
            let pendapatan = parseNumber($('#pendapatan').val())
            let setoran = parseNumber($('#setoran').val())
            let asal_pendapatan = $('#asal_pendapatan').val();
            let penyetor = $('#penyetor').val();
            let noSbs = $('#noSbs').val();
            if (tanggal == "" || pendapatan == "" || setoran == "" || asal_pendapatan == "" || penyetor == "") {
                tampilkanEror('Data belum lengkap');
                return
            }
            let method = id == "" ? 'POST' : 'PUT';
            let url = id == "" ? '/api/pendapatanLain/simpan' : '/api/pendapatanLain/ubah/' + id;
            tampilkanLoading();
            $.ajax({
                url: url,
                type: method,
                data: {
                    id: id,
                    noSbs: noSbs,
                    tanggal: tanggal,
                    pendapatan: pendapatan,
                    setoran: setoran,
                    asal_pendapatan: asal_pendapatan,
                    penyetor: penyetor,
                },
                success: function(response) {
                    console.log("🚀 ~ simpan ~ response:", response)
                    if (response.status == 'success') {
                        tampilkanSukses(response.message);

                        data = response.data
                        drawTable(
                            data
                        );
                        resetForm();
                    }
                },
                error: function(xhr, status, error) {
                    console.log(error);
                    tampilkanEror('Terjadi kesalahan saat menyimpan data. Silakan coba lagi.' + error);
                }
            });
        }

        function resetForm(idForm) {
            if (idForm == "lainnya") {
                document.getElementById("form_input").reset();
            } else {
                document.getElementById("form_input_tutup_kas").reset();
            }
        }

        async function getData() {
            // Ambil nilai tahun dari elemen input
            const tahun = $('#tahun').val();

            // Validasi nilai input
            if (!tahun) {
                console.error('Tahun tidak valid atau kosong');
                tampilkanEror('Harap isi tahun terlebih dahulu!');
                return;
            }

            // Tampilkan indikator loading
            tampilkanLoading();

            try {
                // Fetch data dari API
                const response = await fetch('/api/kasir/setoran/' + tahun);

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
