<table id="tabelDaftarSisaCuti" class="table table-bordered table-striped">
    <thead class="bg-warning">
        <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Sisa Cuti {{ date('Y') }}</th>
            <th>Sisa Cuti {{ date('Y') - 1 }}</th>
            <th>Sisa Cuti {{ date('Y') - 2 }}</th>
            <th>Tambahan Cuti</th>
            <th>Permohonan Cuti</th>
            <th>Cuti Disetujui</th>
            <th>Cuti Ditolak</th>
            <th>Sisa Cuti</th>
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
                <td>{{ $cuti->sisaCuti }} hari</td>
                <td>{{ $cuti->sisaCuti_1 }} hari</td>
                <td>{{ $cuti->sisaCuti_2 }} hari</td>
                <td>{{ $cuti->jumlahCutiTambahan }} hari</td>
                <td>{{ $cuti->jumlahCutiDiambil }} hari</td>
                <td>{{ $cuti->jumlahCutiDisetujui }} hari</td>
                <td>{{ $cuti->jumlahCutiDitolak }} hari</td>
                <td>{{ $cuti->jumlahSisaCuti }} hari</td>
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
        console.log("🚀 ~ editSisaCuti ~ sisaCuti_2:", sisaCuti_2)
        console.log("🚀 ~ editSisaCuti ~ sisaCuti_1:", sisaCuti_1)
        console.log("🚀 ~ editSisaCuti ~ sisaCuti:", sisaCuti)
        console.log("🚀 ~ editSisaCuti ~ nip:", nip)
        const thn = "{{ date('Y') }}";
        const thn1 = "{{ date('Y') - 1 }}";
        const thn2 = "{{ date('Y') - 2 }}";
        Swal.fire({
            title: "Edit Sisa Cuti",
            html: `
            <h4 calss="swal2-title">${nama}</h4>
            <label for="jatah_cuti" class="swal2-label">Masukkan jumlah sisa cuti ${thn}:</label>
            <input id="jatah_cuti" type="number" class="swal2-input" placeholder="Contoh: 50000" value="${sisaCuti}">
            <label for="sisa_1" class="swal2-label">Masukkan jumlah sisa cuti ${thn1}:</label>
            <input id="sisa_1" type="number" class="swal2-input" placeholder="Contoh: 50000" value="${sisaCuti_1}">
            <label for="sisa_2" class="swal2-label">Masukkan jumlah sisa cuti ${thn2}:</label>
            <input id="sisa_2" type="number" class="swal2-input" placeholder="Contoh: 50000" value="${sisaCuti_2}">
            <input id="nip" readonly hidden type="number" class="swal2-input" placeholder="Contoh: 50000" value="${nip}">
        `,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Simpan",
            cancelButtonText: "Batal",
            preConfirm: () => {
                const jatah_cuti = document.getElementById("jatah_cuti").value;
                const sisa_1 = document.getElementById("sisa_1").value;
                const sisa_2 = document.getElementById("sisa_2").value;
                const nip = document.getElementById("nip").value;

                if (!jatah_cuti || !sisa_1 || !sisa_2 || !nip) {
                    Swal.showValidationMessage(
                        "Jumlah sisa cuti wajib diisi!"
                    );
                }

                return {
                    jatah_cuti,
                    sisa_1,
                    sisa_2,
                    nip
                }
            },
        }).then((result) => {
            return;
            if (result.isConfirmed) {
                const jatah_cuti = result.value.jatah_cuti;
                const sisa_1 = result.value.sisa_1;
                const sisa_2 = result.value.sisa_2;
                const nip = result.value.nip;

                // Kirim data melalui AJAX
                $.ajax({
                    url: "/tu/cuti/sisa/edit" + nip,
                    type: "post",
                    data: {
                        jatah_cuti: jatah_cuti,
                        sisa_1: sisa_1,
                        sisa_2: sisa_2
                    },
                    success: function(response) {
                        // console.log("🚀 ~ setorkan ~ response:", response);
                        if (response.status == "success") {
                            tampilkanSukses(response.message);
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
</script>
