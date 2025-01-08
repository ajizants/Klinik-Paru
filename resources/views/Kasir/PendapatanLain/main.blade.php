@extends('Template.lte')

@section('content')
    @include('Kasir.PendapatanLain.input')


    <script src="{{ asset('js/template.js') }}"></script>
    <script src="{{ asset('js/populate.js') }}"></script>
    <script type="text/javascript">
        let dataPendLain = @json($data);
        console.log("🚀 ~ dataPendLain:", dataPendLain)

        $(document).ready(function() {
            drawTable(dataPendLain);
        })
        const jumlah = document.getElementById("jumlah"); // Pastikan ID sesuai

        // Fungsi untuk memformat angka dengan titik setiap 3 digit
        function formatNumber2(value) {
            return value
                .replace(/\D/g, "") // Hapus karakter non-angka
                .replace(/\B(?=(\d{3})+(?!\d))/g, "."); // Tambahkan titik setiap 3 digit
        }

        // Fungsi untuk menghapus format dan mendapatkan nilai asli (numerik)
        function parseNumber(value) {
            return parseFloat(value.replace(/[^\d]/g, "")) || 0;
        }

        // Fungsi untuk menambahkan format Rupiah
        function formatRupiah(value) {
            return `Rp ${formatNumber2(value)}`; // Memanggil formatNumber2 untuk format angka
        }

        // Event untuk memformat input saat mengetik
        [jumlah].forEach((input) => {
            input.addEventListener("input", function() {
                const cursorPosition = input.selectionStart; // Simpan posisi kursor
                input.value = formatNumber2(input.value); // Format angka dengan titik
                input.setSelectionRange(cursorPosition, cursorPosition); // Kembalikan posisi kursor
            });

            input.addEventListener("blur", function() {
                // Tambahkan format Rupiah saat blur
                input.value = formatRupiah(input.value.replace(/\D/g,
                    "")); // Hilangkan karakter non-angka saat blur
            });

            input.addEventListener("focus", function() {
                // Hapus format Rupiah saat fokus untuk mempermudah pengeditan
                input.value = input.value.replace(/[^\d]/g, "");
            });
        });



        function drawTable(data) {
            console.log("🚀 ~ drawTable ~ data:", data)
            if ($.fn.DataTable.isDataTable('#dataPendapatanLain')) {
                $('#dataPendapatanLain').DataTable().destroy();
            }

            data.forEach(function(item, index) {
                item.no = index + 1
                item.aksi = `
                                <a type="button" class="edit"
                                    data-id="${item.id}"
                                    data-tanggal="${item.tanggal}"
                                    data-jumlah="${item.jumlah}"
                                    data-asal_pendapatan="${item.asal_pendapatan}"
                                    data-penyetor="${item.penyetor}"
                                    onclick="editPendLain(this)"><i class="fas fa-pen-to-square pr-3"></i></a>
                                <a type="button" class="delete"
                                    data-id="${item.id}"
                                    data-tanggal="${item.tanggal}"
                                    data-jumlah="${item.jumlah}"
                                    data-asal_pendapatan="${item.asal_pendapatan}"
                                    data-penyetor="${item.penyetor}"
                                    onclick="deletePendLain(this)"><i class="fas fa-trash"></i></a>
                            `;
            });

            $('#dataPendapatanLain').DataTable({
                data: data,
                columns: [{
                        data: 'aksi'
                    },
                    {
                        data: 'no'
                    },
                    {
                        data: 'tanggal'
                    },
                    {
                        data: 'jumlah'
                    },
                    {
                        data: 'asal_pendapatan'
                    },
                    {
                        data: 'penyetor'
                    },
                ],
            });
        }

        function editPendLain(data) {
            let id = $(data).data('id');
            let tanggal = $(data).data('tanggal');
            let jumlah = $(data).data('jumlah');
            let asal_pendapatan = $(data).data('asal_pendapatan');
            let penyetor = $(data).data('penyetor');
            $('#id').val(id);
            $('#tanggal').val(tanggal);
            $('#jumlah').val(jumlah);
            $('#asal_pendapatan').val(asal_pendapatan);
            $('#penyetor').val(penyetor);
            $('#penyetor').trigger('change');
        }

        function deletePendLain(data) {
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
                        url: '/api/pendapatanlain/delete',
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
                                    dataPendLain
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

        function simpanPendLain() {
            let id = $('#id').val();
            let tanggal = $('#tanggal').val();
            let jumlah = parseNumber($('#jumlah').val())
            let asal_pendapatan = $('#asal_pendapatan').val();
            let penyetor = $('#penyetor').val();
            let method = id == "" ? 'POST' : 'PUT';
            let url = id == "" ? '/api/pendapatanLain/simpan' : '/api/pendapatanLain/ubah/' + id;
            $.ajax({
                url: url,
                type: method,
                data: {
                    id: id,
                    tanggal: tanggal,
                    jumlah: jumlah,
                    asal_pendapatan: asal_pendapatan,
                    penyetor: penyetor,
                },
                success: function(response) {
                    console.log("🚀 ~ simpanPendLain ~ response:", response)
                    if (response.status == 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            showConfirmButton: true,
                            timer: 1500
                        });
                        dataPendLain = response.data
                        drawTable(
                            dataPendLain
                        );
                        resetForm();
                    }
                },
                error: function(xhr, status, error) {
                    console.log(error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Terjadi kesalahan saat menyimpan data.',
                    });
                }
            });
        }

        function resetForm() {
            document.getElementById("form_input").reset();
        }

        async function getDataPendLain() {
            const tahun = $('#tahun').val(); // Ambil nilai tahun dari elemen input
            try {
                // Fetch data dari API
                const response = await fetch('/api/pendapatanLain/' + tahun);

                // Pastikan respon berhasil
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                // Ambil data dalam format JSON
                const data = await response.json();

                // Panggil fungsi untuk menggambar tabel dengan data yang diterima
                drawTable(data);
            } catch (error) {
                // Tangani error jika ada
                console.error('Error fetching data:', error);
            }
        }
    </script>
@endsection
