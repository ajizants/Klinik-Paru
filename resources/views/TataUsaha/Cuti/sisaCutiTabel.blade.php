<table id="tabelDaftarSisaCuti" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Jumlah Cuti</th>
            <th>Tambahan Cuti</th>
            <th>Cuti Diambil</th>
            <th>Sisa Cuti</th>
            {{-- <th>Update Per</th> --}}
        </tr>
    </thead>
    <tbody>
        @forelse($dataSisaCutiAll as $index => $cuti)
            <tr>
                <td>
                    @if (in_array(Auth::user()->role, ['admin', 'tu']))
                        <button class="btn btn-success btn-sm"
                            onclick="persetujuanCuti({{ $cuti->nip }}, 1)">Tambah</button>
                        <button class="btn btn-danger btn-sm"
                            onclick="persetujuanCuti({{ $cuti->nip }}, 2)">Kurangi</button>
                    @else
                        {{ $index + 1 }}
                    @endif
                </td>
                <td>{{ $cuti->nama ?? '-' }}<br>Nip. {{ $cuti->nip }}</td>
                <td>{{ $cuti->jatah_cuti }}</td>
                <td>{{ $cuti->tambahan_cuti }}</td>
                <td>{{ $cuti->jumalhCutiDiambil }}</td>
                <td>{{ $cuti->jumlahSisaCuti }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center">Tidak ada data.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<script>
    function tambahCuti(nip) {
        tampilkanLoading('Sedang Menambah Cuti...');
        $.ajax({
            url: "/tu/cuti/tambah/" + nip,
            type: "GET",
            success: function(data) {
                generateTabelPermohonanCuti(data.html);
                Swal.close();
            }
        })
    }
</script>
