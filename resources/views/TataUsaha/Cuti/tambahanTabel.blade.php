<table id="tabelDaftarTambahanCuti" class="table table-bordered table-striped">
    <thead class="bg-info">
        <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Jumlah Tambahan</th>
            <th>Tgl Dibuat</th>
            <th>Tgl Update</th>
        </tr>
    </thead>
    <tbody>
        @forelse($dataTambahanCuti as $index => $cuti)
            <tr>
                <td>
                    <a type="button" class="btn btn-info"
                        onclick="editTambahanCuti({{ $cuti->id }}, '{{ addslashes($cuti->pegawai->nama) }}', {{ $cuti->jumlah_tambahan }})">
                        edit
                    </a>

                    <a type="button" class="btn btn-danger" onclick="hapusTambahanCuti({{ $cuti->id }})">
                        hapus
                    </a>
                </td>
                <td>{{ $cuti->pegawai->nama ?? '-' }}<br>Nip. {{ $cuti->nip }}</td>
                <td>{{ $cuti->jumlah_tambahan }} hari</td>
                <td>{{ $cuti->created_at->format('d-m-Y') }}</td>
                <td>{{ $cuti->updated_at->format('d-m-Y') }}</td>
            </tr>
        @empty
        @endforelse
    </tbody>
</table>



<script>
    function editTambahanCuti(id, nama, jumlah) {
        Swal.fire({
            title: "Edit Tambahan Cuti",
            html: `
            <h4 calss="swal2-title">${nama}</h4>
            <label for="jumlah_tambahan" class="swal2-label">Masukkan jumlah tambahan cuti:</label>
            <input id="jumlah_tambahan" type="number" class="swal2-input" placeholder="Contoh: 50000" value="${jumlah}">
            <input id="id_cuti_tambahan" readonly hidden type="number" class="swal2-input" placeholder="Contoh: 50000" value="${id}">
        `,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya",
            cancelButtonText: "Tidak",
            preConfirm: () => {
                const id = document.getElementById("id_cuti_tambahan").value;
                const jumlah_tambahan = document.getElementById("jumlah_tambahan").value;

                if (!id || !jumlah_tambahan) {
                    Swal.showValidationMessage(
                        "Jumlah tambahan cuti wajib diisi!"
                    );
                }

                return {
                    id,
                    jumlah_tambahan,
                };
            },
        }).then((result) => {
            if (result.isConfirmed) {
                // console.log("Setoran:", result.value.setoran);
                // console.log("Tanggal Setor:", result.value.tanggalSetor);

                const id = result.value.id;
                const jumlah_tambahan = result.value.jumlah_tambahan;

                // Kirim data melalui AJAX
                $.ajax({
                    url: "/tu/cuti/tambahan/edit/" + id,
                    type: "post",
                    data: {
                        jumlah_tambahan: jumlah_tambahan,
                    },
                    success: function(response) {
                        // console.log("🚀 ~ setorkan ~ response:", response);
                        if (response.status == "success") {
                            tampilkanSukses(response.message);
                            generateTabelTambahanCuti(response.cutiTambahan);
                            generateTabelSisaCuti(response.sisaCutiAll);
                        } else {
                            tampilkanEror(response.message);
                        }
                    },
                    error: function(xhr) {
                        console.log("🚀 ~ setorkan ~ xhr:", xhr);
                        tampilkanEror(xhr);
                    },
                });
            }
        });
    }

    function hapusTambahanCuti(id) {
        Swal.fire({
            title: "Hapus Cuti Tambahan",
            text: "Anda yakin ingin menghapus cuti tambahan ini?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya",
            cancelButtonText: "Tidak",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/tu/cuti/tambahan/delete/" + id,
                    type: "POST",
                    success: function(response) {
                        if (response.status == "success") {
                            tampilkanSukses(response.message);
                            generateTabelTambahanCuti(response.cutiTambahan);
                            generateTabelSisaCuti(response.sisaCutiAll);
                        } else {
                            tampilkanError(response.message);
                        }
                    },
                    error: function(xhr) {
                        console.log("🚀 ~ setorkan ~ xhr:", xhr);
                        tampilkanError(xhr);
                    },
                });
            }
        });
    }
</script>
