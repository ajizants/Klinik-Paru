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
    </script>
@endsection
