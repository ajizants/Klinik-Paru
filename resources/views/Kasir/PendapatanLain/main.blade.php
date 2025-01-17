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
        // Ambil elemen input berdasarkan ID
        const pendapatan = document.getElementById("pendapatan");
        const setoran = document.getElementById("setoran");
        const saldoKasInput = document.getElementById("saldo_kas");
        const saldoBkuInput = document.getElementById("saldo_bku");
        const selisihInput = document.getElementById("selisih_saldo");

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
            const formattedNumber = formatNumber2(value.replace(/\D/g, "")); // Format angka
            return `Rp ${formattedNumber}`; // Tambahkan "Rp " di depan angka
        }

        // Fungsi untuk menjaga posisi kursor setelah memformat angka
        function setCursorPosition(input, oldValue, cursorPosition) {
            const diff = input.value.length - oldValue.length;
            input.setSelectionRange(cursorPosition + diff, cursorPosition + diff);
        }

        // Fungsi untuk mengisi data penutupan
        function insertPenutupan(data) {
            console.log("🚀 ~ insertPenutupan ~ data:", data);
            $('#total_penerimaan').val('Rp. ' + formatNumber(data.total_penerimaan));
            $('#total_pengeluaran').val('Rp. ' + formatNumber(data.total_pengeluaran));
            $('#saldo_kas').val('Rp. ' + formatNumber(data.saldo_kas));
            $('#saldo_bku').val('Rp. ' + formatNumber(data.saldo_bku));
        }

        // Fungsi untuk memformat angka ke gaya Indonesia
        function formatNumber(value) {
            return Number(value).toLocaleString("id-ID");
        }

        // Event listener untuk menghitung nilai selisih secara real-time
        saldoKasInput.addEventListener("input", () => {
            const saldoKas = parseNumber($('#saldo_kas').val());
            const saldoBku = parseNumber($('#saldo_bku').val());
            const selisih = saldoKas - saldoBku;

            $('#selisih_saldo').val(selisih);
        });

        saldoBkuInput.addEventListener("input", () => {
            const saldoKas = parseNumber($('#saldo_kas').val());
            const saldoBku = parseNumber($('#saldo_bku').val());
            const selisih = saldoKas - saldoBku;

            $('#selisih_saldo').val(formatRupiah(selisih));
        });

        // Event untuk memformat input saat mengetik, blur, atau fokus
        [pendapatan, setoran, saldoKasInput, saldoBkuInput, selisihInput].forEach((input) => {
            input.addEventListener("input", () => {
                const oldValue = input.value; // Simpan nilai lama
                const cursorPosition = input.selectionStart; // Simpan posisi kursor

                input.value = formatNumber2(input.value); // Format angka
                setCursorPosition(input, oldValue, cursorPosition); // Kembalikan posisi kursor
            });

            input.addEventListener("blur", () => {
                input.value = formatRupiah(input.value); // Tambahkan format Rupiah saat blur
            });

            input.addEventListener("focus", () => {
                input.value = input.value.replace(/[^\d]/g,
                    ""); // Hapus format untuk mempermudah pengeditan
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
                                <a type="button" class="btn btn-warning my-1 edit"
                                    data-id="${item.id}"
                                    data-tanggal="${item.tanggal}"
                                    data-pendapatan="${item.pendapatan}"
                                    data-setoran="${item.setoran}"
                                    data-asal_pendapatan="${item.asal_pendapatan}"
                                    data-penyetor="${item.penyetor}"
                                    data-nosbs="${item.noSbs||""}"
                                    onclick="editPendLain(this)"><i class="fas fa-pen-to-square"></i></a>
                                <a type="button" class="btn btn-danger my-1 delete"
                                    data-id="${item.id}"
                                    data-tanggal="${item.tanggal}"
                                    data-pendapatan="${item.pendapatan}"
                                    data-pendapatan="${item.pendapatan}"
                                    data-setoran="${item.setoran}"
                                    data-asal_pendapatan="${item.asal_pendapatan}"
                                    data-penyetor="${item.penyetor}"
                                    onclick="deletePendLain(this)"><i class="fas fa-trash"></i></a>
                            `;
                item.ket = item.asal_pendapatan == "3.003.25581.5" ? "Rawat Jalan" : item.asal_pendapatan;
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
                        data: 'pendapatan',
                        render: function(data, type, row) {
                            var formattedTarif = parseInt(data).toLocaleString(
                                "id-ID", {
                                    style: "currency",
                                    currency: "IDR",
                                    minimumFractionDigits: 0,
                                }
                            );
                            return `${formattedTarif}`;
                        },
                    },
                    {
                        data: 'setoran',
                        render: function(data, type, row) {
                            var formattedTarif = parseInt(data).toLocaleString(
                                "id-ID", {
                                    style: "currency",
                                    currency: "IDR",
                                    minimumFractionDigits: 0,
                                }
                            );
                            return `${formattedTarif}`;
                        },
                    },
                    {
                        data: 'ket'
                    },
                    {
                        data: 'penyetor'
                    },
                ],
                order: [
                    [1, 'dsc']
                ]
            });
        }

        function editPendLain(data) {
            let id = $(data).data('id');
            let tanggal = $(data).data('tanggal');
            let pendapatan = $(data).data('pendapatan');
            let setoran = $(data).data('setoran');
            let asal_pendapatan = $(data).data('asal_pendapatan');
            let penyetor = $(data).data('penyetor');
            let nosbs = $(data).data('nosbs');

            $('#id').val(id);
            $('#tanggal').val(tanggal);
            $('#pendapatan').val(pendapatan);
            $('#setoran').val(setoran);
            $('#asal_pendapatan').val(asal_pendapatan);
            $('#penyetor').val(penyetor);
            $('#penyetor').trigger('change');
            $('#noSbs').val(nosbs);
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
            let pendapatan = parseNumber($('#pendapatan').val())
            let setoran = parseNumber($('#setoran').val())
            let asal_pendapatan = $('#asal_pendapatan').val();
            let penyetor = $('#penyetor').val();
            let noSbs = $('#noSbs').val();
            if (tanggal == "" || pendapatan == "" || asal_pendapatan == "" || penyetor == "") {
                let msg = "Berikut adalah data yang belum diisi: ";

                // Cek masing-masing input dan tambahkan ke pesan jika kosong
                if (tanggal == "") msg += "Tanggal, ";
                if (pendapatan == "") msg += "Pendapatan, ";
                if (setoran == "") msg += "Setoran, ";
                if (asal_pendapatan == "") msg += "Asal Pendapatan, ";
                if (penyetor == "") msg += "Penyetor, ";

                // Hapus koma terakhir dan tampilkan pesan kesalahan
                msg = msg.slice(0, -2); // Hapus koma dan spasi terakhir
                tampilkanEror(msg); // Menampilkan pesan error

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
                    console.log("🚀 ~ simpanPendLain ~ response:", response)
                    if (response.status == 'success') {
                        tampilkanSuccess(response.message);

                        dataPendLain = response.data
                        drawTable(
                            dataPendLain
                        );
                        resetForm('lainnya');
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

        async function getDataPendLain() {
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

        async function getDataPenutupanKas(draw) {
            let tahun = "";
            let bulan = "";
            let bln = ""
            if (draw == true) {
                tahun = $('#tahunTutup').val();
                bulan = String($('#bulanTutup').val()).padStart(2, '0');
                if (tahun == "" || bulan == "") {
                    console.error('Tahun dan bulan tidak valid atau kosong');
                    tampilkanEror('Harap isi tahun dan bulan terlebih dahulu!');
                    return;
                }
            } else {
                const tanggal = $('#tanggal_sekarang').val();
                tahun = new Date(tanggal).getFullYear();
                bln = new Date(tanggal).getMonth() + 1;
                bulan = String(bln).padStart(2, '0');
            }

            if (!tahun || !bulan) {
                console.error('Tahun dan bulan tidak valid atau kosong');
                tampilkanEror('Harap isi tahun dan bulan terlebih dahulu!');
                return;
            }

            // Tampilkan indikator loading
            tampilkanLoading();

            try {
                // Fetch data dari API post
                const response = await fetch('/api/kasir/penutupanKas', {
                    method: 'POST', // Metode POST
                    headers: {
                        'Content-Type': 'application/json', // Konten berupa JSON
                    },
                    body: JSON.stringify({
                        bulan,
                        tahun

                    }), // Parameter bulan dikirim dalam body
                });


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
                if (draw == true) {
                    const dataPenutupanKas = data.data
                    drawTabelPenutupanKas(dataPenutupanKas);
                } else {
                    insertPenutupan(data);
                }

            } catch (error) {
                // Tangani error jika ada
                console.error('Error fetching data:', error);
                tampilkanEror('Error fetching data:', error);
            } finally {
                // Sembunyikan indikator loading, baik berhasil maupun gagal
                swal.close()
            }
        }

        async function simpanPenutupanKas() {
            try {
                // Ambil elemen form
                const form = document.getElementById("form_input_tutup_kas");

                // Ambil data form sebagai FormData
                const formData = new FormData(form);
                console.log("🚀 ~ simpanPenutupanKas ~ formData:", formData);
                tampilkanLoading();

                // Kirim data ke API menggunakan $.ajax
                $.ajax({
                    url: '/api/kasir/penutupanKas/simpan', // Gunakan relative URL
                    type: 'POST',
                    data: formData,
                    processData: false, // Jangan proses data menjadi query string
                    contentType: false, // Biarkan jQuery mengatur konten type secara otomatis
                    success: function(result) {
                        // Tampilkan pesan sukses
                        if (result.status === 'success') {
                            tampilkanSuccess(result.message);
                        } else {
                            tampilkanEror(result.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        // Tangani kesalahan
                        console.error("Error:", error);
                        tampilkanEror(`Terjadi kesalahan saat menyimpan data: ${error}`);
                    }
                });
            } catch (error) {
                // Tangani kesalahan jika terjadi di luar $.ajax
                console.error("Error:", error);
                tampilkanEror(`Terjadi kesalahan saat menyimpan data: ${error}`);
            }
        }

        async function deletePenutupanKas(id) {
            const tahun = $('#tahunTutup').val();
            try {
                // Kirim permintaan DELETE ke API menggunakan $.ajax
                $.ajax({
                    url: `/api/kasir/penutupanKas/delete`, // Gunakan relative URL
                    type: 'DELETE',
                    data: {
                        id,
                        tahun
                    },
                    success: function(result) {
                        // Tampilkan pesan sukses
                        if (result.status === 'success') {
                            tampilkanSuccess(result.message);
                            const data = result.data
                            drawTabelPenutupanKas(data);
                        } else {
                            tampilkanEror(result.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        // Tangani kesalahan
                        console.error("Error:", error);
                        tampilkanEror(`Terjadi kesalahan saat menghapus data: ${error}`);
                    }
                });
            } catch (error) {
                // Tangani kesalahan jika terjadi di luar $.ajax
                console.error("Error:", error);
                tampilkanEror(`Terjadi kesalahan saat menghapus data: ${error}`);
            }
        }

        function editPenutupanKas(button) {
            // Get the row data
            const rowData = $('#dataPenutupanKas').DataTable().row($(button).parents('tr')).data();
            console.log("Editing data:", rowData);
            console.log("Editing data:", rowData.id);

            // Populate the form fields with the selected row's data
            $('#idTutup').val(rowData.id);
            $('#tanggal_sekarang').val(rowData.tanggal_sekarang);
            $('#tanggal_lalu').val(rowData.tanggal_lalu);
            $('#petugas').val(rowData.petugas);
            $('#total_penerimaan').val(rowData.total_penerimaan);
            $('#total_pengeluaran').val(rowData.total_pengeluaran);
            $('#saldo_bku').val(rowData.saldo_bku);
            $('#saldo_kas').val(rowData.saldo_kas);
            $('#selisih_saldo').val(rowData.selisih_saldo);

            // Populate denomination fields (assuming these are also part of rowData)
            $('#kertas100k').val(rowData.kertas100k || 0);
            $('#kertas50k').val(rowData.kertas50k || 0);
            $('#kertas20k').val(rowData.kertas20k || 0);
            $('#kertas10k').val(rowData.kertas10k || 0);
            $('#kertas5k').val(rowData.kertas5k || 0);
            $('#kertas2k').val(rowData.kertas2k || 0);
            $('#kertas1k').val(rowData.kertas1k || 0);
            $('#logam1k').val(rowData.logam1k || 0);
            $('#logam500').val(rowData.logam500 || 0);
            $('#logam200').val(rowData.logam200 || 0);
            $('#logam100').val(rowData.logam100 || 0);

            // Show the form (if hidden)
            $('#form_input_tutup_kas').show();

            // You can also trigger a custom action when opening the form, if needed.
        }


        function drawTabelPenutupanKas(data) {
            console.log("🚀 ~ drawTable ~ data:", data);

            // Destroy the existing DataTable if it already exists
            if ($.fn.DataTable.isDataTable('#dataPenutupanKas')) {
                $('#dataPenutupanKas').DataTable().destroy();
            }

            // Process data to add necessary fields like 'no', 'aksi', and 'ket'
            data.forEach(function(item, index) {
                item.no = index + 1; // Numbering row
                item.aksi = `<a type="button" class="btn btn-warning my-1edit " onclick="editPenutupanKas(this)"><i class="fas fa-pen-to-square"></i></a>
                            <a type="button" class="btn btn-danger my-1 delete" onclick="deletePenutupanKas(${item.id})"><i class="fas fa-trash"></i></a>
                            `;
            });

            // Initialize the DataTable
            $('#dataPenutupanKas').DataTable({
                data: data,
                columns: [{
                        data: 'aksi',
                        title: 'Aksi',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'no',
                        title: 'No'
                    },
                    {
                        data: 'tanggal_sekarang',
                        title: 'Tanggal KAS Sekarang'
                    },
                    {
                        data: 'tanggal_lalu',
                        title: 'Tanggal KAS Lalu'
                    },
                    {
                        data: 'petugas',
                        title: 'Petugas'
                    },
                    {
                        data: 'total_penerimaan',
                        title: 'Penerimaan'
                    },
                    {
                        data: 'total_pengeluaran',
                        title: 'Pengeluaran'
                    },
                    {
                        data: 'saldo_bku',
                        title: 'Saldo BKU'
                    },
                    {
                        data: 'saldo_kas',
                        title: 'Saldo Kas'
                    },
                    {
                        data: 'selisih_saldo',
                        title: 'Selisih'
                    },
                    {
                        data: 'kertas100k',
                        title: 'Kertas 100.000'
                    },
                    {
                        data: 'kertas50k',
                        title: 'Kertas 50.000'
                    },
                    {
                        data: 'kertas20k',
                        title: 'Kertas 20.000'
                    },
                    {
                        data: 'kertas10k',
                        title: 'Kertas 10.000'
                    },
                    {
                        data: 'kertas5k',
                        title: 'Kertas 5.000'
                    },
                    {
                        data: 'kertas2k',
                        title: 'Kertas 2.000'
                    },
                    {
                        data: 'kertas1k',
                        title: 'Kertas 1.000'
                    },
                    {
                        data: 'logam1k',
                        title: 'Logam 1.000'
                    },
                    {
                        data: 'logam500',
                        title: 'Logam 500'
                    },
                    {
                        data: 'logam200',
                        title: 'Logam 200'
                    },
                    {
                        data: 'logam100',
                        title: 'Logam 100'
                    },

                ],
                order: [
                    [0, 'asc']
                ],
                autowidth: false,
            });
        }
    </script>
@endsection
