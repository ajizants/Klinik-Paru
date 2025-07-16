<table id="tabelDaftarSisaCuti" class="table table-bordered table-striped">
    <thead class="bg-warning">
        <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Total Sisa Cuti</th>
            @if (in_array(Auth::user()->role, ['admin', 'tu']))
                <th>Sisa Cuti {{ date('Y') }}</th>
                <th>Sisa Cuti {{ date('Y') - 1 }}</th>
                <th>Sisa Cuti {{ date('Y') - 2 }}</th>
            @endif
            <th>Tambahan Cuti</th>
            <th>Permohonan Cuti</th>
            <th>Cuti Disetujui</th>
            <th>Cuti Ditolak</th>
        </tr>
    </thead>
    <tbody>
        @forelse($dataSisaCutiAll as $index => $cuti)
            <tr>
                <td>
                    @if (in_array(Auth::user()->role, ['admin', 'tu']))
                        <button type="button" class="col btn btn-info btn-sm" data-toggle="modal" data-target="#modal-form"
                            onclick="editPegawai('{{ $cuti->nip }}', '{{ addslashes($cuti->nama) }}');toggleSections('#tab_1');">
                            Update Data Pegawai</button>
                        <button class="mt-2 col btn btn-success btn-sm"
                            onclick="editSisaCuti('{{ $cuti->nip }}', '{{ addslashes($cuti->nama) }}', {{ $cuti->sisaCuti }}, {{ $cuti->sisaCuti_1 }}, {{ $cuti->sisaCuti_2 }})">Edit
                            Sisa Cuti</button><br>
                    @else
                        {{ $index + 1 }}
                    @endif
                </td>
                <td>{{ $cuti->nama ?? '-' }}<br>Nip. {{ $cuti->nip }}</td>
                <td>{{ $cuti->jumlahSisaCuti }} hari</td>
                @if (in_array(Auth::user()->role, ['admin', 'tu']))
                    <td>{{ $cuti->jatah_cuti }} hari</td>
                    <td>{{ $cuti->sisa_1 }} hari</td>
                    <td>{{ $cuti->sisa_2 }} hari</td>
                @endif
                {{-- <td>{{ $cuti->sisaCuti }} hari</td>
                <td>{{ $cuti->sisaCuti_1 }} hari</td>
                <td>{{ $cuti->sisaCuti_2 }} hari</td> --}}
                <td>{{ $cuti->jumlahCutiTambahan }} hari</td>
                <td>{{ $cuti->jumlahCutiDiambil }} hari</td>
                <td>{{ $cuti->jumlahCutiDisetujui }} hari</td>
                <td>{{ $cuti->jumlahCutiDitolak }} hari</td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center">Tidak ada data.</td>
            </tr>
        @endforelse
    </tbody>
</table>


<script>
    function editSisaCuti(nip, nama, sisaCuti, sisaCuti_1, sisaCuti_2) {
        const thn = new Date().getFullYear();
        const thn1 = thn - 1;
        const thn2 = thn - 2;

        Swal.fire({
            title: "Edit Sisa Cuti",
            html: `
            <h4 class="swal2-title">${nama}</h4>
            <label for="jatah_cuti" class="swal2-label">Sisa cuti ${thn}:</label>
            <input id="jatah_cuti" type="number" class="swal2-input" value="${sisaCuti}" min="0">
            <label for="sisa_1" class="swal2-label">Sisa cuti ${thn1}:</label>
            <input id="sisa_1" type="number" class="swal2-input" value="${sisaCuti_1}" min="0">
            <label for="sisa_2" class="swal2-label">Sisa cuti ${thn2}:</label>
            <input id="sisa_2" type="number" class="swal2-input" value="${sisaCuti_2}" min="0">
        `,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Simpan",
            cancelButtonText: "Batal",
            preConfirm: () => {
                const jatah_cuti = parseInt(document.getElementById("jatah_cuti").value) || 0;
                const sisa_1 = parseInt(document.getElementById("sisa_1").value) || 0;
                const sisa_2 = parseInt(document.getElementById("sisa_2").value) || 0;

                if (jatah_cuti < 0 || sisa_1 < 0 || sisa_2 < 0) {
                    Swal.showValidationMessage("Angka tidak boleh negatif");
                    return false;
                }

                return {
                    jatah_cuti,
                    sisa_1,
                    sisa_2
                };
            },
        }).then((result) => {
            if (result.isConfirmed) {
                const {
                    jatah_cuti,
                    sisa_1,
                    sisa_2
                } = result.value;

                $.ajax({
                    url: `/tu/cuti/sisa/edit/${nip}`,
                    type: "POST",
                    data: {
                        jatah_cuti: jatah_cuti,
                        jatah_cuti_1: sisa_1,
                        jatah_cuti_2: sisa_2,
                    },
                    success: function(response) {
                        if (response.status === "success") {
                            tampilkanSukses(response.message);
                            generateTabelPermohonanCuti(response.html);
                            generateTabelSisaCuti(response.sisaCutiAll);
                            tampilkanInfoCuti(response.sisaCuti);
                        } else {
                            tampilkanEror(response.message);
                        }
                    },
                    error: function(xhr) {
                        tampilkanEror("Terjadi kesalahan saat menyimpan.");
                        console.error(xhr);
                    },
                });
            }
        });
    }
</script>
