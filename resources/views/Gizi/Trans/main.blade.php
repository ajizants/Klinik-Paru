{{-- @extends('layouts.layout') --}}
@extends('Template.lte')

@section('content')
    {{-- @include('Gizi.Trans.antrian') --}}

    @include('Gizi.Trans.input')




    @include('Template.script')

    <!-- my script -->
    <script src="{{ asset('js/template.js') }}"></script>
    <script src="{{ asset('js/populate.js') }}"></script>
    <script>
        let age;
        async function cariPasienGizi(norm, tgl, ruang) {

            norm = norm || formatNorm($("#norm").val);
            tgl = tgl || $("#tgltrans").val();
            var requestData = {
                norm: norm,
                tgl: tgl
            };

            Swal.fire({
                icon: "info",
                title: "Sedang mencarikan data pasien...!!!",
                showConfirmButton: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            });

            try {
                const response = await fetch("/api/gizi/asesmenAwal", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify(requestData),
                });

                if (!response.ok) {
                    if (response.status == 404) {
                        cariKominfo(norm, tgl, ruang);
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Terjadi kesalahan saat mengambil data pasien...!!!",
                        });
                        throw new Error("Network response was not ok");
                    }
                } else {
                    const hasil = await response.json();
                    console.log("🚀 ~ cariPasienGizi ~ hasil:", hasil)
                    const data = hasil.data
                    const kunjungan = hasil.data.kunjungan
                    console.log("🚀 ~ cariPasienGizi ~ kunjungan:", kunjungan)
                    console.log("🚀 ~ cariTsLab ~ data:", data);
                    $("#norm").val(data.norm);
                    $("#nama").val(data.nama);
                    $("#nik").val(data.nik);
                    $("#alamat").val(data.alamat);
                    $("#notrans").val(data.notrans);
                    $("#layanan").val(data.layanan);
                    $("#gender").val(data.jk);
                    $("#dokter").val(data.dokter).trigger("change");

                    isiAsesmen(data);
                    calculateAge();

                    tabelKunjungan(kunjungan);

                    Swal.close();

                    scrollToInputSection();
                }
            } catch (error) {
                console.error("Terjadi kesalahan saat mencari data:", error);
                Swal.fire({
                    icon: "error",
                    title: "Terjadi kesalahan saat mencari data...!!! /n" + error,
                });
            }
        }

        function isiAsesmen(data) {
            const fields = [
                "notrans", "tgltrans", "norm", "nama", "tglLahir", "alamat", "layanan",
                "dokter", "ahli_gizi", "frek_makan", "frek_selingan", "makanan_selingan",
                "alergi_makanan", "pantangan_makanan", "makanan_pokok", "lauk_hewani",
                "lauk_nabati", "sayuran", "buah", "minuman", "bb_awal", "bbi", "tb_awal",
                "lla", "imt_awal", "status_gizi", "td", "nadi", "rr", "suhu", "hasil_lab",
                "riwayat_diet_penyakit", "catatan", "dxMedis_awal", "dxGizi_awal",
                "etiologi_awal", "diit", "perinsip_diit", "energi", "protein", "lemak", "karbohidrat"
            ];

            // Handle specific fields for select2
            $('#dxMedis_awal').val(data.dxMedis_awal || []).trigger("change");
            $('#dxGizi_awal').val(data.dxGizi_awal || []).trigger("change");
            $('#status_gizi').val(data.status_gizi || []).trigger("change");

            // Handle 'keluhan_awal' separately
            if (data.keluhan) {
                try {
                    // Parse keluhan as JSON if it's a string
                    const keluhanArray = typeof data.keluhan === 'string' ?
                        JSON.parse(data.keluhan) :
                        data.keluhan || [];
                    console.log("🚀 ~ isiAsesmen ~ keluhanArray:", keluhanArray);
                    $('#keluhan_awal').val(keluhanArray).trigger("change");
                } catch (e) {
                    console.error("Error parsing keluhan data:", e);
                }
            }

            // Handle other fields
            fields.forEach(field => {
                const element = document.getElementById(field);
                if (element) {
                    if (element.type === "select" || element.classList.contains("select2Multi")) {
                        // Handle multi-select (Select2)
                        $(element).val(data[field] || []).trigger("change");
                    } else if (element.type === "text" || element.type === "number" || element.type === "date") {
                        // Handle standard input elements
                        element.value = data[field] || "";
                    } else if (element.type === "textarea") {
                        // Handle textarea elements if needed
                        element.value = data[field] || "";
                    }
                }
            });
        }

        function tabelKunjungan(kunjungan) {
            console.log("🚀 ~ tabelKunjungan ~ kunjungan:", kunjungan);
            kunjungan.forEach(function(item, index) {
                item.actions = `<a href="#" class="delete" data-id="${item.id}">
                            <i class="fas fa-trash"></i>
                        </a>`;
                item.no = index + 1; // Nomor urut dimulai dari 1, bukan 0
                item.dx = item.dx_gizi.sub_kelas + " berhubungan dengan " + item.etiologi
            });

            $("#tabel_kunjungan").DataTable({
                data: kunjungan,
                columns: [{
                        data: "actions"
                    },
                    {
                        data: "no"
                    },
                    {
                        data: 'created_at',
                        render: function(data, type, row) {
                            // Create a new Date object from the data
                            const date = new Date(data);

                            // Format the date as dd/mm/yyyy
                            const day = String(date.getDate()).padStart(2, '0');
                            const month = String(date.getMonth() + 1).padStart(2, '0');
                            const year = date.getFullYear();

                            return `${day}/${month}/${year}`;
                        }
                    },
                    {
                        data: "bb"
                    },
                    {
                        data: "tb"
                    },
                    {
                        data: "imt"
                    },
                    {
                        data: "parameter"
                    },
                    {
                        data: "dx"
                    },
                    {
                        data: "evaluasi"
                    }
                ],
                order: [2, "asc"],
                paging: true,
                pageLength: 5,
            });
        }






        function validasi(tombol) {
            const kunjungan = [
                "norm", "notrans", "dokter", "ahli_gizi", "bb", "tb", "imt", "keluhan",
                "parameter", "dxMedis", "dxGizi", "etiologi", "evaluasi"
            ];
            const asesmen = [
                "notrans", "tgltrans", "norm", "nama", "tglLahir", "alamat", "layanan",
                "dokter", "ahli_gizi", "frek_makan", "frek_selingan", "makanan_selingan",
                "alergi_makanan", "pantangan_makanan", "makanan_pokok", "lauk_hewani",
                "lauk_nabati", "sayuran", "buah", "minuman", "bb_awal", "bbi", "tb_awal",
                "lla", "imt_awal", "status_gizi", "keluhan_awal", "td", "nadi", "rr", "suhu", "hasil_lab",
                "riwayat_diet_penyakit", "catatan", "dxMedis_awal", "dxGizi_awal", "etiologi_awal", "diit",
                "perinsip_diit", "energi", "protein", "lemak", "karbohidrat"
            ];
            var inputsToValidate = tombol === "kunjungan" ? kunjungan : asesmen;

            var error = false;
            var firstErrorElement = null;

            inputsToValidate.forEach(function(inputId) {
                var inputElement = document.getElementById(inputId);

                if (inputElement) {
                    var inputValue = inputElement.value.trim();
                    if (inputValue === "" || (inputElement.classList.contains('select2-hidden-accessible') && !$(
                            inputElement).val().length)) {
                        if ($(inputElement).hasClass("select2-hidden-accessible")) {
                            $(inputElement).next(".select2-container").addClass("input-error");
                        } else {
                            inputElement.classList.add("input-error");
                        }
                        error = true;
                        if (!firstErrorElement) {
                            firstErrorElement = inputElement;
                        }
                    } else {
                        if ($(inputElement).hasClass("select2-hidden-accessible")) {
                            $(inputElement).next(".select2-container").removeClass("input-error");
                        } else {
                            inputElement.classList.remove("input-error");
                        }
                    }
                }
            });

            if (error) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Ada data yang masih kosong! Mohon lengkapi semua data.",
                }).then(() => {
                    if (firstErrorElement) {
                        firstErrorElement.focus();
                    }
                });
            } else {
                tombol === "asesment" ? simpanAsesment() : tombol === "kunjungan" ? simpanKunjungan() : null;
            }
        }



        function getFormValues(ids) {
            let values = {};
            ids.forEach(id => {
                values[id] = document.getElementById(id)?.value || '';
            });
            return values;
        }

        function simpanAsesment() {
            const ids = [
                'notrans', 'tgltrans', 'norm', 'nama', 'tglLahir', 'alamat', 'layanan',
                'dokter', 'ahli_gizi', 'frek_makan', 'frek_selingan', 'makanan_selingan',
                'alergi_makanan', 'pantangan_makanan', 'makanan_pokok', 'lauk_hewani',
                'lauk_nabati', 'sayuran', 'buah', 'minuman', 'bb_awal', 'bbi', 'tb_awal',
                'lla', 'imt_awal', 'status_gizi', 'td', 'nadi', 'rr', 'suhu', 'hasil_lab',
                'riwayat_diet_penyakit', 'catatan', 'dxMedis_awal', 'dxGizi_awal', 'etiologi_awal', 'diit',
                'perinsip_diit', 'energi', 'protein', 'lemak', 'karbohidrat'
            ];
            const values = getFormValues(ids);
            const selectedValues = $('.select2Multi').val();
            var keluhan = selectedValues.join(', ');
            var gender = $('#gender').val();

            values.keluhan = selectedValues;
            values.jk = gender;

            const data = JSON.stringify(values);
            console.log("🚀 ~ simpanKunjungan ~ data:", data)

            // Mengirim data menggunakan fetch
            fetch('/api/gizi/asesmenAwal/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').getAttribute('content')
                    },
                    body: data
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Success:', data);
                    Swal.fire({
                        icon: 'success',
                        title: 'Data Berhasil',
                        text: data.message,
                    })
                })
                .catch((error) => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan saat menyimpan data...!!!\n' + error,
                    });
                });
        }

        function simpanKunjungan() {
            const ids = [
                'norm', 'notrans', 'dokter', 'ahli_gizi', 'bb', 'tb', 'imt', 'keluhan',
                'parameter', 'dxMedis', 'dxGizi', 'etiologi', 'evaluasi'
            ];
            const values = getFormValues(ids);

            const data = JSON.stringify(values);
            console.log("🚀 ~ simpanKunjungan ~ data:", data)

            fetch('/api/gizi/kunjungan/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').getAttribute('content')
                    },
                    body: JSON.stringify(values)
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Success:', data);
                    Swal.fire({
                        icon: 'success',
                        title: 'Data Berhasil',
                        text: data.message,
                    })
                    tabelKunjungan(data.data);
                })
                .catch((error) => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan saat menyimpan data...!!!\n' + error,
                    });
                });
        }

        function reset() {
            document.getElementById("form_kunjungan").reset();
            document.getElementById("form_asesment").reset();
            document.getElementById("form_identitas").reset();
            $("#form_kunjungan select").trigger("change");
            $("#form_asesment select").trigger("change");
            $("#form_identitas select").trigger("change");
            if ($.fn.DataTable.isDataTable("#tabel_kunjungan")) {
                var table = $("#tabel_kunjungan").DataTable();
                table.clear().destroy();
            }
            setTodayDate();
            scrollToTop();
        }

        function calculateIMT(ket) {
            console.log("🚀 ~ calculateIMT ~ ket:", ket);

            let weightInput, heightInput, imtInput;

            if (ket === 'kunjungan') {
                weightInput = document.getElementById('bb');
                heightInput = document.getElementById('tb');
                imtInput = document.getElementById('imt');
            } else {
                weightInput = document.getElementById('bb_awal');
                heightInput = document.getElementById('tb_awal');
                imtInput = document.getElementById('imt_awal');
            }

            const weight = parseFloat(weightInput.value);
            const height = parseFloat(heightInput.value);

            if (!isNaN(weight) && !isNaN(height) && height > 0) {
                // Convert height from cm to meters
                const heightInMeters = height / 100;

                // Calculate IMT
                const imt = weight / (heightInMeters * heightInMeters);

                // Display IMT rounded to 2 decimal places
                imtInput.value = imt.toFixed(2);
            } else {
                // Clear IMT if input is invalid
                imtInput.value = '';
            }
        }

        function calculateAge() {
            // Ambil tanggal lahir dari input
            const birthDateInput = $('#tglLahir').val(); // Use .val() instead of .value
            const birthDate = new Date(birthDateInput);

            // Cek apakah input valid
            if (isNaN(birthDate.getTime())) { // Use getTime() to check if the date is valid
                alert('Silakan masukkan tanggal lahir yang valid.');
                return;
            }

            // Tanggal hari ini
            const today = new Date();

            // Hitung usia
            age = today.getFullYear() - birthDate.getFullYear();
            const monthDifference = today.getMonth() - birthDate.getMonth();

            // Koreksi usia jika belum ulang tahun tahun ini
            if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            console.log("🚀 ~ calculateAge ~ age--:", age)
            $('#age').val(age);
            // calculateNutrients(age);
        }

        function calculateNutrients(age) {
            // Ambil nilai dari input
            age = age || parseFloat(document.getElementById('age').value);
            const weight = parseFloat(document.getElementById('bb_awal').value);
            const height = parseFloat(document.getElementById('tb_awal').value);
            const gender = document.getElementById('gender').value;
            const activityFactor = parseFloat(document.getElementById('activity').value);

            // Cek apakah input valid
            if (isNaN(age) || isNaN(weight) || isNaN(height)) {
                // alert('Silakan isi semua kolom dengan benar.');
                console.log(
                    "🚀 ~ calculateNutrients ~ : Silakan isi semua kolom dengan benar."
                )
                return;
            }

            // Hitung BMR berdasarkan jenis kelamin
            let BMR;
            if (gender === 'male') {
                BMR = 88.362 + (13.397 * weight) + (4.799 * height) - (5.677 * age);
            } else {
                BMR = 447.593 + (9.247 * weight) + (3.098 * height) - (4.330 * age);
            }

            // Hitung kebutuhan kalori harian
            const dailyCalories = BMR * activityFactor;

            // Hitung kebutuhan protein, lemak, dan karbohidrat
            const protein = (dailyCalories * 0.15) / 4; // 15% dari kalori, 1 gram protein = 4 kalori
            const fat = (dailyCalories * 0.25) / 9; // 25% dari kalori, 1 gram lemak = 9 kalori
            const carbs = (dailyCalories * 0.60) / 4; // 60% dari kalori, 1 gram karbohidrat = 4 kalori
            console.log("🚀 ~ calculateNutrients :", carbs, fat, protein, dailyCalories)

            $('#energi').val(dailyCalories.toFixed(2));
            $('#protein').val(protein.toFixed(2));
            $('#lemak').val(fat.toFixed(2));
            $('#karbohidrat').val(carbs.toFixed(2));
        }

        document.getElementById('bb').addEventListener('input', function() {
            calculateIMT('kunjungan');
        });
        document.getElementById('tb').addEventListener('input', function() {
            calculateIMT('kunjungan');
        });

        document.getElementById('bb_awal').addEventListener('input', function() {
            calculateIMT('asesmen');
        });
        document.getElementById('tb_awal').addEventListener('input', function() {
            calculateIMT('asesmen');
        });


        $(document).ready(function() {
            setTodayDate();
            populateDokterOptions();
            $('.select2Multi').select2({
                placeholder: "Pilih Keluhan",
                tags: true,
            });

            $('#getValuesButton').on('click', function() {
                var selectedValues = $('.select2Multi').val();
            });

            $('#activity').change(function() {
                calculateNutrients();
            });

        });
    </script>


    <div class="modal fade" id="modal-xl">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Stok Obat Farmasi Kurang Dari 200</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="card-body">
                            <div class=" border border-black">
                                <div class="card-body card-body-hidden p-2">
                                    <table id="farmasiObat" class="table table-striped fs-6" style="width:100%"
                                        cellspacing="0">
                                        <thead class="table-secondary table-sm">
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Barang</th>
                                                <th>Pabrikan</th>
                                                <th>Sediaan</th>
                                                <th>Suplier</th>
                                                <th>Tgl ED</th>
                                                <th>Stok Akhir</th>
                                            </tr>
                                        </thead>
                                    </table>
                                    <div id="loadingSpinner" style="display: none;" class="text-center">
                                        <i class="fa fa-spinner fa-spin"></i> Sedang mencari data...
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    <div class="modal fade" id="riwayatModal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Riwayat Transaksi Farmasi</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="card-body">
                            <div class=" border border-black">
                                <div class="card-body card-body-hidden p-2">
                                    <table id="riwayat" class="table table-striped fs-6" style="width:100%"
                                        cellspacing="0">
                                        <thead class="table-secondary table-sm">
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal</th>
                                                <th>Data FArmasi</th>
                                                <th>Data Tindakan</th>
                                            </tr>
                                        </thead>
                                    </table>
                                    <div id="loadingSpinner" style="display: none;" class="text-center">
                                        <i class="fa fa-spinner fa-spin"></i> Sedang mencari data...
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
@endsection
